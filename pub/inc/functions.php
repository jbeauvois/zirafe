<?php

/*
 * This file is part of Zirafe.
 *
 * Zirafe is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Zirafe is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Zirafe. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * transforms a php.ini string representing a value in an integer
 * @param $value the value from php.ini
 * @returns an integer for this value
 */
function zirafe_ini_to_bytes($value)
{
	$modifier = substr($value, -1);
	$bytes = substr($value, 0, -1);

	switch(strtoupper($modifier))
	{
		case 'P':
			$bytes *= 1024;
		case 'T':
			$bytes *= 1024;
		case 'G':
			$bytes *= 1024;
		case 'M':
			$bytes *= 1024;
		case 'K':
			$bytes *= 1024;
		default:
			break;
	}
	return $bytes;
}

/**
 * gets the maximum upload size according to php.ini
 * @returns the maximum upload size
 */
function zirafe_get_max_upload_size()
{
	return min(zirafe_ini_to_bytes(ini_get('post_max_size')), zirafe_ini_to_bytes(ini_get('upload_max_filesize')));
}

/**
 * detects if a given filename is present in a directory and find an alternate filename
 * @param $name the initial filename
 * @param $dir the directory to explore (finishing with a '/')
 * @returns an alternate filename, possibly the initial filename
 */
function zirafe_detect_collision($name, $dir)
{
	if(!file_exists($dir . $name))
	{
		return $name;
	}

	$dot = strpos($name, '.');
	$dot = ($dot === false) ? strlen($name) : $dot;
	$first = substr($name, 0, $dot);
	$second = substr($name, $dot);
	$i = 1;
	do
	{
		$new_name = $first . '-' . $i . $second;
		$i++;
	}
	while(file_exists($dir . $new_name));

	return $new_name;
}

/**
 * gets a string explaining the error
 * @param $code the error code
 * @returns a string explaining the error
 */
function zirafe_upload_errstr($code)
{
	switch($code)
	{
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			return _('Le fichier que vous tentez d\'envoyer excède 42MB.');
			break;

		case UPLOAD_ERR_PARTIAL:
		case UPLOAD_ERR_NO_FILE:
			return _('Aucun fichier n\'a été envoyé sur le serveur. Merci de reéssayer.');
			break;

		case UPLOAD_ERR_NO_TMP_DIR:
		case UPLOAD_ERR_CANT_WRITE:
		case UPLOAD_ERR_EXTENSION:
			return _('Erreur interne du serveur.');
			break;

		default:
			break;
	}
	return _('Erreur inconnue.');
}

/**
 * handles an uploaded file
 * @param $file the file struct given by $_FILE[]
 * @param $one_time_download is the file a one time download ?
 * @param $key if not empty, protect the file with this key
 * @param $time the time of validity of the file
 * @param $cfg the current configuration
 * @returns an array containing some information
 * 'error' => information on possible errors
 * 'link' => the link name of the uploaded file
 */
function create_link($link='')
{
	$length = 8;
	$seed = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	for($x=1;$x<=$length;$x++)
	{
		$link .= $seed{rand(0,strlen($seed)-1)};
	}

	return($link);
}

function zirafe_upload($file, $one_time_download, $key, $time, $cfg)
{
	if(!empty($file['tmp_name']))
	{
		if(is_uploaded_file($file['tmp_name']))
		{

			/* array representing no error */
			$noerr = array('has_error' => false, 'why' => '');

			/* we check if this file is already here */
			$link_name = create_link($one_time_download ? '1' : '');

			if(file_exists(VAR_LINKS . $link_name))
			{
				return(array('error' => $noerr, 'link' => $link_name));
			}

			$mime_type = $file['type'];
			$send_file_name = $file['name'];
			$final_name = trim($file['name']);

			/* security reasons */
			if((strlen($final_name) >= 4) && (substr($final_name, -4) == '.php'))
			{
				$mime_type = 'application/x-httpd-php-source';
			}

			/* we check if there is a file with that name */
			$final_name = zirafe_detect_collision($final_name, VAR_FILES);

			/* we move it to the right place and create the link */
			if(move_uploaded_file($file['tmp_name'], VAR_FILES . $final_name))
			{
				$handle = fopen(VAR_LINKS . $link_name, 'w');
				fwrite($handle, $final_name . NL . $send_file_name . NL . $mime_type . NL . $file['size'] . NL . $key . NL . $time . NL);
				fclose($handle);

				return(array('error' => $noerr, 'link' => $link_name, 'file' => $send_file_name, 'final_name' => VAR_FILES.$final_name));
			}
		}
	}

	return(array('error' => array('has_error' => true, 'why' => zirafe_upload_errstr($file['error'])), 'link' => ''));
}

/**
 * tells if a mime-type is viewable in a browser
 * @param $mime the mime type
 * @returns a boolean telling if a mime type is viewable
 */
function zirafe_is_viewable($mime)
{
	if(!empty($mime))
	{
		// actually, verify if mime-type is an image or a text
		$viewable1 = array('image', 'text');
		$viewable2 = array('pdf', 'x-shockwave-flash');
		$decomposed = explode('/', $mime);
		$ok = in_array($decomposed[0], $viewable1) || in_array($decomposed[1], $viewable2);
		return $ok;
	}
	return false;
}


// Error handling functions.
//! Global array that contains all registered errors.
$error_list = array ();

/**
 * Adds an error to the list of errors.
 * @param $title the error's title
 * @param $description is a human-friendly description of the problem.
 */
function add_error ($title, $description)
{
	global $error_list;
	$error_list[] = '<p>' . $title . '<br />' . $description . '</p>';
}

/**
 * Informs whether any error has been registered yet.
 * @return true if there are errors.
 */
function has_error ()
{
	global $error_list;
	return !empty ($error_list);
}

/**
 * Displays all the errors.
 */
function show_errors ()
{
	if (has_error ())
	{
		global $error_list;
		echo '<div class="error">';
		foreach ($error_list as $error)
		{
			echo $error;
		}
		echo '</div>';
	}
}

/**
* Display human readable text from seconds.
*/
function secondsToHuman($seconds)
{
	$units = array(
		"semaine"	=> 7*24*3600,
		"jour"		=>   24*3600,
		"heure"		=>      3600,
		"minute"	=>        60,
		"seconde"	=>         1,
        );

	// 0 seconds is 0 seconds AHAH :D
	if ( $seconds == 0 ) { 
		return "0 seconds";
	}

	$string = "";

	foreach ($units as $name => $divisor) {
		if ($quot = intval($seconds / $divisor)) {
			$string .= "$quot $name";
                        $string .= (abs($quot) > 1 ? "s" : "") . ", ";
                        $seconds -= $quot * $divisor;
                }
        }

        return substr($string, 0, -2);
}

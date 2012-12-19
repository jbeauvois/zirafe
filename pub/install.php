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


/*
############################################
####	Set and load installer's needs
############################################
*/
// # define the Zirafe root directory
if(!defined('ZIRAFE_ROOT')) {
	define('ZIRAFE_ROOT', dirname(__FILE__)."/");
}

// # loads Zirafe functions
require_once ZIRAFE_ROOT."inc/functions.php";

// # availables times for retention file
$retention = array(
	'42i' => 2520, 
        '42h' => 151200, 
        '42d' => 3628800,
        '42w' => 25401600,
);

/*
############################################
####	Functions for installer
############################################
*/
// # is argument a valid FQDN ?
function isValidFQDN($fqdn) {
	return (!empty($fqdn) && preg_match('/(?=^.{1,254}$)(^(?:(?!\d+\.|-)[a-zA-Z0-9_\-]{1,63}(?<!-)\.?)+(?:[a-zA-Z]{2,})$)/i', $fqdn) > 0);
}

// # setUnixPath() adds / at the end of the path (if missing)
function setUnixPath($path) {
	if (substr($path, -1) != "/") {
		$path = $path ."/";
	}
	return $path;
}

// # isValidUnixPath() checks if the path is a valid Unix path
function isValidUnixPath($pathToCheck) {
	$pathToCheck = setUnixPath($pathToCheck);

	if (preg_match('/^\/.*/', $pathToCheck) > 0) {
		return 1;
	} else {
		return 0;
	}
}

// # setWindowsPath() adds \ at the end of the path (is missing)
function setWindowsPath($path) {
	if (substr($path, -1) != "\\") {
		$path = $path ."\\";
	}
	return $path;
}

// # isValidWinPath() checks if the path is a valid Windows path
// # TODO: enhance regex
function isValidWinPath($pathToCheck) {
	$pathToCheck = setWindowsPath($pathToCheck);	

	// path must not have < > : " / | ? *  chars except 3rd one chars
	$denyChars = array("<",">",":","\"","\/","|","?","*");
	foreach ($denyChars as $char) {
		if (strpos(substr($pathToCheck, 3), $char) != false) {
			return 0;
		}
	}

	// return (the regex must be better)
	if (strlen($pathToCheck) <= 259 && preg_match('/^[a-zA-Z]{1}:\\\\.*$/', $pathToCheck) > 0) {
		return 1;
	} else {
		return 0;
	}
}

// # setPort() checks if user has setted a port. if not, set port to default value in regards to the SSL checkbox
function setPort($port) {
	// default port
	if (!isset($_POST['port']) || $_POST['port'] === '') {
		if (!isset($_POST['ssl']) || $_POST['ssl'] === '') {
			$port = 80;
		} else {
			$port = 443;
		}
	// port setted by user
	} else {
		$port = $_POST['port'];
	}
	
	return $port;
}

// # isValidPort() checks if a port is between 1 AND 65536
function isValidPort($port) {
	if (($port > 0) AND ($port < 65536)) {
		return 1;
	} else {
		return 0;
	}
}

// # isValidRetention() checks if retention selected is part of $retention array
function isValidRetention($time) {
	global $retention;
	foreach ($retention as $retName => $retTime) {
		if ($retName != $time) {
			continue;
		} else {
			return 1;
		}
	}
	
	return 0;
}


// # printStep0() prints the form with informational messages like licence and prerequisites
// # TODO: licence
function printStep0() {
	echo '<h1>Installer : Licence agreement</h1>';
	
	echo 'Before continuing, you have to create a Zirafe directory. Your webserver must be the owner and must have the RW mode on it.<br />';

	echo '<form action="install.php" method="POST">';
		echo '<input type="hidden" name="install" value="step0" />';
		echo '<label for="gpl">Accept the licence : </label><input type="checkbox" name="gpl" id="gpl" /><br />';
		echo '<input type="submit" value="Install Zirafe" />';
	echo '</form>';
}

// # printStep1() prints the form to build the config.php configuration file for Zirafe
// # TODO: retrieve automatically information to fill defaults inputs fields
function printStep1() {
	global $retention;
	echo '<h1>Installer : Configuration</h1>';
	
	echo '<form action="install.php" method="POST">';
		echo '<input type="hidden" name="install" value="step1" />';
		echo '<label for="ip-dns">IP or DNS : </label><input type="text" id="ip-dns" name="ip-dns" /><br />';
		echo '<label for="port">Port : </label><input type="text" id="port" name="port" /><br />';
		echo '<label for="webroot">Webroot (keep / if none) : </label><input type="text" id="webroot" name="webroot" value="/" /><br />';
		echo '<label for="ssl">SSL : </label><input type="checkbox" id="ssl" name="ssl" /><br />';
		echo '<label for="directory">Working directory : </label><input type="text" id="directory" name="directory" value="/var/zirafe/" /><br />';
		echo '<label>Default file retention : </label><select id="default_ret" name="default_ret">';
			foreach ($retention as $expiration => $expiration_time)
			{
				if ($expiration == '42d') {
					echo '<option value="'.$expiration.'" selected="selected">'.date_to_human($expiration).'</option>';
				} else {
					echo '<option value="'.$expiration.'">'.date_to_human($expiration).'</option>';
				}
			}
		echo '</select><br />';
		echo '<input type="submit" value="Step 2 >" />';
	echo '</form>';
}

// # printStep2() prints summary of configuration
// # TODO: print retention into human readable text and write config.php
function printStep2() {
	echo '<h1>Installer : Configuration summary</h1>';

	// echo 'IP or DNS : '.$_POST['ip-dns'].'<br />';
	// echo 'Port : '.setPort($_POST['port']).'<br />';
	// echo 'Webroot : '.setUnixPath($_POST['webroot']).'<br />';
	echo 'Webroot : ';
	if (isset($_POST['ssl'])) {
		echo "https://".$_POST['ip-dns'].":".setPort($_POST['port']).setUnixPath($_POST['webroot'])."<br />";
	} else {
		echo "http://".$_POST['ip-dns'].":".setPort($_POST['port']).setUnixPath($_POST['webroot'])."<br />";
	}
	echo 'Zirafe directory : ';
	if (isValidUnixPath(setUnixPath($_POST['directory']))) {
		echo setUnixPath($_POST['directory']).'<br />';
	} else {
		echo setWindowsPath($_POST['directory']).'<br />';
	}
	echo 'Default retention : '.date_to_human($_POST['default_ret']).'<br />';
}

// # checkStep0() checks the Zirafe licence agreement
function checkStep0() {
	if(!isset($_POST['gpl'])) {
		return 0;
	} else {
		return 1;
	}
}

// # checkStep1() checks the configuration parameters setted like : ip/dns, port, webroot, Zirafe directory and default file retention
// # TODO: exists and writables Zirafe directory
function checkStep1() {
	$error = array();

	// check IP or DNS
	if(!(filter_var($_POST['ip-dns'], FILTER_VALIDATE_IP) || isValidFQDN($_POST['ip-dns']))) {
		$error['ip-dns'] = 1;
	}

	// check Port
	$port = setPort($_POST['port']);
	if(!isValidPort($port)) {
		$error['port'] = 1;
	}

	// check Webroot
	if (!isValidUnixPath(setUnixPath($_POST['webroot']))) {
		$error['webroot'] = 1;
	}

	// check Zirafe directory (exists and writables)
	if(empty($_POST['directory']) OR (!isValidWinPath(setWindowsPath($_POST['directory'])) AND !isValidUnixPath(setUnixPath($_POST['directory'])))) {
		$error['directory'] = 1;
	}

	// check retention time selected
	if(empty($_POST['default_ret']) OR (!isValidRetention($_POST['default_ret']))) {
		$error['default_ret'] = 1;
	}

	// return error(s)
	if(count($error) > 0) {
		echo print_r($error); // debug error message (tmp dev)
		return 0;
	} else {
		return 1;
	}
}

// # writeConfigFile() writes config.php file
function writeConfigFile() {
	// Get the path of the config file config.php
	if (isValidUnixPath(setUnixPath(ZIRAFE_ROOT))) {
		$file = ZIRAFE_ROOT."inc/config.php";
	} else {
		$file = ZIRAFE_ROOT."inc\\config.php";
	}

	// Get all config values
	$ip = $_POST['ip-dns'];
	$port = setPort($_POST['port']);
	if(isset($_POST['ssl'])) {
		$webroot = "https://".$ip.":".$port;
	} else {
		$webroot = "http://".$ip.":".$port;
	}
	$webroot .= setUnixPath($_POST['webroot']);
	if (isValidUnixPath(setUnixPath($_POST['directory']))) {
		$directory = setUnixPath($_POST['directory']);
	} else {
		$directory = setWindowsPath($_POST['directory']);
	}
	$default_ret = $_POST['default_ret']; 

	// Write config file and close it
	global $retention;
	$fh = fopen($file, 'w') or die ("Can't write configuration into inc/config.php. Please check rights");
	fwrite($fh, "<?php\n");
	fwrite($fh, "// This file was generated by Zirafe Installer (".date("Y-m-d").")\n");
	fwrite($fh, "\$cfg['web_root'] = '".$webroot."';\n");
	fwrite($fh, "\$cfg['var_root'] = '".$directory."';\n");
	fwrite($fh, "\$cfg['expiration_time_config'] = array(\n");
	foreach($retention as $retName => $retTime) {
		fwrite($fh, "\t'".$retName."',\n");
	}
	fwrite($fh, ");\n");
	fwrite($fh, "\$cfg['default_expiration_time_config'] = '".$default_ret."';\n");
	fclose($fh);

	return 1;
}

/*
############################################
####	Installer's Core
############################################
*/
// # Zirafe has already been installed (config.php exists)
if(file_exists(ZIRAFE_ROOT."inc/config.php")) {
	die('Zirafe sounds already installed. To run again the Zirafe installer, please remove inc/config.php.');
}
// # Zirafe isn't yet installed (config.php doesn't exist)
else {
	// TODO : fill inc/config.php + check directories + check php.ini (upload values)

	// STEP 0 : Display the prerequisites and the licence
	if(!isset($_POST['install'])) {
		printStep0();
	}
	// STEP 1 : Zirafe Configuration
	elseif ($_POST['install'] == 'step0' && checkStep0()) {
		printStep1();
	}
	// STEP 2 : zirafe directories
	elseif ($_POST['install'] == 'step1' && checkStep1()) {
		printStep2();
		if (writeConfigFile()) {
			echo "Zirafe is now installed and configured. Go home page";
		}
	}
	// Error detected in the last install step
	else {
		switch ($_POST['install']) {
			case 'step0':
				echo 'Error in step 0';
				printStep0();
				break;
			case 'step1':
				echo 'Error in step 1';
				printStep1();
				break;
		}
	}
}

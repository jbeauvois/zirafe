<?php

/*
 *  This file is part of Zirafe.
 *
 *  Zirafe is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Zirafe is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with Zirafe.  If not, see <http://www.gnu.org/licenses/>.
 */

define('ZIRAFE_ROOT', dirname(__FILE__) . '/');

require(ZIRAFE_ROOT . 'lib/config.php');
require(ZIRAFE_ROOT . 'lib/settings.php');
require(ZIRAFE_ROOT . 'lib/functions.php');	

if(!empty($_GET['h'])) {
  $link_name = $_GET['h'];

  $link_file = VAR_LINKS . $link_name;
  if(file_exists($link_file)) {
    $content = file($link_file);    
    $file_name = trim($content[0]);
    $send_file_name = trim($content[1]);
    $mime_type = trim($content[2]);
    $file_size = trim($content[3]);
    $key = trim($content[4], NL);
    $time = trim($content[5]);
    $ext = pathinfo(VAR_FILES . $file_name, PATHINFO_EXTENSION);
        
    if(!empty($_GET['ext'])) {
    if($_GET['ext'] != $ext) {
    
    header("HTTP/1.0 404 Not Found");
    require(ZIRAFE_ROOT . 'lib/template/header.php');
        echo '<div class="error"><p>' . _('Malheureusement, le lien sur lequel vous avez cliqué est incorret ou a expiré.') . '</p></div>';
    require(ZIRAFE_ROOT . 'lib/template/footer.php');
    exit;
    }
}
    
    
    if($time != ZIRAFE_INFINITY) {
      if(time() > $time) {
        unlink($link_file);
        $new_name = zirafe_detect_collision($file_name, VAR_TRASH);
        rename(VAR_FILES . $file_name, VAR_TRASH . $new_name);

	header("HTTP/1.0 404 Not Found");
        require(ZIRAFE_ROOT . 'lib/template/header.php');
        echo '<div class="error"><p>' . _('Malheureusement, le lien sur lequel vous avez cliqué est incorret ou a expiré.') . '</p></div>';
        require(ZIRAFE_ROOT . 'lib/template/footer.php');
        exit;

      }
    }

    if(!isset($_GET['text'])) {
    if(empty($_GET['f']) && empty($_GET['ext'])) {   
        header('HTTP/1.1 301 Moved Permanently', false, 301);
	header('Location: /'.$_GET['h'].'/'.rawurlencode($send_file_name));
        exit;
    }
    
    if(!empty($_GET['f'])) {
    if($_GET['f'] != $send_file_name) {
    
    header("HTTP/1.0 404 Not Found");
    require(ZIRAFE_ROOT . 'lib/template/header.php');
        echo '<div class="error"><p>' . _('Malheureusement, le lien sur lequel vous avez cliqué est incorret ou a expiré.') . '</p></div>';
    require(ZIRAFE_ROOT . 'lib/template/footer.php');
    exit;
    }
    }
    } 
    if(!empty($key)) {
      if(!isset($_POST['key'])) {
        require(ZIRAFE_ROOT . 'lib/template/header.php');
?>
<div id="upload">
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<input type="hidden" name="zirafe" value="<?php $_42 = rand(); echo md5($_42); ?>" />
  <div><span class="title"><?php echo _('Mot de passe'); ?></span></div>
  <table>
  <tr>
    <td><?php echo _('Entrez le mot de passe pour obtenir votre fichier :'); ?><br/><input style="width: 84%;" type="password" name="key" /></td>
  </tr>
  <tr>
    <td><input type="submit" value="<?php echo _('Valider'); ?>" /></td>
  </tr>
  </table>
</form>
</div>
<?php
        require(ZIRAFE_ROOT . 'lib/template/footer.php');
        exit;
      } else {
        if($key != $_POST['key']) {
          header("HTTP/1.0 403 Forbidden");

          require(ZIRAFE_ROOT . 'lib/template/header.php');
          echo '<div class="error"><p>Mot de passe invalide.<hr/><a href="">Essayez à nouveau</a></p></div>';
          require(ZIRAFE_ROOT . 'lib/template/footer.php');
          exit;
        }
      }
    }

    header('Content-Length: ' . $file_size);
    if(isset($_GET['text'])) {  $mime_type = "text/plain";}
    header('Content-Type: ' . $mime_type);
    if((!zirafe_is_viewable($mime_type) && !isset($_GET['view'])) || isset($_GET['force'])) {
    
    if(empty($_GET['ext'])) {
          header('Content-Disposition: attachment; filename="' . $send_file_name . '"');
	  } else {
	  header('Content-Disposition: attachment; filename="' . $_GET['h'].'.' .$_GET['ext']. '"');
	  }
    }
    readfile(VAR_FILES . $file_name);

    if(strlen($link_name) > 8) {
      unlink($link_file);
      $new_name = zirafe_detect_collision($file_name, VAR_TRASH);
      rename(VAR_FILES . $file_name, VAR_TRASH . $new_name);
    }
    exit;
  } else {
    header("HTTP/1.0 404 Not Found");

    require(ZIRAFE_ROOT . 'lib/template/header.php');
    echo '<div class="error"><p>' . _('Malheureusement, le lien sur lequel vous avez cliqué est incorrect ou a expiré.') . '</p></div>';
    require(ZIRAFE_ROOT . 'lib/template/footer.php');
    exit;
  }
} else {
    header("HTTP/1.0 404 Not Found");

    require(ZIRAFE_ROOT . 'lib/template/header.php');
    echo '<div class="error"><p>' . _('La page n\'existe pas !') . '</p></div>';
    require(ZIRAFE_ROOT . 'lib/template/footer.php');
    exit;
}

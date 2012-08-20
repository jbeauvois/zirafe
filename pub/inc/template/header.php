<?php
header("Vary: Accept");
if(stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml'))
{
	$content_type = 'application/xhtml+xml; charset=utf-8';
}
else
{
	$content_type = 'text/html; charset=utf-8';
}

header('Content-Type: ' . $content_type);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo _('La réponse ultime'); ?></title>
	<meta http-equiv="Content-Type" content="<?php echo $content_type; ?>" />
	<link href="<?php echo $cfg['web_root'] . 'style.css'; ?>" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="media/jquery.js"></script>
</head>
<body>

<div id="content">
	<h1><a href="<?php echo $cfg['web_root']; ?>"><img src="<?php echo $cfg['web_root'] . 'media/answer_to_life.png'; ?>" alt="answer to life"/></a></h1>
	<h2>Hébergement temporaire de fichier en un clic et sans captcha.</h2>

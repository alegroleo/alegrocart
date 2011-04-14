<?php

require('common.php');

// Include Config and Initialisation
//require('../config.php');
define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_NAME', '');
define('DIR_BASE', getbasepath());
define('HTTP_BASE', getbaseurl());
define('HTTPS_BASE', '');
require('../common.php');

$step=(isset($_REQUEST['step']))?$_REQUEST['step']:1;
if (filesize('../config.php') > 0) { $step=3; }

$errors = array();

$files=array('cache'.D_S, 'image'.D_S, 'image'.D_S.'cache'.D_S, 'image'.D_S.'flash'.D_S ,'download'.D_S);
foreach ($files as $file) {
	$file=DIR_BASE.$file;
	if (!file_exists($file)) { $errors[]="'$file' was not found! (ensure you have uploaded it)"; }
	elseif (!is_writable($file)) { 
		@chmod($file, 0755);
		if (!is_writable($file)){
			$errors[]="'$file' is not writable! (chmod a+w or chmod 777)";
		}
	}
}
$files=array('logs'.D_S, 'logs'.D_S.'error_log'.D_S, 'logs'.D_S.'google_log'.D_S);
foreach ($files as $file) {
	$file=DIR_BASE.$file;
	if (!file_exists($file)) { $errors[]="'$file' was not found! (ensure you have uploaded it)"; }
	elseif (!is_writable($file)) { 
		@chmod($file, 0750);
	if (!is_writable($file)){
			$errors[]="'$file' is not writable! (chmod a+w or chmod 777)";
		}
	}	
}
$files=array('config.php', '.htaccess');
foreach ($files as $file) {
	$file=DIR_BASE.$file;
	if (!file_exists($file)) { $errors[]="'$file' was not found! (ensure you have uploaded it)"; }
	elseif (!is_writable($file)) { 
		@chmod($file, 0666);
		if (!is_writable($file)){
			$errors[]="'$file' is not writable! (chmod a+w or chmod 666)";
		}
	}
}
	if (phpversion() < '5.0'){
		$errors[] = 'PHP 5.0 or greater is require for AlegroCart';
	}
	if (ini_get('session.auto_start')){
		$errors[] = 'You must disable session.auto_start in php.ini to use AlegroCart';
	}
	if (!extension_loaded('mysql')){
		$errors[] = 'MySql extension is required to run AlegroCart';
	}
	if (!extension_loaded('gd')){
		$errors[] = 'GD extension is required to run AlegroCart';
	}
	if (!ini_get('file_uploads')){
		$errors[] = 'File uploads is required to be enable to run AlegroCart';
	}
	if (!extension_loaded('zlib')){
		$errors[] = 'ZLIB must be loaded in php.ini for AlegroCart to work';
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Installation</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body>

		<div id="container">
			<div id="logo"></div>
<?php 
	if (!empty($errors)) { ?>
		<p class="a">The following errors occured:</p>
		<?php foreach ($errors as $error) {?>
		<div class="warning"><?php echo $error;?></div><br>
		<?php } ?>
		<p class="a">Please fix the above error(s), install halted!</p>
<?php
	} else {
		switch ($step) {
			case '1':
				require('step1.php');
				break;
			case '2':
				require('step2.php');
				break;
			case '3':
				require('step3.php');
				break;
		}
	}
?>
			<div class="center"><a href="http://www.alegrocart.com/">AlegroCart</a></div>
		</div>
		
	</body>
</html>

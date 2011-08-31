<?php

require('common.php');
require('language.php');

define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_NAME', '');
define('DIR_BASE', getbasepath());
define('HTTP_BASE', getbaseurl());
define('HTTPS_BASE', '');
define('UPLOAD', 'install/upload.txt');
require('../common.php');

$errors = array();

$language = new language;
$language->get_languages();
$language->check_default();

if ($language->error) {
$errors[]=$language->error;
}

$language->load($_POST['language']);
$languages=$language->langs;

$step=(isset($_REQUEST['step']))?$_REQUEST['step']:1;
if (filesize('../config.php') > 0) { $step=3; }

if (file_exists(DIR_BASE.UPLOAD)) {
$lines=array();
$lines = file(DIR_BASE.UPLOAD);
foreach ($lines as $line) {
$line=DIR_BASE.(substr(trim($line),2));
	if (!file_exists($line)) { $errors[]=$language->get('error_not_found',$line);}
}
} else {  
$errors[]= DIR_BASE.UPLOAD.$language->get('error_not_found'); 
}

$files=array('cache'.D_S, 'image'.D_S, 'image'.D_S.'cache'.D_S, 'image'.D_S.'flash'.D_S ,'download'.D_S);
foreach ($files as $file) {
	$file=DIR_BASE.$file;
	if (!is_writable($file)) { 
		@chmod($file, 0755);
		if (!is_writable($file)){
			$errors[]= $language->get('error_not_777',$file);
		}
	}
}
$files=array('logs'.D_S, 'logs'.D_S.'error_log'.D_S, 'logs'.D_S.'google_log'.D_S);
foreach ($files as $file) {
	$file=DIR_BASE.$file;
	if (!is_writable($file)) { 
		@chmod($file, 0750);
	if (!is_writable($file)){
			$errors[]=$language->get('error_not_666',$file);
		}
	}	
}
$files=array('config.php', '.htaccess');
foreach ($files as $file) {
	$file=DIR_BASE.$file;
	if (!is_writable($file)) { 
		@chmod($file, 0666);
		if (!is_writable($file)){
			$errors[]=$language->get('error_not_666',$file);
		}
	}
}
	if (phpversion() < '5.0'){
		$errors[] = $language->get('error_php');
	}
	if (ini_get('session.auto_start')){
		$errors[] = $language->get('error_session');
	}
	if (!extension_loaded('mysql')){
		$errors[] = $language->get('error_mysql');
	}
	if (!extension_loaded('gd')){
		$errors[] = $language->get('error_gd');
	}
	if (!ini_get('file_uploads')){
		$errors[] = $language->get('error_upload');
	}
	if (!extension_loaded('zlib')){
		$errors[] = $language->get('error_zlib');
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	  <title><?php echo $language->get('heading_title')?></title>
	  <link rel="stylesheet" type="text/css" href="styles/style.css">
	</head>

	<body>
	<div id="header">
	    
	    <div class="logo"></div>
	    <?php echo $language->get('heading_step'.$step)?>
	    <div class="language">
	    <?php foreach ($languages as $value) { ?>
	    <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST" enctype="multipart/form-data">
	    <div>
	    <input type="image" src="./image/<?php echo $value; ?>.png" alt="<?php echo $value; ?>">
	    <input type="hidden" name="language" value="<?php echo $value; ?>">
	    <input type="hidden" name="step" value="<?php echo $step; ?>">
	    <?php if (isset($_POST['db_host'])) { ?>
		  <input type="hidden" name="db_host" value="<?php echo $_POST['db_host']; ?>"><?php } ?>
	    <?php if (isset($_POST['db_user'])) { ?>
		  <input type="hidden" name="db_user" value="<?php echo $_POST['db_user']; ?>"><?php } ?>
	    <?php if (isset($_POST['db_name'])) { ?>
		  <input type="hidden" name="db_name" value="<?php echo $_POST['db_name']; ?>"><?php } ?>
	    <?php if (isset($_POST['db_pass'])) { ?>
		  <input type="hidden" name="db_pass" value="<?php echo $_POST['db_pass']; ?>"><?php } ?>
	    <?php if (isset($_POST['username'])) { ?>
		  <input type="hidden" name="username" value="<?php echo $_POST['username']; ?>"><?php } ?>
	    <?php if (isset($_POST['password'])) { ?>
		  <input type="hidden" name="password" value="<?php echo $_POST['password']; ?>"><?php } ?>
	    </div>
	    </form>
	    <?php } ?>
	    </div>	
	</div>
	
	<div id="container">
			
	<?php 
	if (!empty($errors)) { ?>
		<p class="b"><?php echo $language->get('error')?></p>
		<?php foreach ($errors as $error) {?>
		<div class="warning"><?php echo $error;?></div>
		<?php } ?>
		<p class="b"><?php echo $language->get('error_fix')?></p>
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
			
	</div>
	<div id="footer">
	    <ul>			
		<li><a href="http://www.alegrocart.com/"><?php echo $language->get('ac')?></a></li>
		<li><a href="http://forum.alegrocart.com/"><?php echo $language->get('acforum')?></a></li>
	    </ul>
	</div>

	</body>
</html>

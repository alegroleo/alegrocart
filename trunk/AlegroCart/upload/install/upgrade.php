<?php

// Installed?
if (filesize('../config.php') == 0) { header('Location: index.php'); exit; }

define('VALID_ACCESS', TRUE);
require('common.php');
require('language.php');
// Include Config and Common
require('../config.php');
if (!defined('DIR_BASE')) { define('DIR_BASE', getbasepath()); }
if (!defined('HTTP_BASE')) { define('HTTP_BASE', getbaseurl()); }
require('../common.php');

$errors = array();

$language = new language;
$language->get_languages();
$language->check_default();

if ($language->error) {
$errors[]=$language->error;
}

$language->load(isset($_POST['language']) ? $_POST['language'] : ($language->detect() ? $language->detect(): 'en'));
$languages=$language->langs;

$step=(isset($_REQUEST['step']))?$_REQUEST['step']:1;

$files=array('config.php');  //,'admin'.DIRECTORY_SEPARATOR.'config.php'  Not Required
foreach ($files as $file) {
	$file=DIR_BASE.$file;
	if (!file_exists($file)) { $errors[]=$language->get('error_not_found',$file); }
	elseif (!is_writable($file)) {
		@chmod($file, 0666);
		if (!is_writable($file)){
			$errors[]=$language->get('error_not_666',$file); 
		}
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $language->get('heading_title')?></title>
	<link rel="stylesheet" type="text/css" href="../image/install/style.css">
	<!--[if !IE 7]>
		  <style type="text/css">
			  #wrap {display:table;height:100%}
		  </style>
	  <![endif]-->
	</head>
	<body>
	<div id="wrap">
	<div id="header">
	    <div class="header_content">
	    <img src="../image/install/aclogo.png" alt="AlegroCart open source E-commerce"/> 
	    <div class="language">
	    <?php foreach ($languages as $value) { ?>
	    <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST" enctype="multipart/form-data">
	    <div>
	    <input type="image" src="../image/install/<?php echo $value; ?>.png" alt="<?php echo $value; ?>">
	    <input type="hidden" name="language" value="<?php echo $value; ?>">
		<input type="hidden" name="step" value="<?php echo $step; ?>">
		<?php if (isset($_POST['root_dirs'])) { ?>
		      <input type="hidden" name="root_dirs" value="<?php echo $_POST['root_dirs']; ?>"><?php } ?>
	    </div>
	    </form>
	    <?php } ?>
	    </div>
	</div>
	</div>
	<div id="header_bottom">
	  <div class="header_bottom_content">
	    <div class="header_text">
	      <?php echo $language->get('heading_title')?>
	    </div>
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
				require('upgrade_step1.php');
				break;
			case '2':
				require('upgrade_step2.php');
				break;
		}
	}
	?>

	</div><!--div/container-->
	</div>
		<div id="footer">
		<ul>
			<li><a href="http://www.alegrocart.com/"><?php echo $language->get('ac')?></a></li>
			<li><a href="http://forum.alegrocart.com/"><?php echo $language->get('acforum')?></a></li>
		</ul>
		</div>
	</body>
</html>

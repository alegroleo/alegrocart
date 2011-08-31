<?php

// Installed?
if (filesize('../config.php') == 0) { header('Location: index.php'); exit; }

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

$language->load($_POST['language']);
$languages=$language->langs;

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

if (!$link = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
	$errors[] = $language->get('error_dbconnect');
}
else {
	if (!@mysql_select_db(DB_NAME, $link)) {
		$errors[] = $language->get('error_dbperm');
	}
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
	    <?php echo $language->get('heading_title')?>
	    <div class="language">
	    <?php foreach ($languages as $value) { ?>
	    <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST" enctype="multipart/form-data">
	    <div>
	    <input type="image" src="./image/<?php echo $value; ?>.png" alt="<?php echo $value; ?>">
	    <input type="hidden" name="language" value="<?php echo $value; ?>">
	    <?php if (isset($_POST['db_host'])) { ?>
		  <input type="hidden" name="db_host" value="<?php echo $_POST['db_host']; ?>"><?php } ?>
	    <?php if (isset($_POST['db_user'])) { ?>
		  <input type="hidden" name="db_user" value="<?php echo $_POST['db_user']; ?>"><?php } ?>
	    <?php if (isset($_POST['db_name'])) { ?>
		  <input type="hidden" name="db_name" value="<?php echo $_POST['db_name']; ?>"><?php } ?>
	    <?php if (isset($_POST['db_pass'])) { ?>
		  <input type="hidden" name="db_pass" value="<?php echo $_POST['db_pass']; ?>"><?php } ?>
	    </div>
	    </form>
	    <?php } ?>
	    </div>	
	</div>	

	<div id="container">
		
		
	<div id="content">
	<?php
	if (empty($errors)) {
		//replace existing config with new one
		$newfile='default.config.php';
		$file='../config.php';
		$str=file_get_contents($newfile);
		if($handle = fopen($file, 'w')) {
			$reps=array(
				'DB_HOST' => DB_HOST,
				'DB_USER' => DB_USER,
				'DB_PASSWORD' => DB_PASSWORD,
				'DB_NAME' => DB_NAME,
				'DIR_BASE' => addslashes(DIR_BASE),
				'HTTP_BASE' => HTTP_BASE,
				'HTTPS_BASE' => HTTPS_SERVER
			);

			foreach ($reps as $key => $val) {
				$str=preg_replace("/($key', ')(.*?)(')/", '${1}'.addslashes($val).'$3', $str);
			}

			if(fwrite($handle, $str)) {
				echo "<p class=\"a\">".$language->get('success',$file)."</p>\n";
				fclose($handle);
			}
			else { $errors[]=$language->get('error_write',$file); }
		} 
		else { $errors[]="<b>$language->get('error_open',$file)"; }
		unset($str);

		mysql_query('set character set utf8', $link);
		mysql_query('set @@session.sql_mode="MYSQL40"', $link);

		//run sql
		$files='upgrade.sql';
		$files=explode(',',$files);
		foreach ($files as $file) {
			if (!$errors && file_exists($file)) {
				mysql_import_file($file,$link);
			} else {
				$errors[] = $language->get('error_sql',$file);
			}
		}
	}
	if (!empty($errors)) { //has to be a separate if
		?>
		<p class="b"><?php echo $language->get('error')?></p>
		<?php foreach ($errors as $error) {?>
		<div class="warning"><?php echo $error;?></div><br>
		<?php } ?>
		<p class="b"><?php echo $language->get('error_fix')?></p>
	<?php } else {
		$file = DIR_BASE.'config.php';
		@chmod($file, 0644);
	?>
		  <div class="warning"><?php echo $language->get('del_inst')?></div>
		  <?php if(is_writable($file)) { ?>
			<div class="warning"><?php echo $language->get('config')?></div>
		  <?php }?>
		  <p class="a"><?php echo $language->get('congrat_upg')?></p>
		  
	<?php } ?>
		</div><!--div/content-->

		<div id="buttons">
		<form>
		  <input type="button" value="<?php echo $language->get('shop')?>" class="button" onclick="location='<?php echo HTTP_CATALOG; ?>';">
		  <input type="button" value="<?php echo $language->get('admin')?>" class="button" onclick="location='<?php echo HTTP_ADMIN; ?>';">
		</form>
		</div>
		
	   </div><!--div/container-->
		 <div id="footer">
		    <ul>			
			<li><a href="http://www.alegrocart.com/"><?php echo $language->get('ac')?></a></li>
			<li><a href="http://forum.alegrocart.com/"><?php echo $language->get('acforum')?></a></li>
		    </ul>
	     </div>
 
	</body>
</html>
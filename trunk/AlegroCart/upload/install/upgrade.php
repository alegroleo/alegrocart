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
	</div>
	<div id="header_bottom">
	  <div class="header_bottom_content">
	    <div class="header_text">
	      <?php echo $language->get('heading_title')?>
	    </div>
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
				'DIR_BASE' => addslashes(DIR_BASE),
				'HTTP_BASE' => HTTP_BASE,
				'DB_HOST' => DB_HOST,
				'DB_USER' => DB_USER,
				'DB_PASSWORD' => DB_PASSWORD,
				'DB_NAME' => DB_NAME,
				'HTTPS_BASE' => HTTPS_SERVER,
				'PATH_ADMIN' => PATH_ADMIN
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
		  <?php if(is_writable($file)) { ?>
			<div class="warning"><?php echo $language->get('config')?></div>
		  <?php }?>
		  <p class="a"><?php echo $language->get('congrat_upg')?></p>
	<?php } ?>
		</div><!--div/content-->
		<div id="buttons">
		<div class="left">
		<a onclick="location='<?php echo HTTP_CATALOG; ?>';" >
		<img src="../image/install/Shopping_Cart.png" alt="<?php echo $language->get('shop')?>" title="<?php echo $language->get('shop')?>">
		</a>
		<p class="a"><?php echo HTTP_CATALOG; ?></p>
		</div>
		<div class="right">
		<a onclick="location='<?php echo HTTP_BASE.PATH_ADMIN; ?>';">
		<img src="../image/install/Admin.png" alt="<?php echo $language->get('admin')?>" title="<?php echo $language->get('admin')?>">
		</a>
		<p class="a"><?php echo HTTP_BASE.PATH_ADMIN; ?></p>
		</div>
		</div>
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
<?php
$dir = '..' . DIRECTORY_SEPARATOR. 'install';
getFiles($dir);

arsort($directories);
foreach($installfiles as $installfile){
	unlink($installfile);
}
foreach($directories as $directory){
	rmdir($directory);
}
rmdir($dir);

function getFiles($dir){
	$directories = array();
	global $directories;
	$installfiles = array();
	global $installfiles;
	$sdir = scandir($dir);
	foreach($sdir as $key => $value){
		if (!in_array($value,array(".",".."))){
			if (is_dir($dir . DIRECTORY_SEPARATOR . $value)){
			$directories[] = $dir . DIRECTORY_SEPARATOR . $value;
			getFiles($dir . DIRECTORY_SEPARATOR . $value);
			} else {
			$installfiles[] = $dir . DIRECTORY_SEPARATOR . $value;
			}
		}
	}
}
?>

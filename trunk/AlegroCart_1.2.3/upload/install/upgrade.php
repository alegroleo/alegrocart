<?php

// Installed?
if (filesize('../config.php') == 0) { header('Location: index.php'); exit; }

require('common.php');

// Include Config and Common
require('../config.php');
if (!defined('DIR_BASE')) { define('DIR_BASE', getbasepath()); }
if (!defined('HTTP_BASE')) { define('HTTP_BASE', getbaseurl()); }
require('../common.php');

$errors = array();

$files=array('config.php');  //,'admin'.DIRECTORY_SEPARATOR.'config.php'  Not Required
foreach ($files as $file) {
	$file=DIR_BASE.$file;
	if (!file_exists($file)) { $errors[]="'$file' was not found! (ensure you have uploaded it)"; }
	elseif (!is_writable($file)) { $errors[]="'$file' is not writable! (chmod a+w)"; }
}

if (!$link = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
	$errors[] = 'Could not connect to the database server using the username and password provided.';
}
else {
	if (!@mysql_select_db(DB_NAME, $link)) {
		$errors[] = 'The database could selected, check you have permissions, and check it exists on the server.';
	}
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
		<h1>AlegroCart</h1>
		<div id="container">
		<div id="header">Upgrade</div>
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
				echo "<p>'$file' was updated successfully.</p>\n";
				fclose($handle);
			}
			else { $errors[]="Could not write to '$file' file."; }
		} 
		else { $errors[]="<b>Could not open '$file' file for writing."; }
		unset($str);

		//replace existing admin config with new one (NOT required any more)
		/*$file=DIR_ADMIN.'config.php';
		$str="<?php include('../config.php'); ?>";
		if($handle = fopen($file, 'w')) {
			if(fwrite($handle, $str)) {
				echo "<p>'$file' was updated successfully.</p>\n";
				fclose($handle);
			}
			else { $errors[]="Could not write to '$file' file."; }
		} 
		else { $errors[]="<b>Could not open '$file' file for writing."; }
		unset($str);*/

		mysql_query('set character set utf8', $link);
		mysql_query('set @@session.sql_mode="MYSQL40"', $link);

		//run sql
		$files='upgrade.sql';
		$files=explode(',',$files);
		foreach ($files as $file) {
			if (!$errors && file_exists($file)) {
				mysql_import_file($file,$link);
			} else {
				$errors[] = 'Install SQL file '.$file.' could not be found.';
			}
		}
	}
	if (!empty($errors)) { //has to be a separate if
		?>
		<p>The following errors occured:</p>
		<?php foreach ($errors as $error) {?>
		<div class="warning"><?php echo $error;?></div><br>
		<?php } ?>
		<p>Please fix the above error(s), install halted!</p>
	<?php } else { ?>
		  <div class="warning">You MUST delete this install directory!</div><br>
		  <p>Congratulations! You have successfully upgraded <a href="http://www.alegrocart.com/">AlegroCart</a>.</p>
	<?php } ?>
		</div><!--div/content-->
		<div id="footer">
		<form name="finish" id="finish">
		  <input type="button" value="Online Shop" onclick="location='<?php echo HTTP_CATALOG; ?>';">
		  <input type="button" value="Administration" onclick="location='<?php echo HTTP_ADMIN; ?>';">
		</form>
		</div><!--div/container-->
	</body>
</html>
<?php
if (!$step) { header('Location: .'); die(); }

$length = function_exists('mb_strlen')?mb_strlen($_POST['new_admin_name'],'UTF-8'):strlen($_POST['new_admin_name']);
$restricted = array('admin','administration');
$existing = array('cache','library', 'logs','catalog','image','download','install');

if (empty($_POST['new_admin_name'])) {
$errors[] = $language->get('error_new_admin_name');
}
elseif ($length<5 || $length>15) {
	$errors[] = $language->get('error_length'); 
}
elseif (in_array($_POST['new_admin_name'], $restricted) || in_array($_POST['new_admin_name'], $existing)) {
	$errors[] = $language->get('error_restricted', $_POST['new_admin_name']); 
}
elseif (!preg_match('/^[a-z0-9_\-]+$/', $_POST['new_admin_name'])) {
	$errors[] = $language->get('error_alphanumeric'); 
} else {
	if ($_POST['root_dirs']=='admin'){
	//not renamed yet, let us rename it
		if (!$renamed=rename(DIR_BASE.'admin', DIR_BASE.$_POST['new_admin_name'])) {
		$errors[] = $language->get('error_rename'); 
		}
	} else {
		//already renamed manually?
		if ($_POST['root_dirs']!==$_POST['new_admin_name']){
			$errors[] = $language->get('error_post'); 
		}
	}
}

if (!$errors) {
	if (!$link = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
		$errors[] = $language->get('error_dbconnect');
	}
	else {
		if (!@mysql_select_db(DB_NAME, $link)) {
			$errors[] = $language->get('error_dbperm');
		}
	}
}

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
			'PATH_ADMIN' => isset($_POST['new_admin_name'])?$_POST['new_admin_name']:''
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

if ($errors && $step == 2) {
	require('upgrade_step1.php');
} else {
		$file = DIR_BASE.'config.php';
		@chmod($file, 0644);
	?>
		<?php if(is_writable($file)) { ?>
			<div class="warning"><?php echo $language->get('config')?></div>
		<?php }?>
		<p class="a"><?php echo $language->get('congrat_upg')?></p>

		<div id="buttons">
		<div class="left">
		<a onclick="location='<?php echo HTTP_CATALOG; ?>';" >
		<img src="../image/install/Shopping_Cart.png" alt="<?php echo $language->get('shop')?>" title="<?php echo $language->get('shop')?>">
		</a>
		<p class="a"><?php echo HTTP_CATALOG; ?></p>
		</div>
		<div class="right">
		<a onclick="location='<?php echo HTTP_BASE.$_POST['new_admin_name']; ?>';">
		<img src="../image/install/Admin.png" alt="<?php echo $language->get('admin')?>" title="<?php echo $language->get('admin')?>">
		</a>
		<p class="a"><?php echo HTTP_BASE.$_POST['new_admin_name']; ?></p>
		</div>
		</div>

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
}

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

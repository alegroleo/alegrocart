<?php

if (!$step) { header('Location: .'); die(); }

if (filesize('../config.php') == 0) { //install is already done...

if (empty($_POST['username'])) { $errors[] = 'Admin username is required'; }
if (empty($_POST['password'])) { $errors[] = 'Admin password is required'; }

if (!$errors) {
		//replace existing config with new one
		$newfile='default.config.php';
		$file='../config.php';
		$str=file_get_contents($newfile);
		if ($handle = fopen($file, 'w')) {
			$reps=array(
				'DIR_BASE' => addslashes(DIR_BASE),
				'HTTP_BASE' => HTTP_BASE,
				'DB_HOST' => isset($_POST['db_host'])?$_POST['db_host']:'',
				'DB_USER' => isset($_POST['db_user'])?$_POST['db_user']:'',
				'DB_PASSWORD' => isset($_POST['db_pass'])?$_POST['db_pass']:'',
				'DB_NAME' => isset($_POST['db_name'])?$_POST['db_name']:''
			);
			foreach ($reps as $key => $val) {
				$str=preg_replace("/($key', ')(.*?)(')/", '${1}'.addslashes($val).'$3', $str);
			}

			if (fwrite($handle, $str)) {
				echo "<p>'$file' was updated successfully.</p>\n";
				fclose($handle);
			}
			else { $errors[]="Could not write to '$file' file."; }
		} 
		else { $errors[]="<b>Could not open '$file' file for writing."; }
		unset($str);
}

if (!$errors) {
	if (!$link = @mysql_connect($_POST['db_host'], $_POST['db_user'], $_POST['db_pass'])) {
		$errors[] = 'Could not connect to the database server using the username and password provided.';
	}
	else {
		if (!@mysql_select_db($_POST['db_name'], $link)) {
			$errors[] = 'The database could selected, check you have permissions, and check it exists on the server.';
		}			
	}
}
						
if (!$errors) {
	mysql_query('set character set utf8', $link);
	mysql_query('set @@session.sql_mode="MYSQL40"', $link);
	mysql_query("delete from user where user_id = '1'", $link);
	mysql_query("insert into `user` set user_id = '1', user_group_id = '1', username = '" . mysql_real_escape_string($_POST['username']) . "', password = '" . mysql_real_escape_string(md5($_POST['password'])) . "', date_added = now()", $link);
	mysql_close($link);
}

} //end install check

if ($errors && $step == 3) {
	require('step2.php');
} else {
?>

<div id="header">Finished!</div>
<div id="content">
  <div class="warning">You MUST delete this install directory!</div><br>
  <div class="warning">Make 'config.php' unwritable (chmod go-w).</div><br>
  <div class="warning">Make 'admin/config.php' unwritable (chmod go-w).</div>
  <p>Congratulations! You have successfully installed <a href="http://www.alegrocart.com/">AlegroCart</a>.</p>
</div>
<div id="footer">
<form>
		  <input type="button" value="Online Shop" onclick="location='<?php echo HTTP_CATALOG; ?>';">
		  <input type="button" value="Administration" onclick="location='<?php echo HTTP_ADMIN; ?>';">
</form>
</div>
<?php } ?>
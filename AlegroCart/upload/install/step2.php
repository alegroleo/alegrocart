<?php
if (!$step) { header('Location: .'); die(); }

if (empty($_POST['db_host'])) { $errors[] = 'Database Host is required'; }
if (empty($_POST['db_user'])) { $errors[] = 'Database Username is required'; }
if (empty($_POST['db_name'])) { $errors[] = 'Database Name is required'; }

if (!$errors) {
	if (!$link = @mysql_connect($_POST['db_host'], $_POST['db_user'], $_POST['db_pass'])) {
		$errors[] = 'Could not connect to the database server using the username and password provided.';
	}
	else {
		if (!@mysql_select_db($_POST['db_name'], $link)) {
			$errors[] = 'The database could not be accessed. Check that you have permissions, and that it exists on the server.';
		}			
	}
	@mysql_query('set character set utf8', $link);
}

if (!$errors) {
	$files='structure.sql,default.sql,upgrade.sql';
	$files=explode(',',$files);
	foreach ($files as $file) {
		if (!$errors && file_exists($file)) {
			mysql_import_file($file,$link);
		} else {
			$errors[] = 'Install SQL file '.$file.' could not be found.';
		}
	}
	mysql_close($link);
}

if ($errors && $step == 2) {
	require('step1.php');
} else {
?>
<div id="header">Step 2 - Administration</div>
<div id="content">
<?php if (!empty($errors)) { ?>
		<p>The following errors occured:</p>
		<?php foreach ($errors as $error) {?>
		<div class="warning"><?php echo $error;?></div><br>
		<?php } ?>
		<p>Please fix the above error(s), install halted!</p>
<?php } ?>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="step" value="3">
<input type="hidden" name="db_host" value="<?php echo $_POST['db_host']; ?>">
<input type="hidden" name="db_user" value="<?php echo $_POST['db_user']; ?>">
<?php if (!empty($_POST['db_pass'])) { ?>
<input type="hidden" name="db_pass" value="<?php echo $_POST['db_pass']; ?>">
<?php } ?>
<input type="hidden" name="db_name" value="<?php echo $_POST['db_name']; ?>">
    <p class="a">Please enter a username and password for the administration.</p>
    <table>
      <tr>
        <td width="185" class="set">Username:</td>
        <td><input type="text" name="username" value="<?php echo (isset($_POST['username']) ? $_POST['username'] : 'admin'); ?>">
          <span class="required">*</span>
		</td>
      </tr>
      <tr>
        <td class="set">Password:</td>
        <td><input type="text" name="password" value="<?php echo @$_POST['password']; ?>">
          <span class="required">*</span>
		</td>
      </tr>
    </table>
	</div>
  <div id="footer">
    <input type="submit" value="Continue" class="submit">
  </div>
</form>
<?php } ?>
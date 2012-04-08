<?php
if (!$step) { header('Location: .'); die(); }

if (empty($_POST['db_host'])) { $errors[] = $language->get('error_dbhost'); }
if (empty($_POST['db_user'])) { $errors[] = $language->get('error_dbuser'); }
if (empty($_POST['db_name'])) { $errors[] = $language->get('error_dbname'); }

if (!$errors) {
	if (!$link = @mysql_connect($_POST['db_host'], $_POST['db_user'], $_POST['db_pass'])) {
		$errors[] = $language->get('error_dbconnect');
	}
	else {
		if (!@mysql_select_db($_POST['db_name'], $link)) {
			$errors[] = $language->get('error_dbperm');
		}			
	}
	@mysql_query('set character set utf8', $link);
}

if (!$errors) {
	if ($_POST['method']=='default') {
	      $files='structure.sql,default.sql,upgrade.sql';
	} else {
	      $files='structure.sql,clean.sql,upgrade.sql';
	}

	$files=explode(',',$files);
	foreach ($files as $file) {
		if (!$errors && file_exists($file)) {
			mysql_import_file($file,$link);
		} else {
			$errors[] = $language->get('error_sql',$file);
		}
	}
	mysql_close($link);
}

if ($errors && $step == 2) {
	require('step1.php');
} else {
?>
<div id="content">
<?php if (!empty($errors)) { ?>
		<p class="b"><?php echo $language->get('error')?></p>
		<?php foreach ($errors as $error) {?>
		<div class="warning"><?php echo $error;?></div>
		<?php } ?>
		<p class="b"><?php echo $language->get('error_fix')?></p>
<?php } ?>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="step" value="3">
<input type="hidden" name="language" value="<?php echo $_POST['language']; ?>">
<input type="hidden" name="db_host" value="<?php echo $_POST['db_host']; ?>">
<input type="hidden" name="db_user" value="<?php echo $_POST['db_user']; ?>">
<input type="hidden" name="method" value="<?php echo $_POST['method']; ?>">
<?php if (!empty($_POST['db_pass'])) { ?>
<input type="hidden" name="db_pass" value="<?php echo $_POST['db_pass']; ?>">
<?php } ?>
<input type="hidden" name="db_name" value="<?php echo $_POST['db_name']; ?>">
    <p class="a"><?php echo $language->get('admin_details')?></p>
    <table>
      <tr>
        <td width="185" class="set"><?php echo $language->get('uname')?></td>
        <td><input type="text" name="username" value="<?php echo (isset($_POST['username']) ? $_POST['username'] : 'admin'); ?>">
          <span class="required">*</span>
	</td>
      </tr>
      <tr>
        <td class="set"><?php echo $language->get('passw')?></td>
        <td><input type="text" name="password" value="<?php echo @$_POST['password']; ?>">
          <span class="required">*</span>
	</td>
      </tr>
    </table>
</div>
<div id="buttons">
    <input type="submit" value="<?php echo $language->get('continue')?>" class="submit">
</div>
</form>
<?php } ?>
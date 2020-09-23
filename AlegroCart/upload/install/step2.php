<?php
if (!$step) {
	header('Location: .'); die();
}

if (empty($_POST['db_host'])) { $ferrors['host'] = $language->get('error_dbhost'); }
if (empty($_POST['db_user'])) { $ferrors['user'] = $language->get('error_dbuser'); }
if (empty($_POST['db_name'])) { $ferrors['name'] = $language->get('error_dbname'); }
if (empty($_POST['db_pass'])) { $ferrors['pass'] = $language->get('error_dbpass'); }

if (!$errors && !$ferrors) {
	$database->connect($_POST['db_host'], $_POST['db_user'], $_POST['db_pass'], $_POST['db_name']);
}

if (!$errors && !$ferrors) {
	if ($_POST['method']=='default') {
		$files='structure.sql,default.sql,upgrade.sql';
	} else {
		$files='structure.sql,clean.sql,upgrade.sql';
	}

	$files=explode(',',$files);
	foreach ($files as $file) {
		if (!$errors && file_exists($file)) {
			$database->import_file($file);
		} else {
			$errors[] = $language->get('error_sql',$file);
		}
	}
	$database->disconnect();
}

if (($errors || $ferrors) && $step == 2) {
	require('step1.php');
} else {
?>
<div id="content">
<?php if (!empty($errors)) { ?>
		<p class="b"><?php echo $language->get('error')?></p>
		<?php foreach ($errors as $error) {?>
		<div class="error"><?php echo $error;?></div>
		<?php } ?>
		<p class="b"><?php echo $language->get('error_fix')?></p>
<?php } ?>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="step" value="3">
<input type="hidden" name="language" value="<?php echo $_POST['language']; ?>">
<input type="hidden" name="db_host" value="<?php echo $_POST['db_host']; ?>">
<input type="hidden" name="db_user" value="<?php echo $_POST['db_user']; ?>">
<input type="hidden" name="method" value="<?php echo $_POST['method']; ?>">
<input type="hidden" name="db_pass" value="<?php echo $_POST['db_pass']; ?>">
<input type="hidden" name="db_name" value="<?php echo $_POST['db_name']; ?>">
    <p class="b"><?php echo $language->get('rename')?></p>
    <table>
      <tr>
        <td width="185" class="set"><span class="required">*</span><?php echo $language->get('new_admin')?></td>
        <td><input type="text" name="new_admin_name" value="<?php echo (isset($_POST['new_admin_name']) ? $_POST['new_admin_name'] : ($root_dirs[0] !== 'admin' ? $root_dirs[0] : '')); ?>" <?php echo ($root_dirs[0] !== 'admin' ? 'readonly="readonly"' : ''); ?>>
		<?php if (isset($ferrors['new_admin_name'])) { ?>
                      <span class="error"><?php echo $ferrors['new_admin_name']; ?></span>
		<?php } ?>
		<?php if (isset($ferrors['length'])) { ?>
                      <span class="error"><?php echo $ferrors['length']; ?></span>
		<?php } ?>
		<?php if (isset($ferrors['restricted'])) { ?>
                      <span class="error"><?php echo $ferrors['restricted']; ?></span>
		<?php } ?>
		<?php if (isset($ferrors['alphanumeric'])) { ?>
                      <span class="error"><?php echo $ferrors['alphanumeric']; ?></span>
		<?php } ?>
		<?php if (isset($ferrors['post'])) { ?>
                      <span class="error"><?php echo $ferrors['post']; ?></span>
		<?php } ?>
	</td>
      </tr>
      <tr>
        <td class="set"></td>
        <td><?php echo $language->get('rename_expl')?>
	</td>
      </tr>
    </table>
    <p class="b"><?php echo $language->get('admin_details')?></p>
    <table>
      <tr>
        <td width="185" class="set"><span class="required">*</span><?php echo $language->get('uname')?></td>
        <td><input type="text" name="username" value="<?php echo (isset($_POST['username']) ? $_POST['username'] : 'admin'); ?>">
		<?php if (isset($ferrors['admin_uname'])) { ?>
                      <span class="error"><?php echo $ferrors['admin_uname']; ?></span>
		<?php } ?>
	</td>
      </tr>
      <tr>
        <td class="set"><span class="required">*</span><?php echo $language->get('passw')?></td>
        <td><input type="text" name="password" value="<?php echo @$_POST['password']; ?>">
		<?php if (isset($ferrors['admin_passw'])) { ?>
                      <span class="error"><?php echo $ferrors['admin_passw']; ?></span>
		<?php } ?>
		<?php if (isset($ferrors['admin_passw_lenght'])) { ?>
                      <span class="error"><?php echo $ferrors['admin_passw_lenght']; ?></span>
		<?php } ?>
	</td>
      </tr>
    </table>
</div>
<div id="buttons">
    <input type="submit" value="<?php echo $language->get('continue')?>" class="submit">
</div>
</form>
<?php } ?>

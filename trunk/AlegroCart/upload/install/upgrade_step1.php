<?php
if (!$step) { header('Location: .'); die(); }

$root_dirs = array();
$existing = array('cache','library', 'logs','catalog','image','download','install');

$dir_root_handle = opendir(DIR_BASE);
if ($dir_root_handle) {
	while (false !== ($fname = readdir($dir_root_handle))) {
		if (($fname != '.') && ($fname != '..') && (!in_array($fname, $existing )) && is_dir(DIR_BASE.$fname)) {
			$root_dirs[] = $fname;
		}
	}
closedir($dir_root_handle);
}

if (count($root_dirs)!==1) {
$errors[] = $language->get('error_dir'); 
}
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
<input type="hidden" name="step" value="2">
<input type="hidden" name="language" value="<?php echo isset($_POST['language']) ? $_POST['language'] : $language->detect(); ?>">
<input type="hidden" name="root_dirs" value="<?php echo $root_dirs[0]; ?>">
    <p class="a"><?php echo $language->get('rename')?></p>
    <table>
      <tr>
        <td width="185" class="set"><?php echo $language->get('new_admin')?></td>
        <td><input type="text" name="new_admin_name" value="<?php echo (isset($_POST['new_admin_name']) ? $_POST['new_admin_name'] : ($root_dirs[0] !== 'admin' ? $root_dirs[0] : '')); ?>" <?php echo ($root_dirs[0] !== 'admin' ? 'readonly="readonly"' : ''); ?>>
          <span class="required">*</span>
	</td>
      </tr>
      <tr>
        <td class="set"></td>
        <td><?php echo $language->get('rename_expl')?>
	</td>
      </tr>
    </table>
</div>
<div id="buttons">
    <input type="submit" value="<?php echo $language->get('continue')?>" class="submit">
</div>
</form>

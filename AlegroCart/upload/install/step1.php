<?php
if (!$step) { header('Location: .'); die(); }
?>

<div id="content">
<?php 
	if (!empty($errors)) { ?>
		<p class="b"><?php echo $language->get('error')?></p>
		<?php foreach ($errors as $error) { ?>
			<div class="error"><?php echo $error;?></div>
		<?php } ?>
		<p class="b"><?php echo $language->get('error_fix')?></p>
<?php } ?>
<p class="b"><?php echo $language->get('fresh')?></p>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="step" value="2">
<input type="hidden" name="language" value="<?php echo isset($_POST['language']) ? $_POST['language'] : $language->detect(); ?>">
    <p class="b"><?php echo $language->get('database_details')?></p>
    <table>
      <tr>
        <td width="185" class="set"><span class="required">*</span><?php echo $language->get('dbhost')?></td>
        <td width="225"><?php if (isset($_POST['db_host'])) { ?>
          <input type="text" name="db_host" value="<?php echo $_POST['db_host']; ?>">
          <?php } elseif (isset($_SESSION['db_host'])) { ?>
          <input type="text" name="db_host" value="<?php echo $_SESSION['db_host']; ?>">
          <?php } else {?>
          <input type="text" name="db_host" value="localhost">
          <?php } ?>
		<?php if (isset($ferrors['host'])) { ?>
                      <span class="error"><?php echo $ferrors['host']; ?></span>
		<?php } ?>
	 </td>
      </tr>
      <tr>
        <td class="set"><span class="required">*</span><?php echo $language->get('dbuser')?></td>
        <td width="225"><input type="text" name="db_user" value="<?php echo (isset($_POST['db_user']) ? $_POST['db_user'] : @$_SESSION['db_user']); ?>">
		<?php if (isset($ferrors['user'])) { ?>
                      <span class="error"><?php echo $ferrors['user']; ?></span>
		<?php } ?></td>
      </tr>
      <tr>
        <td class="set"><span class="required">*</span><?php echo $language->get('dbpassw')?></td>
        <td width="225"><input type="text" name="db_pass" value="<?php echo (isset($_POST['db_pass']) ? $_POST['db_pass'] : @$_SESSION['db_pass']); ?>">
		<?php if (isset($ferrors['pass'])) { ?>
                      <span class="error"><?php echo $ferrors['pass']; ?></span>
		<?php } ?>
	</td>
      </tr>
      <tr>
        <td class="set"><span class="required">*</span><?php echo $language->get('dbname')?></td>
        <td width="225"><input type="text" name="db_name" value="<?php echo (isset($_POST['db_name']) ? $_POST['db_name'] : @$_SESSION['db_name']); ?>">
		<?php if (isset($ferrors['name'])) { ?>
                      <span class="error"><?php echo $ferrors['name']; ?></span>
		<?php } ?>
		</td>
      </tr>
      <tr>
        <td class="set"><?php echo $language->get('method')?></td>
        <td width="225" class="set"><input type="radio" name="method" <?php echo (isset($_POST['method']) ? ($_POST['method'] =='default' ? 'checked' : 'unchecked') : 'checked'); ?> id="default" value="default"><label for="default"><?php echo $language->get('default_install')?></label>
	</td>
      </tr>
      <tr>
        <td class="set"></td>
        <td width="225"><?php echo $language->get('default_expl')?>
	</td>
      </tr>
      <tr>
        <td class="set"></td>
        <td width="225" class="set"><input type="radio" name="method" <?php echo (isset($_POST['method']) ? ($_POST['method'] =='clean' ? 'checked' : 'unchecked') : @$_SESSION['method']); ?> id="clean" value="clean"><label for="clean"><?php echo $language->get('clean_install')?></label>
	</td>
      </tr>
      <tr>
        <td class="set"></td>
        <td width="225"><?php echo $language->get('clean_expl')?>
	</td>
      </tr>
    </table>
  </div>
  <div id="buttons">
    <input type="submit" value="<?php echo $language->get('continue')?>" class="submit">
  </div>
</form>

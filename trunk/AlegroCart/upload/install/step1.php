<?php
if (!$step) { header('Location: .'); die(); }
?>

<div id="header">Step 1 - Database</div>
<div id="content">
<?php 
	if (!empty($errors)) { ?>
		<p>The following errors occured:</p>
		<?php foreach ($errors as $error) { ?>
			<div class="warning"><?php echo $error;?></div><br>
		<?php } ?>
		<p>Please fix the above error(s), install halted!</p>
<?php } ?>
<p><strong>THIS IS FOR FRESH INSTALLS ONLY! YOUR DATABASE WILL BE REMOVED.</strong></p>
<form method="post" enctype="multipart/form-data">
	<input type="hidden" name="step" value="2">
    <p class="a">Please enter your database connection details.</p>
    <table>
      <tr>
        <td width="185" class="set">Database Host:</td>
        <td><?php if (isset($_POST['db_host'])) { ?>
          <input type="text" name="db_host" value="<?php echo $_POST['db_host']; ?>">
          <?php } elseif (isset($_SESSION['db_host'])) { ?>
          <input type="text" name="db_host" value="<?php echo $_SESSION['db_host']; ?>">
          <?php } else {?>
          <input type="text" name="db_host" value="localhost">
          <?php } ?>
          <span class="required">*</span>
		 </td>
      </tr>
      <tr>
        <td class="set">Database Username:</td>
        <td><input type="text" name="db_user" value="<?php echo (isset($_POST['db_user']) ? $_POST['db_user'] : @$_SESSION['db_user']); ?>">
          <span class="required">*</span>
		</td>
      </tr>
      <tr>
        <td class="set">Database Password:</td>
        <td><input type="text" name="db_pass" value="<?php echo (isset($_POST['db_pass']) ? $_POST['db_pass'] : @$_SESSION['db_pass']); ?>">
          <?php if (isset($errors['db_pass'])) { ?>
          <span class="required"><?php echo $errors['db_pass']; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td class="set">Database Name:</td>
        <td><input type="text" name="db_name" value="<?php echo (isset($_POST['db_name']) ? $_POST['db_name'] : @$_SESSION['db_name']); ?>">
          <span class="required">*</span>
		</td>
      </tr>
    </table>
  </div>
  <div id="footer">
    <input type="submit" value="Continue" class="submit">
  </div>
</form>

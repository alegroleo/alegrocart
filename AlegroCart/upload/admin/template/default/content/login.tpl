<link rel="stylesheet" type="text/css" href="template/default/css/login.css">
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($error_attempts){ ?>
<div class="warning"><?php echo $error_attempts; ?></div>
<?php } ?>
<table cellspacing="5" cellpadding="6" id="login">
  <tr>
    <td><img src="template/<?php echo $this->directory?>/image/login.png" alt="<?php echo $button_login; ?>"></td>
    <td id="login_form"><form id="form_login" name="form_login" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <table cellpadding="1">
          <tr>
            <td><?php echo $entry_username; ?></td>
          </tr>
          <tr>
            <td><input type="text" name="username" value="<?php if (isset($username)) echo $username; ?>"></td>
          </tr>
          <tr>
            <td><?php echo $entry_password; ?></td>
          </tr>
          <tr>
            <td><input type="password" name="password"></td>
          </tr>
          <tr>
            <td><input type="submit" value="<?php echo $button_login; ?>" id="login_button"></td>
          </tr>
        </table>
		<input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
      </form></td>
  </tr>
</table>

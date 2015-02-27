<?php 
  $head_def->setcss($this->style . "/css/account_edit.css");
?>
<div class="headingbody"><h1><?php echo $heading_title; ?></h1></div>
<div class="contentBody">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div id="edit">
    <div class="a"><?php echo $text_your_details; ?></div>
    <div class="b">
      <table>
        <tr>
          <td width="150"><span class="required">*</span> <?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname" id="firstname" value="<?php echo $firstname; ?>" class="validate_alpha">
            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname" id="lastname" value="<?php echo $lastname; ?>" class="validate_alpha">
            <?php if ($error_lastname) { ?>
            <span class="error"><?php echo $error_lastname; ?></span>
            <?php } ?></td>
        </tr>
        <tr> 
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><input type="text" name="email" id="email" value="<?php echo $email; ?>" class="validate_mail">
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
          <td><input type="text" name="telephone" id="telephone" value="<?php echo $telephone; ?>" class="validate_phone">
            <?php if ($error_telephone) { ?>
            <span class="error"><?php echo $error_telephone; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_fax; ?></td>
          <td><input type="text" name="fax" id="fax" value="<?php echo $fax; ?>" class="validate_phone"></td>
        </tr>
      </table>
    </div>
  </div>
  <div class="buttons">
    <table>
      <tr>
        <td align="left"><input type="button" value="<?php echo $button_back; ?>" onclick="location='<?php echo $back; ?>'"></td>
        <td align="right"><input type="submit" value="<?php echo $button_continue; ?>"></td>
      </tr>
    </table>
  </div>
  <input type="hidden" name="account_validation" value="<?php echo $account_validation;?>">
</form></div>
<div class="contentBodyBottom"></div>

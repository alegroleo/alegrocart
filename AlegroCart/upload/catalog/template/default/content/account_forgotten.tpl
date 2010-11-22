<?php 
  $head_def->setcss($this->style . "/css/account_forgotten.css");
?>
<div class="headingbody"><?php echo $heading_title; ?></div>
<div class="contentBody">
<?php if ($error) { ?> 
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  
  <div id="forgotten">
    <p><?php echo $text_email; ?></p>
    <div class="a"><?php echo $text_your_email; ?></div>
    <div class="b">
      <table>
        <tr>
          <td width="150"><?php echo $entry_email; ?></td>
          <td><input type="text" name="email"></td>
        </tr>
      </table>
    </div>
  </div></div>
  <div class="contentBodyBottom"></div>
  <div class="buttons">
    <table>
      <tr>
        <td align="left"><input type="button" value="<?php echo $button_back; ?>" onclick="location='<?php echo $back; ?>'"></td>
        <td align="right"><input type="submit" value="<?php echo $button_continue; ?>"></td>
      </tr>
    </table>
	<input type="hidden" name="account_validation" value="<?php echo $account_validation;?>">
  </div>
</form>

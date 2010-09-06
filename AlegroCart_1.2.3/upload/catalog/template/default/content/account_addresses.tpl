<?php 
  $head_def->setcss($this->style . "/css/account_address.css");  
?>
<div class="headingbody"><?php echo $heading_title; ?></div>
  <div class="contentBody">
<div id="address">

  <?php if ($message) { ?> 
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
  <div class="b"><?php echo $text_address_book; ?> </div>
  <?php foreach ($addresses as $result) { ?>
  <div class="c">
    <table style="width:=580px">
      <tr>
        <td width="70%"><?php echo $result['address']; ?></td>
        <td><input type="button" value="<?php echo $button_edit; ?>" onClick="location='<?php echo $result['update']; ?>'"></td>
        <td><input type="button" value="<?php echo $button_delete; ?>" onClick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $result['delete']; ?>'; } else { return; }" ></td>
      </tr>
    </table>
  </div>
  <?php } ?>
  </div>
  <div class="buttons">
    <table>
      <tr>
        <td align="left"><input type="button" value="<?php echo $button_back; ?>" onClick="location='<?php echo $back; ?>'"></td>
        <td align="right"><input type="submit" value="<?php echo $button_new_address; ?>" onClick="location='<?php echo $insert; ?>'"></td>
      </tr>
    </table>
  </div>
</div>
<div class="contentBodyBottom"></div>
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<script type="text/javascript" src="javascript/fckeditor/fckeditor.js"></script>
<div id="mail">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    <table>
      <tr>
        <td align="right"><?php echo $entry_to; ?></td>
        <td><select name="to">
            <?php if ($to == 'newsletter') { ?>
            <option value="newsletter" SELECTED><?php echo $text_newsletter; ?></option>
            <?php } else { ?>
            <option value="newsletter"><?php echo $text_newsletter; ?></option>
            <?php } ?>
            <?php if ($to == 'customer') { ?>
            <option value="customer" SELECTED><?php echo $text_customer; ?></option>
            <?php } else { ?>
            <option value="customer"><?php echo $text_customer; ?></option>
            <?php } ?>
            <?php foreach ($customers as $customer) { ?>
            <?php if ($customer['customer_id'] == $to) { ?>
            <option value="<?php echo $customer['customer_id']; ?>" SELECTED><?php echo $customer['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $customer['customer_id']; ?>"><?php echo $customer['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td align="right"><span class="required">*</span> <?php echo $entry_subject; ?></td>
        <td><input name="subject" value="<?php echo $subject; ?>">
          <?php if ($error_subject) { ?>
          <span class="error"><?php echo $error_subject; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td align="right" valign="top"><span class="required">*</span> <?php echo $entry_content; ?></td>
        <td><textarea name="content" id="content"><?php echo $content; ?></textarea>
          <?php if ($error_content) { ?>
          <span class="error"><?php echo $error_content; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td align="right" colspan="2"><input type="submit" value="<?php echo $button_send; ?>"></td>
      </tr>
    </table>
	<input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
    <script type="text/javascript"><!--
	var sBasePath           = '<?php echo HTTP_SERVER.'javascript/fckeditor/'?>';
	var oFCKeditor          = new FCKeditor('content');
	oFCKeditor.BasePath = sBasePath;
	oFCKeditor.Value	= document.getElementById('content').value;
	oFCKeditor.Width    = '600';
	oFCKeditor.Height   = '300';
	oFCKeditor.Config['CustomConfigurationsPath'] = oFCKeditor.BasePath + 'myconfig.js';
	oFCKeditor.Config['DocType'] = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
	oFCKeditor.Config['SkinPath'] = oFCKeditor.BasePath + 'editor/skins/silver/' ;
	oFCKeditor.ToolbarSet = 'Custom' ;
	oFCKeditor.ReplaceTextarea();  
  //--></script>
  </form>
</div>

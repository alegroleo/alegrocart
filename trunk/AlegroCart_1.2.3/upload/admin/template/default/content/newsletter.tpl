<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/<?php echo $this->directory?>/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $insert; ?>'"><img src="template/<?php echo $this->directory?>/image/insert_enabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
  <?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/fckeditor/fckeditor.js"></script>
<div id="mail">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    <table>
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
        <td align="right"><?php echo $entry_send; ?></td>
        <td><?php if ($send == 1) { ?>
          <input type="radio" name="send" value="1" checked>
          <?php echo $text_yes; ?>
          <input type="radio" name="send" value="0">
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="send" value="1"d>
          <?php echo $text_yes; ?>
          <input type="radio" name="send" value="0" checke>
          <?php echo $text_no; ?>
          <?php } ?></td>
      </tr>
    </table>
	<input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  </form>
</div>
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
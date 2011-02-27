<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/list_disabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><?php echo $tab_general; ?></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><?php echo $entry_backup; ?></td>
	      <td><input type="button" class="button" value="<?php echo $text_backup; ?>" onclick="location='<?php echo $download; ?>'"></td>
	      <td class="expl">
                <?php echo($explanation_entry_backup); ?>
	      </td>
	    </tr>
	    <tr>
              <td width="185" class="set"><?php echo $entry_restore; ?></td>
 	      <td><input type="text" id="fileName" class="file_input_textbox" readonly="readonly">
	      <div class="file_input_div">
	      <input type="button" value="<?php echo $text_browse; ?>" class="file_input_button" />
	      <input type="file" name="database" class="file_input_hidden" onchange="javascript: document.getElementById('fileName').value = this.value" />
	      </div></td>
            <td class="expl">
                <?php echo($explanation_entry_restore); ?>
            </td>
	    </tr>
          </table>
		  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
</form>

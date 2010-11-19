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
              <td width="145"><?php echo $entry_minov; ?></td>
              <td ><input size="10" type="text" name="global_minov_value" value="<?php echo $global_minov_value; ?>"></td>
	      <td>
                <?php echo($explanation_entry_minov); ?>
	      </td> 
            </tr>
            <tr>
              <td><?php echo $entry_minov_status; ?></td>
              <td width="160"><?php if ($global_minov_status) { ?>
                <input type="radio" name="global_minov_status" value="1" checked>
                <?php echo $text_enabled; ?>
                <input type="radio" name="global_minov_status" value="0">
                <?php echo $text_disabled; ?>
                <?php } else { ?>
                <input type="radio" name="global_minov_status" value="1">
                <?php echo $text_enabled; ?>
                <input type="radio" name="global_minov_status" value="0" checked>
                <?php echo $text_disabled; ?>
                <?php } ?></td>
	      <td>
                <?php echo($explanation_entry_minov_status); ?>
	      </td> 
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
 <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
 <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
</form>

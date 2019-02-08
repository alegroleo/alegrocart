<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
   <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="getValues();document.getElementById('update_form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
   <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
   <input type="hidden" name="update_form" value="1">
   <input type="hidden" name="name" value="">
   <input type="hidden" name="code" value="">
   <input type="hidden" name="image" value="">
   <input type="hidden" name="directory" value="">
   <input type="hidden" name="filename" value="">
   <input type="hidden" name="language_status" value="">
   <input type="hidden" name="sort_order" value="">
  <input type="hidden" name="language_id" value="<?php echo $language_id; ?>">
  </form>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
  <?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?>
 <em></em>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><span class="required">*</span> <?php echo $entry_name; ?></td>
              <td>
			    <?php if($code == "en"){?>
			      <input type="text" readonly="readonly" name="name" maxlength="32" value="<?php echo $name; ?>">
			    <?php } else {?>
			      <input type="text" name="name" maxlength="32" value="<?php echo $name; ?>">
				<?php } ?>
                <?php if ($error_name) { ?>
                <span class="error"><?php echo $error_name; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_code; ?></td>
              <td>
			    <?php if($code == "en"){?>
				  <input type="text" readonly="readonly" name="code" maxlength="32" value="<?php echo $code; ?>">
				<?php } else {?>
			      <input class="validate_alpha" id="code" type="text" name="code" maxlength="32" value="<?php echo $code; ?>">
			    <?php } ?>
                <?php if ($error_code) { ?>
                <span class="error"><?php echo $error_code; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_image; ?></td>
              <td><input class="validate_file" type="text" id="flag_image" name="image" maxlength="32" value="<?php echo $image; ?>">
                <?php if ($error_image) { ?>
                <span class="error"><?php echo $error_image; ?></span>
                <?php } ?></td>
				<td>
				  <select  name="flag_image" onchange="$('#image').load('index.php?controller=language&action=view_image&flag_image='+this.value), $('#flag_image').val(this.value);">
				    <?php foreach($flags as $flag){?>
				      <option value="<?php echo $flag['filename'];?>"<?php if($image == $flag['filename']){ echo ' selected';}?>><?php echo $flag['country'] . ' (' . $flag['name'] . ') ';?></option>
					<?php }?>
				  </select>
				<td>
				<td id="image">
				  <?php if(isset($image_thumb)){?>
				    <img src="<?php echo $image_thumb;?>" alt="" title="Flag" width="32" height="22">
				  <?php }?>
				</td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_directory; ?></td>
              <td>
			    <?php if($code == "en"){?>
				  <input type="text" readonly="readonly" name="directory" maxlength="32" value="<?php echo $directory; ?>">
				<?php } else {?>
			      <input class="validate_alpha_num" id="directory" type="text" name="directory" maxlength="32" value="<?php echo $directory; ?>">
			    <?php } ?>
                <?php if ($error_directory) { ?>
                <span class="error"><?php echo $error_directory; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_filename; ?></td>
              <td>
			    <?php if($code == "en"){?>
				  <input type="text" readonly="readonly" name="filename" maxlength="32" value="<?php echo $filename; ?>">
				<?php } else {?>
			      <input class="validate_file" id="filename" type="text" name="filename" maxlength="32" value="<?php echo $filename; ?>">
				<?php } ?>
                <?php if ($error_filename) { ?>
                <span class="error"><?php echo $error_filename; ?></span>
                <?php } ?></td>
            </tr>
	    <tr>
              <td class="set"><?php echo $entry_language_status; ?></td>
              <td><select name="language_status">
                  <?php if ($language_status == '1') { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_sort_order; ?></td>
              <td><input class="validate_int" id="sort_order" type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1"></td>
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
  <script type="text/javascript"><!--
    $('input[name="name"]').change(function () {
      var value = $(this).val();
      $(".heading em").text(value);
    }).change();
  //--></script>
  <script type="text/javascript"><!--
    $(document).ready(function() {
	  RegisterValidation();
    });
  //--></script>
  <script type="text/javascript"><!--
	function getValues() {
		document.forms['update_form'].name.value=document.forms['form'].name.value;
		document.forms['update_form'].code.value=document.forms['form'].code.value;
		document.forms['update_form'].image.value=document.forms['form'].image.value;
		document.forms['update_form'].directory.value=document.forms['form'].directory.value;
		document.forms['update_form'].filename.value=document.forms['form'].filename.value;
		document.forms['update_form'].language_status.value=document.forms['form'].language_status.value;
		document.forms['update_form'].sort_order.value=document.forms['form'].sort_order.value;
	}
  //--></script>
  <script type="text/javascript"><!--
  $(document).ready(function() {
	$('.task').each(function(){
	$('.task .disabled').hide();
	});
	<?php if (!$help) { ?>
		$('.description').hide(0);
	<?php } ?>
  });
  function ShowDesc(){
	$.ajax({
		type:    'POST',
		url:     'index.php?controller=language&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
</form>

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
   <input type="hidden" name="title" value="">
   <input type="hidden" name="code" value="">
   <input type="hidden" name="status" value="">
   <input type="hidden" name="lock_rate" value="">
   <input type="hidden" name="symbol_left" value="">
   <input type="hidden" name="symbol_right" value="">
   <input type="hidden" name="decimal_place" value="">
   <input type="hidden" name="value" value="">
  <input type="hidden" name="currency_id" value="<?php echo $currency_id; ?>">
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
              <td width="185" class="set"><span class="required">*</span> <?php echo $entry_title; ?></td>
              <td><input type="text" name="title" value="<?php echo $title; ?>">
                <?php if ($error_title) { ?>
                <span class="error"><?php echo $error_title; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_code; ?></td>
              <td><input class="validate_alpha" id="code" type="text" name="code" value="<?php echo $code; ?>">
                <?php if ($error_code) { ?>
                <span class="error"><?php echo $error_code; ?></span>
                <?php } ?></td>
            </tr>
			<tr>
			  <td class="set"><?php echo $entry_status;?></td>
			  <td><select name="status">
			    <?php if($status) { ?>
			    <option value="1" selected><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected><?php echo $text_disabled; ?></option>
			    <?php } ?>
			  </select>
			  <?php if ($error_default) { ?>
                <span class="error"><?php echo $error_default; ?></span>
                <?php } ?>
			  </td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_lock_rate;?></td>
			  <td><select name="lock_rate">
			    <?php if($lock_rate) { ?>
			    <option value="1" selected><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected><?php echo $text_disabled; ?></option>
			    <?php } ?>
			  </select></td>
			  <td class="expl"><?php echo $text_lock_rate; ?></td>
			</tr>
            <tr>
              <td class="set"><?php echo $entry_symbol_left; ?></td>
              <td><input type="text" name="symbol_left" value="<?php echo $symbol_left; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_symbol_right; ?></td>
              <td><input type="text" name="symbol_right" value="<?php echo $symbol_right; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_decimal_place; ?></td>
              <td><input class="validate_int" id="decimal_place" type="text" name="decimal_place" value="<?php echo $decimal_place; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_value; ?></td>
              <td><input class="validate_float" id="value" type="text" name="value" value="<?php echo $value; ?>"></td>
			  <td class="expl"><?php echo $text_default_rate ;?></td>
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
    $('input[name="title"]').change(function () {
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
		document.forms['update_form'].title.value=document.forms['form'].title.value;
		document.forms['update_form'].code.value=document.forms['form'].code.value;
		document.forms['update_form'].status.value=document.forms['form'].status.value;
		document.forms['update_form'].lock_rate.value=document.forms['form'].lock_rate.value;
		document.forms['update_form'].symbol_left.value=document.forms['form'].symbol_left.value;
		document.forms['update_form'].symbol_right.value=document.forms['form'].symbol_right.value;
		document.forms['update_form'].decimal_place.value=document.forms['form'].decimal_place.value;
		document.forms['update_form'].value.value=document.forms['form'].value.value;
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
		url:     'index.php?controller=currency&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
</form>

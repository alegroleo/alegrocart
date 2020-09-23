<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" width=32 height=32 alt="<?php echo $button_insert; ?>" class="png" ><?php echo $button_insert; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="getValues();document.getElementById('update_form').submit();;"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" width=32 height=32 alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="global_paymate_status" value="">
  <input type="hidden" name="global_paymate_test" value="">
  <input type="hidden" name="global_paymate_geo_zone_id" value="">
  <input type="hidden" name="global_paymate_mid" value="">
  <input type="hidden" name="paymate_order_status" value="">
  <input type="hidden" name="global_paymate_sort_order" value="">
</form>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" width=32 height=32 alt="<?php echo $button_delete; ?>" class="png" ><?php echo $button_delete; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" width=32 height=32 alt="<?php echo $button_save; ?>" class="png" ><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" width=32 height=32 alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" width=32 height=32 alt="<?php echo $button_cancel; ?>" class="png" ><?php echo $button_cancel; ?></div>
<?php if (@$last) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $last; ?>'"><img src="template/<?php echo $this->directory?>/image/last_enabled.png" width=32 height=32 alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/last_disabled.png" width=32 height=32 alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } ?>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_payment; ?><em><?php echo $heading_title; ?></em>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" width=31 height=30 alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
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
              <td width="185" class="set"><?php echo $entry_status; ?></td>
              <td><select name="global_paymate_status">
                  <?php if ($global_paymate_status) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td class="expl"><?php echo $explanation_entry_status; ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_test; ?></td>
              <td><?php if ($global_paymate_test) { ?>
                <input type="radio" name="global_paymate_test" value="1" checked id="yes">
                <label for="yes"> <?php echo $text_yes; ?></label>
                <input type="radio" name="global_paymate_test" value="0" id="no">
                <label for="no"> <?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_paymate_test" value="1" id="yes">
                <label for="yes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_paymate_test" value="0" checked id="no">
                <label for="no"><?php echo $text_no; ?></label>
                <?php } ?></td>
              <td class="expl"><?php echo $explanation_entry_test; ?></td>
            </tr>
            <tr>
              <td width="185" class="set"><?php echo $entry_geo_zone; ?></td>
              <td><select name="global_paymate_geo_zone_id">
                  <option value="0"><?php echo $text_all_zones; ?></option>
                  <?php foreach ($geo_zones as $geo_zone) { ?>
                  <?php if ($geo_zone['geo_zone_id'] == $geo_zone_id) { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected><?php echo $geo_zone['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="expl"><?php echo $explanation_entry_geo_zone; ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_mid; ?></td>
              <td><input class="validate_alpha_num" id="paymate_mid" type="text" name="global_paymate_mid" style="width:200px;" value="<?php echo $global_paymate_mid; ?>" >
                <?php if ($error_mid) { ?>
                <span class="error"><?php echo $error_mid; ?></span>
                <?php } ?></td>
              <td class="expl"><?php echo $explanation_entry_mid; ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_order_status; ?></td>
              <td><select name="paymate_order_status">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $paymate_order_status) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" ><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="expl"><?php echo $explanation_entry_order_status; ?></td>
            </tr>
            <tr>
              <td valign="top" class="set"><?php echo $entry_currency; ?></td>
              <td><select name="global_paymate_currency[]" multiple="multiple" size="6">
                  <?php foreach ($currencies as $currency) { ?>
                  <?php if ($currency['selected']) { ?>
                  <option value="<?php echo $currency['value']; ?>" selected><?php echo $currency['text']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $currency['value']; ?>"><?php echo $currency['text']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="expl"><?php echo $explanation_entry_currency; ?><br><br><?php echo $explanation_multiselect;?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_sort_order; ?></td>
              <td><input class="validate_int" id="paymate_sort_order" type="text" name="global_paymate_sort_order" value="<?php echo $global_paymate_sort_order; ?>" size="1" ></td>
              <td class="expl"><?php echo $explanation_entry_sort_order; ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <script type="text/javascript">
  tabview_initialize('tab');
  </script>
  <script type="text/javascript">
	function getValues() {
		document.forms['update_form'].global_paymate_status.value=document.forms['form'].global_paymate_status.value;
		document.forms['update_form'].global_paymate_test.value=document.forms['form'].global_paymate_test.value;
		document.forms['update_form'].global_paymate_geo_zone_id.value=document.forms['form'].global_paymate_geo_zone_id.value;
		document.forms['update_form'].global_paymate_mid.value=document.forms['form'].global_paymate_mid.value;
		document.forms['update_form'].paymate_order_status.value=document.forms['form'].paymate_order_status.value;
		document.forms['update_form'].global_paymate_sort_order.value=document.forms['form'].global_paymate_sort_order.value;

		getMultipleSelection('form', 'global_paymate_currency[]', 'update_form');

	}
	function getMultipleSelection(formName,elementName,newFormName){ 
		var html ='';
		var mySelect = document.forms[formName].elements[elementName];
		for(let j = 0; j < mySelect.options.length; j++) { 
			if(mySelect.options[j].selected) { 
				html +='<input type="hidden" name="' + elementName + '" value="' + mySelect.options[j].value + '">';
			}
		}
		document.forms[newFormName].innerHTML += html;
	}
  </script>
  <script type="text/javascript">
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
		url:     'index.php?controller=payment_paymate&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
	  RegisterValidation();
    });
  </script>
</form>

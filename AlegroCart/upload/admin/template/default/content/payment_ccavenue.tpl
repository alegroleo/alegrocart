<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="getValues();document.getElementById('update_form').submit();;"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="global_ccavenue_status" value="">
  <input type="hidden" name="global_ccavenue_geo_zone_id" value="">
  <input type="hidden" name="global_ccavenue_merchant_id" value="">
  <input type="hidden" name="global_ccavenue_working_key" value="">
  <input type="hidden" name="global_ccavenue_sort_order" value="">
</form>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
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
<div class="heading"><?php echo $heading_payment; ?><em><?php echo $heading_title; ?></em>
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
              <td width="185" class="set"><?php echo $entry_status; ?></td>
              <td><select name="global_ccavenue_status">
                  <?php if ($global_ccavenue_status) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
		    <tr>
              <td width="185" class="set"><?php echo $entry_geo_zone; ?></td>
              <td><select name="global_ccavenue_geo_zone_id">
                  <option value="0"><?php echo $text_all_zones; ?></option>
                  <?php foreach ($geo_zones as $geo_zone) { ?>
                  <?php if ($geo_zone['geo_zone_id'] == $geo_zone_id) { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected><?php echo $geo_zone['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
			<tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_merchant_id; ?></td>
              <td><input class="validate_int" id="ccavenue_merchant_id" type="text" name="global_ccavenue_merchant_id" value="<?php echo $global_ccavenue_merchant_id; ?>">
               </td>
			   <td class="expl">
			    <?php echo $text_merchant_id; ?> 
			  </td>
            </tr>
			
			<tr>
              <td valign="top" class="set"><?php echo $entry_currency; ?></td>
              <td><select name="global_ccavenue_currency[]" multiple="multiple">
                  <?php foreach ($currencies as $currency) { ?>
                  <?php if ($currency['selected']) { ?>
                  <option value="<?php echo $currency['value']; ?>" selected><?php echo $currency['text']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $currency['value']; ?>"><?php echo $currency['text']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="expl"><?php echo $text_currency; ?><br><br><?php echo $explanation_multiselect;?></td>
            </tr>

			<tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_working_key; ?></td>
              <td><input class="validate_alpha_num" id="ccavenue_working_key" type="text" name="global_ccavenue_working_key" value="<?php echo $global_ccavenue_working_key; ?>">
               </td>
			   <td class="expl">
			    <?php echo $text_working_key; ?> 
			  </td>
            </tr>
			<tr>
              <td class="set"><?php echo $entry_sort_order; ?></td>
              <td><input class="validate_int" id="ccavenue_sort_order" type="text" name="global_ccavenue_sort_order" value="<?php echo $global_ccavenue_sort_order; ?>" size="1"></td>
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
    $(document).ready(function() {
	  RegisterValidation();
    });
  //--></script>
  <script type="text/javascript"><!--
	function getValues() {
		document.forms['update_form'].global_ccavenue_status.value=document.forms['form'].global_ccavenue_status.value;
		document.forms['update_form'].global_ccavenue_geo_zone_id.value=document.forms['form'].global_ccavenue_geo_zone_id.value;
		document.forms['update_form'].global_ccavenue_merchant_id.value=document.forms['form'].global_ccavenue_merchant_id.value;
		document.forms['update_form'].global_ccavenue_working_key.value=document.forms['form'].global_ccavenue_working_key.value;
		document.forms['update_form'].global_ccavenue_sort_order.value=document.forms['form'].global_ccavenue_sort_order.value;

		getMultipleSelection('form', 'global_ccavenue_currency[]', 'update_form');

	}
	function getMultipleSelection(formName,elementName,newFormName){ 
		var html ='';
		var mySelect = document.forms[formName].elements[elementName];
		for(j = 0; j < mySelect.options.length; j++) { 
			if(mySelect.options[j].selected) { 
				html +='<input type="hidden" name="' + elementName + '" value="' + mySelect.options[j].value + '">';
			}
		}
		document.forms[newFormName].innerHTML += html;
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
		url:     'index.php?controller=payment_ccavenue&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
</form>

<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_css("template/".$this->directory."/css/datepicker.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
  $head_def->set_admin_javascript("javascript/preview/preview.js");
  $head_def->set_admin_javascript("javascript/datepicker/datepicker.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" width=32 height=32 alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled store" onmouseover="className='hover store'" onmouseout="className='enabled store'" onclick="getTabs();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" width=32 height=32 alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <?php foreach ($coupons as $coupon) { ?>
  <input type="hidden" name="language[<?php echo $coupon['language_id']; ?>][name]" value="">
  <input type="hidden" name="language[<?php echo $coupon['language_id']; ?>][description]" value="">
  <?php } ?>
  <input type="hidden" name="code" value="">
  <input type="hidden" name="status" value="">
  <input type="hidden" name="date_start_day" value="">
  <input type="hidden" name="date_start_month" value="">
  <input type="hidden" name="date_start_year" value="">
  <input type="hidden" name="date_end_day" value="">
  <input type="hidden" name="date_end_month" value="">
  <input type="hidden" name="date_end_year" value="">
  <input type="hidden" name="discount" value="">
  <input type="hidden" name="prefix" value="">
  <input type="hidden" name="minimum_order" value="">
  <input type="hidden" name="shipping" value="">
  <input type="hidden" name="uses_total" value="">
  <input type="hidden" name="uses_customer" value="">
  <input type="hidden" name="coupon_id" id="coupon_id" value="<?php echo $coupon_id; ?>">
  </form>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" width=32 height=32 alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
  <?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" width=32 height=32 alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" width=32 height=32 alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="saveTabs();document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" width=32 height=32 alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" width=32 height=32 alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" width=32 height=32 alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
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
<div class="heading"><?php echo $heading_title; ?>
 <em></em>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" width=31 height=30 alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a><a><div class="tab_text"><?php echo $tab_data; ?></div></a><a><div class="tab_text"><?php echo $tab_validity; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div id="tabmini">
          <div class="tabs">
            <?php foreach ($coupons as $coupon) { ?>
            <a><div class="tab_text"><?php echo $coupon['language']; ?></div></a>
            <?php } ?>
          </div>
          <div class="pages">
            <?php foreach ($coupons as $coupon) { ?>
            <div class="page">
              <div class="minipad">
                <table>
                  <tr>
                    <td width="185" class="set"><span class="required">*</span> <?php echo $entry_name; ?></td>
                    <td><input name="language[<?php echo $coupon['language_id']; ?>][name]" value="<?php echo $coupon['name']; ?>">
                      <?php if (@$error_name[$coupon['language_id']]) { ?>
                      <span class="error"><?php echo $error_name[$coupon['language_id']]; ?></span>
                      <?php } ?></td>
                  </tr>
                  <tr>
                    <td valign="top" class="set"><span class="required">*</span> <?php echo $entry_description; ?></td>
                    <td><textarea name="language[<?php echo $coupon['language_id']; ?>][description]" cols="40" rows="5" id="description<?php echo $coupon['language_id']; ?>"><?php echo $coupon['description']; ?></textarea>
                      <?php if (@$error_description[$coupon['language_id']]) { ?>
                      <span class="error"><?php echo $error_description[$coupon['language_id']]; ?></span>
                      <?php } ?></td>
                  </tr>
                </table>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><span class="required">*</span> <?php echo $entry_code; ?></td>
              <td><input class="validate_alpha_num" id="code" type="text" name="code" value="<?php echo $code; ?>">
                <?php if ($error_code) { ?>
                <span class="error"><?php echo $error_code; ?></span>
                <?php } ?></td>
            </tr>
	    <tr>
              <td class="set"><?php echo $entry_status; ?></td>
              <td><select name="status">
                  <?php if ($status) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
	    <tr>
              <td class="set"><?php echo $entry_date_start; ?></td>
              <td><input class="validate_int" id="date_start_day" name="date_start_day" value="<?php echo $date_start_day; ?>" size="2" maxlength="2">
                <select name="date_start_month" id="date_start_month">
                  <?php foreach ($months as $month) { ?>
                  <?php if ($month['value'] == $date_start_month) { ?>
                  <option value="<?php echo $month['value']; ?>" selected><?php echo $month['text']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <input class="validate_int" id="date_start_year" name="date_start_year" value="<?php echo $date_start_year; ?>" size="4" maxlength="4">
                <?php if ($error_date_start) { ?>
                <span class="error"><?php echo $error_date_start; ?></span>
                <?php } elseif ($error_date) { ?>
                <span class="error"><?php echo $error_date; ?></span>
                  <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_date_end; ?></td>
              <td><input class="validate_int" id="date_end_day" name="date_end_day" value="<?php echo $date_end_day; ?>" size="2" maxlength="2">
                <select name="date_end_month" id="date_end_month">
                  <?php foreach ($months as $month) { ?>
                  <?php if ($month['value'] == $date_end_month) { ?>
                  <option value="<?php echo $month['value']; ?>" selected><?php echo $month['text']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <input class="validate_int" id="date_end_year" name="date_end_year" value="<?php echo $date_end_year; ?>" size="4" maxlength="4">
                <?php if ($error_date_end) { ?>
                <span class="error"><?php echo $error_date_end; ?></span>
                <?php } ?></td>
            </tr>
	    <tr>
	      <td colspan="2"><hr></td>
	    </tr>
            <tr>
              <td class="set"><?php echo $entry_discount; ?></td>
              <td><input class="validate_float" id="discount" type="text" name="discount" id="discount" value="<?php echo $discount; ?>" onchange="check_discount()"></td>
			  <td><span id="error_discount"></span></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_prefix; ?></td>
              <td><select name="prefix" id="prefix" onchange="check_discount()">
                  <?php if ($prefix != '-') { ?>
                  <option value="%" selected><?php echo $text_percent; ?></option>
                  <option value="-"><?php echo $text_minus; ?></option>
                  <?php } else { ?>
                  <option value="%"><?php echo $text_percent; ?></option>
                  <option value="-" selected><?php echo $text_minus; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
			<tr>
			  <td class="set"><?php echo $entry_minimum_order; ?></td>
			  <td><input class="validate_float" id="minimum_order" type="text" name="minimum_order" value="<?php echo $minimum_order; ?>"></td>
			</tr>
            <tr>
              <td class="set"><?php echo $entry_shipping; ?></td>
              <td><?php if ($shipping) { ?>
                <input type="radio" name="shipping" value="1" checked id="yes">
                <label for="yes"><?php echo $text_yes; ?></label>
                <input type="radio" name="shipping" value="0" id="no">
                <label for="no"> <?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="shipping" value="1" id="yes">
                <label for="yes"><?php echo $text_yes; ?></label>
                <input type="radio" name="shipping" value="0" checked id="no">
                <label for="no"><?php echo $text_no; ?></label>
                <?php } ?></td>
            </tr>
	    <tr>
	      <td colspan="2"><hr></td>
	    </tr>
            <tr>
              <td class="set"><?php echo $entry_uses_total; ?></td>
              <td><input class="validate_int" id="uses_total" type="text" name="uses_total" value="<?php echo $uses_total; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_uses_customer; ?></td>
              <td><input class="validate_int" id="uses_customer" type="text" name="uses_customer" value="<?php echo $uses_customer; ?>"></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
	    <tr>
              <td valign="top" class="set"><?php echo $entry_product; ?></td>
              <td><select id="image_to_preview" name="product[]" multiple="multiple" size="15">
                  <?php foreach ($products as $product) { ?>
                  <?php if ($product['coupon_id']) { ?>
                  <option title="<?php echo $product['previewimage']; ?>" value="<?php echo $product['product_id']; ?>" selected><?php echo $product['name']; ?></option>
                  <?php } else { ?>
                  <option title="<?php echo $product['previewimage']; ?>" value="<?php echo $product['product_id']; ?>"><?php echo $product['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
	      <td valign="top" class="expl"><?php echo $explanation_entry_product; ?><br><br><?php echo $explanation_multiselect;?></td>
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
  tabview_initialize('tabmini');
  </script>
  <script type="text/javascript"> 
  function check_discount(){
	let Discount = $('#discount').val();
	let Prefix = $('#prefix').val();
	if (Prefix == "%" && Discount > 100){
	  $('#discount').val(100);
	  $('#error_discount').text('<?php echo $error_discount;?>');
	} else
	  $('#error_discount').text('');
  }
  </script>
  <script type="text/javascript">
    $('input[name="language[1][name]"]').change(function () {
      let value = $(this).val();
      $(".heading em").text(value);
    }).change();
  </script>
  <script type="text/javascript">
    datePickerController.createDatePicker({
    formElements:{"date_start_year":"%Y","date_start_month":"%n", "date_start_day":"%d"},
    showWeeks:true,
    statusFormat:"%l, %d %F %Y"
    });
  </script>
  <script type="text/javascript">
    datePickerController.createDatePicker({
    formElements:{"date_end_year":"%Y","date_end_month":"%n", "date_end_day":"%d"},
    showWeeks:true,
    statusFormat:"%l, %d %F %Y"
    });
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
		url:     'index.php?controller=coupon&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  </script>
  <script type="text/javascript">
	function saveTabs() {
	let activeTab = $('#tab > .tabs > a.active').index()+1;
	let activeTabmini = $('#tabmini > .tabs > a:visible.active').index()+1;
	let id = $('#coupon_id').val();
	let data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini, 'id':id};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=coupon&action=tab',
		data: data_json,
		dataType:'json'
	});
	}
	function getTabs() {
	let activeTab = $('#tab > .tabs > a.active').index()+1;
	let activeTabmini = $('#tabmini > .tabs > a:visible.active').index()+1;
	let id = $('#coupon_id').val();
	let data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini, 'id':id};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=coupon&action=tab',
		data: data_json,
		dataType:'json',
		success: function (data) {
				if (data.status===true) {
					getValues();
					document.getElementById('update_form').submit();
				} else {
					$('<div class="warning"><?php echo $error_update; ?></div>').insertBefore(".heading");
				}
		}
	});
	}

	function getValues() {
		<?php foreach ($coupons as $coupon) { ?>
			document.forms['update_form'].elements['language[<?php echo $coupon['language_id']; ?>][name]'].value=document.forms['form'].elements['language[<?php echo $coupon['language_id']; ?>][name]'].value;
			document.forms['update_form'].elements['language[<?php echo $coupon['language_id']; ?>][description]'].value=document.forms['form'].elements['language[<?php echo $coupon['language_id']; ?>][description]'].value;
		<?php } ?>
		document.forms['update_form'].code.value=document.forms['form'].code.value;
		document.forms['update_form'].status.value=document.forms['form'].status.value;
		document.forms['update_form'].date_start_day.value=document.forms['form'].date_start_day.value;
		document.forms['update_form'].date_start_month.value=document.forms['form'].date_start_month.value;
		document.forms['update_form'].date_start_year.value=document.forms['form'].date_start_year.value;
		document.forms['update_form'].date_end_day.value=document.forms['form'].date_end_day.value;
		document.forms['update_form'].date_end_month.value=document.forms['form'].date_end_month.value;
		document.forms['update_form'].date_end_year.value=document.forms['form'].date_end_year.value;
		document.forms['update_form'].discount.value=document.forms['form'].discount.value;
		document.forms['update_form'].prefix.value=document.forms['form'].prefix.value;
		document.forms['update_form'].minimum_order.value=document.forms['form'].minimum_order.value;
		document.forms['update_form'].shipping.value=document.forms['form'].shipping.value;
		document.forms['update_form'].uses_total.value=document.forms['form'].uses_total.value;
		document.forms['update_form'].uses_customer.value=document.forms['form'].uses_customer.value;

		getMultipleSelection('form', 'product[]', 'update_form');

	}
	function getMultipleSelection(formName,elementName,newFormName){ 
		let html ='';
		let mySelect = document.forms[formName].elements[elementName];
		let mySelectLength = mySelect.options.length;
		for(let j = 0; j < mySelectLength; j++) { 
			if(mySelect.options[j].selected) { 
				html +='<input type="hidden" name="' + elementName + '" value="' + mySelect.options[j].value + '">';
			}
		}
		document.forms[newFormName].innerHTML += html;
	}
  </script>
  <script type="text/javascript">
  $(document).ready(function() {
	if (<?php echo $tab; ?>!=undefined && <?php echo $tab; ?> > 0) {
		tabview_switch('tab', <?php echo $tab; ?>);
		if (<?php echo $tabmini; ?>!=undefined && <?php echo $tabmini; ?> > 0) {
			tabview_switch('tabmini', <?php echo $tabmini; ?>);
		}
	}
   });
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
	  RegisterValidation();
    });
  </script>
</form>

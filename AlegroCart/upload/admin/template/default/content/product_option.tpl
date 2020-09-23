<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" width=32 height=32 alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled store" onmouseover="className='hover store'" onmouseout="className='enabled store'" onclick="getValues();document.getElementById('update_form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" width=32 height=32 alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="option" value="">
  <input type="hidden" name="option_name" value="">
  <input type="hidden" name="prefix" value="">
  <input type="hidden" name="price" value="">
  <input type="hidden" name="option_weightclass_id" value="">
  <input type="hidden" name="option_weight" value="">
  <input type="hidden" name="sort_order" value="">
  <input type="hidden" name="product_to_option_id" id="product_to_option_id" value="<?php echo $product_to_option_id; ?>">
  </form>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" width=32 height=32 alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
  <?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" width=32 height=32 alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" width=32 height=32 alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" width=32 height=32 alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
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
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><?php echo $entry_option; ?></td>
              <td><select name="option" id="productoption">
                  <?php foreach ($options as $option) { ?>
                  <optgroup label="<?php echo $option['name']; ?>">
                  <?php foreach ($option['value'] as $option_value) { ?>
                  <?php if ($option_value['option_value_id'] == $option_value_id) { ?>
                  <option value="<?php echo $option_value['option_value_id']; ?>" selected="selected"><?php echo $option_value['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                  </optgroup>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_prefix; ?></td>
              <td><select name="prefix">
                  <?php if ($prefix == '+') { ?>
                  <option value="+" selected><?php echo $text_plus; ?></option>
                  <option value="-"><?php echo $text_minus; ?></option>
                  <?php } else { ?>
                  <option value="+"><?php echo $text_plus; ?></option>
                  <option value="-" selected><?php echo $text_minus; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_price; ?></td>
              <td><input type="text" name="price" value="<?php echo $price; ?>"></td>
            </tr>
			<tr><td colspan="2"><hr></td></tr>
			<tr>
              <td class="set"><?php echo $entry_weight_class; ?></td>
              <td><select name="option_weightclass_id">
                  <?php foreach ($weight_classes as $weight_class) { ?>
                  <?php if ($weight_class['weight_class_id'] == $option_weightclass_id) { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>" selected><?php echo $weight_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
			
			<tr>
			  <td class="set"><?php echo $entry_option_weight; ?></td>
			  <td><input type="text" name="option_weight" value="<?php echo $option_weight; ?>"></td>
			</tr>
			<tr><td colspan="2"><hr></td></tr>
            <tr>
              <td class="set"><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1"></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="option_name" value="">
  <script type="text/javascript">
  tabview_initialize('tab');
  </script>
  <script type="text/javascript">
  $(function(){
	$('#productoption').change(function(){
	var poselected = $('#productoption option:selected').text();
	$(".heading em").text("<?php echo $product_name;?>"+" : "+ poselected);
	$('input[name=option_name]').val(poselected);
  }).change();
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
		url:     'index.php?controller=product_option&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  </script>
  <script type="text/javascript">
	function getValues() {
		document.forms['update_form'].option.value=document.forms['form'].option.value;
		document.forms['update_form'].prefix.value=document.forms['form'].prefix.value;
		document.forms['update_form'].price.value=document.forms['form'].price.value;
		document.forms['update_form'].option_weightclass_id.value=document.forms['form'].option_weightclass_id.value;
		document.forms['update_form'].option_weight.value=document.forms['form'].option_weight.value;
		document.forms['update_form'].sort_order.value=document.forms['form'].sort_order.value;
	}
  </script>
</form>

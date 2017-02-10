<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled store" onmouseover="className='hover store'" onmouseout="className='enabled store'" onclick="getValues();document.getElementById('update_form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="model_number" value="">
  <input type="hidden" name="quantity" value="">
  <input type="hidden" name="encoding" value="">
  <input type="hidden" name="barcode" value="">
  <input type="hidden" name="type_id" value="">
  <input type="hidden" name="image_id" value="">
  <input type="hidden" name="dimension_id" value="">
  <input type="hidden" name="product_id" id="product_id" value="<?php echo $product_id; ?>">
  <input type="hidden" name="product_option" id="product_option" value="<?php echo $product_option; ?>">
  <input type="hidden" name="no_image_id" value="<?php echo $no_image_id; ?>">
  </form>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
<?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <?php if (@$productwo_id) {?>
    <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <?php } else { ?>
	<div class="disabled"><img src="template/<?php echo $this->directory?>/image/save_disabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <?php } ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <?php if (@$productwo_id) {?>
    <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
  <?php } else { ?>
    <div class="disabled"><img src="template/<?php echo $this->directory?>/image/cancel_disabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
  <?php } ?>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?>
 <?php if($productwo_id){ ?> <em><?php echo $product_name;?></em><?php } ?>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css">
<script type="text/javascript" src="javascript/ajax/jquery.js"></script>
<script type="text/javascript" src="javascript/ajax/validateforms.js"></script>
<script type="text/javascript" src="javascript/preview/preview.js"></script>
<?php if(!$productwo_id){ ?>
  <form action="<?php echo $action_product; ?>" method="post" enctype="multipart/form-data">
	<table style="width: 100%;"><tr><td><hr></td></tr></table>
	<table>
	  <tr>
		<td><?php echo $entry_select_product;?></td>
		<td><select name="productwo_id" onchange="this.form.submit();">
			<option value=""><?php echo $text_select;?></option>
			<?php foreach($products as $product){?>
			  <option value="<?php echo $product['product_id'];?>"><?php echo $product['product_name'];?></option>
			<?php }?>
		</select></td>
	  </tr>
	</table>
  </form>
<?php } else { ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="form>
    <table style="width: 100%;"><tr><td><hr></td></tr></table>
	<table>
	  <tr><td style="width: 185px;" class="set"><?php echo $entry_model_number;?></td>
	    <td><input class="validate_alpha_num" id="model_number" Type="text" size="32" maxlength="32" name="model_number" value="<?php echo $model_number;?>"></td>
	  </tr>
	  <tr><td style="width: 185px;" class="set"><?php echo $entry_quantity;?></td>
	    <td><input class="validate_int" id="quantity" type="text" size="6" name="quantity" value="<?php echo $quantity;?>"></td>
	  </tr>
	  <input id="product_option" type="hidden" name="product_option" value="<?php echo $product_option;?>">
	  <input id="product_id" type="hidden" name="product_id" value="<?php echo $product_id;?>">
	</table>
	<table>
	  <tr><td colspan="2"><hr></td></tr>
	  <tr>
              <td style="width: 185px;" class="set"><?php echo $entry_barcode_encoding; ?></td>
              <td><select id="encoding" name="encoding" onchange="$('#barcode').val('')">
                  <?php if ($encoding == 'upc') { ?>
                  <option value="upc" selected><?php echo $text_upc; ?></option>
                  <option value="ean"><?php echo $text_ean; ?></option>
                  <?php } else { ?>
                  <option value="upc"><?php echo $text_upc; ?></option>
                  <option value="ean" selected><?php echo $text_ean; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
	    <tr>
              <td style="width: 185px;" class="set"><?php echo $entry_barcode; ?></td>
              <td id="barcodefield"><input class="validate_int" id="barcode" type="text" size="14" maxlength="13" name="barcode" value="<?php echo $barcode; ?>" onchange="validate_barcode()">
			</td>
        </tr>
	</table>
	<table>
	  <tr><td colspan="2"><hr></td></tr>
	  <tr>
		<td style="width: 185px;" class="set"><?php echo $entry_dimension_class;?></td>
		<td><select id="type_id" name="type_id" onchange="$('#dimensions').load('index.php?controller=products_with_options&action=dimensions&type_id='+this.value);">
		  <?php foreach($types as $type){?>
			 <option value="<?php echo $type['type_id'];?>"<?php if($type['type_id'] == $type_id){echo ' selected';}?>><?php echo $type['type_text'];?></option>
		  <?php }?>
		</select></td>
	  </tr>
	</table>
	<table id="dimensions">
	  <?php echo $dimensions;?>
	</table>
	<table style="width: 100%;"><tr><td><hr></td></tr></table>
	<table>
	  <tr>
        <td width="185px" class="set"><?php echo $entry_image; ?></td>
        <td><select name="image_id" id="image_id" onchange="$('#image').load('index.php?controller=image&action=view&image_id='+this.value);">
		  <option title="<?php echo $no_image_filename; ?>" value="<?php echo $no_image_id;?>"<?php if($image_id == '0'){ echo ' selected';}?>><?php echo $text_no_image;?></option>
          <?php foreach ($images as $image) { ?>
		    <?php if ($image['image_id'] != $no_image_id){?>
			  <option title="<?php echo $image['previewimage']; ?>" value="<?php echo $image['image_id']; ?>"<?php if ($image['image_id'] == $image_id) {echo ' selected';} ?>><?php echo $image['title']; ?></option>
			<?php }?>
          <?php } ?>
        </select></td>
      </tr>
      <tr>
        <td></td>
        <td id="image"></td>
      </tr>
	</table>
	<input type="hidden" name="no_image_id" value="<?php echo $no_image_id;?>">
	<input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  </form>
<?php }?>
<script type="text/javascript"><!--
  function validate_barcode(){
	var Encoding = $('#encoding').val();
	var Barcode = $('#barcode').val();
	var ProductID = $('#product_id').val();
	var OptionID = $('#product_option').val();
	
	if(Barcode > 0){
		$.ajax({
			type:    'GET',
			url:
			'index.php?controller=products_with_options&action=validate_barcode&encoding='+Encoding+'&barcode='+Barcode+'&product_id='+ProductID+'&option_id='+OptionID,
			async:   false,
			success: function(Barcode_data) {
     		$('#barcodefield').html(Barcode_data);
			}
		});
	}
  }
  //--></script> 
  <script type="text/javascript"><!--
  $('#image').load('index.php?controller=image&action=view&image_id='+document.getElementById('image_id').value);
  //--></script>
  <script type="text/javascript"><!--
    $(document).ready(function() {
	  RegisterValidation();
    });
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
		url:     'index.php?controller=products_with_options&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
  <script type="text/javascript"><!--
	function getValues() {
		document.forms['update_form'].model_number.value=document.forms['form'].model_number.value;
		document.forms['update_form'].quantity.value=document.forms['form'].quantity.value;
		document.forms['update_form'].encoding.value=document.forms['form'].encoding.value;
		document.forms['update_form'].barcode.value=document.forms['form'].barcode.value;
		document.forms['update_form'].type_id.value=document.forms['form'].type_id.value;
		document.forms['update_form'].image_id.value=document.forms['form'].image_id.value;
		document.forms['update_form'].dimension_id.value=document.forms['form'].dimension_id.value;

		getDimensionValues('form', 'dimension_value', 'update_form');

	}
	function getDimensionValues(formName,elementName,newFormName){ 
		var html ='';
		var len = $('[id^='+ elementName + ']').length;
		for(j = 0; j < len; j++) { 
				html +='<input type="hidden" name="' + elementName + '[' + j + ']" value="' + document.forms[formName].elements[elementName + '[' + j +']'].value + '">';
		}
		document.forms[newFormName].innerHTML += html;
	}
  //--></script>

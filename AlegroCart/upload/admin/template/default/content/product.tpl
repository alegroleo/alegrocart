<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_css("template/".$this->directory."/css/datepicker.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
  $head_def->set_admin_javascript("javascript/ckeditor/ckeditor.js");
  $head_def->set_admin_javascript("javascript/preview/preview.js");
  $head_def->set_admin_javascript("javascript/ajax/tooltip.js");
  $head_def->set_admin_javascript("javascript/datepicker/datepicker.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled store" onmouseover="className='hover store'" onmouseout="className='enabled store'" onclick="getTabs();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="status" value="">
  <input type="hidden" name="date_available_day" value="">
  <input type="hidden" name="date_available_month" value="">
  <input type="hidden" name="date_available_year" value="">
  <input type="hidden" name="quantity" value="">
  <input type="hidden" name="min_qty" value="">
  <input type="hidden" name="max_qty" value="">
  <input type="hidden" name="multiple" value="">
  <?php if(!$option_status){?>
    <input type="hidden" name="encoding" value="">
    <input type="hidden" name="barcode" value="">
  <?php } ?>
  <input type="hidden" name="manufacturer_id" value="">
  <input type="hidden" name="vendor_id" value="">
  <input type="hidden" name="shipping" value="">
  <input type="hidden" name="shipping_time_from" value="">
  <input type="hidden" name="shipping_time_to" value="">
  <input type="hidden" name="tax_class_id" value="">
  <input type="hidden" name="price" value="">
  <input type="hidden" name="sort_order" value="">
  <input type="hidden" name="weight_class_id" value="">
  <input type="hidden" name="weight" value="">
  <input type="hidden" name="type_id" value="">
  <input type="hidden" name="dimension_id" value="">
  <input type="hidden" name="image_id" value="">
  <input type="hidden" name="featured" value="">
  <input type="hidden" name="special_offer" value="">
  <input type="hidden" name="related" value="">
  <input type="hidden" name="special_price" value="">
  <input type="hidden" name="special_discount" value="">
  <input type="hidden" name="start_date_day" value="">
  <input type="hidden" name="start_date_month" value="">
  <input type="hidden" name="start_date_year" value="">
  <input type="hidden" name="end_date_day" value="">
  <input type="hidden" name="end_date_month" value="">
  <input type="hidden" name="end_date_year" value="">
  <input type="hidden" name="remaining" value="">
  <?php foreach ($products as $product) { ?>
    <input type="hidden" name="name[<?php echo $product['language_id']; ?>]" value="">
    <input type="hidden" name="model_number[<?php echo $product['language_id']; ?>]" value="">
    <input type="hidden" name="model[<?php echo $product['language_id']; ?>]" value="">
    <input type="hidden" name="description[<?php echo $product['language_id']; ?>]" value="">
    <input type="hidden" name="meta_title[<?php echo $product['language_id']; ?>]" value="">
    <input type="hidden" name="meta_description[<?php echo $product['language_id']; ?>]" value="">
    <input type="hidden" name="meta_keywords[<?php echo $product['language_id']; ?>]" value="">
    <input type="hidden" name="alt_description[<?php echo $product['language_id']; ?>]" value="">
    <input type="hidden" name="technical_name[<?php echo $product['language_id']; ?>]" value="">
    <input type="hidden" name="technical[<?php echo $product['language_id']; ?>]" value="">
  <?php } ?>
  <?php if($product_options){?>
  <?php $option_rows = 0;?>
    <?php foreach($product_options as $key => $product_option){?>
    <input type="hidden" name="product_options[<?php echo $option_rows;?>][product_option]" value="">
    <input type="hidden" name="product_options[<?php echo $option_rows;?>][option_name]" value="">
    <input type="hidden" name="product_options[<?php echo $option_rows;?>][model_number]" value="">
    <input type="hidden" name="product_options[<?php echo $option_rows;?>][encoding]" value="">
    <input type="hidden" name="product_options[<?php echo $option_rows;?>][barcode]" value="">
    <input type="hidden" name="product_options[<?php echo $option_rows;?>][quantity]" value="">
    <input type="hidden" name="product_options[<?php echo $option_rows;?>][product_id]" value="">
    <input type="hidden" name="product_options[<?php echo $option_rows;?>][image_id]" value="">
    <input type="hidden" name="product_options[<?php echo $option_rows;?>][dimension_id]" value="">
    <input type="hidden" name="product_options[<?php echo $option_rows;?>][dimension_value]" value="">
    <?php ++$option_rows;?>
    <?php }?>
  <?php }?>
  <input type="hidden" name="product_id" id="product_id" value="<?php echo $product_id; ?>">
  </form>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
<?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="saveTabs;document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" alt="<?php echo $button_print; ?>" class="png"><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
<?php if (@$last) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $last; ?>'"><img src="template/<?php echo $this->directory?>/image/last_enabled.png" alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/last_disabled.png" alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
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
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a><a><div class="tab_text"><?php echo $tab_data; ?></div></a><?php if($product_options){echo '<a><div class="tab_text">' . $tab_product_options . '</div></a>';}?><a><div class="tab_text"><?php echo $tab_image; ?></div></a><a><div class="tab_text"><?php echo $tab_download; ?></div></a><a><div class="tab_text"><?php echo $tab_category; ?></div></a><a><div class="tab_text"><?php echo $tab_home; ?></div></a><a href="#discount"><div class="tab_text"><?php echo $tab_discount; ?></div></a><a><div class="tab_text"><?php echo $tab_dated_special; ?></div></a><a><div class="tab_text"><?php echo $tab_alt_description; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div id="tabmini">
          <div class="tabs">
            <?php foreach ($products as $product) { ?>
            <a><div class="tab_text"><?php echo $product['language']; ?></div></a>
            <?php } ?>
          </div>
          <div class="pages">
            <?php foreach ($products as $product) { ?>
            <div class="page">
              <div class="minipad">
                <table>
                  <tr>
                    <td style="width: 185px;" class="set"><span class="required">*</span> <?php echo $entry_name; ?></td>
                    <td style="width: 265px;"><input name="name[<?php echo $product['language_id']; ?>]" value="<?php echo $product['name']; ?>" size="32">
                      <?php if (@$error_name[$product['language_id']]) { ?>
                      <span class="error"><?php echo $error_name[$product['language_id']]; ?></span>
                      <?php } ?>
					 <?php if (@$error_duplicate_name[$product['language_id']]) { ?>
                      <span class="error"><?php echo $error_duplicate_name[$product['language_id']]; ?></span>
                      <?php } ?>
					</td>
					<td class="expl"><?php echo $text_unique; ?></td>
                  </tr>
				  <tr>
				    <td style="width: 185px;" class="set"><?php echo $entry_model_number; ?></td>
					<td style="width: 265px;"><input class="validate_alpha_num" id="model_number<?php echo $product['language_id']; ?>" size="32" maxlength="32" <?php if($option_status) echo 'readonly="readonly" '; ?>name="model_number[<?php echo $product['language_id']; ?>]" value="<?php echo $product['model_number']; ?>"></td>
					<?php if($option_status) {?>
					<td style="color:red;padding-left:10px;"><b ><?php echo $text_model_options;?></b></td>
			  <?php }?>
				  </tr>
				  <tr>
					<td style="width: 185px;" class="set"><?php echo $entry_model; ?></td>
					<td><input size="32" maxlength="32" id="model<?php echo $product['language_id']; ?>" name="model[<?php echo $product['language_id']; ?>]" value="<?php echo $product['model']; ?>"></td>
					<td class="expl"><?php echo $text_model; ?></td>
				  </tr>	
				  <tr>
					<td style="width: 185px;" class="set"></td>
					<td>
					  <select id="model_data<?php echo $product['language_id']; ?>" name="model_data<?php echo $product['language_id']; ?>" onchange="$('#model<?php echo $product['language_id']; ?>').val(this.value)">
						<?php if(!$product['model']){?>
							<option value="" selected><?php echo $select_model;?></option><?php }?>
						<?php foreach($product['models_data'] as $model_data){?>
						  <?php if ($model_data['model'] == $product['model']){?>
							<option value="<?php echo $model_data['model']; ?>" selected><?php echo $model_data['model']; ?></option>
						  <?php } else { ?>
							<option value="<?php echo $model_data['model']; ?>"><?php echo $model_data['model']; ?></option>
						  <?php } ?>
						<?php } ?>
					  </select>
					</td>
					<td class="expl"><?php echo $text_model_select; ?></td>
				  </tr>
				</table>
				<table>
                  <tr>
                    <td style="vertical-align: top; width: 185px" class="set"><?php echo $entry_description; ?></td>
                    <td><textarea name="description[<?php echo $product['language_id']; ?>]" id="description<?php echo $product['language_id']; ?>"><?php echo $product['description']; ?></textarea></td>
                  </tr>
                  <tr>
<td></td>
			<td class="expl"><?php echo $explanation_description; ?></td>	
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
              <td class="set"><?php echo $entry_status; ?></td>
              <td><select name="status">
                  <?php if ($status == '1') { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
			<tr>
              <td class="set"><?php echo $entry_date_available; ?></td>
              <td><input class="validate_int" id="date_available_day" name="date_available_day" value="<?php echo $date_available_day; ?>" size="2" maxlength="2">
                <select name="date_available_month" id="date_available_month">
                  <?php foreach ($months as $month) { ?>
                  <?php if ($month['value'] == $date_available_month) { ?>
                  <option value="<?php echo $month['value']; ?>" selected><?php echo $month['text']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <input class="validate_int" id="date_available_year" name="date_available_year" value="<?php echo $date_available_year; ?>" size="4" maxlength="4">
                <?php if ($error_date_available) { ?>
                <span class="error"><?php echo $error_date_available; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_quantity; ?></td>
              <td><input class="validate_int" type="text" <?php if($option_status) echo 'readonly="readonly" '; ?>name="quantity" id="quantity" value="<?php echo $quantity; ?>" size="2"></td>
			  <?php if($option_status) {?>
			    <td><b style="color:red;padding-left:10px;"><?php echo $text_quantity_options;?></b></td>
			  <?php }?>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_min_qty; ?></td>
              <td><input class="validate_int" name="min_qty" id="min_qty" value="<?php echo $min_qty; ?>" size="2"></td>
	      <td class="expl"><?php echo $explanation_min_qty; ?></td>	
            </tr>
	    <tr>
              <td class="set"><?php echo $entry_max_qty; ?></td>
              <td><input class="validate_int" name="max_qty" id="max_qty" value="<?php echo $max_qty; ?>" size="2">
		<?php if ($error_max_qty) { ?>
		<span class="error"><?php echo $error_max_qty; ?></span>
		<?php } ?></td>
	      <td class="expl"><?php echo $explanation_max_qty; ?></td>	
            </tr>
	    <tr>
              <td class="set"><?php echo $entry_multiple; ?></td>
              <td><input class="validate_int" name="multiple" id="multiple" value="<?php echo $multiple; ?>" size="2">
		<?php if ($error_multiple) { ?>
		<span class="error"><?php echo $error_multiple; ?></span>
		<?php } ?></td>
	      <td class="expl"><?php echo $explanation_multiple; ?></td
	    <tr><td colspan="2"><hr></td></tr>
	    <tr>
              <td class="set"><?php echo $entry_barcode_encoding; ?></td>
              <td><select id="encoding_9999" name="encoding" onchange="$('#barcode_9999').val('')">
                  <?php if ($encoding == 'upc') { ?>
                  <option value="upc" selected><?php echo $text_upc; ?></option>
                  <option value="ean"><?php echo $text_ean; ?></option>
                  <?php } else { ?>
                  <option value="upc"><?php echo $text_upc; ?></option>
                  <option value="ean" selected><?php echo $text_ean; ?></option>
                  <?php } ?>
                </select></td>
             <td class="expl"><?php if($option_status) {?><b style="color:red;"><?php echo $text_barcode_options;?></b></br><?php }?><?php echo $text_barcode_enc_explanation; ?></td>
	    </tr>
	    <tr>
           <td class="set"><?php echo $entry_barcode; ?></td>
              <td id="barcodefield_9999"><input class="validate_int" id="barcode_9999" type="text" size="14" maxlength="15" <?php if($option_status) echo 'readonly="readonly" '; ?>name="barcode" value="<?php echo $barcode; ?>" onchange="validate_barcode('9999')">
			</td>
	      <td class="expl"><?php if($option_status) {?><b style="color:red;"><?php echo $text_barcode_options;?></b></br><?php }?><?php echo $text_barcode_explanation; ?></td>
        </tr>
	    <tr><td colspan="2"><hr></td></tr>
	     <tr>
              <td class="set"><?php echo $entry_manufacturer; ?></td>
              <td><select id="manufacturer_id" name="manufacturer_id">
                  <option value="0" selected><?php echo $text_none; ?></option>
                  <?php foreach ($manufacturers as $manufacturer) { ?>
                  <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
                  <option title="<?php echo $manufacturer['previewimage']; ?>" value="<?php echo $manufacturer['manufacturer_id']; ?>" selected><?php echo $manufacturer['name']; ?></option>
                  <?php } else { ?>
                  <option title="<?php echo $manufacturer['previewimage']; ?>" value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
	     <tr>
              <td class="set"><?php echo $entry_vendor; ?></td>
              <td><select id="vendor_id" name="vendor_id">
                  <option value="0" selected><?php echo $text_none; ?></option>
                  <?php foreach ($vendors as $vendor) { ?>
                  <?php if ($vendor['vendor_id'] == $vendor_id) { ?>
                  <option title="<?php echo $vendor['previewimage']; ?>" value="<?php echo $vendor['vendor_id']; ?>" selected><?php echo $vendor['name']; ?></option>
                  <?php } else { ?>
                  <option title="<?php echo $vendor['previewimage']; ?>" value="<?php echo $vendor['vendor_id']; ?>"><?php echo $vendor['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_shipping; ?></td>
              <td><?php if ($shipping == 1) { ?>
                <input type="radio" name="shipping" value="1" checked="checked" id="syes">
                <label for="syes"><?php echo $text_yes; ?></label>
                <input type="radio" name="shipping" value="0" id="sno">
                <label for="sno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="shipping" value="1" id="syes">
                <label for="syes"><?php echo $text_yes; ?></label>
                <input type="radio" name="shipping" value="0" checked="checked" id="sno">
                <label for="sno"><?php echo $text_no; ?></label>
                <?php } ?></td>
            </tr>
	    <tr>
              <td class="set"><?php echo $entry_shipping_time; ?></td>
              <td><input class="validate_int" id="shipping_time_from" name="shipping_time_from" value="<?php echo $shipping_time_from; ?>" size="1" id="shipping_time_from" disabled="disabled"> - <input class="validate_int" name="shipping_time_to" value="<?php echo $shipping_time_to; ?>" size="1" id="shipping_time_to" disabled="disabled"></td>
	      <td class="expl"><?php echo $explanation_shipping_time; ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_tax_class; ?></td>
              <td><select name="tax_class_id">
		  <option value="0" selected><?php echo $text_none; ?></option>
                  <?php foreach ($tax_classes as $tax_class) { ?>
                  <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>" selected><?php echo $tax_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_price; ?></td>
              <td><input class="validate_float" name="price" id="price" value="<?php echo $price; ?>" onchange="price_update()"></td>
			  <td class="expl"><?php echo 'Currency : '. $currency_code;?>
			  <input type="hidden" id="decimal_place" value="<?php echo $decimal_place;?>">
			  </td>
            </tr>
	    <tr>
              <td class="set"><?php echo $entry_sort_order; ?></td>
              <td><input class="validate_int" id="sort_order" name="sort_order" value="<?php echo $sort_order; ?>" size="1"></td>
            </tr>
        	<tr><td colspan="2"><hr></td></tr>
            <tr>
              <td class="set"><?php echo $entry_weight_class; ?></td>
              <td><select name="weight_class_id">
                  <?php foreach ($weight_classes as $weight_class) { ?>
                  <?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>" selected><?php echo $weight_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_weight; ?></td>
              <td><input class="validate_float" id="weight" name="weight" value="<?php echo $weight; ?>"></td>
            </tr>
			<tr><td colspan="2"><hr></td></tr>
			<tr>
			 <td class="set"><?php echo $entry_dimension_class;?></td>
			 <td><select id="type_id" name="type_id" onchange="$('#dimensions').load('index.php?controller=product&action=dimensions&type_id='+this.value);">
				<?php foreach($types as $type){?>
				  <option value="<?php echo $type['type_id'];?>"<?php if($type['type_id'] == $type_id){echo ' selected';}?>><?php echo $type['type_text'];?></option>
				<?php }?>
			  </select></td>
			</tr>
          </table>
		  <table id="dimensions">
			<?php echo $dimensions;?>
		  </table>
        </div>
      </div>
	  <?php if($product_options){?>
	  <div class="page">
        <div class="pad">
	      <table>
		    <tr>
			  <script type="text/javascript">
			    $(document).ready(function(){
				  $('.optionE[title]').tooltip({
				  offset: [200,100], tipClass: 'tooltip_white'});
				});
			  </script>
			  <?php echo '<th style="color:red"><div title="' . $text_option_explantion . '" class="optionE" >'. $text_option_info . '</div></th>';?>
			</tr>
			</table>
			<table class="list">
			<tr>
			  <th align="left"><?php echo $entry_product_option;?></th>
			  <th align="left"><?php echo $option_names ;?></th>
			  <th align="left"><?php echo $entry_model_numbers; ?></th>
			  <th align="left"><?php echo $entry_po_barcode_encoding;?></th>
			  <th align="left"><?php echo $entry_po_barcode;?></th>
			  <th align="left"><?php echo $entry_po_quantity;?></th>
			</tr>
			<?php $option_rows = 0;?>
			<?php foreach($product_options as $key => $product_option){?>
			  <tr <?php if($option_rows%2){ echo 'class="row1"'; } else { echo 'class="row2"'; }?>>
				<td><?php echo $product_option['product_option'];?></td>
				<input id="option_id_<?php echo $option_rows;?>" type="hidden" name="product_options[<?php echo $option_rows;?>][product_option]" value="<?php echo $product_option['product_option'];?>">
				<td><?php echo $product_option['option_name'];?></td>
				<input type="hidden" name="product_options[<?php echo $option_rows;?>][option_name]" value="<?php echo $product_option['option_name'];?>">
				<td><input type="text" class="validate_alpha_num" id="product_options_model_number<?php echo $option_rows;?>" size="32" maxlength="32" name="product_options[<?php echo $option_rows;?>][model_number]" value="<?php echo $product_option['model_number'];?>"></td>
				<td><select id="encoding_<?php echo $option_rows;?>" name="product_options[<?php echo $option_rows;?>][encoding]" onchange="$('#barcode_<?php echo $option_rows;?>').val('')">
				  <?php if ($product_option['encoding'] == 'upc') { ?>
				    <option value="upc" selected><?php echo $text_upc; ?></option>
				    <option value="ean"><?php echo $text_ean; ?></option>
				  <?php } else { ?>
				    <option value="upc"><?php echo $text_upc; ?></option>
				    <option value="ean" selected><?php echo $text_ean; ?></option>
				  <?php } ?>
			    </select></td>
				<td id="barcodefield_<?php echo $option_rows;?>">
					<input class="validate_int" id="barcode_<?php echo $option_rows;?>" type="text" size="14" maxlength="15" name="product_options[<?php echo $option_rows;?>][barcode]" value="<?php echo $product_option['barcode'];?>" onchange="validate_barcode('<?php echo $option_rows;?>')">
					
				    <?php if (@$error_barcode[$product_option['product_option']]) { ?>
						<span class="error"><?php echo $error_barcode[$product_option['product_option']]; ?></span>
				    <?php } ?>
				</td>
				<td><input class="validate_int" id="product_options_quantity<?php echo $option_rows;?>" type="text" size="6" name="product_options[<?php echo $option_rows;?>][quantity]" value="<?php echo $product_option['quantity'];?>"></td>
				<input type="hidden" name="product_options[<?php echo $option_rows;?>][product_id]" value="<?php echo $product_option['product_id'];?>">
				<input type="hidden" name="product_options[<?php echo $option_rows;?>][image_id]" value="<?php echo $product_option['image_id'];?>">
				<input type="hidden" name="product_options[<?php echo $option_rows;?>][dimension_id]" value="<?php echo $product_option['dimension_id'];?>">
				<input type="hidden" name="product_options[<?php echo $option_rows;?>][dimension_value]" value="<?php echo $product_option['dimension_value'];?>">
			  </tr>
			  <?php ++$option_rows;?>
			<?php }?>
		  </table>
	    </div>
	  </div>
	  <?php }?>
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><?php echo $entry_image; ?></td>
              <td><select name="image_id" id="image_id" onchange="$('#image').load('index.php?controller=image&action=view&image_id='+this.value);">
                  <?php foreach ($images as $image) { ?>
                  <?php if ($image['image_id'] == $image_id) { ?>
                  <option title="<?php echo $image['previewimage']; ?>" value="<?php echo $image['image_id']; ?>" selected><?php echo $image['title']; ?></option>
                  <?php } else { ?>
                  <option title="<?php echo $image['previewimage']; ?>" value="<?php echo $image['image_id']; ?>"><?php echo $image['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td></td>
              <td id="image"></td>
            </tr>
            <tr>
              <td valign="top" class="set"><?php echo $entry_images; ?></td>
              <td><select id="image_to_preview" name="image[]" multiple="multiple" size="15">
                  <?php foreach ($images as $image) { ?>
                  <?php if (!$image['product_id']) { ?>
                  <option title="<?php echo $image['previewimage']; ?>" value="<?php echo $image['image_id']; ?>"><?php echo $image['title']; ?></option>
                  <?php } else { ?>
                  <option title="<?php echo $image['previewimage']; ?>"value="<?php echo $image['image_id']; ?>" selected><?php echo $image['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
	      <td class="expl"><?php echo $explanation_multiselect_img;?></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table width="800">
            <tr>
              <td width="185" valign="top" class="set right"><?php echo $entry_download; ?></td>
              <td><select name="download[]" multiple="multiple" size="10" style="min-width:200px">
                  <?php foreach ($downloads as $download) { ?>
                  <?php if (!$download['product_id']) { ?>
                  <option value="<?php echo $download['download_id']; ?>"><?php echo $download['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $download['download_id']; ?>" selected><?php echo $download['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
	      <td width="185" valign="top" class="set right"><?php echo $entry_free_download; ?></td>
              <td><select name="fdownload[]" multiple="multiple" size="10" style="min-width:200px">
                  <?php foreach ($fdownloads as $fdownload) { ?>
                  <?php if (!$fdownload['product_id']) { ?>
                  <option value="<?php echo $fdownload['download_id']; ?>"><?php echo $fdownload['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $fdownload['download_id']; ?>" selected><?php echo $fdownload['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" valign="top" class="set"><?php echo $entry_category; ?></td>
              <td><select id="image_to_preview" name="category[]" multiple="multiple" size="15">
                  <?php foreach ($categories as $category) { ?>
                  <?php if (!$category['product_id']) { ?>
                  <option title="<?php echo $category['previewimage']; ?>" value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                  <?php } else { ?>
                  <option title="<?php echo $category['previewimage']; ?>" value="<?php echo $category['category_id']; ?>" selected><?php echo $category['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
 	      <td class="expl"><?php echo $explanation_multiselect_cat;?></td>
            </tr>
          </table>
        </div>
      </div>
      <!-- B: Latest/Featured/Specials Contrib -->
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td class="set"><?php echo $entry_featured; ?></td>
              <td><select name="featured">
                  <?php if ($featured == '1') { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_specials; ?></td>
              <td><select name="special_offer">
                  <?php if ($special_offer == '1') { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
			<!-- Related products by JT -->
			<tr>
              <td class="set"><?php echo $entry_related; ?></td>
              <td><select name="related">
                  <?php if ($related == '1') { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
			<tr>
              <td width="185" valign="top" class="set"><?php echo $entry_related; ?></td>
              <td><select id="image_to_preview" name="relateddata[]" multiple="multiple" size="15">
                  <?php foreach ($relateddata as $product) { ?>
                  <?php if (!$product['relateddata']) { ?>
                  <option title="<?php echo $product['previewimage']; ?>" value="<?php echo $product['product_id']; ?>"><?php echo $product['name']; ?></option>
                  <?php } else { ?>
                  <option title="<?php echo $product['previewimage']; ?>" value="<?php echo $product['product_id']; ?>" selected><?php echo $product['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
 	      <td class="expl"><?php echo $explanation_multiselect_pr;?></td>
            </tr>
          </table>
        </div>
      </div>
      <!-- E: Latest/Featured/Specials Contrib -->
	  <!-- S: Quantity Discounts -->
      <div id="discount" class="page">
        <div class="pad">
		  <div class="set"><?php echo $entry_regular_price;?><input type="text" id="discount_regular_price" size="11" disabled="disabled" value="<?php echo $price;?>">		  
		  <?php echo $entry_dated_special;?><input type="text" id="discount_special_price" size="11" disabled="disabled" value="<?php echo $special_price;?>"></div>		  
          <table id="discounts">
            <?php $i = 0; ?>
            <?php foreach ($product_discounts as $product_discount) { ?>
            <tr name="qty_discount_<?php echo $i; ?>" id="discount_<?php echo $i; ?>">
              <td><?php echo $entry_quantity; ?></td>
              <td><input class="validate_int" id="discount_quantity<?php echo $i;?>" type="text" name="product_discount[<?php echo $i; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" size="2"></td>
			  <td><?php echo $entry_percent_discount;?></td>
              <td><input class="validate_float" type="text" id="discount_percent<?php echo $i; ?>" name="product_discount[<?php echo $i; ?>][discount]" size="11" value="<?php echo $product_discount['discount']; ?>" onchange="quantity_percent('<?php echo $i; ?>')"></td>
              <td><?php echo $entry_discount; ?></td>
			  <?php $discountvalue = number_format($price*($product_discount['discount']/100),2); ?>
			  <td><input class="validate_float" type="text" id="discount_amount<?php echo $i;?>" size="8" value="<?php echo $discountvalue;?>" name="discount_amount<?php echo $i;?>" onchange="quantity_discount('<?php echo $i; ?>')"></td>
              <td><input type="button" class="button" value="<?php echo $button_remove; ?>" onclick="removeDiscount('discount_<?php echo $i; ?>');"></td>
            </tr>
            <?php $i++; ?>
            <?php } ?>
          </table>
          <table>
            <tr>
              <td colspan="5"><input type="button" class="button" value="<?php echo $button_add; ?>" onclick="addDiscount();"></td>
            </tr>
          </table>
		  <table><tr><td class="expl"><?php echo $entry_quantity_discount;?>
		    </td></tr></table>
        </div>
      </div>
	  <!-- E: Quantity Discounts -->
	  <!-- S: Dated Specials -->
      <div class="page">
        <div class="pad">
	      <table>
		    <tr>
			<td class="set"><?php echo $entry_regular_price;?></td>
			<td><input class="validate_float" type="text" id="regular_price" value="<?php echo $price; ?>" onchange="regular_price_update()">
			</td>
		    </tr>
		    <tr>
		      <td class="set"><?php echo $entry_dated_special; ?></td>
		      <td><input class="validate_float" id="special_price" name="special_price" value="<?php echo $special_price; ?>" onchange="calculate_percent()"></td>
		    </tr>
		    <tr>
		      <td class="set"><?php echo $entry_percent_discount;?></td>
		      <?php $special_discount = $special_price>0 ? ceil((100-(($special_price/$price)*100))*10000)/10000 : 0;?>
		      <td><input class="validate_float" id="special_discount" name="special_discount" value="<?php echo $special_discount; ?>" onchange="calculate_discount()">
		      </td>
		    </tr> 
		    <tr><td colspan="2"><hr></td></tr>
		    <tr>
			  <td class="set"><?php echo $entry_start_date; ?></td>
			  <td><input class="validate_int" id="start_date_day" name="start_date_day" value="<?php echo $start_date_day; ?>" size="2" maxlength="2">
			  <select name="start_date_month" id="start_date_month">
			    <?php if ($start_date_month == '00'){ ?>
				<option value="00" selected>00</option>
				<?php } else { ?>
				<option value="00">00</option>
				<?php };
				  foreach ($months as $month) { ?>
				    <?php if ($month['value'] == $start_date_month) { ?>
					<option value="<?php echo $month['value']; ?>" selected><?php echo $month['text']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
				    <?php } ?>
					<?php } ?>
				</select>
				<input class="validate_int" id="start_date_year" name="start_date_year" value="<?php echo $start_date_year; ?>" size="4" maxlength="4">
				<?php if ($error_start_date) { ?>
				<span class="error"><?php echo $error_start_date; ?></span>
				<?php } ?>
			  </td>
		    </tr>
		    <tr>
			  <td class="set"><?php echo $entry_end_date; ?></td>
                          <td><input class="validate_int" id="end_date_day" name="end_date_day" value="<?php echo $end_date_day; ?>" size="2" maxlength="2">
			  <select name="end_date_month" id="end_date_month">
			    <?php if ($end_date_month == '00'){ ?>
				<option value="00" selected>00</option>
				<?php } else { ?>
				<option value="00">00</option>
				<?php };
				  foreach ($months as $month) { ?>
				    <?php if ($month['value'] == $end_date_month) { ?>
					<option value="<?php echo $month['value']; ?>" selected><?php echo $month['text']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
				    <?php } ?>
					<?php } ?>
				</select>
				<input class="validate_int" id="end_date_year" name="end_date_year" value="<?php echo $end_date_year; ?>" size="4" maxlength="4">
				<?php if ($error_end_date) { ?>
				<span class="error"><?php echo $error_end_date; ?></span>
				<?php } ?>
			  </td>
		      </tr>	  
		      <tr>
			  <td class="set"><?php echo $entry_remaining; ?></td>
			  <td><?php if ($remaining == 1) { ?>
			    <input type="radio" name="remaining" value="1" checked id="yes">
			    <label for="yes"><?php echo $text_yes; ?></label>
			    <input type="radio" name="remaining" value="0" id="no">
			    <label for="no"><?php echo $text_no; ?></label>
			    <?php } else { ?>
			    <input type="radio" name="remaining" value="1" id="yes">
			    <label for="yes"><?php echo $text_yes; ?></label>
			    <input type="radio" name="remaining" value="0" checked id="no">
			    <label for="no"><?php echo $text_no; ?></label>
			    <?php } ?></td>
			</tr>    
	      </table>
	    </div>
	  </div>
	  <!-- E: Dated Specials -->
	  <!-- S: Alt Description Meta Tags -->
      <div class="page">
        <div id="tabmini2">
          <div class="tabs">
            <?php foreach ($products as $product) { ?>
            <a><div class="tab_text"><?php echo $product['language']; ?></div></a>
            <?php } ?>
          </div>
          <div class="pages">
            <?php foreach ($products as $product) { ?>
            <div class="page">
              <div class="minipad">
                <table>
                  <tr>
                    <td width="185" class="set"> <?php echo $entry_meta_title; ?></td>
                    <td><input class="validate_meta" id="meta_title<?php echo $product['language_id']; ?>" size="60" maxlength="60" name="meta_title[<?php echo $product['language_id']; ?>]" value="<?php echo $product['meta_title']; ?>"></td> 
                  </tr>
				  <tr>
                    <td width="185" class="set"> <?php echo $entry_meta_description; ?></td>
                    <td><input class="validate_meta" id="meta_description<?php echo $product['language_id']; ?>" size="60" maxlength="120" name="meta_description[<?php echo $product['language_id']; ?>]" value="<?php echo $product['meta_description']; ?>"></td>
				  </tr>
				  <tr>
                    <td width="185" class="set"> <?php echo $entry_meta_keywords; ?></td>
                    <td><input class="validate_meta" id="meta_keywords<?php echo $product['language_id']; ?>" size="60" maxlength="120" name="meta_keywords[<?php echo $product['language_id']; ?>]" value="<?php echo $product['meta_keywords']; ?>"></td>
				  </tr>
                  <tr>
                    <td valign="top" class="set"><?php echo $entry_alt_description; ?></td>
                    <td><textarea name="alt_description[<?php echo $product['language_id']; ?>]" id="alt_description<?php echo $product['language_id']; ?>"><?php echo $product['alt_description']; ?></textarea></td>
                  </tr>
				</table>
				<table>
				  <tr>
				    <td width="185" class="set"> <?php echo $entry_technical_name; ?></td>
				    <td><input size="64" maxlength="64" name="technical_name[<?php echo $product['language_id']; ?>]" value="<?php echo $product['technical_name']; ?>"></td> 
					<td class="expl"><?php echo $text_technical_name;?></td>
				  </tr>
				</table>
				<table>
				  <tr>
				    <td width="185" valign="top" class="set"><?php echo $entry_technical; ?></td>
				    <td><textarea name="technical[<?php echo $product['language_id']; ?>]" id="technical<?php echo $product['language_id']; ?>"><?php echo $product['technical']; ?></textarea></td>
				  </tr>
                </table>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>
	  </div>
	  <!-- E: Alt Description Meta Tags -->
    </div>
  </div>
  <input type="hidden" id="product_id" name="product_id" value="<?php echo $product_id;?>">
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">

  <script type="text/javascript"><!--
  <?php foreach ($products as $product) { ?>
    CKEDITOR.replace( 'description<?php echo $product['language_id']; ?>' );
  <?php } ?>      
  //--></script>
  <script type="text/javascript"><!--
  <?php foreach ($products as $product) { ?>
    CKEDITOR.replace( 'alt_description<?php echo $product['language_id']; ?>' );
  <?php } ?>      
  //--></script>
  <script type="text/javascript"><!--
  <?php foreach ($products as $product) { ?>
    CKEDITOR.replace( 'technical<?php echo $product['language_id']; ?>' );
  <?php } ?>      
  //--></script>

  <script type="text/javascript"><!-- 
  function quantity_percent(row){
  var Decimal = $('#decimal_place').val();
  var RegularPrice = $('#price').val();
  var DiscountPercent = $('#discount_percent'+row).val();
  var DiscountAmount;
  if(RegularPrice > 0 && DiscountPercent >0){
    DiscountAmount = (((RegularPrice * (DiscountPercent/100))*100)/100).toFixed([Decimal]);
	$('#discount_amount'+row).val(DiscountAmount);
    $('#discount_percent'+row).val(((DiscountPercent*10000)/10000).toFixed([4]));
  }
  return;
  }
  function quantity_discount(row){
  var Decimal = $('#decimal_place').val();
  var RegularPrice = $('#price').val();
  var DiscountAmount = $('#discount_amount'+row).val();
  var Percent;
  if(RegularPrice > 0 && DiscountAmount >0){
    Percent = ((DiscountAmount/RegularPrice)*100).toFixed([4]);
	$('#discount_percent'+row).val(Percent);
	$('#discount_amount'+row).val(((DiscountAmount*100)/100).toFixed([Decimal]));
  }  
  return;
  }

  function regular_price_update(){
  var Decimal = $('#decimal_place').val();
  var RegularPrice = $('#regular_price').val();
  var Special_Price = $('#special_price').val();
  var Price = $('#price').val();  
  var Percent;
  if(Special_Price > 0 && Price >0){
    Percent = (100-((Special_Price/Price)*100)).toFixed([4]);
	$('#special_discount').val(Percent);
    DiscountPrice = (((RegularPrice - (RegularPrice * (Percent/100)))*100)/100).toFixed([Decimal]);
	$('#special_price').val(DiscountPrice);
	$('#discount_special_price').val(DiscountPrice); 
  }
  if($('#discounts tr').size() >0){
    QtyDiscount_Update(Price,RegularPrice);
  }  
	$('#regular_price').val(((RegularPrice*100)/100).toFixed([Decimal])); 
    $('#price').val($('#regular_price').val());
	$('#discount_regular_price').val($('#regular_price').val());

    return;
  }

  function price_update(){
  var Decimal = $('#decimal_place').val();
  var RegularPrice = $('#regular_price').val();
  var Special_Price = $('#special_price').val();
  var Price = $('#price').val();  
  var Percent;
  if(Special_Price > 0 && RegularPrice >0){
    Percent = (100-((Special_Price/RegularPrice)*100)).toFixed([4]);
	$('#special_discount').val(Percent);
    DiscountPrice = (((Price - (Price * (Percent/100)))*100)/100).toFixed([Decimal]);
	$('#special_price').val(DiscountPrice);
	$('#discount_special_price').val(DiscountPrice);
  }
  if($('#discounts tr').size() >0){
	  QtyDiscount_Update(RegularPrice,Price);
  }
  $('#price').val(((Price*100)/100).toFixed([Decimal])); 
  $('#regular_price').val($('#price').val());
  $('#discount_regular_price').val($('#price').val());   
   return;	
  }

  function QtyDiscount_Update(OldPrice,NewPrice){
  var Decimal = $('#decimal_place').val();
  var count = $('#discounts tr').size();
  for (i=0; i < count; i++){
    var DiscountPercent = $('#discount_percent'+i).val();
    var NewDiscountPrice = (((NewPrice * (DiscountPercent/100))*100)/100).toFixed([Decimal]);
	$('#discount_amount'+i).val(NewDiscountPrice); 
  }
  return;
  }

  function calculate_percent(){
  var Decimal = $('#decimal_place').val();
  var Special_Price = $('#special_price').val();
  var RegularPrice = $('#price').val();
  var OldPercent = $('#special_discount').val();
  var Percent;
  if(Special_Price > 0 && RegularPrice >0){
    Percent = (100-((Special_Price/RegularPrice)*100)).toFixed([4]);
	$('#special_discount').val(Percent);
    $('#special_price').val(((Special_Price*100)/100).toFixed([Decimal]));
    $('#discount_special_price').val(((Special_Price*100)/100).toFixed([Decimal]));
  } else if(Special_Price == 0) {
    $('#special_price').val((0).toFixed([Decimal])); 
    $('#special_discount').val((0).toFixed([4]));
	$('#discount_special_price').val((0).toFixed([Decimal]));
  }
  return;
  }

  function calculate_discount(){
  var Decimal = $('#decimal_place').val();
  var RegularPrice = $('#price').val();
  var SpecialDiscount = $('#special_discount').val();
  var OldSpecialPrice = $('#special_price').val();
  var DiscountedPrice;
  if(RegularPrice > 0 && SpecialDiscount >0){
    DiscountPrice = (((RegularPrice - (RegularPrice * (SpecialDiscount/100)))*100)/100).toFixed([Decimal]);
	$('#special_price').val(DiscountPrice);
    $('#special_discount').val(((SpecialDiscount*10000)/10000).toFixed([4]));
	$('#discount_special_price').val(DiscountPrice);
  } else if(SpecialDiscount == 0) {
    $('#special_price').val((0).toFixed([Decimal]));
    $('#special_discount').val((0).toFixed([4]));
	$('#discount_special_price').val((0).toFixed([Decimal]));
  }
  return;
}
//--></script>

<script type="text/javascript"><!--
function validate_barcode(optionRow){
	var Encoding = $('#encoding_'+optionRow).val();
	var Barcode = $('#barcode_'+ optionRow).val();
	var ProductID = $('#product_id').val();
	var OptionID = $('#option_id_'+ optionRow).val();
	if(OptionID==undefined){
		OptionID = 0;
	}
	
	if(Barcode > 0){
		$.ajax({
			type:    'GET',
			url:
			'index.php?controller=product&action=validate_barcode&encoding='+Encoding+'&barcode='+Barcode+'&row='+optionRow+'&product_id='+ProductID+'&option_id='+OptionID,
			async:   false,
			success: function(Barcode_data) {
     		$('#barcodefield_'+optionRow).html(Barcode_data);
			}
		});
	}
}
  //--></script> 
  
  <script type="text/javascript"><!--
function addDiscount() {
	var Last = $('#discounts tr:last');
	var nextId = Last.size() == 0 ? 1 : + Last.attr('id').split("_").pop() + 1;
	$.ajax({
   		type:    'GET',
   		url:     'index.php?controller=product&action=discount&discount_id='+nextId,
		async:   false,
   		success: function(data) {
     		$('#discounts').append(data);
   		}
 	});
}
  
function removeDiscount(row) {
  	$('#'+row).remove();
}

//--></script>
  <script type="text/javascript"><!--
  $('#image').load('index.php?controller=image&action=view&image_id='+document.getElementById('image_id').value);
  //--></script>
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
  <script type="text/javascript"><!--
  tabview_initialize('tabmini');
  //--></script>
  <script type="text/javascript"><!--
  tabview_initialize('tabmini2');
  //--></script>
  <script type="text/javascript"><!--
    $('input[name="name[1]"]').change(function () {
      var value = $(this).val();
      $(".heading em").text(value);
    }).change();
  //--></script>
  <script type="text/javascript"><!--
  $('#syes').on("click", function() {
		$('#shipping_time_from').attr("disabled", false);
		$('#shipping_time_to').attr("disabled", false);
  });
    //--></script>
  <script type="text/javascript"><!--
  $('#sno').on("click", function() {
		$('#shipping_time_from').attr("disabled", true);
		$('#shipping_time_to').attr("disabled", true);
});
    //--></script>
  <script type="text/javascript"><!--
    datePickerController.createDatePicker({
    formElements:{"date_available_year":"%Y","date_available_month":"%n", "date_available_day":"%d"},
    showWeeks:true,
    statusFormat:"%l, %d %F %Y"
    });
  //--></script>
  <script type="text/javascript"><!--
    datePickerController.createDatePicker({
    formElements:{"start_date_year":"%Y","start_date_month":"%n", "start_date_day":"%d"},
    showWeeks:true,
    statusFormat:"%l, %d %F %Y"
    });
  //--></script>
  <script type="text/javascript"><!--
    datePickerController.createDatePicker({
    formElements:{"end_date_year":"%Y","end_date_month":"%n", "end_date_day":"%d"},
    showWeeks:true,
    statusFormat:"%l, %d %F %Y"
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
		url:     'index.php?controller=product&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
  <script type="text/javascript"><!--
	function saveTabs() {
	var activeTab = $('#tab > .tabs > a.active').index()+1;
	var activeTabmini = $('#tabmini > .tabs > a:visible.active').index()+1;
	var activeTabmini2 = $('#tabmini2 > .tabs > a:visible.active').index()+1;
	var id = $('#product_id').val();
	var data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini, 'id':id, 'activeTabmini2':activeTabmini2};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=product&action=tab',
		data: data_json,
		dataType:'json'
	});
	}
	function getTabs() {
	var activeTab = $('#tab > .tabs > a.active').index()+1;
	var activeTabmini = $('#tabmini > .tabs > a:visible.active').index()+1;
	var activeTabmini2 = $('#tabmini2 > .tabs > a:visible.active').index()+1;
	var id = $('#product_id').val();
	var data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini, 'id':id, 'activeTabmini2':activeTabmini2};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=product&action=tab',
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
	function copyDiscount() {
		var ids = [];
		var html ='';
		var boxes = document.querySelectorAll("[name^='qty_discount_']");
		var boxesLenght = boxes.length;
		for (j =0; j < boxesLenght; j++) {
			ids[j] = boxes[j].getAttribute("name").split("_").pop();
		}
		for (i =0; i < boxesLenght; i++) {
			if (document.forms['form'].elements['product_discount['+ids[i]+'][quantity]'] !=undefined){
			html +='<input type="hidden" name="product_discount[' + [i] + '][quantity]" value="' + document.forms['form'].elements['product_discount['+ids[i]+'][quantity]'].value + '">';
			html +='<input type="hidden" name="product_discount[' + [i] + '][discount]" value="' + document.forms['form'].elements['product_discount['+ids[i]+'][discount]'].value + '">';
			html +='<input type="hidden" name="discount_amount' + [i] + '" value="' + document.forms['form'].elements['discount_amount'+ids[i]].value + '">';
			}
		}
		document.forms['update_form'].innerHTML += html;
	}
	function getValues() {

		document.forms['update_form'].status.value=document.forms['form'].status.value;
		document.forms['update_form'].date_available_day.value=document.forms['form'].date_available_day.value;
		document.forms['update_form'].date_available_month.value=document.forms['form'].date_available_month.value;
		document.forms['update_form'].date_available_year.value=document.forms['form'].date_available_year.value;
		document.forms['update_form'].quantity.value=document.forms['form'].quantity.value;
		document.forms['update_form'].min_qty.value=document.forms['form'].min_qty.value;
		document.forms['update_form'].max_qty.value=document.forms['form'].max_qty.value;
		document.forms['update_form'].multiple.value=document.forms['form'].multiple.value;
		<?php if(!$option_status){?>
			document.forms['update_form'].encoding.value=document.forms['form'].encoding.value;
			document.forms['update_form'].barcode.value=document.forms['form'].barcode.value;
		<?php } ?>
		document.forms['update_form'].manufacturer_id.value=document.forms['form'].manufacturer_id.value;
		document.forms['update_form'].vendor_id.value=document.forms['form'].vendor_id.value;
		document.forms['update_form'].shipping.value=document.forms['form'].shipping.value;
		document.forms['update_form'].shipping_time_from.value=document.forms['form'].shipping_time_from.value;
		document.forms['update_form'].shipping_time_to.value=document.forms['form'].shipping_time_to.value;
		document.forms['update_form'].tax_class_id.value=document.forms['form'].tax_class_id.value;
		document.forms['update_form'].price.value=document.forms['form'].price.value;
		document.forms['update_form'].sort_order.value=document.forms['form'].sort_order.value;
		document.forms['update_form'].weight_class_id.value=document.forms['form'].weight_class_id.value;
		document.forms['update_form'].weight.value=document.forms['form'].weight.value;
		document.forms['update_form'].type_id.value=document.forms['form'].type_id.value;
		document.forms['update_form'].dimension_id.value=document.forms['form'].dimension_id.value;
		document.forms['update_form'].image_id.value=document.forms['form'].image_id.value;
		document.forms['update_form'].featured.value=document.forms['form'].featured.value;
		document.forms['update_form'].special_offer.value=document.forms['form'].special_offer.value;
		document.forms['update_form'].related.value=document.forms['form'].related.value;
		document.forms['update_form'].special_price.value=document.forms['form'].special_price.value;
		document.forms['update_form'].special_discount.value=document.forms['form'].special_discount.value;
		document.forms['update_form'].start_date_day.value=document.forms['form'].start_date_day.value;
		document.forms['update_form'].start_date_month.value=document.forms['form'].start_date_month.value;
		document.forms['update_form'].start_date_year.value=document.forms['form'].start_date_year.value;
		document.forms['update_form'].end_date_day.value=document.forms['form'].end_date_day.value;
		document.forms['update_form'].end_date_month.value=document.forms['form'].end_date_month.value;
		document.forms['update_form'].end_date_year.value=document.forms['form'].end_date_year.value;
		document.forms['update_form'].remaining.value=document.forms['form'].remaining.value;

		<?php foreach ($products as $product) { ?>
			document.forms['update_form'].elements['name[<?php echo $product['language_id']; ?>]'].value=document.forms['form'].elements['name[<?php echo $product['language_id']; ?>]'].value;
			document.forms['update_form'].elements['model_number[<?php echo $product['language_id']; ?>]'].value=document.forms['form'].elements['model_number[<?php echo $product['language_id']; ?>]'].value;
			document.forms['update_form'].elements['model[<?php echo $product['language_id']; ?>]'].value=document.forms['form'].elements['model[<?php echo $product['language_id']; ?>]'].value;
			document.forms['update_form'].elements['description[<?php echo $product['language_id']; ?>]'].value= CKEDITOR.instances.description<?php echo $product['language_id']; ?>.getData();
			document.forms['update_form'].elements['meta_title[<?php echo $product['language_id']; ?>]'].value=document.forms['form'].elements['meta_title[<?php echo $product['language_id']; ?>]'].value;
			document.forms['update_form'].elements['meta_description[<?php echo $product['language_id']; ?>]'].value=document.forms['form'].elements['meta_description[<?php echo $product['language_id']; ?>]'].value;
			document.forms['update_form'].elements['meta_keywords[<?php echo $product['language_id']; ?>]'].value=document.forms['form'].elements['meta_keywords[<?php echo $product['language_id']; ?>]'].value;
			document.forms['update_form'].elements['alt_description[<?php echo $product['language_id']; ?>]'].value= CKEDITOR.instances.alt_description<?php echo $product['language_id']; ?>.getData();
			document.forms['update_form'].elements['technical_name[<?php echo $product['language_id']; ?>]'].value=document.forms['form'].elements['technical_name[<?php echo $product['language_id']; ?>]'].value;
			document.forms['update_form'].elements['technical[<?php echo $product['language_id']; ?>]'].value= CKEDITOR.instances.technical<?php echo $product['language_id']; ?>.getData();
		<?php } ?>

		<?php if($product_options){?>
			<?php $option_rows = 0;?>
			<?php foreach($product_options as $key => $product_option){?>
				document.forms['update_form'].elements['product_options[<?php echo $option_rows; ?>][product_option]'].value=document.forms['form'].elements['product_options[<?php echo $option_rows; ?>][product_option]'].value;
				document.forms['update_form'].elements['product_options[<?php echo $option_rows; ?>][option_name]'].value=document.forms['form'].elements['product_options[<?php echo $option_rows; ?>][option_name]'].value;
				document.forms['update_form'].elements['product_options[<?php echo $option_rows; ?>][model_number]'].value=document.forms['form'].elements['product_options[<?php echo $option_rows; ?>][model_number]'].value;
				document.forms['update_form'].elements['product_options[<?php echo $option_rows; ?>][encoding]'].value=document.forms['form'].elements['product_options[<?php echo $option_rows; ?>][encoding]'].value;
				document.forms['update_form'].elements['product_options[<?php echo $option_rows; ?>][barcode]'].value=document.forms['form'].elements['product_options[<?php echo $option_rows; ?>][barcode]'].value;
				document.forms['update_form'].elements['product_options[<?php echo $option_rows; ?>][quantity]'].value=document.forms['form'].elements['product_options[<?php echo $option_rows; ?>][quantity]'].value;
				document.forms['update_form'].elements['product_options[<?php echo $option_rows; ?>][product_id]'].value=document.forms['form'].elements['product_options[<?php echo $option_rows; ?>][product_id]'].value;
				document.forms['update_form'].elements['product_options[<?php echo $option_rows; ?>][image_id]'].value=document.forms['form'].elements['product_options[<?php echo $option_rows; ?>][image_id]'].value;
				document.forms['update_form'].elements['product_options[<?php echo $option_rows; ?>][dimension_id]'].value=document.forms['form'].elements['product_options[<?php echo $option_rows; ?>][dimension_id]'].value;
				document.forms['update_form'].elements['product_options[<?php echo $option_rows; ?>][dimension_value]'].value=document.forms['form'].elements['product_options[<?php echo $option_rows; ?>][dimension_value]'].value;
				<?php ++$option_rows;?>
			<?php }?>
		<?php }?>

		getMultipleSelection('form', 'image[]', 'update_form');
		getMultipleSelection('form', 'download[]', 'update_form');
		getMultipleSelection('form', 'fdownload[]', 'update_form');
		getMultipleSelection('form', 'relateddata[]', 'update_form');
		getMultipleSelection('form', 'category[]', 'update_form');
		getDimensionValues('form', 'dimension_value', 'update_form');
		copyDiscount();
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
	function getDimensionValues(formName,elementName,newFormName){ 
		var html ='';
		var len = $('[id^='+ elementName + ']').length;
		for(j = 0; j < len; j++) { 
				html +='<input type="hidden" name="' + elementName + '[' + j + ']" value="' + document.forms[formName].elements[elementName + '[' + j +']'].value + '">';
		}
		document.forms[newFormName].innerHTML += html;
	}
  //--></script>
  <script type="text/javascript"><!--
  $(document).ready(function() {
	if (<?php echo $tab; ?>!=undefined && <?php echo $tab; ?> > 0) {
		tabview_switch('tab', <?php echo $tab; ?>);
		if (<?php echo $tabmini; ?>!=undefined && <?php echo $tabmini; ?> > 0) {
			tabview_switch('tabmini', <?php echo $tabmini; ?>);
		}
		if (<?php echo $tabmini2; ?>!=undefined && <?php echo $tabmini2; ?> > 0) {
			tabview_switch('tabmini2', <?php echo $tabmini2; ?>);
		}
	}
   });
  //--></script>
  <script type="text/javascript"><!--
  $(document).ready(function() {
    $('#syes:checked').each(function() {
		$('#shipping_time_from').attr("disabled", false);
		$('#shipping_time_to').attr("disabled", false);
    });
});
    //--></script>
<script type="text/javascript"><!--
  $(document).ready(function() {
	RegisterValidation();
  });
//--></script>
</form>

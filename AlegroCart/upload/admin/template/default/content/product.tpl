<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/<?php echo $this->directory?>/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $insert; ?>'"><img src="template/<?php echo $this->directory?>/image/insert_enabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
<?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css">
<script type="text/javascript" src="javascript/ajax/jquery.js"></script>
<script type="text/javascript" src="javascript/fckeditor/fckeditor.js"></script>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><?php echo $tab_general; ?></a><a><?php echo $tab_data; ?></a><a><?php echo $tab_image; ?></a><a><?php echo $tab_download; ?></a><a><?php echo $tab_category; ?></a><a><?php echo $tab_home; ?></a><a href="#discount"><?php echo $tab_discount; ?></a><a><?php echo $tab_dated_special; ?></a><a><?php echo $tab_alt_description; ?></a></div>
    <div class="pages">
      <div class="page">
        <div id="tabmini">
          <div class="tabs">
            <?php foreach ($products as $product) { ?>
            <a><?php echo $product['language']; ?></a>
            <?php } ?>
          </div>
          <div class="pages">
            <?php foreach ($products as $product) { ?>
            <div class="page">
              <div class="minipad">
                <table>
                  <tr>
                    <td style="width: 185px;"><span class="required">*</span><?php echo $entry_name; ?></td>
                    <td style="width: 265px;"><input name="name[<?php echo $product['language_id']; ?>]" value="<?php echo $product['name']; ?>">
                      <?php if ($error_name) { ?>
                      <span class="error"><?php echo $error_name; ?></span>
                      <?php } ?></td>
					<td><?php echo $text_unique; ?></td>	
                  </tr>
				  <tr>
				    <td style="width: 185px;"><?php echo $entry_model_number; ?></td>
					<td style="width: 265px;"><input size="32" maxlength="32" name="model_number[<?php echo $product['language_id']; ?>]" value="<?php echo $product['model_number']; ?>"></td>
				  </tr>
				  <tr>
					<td style="width: 185px;"><?php echo $entry_model; ?></td>
					<td><input size="32" maxlength="32" id="model<?php echo $product['language_id']; ?>" name="model[<?php echo $product['language_id']; ?>]" value="<?php echo $product['model']; ?>"></td>
					<td>
					  <select id="model_data<?php echo $product['language_id']; ?>" name="model_data<?php echo $product['language_id']; ?>" onchange="$('#model<?php echo $product['language_id']; ?>').val(this.value)">
						<?php if(!$product['model']){?>
							<option value=""><?php echo $select_model;?></option><?php }?>
						<?php foreach($product['models_data'] as $model_data){?>
						  <?php if ($model_data['model'] == $product['model']){?>
							<option value="<?php echo $model_data['model']; ?>" selected><?php echo $model_data['model']; ?></option>
						  <?php } else { ?>
							<option value="<?php echo $model_data['model']; ?>"><?php echo $model_data['model']; ?></option>
						  <?php } ?>
						<?php } ?>
					  </select>
					</td>
					<td><?php echo $text_model; ?></td>			  
				  </tr>				  
				</table>
				<table>
                  <tr>
                    <td style="vertical-align: top; width: 185px"><?php echo $entry_description; ?></td>
                    <td><textarea name="description[<?php echo $product['language_id']; ?>]" id="description<?php echo $product['language_id']; ?>"><?php echo $product['description']; ?></textarea>
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
              <td><?php echo $entry_manufacturer; ?></td>
              <td><select name="manufacturer_id">
                  <option value="0" selected><?php echo $text_none; ?></option>
                  <?php foreach ($manufacturers as $manufacturer) { ?>
                  <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
                  <option value="<?php echo $manufacturer['manufacturer_id']; ?>" selected><?php echo $manufacturer['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_shipping; ?></td>
              <td><?php if ($shipping == 1) { ?>
                <input type="radio" name="shipping" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="shipping" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="shipping" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="shipping" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_date_available; ?></td>
              <td><input name="date_available_day" value="<?php echo $date_available_day; ?>" size="2" maxlength="2">
                <select name="date_available_month">
                  <?php foreach ($months as $month) { ?>
                  <?php if ($month['value'] == $date_available_month) { ?>
                  <option value="<?php echo $month['value']; ?>" selected><?php echo $month['text']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <input name="date_available_year" value="<?php echo $date_available_year; ?>" size="4" maxlength="4">
                <?php if ($error_date_available) { ?>
                <span class="error"><?php echo $error_date_available; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_quantity; ?></td>
              <td><input name="quantity" value="<?php echo $quantity; ?>" size="2"></td>
            </tr>
            <tr>
              <td><?php echo $entry_min_qty; ?></td>
              <td><input name="min_qty" value="<?php echo $min_qty; ?>" size="2" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
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
              <td><?php echo $entry_sort_order; ?></td>
              <td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1"></td>
            </tr>
            <tr>
              <td><?php echo $entry_tax_class; ?></td>
              <td><select name="tax_class_id">
                  <?php foreach ($tax_classes as $tax_class) { ?>
                  <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>" selected><?php echo $tax_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
				  <option value="0"><?php echo $text_none; ?></option>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_price; ?></td>
              <td><input name="price" id="price" value="<?php echo $price; ?>" onchange="price_update()"></td>
			  <td><?php echo 'Currency : '. $currency_code;?>
			  <input type="hidden" id="decimal_place" value="<?php echo $decimal_place;?>">
			  </td>
            </tr>
			<tr><td colspan="2"><hr></td></tr>
            <tr>
              <td><?php echo $entry_weight_class; ?></td>
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
              <td><?php echo $entry_weight; ?></td>
              <td><input name="weight" value="<?php echo $weight; ?>"></td>
            </tr>
			<tr><td colspan="2"><hr></td></tr>
			<tr>
			 <td><?php echo $entry_dimension_class;?></td>
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
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185"><?php echo $entry_image; ?></td>
              <td><select name="image_id" id="image_id" onchange="$('#image').load('index.php?controller=image&action=view&image_id='+this.value);">
                  <?php foreach ($images as $image) { ?>
                  <?php if ($image['image_id'] == $image_id) { ?>
                  <option value="<?php echo $image['image_id']; ?>" selected><?php echo $image['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $image['image_id']; ?>"><?php echo $image['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td></td>
              <td id="image"></td>
            </tr>
            <tr>
              <td valign="top"><?php echo $entry_images; ?></td>
              <td><select name="image[]" multiple="multiple">
                  <?php foreach ($images as $image) { ?>
                  <?php if (!$image['product_id']) { ?>
                  <option value="<?php echo $image['image_id']; ?>"><?php echo $image['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $image['image_id']; ?>" selected><?php echo $image['title']; ?></option>
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
              <td width="185" valign="top"><?php echo $entry_download; ?></td>
              <td><select name="download[]" multiple="multiple">
                  <?php foreach ($downloads as $download) { ?>
                  <?php if (!$download['product_id']) { ?>
                  <option value="<?php echo $download['download_id']; ?>"><?php echo $download['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $download['download_id']; ?>" selected><?php echo $download['name']; ?></option>
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
              <td width="185" valign="top"><?php echo $entry_category; ?></td>
              <td><select name="category[]" multiple="multiple">
                  <?php foreach ($categories as $category) { ?>
                  <?php if (!$category['product_id']) { ?>
                  <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $category['category_id']; ?>" selected><?php echo $category['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
      </div>
      <!-- B: Latest/Featured/Specials Contrib -->
      <div class="page">
        <div class="pad">
          <table>

            <tr>
              <td><?php echo $entry_featured; ?></td>
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
              <td><?php echo $entry_specials; ?></td>
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
              <td><?php echo $entry_related; ?></td>
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
              <td width="185" valign="top"><?php echo $entry_related; ?></td>
              <td><select name="relateddata[]" multiple="multiple">
                  <?php foreach ($relateddata as $product) { ?>
                  <?php if (!$product['relateddata']) { ?>
                  <option value="<?php echo $product['product_id']; ?>"><?php echo $product['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $product['product_id']; ?>" selected><?php echo $product['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
			
          </table>
        </div>
      </div>
      <!-- E: Latest/Featured/Specials Contrib -->
	  <!-- S: Quantity Discounts -->
      <div id="discount" class="page">
        <div class="pad">
		  <div><?php echo $entry_regular_price;?><input type="text" id="discount_regular_price" size="11" disabled="disabled" value="<?php echo $price;?>">		  
		  <?php echo $entry_dated_special;?><input type="text" id="discount_special_price" size="11" disabled="disabled" value="<?php echo $special_price;?>"></div>		  
          <table id="discounts">

            <?php $i = 0; ?>
            <?php foreach ($product_discounts as $product_discount) { ?>
            <tr id="discount_<?php echo $i; ?>">
              <td><?php echo $entry_quantity; ?></td>
              <td><input type="text" name="product_discount[<?php echo $i; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" size="2"></td>

			  <td><?php echo $entry_percent_discount;?></td>			  
              <td><input type="text" id="discount_percent<?php echo $i; ?>" name="product_discount[<?php echo $i; ?>][discount]" size="11" value="<?php echo $product_discount['discount']; ?>" onchange="quantity_percent('<?php echo $i; ?>')"></td>
			  

              <td><?php echo $entry_discount; ?></td>
			  <?php $discountvalue = number_format($price*($product_discount['discount']/100),2); ?>	
			  <td><input type="text" id="discount_amount<?php echo $i;?>" size="8" value="<?php echo $discountvalue;?>" name="discount_amount<?php echo $i;?>" onchange="quantity_discount('<?php echo $i; ?>')"></td>
			  
              <td><input type="button" value="<?php echo $button_remove; ?>" onclick="removeDiscount('discount_<?php echo $i; ?>');"></td>
            </tr>
            <?php $i++; ?>
            <?php } ?>
          </table>
          <table>
            <tr>
              <td colspan="5"><input type="button" value="<?php echo $button_add; ?>" onclick="addDiscount();"></td>
            </tr>
          </table>
		  <?php echo $entry_quantity_discount;?>
        </div>
      </div>
	  <!-- E: Quantity Discounts -->	  
	  <!-- S: Dated Specials -->
      <div class="page">
        <div class="pad">
	      <table>
		    <tr><td><b>
			<?php echo $products['0']['name']?>
			</b></td></tr>		  
			<tr>
			  <td><?php echo $entry_regular_price;?></td>
			  <td>  <input type="text" id="regular_price" value="<?php echo $price; ?>"onchange="regular_price_update()">
			  </td>
			  
			</tr>
		    <tr>
			  <td><?php echo $entry_start_date; ?></td>
              <td><input name="start_date_day" value="<?php echo $start_date_day; ?>" size="2" maxlength="2">
			  <select name="start_date_month">
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
				<input name="start_date_year" value="<?php echo $start_date_year; ?>" size="4" maxlength="4">
				<?php if ($error_start_date) { ?>
				<span class="error"><?php echo $error_start_date; ?></span>
				<?php } ?>
			  </td>
			</tr>
		    <tr>
			  <td><?php echo $entry_end_date; ?></td>
              <td><input name="end_date_day" value="<?php echo $end_date_day; ?>" size="2" maxlength="2">
			  <select name="end_date_month">
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
				<input name="end_date_year" value="<?php echo $end_date_year; ?>" size="4" maxlength="4">
				<?php if ($error_end_date) { ?>
				<span class="error"><?php echo $error_end_date; ?></span>
				<?php } ?>
			  </td>
			</tr>	  
            <tr>
              <td><?php echo $entry_dated_special; ?></td>
              <td><input id="special_price" name="special_price" value="<?php echo $special_price; ?>" onchange="calculate_percent()"></td>
            </tr>
			<tr>
			
			  <td><?php echo $entry_percent_discount;?></td>
			  <?php $special_discount = $special_price>0 ? ceil((100-(($special_price/$price)*100))*10000)/10000 : 0;?>
			  <td><input id="special_discount" name="special_discount" value="<?php echo $special_discount; ?>" onchange="calculate_discount()">
			
			  </td>
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
            <a><?php echo $product['language']; ?></a>
            <?php } ?>
          </div>
          <div class="pages">
            <?php foreach ($products as $product) { ?>
            <div class="page">
              <div class="minipad">
                <table>
                  <tr>
                    <td width="185"> <?php echo $entry_meta_title; ?></td>
                    <td><input size="60" maxlength="60" name="meta_title[<?php echo $product['language_id']; ?>]" value="<?php echo $product['meta_title']; ?>"></td> 
                  </tr>
				  <tr>
                    <td width="185"> <?php echo $entry_meta_description; ?></td>
                    <td><input size="60" maxlength="120" name="meta_description[<?php echo $product['language_id']; ?>]" value="<?php echo $product['meta_description']; ?>"></td>					
				  </tr>
				  <tr>
                    <td width="185"> <?php echo $entry_meta_keywords; ?></td>
                    <td><input size="60" maxlength="120" name="meta_keywords[<?php echo $product['language_id']; ?>]" value="<?php echo $product['meta_keywords']; ?>"></td>
				  </tr>
                  <tr>
                    <td valign="top"><?php echo $entry_alt_description; ?></td>
                    <td><textarea name="alt_description[<?php echo $product['language_id']; ?>]" id="alt_description<?php echo $product['language_id']; ?>"><?php echo $product['alt_description']; ?></textarea>
                  </tr>
				  <tr>
				    <td valign="top"><?php echo $entry_technical; ?></td>
				    <td><textarea name="technical[<?php echo $product['language_id']; ?>]" id="technical<?php echo $product['language_id']; ?>"><?php echo $product['technical']; ?></textarea>
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
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <script type="text/javascript">//<!--
  var sBasePath           = '<?php echo HTTP_SERVER.'javascript/fckeditor/'?>';
  <?php foreach ($products as $product) { ?>
	var oFCKeditor<?php echo $product['language_id']."desc"; ?> = new FCKeditor('description<?php echo $product['language_id']; ?>');
	oFCKeditor<?php echo $product['language_id']."desc"; ?>.BasePath = sBasePath;
	oFCKeditor<?php echo $product['language_id']."desc"; ?>.Value	= document.getElementById('description<?php echo $product['language_id']; ?>').value;
	oFCKeditor<?php echo $product['language_id']."desc"; ?>.Width    = '600';
	oFCKeditor<?php echo $product['language_id']."desc"; ?>.Height   = '300';
	oFCKeditor<?php echo $product['language_id']."desc"; ?>.Config['CustomConfigurationsPath'] = oFCKeditor<?php echo $product['language_id']."desc"; ?>.BasePath + 'myconfig.js';
	oFCKeditor<?php echo $product['language_id']."desc"; ?>.Config['DocType'] = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
	oFCKeditor<?php echo $product['language_id']."desc"; ?>.Config['SkinPath'] = oFCKeditor<?php echo $product['language_id']."desc"; ?>.BasePath + 'editor/skins/silver/' ;
	oFCKeditor<?php echo $product['language_id']."desc"; ?>.ToolbarSet = 'Custom' ;
	oFCKeditor<?php echo $product['language_id']."desc"; ?>.ReplaceTextarea();	

	var oFCKeditor<?php echo $product['language_id']."alt"; ?> = new FCKeditor('alt_description<?php echo $product['language_id']; ?>');
	oFCKeditor<?php echo $product['language_id']."alt"; ?>.BasePath = sBasePath;
	oFCKeditor<?php echo $product['language_id']."alt"; ?>.Value	= document.getElementById('alt_description<?php echo $product['language_id']; ?>').value;
	oFCKeditor<?php echo $product['language_id']."alt"; ?>.Width    = '600';
	oFCKeditor<?php echo $product['language_id']."alt"; ?>.Height   = '300';
	oFCKeditor<?php echo $product['language_id']."alt"; ?>.Config['CustomConfigurationsPath'] = oFCKeditor<?php echo $product['language_id']."alt"; ?>.BasePath + 'myconfig.js';
	oFCKeditor<?php echo $product['language_id']."alt"; ?>.Config['DocType'] = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
	oFCKeditor<?php echo $product['language_id']."alt"; ?>.Config['SkinPath'] = oFCKeditor<?php echo $product['language_id']."alt"; ?>.BasePath + 'editor/skins/silver/' ;
	oFCKeditor<?php echo $product['language_id']."alt"; ?>.ToolbarSet = 'Custom' ;
	oFCKeditor<?php echo $product['language_id']."alt"; ?>.ReplaceTextarea();
	
	var oFCKeditor<?php echo $product['language_id']."tech"; ?> = new FCKeditor('technical<?php echo $product['language_id']; ?>');
	oFCKeditor<?php echo $product['language_id']."tech"; ?>.BasePath = sBasePath;
	oFCKeditor<?php echo $product['language_id']."tech"; ?>.Value	= document.getElementById('technical<?php echo $product['language_id']; ?>').value;
	oFCKeditor<?php echo $product['language_id']."tech"; ?>.Width    = '600';
	oFCKeditor<?php echo $product['language_id']."tech"; ?>.Height   = '300';
	oFCKeditor<?php echo $product['language_id']."tech"; ?>.Config['CustomConfigurationsPath'] = oFCKeditor<?php echo $product['language_id']."alt"; ?>.BasePath + 'myconfig.js';
	oFCKeditor<?php echo $product['language_id']."tech"; ?>.Config['DocType'] = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
	oFCKeditor<?php echo $product['language_id']."tech"; ?>.Config['SkinPath'] = oFCKeditor<?php echo $product['language_id']."alt"; ?>.BasePath + 'editor/skins/silver/' ;
	oFCKeditor<?php echo $product['language_id']."tech"; ?>.ToolbarSet = 'Custom' ;
	oFCKeditor<?php echo $product['language_id']."tech"; ?>.ReplaceTextarea();
	
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
function addDiscount() {
	$.ajax({
   		type:    'GET',
   		url:     'index.php?controller=product&action=discount&discount_id='+$('#discounts tr').size(),
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
</form>
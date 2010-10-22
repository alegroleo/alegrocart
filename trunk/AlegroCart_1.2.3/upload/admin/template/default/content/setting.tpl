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
<script type="text/javascript" src="javascript/ajax/jquery.js"></script>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><?php echo $tab_shop; ?></a><a><?php echo $tab_admin; ?></a><a><?php echo $tab_local; ?></a><a><?php echo $tab_stock; ?></a><a><?php echo $tab_option; ?></a><a><?php echo $tab_mail; ?></a><a><?php echo $tab_cache; ?></a><a><?php echo $tab_image; ?></a><a><?php echo $tab_download; ?></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185"><span class="required">*</span> <?php echo $entry_store; ?></td>
              <td><input type="text" name="global_config_store" value="<?php echo $global_config_store; ?>">
                <?php if ($error_store) { ?>
                <span class="error"><?php echo $error_store; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_owner; ?></td>
              <td><input type="text" name="global_config_owner" value="<?php echo $global_config_owner; ?>">
                <?php if ($error_owner) { ?>
                <span class="error"><?php echo $error_owner; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td valign="top"><span class="required">*</span> <?php echo $entry_address; ?></td>
              <td><textarea name="global_config_address" cols="40" rows="5"><?php echo $global_config_address; ?></textarea>
                <?php if ($error_address) { ?>
                <span class="error"><?php echo $error_address; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
              <td><input type="text" name="global_config_telephone" value="<?php echo $global_config_telephone; ?>">
                <?php if ($error_telephone) { ?>
                <span class="error"><?php echo $error_telephone; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_fax; ?></td>
              <td><input type="text" name="global_config_fax" value="<?php echo $global_config_fax; ?>"></td>
            </tr>
            <tr>
              <td><?php echo $entry_template; ?></td>
              <td><select name="catalog_config_template">
                  <?php foreach ($catalog_templates as $catalog_template) { ?>
                  <?php if ($catalog_template == $catalog_config_template) { ?>
                  <option value="<?php echo $catalog_template; ?>" selected><?php echo $catalog_template; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $catalog_template; ?>"><?php echo $catalog_template; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr> 
			<tr> <!-- Columns -->
			  <td><?php echo $entry_columns; ?></td>
			  <td><select name="catalog_config_columns" id="catalog_config_columns" onchange="$('#colors').load('index.php?controller=setting&action=getColors&'+change_columns(this.value));">
                  <?php foreach ($page_columns as $page_column) { ?>
                  <?php if ($page_column == $catalog_config_columns) { ?>
                  <option value="<?php echo $page_column; ?>" selected><?php echo $page_column; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $page_column; ?>"><?php echo $page_column; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
			</tr>
			<tr> <!-- Styles -->
              <td><?php echo $entry_styles; ?></td>
              <td><select name="catalog_config_styles" id="catalog_config_styles" onchange="$('#colors').load('index.php?controller=setting&action=getColors&'+change_styles(this.value));">
                  <?php foreach ($catalog_styles as $catalog_style) { ?>
                  <?php if ($catalog_style == $catalog_config_styles) { ?>
                  <option value="<?php echo $catalog_style; ?>" selected><?php echo $catalog_style; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $catalog_style; ?>"><?php echo $catalog_style; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
			  </td>
            </tr>
			<tr> <!-- Colors -->
              <td><?php echo $entry_colors; ?></td>
              <td id="colors"><select name="catalog_config_colors">
                  <?php foreach ($catalog_colors as $catalog_color) { ?>
                  <?php if ($catalog_color['colorcss'] == $catalog_config_colors) { ?>
                  <option value="<?php echo $catalog_color['colorcss']; ?>" selected><?php echo $catalog_color['colorcss']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $catalog_color['colorcss']; ?>"><?php echo $catalog_color['colorcss']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
				<?php if ($error_color) { ?>
                <span class="error"><?php echo $error_color; ?></span>
                <?php } ?>
			  </td>
            </tr>
            <tr>
              <td><?php echo $entry_rows_per_page; ?></td>
              <td><input type="text" name="catalog_config_max_rows" value="<?php echo $catalog_config_max_rows; ?>" size="2"></td>
            </tr>
            <tr>
              <td><?php echo $entry_url_alias; ?></td>
              <td><?php if ($global_config_url_alias) { ?>
                <input type="radio" name="global_config_url_alias" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="global_config_url_alias" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="global_config_url_alias" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="global_config_url_alias" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
			<tr>
              <td><?php echo $entry_seo; ?></td>
              <td><?php if ($global_config_seo) { ?>
                <input type="radio" name="global_config_seo" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="global_config_seo" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="global_config_seo" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="global_config_seo" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_parse_time; ?></td>
              <td><?php if ($catalog_config_parse_time) { ?>
                <input type="radio" name="catalog_config_parse_time" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="catalog_config_parse_time" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="catalog_config_parse_time" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="catalog_config_parse_time" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_ssl; ?></td>
              <td><?php if ($catalog_config_ssl) { ?>
                <input type="radio" name="catalog_config_ssl" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="catalog_config_ssl" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="catalog_config_ssl" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="catalog_config_ssl" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185"><?php echo $entry_template; ?></td>
              <td><select name="admin_config_template">
                  <?php foreach ($admin_templates as $admin_templates) { ?>
                  <?php if ($admin_templates == $admin_config_template) { ?>
                  <option value="<?php echo $admin_templates; ?>" selected><?php echo $admin_templates; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $admin_templates; ?>"><?php echo $admin_templates; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_rows_per_page; ?></td>
              <td><input type="text" name="admin_config_max_rows" value="<?php echo $admin_config_max_rows; ?>" size="2"></td>
            </tr>
            <tr>
              <td><?php echo $entry_parse_time; ?></td>
              <td><?php if ($admin_config_parse_time) { ?>
                <input type="radio" name="admin_config_parse_time" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="admin_config_parse_time" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="admin_config_parse_time" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="admin_config_parse_time" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_ssl; ?></td>
              <td><?php if ($admin_config_ssl) { ?>
                <input type="radio" name="admin_config_ssl" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="admin_config_ssl" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="admin_config_ssl" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="admin_config_ssl" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_language; ?></td>
              <td><select name="admin_config_language">
                  <?php foreach ($languages as $language) { ?>
                  <?php if ($language['code'] == $admin_config_language) { ?>
                  <option value="<?php echo $language['code']; ?>" selected><?php echo $language['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185"><?php echo $entry_country; ?></td>
              <td><select name="global_config_country_id" onchange="$('#zone').load('index.php?controller=setting&action=zone&country_id='+this.value+'&zone_id=<?php echo $global_config_zone_id; ?>');">
                  <?php foreach ($countries as $country) { ?>
                  <?php if ($country['country_id'] == $global_config_country_id) { ?>
                  <option value="<?php echo $country['country_id']; ?>" selected><?php echo $country['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_zone; ?></td>
              <td id="zone"><select name="global_config_zone_id">
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_language; ?></td>
              <td><select name="catalog_config_language">
                  <?php foreach ($languages as $language) { ?>
                  <?php if ($language['code'] == $catalog_config_language) { ?>
                  <option value="<?php echo $language['code']; ?>" selected><?php echo $language['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_currency; ?></td>
              <td><select name="global_config_currency">
                  <?php foreach ($currencies as $currencies) { ?>
                  <?php if ($currencies['code'] == $global_config_currency) { ?>
                  <option value="<?php echo $currencies['code']; ?>" selected><?php echo $currencies['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $currencies['code']; ?>"><?php echo $currencies['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
			<tr>
			  <td><?php echo $entry_currency_surcharge;?></td>
			  <td><input type="text" name="global_config_currency_surcharge" value="<?php echo $global_config_currency_surcharge;?>"></td>
			  <td><?php echo $text_surcharge;?></td>
			</tr>
            <tr>
              <td><?php echo $entry_weight; ?></td>
              <td><select name="global_config_weight_class_id">
                  <?php foreach ($weight_classes as $weight_class) { ?>
                  <?php if ($weight_class['weight_class_id'] == $global_config_weight_class_id) { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>" selected><?php echo $weight_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_tax; ?></td>
              <td><?php if ($global_config_tax) { ?>
                <input type="radio" name="global_config_tax" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="global_config_tax" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="global_config_tax" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="global_config_tax" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
				<td><?php echo $text_prices_tax;?></td>
            </tr>
            <tr>
              <td><?php echo $entry_order_status; ?></td>
              <td><select name="global_config_order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $global_config_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
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
              <td width="185"><?php echo $entry_stock_check; ?></td>
              <td><?php if ($catalog_config_stock_check) { ?>
                <input type="radio" name="catalog_config_stock_check" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="catalog_config_stock_check" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="catalog_config_stock_check" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="catalog_config_stock_check" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_stock_checkout; ?></td>
              <td><?php if ($catalog_config_stock_checkout) { ?>
                <input type="radio" name="catalog_config_stock_checkout" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="catalog_config_stock_checkout" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="catalog_config_stock_checkout" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="catalog_config_stock_checkout" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td width="185"><?php echo $entry_stock_subtract; ?></td>
              <td><?php if ($catalog_config_stock_subtract) { ?>
                <input type="radio" name="catalog_config_stock_subtract" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="catalog_config_stock_subtract" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="catalog_config_stock_subtract" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="catalog_config_stock_subtract" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185"><?php echo $entry_vat; ?></td>
              <td><input type="text" name="catalog_config_vat" value="<?php echo $catalog_config_vat; ?>"></td>
            </tr>
            <tr>
              <td><?php echo $entry_account; ?></td>
              <td><select name="catalog_config_account_id">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($informations as $information) { ?>
                  <?php if ($information['information_id'] == $catalog_config_account_id) { ?>
                  <option value="<?php echo $information['information_id']; ?>" selected><?php echo $information['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_checkout; ?></td>
              <td><select name="catalog_config_checkout_id">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($informations as $information) { ?>
                  <?php if ($information['information_id'] == $catalog_config_checkout_id) { ?>
                  <option value="<?php echo $information['information_id']; ?>" selected><?php echo $information['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
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
              <td width="185"><?php echo $entry_email; ?></td>
              <td><input size="64" type="text" name="global_config_email" value="<?php echo $global_config_email; ?>"></td>
            </tr>
            <tr>
              <td><?php echo $entry_email_send; ?></td>
              <td><?php if ($global_config_email_send) { ?>
                <input type="radio" name="global_config_email_send" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="global_config_email_send" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="global_config_email_send" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="global_config_email_send" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
			<tr>
			  <td><?php echo $text_emails; ?></td>
			</tr>
			<tr>
			  <td width="185"><?php echo $entry_email_orders; ?></td>
			  <td><input size="64" type="text" name="global_config_email_orders" value="<?php echo $global_config_email_orders; ?>"></td>
			</tr>
			<tr>
			  <td width="185"><?php echo $entry_email_accounts; ?></td>
			  <td><input size="64" type="text" name="global_config_email_accounts" value="<?php echo $global_config_email_accounts; ?>"></td>
			</tr>
			</tr>
			<tr>
			  <td width="185"><?php echo $entry_email_newsletter; ?></td>
			  <td><input size="64" type="text" name="global_config_email_newsletter" value="<?php echo $global_config_email_newsletter; ?>"></td>
			</tr>
			</tr>
			<tr>
			  <td width="185"><?php echo $entry_email_mail; ?></td>
			  <td><input size="64" type="text" name="global_config_email_mail" value="<?php echo $global_config_email_mail; ?>"></td>
			</tr>
			</tr>
			<tr>
			  <td width="185"><?php echo $entry_email_contact; ?></td>
			  <td><input size="64" type="text" name="global_config_email_contact" value="<?php echo $global_config_email_contact; ?>"></td>
			</tr>
          </table>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td><?php echo $entry_cache_query; ?></td>
              <td><?php if ($global_config_cache_query) { ?>
                <input type="radio" name="global_config_cache_query" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="global_config_cache_query" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="global_config_cache_query" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="global_config_cache_query" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td width="185"><?php echo $entry_compress_output; ?></td>
              <td><?php if ($global_config_compress_output) { ?>
                <input type="radio" name="global_config_compress_output" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="global_config_compress_output" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="global_config_compress_output" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="global_config_compress_output" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_compress_level; ?></td>
              <td><input type="text" name="global_config_compress_level" value="<?php echo $global_config_compress_level; ?>" size="3"></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="page">  <!--  Start Image and Add to Cart Controls -->
        <div class="pad">
          <table>
            <tr>
              <td width="185"><?php echo $entry_image_resize; ?></td>
              <td><?php if ($global_config_image_resize) { ?>
                <input type="radio" name="global_config_image_resize" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="global_config_image_resize" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="global_config_image_resize" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="global_config_image_resize" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr bgcolor="#EEEEEE">   <!-- Default Image Size -->
              <td><?php echo $entry_image_width; ?></td>
              <td><input type="text" name="global_config_image_width" value="<?php echo $global_config_image_width; ?>" size="3"></td>
              <td><?php echo $entry_image_height; ?></td>
              <td><input type="text" name="global_config_image_height" value="<?php echo $global_config_image_height; ?>" size="3"></td>			  
			</tr>
			<tr bgcolor="#CCFFFF">   <!-- Product -->
			  <td><?php echo $entry_product_width; ?></td>
			  <td><input type="text" name="catalog_product_image_width" value="<?php echo $catalog_product_image_width; ?>" size="3"></td>
			  <td><?php echo $entry_product_height; ?></td>
			  <td><input type="text" name="catalog_product_image_height" value="<?php echo $catalog_product_image_height; ?>" size="3"></td>
			<tr bgcolor="#CCFFFF">
			  <td><?php echo $entry_image_display;?></td>
				<td><select name="catalog_product_image_display">
				<?php foreach($image_displays_product as $image_display_product){?>
				  <?php if($image_display_product == $catalog_product_image_display){?>
				    <option value="<?php echo $image_display_product;?>" selected><?php echo $image_display_product;?></option>
				  <?php } else {?>
				    <option value="<?php echo $image_display_product;?>"><?php echo $image_display_product;?></option>
			      <?php }?>
				<?php }?> 
			  </select></td>
			</tr> 
			<tr bgcolor="#CCFFFF">
              <td><?php echo $entry_product_addtocart; ?></td>
              <td><select name="catalog_product_addtocart">
                  <?php if ($catalog_product_addtocart) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
			<tr bgcolor="#CCFFFF">
			  <td><?php echo $entry_options_select; ?></td>
			  <td><select name="catalog_product_options_select">
			        <option value="<?php echo $text_select;?>"<?php if($catalog_product_options_select == $text_select){echo ' selected';}?>><?php echo $text_select;?></option>
				    <option value="<?php echo $text_radio;?>"<?php if($catalog_product_options_select == $text_radio){echo ' selected';}?>><?php echo $text_radio;?></option>
			  </select></td>
			</tr>  
			<tr bgcolor="#CCFFFF">  <!-- Addition Product Images -->
			  <td><?php echo $entry_additional_width; ?></td>
			  <td><input type="text" name="catalog_additional_image_width" value="<?php echo $catalog_additional_image_width; ?>" size="3"></td>
			  <td><?php echo $entry_additional_height; ?></td>
			  <td><input type="text" name="catalog_additional_image_height" value="<?php echo $catalog_additional_image_height; ?>" size="3"></td>
			<tr>
			<tr bgcolor="#FFFF99">  <!-- Category -->
			  <td><?php echo $entry_category_width; ?></td>
			  <td><input type="text" name="catalog_category_image_width" value="<?php echo $catalog_category_image_width; ?>" size="3"></td>
			  <td><?php echo $entry_category_height; ?></td>
			  <td><input type="text" name="catalog_category_image_height" value="<?php echo $catalog_category_image_height; ?>" size="3"></td>
			<tr bgcolor="#FFFF99">
              <td><?php echo $entry_category_addtocart; ?></td>
              <td><select name="catalog_category_addtocart">
                  <?php if ($catalog_category_addtocart) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
			<tr bgcolor="#FFFF99">
			  <td><?php echo $entry_options_select; ?></td>
			  <td><select name="catalog_category_options_select">
			        <option value="<?php echo $text_select;?>"<?php if($catalog_category_options_select == $text_select){echo ' selected';}?>><?php echo $text_select;?></option>
				    <option value="<?php echo $text_radio;?>"<?php if($catalog_category_options_select == $text_radio){echo ' selected';}?>><?php echo $text_radio;?></option>
			  </select></td>
			</tr> 
			<tr bgcolor="#CCFFCC">  <!-- Search -->
			  <td><?php echo $entry_search_width; ?></td>
			  <td><input type="text" name="catalog_search_image_width" value="<?php echo $catalog_search_image_width; ?>" size="3"></td>
			  <td><?php echo $entry_search_height; ?></td>
			  <td><input type="text" name="catalog_search_image_height" value="<?php echo $catalog_search_image_height; ?>" size="3"></td>
			<tr bgcolor="#CCFFCC">
              <td><?php echo $entry_search_addtocart; ?></td>
              <td><select name="catalog_search_addtocart">
                  <?php if ($catalog_search_addtocart) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
			<tr bgcolor="#CCFFCC">
			  <td><?php echo $entry_options_select; ?></td>
			  <td><select name="catalog_search_options_select">
			        <option value="<?php echo $text_select;?>"<?php if($catalog_search_options_select == $text_select){echo ' selected';}?>><?php echo $text_select;?></option>
				    <option value="<?php echo $text_radio;?>"<?php if($catalog_search_options_select == $text_radio){echo ' selected';}?>><?php echo $text_radio;?></option>
			  </select></td>
			</tr> 
			<tr bgcolor="#CCFFCC">
              <td><?php echo $entry_search_limit; ?></td>
              <td><input type="text" name="catalog_search_limit" value="<?php echo $catalog_search_limit; ?>" size="3"></td>
            </tr>
			<tr bgcolor="#CCFFFF">
			  <td><?php echo $entry_image_display;?></td>
				<td><select name="catalog_content_image_display">
				<?php foreach($image_displays_content as $image_display_content){?>
				  <?php if($image_display_content == $catalog_content_image_display){?>
				    <option value="<?php echo $image_display_content;?>" selected><?php echo $image_display_content;?></option>
				  <?php } else {?>
				    <option value="<?php echo $image_display_content;?>"><?php echo $image_display_content;?></option>
			      <?php }?>
				<?php }?> 
			  </select></td>
			</tr>
			<tr bgcolor="#CCFFFF">
              <td><?php echo $entry_lines_single; ?></td>
              <td><input type="text" name="catalog_content_lines_single" value="<?php echo $catalog_content_lines_single; ?>" size="1" /></td>
            </tr>
			<tr bgcolor="#CCFFFF">
              <td><?php echo $entry_lines_multi; ?></td>
              <td><input type="text" name="catalog_content_lines_multi" value="<?php echo $catalog_content_lines_multi; ?>" size="1" /></td>
            </tr>
			<tr bgcolor="#CCFFFF">
              <td><?php echo $entry_lines_char; ?></td>
              <td><input type="text" name="catalog_content_lines_char" value="<?php echo $catalog_content_lines_char; ?>" size="3" /></td>
            </tr>
			<tr>
			  <td><?php echo $entry_addtocart_quantity; ?></td>
			  <td><select name="catalog_addtocart_quantity_box">
			    <?php foreach($quantity_selects as $quantity_select){?>
			      <option value="<?php echo $quantity_select;?>" <?php if($quantity_select == $catalog_addtocart_quantity_box){ echo ' selected';}?>><?php echo $quantity_select;?></option>
				<?php }?>
			  </select></td>
			</tr>
			<tr>
			  <td><?php echo $entry_addtocart_maximum; ?></td>
			  <td><input type="text" name="catalog_addtocart_quantity_max" value="<?php echo $catalog_addtocart_quantity_max;?>" size="4" /></td>
			</tr>
          </table>
		  <table>
		    <tr><td style="width: 100px;"></td><td>
			  <?php echo $text_instruction;?>
			</td></tr>
		  </table>
        </div>
      </div>   <!--  End Image and Add to Cart Controls -->
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185"><?php echo $entry_download; ?></td>
              <td><?php if ($catalog_config_download) { ?>
                <input type="radio" name="catalog_config_download" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="catalog_config_download" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="catalog_config_download" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="catalog_config_download" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_download_status; ?></td>
              <td><select name="catalog_config_download_status">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $catalog_config_download_status) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <script type="text/javascript"><!--
    function change_columns(column){
      var Style = $('#catalog_config_styles').val();
      var Column = column;
      var Query = String("style=" + Style + "&columns=" + Column);
      return Query;
    }
    function change_styles(style){
	 var Style = style;
	  var Column = $('#catalog_config_columns').val();
	  var Query = String("style=" + Style + "&columns=" + Column);
	  return Query;
    }
  //--></script>
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
  <script type="text/javascript"><!--
  $('#zone').load('index.php?controller=setting&action=zone&country_id=<?php echo $global_config_country_id; ?>&zone_id=<?php echo $global_config_zone_id; ?>');
  //--></script>
</form>
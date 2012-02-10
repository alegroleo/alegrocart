<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/list_disabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
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
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css">
<script type="text/javascript" src="javascript/ajax/jquery.js"></script>
<script type="text/javascript" src="javascript/ajax/tooltip.js"></script>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_shop; ?></div></a><a><div class="tab_text"><?php echo $tab_admin; ?></div></a><a><div class="tab_text"><?php echo $tab_local; ?></div></a><a><div class="tab_text"><?php echo $tab_stock; ?></div></a><a><div class="tab_text"><?php echo $tab_option; ?></div></a><a><div class="tab_text"><?php echo $tab_mail; ?></div></a><a><div class="tab_text"><?php echo $tab_cache; ?></div></a><a><div class="tab_text"><?php echo $tab_image; ?></div></a><a><div class="tab_text"><?php echo $tab_download; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><span class="required">*</span> <?php echo $entry_store; ?></td>
              <td><input type="text" name="global_config_store" value="<?php echo $global_config_store; ?>">
                <?php if ($error_store) { ?>
                <span class="error"><?php echo $error_store; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_owner; ?></td>
              <td><input type="text" name="global_config_owner" value="<?php echo $global_config_owner; ?>">
                <?php if ($error_owner) { ?>
                <span class="error"><?php echo $error_owner; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td valign="top" class="set"><span class="required">*</span> <?php echo $entry_address; ?></td>
              <td><textarea name="global_config_address" cols="40" rows="7"><?php echo $global_config_address; ?></textarea>
                <?php if ($error_address) { ?>
                <span class="error"><?php echo $error_address; ?></span>
                <?php } ?></td>
	      <td class="expl">
			    <?php echo $explanation_address; ?> 
	      </td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_telephone; ?></td>
              <td><input type="text" name="global_config_telephone" value="<?php echo $global_config_telephone; ?>">
                <?php if ($error_telephone) { ?>
                <span class="error"><?php echo $error_telephone; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_fax; ?></td>
              <td><input type="text" name="global_config_fax" value="<?php echo $global_config_fax; ?>"></td>
            </tr>
	    <tr>
	      <td colspan="2"><hr></td>
	    </tr>
            <tr>
              <td class="set"><?php echo $entry_template; ?></td>
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
			  <td class="set"><?php echo $entry_columns; ?></td>
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
              <td class="set"><?php echo $entry_styles; ?></td>
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
              <td class="set"><?php echo $entry_colors; ?></td>
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
              <td class="set"><?php echo $entry_items_per_page; ?></td>
              <td><input type="text" name="catalog_config_max_rows" value="<?php echo $catalog_config_max_rows; ?>" size="2"></td>
			  <td class="expl"><?php echo $text_items_per_page;?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_url_alias; ?></td>
              <td><?php if ($global_config_url_alias) { ?>
                <input type="radio" name="global_config_url_alias" value="1" id="gcuayes" checked>
                <label for="gcuayes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_url_alias" value="0" id="gcuano">
                <label for="gcuano"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_config_url_alias" value="1" id="gcuayes">
                <label for="gcuayes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_url_alias" value="0" id="gcuano" checked>
                <label for="gcuano"><?php echo $text_no; ?></label>
                <?php } ?></td>
            </tr>
			<tr>
              <td class="set"><?php echo $entry_seo; ?></td>
              <td><?php if ($global_config_seo) { ?>
                <input type="radio" name="global_config_seo" value="1" id="gcsyes" checked>
                <label for="gcsyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_seo" value="0" id="gcsno">
                <label for="gcsno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_config_seo" value="1" id="gcsyes">
                <label for="gcsyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_seo" value="0" id="gcsno" checked>
                <label for="gcsno"><?php echo $text_no; ?></label>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_parse_time; ?></td>
              <td><?php if ($catalog_config_parse_time) { ?>
                <input type="radio" name="catalog_config_parse_time" value="1" id="ccptyes" checked>
                <label for="ccptyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_parse_time" value="0" id="ccptno">
                <label for="ccptno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="catalog_config_parse_time" value="1" id="ccptyes">
                <label for="ccptyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_parse_time" value="0" id="ccptno" checked>
                <label for="ccptno"><?php echo $text_no; ?></label>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_ssl; ?></td>
              <td><?php if ($catalog_config_ssl) { ?>
                <input type="radio" name="catalog_config_ssl" value="1" id="ccsyes" checked>
                <label for="ccsyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_ssl" value="0" id="ccsno">
                <label for="ccsno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="catalog_config_ssl" value="1" id="ccsyes">
                <label for="ccsyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_ssl" value="0" id="ccsno" checked>
                <label for="ccsno"><?php echo $text_no; ?></label>
                <?php } ?></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><?php echo $entry_template; ?></td>
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
              <td class="set"><?php echo $entry_rows_per_page; ?></td>
              <td><input type="text" name="admin_config_max_rows" value="<?php echo $admin_config_max_rows; ?>" size="2"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_parse_time; ?></td>
              <td><?php if ($admin_config_parse_time) { ?>
                <input type="radio" name="admin_config_parse_time" value="1" id="acptyes" checked>
                <label for="acptyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="admin_config_parse_time" value="0" id="acptno">
                <label for="acptno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="admin_config_parse_time" value="1" id="acptyes">
                <label for="acptyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="admin_config_parse_time" value="0" id="acptno" checked>
                <label for="acptno"><?php echo $text_no; ?></label>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_ssl; ?></td>
              <td><?php if ($admin_config_ssl) { ?>
                <input type="radio" name="admin_config_ssl" value="1" id="acsyes" checked>
                <label for="acsyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="admin_config_ssl" value="0" id="acsno">
                <label for="acsno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="admin_config_ssl" value="1" id="acsyes">
                <label for="acsyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="admin_config_ssl" value="0" id="acsno" checked>
                <label for="acsno"><?php echo $text_no; ?></label>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_language; ?></td>
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

			<tr>
			  <td class="set"><?php echo $entry_token; ?></td>
			  <td id="token"><input type="text" size="40" <?php if($global_config_token){ echo 'readonly="readonly" ';}?>name="global_config_token" value="<?php echo $global_config_token;?>" onchange="$('#token').load('index.php?controller=setting&action=getToken&token='+encodeURIComponent(this.value)); ">
			  </td>
			  <td class="expl">
			      <?php echo $text_token; ?> 
			  </td>
			</tr>
			<tr> 
			  <td class="set"><?php echo $entry_session_expire; ?></td>
			  <td><input type="text" name="global_config_session_expire" value="<?php echo $global_config_session_expire; ?>" size="2"></td>
			  <td class="expl"><?php echo $explanation_session_expire; ?></td>
			</tr>
			<tr>
			  <td valign="top" class="set"><?php echo $entry_address_format; ?></td>
			  <td><textarea name="global_config_address_format" cols="50" rows="7"><?php echo $global_config_address_format; ?></textarea>
			  <td class="expl">
			      <?php echo $text_address_explantion; ?> 
			  </td>
			</tr>
			<tr>
			  <td colspan="2"><hr></td>
			</tr>
			<tr>
              <td class="set"><?php echo $entry_error_handler_status; ?></td>
              <td><?php if ($global_error_handler_status) { ?>
                <input type="radio" name="global_error_handler_status" value="1" id="ehsyes" checked>
                <label for="ehsyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_error_handler_status" value="0" id="ehsno">
                <label for="ehsno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_error_handler_status" value="1" id="ehsyes">
                <label for="ehsyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_error_handler_status" value="0" id="ehsno" checked>
                <label for="ehsno"><?php echo $text_no; ?></label>
                <?php } ?>
			  </td>
			  <td class="expl">
			    <?php echo $text_error_handler; ?> 
			  </td>
            </tr>
			
			<tr>
              <td class="set"><?php echo $entry_error_email_status; ?></td>
              <td><?php if ($global_error_email_status) { ?>
                <input type="radio" name="global_error_email_status" value="1" id="eesyes" checked>
                <label for="eesyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_error_email_status" value="0" id="eesno">
                <label for="eesno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_error_email_status" value="1" id="eesyes">
                <label for="eesyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_error_email_status" value="0" id="eesno" checked>
                <label for="eesno"><?php echo $text_no; ?></label>
                <?php } ?>
			  </td>
			  <td class="expl">
			    <?php echo $text_error_email_status; ?> 
			  </td>
            </tr>
			
			
			<tr>
              <td width="185" class="set"><?php echo $entry_error_email; ?></td>
              <td><input size="64" type="text" name="global_config_error_email" value="<?php echo $global_config_error_email; ?>"></td>
			  <td class="expl">
			    <?php echo $text_error_email; ?> 
			  </td>
            </tr>
			<tr>
              <td class="set"><?php echo $entry_error_show_user; ?></td>
              <td><?php if ($global_error_show_user) { ?>
                <input type="radio" name="global_error_show_user" value="1" id="esuyes" checked>
                <label for="esuyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_error_show_user" value="0" id="esuno">
                <label for="esuno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_error_show_user" value="1" id="esuyes">
                <label for="esuyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_error_show_user" value="0" id="esuno" checked>
                <label for="esuno"><?php echo $text_no; ?></label>
                <?php } ?>
			  </td>
			  <td class="expl">
			    <?php echo $text_error_show_user; ?> 
			  </td>
            </tr>
			<tr>
              <td class="set"><?php echo $entry_error_show_developer; ?></td>
              <td><?php if ($global_error_show_developer) { ?>
                <input type="radio" name="global_error_show_developer" value="1" id="esdyes" checked>
                <label for="esdyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_error_show_developer" value="0" id="esdno">
                <label for="esdno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_error_show_developer" value="1" id="esdyes">
                <label for="esdyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_error_show_developer" value="0" id="esdno" checked>
                <label for="esdno"><?php echo $text_no; ?></label>
                <?php } ?>
			  </td>
			  <td class="expl">
			    <?php echo $text_error_show_developer; ?> 
			  </td>
            </tr>
			<tr>
              <td width="185" class="set"><?php echo $entry_error_developer_ip; ?></td>
              <td><input size="64" type="text" name="global_error_developer_ip" value="<?php echo $global_error_developer_ip; ?>"></td>
			  <td class="expl">
			    <?php echo $text_error_developer_ip; ?> 
			  </td>
            </tr>
			
          </table>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="220" class="set"><?php echo $entry_country; ?></td>
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
              <td class="set"><?php echo $entry_zone; ?></td>
              <td id="zone"><select name="global_config_zone_id">
                </select></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_language; ?></td>
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
			  <td class="set"><?php echo $entry_time_zone;?></td>
			  <td><input type="text" name="global_config_time_zone" value="<?php echo $global_config_time_zone;?>"></td>
			  <td class="expl"><?php echo $text_time_zone;?></td>
			</tr>
            <tr>
              <td class="set"><?php echo $entry_currency; ?></td>
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
			  <td class="set"><?php echo $entry_currency_surcharge;?></td>
			  <td><input type="text" name="global_config_currency_surcharge" value="<?php echo $global_config_currency_surcharge;?>"></td>
			  <td class="expl"><?php echo $text_surcharge;?></td>
			</tr>
			<tr> <!--  Moved Barcode -->
              <td class="set"><?php echo $entry_barcode; ?></td>
              <td><select name="global_config_barcode_encoding">
                  <?php if ($global_config_barcode_encoding == 'upc') { ?>
                  <option value="upc" selected><?php echo $text_upc; ?></option>
                  <option value="ean"><?php echo $text_ean; ?></option>
                  <?php } else { ?>
                  <option value="upc"><?php echo $text_upc; ?></option>
                  <option value="ean" selected><?php echo $text_ean; ?></option>
                  <?php } ?>
                </select></td>
			</tr>
			<tr><td colspan="2"><hr></td></tr>
            <tr>
              <td class="set"><?php echo $entry_weight; ?></td>
              <td><select name="global_config_weight_class_id">
                  <?php foreach ($weight_classes as $weight_class) { ?>
                  <?php if ($weight_class['weight_class_id'] == $global_config_weight_class_id) { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>" selected><?php echo $weight_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
				<td class="expl"><?php echo $explanation_default_weight; ?></td>
            </tr>
		       <tr> <!--  New Weight Decimal Places -->
			  <td class="set"><?php echo $entry_weight_decimal; ?></td>
			  <td><input type="text" name="global_config_weight_decimal" value="<?php echo $global_config_weight_decimal; ?>" size="2"></td>
			  <td class="expl"><?php echo $text_weight_decimal; ?></td>
			</tr>
			<tr><td colspan="2"><hr></td></tr>
			<tr>
			  <td class="set"><?php echo $entry_dimension_type; ?></td>
			  <td><select name="global_config_dimension_type_id">
			    <?php foreach($types as $type){?>
			      <option value="<?php echo $type['type_id'];?>"<?php if($type['type_id'] == $global_config_dimension_type_id){ echo ' selected';}?>><?php echo $type['type_text'];?></option>
			    <?php }?>
			  </select></td>
			</tr>
		  <?php for($i=1; $i < 4; $i++){?>
			<?php if(isset($dimensions[$i])){?>
			  <tr>
			    <td class="set"><?php echo $entry_dimension[$i]; ?></td>
			    <td><select name="global_config_dimension_<?php echo $i;?>_id">
			      <?php foreach($dimensions[$i] as $dimension){?>
				    <?php $global_config_dimension = ('global_config_dimension_' . $i . '_id');?>
			        <option value="<?php echo $dimension['dimension_id'];?>"<?php if($$global_config_dimension == $dimension['dimension_id']){echo ' selected';}?>><?php echo $dimension['title'] . ' (' . $dimension['unit'] . ')';?></option>
				  <?php }?>
			    </select></td>
			  </tr>
			<?php }?>
		  <?php }?>
		    <tr>
			  <td class="set"><?php echo $entry_dimension_decimal; ?></td>
			  <td><input type="text" name="global_config_dimension_decimal" value="<?php echo $global_config_dimension_decimal; ?>" size="2"></td>
			  <td class="expl"><?php echo $text_dimension_decimal; ?></td>
			</tr>
			<tr><td colspan="2"><hr></td></tr>
            <tr>
              <td class="set"><?php echo $entry_tax; ?></td>
              <td><?php if ($global_config_tax) { ?>
                <input type="radio" name="global_config_tax" value="1" id="gctyes" checked>
                <label for="gctyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_tax" value="0" id="gctno">
                <label for="gctno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_config_tax" value="1" id="gctyes">
                <label for="gctyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_tax" value="0" id="gctno" checked>
                <label for="gctno"><?php echo $text_no; ?></label>
                <?php } ?></td>
				<td class="expl"><?php echo $text_prices_tax;?></td>
            </tr>
			<tr>
              <td class="set"><?php echo $entry_tax_store; ?></td>
              <td><?php if ($global_config_tax_store) { ?>
                <input type="radio" name="global_config_tax_store" value="1" id="gctsyes" checked>
                <label for="gctsyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_tax_store" value="0" id="gctsno">
                <label for="gctsno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_config_tax_store" value="1" id="gctsyes">
                <label for="gctsyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_tax_store" value="0" id="gctsno" checked>
                <label for="gctsno"><?php echo $text_no; ?></label>
                <?php } ?></td>
				<td class="expl"><?php echo $text_tax_products;?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_order_status; ?></td>
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
			<tr>
			  <td class="set"><?php echo $entry_invoice_number; ?></td>
			  <td><input type="text" name="global_invoice_number" value="<?php echo $global_invoice_number; ?>" size="16" maxlength="32"></td>
			  <td class="expl"><?php echo $text_invoice_number;?></td>
			</tr>
          </table>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><?php echo $entry_stock_check; ?></td>
              <td><?php if ($catalog_config_stock_check) { ?>
                <input type="radio" name="catalog_config_stock_check" value="1"id="ccscyes"  checked>
                <label for="ccscyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_stock_check" value="0" id="ccscno">
                <label for="ccscno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="catalog_config_stock_check" value="1" id="ccscyes">
                <label for="ccscyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_stock_check" value="0" id="ccscno" checked>
                <label for="ccscno"><?php echo $text_no; ?></label>
                <?php } ?></td>
				<td class="expl">
				  <?php echo $text_check_stock_explantion; ?> 
				</td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_stock_checkout; ?></td>
              <td><?php if ($catalog_config_stock_checkout) { ?>
                <input type="radio" name="catalog_config_stock_checkout" value="1" id="ccscoyes" checked>
                <label for="ccscoyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_stock_checkout" value="0" id="ccscono">
                <label for="ccscono"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="catalog_config_stock_checkout" value="1" id="ccscoyes">
                <label for="ccscoyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_stock_checkout" value="0" id="ccscono" checked>
                <label for="ccscono"><?php echo $text_no; ?></label>
                <?php } ?></td>
				<td class="expl">
				  <?php echo $text_allow_checkout_explantion; ?> 
				</td>
            </tr>
            <tr>
              <td width="185" class="set"><?php echo $entry_stock_subtract; ?></td>
              <td width="100"><?php if ($catalog_config_stock_subtract) { ?>
                <input type="radio" name="catalog_config_stock_subtract" value="1" id="ccssyes" checked>
                <label for="ccssyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_stock_subtract" value="0" id="ccssno">
                <label for="ccssno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="catalog_config_stock_subtract" value="1" id="ccssyes">
                <label for="ccssyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_stock_subtract" value="0" id="ccssno" checked>
                <label for="ccssno"><?php echo $text_no; ?></label>
                <?php } ?></td>
				<td class="expl">
				  <?php echo $text_subtract_stock_explantion; ?> 
				</td>
			</tr>
			<tr><td colspan="2"><hr></td></tr>
			<tr>
              <td width="185" class="set"><?php echo $entry_show_stock; ?></td>
              <td width="100"><?php if ($catalog_config_show_stock) { ?>
                <input type="radio" name="catalog_config_show_stock" value="1" id="stockyes" checked>
                <label for="stockyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_show_stock" value="0" id="stockno">
                <label for="stockno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="catalog_config_show_stock" value="1" id="stockyes">
                <label for="stockyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_show_stock" value="0" id="stockno" checked>
                <label for="stockno"><?php echo $text_no; ?></label>
                <?php } ?></td>
				<td class="expl">
				  <?php echo $text_show_stock; ?> 
				</td>
			</tr>
			
			<tr>
              <td width="185" class="set"><?php echo $entry_show_stock_icon; ?></td>
              <td width="100"><?php if ($catalog_config_show_stock_icon) { ?>
                <input type="radio" name="catalog_config_show_stock_icon" value="1" id="sticonyes" checked>
                <label for="sticonyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_show_stock_icon" value="0" id="sticonno">
                <label for="sticonno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="catalog_config_show_stock_icon" value="1" id="sticonyes">
                <label for="sticonyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_show_stock_icon" value="0" id="sticonno" checked>
                <label for="sticonno"><?php echo $text_no; ?></label>
                <?php } ?></td>
				<td class="expl">
				  <?php echo $explanation_stock_icon; ?> 
				</td>
			</tr>
			
			<tr>
			  <td class="set"><?php echo $entry_low_stock_warning; ?></td>
			  <td><input type="text" name="catalog_config_low_stock_warning" value="<?php echo $catalog_config_low_stock_warning; ?>" size="3" maxlength="3"></td>
			  <td class="expl"><?php echo $explanation_stock_warning;?></td>
			</tr>
			<tr><td colspan="2"><hr></td></tr>
			<tr>
              <td width="185" class="set"><?php echo $entry_discount_options; ?></td>
              <td width="100"><?php if ($catalog_config_discount_options) { ?>
                <input type="radio" name="catalog_config_discount_options" value="1" id="doyes" checked>
                <label for="doyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_discount_options" value="0" id="dono">
                <label for="dono"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="catalog_config_discount_options" value="1" id="doyes">
                <label for="doyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_discount_options" value="0" id="dono" checked>
                <label for="dono"><?php echo $text_no; ?></label>
                <?php } ?></td>
				<td class="expl">
				  <?php echo $explanation_option_discount; ?> 
				</td>
			</tr>
			
			<tr>
              <td width="185" class="set"><?php echo $entry_guest_checkout; ?></td>
              <td width="100"><?php if ($catalog_config_guest_checkout) { ?>
                <input type="radio" name="catalog_config_guest_checkout" value="1" id="ccgcyes" checked>
                <label for="ccgcyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_guest_checkout" value="0" id="ccgcno">
                <label for="ccgcno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="catalog_config_guest_checkout" value="1" id="ccgcyes">
                <label for="ccgcyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_guest_checkout" value="0" id="ccgcno" checked>
                <label for="ccgcno"><?php echo $text_no; ?></label>
                <?php } ?></td>
				<td class="expl">
				  <?php echo $text_guest_checkout; ?> 
				</td>
			</tr>
          </table>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="165" class="set"><?php echo $entry_vat; ?></td>
              <td width="130"><input type="text" name="catalog_config_vat" value="<?php echo $catalog_config_vat; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_account; ?></td>
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
              <td class="set"><?php echo $entry_checkout; ?></td>
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
			
			<tr>
              <td class="set"><?php echo $entry_rss_status; ?></td>
              <td><?php if ($global_config_rss_status) { ?>
                <input type="radio" name="global_config_rss_status" value="1" id="rssyes" checked>
                <label for="rssyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_rss_status" value="0" id="rssno">
                <label for="rssno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_config_rss_status" value="1" id="rssyes">
                <label for="rssyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_rss_status" value="0" id="rssno" checked>
                <label for="rssno"><?php echo $text_no; ?></label>
                <?php } ?>
			  </td>
			  <td class="expl">
			    <?php echo $text_rss_status; ?> 
			  </td>
            </tr>
			<tr>
			  <td class="set"><?php echo $entry_rss_source; ?></td>
			  <td><select name="global_config_rss_source">
			    <?php foreach($rss_sources as $key => $rss_source){?>
				  <option value="<?php echo $key;?>"<?php if($key == $global_config_rss_source) { echo ' selected';}?>><?php echo $rss_source;?></option>
				<?php }?>
			  </select></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_rss_limit; ?></td>
			  <td><input type="text" name="global_config_rss_limit" value="<?php echo $global_config_rss_limit; ?>" size="6"></td>
			  <td class="expl"><?php echo $text_rss_info;?></td>
			</tr>
			<tr><td colspan="2"><hr></td></tr>
          </table>
		  
		  <table>
			<tr><td width="165" style="color:#0099FF; font-weight:bold"><p><?php echo $text_store_logo;?></p></td>
			  <td width="130"></td>
			</tr>
		    <tr>
			  <td class="set"><?php echo $entry_logo;?></td>
		      <td><select id="logo_id" name="catalog_config_store_logo" onchange="$('#logo_image').load('index.php?controller=setting&action=viewLogo&store_logo='+this.value);">
			    <?php foreach ($logos as $logo){?>
				  <option value="<?php echo $logo['logo'];?>"<?php if($logo['logo'] == $catalog_config_store_logo){echo ' selected';}?>><?php echo $logo['logo'];?></option>
			    <?php }?>
			  </select></td>
			<td class="logo_image" id="logo_image"></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_logo_left;?></td>
			  <td><input type="text" name="catalog_config_logo_left" value="<?php echo $catalog_config_logo_left; ?>" size="6"></td>
			  <td class="expl"><?php echo $text_logo_left_exp;?></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_logo_top;?></td>
			  <td><input type="text" name="catalog_config_logo_top" value="<?php echo $catalog_config_logo_top; ?>" size="6"></td>
			  <td class="expl"><?php echo $text_logo_top_exp;?></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_logo_width;?></td>
			  <td><input type="text" name="catalog_config_logo_width" value="<?php echo $catalog_config_logo_width; ?>" size="6"></td>
			  
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_logo_height;?></td>
			  <td><input type="text" name="catalog_config_logo_height" value="<?php echo $catalog_config_logo_height; ?>" size="6"></td>
			  
			</tr>
			<tr><td colspan="2"><hr></td></tr>
		  </table>
		  <table>
		    <tr><td width="165" style="color:#0099FF; font-weight:bold"><p><?php echo $text_footer_logo;?></p></td>
			  <td width="130"></td>
			</tr>
		    <tr>
			  <td class="set"><?php echo $entry_footer_logo;?></td>
		      <td><select id="footer_logo_id" name="catalog_config_footer_logo" onchange="$('#footer_logo_image').load('index.php?controller=setting&action=viewFooterLogo&footer_logo='+this.value);">
			    <option value="0"><?php echo $text_none; ?></option>
			    <?php foreach ($logos as $logo){?>
				  <option value="<?php echo $logo['logo'];?>"<?php if($logo['logo'] == $catalog_config_footer_logo){echo ' selected';}?>><?php echo $logo['logo'];?></option>
			    <?php }?>
			  </select></td>
			<td class="footer_logo_image" id="footer_logo_image"></td>
			</tr>
		    <tr>
			  <td class="set"><?php echo $entry_footer_logo_left;?></td>
			  <td><input type="text" name="catalog_footer_logo_left" value="<?php echo $catalog_footer_logo_left; ?>" size="6"></td>
			  <td class="expl"><?php echo $text_footer_left_exp;?></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_footer_logo_top;?></td>
			  <td><input type="text" name="catalog_footer_logo_top" value="<?php echo $catalog_footer_logo_top; ?>" size="6"></td>
			  <td class="expl"><?php echo $text_footer_top_exp;?></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_footer_logo_width;?></td>
			  <td><input type="text" name="catalog_footer_logo_width" value="<?php echo $catalog_footer_logo_width; ?>" size="6"></td>
			  
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_footer_logo_height;?></td>
			  <td><input type="text" name="catalog_footer_logo_height" value="<?php echo $catalog_footer_logo_height; ?>" size="6"></td>
			</tr>
		  <tr><td colspan="2"><hr></td></tr>
		</table>
		<table>
		  <tr><td  width="165" style="color:#0099FF; font-weight:bold"><p><?php echo $text_captcha;?></p></td> <td width="130"></td>
		  </tr>
		  <tr>
			<td class="set"><?php echo $entry_captcha_contactus; ?></td>
			<td><?php if ($catalog_captcha_contactus) { ?>
			<input type="radio" name="catalog_captcha_contactus" value="1" id="gcccyes" checked>
			<label for="gcccyes"><?php echo $text_yes; ?></label>
			<input type="radio" name="catalog_captcha_contactus" value="0" id="gcccno">
			<label for="gcccno"><?php echo $text_no; ?></label>
			<?php } else { ?>
			<input type="radio" name="catalog_captcha_contactus" value="1" id="gcccyes">
			<label for="gcccyes"><?php echo $text_yes; ?></label>
			<input type="radio" name="catalog_captcha_contactus" value="0" id="gcccno" checked>
			<label for="gcccno"><?php echo $text_no; ?></label>
			<?php } ?>
			</td>
			<td class="expl">
			    <?php echo $text_captcha_contactus; ?> 
			</td>
		  </tr>
		  <tr>
			<td class="set"><?php echo $entry_captcha_reg; ?></td>
			<td><?php if ($catalog_captcha_reg) { ?>
			<input type="radio" name="catalog_captcha_reg" value="1" id="gccryes" checked>
			<label for="gccryes"><?php echo $text_yes; ?></label>
			<input type="radio" name="catalog_captcha_reg" value="0" id="gccrno">
			<label for="gccrno"><?php echo $text_no; ?></label>
			<?php } else { ?>
			<input type="radio" name="catalog_captcha_reg" value="1" id="gccryes">
			<label for="gccryes"><?php echo $text_yes; ?></label>
			<input type="radio" name="catalog_captcha_reg" value="0" id="gccrno" checked>
			<label for="gccrno"><?php echo $text_no; ?></label>
			<?php } ?>
			</td>
			<td class="expl">
			    <?php echo $text_captcha_reg; ?> 
			</td>
		  </tr>
		  <tr>
			<td class="set"><?php echo $entry_captcha_length ?></td>
			<td><select name="catalog_captcha_length">
			      <option value="5"<?php if('5'==$catalog_captcha_length) { echo ' selected';}?>>5</option>
			      <option value="6"<?php if('6'==$catalog_captcha_length) { echo ' selected';}?>>6</option>
			      <option value="7"<?php if('7'==$catalog_captcha_length) { echo ' selected';}?>>7</option>
			      <option value="8"<?php if('8'==$catalog_captcha_length) { echo ' selected';}?>>8</option>
			  </select>
			</td>
			<td class="expl">
			    <?php echo $text_captcha_length; ?> 
			</td>
		 </tr>
	    </table>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><?php echo $entry_email; ?></td>
              <td><input size="64" type="text" name="global_config_email" value="<?php echo $global_config_email; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_email_send; ?></td>
              <td><?php if ($global_config_email_send) { ?>
                <input type="radio" name="global_config_email_send" value="1" id="gcesyes" checked>
                <label for="gcesyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_email_send" value="0" id="gcesno">
                <label for="gcesno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_config_email_send" value="1" id="gcesyes">
                <label for="gcesyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_email_send" value="0" id="gcesno" checked>
                <label for="gcesno"><?php echo $text_no; ?></label>
                <?php } ?></td>
            </tr> 
	    <tr><td colspan="2"><hr></td>
	    </tr>
	 
			<tr>
			  <td style="color:#0099FF; font-weight:bold"><p><?php echo $text_emails; ?></p></td>
			</tr>
			<tr>
			  <td width="185" class="set"><?php echo $entry_email_orders; ?></td>
			  <td><input size="64" type="text" name="global_config_email_orders" value="<?php echo $global_config_email_orders; ?>"></td>
			</tr>
			<tr>
			  <td width="185" class="set"><?php echo $entry_email_accounts; ?></td>
			  <td><input size="64" type="text" name="global_config_email_accounts" value="<?php echo $global_config_email_accounts; ?>"></td>
			</tr>
			</tr>
			<tr>
			  <td width="185" class="set"><?php echo $entry_email_newsletter; ?></td>
			  <td><input size="64" type="text" name="global_config_email_newsletter" value="<?php echo $global_config_email_newsletter; ?>"></td>
			</tr>
			</tr>
			<tr>
			  <td width="185" class="set"><?php echo $entry_email_mail; ?></td>
			  <td><input size="64" type="text" name="global_config_email_mail" value="<?php echo $global_config_email_mail; ?>"></td>
			</tr>
			</tr>
			<tr>
			  <td width="185" class="set"><?php echo $entry_email_contact; ?></td>
			  <td><input size="64" type="text" name="global_config_email_contact" value="<?php echo $global_config_email_contact; ?>"></td>
			</tr>
          </table>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td class="set"><?php echo $entry_cache_query; ?></td>
              <td><?php if ($global_config_cache_query) { ?>
                <input type="radio" name="global_config_cache_query" value="1" id="gccqyes" checked>
                <label for="gccqyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_cache_query" value="0" id="gccqno">
                <label for="gccqno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_config_cache_query" value="1" id="gccqyes">
                <label for="gccqyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_cache_query" value="0" id="gccqno" checked>
                <label for="gccqno"><?php echo $text_no; ?></label>
                <?php } ?></td>
            </tr>
            <tr>
              <td width="185" class="set"><?php echo $entry_compress_output; ?></td>
              <td><?php if ($global_config_compress_output) { ?>
                <input type="radio" name="global_config_compress_output" value="1" id="gccoyes" checked>
                <label for="gccoyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_compress_output" value="0" id="gccono">
                <label for="gccono"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_config_compress_output" value="1" id="gccoyes">
                <label for="gccoyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_compress_output" value="0" id="gccono" checked>
                <label for="gccono"><?php echo $text_no; ?></label>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_compress_level; ?></td>
              <td><input type="text" name="global_config_compress_level" value="<?php echo $global_config_compress_level; ?>" size="3"></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="page">  <!--  Start Image and Add to Cart Controls -->
	<div id="tabmini">
	  <div class="tabs">
	  <a><div class="tab_text"><?php echo $tab_general; ?></div></a>
	  <a><div class="tab_text"><?php echo $tab_watermark; ?></div></a>
	  </div>
	  <div class="pages">
            <div class="page">
             <div class="minipad">
          <table>
            <tr>
              <td width="185" class="set"><?php echo $entry_image_resize; ?></td>
              <td><?php if ($global_config_image_resize) { ?>
                <input type="radio" name="global_config_image_resize" value="1" id="gciryes" checked>
                <label for="gciryes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_image_resize" value="0" id="gcirno">
                <label for="gcirno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_config_image_resize" value="1" id="gciryes">
                <label for="gciryes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_config_image_resize" value="0" id="gcirno" checked>
                <label for="gcirno"><?php echo $text_no; ?></label>
                <?php } ?></td>
            </tr>
            <tr>   <!-- Default Image Size -->
              <td class="set"><?php echo $entry_image_width; ?></td>
              <td><input type="text" name="global_config_image_width" value="<?php echo $global_config_image_width; ?>" size="3"></td>
              <td class="set"><?php echo $entry_image_height; ?></td>
              <td><input type="text" name="global_config_image_height" value="<?php echo $global_config_image_height; ?>" size="3"></td>			  
			</tr>
			<tr><td colspan="4"><hr></td>
			</tr>
			<tr>   <!-- Product -->
			  <td style="color:#0099FF; font-weight:bold"><p><?php echo $text_product; ?></p></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_product_width; ?></td>
			  <td><input type="text" name="catalog_product_image_width" value="<?php echo $catalog_product_image_width; ?>" size="3"></td>
			  <td class="set"><?php echo $entry_product_height; ?></td>
			  <td><input type="text" name="catalog_product_image_height" value="<?php echo $catalog_product_image_height; ?>" size="3"></td>
			</tr>
			<tr>  <!-- Addition Product Images -->
			  <td class="set"><?php echo $entry_additional_width; ?></td>
			  <td><input type="text" name="catalog_additional_image_width" value="<?php echo $catalog_additional_image_width; ?>" size="3"></td>
			  <td class="set"><?php echo $entry_additional_height; ?></td>
			  <td><input type="text" name="catalog_additional_image_height" value="<?php echo $catalog_additional_image_height; ?>" size="3"></td>
			<tr/>
			<tr>
			  <td class="set"><?php echo $entry_image_display;?></td>
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
			<tr>
              <td class="set"><?php echo $entry_product_addtocart; ?></td>
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
			<tr>
			  <td class="set"><?php echo $entry_options_select; ?></td>
			  <td><select name="catalog_product_options_select">
			        <option value="<?php echo $text_select;?>"<?php if($catalog_product_options_select == $text_select){echo ' selected';}?>><?php echo $text_select;?></option>
				    <option value="<?php echo $text_radio;?>"<?php if($catalog_product_options_select == $text_radio){echo ' selected';}?>><?php echo $text_radio;?></option>
			  </select></td>
			</tr>  
			<tr>
			  <td class="set"><?php echo $entry_alt_description; ?></td>
			  <td><?php if ($catalog_alternate_description) { ?>
			    <input type="radio" name="catalog_alternate_description" value="1" id="cadyes" checked>
			    <label for="cadyes"><?php echo $text_yes; ?></label>
			    <input type="radio" name="catalog_alternate_description" value="0" id="cadno">
			    <label for="cadno"><?php echo $text_no; ?></label>
			    <?php } else { ?>
			    <input type="radio" name="catalog_alternate_description" value="1" id="cadyes">
			    <label for="cadyes"><?php echo $text_yes; ?></label>
			    <input type="radio" name="catalog_alternate_description" value="0" id="cadno" checked>
			    <label for="cadno"><?php echo $text_no; ?></label>
			    <?php } ?></td>
			</tr>
			<tr> 
			  <td class="set"><?php echo $entry_magnifier; ?></td>
			  <td><?php if ($catalog_magnifier) { ?>
			  <input type="radio" name="catalog_magnifier" value="1" id="myes" checked>
			  <label for="myes"><?php echo $text_yes; ?></label>
			  <input type="radio" name="catalog_magnifier" value="0" id="mno">
			  <label for="mno"><?php echo $text_no; ?></label>
			  <?php } else { ?>
			  <input type="radio" name="catalog_magnifier" value="1" id="myes">
			  <label for="myes"><?php echo $text_yes; ?></label>
			  <input type="radio" name="catalog_magnifier" value="0" id="mno" checked>
			  <label for="mno"><?php echo $text_no; ?></label>
			  <?php } ?></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_magnifier_width; ?></td>
			  <td><input type="text" name="catalog_magnifier_width" value="<?php echo $catalog_magnifier_width; ?>" size="3"></td>
			  <td class="set"><?php echo $entry_magnifier_height; ?></td>
			  <td><input type="text" name="catalog_magnifier_height" value="<?php echo $catalog_magnifier_height; ?>" size="3"></td>
			</tr>
			<tr>
			  <td colspan="4"><hr></td>
			</tr>
			<tr>  <!-- Category -->
			  <td style="color:#0099FF; font-weight:bold"><p><?php echo $text_category; ?></p></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_category_width; ?></td>
			  <td><input type="text" name="catalog_category_image_width" value="<?php echo $catalog_category_image_width; ?>" size="3"></td>
			  <td class="set"><?php echo $entry_category_height; ?></td>
			  <td><input type="text" name="catalog_category_image_height" value="<?php echo $catalog_category_image_height; ?>" size="3"></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_items_per_page; ?></td>
			  <td><input type="text" name="catalog_category_rows" value="<?php echo $catalog_category_rows; ?>" size="3"></td>
			  <td colspan="2"><td class="expl"><?php echo $text_default_rows; ?></td>
			</tr>
			<tr>
              <td class="set"><?php echo $entry_category_addtocart; ?></td>
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
			<tr>
			  <td class="set"><?php echo $entry_options_select; ?></td>
			  <td><select name="catalog_category_options_select">
			        <option value="<?php echo $text_select;?>"<?php if($catalog_category_options_select == $text_select){echo ' selected';}?>><?php echo $text_select;?></option>
				    <option value="<?php echo $text_radio;?>"<?php if($catalog_category_options_select == $text_radio){echo ' selected';}?>><?php echo $text_radio;?></option>
			  </select></td>
			</tr> 
			<tr><td colspan="4"><hr></td>
			</tr>
			<tr>  <!-- Search -->
			  <td style="color:#0099FF; font-weight:bold"><p><?php echo $text_search; ?></p></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_search_width; ?></td>
			  <td><input type="text" name="catalog_search_image_width" value="<?php echo $catalog_search_image_width; ?>" size="3"></td>
			  <td class="set"><?php echo $entry_search_height; ?></td>
			  <td><input type="text" name="catalog_search_image_height" value="<?php echo $catalog_search_image_height; ?>" size="3"></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_items_per_page; ?></td>
			  <td><input type="text" name="catalog_search_rows" value="<?php echo $catalog_search_rows; ?>" size="3"></td>
			  <td colspan="2"><td class="expl"><?php echo $text_default_rows; ?></td>
			</tr>
			<tr>
              <td class="set"><?php echo $entry_search_addtocart; ?></td>
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
			<tr>
			  <td class="set"><?php echo $entry_options_select; ?></td>
			  <td><select name="catalog_search_options_select">
			        <option value="<?php echo $text_select;?>"<?php if($catalog_search_options_select == $text_select){echo ' selected';}?>><?php echo $text_select;?></option>
				    <option value="<?php echo $text_radio;?>"<?php if($catalog_search_options_select == $text_radio){echo ' selected';}?>><?php echo $text_radio;?></option>
			  </select></td>
			</tr> 
			<tr>
              <td class="set"><?php echo $entry_search_limit; ?></td>
              <td><input type="text" name="catalog_search_limit" value="<?php echo $catalog_search_limit; ?>" size="3"></td>
            </tr>
	    <tr><td colspan="4"><hr></td>
	    </tr>
			<tr> <!-- Category + Search -->
			  <td style="color:#0099FF; font-weight:bold"><p><?php echo $text_category_search;?><p></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_image_display;?></td>
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
			<tr>
              <td class="set"><?php echo $entry_lines_single; ?></td>
              <td><input type="text" name="catalog_content_lines_single" value="<?php echo $catalog_content_lines_single; ?>" size="1" /></td>
	      <td colspan="2"><td class="expl"><?php echo $text_instruction;?></td>
            </tr>
			<tr>
              <td class="set"><?php echo $entry_lines_multi; ?></td>
              <td><input type="text" name="catalog_content_lines_multi" value="<?php echo $catalog_content_lines_multi; ?>" size="1" /></td>
            </tr>
			<tr>
              <td class="set"><?php echo $entry_lines_char; ?></td>
              <td><input type="text" name="catalog_content_lines_char" value="<?php echo $catalog_content_lines_char; ?>" size="3" /></td>
            </tr><tr><td colspan="4"><hr></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_addtocart_quantity; ?></td>
			  <td><select name="catalog_addtocart_quantity_box">
			    <?php foreach($quantity_selects as $quantity_select){?>
			      <option value="<?php echo $quantity_select;?>" <?php if($quantity_select == $catalog_addtocart_quantity_box){ echo ' selected';}?>><?php echo $quantity_select;?></option>
				<?php }?>
			  </select></td>
			  <td colspan="2"><td class="expl"><?php echo $text_cart_wide; ?></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_addtocart_maximum; ?></td>
			  <td><input type="text" name="catalog_addtocart_quantity_max" value="<?php echo $catalog_addtocart_quantity_max;?>" size="4" /></td>
			  <td colspan="2"></td><td class="expl"><?php echo $text_cart_quantity; ?></td>
			</tr>
           </table>
	  </div>
	  </div>
	  <div class="page">
	  <div class="minipad">
	  <table>
	     <tr> 
		  <td style="color:#0099FF; font-weight:bold"><p><?php echo $text_wm_with_text; ?></p></td>
	     </tr>  
	     <tr>
		  <td width="185" class="set"><?php echo $entry_wm_text; ?></td>
		  <td><input size="48" type="text" name="wm_text" value="<?php echo $wm_text; ?>">
		  <?php if ($error_wm_text) { ?>
		  <span class="error"><?php echo $error_wm_text; ?></span>
		  <?php } ?></td>
			      <td class="expl">
				<?php echo $explanation_wm_text; ?> 
		  </td>
	      </tr>
	      <tr> 
		  <td class="set"><?php echo $entry_wm_fontsize; ?></td>
		  <td><select name="wm_font">
                  <?php foreach ($font_sizes as $font_size) { ?>
                  <?php if ($font_size == $wm_font) { ?>
                  <option value="<?php echo $font_size; ?>" selected><?php echo $font_size; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $font_size; ?>"><?php echo $font_size; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
		  <td class="expl">
		      <?php echo $explanation_wm_fontsize; ?> 
		    </td>
	      </tr>
	      <tr>
		  <td width="185" class="set"><?php echo $entry_wm_fontcolor; ?></td>
		  <td><input type="text" name="wm_fontcolor" value="<?php echo $wm_fontcolor; ?>" size="6">
		  <?php if ($error_wm_fontcolor) { ?>
		  <span class="error"><?php echo $error_wm_fontcolor; ?></span>
		  <?php } ?></td>
		  <td class="expl">
		    <?php echo $explanation_wm_fontcolor; ?> 
		  </td>
	      </tr>
	      <tr>
		  <td width="185" class="set"><?php echo $entry_wm_transparency; ?></td>
		  <td><input type="text" name="wm_transparency" value="<?php echo $wm_transparency; ?>" size="4">
		  <?php if ($error_wm_transparency) { ?>
		  <span class="error"><?php echo $error_wm_transparency; ?></span>
		  <?php } ?></td>
		  <td class="expl">
		    <?php echo $explanation_wm_transparency; ?> 
		  </td>
	      </tr>
	      <tr>
		  <td class="set"><?php echo $entry_wm_thposition ?></td>
		  <td><select name="wm_thposition">
			<option value="LEFT"<?php if('LEFT'==$wm_thposition) { echo ' selected';}?>><?php echo $text_left ?></option>
			<option value="CENTER"<?php if('CENTER'==$wm_thposition) { echo ' selected';}?>><?php echo $text_center ?></option>
			<option value="RIGHT"<?php if('RIGHT'==$wm_thposition) { echo ' selected';}?>><?php echo $text_right ?></option>
		    </select>
		  </td>
		  <td class="expl">
		      <?php echo $explanation_wm_thposition; ?> 
		  </td>
	       </tr>
	       <tr>
		  <td class="set"><?php echo $entry_wm_tvposition ?></td>
		  <td><select name="wm_tvposition">
			<option value="TOP"<?php if('TOP'==$wm_tvposition) { echo ' selected';}?>><?php echo $text_top ?></option>
			<option value="CENTER"<?php if('CENTER'==$wm_tvposition) { echo ' selected';}?>><?php echo $text_center ?></option>
			<option value="BOTTOM"<?php if('BOTTOM'==$wm_tvposition) { echo ' selected';}?>><?php echo $text_bottom ?></option>
		    </select>
		  </td>
		  <td class="expl">
		      <?php echo $explanation_wm_tvposition; ?> 
		  </td>
		</tr>
	        <tr>
		  <td width="185" class="set"><?php echo $entry_wm_thmargin; ?></td>
		  <td><input type="text" name="wm_thmargin" value="<?php echo $wm_thmargin; ?>" size="4"></td>
		  <td class="expl">
		    <?php echo $explanation_wm_thmargin; ?> 
		  </td>
	      </tr>
	      <tr>
		  <td width="185" class="set"><?php echo $entry_wm_tvmargin; ?></td>
		  <td><input type="text" name="wm_tvmargin" value="<?php echo $wm_tvmargin; ?>" size="4"></td>
		  <td class="expl">
		    <?php echo $explanation_wm_tvmargin; ?> 
		  </td>
	      </tr>
              </tr>
		  <tr><td colspan="2"><hr></td>
		  </tr>
	      <tr> 
		  <td style="color:#0099FF; font-weight:bold"><p><?php echo $text_wm_with_image; ?></p></td>
	      </tr> 
	      <tr>
		 <td class="set"><?php echo $entry_wm_image;?></td>
		  <td><select id="wm_image_id" name="wm_image" onchange="$('#wm_image').load('index.php?controller=setting&action=viewWmImage&wm_image='+this.value);">
			<option value="0"><?php echo $text_none; ?></option>
			<?php foreach ($images as $image){?>
			      <option value="<?php echo $image['image'];?>"<?php if($image['image'] == $wm_image){echo ' selected';}?>><?php echo $image['image'];?></option>
			<?php }?>
		      </select></td>
		  <td class="expl">
		    <?php echo $explanation_wm_image; ?> 
		  </td> 
	      </tr>
	      <tr>
		  <td class="wm_image" id="wm_image" colspan="3"></td>
	      </tr>
	      <tr>
		  <td width="185" class="set"><?php echo $entry_wm_scale; ?></td>
		  <td><input type="text" name="wm_scale" value="<?php echo $wm_scale; ?>" size="4">
		  <?php if ($error_wm_scale) { ?>
		  <span class="error"><?php echo $error_wm_scale; ?></span>
		  <?php } ?></td>
		  <td class="expl">
		    <?php echo $explanation_wm_scale; ?> 
		  </td>
	      </tr>
	      <tr>
		<td class="set"><?php echo $entry_wm_ihposition ?></td>
		<td><select name="wm_ihposition">
		      <option value="LEFT"<?php if('LEFT'==$wm_ihposition) { echo ' selected';}?>><?php echo $text_left ?></option>
		      <option value="CENTER"<?php if('CENTER'==$wm_ihposition) { echo ' selected';}?>><?php echo $text_center ?></option>
		      <option value="RIGHT"<?php if('RIGHT'==$wm_ihposition) { echo ' selected';}?>><?php echo $text_right ?></option>
		  </select>
		</td>
		<td class="expl">
		    <?php echo $explanation_wm_ihposition; ?> 
		</td>
	      </tr>
	      <tr>
		<td class="set"><?php echo $entry_wm_ivposition ?></td>
		<td><select name="wm_ivposition">
		      <option value="TOP"<?php if('TOP'==$wm_ivposition) { echo ' selected';}?>><?php echo $text_top ?></option>
		      <option value="CENTER"<?php if('CENTER'==$wm_ivposition) { echo ' selected';}?>><?php echo $text_center ?></option>
		      <option value="BOTTOM"<?php if('BOTTOM'==$wm_ivposition) { echo ' selected';}?>><?php echo $text_bottom ?></option>
		  </select>
		</td>
		<td class="expl">
		    <?php echo $explanation_wm_ivposition; ?> 
		</td>
	      </tr>
	      <tr>
		  <td width="185" class="set"><?php echo $entry_wm_ihmargin; ?></td>
		  <td><input type="text" name="wm_ihmargin" value="<?php echo $wm_ihmargin; ?>" size="4"></td>
		  <td class="expl">
		    <?php echo $explanation_wm_ihmargin; ?> 
		  </td>
	      </tr>
	      <tr>
		  <td width="185" class="set"><?php echo $entry_wm_ivmargin; ?></td>
		  <td><input type="text" name="wm_ivmargin" value="<?php echo $wm_ivmargin; ?>" size="4"></td>
		  <td class="expl">
		    <?php echo $explanation_wm_ivmargin; ?> 
		  </td>
	      </tr>
	  </table>
	 </div>
	 </div>
	</div> 
       </div>
      </div>   <!--  End Image and Add to Cart Controls -->
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><?php echo $entry_download; ?></td>
              <td><?php if ($catalog_config_download) { ?>
                <input type="radio" name="catalog_config_download" value="1" id="ccdyes" checked>
                <label for="ccdyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_download" value="0" id="ccdno">
                <label for="ccdno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="catalog_config_download" value="1" id="ccdyes">
                <label for="ccdyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_download" value="0" id="ccdno" checked>
                <label for="ccdno"><?php echo $text_no; ?></label>
                <?php } ?></td>
	      <td class="expl">
		<?php echo $explanation_pr_download; ?> 
	      </td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_download_status; ?></td>
              <td><select name="catalog_config_download_status">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $catalog_config_download_status) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
	      <td class="expl">
		<?php echo $explanation_pr_download_status; ?> 
	      </td>
            </tr>
	    <tr><td colspan="4"><hr></td>
	    <tr>
              <td width="185" class="set"><?php echo $entry_free_download; ?></td>
              <td><?php if ($catalog_config_freedownload) { ?>
                <input type="radio" name="catalog_config_freedownload" value="1" id="ccfdyes" checked>
                <label for="ccfdyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_freedownload" value="0" id="ccfdno">
                <label for="ccfdno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="catalog_config_freedownload" value="1" id="ccfdyes">
                <label for="ccfdyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="catalog_config_freedownload" value="0" id="ccfdno" checked>
                <label for="ccfdno"><?php echo $text_no; ?></label>
                <?php } ?></td>
	      <td class="expl">
		<?php echo $explanation_free_download; ?> 
	      </td>
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
	function removeSpaces(string) {
	return string.split(' ').join('');
  }
  //--></script>
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
 <script type="text/javascript"><!--
  tabview_initialize('tabmini');
  //--></script>
  <script type="text/javascript"><!--
  $('#zone').load('index.php?controller=setting&action=zone&country_id=<?php echo $global_config_country_id; ?>&zone_id=<?php echo $global_config_zone_id; ?>');
  //--></script>
  <script type="text/javascript"><!--
    $('#logo_image').load('index.php?controller=setting&action=viewLogo&store_logo='+document.getElementById('logo_id').value);
  //--></script>
  <script type="text/javascript"><!--
    $('#footer_logo_image').load('index.php?controller=setting&action=viewFooterLogo&footer_logo='+document.getElementById('footer_logo_id').value);
  //--></script>
  <script type="text/javascript"><!--
      $('#wm_image').load('index.php?controller=setting&action=viewWmImage&wm_image='+document.getElementById('wm_image_id').value);
    //--></script>
</form>
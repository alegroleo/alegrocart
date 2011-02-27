<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/default/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png" /><?php echo $button_list; ?></div>
  <div class="disabled"><img src="template/default/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png" /><?php echo $button_insert; ?></div>
  <div class="disabled"><img src="template/default/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png" /><?php echo $button_update; ?></div>
  <div class="disabled"><img src="template/default/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png" /><?php echo $button_delete; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/default/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png" /><?php echo $button_save; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/default/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png" /><?php echo $button_cancel; ?></div>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css" />
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><?php echo $tab_general; ?></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><?php echo $entry_status; ?></td>
              <td><select name="catalog_manufacturer_status">
                  <?php if ($catalog_manufacturer_status) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_addtocart; ?></td>
              <td><select name="catalog_manufacturer_addtocart">
                  <?php if ($catalog_manufacturer_addtocart) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
			<tr>
			  <td class="set"><?php echo $entry_height; ?></td>
			  <td><input type="text" name="catalog_manufacturer_image_height" value="<?php echo $catalog_manufacturer_image_height; ?>" size="3"></td>
			  <td class="expl"><?php echo $text_image;?></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_width; ?></td>
			  <td><input type="text" name="catalog_manufacturer_image_width" value="<?php echo $catalog_manufacturer_image_width; ?>" size="3"></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_columns; ?></td>
			  <td><select name="catalog_manufacturer_columns">
			    <?php foreach($columns as $column){?>
				  <?php if($column == $catalog_manufacturer_columns) {?>
				    <option value="<?php echo $column;?>" selected><?php echo $column;?></option>
				  <?php } else { ?>
				    <option value="<?php echo $column;?>"><?php echo $column;?></option>
				  <?php } ?>
				<?php } ?>
			  </td>				 
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_items_per_page; ?></td>
			  <td><input type="text" name="catalog_manufacturer_rows" value="<?php echo $catalog_manufacturer_rows; ?>" size="3"></td>
			  <td class="expl"><?php echo $text_default_rows; ?></td>
			<tr>
              <td class="set"><?php echo $entry_display_lock; ?></td>
              <td><select name="catalog_manufacturer_display_lock">
                  <?php if ($catalog_manufacturer_display_lock) { ?>
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
			  <td><select name="catalog_manufacturer_options_select">
			        <option value="<?php echo $text_select;?>"<?php if($catalog_manufacturer_options_select == $text_select){echo ' selected';}?>><?php echo $text_select;?></option>
				    <option value="<?php echo $text_radio;?>"<?php if($catalog_manufacturer_options_select == $text_radio){echo ' selected';}?>><?php echo $text_radio;?></option>
			  </select></td>
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
</form>
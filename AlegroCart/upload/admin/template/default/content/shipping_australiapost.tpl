<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/<?php echo $this->directory?>/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
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
<div class="heading"><?php echo $heading_shipping; ?><em><?php echo $heading_title; ?></em></div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><?php echo $entry_status; ?></td>
              <td><select name="global_australiapost_status">
                  <?php if ($global_australiapost_status) { ?>
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
			  <td class="set"><?php echo $entry_postcode; ?></td>
              <td><input type="text" name="global_australiapost_postcode" value="<?php echo $global_australiapost_postcode; ?>" size="10"></td>
			  <td class="expl"><?php echo $explanation_postcode; ?></td>
			</tr>
            <tr>
              <td width="185" class="set"><?php echo $entry_geo_zone; ?></td>
              <td><select name="global_australiapost_geo_zone_id">
                  <option value="0"><?php echo $text_all_zones; ?></option>
                  <?php foreach ($geo_zones as $geo_zone) { ?>
                  <?php if ($geo_zone['geo_zone_id'] == $global_australiapost_geo_zone_id) { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected><?php echo $geo_zone['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
	          <td class="expl"><?php echo $explanation_entry_geo_zone; ?></td>
		    </tr>
		    <tr>
              <td class="set"><?php echo $entry_weight_class; ?></td>
              <td><select name="global_australiapost_weight_class">
                  <?php foreach ($weight_classes as $weight_class) { ?>
                  <?php if ($weight_class['weight_class_id'] == $global_australiapost_weight_class) { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>" selected><?php echo $weight_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
              </select></td>
			  <td class="expl"><?php echo $explanation_weight; ?></td>
            </tr>
		    <tr>
			  <td class="set"><?php echo $entry_dimension_class; ?></td>
			  <td><select name="global_australiapost_dimension_class">
			    <?php foreach($dimension_classes as $dimension_class){?>
			      <option value="<?php echo $dimension_class['dimension_id'];?>"<?php if($dimension_class['dimension_id'] == $global_australiapost_dimension_class){ echo ' selected';}?>><?php echo $dimension_class['title'];?></option>
			    <?php }?>
			  </select></td>
			  <td class="expl"><?php echo $explanation_dimension; ?></td>
			</tr>
			
			<tr>
			  <td class="set"><?php echo $entry_default_method; ?></td>
			  <td><select name="global_australiapost_default_method">
                  <?php foreach ($default_methods as $default_method) { ?>
                  <?php if ($default_method == $global_australiapost_default_method) { ?>
                  <option value="<?php echo $default_method; ?>" selected><?php echo $default_method; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $default_method; ?>"><?php echo $default_method; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
	          <td class="expl"><?php echo $explanation_entry_method; ?>
			</tr>
            <tr>
              <td class="set"><?php echo $entry_tax; ?></td>
              <td><select name="global_australiapost_tax_class_id">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($tax_classes as $tax_class) { ?>
                  <?php if ($tax_class['tax_class_id'] == $global_australiapost_tax_class_id) { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>" selected><?php echo $tax_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
	          <td class="expl"><?php echo $explanation_entry_tax; ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="global_australiapost_sort_order" value="<?php echo $global_australiapost_sort_order; ?>" size="1"></td>
	      <td class="expl"><?php echo $explanation_entry_sort_order; ?></td>
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

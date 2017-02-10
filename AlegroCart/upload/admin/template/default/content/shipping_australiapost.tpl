<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="getValues();document.getElementById('update_form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="global_australiapost_status" value="">
  <input type="hidden" name="global_australiapost_postcode" value="">
  <input type="hidden" name="global_australiapost_geo_zone_id" value="">
  <input type="hidden" name="global_australiapost_weight_class" value="">
  <input type="hidden" name="global_australiapost_tax_class_id" value="">
  <input type="hidden" name="global_australiapost_dimension_class" value="">
  <input type="hidden" name="global_australiapost_sort_order" value="">
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
<div class="heading"><?php echo $heading_shipping; ?><em><?php echo $heading_title; ?></em>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/ajax/jquery.js"></script>
<script type="text/javascript" src="javascript/ajax/validateforms.js"></script>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="form">
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
              <td><input class="validate_int" id="australiapost_postcode" type="text" name="global_australiapost_postcode" value="<?php echo $global_australiapost_postcode; ?>" size="10"></td>
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
              <td><input class="validate_int" id="australiapost_sort_order" type="text" name="global_australiapost_sort_order" value="<?php echo $global_australiapost_sort_order; ?>" size="1"></td>
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
  <script type="text/javascript"><!--
    $(document).ready(function() {
	  RegisterValidation();
    });
  //--></script>
  <script type="text/javascript"><!--
	function getValues() {
		document.forms['update_form'].global_australiapost_status.value=document.forms['form'].global_australiapost_status.value;
		document.forms['update_form'].global_australiapost_postcode.value=document.forms['form'].global_australiapost_postcode.value;
		document.forms['update_form'].global_australiapost_geo_zone_id.value=document.forms['form'].global_australiapost_geo_zone_id.value;
		document.forms['update_form'].global_australiapost_tax_class_id.value=document.forms['form'].global_australiapost_tax_class_id.value;
		document.forms['update_form'].global_australiapost_dimension_class.value=document.forms['form'].global_australiapost_dimension_class.value;
		document.forms['update_form'].global_australiapost_weight_class.value=document.forms['form'].global_australiapost_weight_class.value;
		document.forms['update_form'].global_australiapost_sort_order.value=document.forms['form'].global_australiapost_sort_order.value;
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
		url:     'index.php?controller=shipping_australiapost&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
</form>

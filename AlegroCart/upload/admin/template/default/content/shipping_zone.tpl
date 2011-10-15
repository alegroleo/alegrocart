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
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css">
<script type="text/javascript" src="javascript/ajax/jquery.js"></script>
<script type="text/javascript" src="javascript/ajax/tooltip.js"></script>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_module; ?></div></a><a><div class="tab_text"><?php echo $tab_data; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
		    <tr>
			  <td class="set"><?php echo $entry_module_status;?></td>
			  <td><select name="global_zone_status">
			    <option value="1"<?php if($global_zone_status){echo ' selected';}?>><?php echo $text_enabled; ?></option>
				<option value="0"<?php if(!$global_zone_status){echo ' selected';}?>><?php echo $text_disabled; ?></option>
			  </select></td>
			</tr>
			<tr>
              <td class="set"><?php echo $entry_tax; ?></td>
              <td><select name="global_zone_tax_class_id">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($tax_classes as $tax_class) { ?>
                  <?php if ($tax_class['tax_class_id'] == $global_zone_tax_class_id) { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>" selected><?php echo $tax_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
			<tr>
              <td class="set"><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="global_zone_sort_order" value="<?php echo $global_zone_sort_order; ?>" size="1"></td>
            </tr>
		  </table>
		</div>
	  </div>
	  <div class="page">
		<div class="pad">
		  <table>
		    <tr><td style="width: 360px;"><td><td>
			  <script type="text/javascript">
			    $(document).ready(function(){
				  $('.zoneE[title]').tooltip({
				  offset: [30,90], tipClass: 'tooltip_large'});
				});
			  </script>
			  <?php echo '<th style="color:red"><div title="' . $text_instruction . '" class="zoneE" >'. $text_zone_info . '</div></th>';?>
			</td></tr>
		  </table>
		  <table id="geo_zones">
			<tr>
			  <td style="width: 160px;" class="set"><?php echo $entry_geo_zone; ?></td>
			  <td style="width: 80px;" class="set"><?php echo $entry_status; ?></td>
			  <td style="width: 90px;" class="set"><?php echo $entry_free_amount; ?></td>
			  <td style="width: 490px; text-align: center;" class="set"><?php echo $entry_cost; ?></td>
			</tr>
			<tr><td colspan="4" style="width: 820px; text-align: center;" class="set"><?php echo $entry_message; ?></td></tr>
			<tr><td colspan="5"><hr></td></tr>
            <?php foreach ($geo_zones as $geo_zone) { ?>
            <tr id="geozone<?php echo $geo_zone['geo_zone_id'] ;?>A">
              <td width="160" class="set"><?php echo $geo_zone['name']; ?></td>
              <td><select name="geo_zone[<?php echo $geo_zone['geo_zone_id']; ?>][status]">
                  <?php if ($geo_zone['status']) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
				<td><input size="8" type="text" name="geo_zone[<?php echo $geo_zone['geo_zone_id']; ?>][free_amount]" value="<?php echo $geo_zone['free_amount']; ?>"></td>
				<td><input size="71" type="text" name="geo_zone[<?php echo $geo_zone['geo_zone_id']; ?>][cost]" value="<?php echo $geo_zone['cost']; ?>"></td>
            </tr>
            <tr id="geozone<?php echo $geo_zone['geo_zone_id'] ;?>B">
              <td width="160" class="set"><?php echo $geo_zone['description']; ?></td>
              <td colspan="3"><input size="100" type="text" name="geo_zone[<?php echo $geo_zone['geo_zone_id']; ?>][message]" value="<?php echo $geo_zone['message']; ?>"></td>
			  <td><input type="button" class="button" value="<?php echo $button_remove; ?>" onclick="removeZone('geozone<?php echo $geo_zone['geo_zone_id'] ;?>');"></td> 
            </tr>
			<tr id="geozone<?php echo $geo_zone['geo_zone_id'] ;?>C"><td colspan="5"><hr style="color: #EEEEEE;"></td></tr>
            <?php } ?>
          </table>
		  <table>
            <tr>
              <td colspan="5"><input type="button" class="button" value="<?php echo $button_add; ?>" onclick="getzones();"></td>
            </tr>
          </table>
		  
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
</form>
<script type="text/javascript"><!--
  tabview_initialize('tab');
//--></script>
<script type="text/javascript"><!--
function removeZone(row){
	$('#'+row+'A').remove();
	$('#'+row+'B').remove();
	$('#'+row+'C').remove();
}
function addzone(){
	GeoZone_id = $('#temp_select').val();
	$('#geo_select').remove();
	$.ajax({
   		type:    'GET',
		url:'index.php?controller=shipping_zone&action=addzone&geozone_id='+GeoZone_id,
		async:   false,
		success: function(data) {
     		$('#geo_zones').append(data);
   		}
 	});
}

function getzones(){
	$.ajax({
   		type:    'GET',
		url:'index.php?controller=shipping_zone&action=getzones',
		async:   false,
		success: function(data) {
     		$('#geo_zones').append(data);
   		}
 	});
}

//--></script>
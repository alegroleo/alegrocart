<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
  $head_def->set_admin_javascript("javascript/ajax/tooltip.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="getValues()"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="global_zone_status" value="">
  <input type="hidden" name="global_zone_tax_class_id" value="">
  <input type="hidden" name="global_zone_sort_order" value="">
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
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="form">
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
              <td><input class="validate_int" id="zone_sort_order" type="text" name="global_zone_sort_order" value="<?php echo $global_zone_sort_order; ?>" size="1"></td>
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
				<td><input class="validate_float" id="geo_zone<?php echo $geo_zone['geo_zone_id']; ?>free_amount" size="8" type="text" name="geo_zone[<?php echo $geo_zone['geo_zone_id']; ?>][free_amount]" value="<?php echo $geo_zone['free_amount']; ?>"></td>
				<td><input class="validate_shipping" id="geo_zone<?php echo $geo_zone['geo_zone_id']; ?>cost" size="71" type="text" name="geo_zone[<?php echo $geo_zone['geo_zone_id']; ?>][cost]" value="<?php echo $geo_zone['cost']; ?>"></td>
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
		url:     'index.php?controller=shipping_zone&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
  <script type="text/javascript"><!--
	$('.tabs a').on('click', function() {
	var activeTab = $(this).index()+1;
	var data_json = {'activeTab':activeTab};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=shipping_zone&action=tab',
		data: data_json,
		dataType:'json'
	});
	});
	function copyAllZones() {
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=shipping_zone&action=getzoneids',
		dataType:'json',
		success: function (data) {
			if (data.status===true) {
				var ids = data.zoneids;
				var html ='';
				for (i =0, len = ids.length; i < len; i++) {
					if (document.forms['form'].elements['geo_zone['+ids[i]+'][status]'] !=undefined){
						html +='<input type="hidden" name="geo_zone['+ids[i]+'][status]" value="' + document.forms['form'].elements['geo_zone['+ids[i]+'][status]'].value + '">';
						html +='<input type="hidden" name="geo_zone['+ids[i]+'][free_amount]" value="' + document.forms['form'].elements['geo_zone['+ids[i]+'][free_amount]'].value + '">';
						html +='<input type="hidden" name="geo_zone['+ids[i]+'][cost]" value="' + document.forms['form'].elements['geo_zone['+ids[i]+'][cost]'].value + '">';
						html +='<input type="hidden" name="geo_zone['+ids[i]+'][message]" value="' + document.forms['form'].elements['geo_zone['+ids[i]+'][message]'].value + '">';
					}
				}
				document.forms['update_form'].innerHTML += html;
				document.getElementById('update_form').submit();
			} else {
				$('<div class="warning"><?php echo $error_update; ?></div>').insertBefore(".heading");
			}
		}
	});
	}
	function getValues() {
		document.forms['update_form'].global_zone_status.value=document.forms['form'].global_zone_status.value;
		document.forms['update_form'].global_zone_tax_class_id.value=document.forms['form'].global_zone_tax_class_id.value;
		document.forms['update_form'].global_zone_sort_order.value=document.forms['form'].global_zone_sort_order.value;

		copyAllZones();
	}
  //--></script>
  <script type="text/javascript"><!--
  $(document).ready(function() {
	if (<?php echo $tab; ?>!=undefined && <?php echo $tab; ?> > 0) {
		tabview_switch('tab', <?php echo $tab; ?>);
	}
   });
  //--></script>

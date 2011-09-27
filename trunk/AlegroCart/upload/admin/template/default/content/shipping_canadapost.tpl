<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/<?php echo $this->directory?>/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
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
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

  <div class="tab" id="tab">
	<div class="tabs"><a><div class="tab_text"><?php echo $tab_required; ?></div></a><a><div class="tab_text"><?php echo $tab_optional; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
		  <table>
			<tr>
			  <td class="set"><?php echo $entry_status;?></td>
			  <td><select name="global_canadapost_status">
			    <option value="1"<?php if($global_canadapost_status){echo ' selected';}?>><?php echo $text_enabled; ?></option>
				<option value="0"<?php if(!$global_canadapost_status){echo ' selected';}?>><?php echo $text_disabled; ?></option>
			  </select></td>
			</tr>
			
			<tr>
              <td width="185" class="set"><?php echo $entry_geo_zone; ?></td>
              <td><select name="global_canadapost_geo_zone_id">
                  <option value="0"><?php echo $text_all_zones; ?></option>
                  <?php foreach ($geo_zones as $geo_zone) { ?>
                  <?php if ($geo_zone['geo_zone_id'] == $global_canadapost_geo_zone_id) { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected><?php echo $geo_zone['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
	          <td class="expl"><?php echo $explanation_geo_zone; ?></td>
		    </tr>
		   
		    <tr>
			  <td class="set"><?php echo $entry_canadapost_ip; ?></td>
              <td><input size="25" type="text" name="global_canadapost_ip" value="<?php echo $global_canadapost_ip; ?>" size="10"></td>
			  <td class="expl"><?php echo $explanation_canadapost_ip; ?></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_canadapost_port; ?></td>
              <td><input type="text" name="global_canadapost_port" value="<?php echo $global_canadapost_port; ?>" size="10"></td>
			  <td class="expl"><?php echo $explanation_canadapost_port; ?></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_merchant_id; ?></td>
              <td><input type="text" name="global_canadapost_merchant_id" value="<?php echo $global_canadapost_merchant_id; ?>" size="10"></td>
			  <td class="expl"><?php echo $explanation_merchant_id; ?></td>
			</tr>
			
			
			<tr>
              <td class="set"><?php echo $entry_tax; ?></td>
              <td><select name="global_canadapost_tax_class_id">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($tax_classes as $tax_class) { ?>
                  <?php if ($tax_class['tax_class_id'] == $global_canadapost_tax_class_id) { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>" selected><?php echo $tax_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
              </select></td>
			  <td class="expl"><?php echo $explanation_tax; ?></td>
            </tr>
			<tr>
              <td class="set"><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="global_canadapost_sort_order" value="<?php echo $global_canadapost_sort_order; ?>" size="1"></td>
			  <td class="expl"><?php echo $explanation_sort_order; ?></td>
            </tr>
		  </table>
		</div>
	  </div>
	  <div class="page">
		<div class="pad">
		  <table>
		    <tr>
			  <td class="set"><?php echo $entry_postcode; ?></td>
              <td><input type="text" name="global_canadapost_postcode" value="<?php echo $global_canadapost_postcode; ?>" size="10"></td>
			  <td class="expl"><?php echo $explanation_postcode; ?></td>
			</tr>
			<tr>
              <td class="set"><?php echo $entry_language; ?></td>
              <td><select name="global_canadapost_language">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($cp_languages as $cp_language) { ?>
                  <?php if ($cp_language['code'] == $global_canadapost_language) { ?>
                  <option value="<?php echo $cp_language['code']; ?>" selected><?php echo $cp_language['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $cp_language['code']; ?>"><?php echo $cp_language['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
			  <td class="expl"><?php echo $explanation_language; ?></td>
            </tr>
		   <tr>
			  <td class="set"><?php echo $entry_turnaround; ?></td>
              <td><input type="text" name="global_canadapost_turnaround" value="<?php echo $global_canadapost_turnaround; ?>" size="10"></td>
			  <td class="expl"><?php echo $explanation_turnaround; ?></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_readytoship;?></td>
			  <td><?php if ($global_canadapost_readytoship == 1) { ?>
                <input type="radio" name="global_canadapost_readytoship" value="1" checked id="rtsyes">
                <label for="rtsyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_canadapost_readytoship" value="0" id="rtsno">
                <label for="rtsno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_canadapost_readytoship" value="1" id="rtsyes">
                <label for="rtsyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_canadapost_readytoship" value="0" checked id="rtsno">
                <label for="rtsno"><?php echo $text_no; ?></label>
                <?php } ?></td>
			  <td class="expl"><?php echo $explanation_readytoship; ?></td>
			</tr>
			
			<tr>
			  <td class="set"><?php echo $entry_package;?></td>
			  <td><?php if ($global_canadapost_package == 1) { ?>
                <input type="radio" name="global_canadapost_package" value="1" checked id="pkgyes">
                <label for="pkgyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_canadapost_package" value="0" id="pkgno">
                <label for="pkgno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_canadapost_package" value="1" id="pkgyes">
                <label for="pkgyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_canadapost_package" value="0" checked id="pkgno">
                <label for="pkgno"><?php echo $text_no; ?></label>
                <?php } ?></td>
			  <td class="expl"><?php echo $explanation_package; ?></td>
			</tr>
			
		  </table>
		
		
	  </div>
	</div>	
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
</form>
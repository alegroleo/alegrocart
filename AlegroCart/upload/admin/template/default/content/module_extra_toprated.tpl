<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
?>
<div class="task">
  <div class="disabled"><img src="template/default/image/insert_disabled.png" width=32 height=32 alt="<?php echo $button_insert; ?>" class="png" /><?php echo $button_insert; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="getValues();document.getElementById('update_form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" width=32 height=32 alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="catalog_toprated_status" value="">
  <input type="hidden" name="catalog_toprated_rating" value="">
  <input type="hidden" name="catalog_toprated_total" value="">
  <input type="hidden" name="catalog_toprated_image_height" value="">
  <input type="hidden" name="catalog_toprated_image_width" value="">
  <input type="hidden" name="catalog_toprated_image_display" value="">
  <input type="hidden" name="catalog_toprated_ratings" value="">
  <input type="hidden" name="catalog_toprated_addtocart" value="">
  <input type="hidden" name="catalog_toprated_lines_single" value="">
  <input type="hidden" name="catalog_toprated_lines_multi" value="">
  <input type="hidden" name="catalog_toprated_lines_char" value="">
  <input type="hidden" name="catalog_toprated_slider" value="">
  <input type="hidden" name="catalog_toprated_scolumns" value="">
  <input type="hidden" name="catalog_toprated_slimit" value="">
  <input type="hidden" name="catalog_toprated_columns" value="">
  <input type="hidden" name="catalog_toprated_limit" value="">
</form>
  <div class="disabled"><img src="template/default/image/delete_disabled.png" width=32 height=32 alt="<?php echo $button_delete; ?>" class="png" /><?php echo $button_delete; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/default/image/save_enabled.png" width=32 height=32 alt="<?php echo $button_save; ?>" class="png" /><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" width=32 height=32 alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/default/image/cancel_enabled.png" width=32 height=32 alt="<?php echo $button_cancel; ?>" class="png" /><?php echo $button_cancel; ?></div>
<?php if (@$last) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $last; ?>'"><img src="template/<?php echo $this->directory?>/image/last_enabled.png" width=32 height=32 alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/last_disabled.png" width=32 height=32 alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } ?>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_module; ?><em><?php echo $heading_title; ?></em>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" width=31 height=30 alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><?php echo $entry_status; ?></td>
              <td><select name="catalog_toprated_status">
                  <?php if ($catalog_toprated_status) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
		<td class="expl">
			<?php echo($explanation_entry_status); ?>
		</td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_rating; ?></td>
              <td><input class="validate_float" id="toprated_rating" type="text" name="catalog_toprated_rating" value="<?php echo $catalog_toprated_rating; ?>" size="1"></td>
		<td class="expl">
			<?php echo($explanation_entry_rating); ?>
		</td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_total; ?></td>
              <td><input class="validate_int" id="toprated_total" type="text" name="catalog_toprated_total" value="<?php echo $catalog_toprated_total; ?>" size="3"></td>
		<td class="expl">
			<?php echo($explanation_entry_total); ?>
		</td>
            </tr>			
			<tr>
			  <td class="set"><?php echo $entry_height; ?></td>
			  <td><input class="validate_int" id="toprated_image_height" type="text" name="catalog_toprated_image_height" value="<?php echo $catalog_toprated_image_height; ?>" size="3"></td>
		<td class="expl">
			<?php echo($explanation_entry_height); ?>
		</td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_width; ?></td>
			  <td><input class="validate_int" id="toprated_image_width" type="text" name="catalog_toprated_image_width" value="<?php echo $catalog_toprated_image_width; ?>" size="3"></td>
		<td class="expl">
			<?php echo($explanation_entry_width); ?>
		</td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_image_display;?></td>
				<td><select name="catalog_toprated_image_display">
				<?php foreach($image_displays as $image_display){?>
				  <?php if($image_display == $catalog_toprated_image_display){?>
				    <option value="<?php echo $image_display;?>" selected><?php echo $image_display;?></option>
				  <?php } else {?>
				    <option value="<?php echo $image_display;?>"><?php echo $image_display;?></option>
			      <?php }?>
				<?php }?> 
			  </select></td>
		<td class="expl">
			<?php echo($explanation_entry_image_display); ?>
		</td>
			</tr>
		<tr>
              <td style="width:250px;" class="set"><?php echo $entry_ratings; ?></td>
              <td><select name="catalog_toprated_ratings">
                  <?php if ($catalog_toprated_ratings) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
		<td class="expl">
			<?php echo($explanation_entry_ratings); ?>
		</td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_addtocart; ?></td>
              <td><select name="catalog_toprated_addtocart">
                  <?php if ($catalog_toprated_addtocart) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
		<td class="expl">
			<?php echo($explanation_entry_addtocart); ?>
		</td>
            </tr>
			<tr>
              <td class="set"><?php echo $entry_lines_single; ?></td>
              <td><input class="validate_int" id="toprated_lines_single" type="text" name="catalog_toprated_lines_single" value="<?php echo $catalog_toprated_lines_single; ?>" size="1" /></td>
		<td class="expl">
			<?php echo($explanation_entry_lines_single); ?>
		</td>
            </tr>
			<tr>
              <td class="set"><?php echo $entry_lines_multi; ?></td>
              <td><input class="validate_int" id="toprated_lines_multi" type="text" name="catalog_toprated_lines_multi" value="<?php echo $catalog_toprated_lines_multi; ?>" size="1" /></td>
		<td class="expl">
			<?php echo($explanation_entry_lines_multi); ?>
		</td>
            </tr>
			<tr>
              <td class="set"><?php echo $entry_lines_char; ?></td>
              <td><input class="validate_int" id="toprated_lines_char" type="text" name="catalog_toprated_lines_char" value="<?php echo $catalog_toprated_lines_char; ?>" size="3" /></td>
		<td class="expl">
			<?php echo($explanation_entry_char); ?>
		</td>
            </tr>
            <tr>
              <td style="color:#0099FF; font-weight:bold"><?php if ($catalog_toprated_slider) { ?>
                <input type="radio" name="catalog_toprated_slider" value="1" id="cfsyes" checked="checked">
                <label for="cfsyes"><?php echo $entry_sliderb; ?></label>
                <?php } else { ?>
                <input type="radio" name="catalog_toprated_slider" value="1" id="cfsyes" >
                <label for="cfsyes"><?php echo $entry_sliderb; ?></label>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_columns; ?></td>
              <td><select name="catalog_toprated_scolumns">
				<?php foreach($scolumn_data as $scolumn){?>
				  <?php if($scolumn == $catalog_toprated_scolumns){?>
				    <option value="<?php echo $scolumn;?>" selected><?php echo $scolumn;?></option>
				  <?php } else {?>
				    <option value="<?php echo $scolumn;?>"><?php echo $scolumn;?></option>
			      <?php }?>
				<?php }?> 
			  </select></td>
		<td class="expl">
			<?php echo($explanation_entry_columns); ?>
		</td>
			</tr>
            <tr>
              <td class="set"><?php echo $entry_limit; ?></td>
              <td><input class="validate_int" id="toprated_slimit" type="text" name="catalog_toprated_slimit" value="<?php echo $catalog_toprated_slimit; ?>" size="1" /></td>
		<td class="expl">
			<?php echo($explanation_entry_slimit); ?>
		</td>
		</tr>
            <tr>
              <td style="color:#0099FF; font-weight:bold"><?php if ($catalog_toprated_slider) { ?>
                <input type="radio" name="catalog_toprated_slider" value="0" id="cfsno" >
                <label for="cfsno"><?php echo $entry_columnb; ?></label>
                <?php } else { ?>
                <input type="radio" name="catalog_toprated_slider" value="0" id="cfsno" checked="checked">
                <label for="cfsno"><?php echo $entry_columnb; ?></label>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_columns; ?></td>
              <td><select name="catalog_toprated_columns">
				<?php foreach($column_data as $column){?>
				  <?php if($column == $catalog_toprated_columns){?>
				    <option value="<?php echo $column;?>" selected><?php echo $column;?></option>
				  <?php } else {?>
				    <option value="<?php echo $column;?>"><?php echo $column;?></option>
			      <?php }?>
				<?php }?> 
			  </select></td>
		<td class="expl">
			<?php echo($explanation_entry_columns); ?>
		</td>
			</tr>
            <tr>
              <td class="set"><?php echo $entry_limit; ?></td>
              <td><input class="validate_int" id="toprated_limit" type="text" name="catalog_toprated_limit" value="<?php echo $catalog_toprated_limit; ?>" size="1" /></td>
		<td class="expl">
			<?php echo($explanation_entry_limit); ?>
		</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <script type="text/javascript">
  tabview_initialize('tab');
  </script>
  <script type="text/javascript">
	function getValues() {
		document.forms['update_form'].catalog_toprated_status.value=document.forms['form'].catalog_toprated_status.value;
		document.forms['update_form'].catalog_toprated_rating.value=document.forms['form'].catalog_toprated_rating.value;
		document.forms['update_form'].catalog_toprated_total.value=document.forms['form'].catalog_toprated_total.value;
		document.forms['update_form'].catalog_toprated_image_width.value=document.forms['form'].catalog_toprated_image_width.value;
		document.forms['update_form'].catalog_toprated_image_height.value=document.forms['form'].catalog_toprated_image_height.value;
		document.forms['update_form'].catalog_toprated_image_display.value=document.forms['form'].catalog_toprated_image_display.value;
		document.forms['update_form'].catalog_toprated_ratings.value=document.forms['form'].catalog_toprated_ratings.value;
		document.forms['update_form'].catalog_toprated_addtocart.value=document.forms['form'].catalog_toprated_addtocart.value;
		document.forms['update_form'].catalog_toprated_lines_single.value=document.forms['form'].catalog_toprated_lines_single.value;
		document.forms['update_form'].catalog_toprated_lines_multi.value=document.forms['form'].catalog_toprated_lines_multi.value;
		document.forms['update_form'].catalog_toprated_lines_char.value=document.forms['form'].catalog_toprated_lines_char.value;
		document.forms['update_form'].catalog_toprated_slider.value=document.forms['form'].catalog_toprated_slider.value;
		document.forms['update_form'].catalog_toprated_scolumns.value=document.forms['form'].catalog_toprated_scolumns.value;
		document.forms['update_form'].catalog_toprated_slimit.value=document.forms['form'].catalog_toprated_slimit.value;
		document.forms['update_form'].catalog_toprated_columns.value=document.forms['form'].catalog_toprated_columns.value;
		document.forms['update_form'].catalog_toprated_limit.value=document.forms['form'].catalog_toprated_limit.value;
	}
  </script>
  <script type="text/javascript">
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
		url:     'index.php?controller=module_extra_toprated&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
	  RegisterValidation();
    });
  </script>
</form>

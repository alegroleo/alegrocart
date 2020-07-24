<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
?>
<div class="task">
  <div class="disabled"><img src="template/default/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png" /><?php echo $button_insert; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="getValues();document.getElementById('update_form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="catalog_bought_status" value="">
  <input type="hidden" name="catalog_bought_image_height" value="">
  <input type="hidden" name="catalog_bought_image_width" value="">
  <input type="hidden" name="catalog_bought_ratings" value="">
  <input type="hidden" name="catalog_bought_addtocart" value="">
  <input type="hidden" name="catalog_bought_display_lock" value="">
  <input type="hidden" name="catalog_bought_options_select" value="">
  <input type="hidden" name="catalog_bought_columns" value="">
  <input type="hidden" name="catalog_bought_rows" value="">
</form>
  <div class="disabled"><img src="template/default/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png" /><?php echo $button_delete; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/default/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png" /><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/default/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png" /><?php echo $button_cancel; ?></div>
<?php if (@$last) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $last; ?>'"><img src="template/<?php echo $this->directory?>/image/last_enabled.png" alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/last_disabled.png" alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } ?>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_module; ?><em><?php echo $heading_title; ?></em>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
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
              <td><select name="catalog_bought_status">
                  <?php if ($catalog_bought_status) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
		<tr>
              <td style="width:250px;" class="set"><?php echo $entry_ratings; ?></td>
              <td><select name="catalog_bought_ratings">
                  <?php if ($catalog_bought_ratings) { ?>
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
			  <td class="set"><?php echo $entry_height; ?></td>
			  <td><input class="validate_int" id="bought_image_height" type="text" name="catalog_bought_image_height" value="<?php echo $catalog_bought_image_height; ?>" size="3"></td>
			  <td class="expl"><?php echo $explanation_image;?></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_width; ?></td>
			  <td><input class="validate_int" id="bought_image_width" type="text" name="catalog_bought_image_width" value="<?php echo $catalog_bought_image_width; ?>" size="3"></td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_columns; ?></td>
			  <td><select name="catalog_bought_columns">
			    <?php foreach($columns as $column){?>
				  <?php if($column == $catalog_bought_columns) {?>
				    <option value="<?php echo $column;?>" selected><?php echo $column;?></option>
				  <?php } else { ?>
				    <option value="<?php echo $column;?>"><?php echo $column;?></option>
				  <?php } ?>
				<?php } ?>
			  </td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_items_per_page; ?></td>
			  <td><input class="validate_int" id="bought_rows" type="text" name="catalog_bought_rows" value="<?php echo $catalog_bought_rows; ?>" size="3"></td>
			  <td class="expl"><?php echo $explanation_default_rows; ?></td>
			<tr>
              <td class="set"><?php echo $entry_display_lock; ?></td>
              <td><select name="catalog_bought_display_lock">
                  <?php if ($catalog_bought_display_lock) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
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
  tabview_initialize('tab');
  //--></script>
  <script type="text/javascript"><!--
	function getValues() {
		document.forms['update_form'].catalog_bought_status.value=document.forms['form'].catalog_bought_status.value;
		document.forms['update_form'].catalog_bought_image_width.value=document.forms['form'].catalog_bought_image_width.value;
		document.forms['update_form'].catalog_bought_image_height.value=document.forms['form'].catalog_bought_image_height.value;
		document.forms['update_form'].catalog_bought_ratings.value=document.forms['form'].catalog_bought_ratings.value;
		document.forms['update_form'].catalog_bought_addtocart.value=document.forms['form'].catalog_bought_addtocart.value;
		document.forms['update_form'].catalog_bought_options_select.value=document.forms['form'].catalog_bought_options_select.value;
		document.forms['update_form'].catalog_bought_display_lock.value=document.forms['form'].catalog_bought_display_lock.value;
		document.forms['update_form'].catalog_bought_columns.value=document.forms['form'].catalog_bought_columns.value;
		document.forms['update_form'].catalog_bought_rows.value=document.forms['form'].catalog_bought_rows.value;
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
		url:     'index.php?controller=module_extra_bought&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
  <script type="text/javascript"><!--
    $(document).ready(function() {
	  RegisterValidation();
    });
  //--></script>
</form>
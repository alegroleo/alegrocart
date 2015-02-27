<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/default/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png" /><?php echo $button_list; ?></div>
  <div class="disabled"><img src="template/default/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png" /><?php echo $button_insert; ?></div>
  <div class="disabled"><img src="template/default/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png" /><?php echo $button_update; ?></div>
  <div class="disabled"><img src="template/default/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png" /><?php echo $button_delete; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/default/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png" /><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/default/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png" /><?php echo $button_cancel; ?></div>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_module; ?><em><?php echo $heading_title; ?></em></div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/ajax/jquery.js"></script>
<script type="text/javascript" src="javascript/ajax/validateforms.js"></script>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css" />
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><?php echo $entry_status; ?></td>
              <td><select name="catalog_bestseller_status">
                  <?php if ($catalog_bestseller_status) { ?>
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
              <td class="set"><?php echo $entry_trending_days; ?></td>
              <td><input class="validate_int" id="bestseller_trendingdays" type="text" name="catalog_bestseller_trendingdays" value="<?php echo $catalog_bestseller_trendingdays; ?>" size="1"></td>
		<td class="expl">
			<?php echo($explanation_entry_trending_days); ?>
		</td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_limit; ?></td>
              <td><input class="validate_int" id="bestseller_limit" type="text" name="catalog_bestseller_limit" value="<?php echo $catalog_bestseller_limit; ?>" size="1"></td>
		<td class="expl">
			<?php echo($explanation_entry_limit); ?>
		</td>
            </tr>
			<tr>
              <td class="set"><?php echo $entry_columns; ?></td>
              <td><select name="catalog_bestseller_columns">
				<?php foreach($column_data as $column){?>
				  <?php if($column == $catalog_bestseller_columns){?>
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
              <td class="set"><?php echo $entry_total; ?></td>
              <td><input class="validate_int" id="bestseller_total" type="text" name="catalog_bestseller_total" value="<?php echo $catalog_bestseller_total; ?>" size="3"></td>
		<td class="expl">
			<?php echo($explanation_entry_total); ?>
		</td>
            </tr>			
			<tr>
			  <td class="set"><?php echo $entry_height; ?></td>
			  <td><input class="validate_int" id="bestseller_image_height" type="text" name="catalog_bestseller_image_height" value="<?php echo $catalog_bestseller_image_height; ?>" size="3"></td>
		<td class="expl">
			<?php echo($explanation_entry_height); ?>
		</td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_width; ?></td>
			  <td><input class="validate_int" id="bestseller_image_width" type="text" name="catalog_bestseller_image_width" value="<?php echo $catalog_bestseller_image_width; ?>" size="3"></td>
		<td class="expl">
			<?php echo($explanation_entry_width); ?>
		</td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_image_display;?></td>
				<td><select name="catalog_bestseller_image_display">
				<?php foreach($image_displays as $image_display){?>
				  <?php if($image_display == $catalog_bestseller_image_display){?>
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
              <td><select name="catalog_bestseller_ratings">
                  <?php if ($catalog_bestseller_ratings) { ?>
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
              <td><select name="catalog_bestseller_addtocart">
                  <?php if ($catalog_bestseller_addtocart) { ?>
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
              <td><input class="validate_int" id="bestseller_lines_single" type="text" name="catalog_bestseller_lines_single" value="<?php echo $catalog_bestseller_lines_single; ?>" size="1" /></td>
		<td class="expl">
			<?php echo($explanation_entry_lines_single); ?>
		</td>
            </tr>
			<tr>
              <td class="set"><?php echo $entry_lines_multi; ?></td>
              <td><input class="validate_int" id="bestseller_lines_multi" type="text" name="catalog_bestseller_lines_multi" value="<?php echo $catalog_bestseller_lines_multi; ?>" size="1" /></td>
		<td class="expl">
			<?php echo($explanation_entry_lines_multi); ?>
		</td>
            </tr>
			<tr>
              <td class="set"><?php echo $entry_lines_char; ?></td>
              <td><input class="validate_int" id="bestseller_lines_char" type="text" name="catalog_bestseller_lines_char" value="<?php echo $catalog_bestseller_lines_char; ?>" size="3" /></td>
		<td class="expl">
			<?php echo($explanation_entry_char); ?>
		</td>
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
</form>

<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/<?php echo $this->directory?>/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $insert; ?>'"><img src="template/<?php echo $this->directory?>/image/insert_enabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
<?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
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
<script type="text/javascript" src="javascript/ajax/jquery.js"></script>
<script type="text/javascript" src="javascript/fckeditor/fckeditor.js"></script>

<form action="<?php echo $action_flash; ?>" method="post" enctype="multipart/form-data">
  <table align="center">			
	<tr>
	  <td class="set"><?php echo $entry_filename;?></td>
		<td><input size="64" type="text" id="fileName" class="file_input_textbox" readonly="readonly">
	      <div class="file_input_div">
	      <input type="button" value="<?php echo $text_browse; ?>" class="file_input_button" />
	      <input type="file" name="flashimage" class="file_input_hidden" onchange="javascript: document.getElementById('fileName').value = this.value" />
	      </div></td>
	      <td><input type="submit" class="submit" value="<?php echo $button_upload;?>">
		</td>
	</tr>
  </table>
</form>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
	<div class="tabs"><a><?php echo $tab_name; ?></a><a><?php echo $tab_description; ?></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_name; ?></td>
	      <td><input size="32" maxlength="64" name="name" value="<?php echo $name;?>"></td>
	      <td class="expl">
                <?php echo($explanation_entry_name); ?>
              </td>
	   </tr>
           <tr>
              <td class="set"><?php echo $entry_status; ?></td>
              <td><select name="status">
                  <?php if ($status == '1') { ?>
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
			  <td class="set"><?php echo $entry_sort_order;?></td>
			  <td><input type="text" name="sort_order" value="<?php echo $sort_order;?>"></td>
	     <td class="expl">
                <?php echo($explanation_entry_sort_order); ?>
             </td>
	    </tr>
	    <tr>
			  <td class="set"><?php echo $entry_location;?></td>
			  <td><select name="location_id">
			    <?php foreach($locations as $location){?>
			    <option value="<?php echo $location['location_id'];?>" <?php if($location['location_id'] == $location_id){ echo 'selected';}?>><?php echo $location['location'];?></option>
				<?php } ?>
			  </select></td>
	     <td class="expl">
                <?php echo($explanation_entry_location); ?>
            </td>
	   </tr>
	  </table>
		</div>
	  </div>
      <div class="page">
        <div id="tabmini">
          <div class="tabs">
		    <?php foreach($image_display_descriptions as $image_display_description){?>
			  <a><?php echo $image_display_description['language'];?></a>
			<?php }?>
		  </div>
		  <div class="pages">
		    <?php foreach($image_display_descriptions as $image_display_description){?>
		    <div class="page">
			  <div class="minipad">
				<table>
				  <tr>
				    <td style="width: 185px;" class="set"><?php echo $entry_flash_width; ?></td>
				    <td><input name="flash_width[<?php echo $image_display_description['language_id']; ?>]" value="<?php echo $image_display_description['flash_width'];?>"></td>
				    <td class="expl">
					<?php echo($explanation_entry_flash_width); ?>
				    </td>
				  </tr>
				  <tr>
				    <td class="set"><?php echo $entry_flash_height; ?></td>
					<td><input name="flash_height[<?php echo $image_display_description['language_id']; ?>]" value="<?php echo $image_display_description['flash_height'];?>"></td>
				    <td class="expl">
					<?php echo($explanation_entry_flash_height); ?>
				    </td>
				  </tr>
				  <tr>
				    <td class="set"><?php echo $entry_flash_loop; ?></td>
					<td><select name="flash_loop[<?php echo $image_display_description['language_id']; ?>]">
					  <?php if ($image_display_description['flash_loop'] == '1') { ?>
						<option value="1" selected><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
					  <?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected><?php echo $text_disabled; ?></option>
					  <?php } ?>
					</select></td>
				    <td class="expl">
					<?php echo($explanation_entry_flash_loop); ?>
				    </td>
				  </tr>
				  <tr>
				    <td class="set"><?php echo $entry_flash; ?></td>
					<td id="f_upload<?php echo $image_display_description['language_id']; ?>">
					  <select name="flash[<?php echo $image_display_description['language_id']; ?>]" id="flash<?php echo $image_display_description['language_id']; ?>" onchange="$('#flash_name<?php echo $image_display_description['language_id']; ?>').load('index.php?controller=image_display&action=viewFlash&flash='+this.value);">
					      <option value=""><?php echo $text_noflash;?></option>
				      <?php foreach($flash_files as $flash){?>
						<?php if($flash['flash'] == $image_display_description['flash']) {?>
						  <option value="<?php echo $flash['flash']; ?>" selected><?php echo $flash['flash']; ?></option>
						<?php } else {?>
						  <option value="<?php echo $flash['flash']; ?>"><?php echo $flash['flash']; ?></option>
						<?php }?>
					  <?php }?>
				    </select></td>
					<td class="flash_image" id="flash_name<?php echo $image_display_description['language_id']; ?>"></td>
				  </tr>
				</table>
				<table>
				  <tr>
				    <td style="width: 185px;" class="set"><?php echo $entry_image_width; ?></td>
					<td><input name="image_width[<?php echo $image_display_description['language_id']; ?>]" value="<?php echo $image_display_description['image_width'];?>"></td>
				  <td class="expl">
					<?php echo($explanation_entry_image_width); ?>
				    </td></tr>
				  <tr>
				    <td style="width: 185px;" class="set"><?php echo $entry_image_height; ?></td>
					<td><input name="image_height[<?php echo $image_display_description['language_id']; ?>]" value="<?php echo $image_display_description['image_height'];?>"></td>
				    <td class="expl">
					<?php echo($explanation_entry_image_height); ?>
				    </td></tr>
				  <tr>
				    <td style="width: 185px" class="set"><?php echo $entry_image; ?></td>
                    <td><select name="image_id[<?php echo $image_display_description['language_id']; ?>]" id="image_id<?php echo $image_display_description['language_id']; ?>" onchange="$('#image<?php echo $image_display_description['language_id']; ?>').load('index.php?controller=image&action=view&image_id='+this.value);">
						  <option value=""><?php echo $text_noimage;?></option>
                      <?php foreach ($images as $image) { ?>
                        <?php if ($image['image_id'] == $image_display_description['image_id']) { ?>
						  <option value="<?php echo $image['image_id']; ?>" selected><?php echo $image['title']; ?></option>
					    <?php } else { ?>
						  <option value="<?php echo $image['image_id']; ?>"><?php echo $image['title']; ?></option>
					    <?php } ?>
					  <?php } ?>
				    </select></td>
				    <td></td>
				    <td class="product_image" id="image<?php echo $image_display_description['language_id']; ?>"></td>
				  </tr>
				</table>
		      </div>
		    </div>
			<?php } ?>
		  </div>
		</div>
	  </div>
	</div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
</form> 
 
 <?php foreach($languages as $language){?>
   <script type="text/javascript"><!--
     if(document.getElementById('flash<?php echo $language['language_id'];?>').value){
	 $('#flash_name<?php echo $language['language_id']; ?>').load('index.php?controller=image_display&action=viewFlash&flash='+document.getElementById('flash<?php echo $language['language_id'];?>').value);
	 }
	 if(document.getElementById('image_id<?php echo $language['language_id'];?>').value){
     $('#image<?php echo $language['language_id'];?>').load('index.php?controller=image&action=view&image_id='+document.getElementById('image_id<?php echo $language['language_id'];?>').value);
	 }
    //--></script>
  <?php } ?>
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
  <script type="text/javascript"><!--
  tabview_initialize('tabmini');
  //--></script>
  
<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/<?php echo $this->directory?>/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $insert; ?>'"><img src="template/<?php echo $this->directory?>/image/insert_enabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
<?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $delete; ?>'"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
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
  <table>			
	<tr>
	  <td><?php echo $entry_filename;?></td>
		<td><input size="64" type="file" name="flashimage"></td>
		<td><input type="submit" value="<?php echo $button_upload;?>">
		</td>
	</tr>
  </table>
</form>
 
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
	<div class="tabs"><a><?php echo $tab_name; ?></a><a><?php echo $tab_description; ?></a><a><?php echo $tab_meta; ?></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td><span class="required">*</span><?php echo $entry_name; ?></td>
			  <td><input size="32" maxlength="64" name="name" value="<?php echo $name;?>"></td>
			</tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="status">
                  <?php if ($status == '1') { ?>
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
      <div class="page">
        <div id="tabmini">
          <div class="tabs">
		    <?php foreach($home_descriptions as $home_description){?>
			  <a><?php echo $home_description['language'];?></a>
			<?php }?>
		  </div>
		  <div class="pages">
		    <?php foreach($home_descriptions as $home_description){?>
		    <div class="page">
			  <div class="minipad">
				<table>
				  <tr>
					<td style="width: 185px;"><?php echo $entry_run_times;?></td>
					<td style="width: 185px;"><input size="10" name="run_times[<?php echo $home_description['language_id']; ?>]" value="<?php echo $home_description['run_times'];?>"></td>
					<td><?php echo $text_runtimes;?></td>
				  </tr>
				 </table>
			    <table>
				  <tr>
					<td style="width: 185px;"><span class="required">*</span><?php echo $entry_title; ?></td>
					<td style="width: 265px;"><input size="32" maxlength="64" name="title[<?php echo $home_description['language_id']; ?>]" value="<?php echo $home_description['title']; ?>">
                      <?php if ($error_title) { ?>
                      <span class="error"><?php echo $error_title; ?></span>
                      <?php } ?></td>
                  </tr> 
				  <tr>
				    <td><?php echo $entry_flash; ?></td>
					<td id="f_upload<?php echo $home_description['language_id']; ?>">
					  <select name="flash[<?php echo $home_description['language_id']; ?>]" id="flash<?php echo $home_description['language_id']; ?>">
					      <option value="">  </option>
				      <?php foreach($flash_files as $flash){?>
						<?php if($flash['flash'] == $home_description['flash']) {?>
						  <option value="<?php echo $flash['flash']; ?>" selected><?php echo $flash['flash']; ?></option>
						<?php } else {?>
						  <option value="<?php echo $flash['flash']; ?>"><?php echo $flash['flash']; ?></option>
						<?php }?>
					  <?php }?>
				    </select></td>
					<!--<td><input type="button" value="<?php //echo "Refresh";?>" onclick="$('#<?php //echo "f_upload".$home_description['language_id'];?>').load('index.php?controller=homepage&action=flash_refresh&flash_id=<?php //echo $home_description['language_id'];?>');">
					</td>-->
				  </tr>
				  <tr>
				    <td><?php echo $entry_flash_width; ?></td>
					<td><input name="flash_width[<?php echo $home_description['language_id']; ?>]" value="<?php echo $home_description['flash_width'];?>"></td>
				  </tr>
				  <tr>
				    <td><?php echo $entry_flash_height; ?></td>
					<td><input name="flash_height[<?php echo $home_description['language_id']; ?>]" value="<?php echo $home_description['flash_height'];?>"></td>
				  </tr>
				  
                  <tr>
                    <td valign="top"><?php echo $entry_welcome; ?></td>
                    <td><textarea name="welcome[<?php echo $home_description['language_id']; ?>]" id="welcome<?php echo $home_description['language_id']; ?>"><?php echo $home_description['welcome']; ?></textarea>
                  </tr>
				</table>
				<table>
                  <tr>
                    <td style="vertical-align: top; width: 185px"><?php echo $entry_description; ?></td>
                    <td><textarea name="description[<?php echo $home_description['language_id']; ?>]" id="description<?php echo $home_description['language_id']; ?>"><?php echo $home_description['description']; ?></textarea>
                  </tr>
				</table>
				<table>
				  <tr>
				    <td style="width: 185px"><?php echo $entry_image; ?></td>
                    <td><select name="image_id[<?php echo $home_description['language_id']; ?>]" id="image_id<?php echo $home_description['language_id']; ?>" onchange="$('#image').load('index.php?controller=image&action=view&image_id='+this.value);">
						  <option value="">  </option>
                      <?php foreach ($images as $image) { ?>
                        <?php if ($image['image_id'] == $home_description['image_id']) { ?>
						  <option value="<?php echo $image['image_id']; ?>" selected><?php echo $image['title']; ?></option>
					    <?php } else { ?>
						  <option value="<?php echo $image['image_id']; ?>"><?php echo $image['title']; ?></option>
					    <?php } ?>
					  <?php } ?>
				    </select></td>
                  </tr>
				  <tr>
				    <td></td>
				    <td id="image"></td>
				  </tr>
				</table>
		      </div>
		    </div>
			<?php } ?>
		  </div>
		</div>
	  </div>
	  <div class="page">
	    <div id="tabmini2">
		  <div class="tabs">
		  <?php foreach($home_descriptions as $home_description){?>
			  <a><?php echo $home_description['language'];?></a>
			<?php }?>
		  </div>
		  <div class="pages">
			<?php foreach($home_descriptions as $home_description){?>
			  <div class="page">
			    <div class="minipad">
				  <table>
					<tr>
					  <td style="width: 185px"><?php echo $entry_meta_title;?></td>
					  <td><input size="120" maxlength="120" name="meta_title[<?php echo $home_description['language_id']; ?>]" value="<?php echo $home_description['meta_title']; ?>"></td>
					</tr>
					<tr>
					  <td style="width: 185px"><?php echo $entry_meta_description;?></td>
					  <td><input size="120" maxlength="512" name="meta_description[<?php echo $home_description['language_id']; ?>]" value="<?php echo $home_description['meta_description']; ?>"></td>
					</tr>
					<tr>
					  <td style="width: 185px"><?php echo $entry_meta_keywords;?></td>
					  <td><input size="120" maxlength="255" name="meta_keywords[<?php echo $home_description['language_id']; ?>]" value="<?php echo $home_description['meta_keywords']; ?>"></td>
					</tr>
				  </table>
				</div>
			  </div>
			<?php }?>
		  </div>
	    </div>
	  </div>
	</div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
 </form> 
  <script type="text/javascript">//<!--
  var sBasePath           = '<?php echo HTTP_SERVER.'javascript/fckeditor/'?>';
  <?php foreach ($home_descriptions as $home_description) { ?>
	var oFCKeditor<?php echo $home_description['language_id']."desc"; ?> = new FCKeditor('description<?php echo $home_description['language_id']; ?>');
	oFCKeditor<?php echo $home_description['language_id']."desc"; ?>.BasePath = sBasePath;
	oFCKeditor<?php echo $home_description['language_id']."desc"; ?>.Value	= document.getElementById('description<?php echo $home_description['language_id']; ?>').value;
	oFCKeditor<?php echo $home_description['language_id']."desc"; ?>.Width    = '600';
	oFCKeditor<?php echo $home_description['language_id']."desc"; ?>.Height   = '300';
	oFCKeditor<?php echo $home_description['language_id']."desc"; ?>.Config['CustomConfigurationsPath'] = oFCKeditor<?php echo $home_description['language_id']."desc"; ?>.BasePath + 'myconfig.js';
	oFCKeditor<?php echo $home_description['language_id']."desc"; ?>.Config['DocType'] = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
	oFCKeditor<?php echo $home_description['language_id']."desc"; ?>.Config['SkinPath'] = oFCKeditor<?php echo $home_description['language_id']."desc"; ?>.BasePath + 'editor/skins/silver/' ;
	oFCKeditor<?php echo $home_description['language_id']."desc"; ?>.ToolbarSet = 'Custom' ;
	oFCKeditor<?php echo $home_description['language_id']."desc"; ?>.ReplaceTextarea();	

	var oFCKeditor<?php echo $home_description['language_id']."alt"; ?> = new FCKeditor('welcome<?php echo $home_description['language_id']; ?>');
	oFCKeditor<?php echo $home_description['language_id']."alt"; ?>.BasePath = sBasePath;
	oFCKeditor<?php echo $home_description['language_id']."alt"; ?>.Value	= document.getElementById('welcome<?php echo $home_description['language_id']; ?>').value;
	oFCKeditor<?php echo $home_description['language_id']."alt"; ?>.Width    = '600';
	oFCKeditor<?php echo $home_description['language_id']."alt"; ?>.Height   = '150';
	oFCKeditor<?php echo $home_description['language_id']."alt"; ?>.Config['CustomConfigurationsPath'] = oFCKeditor<?php echo $home_description['language_id']."alt"; ?>.BasePath + 'myconfig.js';
	oFCKeditor<?php echo $home_description['language_id']."alt"; ?>.Config['DocType'] = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
	oFCKeditor<?php echo $home_description['language_id']."alt"; ?>.Config['SkinPath'] = oFCKeditor<?php echo $home_description['language_id']."alt"; ?>.BasePath + 'editor/skins/silver/' ;
	oFCKeditor<?php echo $home_description['language_id']."alt"; ?>.ToolbarSet = 'Custom' ;
	oFCKeditor<?php echo $home_description['language_id']."alt"; ?>.ReplaceTextarea();
	
    <?php } ?>	  
  //--></script>
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
  <script type="text/javascript"><!--
  tabview_initialize('tabmini');
  //--></script>  
	<script type="text/javascript"><!--
  tabview_initialize('tabmini2');
  //--></script>
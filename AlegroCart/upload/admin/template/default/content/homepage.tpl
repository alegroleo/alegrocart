<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
  $head_def->set_admin_javascript("javascript/preview/preview.js");
  $head_def->set_admin_javascript("javascript/ckeditor/ckeditor.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled store" onmouseover="className='hover store'" onmouseout="className='enabled store'" onclick="getTabs();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="name" value="">
  <input type="hidden" name="status" value="">
  <input type="hidden" name="no_image_id" value="<?php echo $no_image_id;?>">
  <?php foreach ($home_descriptions as $home_description) { ?>
    <input type="hidden" name="run_times[<?php echo $home_description['language_id']; ?>]" value="">
    <input type="hidden" name="title[<?php echo $home_description['language_id']; ?>]" value="">
    <input type="hidden" name="flash_width[<?php echo $home_description['language_id']; ?>]" value="">
    <input type="hidden" name="flash_height[<?php echo $home_description['language_id']; ?>]" value="">
    <input type="hidden" name="flash_loop[<?php echo $home_description['language_id']; ?>]" value="">
    <input type="hidden" name="flash[<?php echo $home_description['language_id']; ?>]" value="">
    <input type="hidden" name="welcome[<?php echo $home_description['language_id']; ?>]" value="">
    <input type="hidden" name="description[<?php echo $home_description['language_id']; ?>]" value="">
    <input type="hidden" name="image_id[<?php echo $home_description['language_id']; ?>]" value="">
    <input type="hidden" name="meta_description[<?php echo $home_description['language_id']; ?>]" value="">
    <input type="hidden" name="meta_keywords[<?php echo $home_description['language_id']; ?>]" value="">
    <input type="hidden" name="meta_title[<?php echo $home_description['language_id']; ?>]" value="">
  <?php } ?>
  <input type="hidden" name="home_id" id="home_id" value="<?php echo $home_id; ?>">
  </form>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
<?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="saveTabs;document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?>
 <em></em>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>
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
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="form">
  <div class="tab" id="tab">
	<div class="tabs"><a><div class="tab_text"><?php echo $tab_name; ?></div></a><a><div class="tab_text"><?php echo $tab_description; ?></div></a><a><div class="tab_text"><?php echo $tab_meta; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_name; ?></td>
			  <td><input size="32" maxlength="64" name="name" value="<?php echo $name;?>">
                      <?php if ($error_name) { ?>
                      <span class="error"><?php echo $error_name; ?></span>
                      <?php } ?></td>
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
            </tr>
		  </table>
		</div>
	  </div>
      <div class="page">
        <div id="tabmini">
          <div class="tabs">
		    <?php foreach($home_descriptions as $home_description){?>
			  <a><div class="tab_text"><?php echo $home_description['language'];?></div></a>
			<?php }?>
		  </div>
		  <div class="pages">
		    <?php foreach($home_descriptions as $home_description){?>
		    <div class="page">
			  <div class="minipad">
				<table>
				<tr><td width="165" style="color:#0099FF; font-weight:bold"><p><?php echo $text_general;?></p></td>
					<td width="130"></td>
				</tr>
				  <tr>
					<td style="width: 165px;" class="set"><?php echo $entry_run_times;?></td>
					<td style="width: 200px;">
					<input class="validate_int" id="run_times<?php echo $home_description['language_id']; ?>" size="10" name="run_times[<?php echo $home_description['language_id']; ?>]" value="<?php echo $home_description['run_times'];?>"></td>
					<td class="expl"><?php echo $text_runtimes;?></td>
				  </tr>
				 </table>
			    <table>
				  <tr>
					<td style="width: 165px;" class="set"><?php echo $entry_title; ?></td>
					<td style="width: 200px;">
					<input size="30" maxlength="64" name="title[<?php echo $home_description['language_id']; ?>]" value="<?php echo $home_description['title']; ?>">
					</td>
                  		  </tr> 
		 		  <tr>
	                            <td colspan="2"><hr></td>
	   			  </tr>
				<tr><td width="165" style="color:#0099FF; font-weight:bold"><p><?php echo $text_flash;?></p></td>
					<td width="130"></td>
				</tr>
				  <tr>
				    <td class="set"><?php echo $entry_flash_width; ?></td>
					<td><input class="validate_int" id="flash_width<?php echo $home_description['language_id']; ?>" name="flash_width[<?php echo $home_description['language_id']; ?>]" value="<?php echo $home_description['flash_width'];?>"></td>
				  </tr>
				  <tr>
				    <td class="set"><?php echo $entry_flash_height; ?></td>
					<td><input class="validate_int" id="flash_height<?php echo $home_description['language_id']; ?>" name="flash_height[<?php echo $home_description['language_id']; ?>]" value="<?php echo $home_description['flash_height'];?>"></td>
				  </tr>
				  <tr>
				    <td class="set"><?php echo $entry_flash_loop; ?></td>
					<td><select name="flash_loop[<?php echo $home_description['language_id']; ?>]">
					  <?php if ($home_description['flash_loop'] == '1') { ?>
						<option value="1" selected><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
					  <?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected><?php echo $text_disabled; ?></option>
					  <?php } ?>
					</select></td>
				    <td class="expl"><?php echo $text_continous; ?></td>
				  </tr>
				  <tr>
				    <td class="set"><?php echo $entry_flash; ?></td>
					<td id="f_upload<?php echo $home_description['language_id']; ?>">
					  <select name="flash[<?php echo $home_description['language_id']; ?>]" id="flash<?php echo $home_description['language_id']; ?>" onchange="$('#flash_name<?php echo $home_description['language_id']; ?>').load('index.php?controller=image_display&action=viewFlash&flash='+this.value);">
					      <option value=""><?php echo $text_noflash;?></option>
				      <?php foreach($flash_files as $flash){?>
						<?php if($flash['flash'] == $home_description['flash']) {?>
						  <option value="<?php echo $flash['flash']; ?>" selected><?php echo $flash['flash']; ?></option>
						<?php } else {?>
						  <option value="<?php echo $flash['flash']; ?>"><?php echo $flash['flash']; ?></option>
						<?php }?>
					  <?php }?>
				    </select></td>
					<td class="flash_image" id="flash_name<?php echo $home_description['language_id']; ?>"></td>
				  </tr>
		 		  <tr>
	                            <td colspan="2"><hr></td>
	   			  </tr>
				  </table>
				  <table>
				<tr><td width="165" style="color:#0099FF; font-weight:bold"><p><?php echo $text_welcome_message;?></p></td>
					<td width="130"></td>
				</tr>
                  <tr>
                    <td style="vertical-align: top; width: 165px" class="set"><?php echo $entry_welcome; ?></td>
                    <td><textarea name="welcome[<?php echo $home_description['language_id']; ?>]" id="welcome<?php echo $home_description['language_id']; ?>"><?php echo $home_description['welcome']; ?></textarea>
                  </tr>
				</table>
				<table>
                  <tr>
                    <td style="vertical-align: top; width: 165px" class="set"><?php echo $entry_description; ?></td>
                    <td><textarea name="description[<?php echo $home_description['language_id']; ?>]" id="description<?php echo $home_description['language_id']; ?>"><?php echo $home_description['description']; ?></textarea>
                  </tr>
				</table>
				<table>
		 		  <tr>
	                            <td colspan="2"><hr></td>
	   			  </tr>
				<tr><td width="165" style="color:#0099FF; font-weight:bold"><p><?php echo $text_welcome_image;?></p></td>
					<td width="130"></td>
				</tr>
				  <tr>
				    <td style="width: 165px" class="set"><?php echo $entry_image; ?></td>
                    <td><select name="image_id[<?php echo $home_description['language_id']; ?>]" id="image_id<?php echo $home_description['language_id']; ?>" onchange="$('#image<?php echo $home_description['language_id']; ?>').load('index.php?controller=image&action=view&image_id='+this.value);">
						  <option title="<?php echo $no_image_filename; ?>" value="<?php echo $no_image_id;?>"<?php if($home_description['image_id'] == '0'){ echo ' selected';}?>><?php echo $text_no_image;?></option>
                      <?php foreach ($images as $image) { ?>
			<?php if ($image['image_id'] != $no_image_id){?>
                        <?php if ($image['image_id'] == $home_description['image_id']) { ?>
						  <option title="<?php echo $image['previewimage']; ?> "value="<?php echo $image['image_id']; ?>" selected><?php echo $image['title']; ?></option>
					    <?php } else { ?>
						  <option title="<?php echo $image['previewimage']; ?>" value="<?php echo $image['image_id']; ?>"><?php echo $image['title']; ?></option>
						<?php } ?>
					<?php } ?>
					<?php } ?>
				</select></td>
				    <td></td>
				    <td class="product_image" id="image<?php echo $home_description['language_id']; ?>"></td>
				  </tr>
				</table>
				<table>
		 		  <tr>
	                            <td colspan="2"><hr></td>
	   			  </tr>
				<tr><td width="165" style="color:#0099FF; font-weight:bold"><p><?php echo $text_slider;?></p></td>
					<td width="130"></td>
				</tr>
				</table>
		  <table id="slidertable<?php echo $home_description['language_id']; ?>">
		    <?php $i = 0;?>
		    <?php foreach($home_description['slides'] as $slide){?>
			  <tr id="slider<?php echo $home_description['language_id']; ?>_<?php echo $i; ?>">
			    <td style="width: 165px" class="set"><?php echo $entry_image; ?></td>
			    <td><select name="sliderimage_id[<?php echo $home_description['language_id']; ?>][<?php echo $i; ?>]" id="sliderimage_id<?php echo $home_description['language_id']; ?>_<?php echo $i; ?>" onchange="$('#sliderimage<?php echo $home_description['language_id']; ?>_<?php echo $i; ?>').load('index.php?controller=image&action=view&image_id='+this.value);">
					  <option title="<?php echo $no_image_filename; ?>" value="<?php echo $no_image_id;?>"<?php if($slide['sliderimage_id'] == '0'){ echo ' selected';}?>><?php echo $text_no_image;?></option>
					  <?php foreach ($images as $image) { ?>
						<?php if ($image['image_id'] != $no_image_id){?>
						<?php if ($image['image_id'] == $slide['sliderimage_id']) { ?>
						  <option title="<?php echo $image['previewimage']; ?> "value="<?php echo $image['image_id']; ?>" selected><?php echo $image['title']; ?></option>
						<?php } else { ?>
						  <option title="<?php echo $image['previewimage']; ?>" value="<?php echo $image['image_id']; ?>"><?php echo $image['title']; ?></option>
						<?php } ?>
						<?php } ?>
					  <?php } ?>
			    </select></td>
			    <td></td>
			    <td class="product_image" id="sliderimage<?php echo $home_description['language_id']; ?>_<?php echo $i; ?>"></td>
			    <td class="set"><?php echo $entry_sortorder;?></td>
			    <td><input class="validate_int" id="sort_order<?php echo $home_description['language_id']; ?>_<?php echo $i; ?>" type="text" size="2" name="sort_order[<?php echo $home_description['language_id']; ?>][<?php echo $i; ?>]" value="<?php echo $slide['sort_order'];?>"></td>
			    <td><input type="button" class="button" value="<?php echo $button_remove; ?>" onclick="removeModule('slider<?php echo $home_description['language_id']; ?>_<?php echo $i;?>');"></td>
			  </tr>
		    <?php $i++; ?>
		    <?php }?>
				</table>
          <table>
            <tr>
              <td colspan="5"><input type="button" class="button" value="<?php echo $button_add; ?>" onclick="addSlides(<?php echo $home_description['language_id']; ?>);"></td>
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
			  <a><div class="tab_text"><?php echo $home_description['language'];?></div></a>
			<?php }?>
		  </div>
		  <div class="pages">
			<?php foreach($home_descriptions as $home_description){?>
			  <div class="page">
			    <div class="minipad">
				  <table>
					<tr>
					  <td style="width: 185px" class="set"><?php echo $entry_meta_title;?></td>
					  <td><input class="validate_meta" id="meta_title<?php echo $home_description['language_id']; ?>" size="120" maxlength="120" name="meta_title[<?php echo $home_description['language_id']; ?>]" value="<?php echo $home_description['meta_title']; ?>"></td>
					</tr>
					<tr>
					  <td style="width: 185px" class="set"><?php echo $entry_meta_description;?></td>
					  <td><input class="validate_meta" id="meta_description<?php echo $home_description['language_id']; ?>" size="120" maxlength="512" name="meta_description[<?php echo $home_description['language_id']; ?>]" value="<?php echo $home_description['meta_description']; ?>"></td>
					</tr>
					<tr>
					  <td style="width: 185px" class="set"><?php echo $entry_meta_keywords;?></td>
					  <td><input class="validate_meta" id="meta_keywords<?php echo $home_description['language_id']; ?>" size="120" maxlength="255" name="meta_keywords[<?php echo $home_description['language_id']; ?>]" value="<?php echo $home_description['meta_keywords']; ?>"></td>
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
  <input type="hidden" name="no_image_id" value="<?php echo $no_image_id;?>">
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
 </form> 
  <script type="text/javascript">//<!--
    <?php foreach ($home_descriptions as $home_description) { ?>
      CKEDITOR.replace( 'description<?php echo $home_description['language_id']; ?>' );
  <?php } ?>      
  //--></script>
  <script type="text/javascript">//<!--
    <?php foreach ($home_descriptions as $home_description) { ?>
      CKEDITOR.replace( 'welcome<?php echo $home_description['language_id']; ?>' );
  <?php } ?>      
  //--></script>
  <?php foreach($languages as $language){?>
   <script type="text/javascript"><!--
	var flash = document.getElementById('flash<?php echo $language['language_id'];?>');
	if(typeof flash !== 'undefined' && flash !== null) {
		if(flash.value){
			$('#flash_name<?php echo $language['language_id']; ?>').load('index.php?controller=image_display&action=viewFlash&flash='+flash.value);
		}
	}
	var image = document.getElementById('image_id<?php echo $language['language_id'];?>');
	if(typeof image !== 'undefined' && image !== null) {
		if(image.value){
			$('#image<?php echo $language['language_id'];?>').load('index.php?controller=image&action=view&image_id='+image.value);
		}
	}
	var table = document.getElementById('slidertable<?php echo $language['language_id'];?>');
	if(typeof table !== 'undefined' && table !== null) {
		for (var i = 0, trl = table.rows.length; i < trl; ++i) {
			if(document.getElementById('sliderimage_id<?php echo $language['language_id'];?>_' + i).value){
			$('#sliderimage<?php echo $language['language_id'];?>_'+i).load('index.php?controller=image&action=view&image_id='+document.getElementById('sliderimage_id<?php echo $language['language_id'];?>_'+i).value);
			}
			}
	}
    //--></script>
  <script type="text/javascript">//<!--
function addSlides(Language_id) {
	var Last = $('#slidertable' + Language_id + ' tr:last');
	var nextId = Last.size() == 0 ? 1 : + Last.attr('id').split("_").pop() + 1;
	$.ajax({
		type:    'GET',
		url:     'index.php?controller=homepage&action=slides&slide_id='+nextId+'&language_id='+Language_id,
		async:   false,
		success: function(data) {
		$('#slidertable' + Language_id).append(data);
		}
	});
}
function removeModule(row) {
	$('#'+row).remove();
}
  //--></script>
  <?php } ?>
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
  <script type="text/javascript"><!--
  tabview_initialize('tabmini');
  //--></script>  
	<script type="text/javascript"><!--
  tabview_initialize('tabmini2');
  //--></script>
  <script type="text/javascript"><!--
    $('input[name="name"]').change(function () {
      var value = $(this).val();
      $(".heading em").text(value);
    }).change();
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
		url:     'index.php?controller=homepage&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
  <script type="text/javascript"><!--
	function saveTabs() {
	var activeTab = $('#tab > .tabs > a.active').index()+1;
	var activeTabmini = $('#tabmini > .tabs > a:visible.active').index()+1;
	var id = $('#home_id').val();
	var data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini, 'id':id};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=homepage&action=tab',
		data: data_json,
		dataType:'json'
	});
	}
	function getTabs() {
	var activeTab = $('#tab > .tabs > a.active').index()+1;
	var activeTabmini = $('#tabmini > .tabs > a:visible.active').index()+1;
	var id = $('#home_id').val();
	var data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini, 'id':id};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=homepage&action=tab',
		data: data_json,
		dataType:'json',
		success: function (data) {
				if (data.status===true) {
					getValues();
					document.getElementById('update_form').submit();
				} else {
					$('<div class="warning"><?php echo $error_update; ?></div>').insertBefore(".heading");
				}
		}
	});
	}
	function copySlides() {
		var html ='';
		<?php foreach ($home_descriptions as $home_description) { ?>
			var ids = [];
			var slides = document.querySelectorAll("[id^='slider<?php echo $home_description['language_id']; ?>_']");
			var slidesLenght = slides.length;
			for (j =0; j < slidesLenght; j++) {
				ids[j] = slides[j].getAttribute("id").split("_").pop();
			}
			for (i =0; i < slidesLenght; i++) {
				if (document.forms['form'].elements['sliderimage_id[<?php echo $home_description['language_id']; ?>]['+ids[i]+']'] !=undefined){
				html +='<input type="hidden" name="sliderimage_id[<?php echo $home_description['language_id']; ?>]' + '[' + [i] + ']" value="' + document.forms['form'].elements['sliderimage_id[<?php echo $home_description['language_id']; ?>]['+ids[i]+']'].value + '">';
				html +='<input type="hidden" name="sort_order[<?php echo $home_description['language_id']; ?>]' + '[' + [i] + ']" value="' + document.forms['form'].elements['sort_order[<?php echo $home_description['language_id']; ?>]['+ids[i]+']'].value + '">';
				}
			}
		<?php } ?>
		document.forms['update_form'].innerHTML += html;
	}
	function getValues() {
		document.forms['update_form'].name.value=document.forms['form'].name.value;
		document.forms['update_form'].status.value=document.forms['form'].status.value;
		<?php foreach ($home_descriptions as $home_description) { ?>
			document.forms['update_form'].elements['run_times[<?php echo $home_description['language_id']; ?>]'].value=document.forms['form'].elements['run_times[<?php echo $home_description['language_id']; ?>]'].value;
			document.forms['update_form'].elements['title[<?php echo $home_description['language_id']; ?>]'].value=document.forms['form'].elements['title[<?php echo $home_description['language_id']; ?>]'].value;
			document.forms['update_form'].elements['flash_width[<?php echo $home_description['language_id']; ?>]'].value=document.forms['form'].elements['flash_width[<?php echo $home_description['language_id']; ?>]'].value;
			document.forms['update_form'].elements['flash_height[<?php echo $home_description['language_id']; ?>]'].value=document.forms['form'].elements['flash_height[<?php echo $home_description['language_id']; ?>]'].value;
			document.forms['update_form'].elements['flash_loop[<?php echo $home_description['language_id']; ?>]'].value=document.forms['form'].elements['flash_loop[<?php echo $home_description['language_id']; ?>]'].value;
			document.forms['update_form'].elements['flash[<?php echo $home_description['language_id']; ?>]'].value=document.forms['form'].elements['flash[<?php echo $home_description['language_id']; ?>]'].value;
			document.forms['update_form'].elements['welcome[<?php echo $home_description['language_id']; ?>]'].value=CKEDITOR.instances.welcome<?php echo $home_description['language_id']; ?>.getData();
			document.forms['update_form'].elements['description[<?php echo $home_description['language_id']; ?>]'].value=CKEDITOR.instances.description<?php echo $home_description['language_id']; ?>.getData();
			document.forms['update_form'].elements['image_id[<?php echo $home_description['language_id']; ?>]'].value=document.forms['form'].elements['image_id[<?php echo $home_description['language_id']; ?>]'].value;
			document.forms['update_form'].elements['meta_description[<?php echo $home_description['language_id']; ?>]'].value=document.forms['form'].elements['meta_description[<?php echo $home_description['language_id']; ?>]'].value;
			document.forms['update_form'].elements['meta_keywords[<?php echo $home_description['language_id']; ?>]'].value=document.forms['form'].elements['meta_keywords[<?php echo $home_description['language_id']; ?>]'].value;
			document.forms['update_form'].elements['meta_title[<?php echo $home_description['language_id']; ?>]'].value=document.forms['form'].elements['meta_title[<?php echo $home_description['language_id']; ?>]'].value;
		<?php } ?>

		copySlides();
	}
  //--></script>
  <script type="text/javascript"><!--
  $(document).ready(function() {
	if (<?php echo $tab; ?>!=undefined && <?php echo $tab; ?> > 0) {
		tabview_switch('tab', <?php echo $tab; ?>);
	if (<?php echo $tabmini; ?>!=undefined && <?php echo $tabmini; ?> > 0) {
			tabview_switch('tabmini', <?php echo $tabmini; ?>);
		}
	}
   });
  //--></script>

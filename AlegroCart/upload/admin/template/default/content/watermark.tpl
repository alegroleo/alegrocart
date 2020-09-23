<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/preview/preview.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" width=32 height=32 alt="<?php echo $button_insert; ?>" class="png" /><?php echo $button_insert; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" width=32 height=32 alt="<?php echo $button_update; ?>" class="png" /><?php echo $button_update; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" width=32 height=32 alt="<?php echo $button_delete; ?>" class="png" /><?php echo $button_delete; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" width=32 height=32 alt="<?php echo $button_save; ?>" class="png" /><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" width=32 height=32 alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" width=32 height=32 alt="<?php echo $button_cancel; ?>" class="png" /><?php echo $button_cancel; ?></div>
<?php if (@$last) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $last; ?>'"><img src="template/<?php echo $this->directory?>/image/last_enabled.png" width=32 height=32 alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/last_disabled.png" width=32 height=32 alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } ?>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" width=31 height=30 alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_watermark; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
	  <table>
	     <tr height=50px> 
		  <td style="color:#0099FF; font-weight:bold; width:450px"><p><?php echo $text_original; ?></p>
		  </td>
		  <td style="color:#0099FF; font-weight:bold"><p><?php echo $text_watermarked; ?></p>
		  </td>
	     </tr>  
	     <tr height=50px> 
		  <td>
		  <select id="wm_wmimage_id" name="wm_wmimage" onchange="$('#wm_wmimage').load('index.php?controller=watermark&action=viewWmImage&wm_wmimage='+this.value);">
<option value=""><?php echo $text_none; ?></option>
		  <?php foreach ($wmimages as $wmimage){?>
		  <option title="<?php echo $wmimage['previewimage']; ?>" value="<?php echo $wmimage['image'];?>"><?php echo $wmimage['image'];?></option>
		  <?php }?>
		  </select>
		  </td>
		  <td>
		  <input type="button" class="button leftbutton" name="pre_view" id="preview_id" value="<?php echo $button_preview; ?>" onclick="$('#pre_view').load('index.php?controller=watermark&action=previewImage&pre_view='+wm_wmimage_id.value),$('#save_button').show();">
		  <input id="save_button" hidden="hidden" type="button" class="button rightbutton" name="save" value="<?php echo $button_save_wmi; ?>">
		  </td>	
	     </tr>
	     <tr> 
		  <td class="wm_wmimage" id="wm_wmimage" colspan="1">
		  </td>
		  <td class="pre_view" id="pre_view" colspan="1">
		  </td>
	    </tr>  
	    <tr> 
		  <td class="expl">
		  <?php echo $explanation_wm_original; ?> 
		  </td>
		  <td class="expl">
		  <?php echo $explanation_wm_watermarked; ?> 
		  </td>
	    </tr>
	    <tr>
		  <td colspan="2"><hr></td>
	    </tr>
	  </table>
	  <table>
	     <tr height=50px> 
		  <td style="color:#0099FF; font-weight:bold"><p><?php echo $text_wm_with_text; ?></p></td>
	     </tr>  
	     <tr>
		  <td width="185" class="set"><?php echo $entry_wm_text; ?></td>
		  <td><input size="48" type="text" name="wm_text" value="<?php echo $wm_text; ?>">
		  <?php if ($error_wm_text) { ?>
		  <span class="error"><?php echo $error_wm_text; ?></span>
		  <?php } ?></td>
			      <td class="expl">
				<?php echo $explanation_wm_text; ?> 
		  </td>
	      </tr>
	      <tr> 
		  <td class="set"><?php echo $entry_wm_fontsize; ?></td>
		  <td><select name="wm_font">
                  <?php foreach ($font_sizes as $font_size) { ?>
                  <?php if ($font_size == $wm_font) { ?>
                  <option value="<?php echo $font_size; ?>" selected><?php echo $font_size; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $font_size; ?>"><?php echo $font_size; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
		  <td class="expl">
		      <?php echo $explanation_wm_fontsize; ?> 
		    </td>
	      </tr>
	      <tr>
		  <td width="185" class="set"><?php echo $entry_wm_fontcolor; ?></td>
		  <td><input class="validate_hex" id="wm_fontcolor" type="text" name="wm_fontcolor" value="<?php echo $wm_fontcolor; ?>" size="6">
		  <?php if ($error_wm_fontcolor) { ?>
		  <span class="error"><?php echo $error_wm_fontcolor; ?></span>
		  <?php } ?></td>
		  <td class="expl">
		    <?php echo $explanation_wm_fontcolor; ?> 
		  </td>
	      </tr>
	      <tr>
		  <td width="185" class="set"><?php echo $entry_wm_transparency; ?></td>
		  <td><input class="validate_int" id="wm_transparency" type="text" name="wm_transparency" value="<?php echo $wm_transparency; ?>" size="4">
		  <?php if ($error_wm_transparency) { ?>
		  <span class="error"><?php echo $error_wm_transparency; ?></span>
		  <?php } ?></td>
		  <td class="expl">
		    <?php echo $explanation_wm_transparency; ?> 
		  </td>
	      </tr>
	      <tr>
		  <td class="set"><?php echo $entry_wm_thposition ?></td>
		  <td><select name="wm_thposition">
			<option value="LEFT"<?php if('LEFT'==$wm_thposition) { echo ' selected';}?>><?php echo $text_left ?></option>
			<option value="CENTER"<?php if('CENTER'==$wm_thposition) { echo ' selected';}?>><?php echo $text_center ?></option>
			<option value="RIGHT"<?php if('RIGHT'==$wm_thposition) { echo ' selected';}?>><?php echo $text_right ?></option>
		    </select>
		  </td>
		  <td class="expl">
		      <?php echo $explanation_wm_thposition; ?> 
		  </td>
	       </tr>
	       <tr>
		  <td class="set"><?php echo $entry_wm_tvposition ?></td>
		  <td><select name="wm_tvposition">
			<option value="TOP"<?php if('TOP'==$wm_tvposition) { echo ' selected';}?>><?php echo $text_top ?></option>
			<option value="CENTER"<?php if('CENTER'==$wm_tvposition) { echo ' selected';}?>><?php echo $text_center ?></option>
			<option value="BOTTOM"<?php if('BOTTOM'==$wm_tvposition) { echo ' selected';}?>><?php echo $text_bottom ?></option>
		    </select>
		  </td>
		  <td class="expl">
		      <?php echo $explanation_wm_tvposition; ?> 
		  </td>
		</tr>
	        <tr>
		  <td width="185" class="set"><?php echo $entry_wm_thmargin; ?></td>
		  <td><input class="validate_int" id="wm_thmargin" type="text" name="wm_thmargin" value="<?php echo $wm_thmargin; ?>" size="4"></td>
		  <td class="expl">
		    <?php echo $explanation_wm_thmargin; ?> 
		  </td>
	      </tr>
	      <tr>
		  <td width="185" class="set"><?php echo $entry_wm_tvmargin; ?></td>
		  <td><input class="validate_int" id="wm_tvmargin" type="text" name="wm_tvmargin" value="<?php echo $wm_tvmargin; ?>" size="4"></td>
		  <td class="expl">
		    <?php echo $explanation_wm_tvmargin; ?> 
		  </td>
	      </tr>
              </tr>
		  <tr><td colspan="2"><hr></td>
		  </tr>
	      <tr height=50px> 
		  <td style="color:#0099FF; font-weight:bold"><p><?php echo $text_wm_with_image; ?></p></td>
	      </tr> 
	      <tr>
		 <td class="set"><?php echo $entry_wm_image;?></td>
		  <td><select id="wm_image_id" name="wm_image" onchange="$('#wm_image').load('index.php?controller=watermark&action=viewImage&wm_image='+this.value);">
			<option value="0"><?php echo $text_none; ?></option>
			<?php foreach ($images as $image){?>
			      <option value="<?php echo $image['image'];?>"<?php if($image['image'] == $wm_image){echo ' selected';}?>><?php echo $image['image'];?></option>
			<?php }?>
		      </select></td>
		  <td class="expl">
		    <?php echo $explanation_wm_image; ?> 
		  </td> 
	      </tr>
	      <tr>
		  <td class="wm_image" id="wm_image" colspan="3"></td>
	      </tr>
	      <tr>
		  <td width="185" class="set"><?php echo $entry_wm_scale; ?></td>
		  <td><input class="validate_int" id="wm_scale" type="text" name="wm_scale" value="<?php echo $wm_scale; ?>" size="4">
		  <?php if ($error_wm_scale) { ?>
		  <span class="error"><?php echo $error_wm_scale; ?></span>
		  <?php } ?></td>
		  <td class="expl">
		    <?php echo $explanation_wm_scale; ?> 
		  </td>
	      </tr>
	      <tr>
		<td class="set"><?php echo $entry_wm_ihposition ?></td>
		<td><select name="wm_ihposition">
		      <option value="LEFT"<?php if('LEFT'==$wm_ihposition) { echo ' selected';}?>><?php echo $text_left ?></option>
		      <option value="CENTER"<?php if('CENTER'==$wm_ihposition) { echo ' selected';}?>><?php echo $text_center ?></option>
		      <option value="RIGHT"<?php if('RIGHT'==$wm_ihposition) { echo ' selected';}?>><?php echo $text_right ?></option>
		  </select>
		</td>
		<td class="expl">
		    <?php echo $explanation_wm_ihposition; ?> 
		</td>
	      </tr>
	      <tr>
		<td class="set"><?php echo $entry_wm_ivposition ?></td>
		<td><select name="wm_ivposition">
		      <option value="TOP"<?php if('TOP'==$wm_ivposition) { echo ' selected';}?>><?php echo $text_top ?></option>
		      <option value="CENTER"<?php if('CENTER'==$wm_ivposition) { echo ' selected';}?>><?php echo $text_center ?></option>
		      <option value="BOTTOM"<?php if('BOTTOM'==$wm_ivposition) { echo ' selected';}?>><?php echo $text_bottom ?></option>
		  </select>
		</td>
		<td class="expl">
		    <?php echo $explanation_wm_ivposition; ?> 
		</td>
	      </tr>
	      <tr>
		  <td width="185" class="set"><?php echo $entry_wm_ihmargin; ?></td>
		  <td><input class="validate_int" id="wm_ihmargin" type="text" name="wm_ihmargin" value="<?php echo $wm_ihmargin; ?>" size="4"></td>
		  <td class="expl">
		    <?php echo $explanation_wm_ihmargin; ?> 
		  </td>
	      </tr>
	      <tr>
		  <td width="185" class="set"><?php echo $entry_wm_ivmargin; ?></td>
		  <td><input class="validate_int" id="wm_ivmargin" type="text" name="wm_ivmargin" value="<?php echo $wm_ivmargin; ?>" size="4"></td>
		  <td class="expl">
		    <?php echo $explanation_wm_ivmargin; ?> 
		  </td>
	      </tr>
	  </table>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
</form>
  <script type="text/javascript">
  tabview_initialize('tab');
  </script>
  <script type="text/javascript">
  $('#wm_image').load('index.php?controller=watermark&action=viewImage&wm_image='+document.getElementById('wm_image_id').value);
  </script>
  <script type="text/javascript">
  $("#save_button").on("click", function(){
	var save = $('#wm_wmimage_id').val();
	var data_json = {'save':save};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=watermark&action=previewSave',
		data: data_json,
		dataType:'json',
		beforeSend: function (data) {
			$('#pre_view').after('<img src="template/<?php echo $this->directory?>/image/working.gif" alt="" id="working">');
		},
		success: function (data) {
			if (data.status === true) {
				$('#save_button').hide('slow',function(){
					$('#wm_wmimage').load('index.php?controller=watermark&action=viewWmImage&wm_wmimage='+document.getElementById('wm_wmimage_id').value);
				});
			}
		},
		complete: function() {
			$('#working').remove();
		}
	});
  });
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
		url:     'index.php?controller=watermark&action=help',
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

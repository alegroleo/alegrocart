<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/list_disabled.png" alt="<?php echo $button_list; ?>" class="png" /><?php echo $button_list; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png" /><?php echo $button_insert; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png" /><?php echo $button_update; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png" /><?php echo $button_delete; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png" /><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png" /><?php echo $button_cancel; ?></div>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css" />
<script type="text/javascript" src="javascript/ajax/jquery.js"></script>
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
<option value="">None</option>
		  <?php foreach ($wmimages as $wmimage){?>
		  <option value="<?php echo $wmimage['image'];?>"><?php echo $wmimage['image'];?></option>
		  <?php }?>
		  </select>
		  </td>
		  <td>
		  <input type="button" class="button leftbutton" name="preview" id="preview_id" value="<?php echo $button_preview; ?>" onclick="$('#preview').load('index.php?controller=watermark&action=previewImage&preview='+wm_wmimage_id.value),$('#save_button').show();">
		  <input id="save_button" hidden="hidden" type="button" class="button rightbutton" name="save" value="<?php echo $button_save_wmi; ?>" onclick="$('#save').load('index.php?controller=watermark&action=previewSave&save='+wm_wmimage_id.value),RefreshImage();">
		  </td>	
	     </tr>
	     <tr> 
		  <td class="wm_wmimage" id="wm_wmimage" colspan="1">
		  </td>
		  <td class="preview" id="preview" colspan="1">
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
		  <td><input type="text" name="wm_fontcolor" value="<?php echo $wm_fontcolor; ?>" size="6">
		  <?php if ($error_wm_fontcolor) { ?>
		  <span class="error"><?php echo $error_wm_fontcolor; ?></span>
		  <?php } ?></td>
		  <td class="expl">
		    <?php echo $explanation_wm_fontcolor; ?> 
		  </td>
	      </tr>
	      <tr>
		  <td width="185" class="set"><?php echo $entry_wm_transparency; ?></td>
		  <td><input type="text" name="wm_transparency" value="<?php echo $wm_transparency; ?>" size="4">
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
		  <td><input type="text" name="wm_thmargin" value="<?php echo $wm_thmargin; ?>" size="4"></td>
		  <td class="expl">
		    <?php echo $explanation_wm_thmargin; ?> 
		  </td>
	      </tr>
	      <tr>
		  <td width="185" class="set"><?php echo $entry_wm_tvmargin; ?></td>
		  <td><input type="text" name="wm_tvmargin" value="<?php echo $wm_tvmargin; ?>" size="4"></td>
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
		  <td><input type="text" name="wm_scale" value="<?php echo $wm_scale; ?>" size="4">
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
		  <td><input type="text" name="wm_ihmargin" value="<?php echo $wm_ihmargin; ?>" size="4"></td>
		  <td class="expl">
		    <?php echo $explanation_wm_ihmargin; ?> 
		  </td>
	      </tr>
	      <tr>
		  <td width="185" class="set"><?php echo $entry_wm_ivmargin; ?></td>
		  <td><input type="text" name="wm_ivmargin" value="<?php echo $wm_ivmargin; ?>" size="4"></td>
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
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
  <script type="text/javascript"><!--
  $('#wm_image').load('index.php?controller=watermark&action=viewImage&wm_image='+document.getElementById('wm_image_id').value);
  //--></script>
  <script type="text/javascript"><!--
  function RefreshImage(){
	$('#save_button').hide('slow',function(){
		$('#wm_wmimage').load('index.php?controller=watermark&action=viewWmImage&wm_wmimage='+document.getElementById('wm_wmimage_id').value);
	});
  }
  //--></script>
</form>
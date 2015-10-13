<tr id="slider<?php echo $language_id . '_' . $slide_id; ?>">
  <td style="width: 165px" class="set"><?php echo $entry_image; ?></td>
  <td><select name="sliderimage_id[<?php echo $language_id; ?>][<?php echo $slide_id; ?>]" id="sliderimage_id<?php echo $language_id; ?>_<?php echo $slide_id; ?>" onfocus="RegisterPreview()" onchange="$('#sliderimage<?php echo $language_id; ?>_<?php echo $slide_id; ?>').load('index.php?controller=image&action=view&image_id='+this.value);">
	<option title="<?php echo $no_image_filename; ?>" value="<?php echo $no_image_id;?>"><?php echo $text_no_image;?></option>
	<?php foreach ($images as $image) { ?>
		<option title="<?php echo $image['previewimage']; ?>" value="<?php echo $image['image_id']; ?>"><?php echo $image['title']; ?></option>
	<?php } ?>
  </select></td>
  <td></td>
  <td class="product_image" id="sliderimage<?php echo $language_id; ?>_<?php echo $slide_id; ?>"><img src="<?php echo $no_image_filename; ?>"></td>
  <td class="set"><?php echo $entry_sortorder;?></td>
  <td><input class="validate_int" id="sort_order<?php echo $language_id . '_' .  $slide_id; ?>" type="text" name= "sort_order[<?php echo $language_id . '][' .  $slide_id; ?>]" value="" size="2" onfocus="RegisterValidation()"></td>
  <td><input type="button" class="button" value="<?php echo $button_remove; ?>" onclick="removeModule(\'slider'<?php echo $language_id . '_' . $slide_id; ?>');"></td> 
</tr>

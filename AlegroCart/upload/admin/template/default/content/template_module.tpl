<tr id="<?php echo $location . '_' . $module_id; ?>">
  <td><?php echo $entry_module; ?></td>
  <td><select name="<?php echo $location . '[' .  $module_id; ?>][module_code]">
	<?php foreach(${$location.'_modules'} as $module){?>
	  <option value="<?php echo $module;?>"><?php echo $module;?></option>
	<?php }?>
  </select></td>
  <td><?php echo $entry_sortorder;?></td>
  <td><input type="text" name= "<?php echo $location . '[' .  $module_id; ?>][sort_order]" value="" size="2"></td>
  <td><input type="hidden" name="<?php echo $location . '[' .  $module_id; ?>][location_id]" value="<?php echo $location_id;?>"></td>
  <td><input type="button" value="<?php echo $button_remove; ?>" onclick="removeModule('<?php echo $location . '_' . $module_id; ?>');"></td> 
</tr>
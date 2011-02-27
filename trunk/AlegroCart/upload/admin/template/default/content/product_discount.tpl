<tr id="discount_<?php echo $discount_id; ?>">
  <td><?php echo $entry_quantity; ?></td>
  <td><input type="text" name="product_discount[<?php echo $discount_id; ?>][quantity]" value="" size="2"></td>

  <td><?php echo $entry_percent_discount;?></td>  
  <td><input type="text" id="discount_percent<?php echo $discount_id; ?>" size="11" name="product_discount[<?php echo $discount_id; ?>][discount]" value="" onchange="quantity_percent('<?php echo $discount_id; ?>')"></td>
  

  <td><?php echo $entry_discount; ?></td>  
  <td><input type="text" id="discount_amount<?php echo $discount_id;?>" size="8" name="discount_amount<?php echo $discount_id;?>" onchange="quantity_discount('<?php echo $discount_id; ?>')"></td>  
  
  <td><input type="button" class="button" value="<?php echo $button_remove; ?>" onclick="removeDiscount('discount_<?php echo $discount_id; ?>');"></td>
</tr>

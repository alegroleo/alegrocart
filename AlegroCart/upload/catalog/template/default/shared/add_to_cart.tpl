<div id="<?php echo $this_controller.'_'.$product['product_id']; ?>" class="add" <?php if(isset($button_font)){echo 'style="font-size: '.$button_font.'px"'; }?>>
<?php $loadpath = "$('#mini_cart').load('index.php?controller=addtocart&amp;action=add&amp;";?>
  <input class="button" <?php if(isset($button_font)){echo 'style="font-size: '.$button_font.'px"'; }?> type="button" id="<?php echo $this_controller.'_add_'.$product['product_id']; ?>" value="<?php if($product['cart_level']){ echo $product['cart_level'].' '.$Added_to_Cart;} else {echo $Add_to_Cart;} ?>"<?php if($product['max_qty']!=0 &&(($product['max_qty'] - $product['cart_level'] == 0) || ($product['max_qty'] - $product['cart_level'] < $product['multiple']))) {echo ' disabled="disabled "';}?>onclick="<?php if(isset($image_display) && $image_display != 'no_image'){?>$.add2cart('<?php echo $this_controller.'_image'.$product['product_id']; ?>','cart_content'); <?php }?><?php echo $loadpath; ?>'+GetData(<?php echo "'".$product['product_id']."','".$this_controller."'"; ?>)),UpdateAddToCartButton(<?php echo $product['product_id']. ",'" . $this_controller . "','".$Added_to_Cart."','".$Add_to_Cart."'"; ?>),UpdateAddToCart(<?php echo $product['product_id']. ",'" . $this_controller . "'"; ?>);">&nbsp;
	<?php if($product['min_qty'] > '1' && !$product['cart_level']){
		$i = $product['min_qty'];
	} else {
		$i = 1;
	}?>
<?php if (!($product['max_qty']!=0 &&(($product['max_qty'] - $product['cart_level'] == 0) || ($product['max_qty'] - $product['cart_level'] < $product['multiple'])))) { ?>
	<?php if(@$addtocart_quantity_box != 'textbox'){?>
		<select name="<?php echo $this_controller;?>_quantity" id="<?php echo $this_controller.'_quantity_'.$product['product_id']; ?>">
		<?php while ($i <= ($product['max_qty'] ? $product['max_qty'] - $product['cart_level']: (@$addtocart_quantity_max ? $addtocart_quantity_max : '20'))){ ?>
		<?php if (($product['multiple'] != '0') && ($i % $product['multiple'] == '0')) { ?>
		<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
		<?php } elseif ($product['multiple'] == '0') { ?>
		<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
		<?php }?>
		<?php $i ++;
		} ?>
		</select>
	<?php } else {?>
		<?php if($product['cart_level']){
			$product['multiple'] != '0' ? $from = $product['multiple'] : $from = 1;
		} else {
			if ($product['multiple'] != '0'){
				if ($product['multiple'] > $product['min_qty']) {
					$from = $product['multiple'];
				} else {
					if ($product['min_qty'] % $product['multiple'] == 0) { 
						$from = $product['min_qty'];
					} else {
						$rest = $product['min_qty'] % $product['multiple'];
						$from = $product['min_qty'] + $product['multiple'] - $rest;
						if ($product['max_qty'] != 0 && $from > $product['max_qty']) {
							$from -= $product['multiple'];
						}
					}
				}
			} else {
				$product['min_qty'] ? $from = $product['min_qty'] : $from =1;
			}
		}?>
	<input class="validate_int" name="<?php echo $this_controller;?>_quantity" id="<?php echo $this_controller.'_quantity_'.$product['product_id']; ?>" size="3" maxlength="5" value="<?php echo $from ;?>">
	<?php }?>
<?php }?>
  <input type="hidden" id="<?php echo $this_controller.'_min_qty_'.$product['product_id']; ?>" value="<?php echo $product['min_qty'];?>">
  <input type="hidden" id="<?php echo $this_controller.'_max_qty_'.$product['product_id']; ?>" value="<?php echo $product['max_qty'];?>">
  <input type="hidden" id="<?php echo $this_controller.'_multiple_'.$product['product_id']; ?>" value="<?php echo $product['multiple'];?>">
</div>

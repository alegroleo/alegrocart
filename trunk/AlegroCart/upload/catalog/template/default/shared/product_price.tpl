	<?php $tax_included = isset($tax_included) ? $tax_included : 0;?>
	<?php if (($product['special_price'] > '$0.00' ) && date('Y-m-d H:i:s') >= $product['sale_start_date'] && date('Y-m-d') <= $product['sale_end_date']) { ?>
		<div class="price_new"><?php echo ($tax_included ? '<span class="tax">*</span>' : '') . $product['special_price']; ?></div>
		<div class="price_old"><!--<?php echo $regular_price; ?>-->
		<span><?php echo ($tax_included ? '<span class="tax">*</span>' : '') . $product['price']; ?></span></div>
		<?php if(isset($product['days_remaining']) && $product['show_days_remaining']){?>
			<div class="remaining">
			<?php echo $product['days_remaining']; ?>
			</div>
		<?php }?>
		<?php if(isset($product['options']) && $product['options'] && $columns == 1){?>
		<?php
		$product_total = number_format((float)str_replace($decimal_point,'.',str_replace($symbols,'',$product['special_price'])),$decimal_place,'.','');?>
		<input name="base_price" id="base_price_<?php echo $product['product_id']; ?>" value="<?php echo number_format((float)str_replace($decimal_point,'.',str_replace($symbols,'',$product['special_price'])),$decimal_place,'.','');?>" type="hidden">
		<?php } ?>
	<?php } else { ?>
		<div class="price"><?php echo ($tax_included ? '<span class="tax">*</span>' : '') . $product['price']; ?></div>
		<?php if(isset($location) && $location != 'column' && $location != 'columnright'){ 
			if ($discounted) {
				echo '<div class="price_old"><br></div>';
			}
			if ($remaining){
				echo '<div class="remaining"><br></div>';
			}
		}?>
		<?php if(isset($product['options']) && $product['options'] && $columns == 1){?>
		<?php 
		$product_total = number_format((float)str_replace($decimal_point,'.',str_replace($symbols,'',$product['price'])),$decimal_place,'.','');?>
		<input name="base_price" id="base_price_<?php echo $product['product_id']; ?>" value="<?php echo number_format((float)str_replace($decimal_point,'.',str_replace($symbols,'',$product['price'])),$decimal_place,'.','');?>" type="hidden">
		<?php } ?>
	<?php } ?>

	<?php if (($product['special_price'] > '$0.00' ) && date('Y-m-d H:i:s') >= $product['sale_start_date'] && date('Y-m-d') <= $product['sale_end_date']) { ?>
	  <div class="price_old"><?php echo $regular_price; ?>
	  <span><?php echo $product['price']; ?></span></div>
	  <div class="price_new"><?php echo $sale_price . $product['special_price']; ?></div>
	  <?php if(isset($product['options']) && $product['options'] && $columns == 1){?>
		<?php
		$product_total = number_format((float)str_replace($decimal_point,'.',str_replace($symbols,'',$product['special_price'])),$decimal_place,'.','');?>
	    <input name="base_price" id="base_price_<?php echo $product['product_id']; ?>" value="<?php echo number_format((float)str_replace($decimal_point,'.',str_replace($symbols,'',$product['special_price'])),$decimal_place,'.','');?>" type="hidden">
	  <?php } ?>
	<?php } else { ?>
	  <?php if(isset($location) && $location != 'column' && $location != 'columnright'){ echo '<br>';}?>
	  <div class="price_new"><?php echo $product['price']; ?></div>
	  <?php if(isset($product['options']) && $product['options'] && $columns == 1){?>
	  <?php 
	  $product_total = number_format((float)str_replace($decimal_point,'.',str_replace($symbols,'',$product['price'])),$decimal_place,'.','');?>
	  <input name="base_price" id="base_price_<?php echo $product['product_id']; ?>" value="<?php echo number_format((float)str_replace($decimal_point,'.',str_replace($symbols,'',$product['price'])),$decimal_place,'.','');?>" type="hidden">
	  <?php } ?>	  
	<?php } ?>
	
	
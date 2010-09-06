<?php foreach ($products as $key =>$product) { ?>
<div class="productcat_top"></div>
 <div class="productcat">
  <div class="pimage">
	<?php 
	  if(!isset($image_display)){$image_display = 'image_link';}
	  if ($image_display == 'fancybox'){
	    include $shared_path . 'product_image_fancybox.tpl';
	  } else if ($image_display == 'thickbox') {
		include $shared_path . 'product_image_thickbox.tpl';
	  } else {
	    include $shared_path . 'product_image_link.tpl';
	  }?>
  </div>
  <div class="ptext"><b><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></b><br>
   <?php echo $product['description']; ?><br>
   <?php if ($product['product_discounts']){
	echo "<div><b>".$text_quantity_discount.":</b><br>&nbsp;";
	foreach($product['product_discounts'] as $product_discount){
	 echo "&nbsp;(".$product_discount['discount_quantity'].")&nbsp;-".$product_discount['discount_amount']."(".$product_discount['discount_percent']."%)&nbsp;";
	}
	echo "</div>";
   }?>
   <div class="onhand"><?php echo $onhand.$product['stock_level']; ?></div>
   <?php include $shared_path . 'product_price.tpl' ;?>
   <?php if ($addtocart) { ?>
  <?php if ($product['options']){
   if(isset($product_options_select) && $product_options_select == 'radio'){
     include $shared_path . 'product_options_radio.tpl';
   } else {
	 include $shared_path . 'product_options.tpl';
  }} ?>
   <?php include $shared_path . 'add_to_cart.tpl';?>
   <?php } ?>
  </div>
 </div>
<div class="productcat_bottom"></div>
<?php } ?>
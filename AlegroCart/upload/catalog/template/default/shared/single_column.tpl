<?php foreach ($products as $key =>$product) { ?>
<div class="productcat_top"></div>
 <div class="productcat">
  <div class="pimage">
	<?php 
	  if(!isset($image_display)){$image_display = 'image_link';}
	  switch ($image_display){
		case 'fancybox':
			include $shared_path . 'product_image_fancybox.tpl';
			break;
		case 'thickbox':
			include $shared_path . 'product_image_thickbox.tpl';
			break;
		case 'image_link':
			include $shared_path . 'product_image_link.tpl';
			break;
		case 'lightbox':
			include $shared_path . 'product_image_lightbox.tpl';
			break;
		case 'no_image':
			break;
	  }?> 
  </div>
  <div class="ptext"><b><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></b><br>
  <?php if($product['model_number'] || $product['product_options']){?>
    <div class="model_number">
	  <?php echo $text_model_number;?>
	    <span id="<?php echo $this_controller . '_model_' . $product['product_id'];?>"><?php echo $product['model_number'];?></span>
		<?php if($product['product_options']){?>
		  <script language="JavaScript">
			$(document).ready(function(){
			  UpdateModel(<?php echo $product['product_id'] . ',"' . $this_controller . '"';?>);
			});
		  </script>
		  <?php foreach($product['product_options'] as $product_option){?>
		    <input type="hidden" id="<?php echo $this_controller . '_model_' . $product_option['product_option'];?>" value="<?php echo $product_option['model_number'];?>">
		  <?php }?>
		<?php }?>
    </div>
  <?php }?>
  
  
  
  
  
  
   <?php echo $product['description']; ?><br>
   <?php if ($product['product_discounts']){
	echo "<div><b>".$text_quantity_discount.":</b><br>&nbsp;";
	foreach($product['product_discounts'] as $product_discount){
	 echo "&nbsp;(".$product_discount['discount_quantity'].")&nbsp;-".$product_discount['discount_amount']."(".$product_discount['discount_percent']."%)&nbsp;";
	}
	echo "</div>";
   }?>
   <div class="onhand"><?php echo $onhand; ?>
	  <span id="<?php echo $this_controller . '_stock_level_' . $product['product_id'];?>"><?php echo $product['stock_level']; ?></span>
   </div>
   <?php if($product['product_options']){?>
    <script language="JavaScript">
	  $(document).ready(function(){
	    UpdateQuantity(<?php echo $product['product_id'] . ',"' . $this_controller . '"';?>);
	  });
	</script>
	<?php foreach($product['product_options'] as $product_option){?>
	  <input type="hidden" id="<?php echo $this_controller . '_stock_level_' . $product_option['product_option'];?>" value="<?php echo $product_option['quantity'];?>">
	<?php }?>
  <?php }?>  
   
   
   
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
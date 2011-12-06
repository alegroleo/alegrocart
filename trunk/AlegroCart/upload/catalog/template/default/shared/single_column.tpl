<?php if($show_stock_icon){?>
	<input type="hidden" id="stock_status_g" value="<?php echo $stock_status_g;?>">
	<input type="hidden" id="stock_status_o" value="<?php echo $stock_status_o;?>">
	<input type="hidden" id="stock_status_r" value="<?php echo $stock_status_r;?>">
	<input type="hidden" id="low_stock_warning" value="<?php echo $low_stock_warning;?>">
<?php }?>
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
   
   <?php if ($product['product_discounts']){?>
    <?php if($discount_options && $product['product_options']){?>
	  <script language="JavaScript">
		$(document).ready(function(){
		  UpdateDiscounts(<?php echo "'" . $this_controller . "'," . $product['product_id'] . ",". $decimal_place . ",'" . $decimal_point . "'";?>,0);
		});
	  </script>
	  <input id="<?php echo $this_controller.'_discounts_'.$product['product_id'];?>" hidden="hidden" value="<?php echo count($product['product_discounts']);?>">
	<?php }?>
	<?php echo "<div><div class=\"discount\">".$text_quantity_discount.":</div>&nbsp;";
	foreach($product['product_discounts'] as $key => $product_discount){
	 echo "&nbsp;(".$product_discount['discount_quantity'].")&nbsp;-".$symbol_left.'<span id="'. $this_controller.'_discount_'.$product['product_id'].'_'.$key.'">'.$product_discount['discount_amount']."</span>" .$symbol_right."(".'<span id="'. $this_controller.'_percent_'.$product['product_id'].'_'.$key.'">'.$product_discount['discount_percent']."</span>%)&nbsp;";
	}
	echo "</div>";
    }?>
    <?php if($show_stock || $show_stock_icon){?>
      <div class="onhand2"><?php echo $onhand; ?>
	    <span <?php if(!$show_stock){echo 'style="visibility:hidden;" ';}?>id="<?php echo $this_controller . '_stock_level_' . $product['product_id'];?>"><?php echo $product['stock_level']; ?></span>
	    <?php if($show_stock_icon){?>
		  <?php if($product['stock_level'] > 0 && $product['stock_level'] > $low_stock_warning){
		    $icon = $stock_status_g;
		  }else if($product['stock_level'] > 0 && $product['stock_level'] <= $low_stock_warning){
	        $icon = $stock_status_o;
	      } else {
		    $icon = $stock_status_r;
	      }?>
	    <img id="stock_icon_<?php echo $this_controller. '_' . $product['product_id'];?>" src="<?php echo $icon;?>" alt="<?php echo $text_stock_icon;?>" title="<?php echo $text_stock_icon;?>">
		<?php }?>
     </div>
   <?php }?>
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
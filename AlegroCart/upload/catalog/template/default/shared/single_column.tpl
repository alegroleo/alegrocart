<?php if($show_stock_icon){?>
	<input type="hidden" id="stock_status_g" value="catalog/styles/<?php echo $this->style?>/image/stock_status_g.png">
	<input type="hidden" id="stock_status_o" value="catalog/styles/<?php echo $this->style?>/image/stock_status_o.png">
	<input type="hidden" id="stock_status_r" value="catalog/styles/<?php echo $this->style?>/image/stock_status_r.png">
	<input type="hidden" id="low_stock_warning" value="<?php echo $low_stock_warning;?>">
<?php }?>
<?php foreach ($products as $key =>$product) { ?>
<div class="productcat_top"></div>
 <div class="productcat">
  <div class="pimage">
  <?php if (!$product['status']) { ?>
	<img src="<?php echo $product['thumb'];?>" id="<?php echo $this_controller.'_image'.$product['product_id']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>">
  <?php } else { 
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
	  }
  }?> 
  </div>
	<?php if ($product['status']) { ?>
		<div class="ptext"><b><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></b><br>
	<?php } else { ?>
		<div class="ptext"><b><?php echo $product['name']; ?></b><br>
	<?php } ?>
	<?php if (isset($product['average_rating'])) { ?>
    <div><img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" width="112" height="20" data-src="catalog/styles/<?php echo $this->style;?>/image/stars_<?php echo $product['average_rating'] . '.png'; ?>" alt="<?php echo $product['alt_rating']; ?>"></div>
	<?php } ?>
<?php if ($product['vendor_name']) { ?>
	<div class="vendor"><?php echo $text_soldby; ?><?php echo $product['vendor_name']; ?></div>
<?php } ?>
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
<br><?php echo $product['description']; ?>
 <?php if ($product['product_discounts'] && $product['status']){?>
    <?php if($discount_options && $product['product_options']){?>
	  <script language="JavaScript">
		$(document).ready(function(){
		  UpdateDiscounts(<?php echo "'" . $this_controller . "'," . $product['product_id'] . ",". $decimal_place . ",'" . $decimal_point . "'";?>,0);
		});
	  </script>
	  <input id="<?php echo $this_controller.'_discounts_'.$product['product_id'];?>" type="hidden" value="<?php echo count($product['product_discounts']);?>">
	<?php }?>
	<?php echo "<div><div class=\"discount\">".$text_quantity_discount.":</div>";
	foreach($product['product_discounts'] as $key => $product_discount){
	 echo "&nbsp;(".$product_discount['discount_quantity'].")&nbsp;-".$symbol_left.'<span id="'. $this_controller.'_discount_'.$product['product_id'].'_'.$key.'">'.$product_discount['discount_amount']."</span>" .$symbol_right."&nbsp;(".'<span id="'. $this_controller.'_percent_'.$product['product_id'].'_'.$key.'">'.$product_discount['discount_percent']."</span>%)<br>";
	}
	echo "</div>";
    }?>
    <?php if(($show_stock || $show_stock_icon) && $product['status']){?>
      <div class="onhand2"><?php echo $onhand; ?>
	    <span <?php if(!$show_stock){echo 'class="hidden" ';}?>id="<?php echo $this_controller . '_stock_level_' . $product['product_id'];?>"><?php echo $product['stock_level']; ?></span>
	    <?php if($show_stock_icon){?>
		  <?php if($product['stock_level'] > 0 && $product['stock_level'] > $low_stock_warning){
		    $icon ='catalog/styles/'.$this->style.'/image/stock_status_g.png';
		  }else if($product['stock_level'] > 0 && $product['stock_level'] <= $low_stock_warning){
	        $icon = 'catalog/styles/'.$this->style.'/image/stock_status_o.png';
	      } else {
		    $icon = 'catalog/styles/'.$this->style.'/image/stock_status_r.png';
	      }?>
	    <img id="stock_icon_<?php echo $this_controller. '_' . $product['product_id'];?>" src="<?php echo $icon;?>" width="11" height="11" alt="<?php echo $text_stock_icon;?>" title="<?php echo $text_stock_icon;?>">
		<?php }?>
     </div>
   <?php }?>
   <?php if (!$product['product_options']) { ?>
   <input type="hidden" id="<?php echo $this_controller . '_cart_level_' . $product['product_id'];?>" name="<?php echo $this_controller . '_cart_level_' . $product['product_id'];?>" value="<?php echo ($product['cart_level'] ? $product['cart_level'] : 0); ?>">
   <?php } ?>
   <?php if($product['product_options']){?>
    <script language="JavaScript">
	  $(document).ready(function(){
	    UpdateQuantity(<?php echo $product['product_id'] . ',"' . $this_controller . '"';?>);
	    UpdateAddToCartButton(<?php echo $product['product_id'] . ',"' . $this_controller . '","' . $Added_to_Cart . '","' . $Add_to_Cart . '"';?>);
	    UpdateAddToCart(<?php echo $product['product_id'] . ',"' . $this_controller . '"';?>);
	  });
	</script>
	<?php foreach($product['product_options'] as $product_option){?>
	  <input type="hidden" id="<?php echo $this_controller . '_stock_level_' . $product_option['product_option'];?>" value="<?php echo $product_option['quantity'];?>">
	  <input type="hidden" id="<?php echo $this_controller . '_cart_level_' . $product_option['product_option'];?>" name="<?php echo $this_controller . '_cart_level_' . $product_option['product_option'];?>" value="<?php echo ($product_option['cart_level'] ? $product_option['cart_level'] : 0); ?>">
	<?php }?>
  <?php }?>
  <?php if ($product['status']) { ?>
   <?php include $shared_path . 'product_price.tpl' ;?>
  <?php }?>
   <?php if ($addtocart) { ?>
  <?php if ($product['options']){
   if(isset($product_options_select) && $product_options_select == 'radio'){
     include $shared_path . 'product_options_radio.tpl';
   } else {
	 include $shared_path . 'product_options.tpl';
  }} ?>
   <?php include $shared_path . 'add_to_cart.tpl';?>
   <?php } ?>
  <?php if (!$product['status']) { ?>
    <div class="warning_bought_single">
	  <?php echo $product['popup'] ? $text_sold_out : $text_discontinued;?>
    </div>
  <?php }?>
  </div>
 </div>
<div class="productcat_bottom"></div>
<?php } ?>

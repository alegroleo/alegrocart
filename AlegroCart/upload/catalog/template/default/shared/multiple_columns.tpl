<div class="headingpadded"><h2><?php echo $heading_title . $heading_info; ?></h2></div>
 <div class="module">
  <?php $column_count=0;
   if (!$columns){
    $columns = 3;}
	$column_width = str_replace(',', '.', (int)(100-$columns)/$columns);
	if ($columns > 3){
	 $font_size = 9;
	 $button_font =11;
	 $min_height = 220;
	} else {
	 $font_size = 10;
	 $button_font = 11;
	 $min_height = 250;
	}
   ?>
   <?php foreach ($products as $key => $product) { ?>
    <?php if($column_count == $columns){
	 $column_count = 1;
	} else {
	 $column_count++;
	}
	if ($column_count == 1){
	 echo '<div class="column_row" style="min-height: '.$min_height.'px;">';
	}
   ?>
   <div class="module_content" style="width: <?php echo $column_width; ?>%;">
	<div class="a" >
	 <div class="img">
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
	</div>
    <div class="description"><b><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><br></b>
	<?php if (isset($product['average_rating'])) { ?>
    <div><img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" width="112px" height="20px" data-src="catalog/styles/<?php echo $this->style;?>/image/stars_<?php echo $product['average_rating'] . '.png'; ?>" alt="<?php echo $product['alt_rating']; ?>"></div>
	<?php } ?>
	<?php if ($product['description']) { ?>
    <div class="product_desc">
      <?php echo "<span style=\"font-size: ".$font_size."px\">" . $product['description'] . "</span>"; ?>
	</div>
	<?php } ?>
    </div>
	<?php include $shared_path . 'product_price.tpl';?>
	<?php if ($addtocart) { ?>
	 <?php $option = $product['options'];
	 if ($option == TRUE) {?>
	  <div class="options"><a href="<?php echo $product['href']; ?>">
	  <?php echo "<span style=\"font-size: ".$font_size."px\">" . $option . "</span>"; ?></a></div>
	 <?php } else { ?>
	  <?php include $shared_path . 'add_to_cart.tpl';?>
	 <?php } ?>
	<?php } ?>
	<?php if ($product['vendor_name']) { ?>
		<div class="vendor"><?php echo $text_soldby; ?><?php echo $product['vendor_name']; ?></div>
	<?php } ?>
	<?php if(($show_stock  || $show_stock_icon )&& !$product['options']){?>
		<div class="onhand"><?php echo $onhand; ?>
	    <span <?php if(!$show_stock){echo 'class="hidden" ';}?>id="<?php echo $this_controller . '_stock_level_' . $product['product_id'];?>"><?php echo $product['stock_level']; ?></span>
		  <?php if($show_stock_icon){?>
		    <?php if($product['stock_level'] > 0 && $product['stock_level'] > $low_stock_warning){
		      $icon = 'catalog/styles/'.$this->style.'/image/stock_status_g.png';
		    }else if($product['stock_level'] > 0 && $product['stock_level'] <= $low_stock_warning){
	          $icon = 'catalog/styles/'.$this->style.'/image/stock_status_o.png';
	        } else {
		      $icon = 'catalog/styles/'.$this->style.'/image/stock_status_r.png';
	        }?>
	        <img id="stock_icon_<?php echo $this_controller. '_' . $product['product_id'];?>" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="<?php echo $icon;?>" width="11px" height="11px" alt="<?php echo $text_stock_icon;?>" title="<?php echo $text_stock_icon;?>">
		  <?php }?>
		</div>
	<?php } else if($show_stock && $product['options']){?>
	  <br>
	<?php }?>
	<input type="hidden" id="<?php echo $this_controller . '_cart_level_' . $product['product_id'];?>" name="<?php echo $this_controller . '_cart_level_' . $product['product_id'];?>" value="<?php echo ($product['cart_level'] ? $product['cart_level'] : 0); ?>">
   </div>
   <?php if($column_count == $columns){
	echo "</div>";
	 if(($key + 1) < count($products)){
	  echo "<div class=\"divider\"></div>";}
	}
   ?>
  <?php } ?>
  <?php if($column_count != $columns){
   echo "</div>";}
  ?>
  <div class="clearfix"></div>
</div>
<div class="module_bottom"></div>

<div class="headingpadded"><?php echo $heading_title . $heading_info; ?></div>
 <div class="module">
  <?php $column_count=0;
   if (!$columns){
    $columns = 3;}
	$column_width = (int)(100-$columns)/$columns;
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
    <?php If($column_count == $columns){
	 $column_count = 1;
	} else {
	 $column_count++;
	}
	If($column_count == 1){
	 echo '<div class="column_row" style="min-height: '.$min_height.'px;">';
	}
   ?>
   <div class="module_content" style="width: <?php echo $column_width; ?>%;">
	<div class="a" >
	 <div class="img" style="text-align:center;">
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
    <div class="product_desc"<?php if($columns == 3){?> style="min-height: 50px;"<?php }?>>
      <?php echo "<span style=\"font-size: ".$font_size."px\">" . $product['description'] . "</span>"; ?>
	</div></div>
	<?php include $shared_path . 'product_price.tpl';?>
	<?php if ($addtocart) { ?>
	 <?php $option = $product['options'];
	 If ($option == TRUE) {?>
	  <div class="options"><a href="<?php echo $product['href']; ?>">
	  <?php echo "<span style=\"font-size: ".$font_size."px\">" . $options_text . "</span>"; ?></a></div>
	 <?php } else { ?>
	  <?php include $shared_path . 'add_to_cart.tpl';?>
	 <?php } ?>
	<?php } ?>
	<?php if(($show_stock  || $show_stock_icon )&& !$product['options']){?>
		<div class="onhand"><?php echo $onhand; ?>
	    <span <?php if(!$show_stock){echo 'style="visibility:hidden;" ';}?>id="<?php echo $this_controller . '_stock_level_' . $product['product_id'];?>" style="font-weight:normal"><?php echo $product['stock_level']; ?></span>
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
	<?php } else if($show_stock && $product['options']){?>
	  <br>
	<?php }?>
   </div>
   <?php If($column_count == $columns){
	echo "</div>";
	 if(($key + 1) < count($products)){
	  echo "<div class=\"divider\"></div>";}
	}
   ?>
  <?php } ?>
  <?php If($column_count != $columns){
   echo "</div>";}
  ?>
  <div class="clearfix"></div>
</div>
<div class="module_bottom"></div>
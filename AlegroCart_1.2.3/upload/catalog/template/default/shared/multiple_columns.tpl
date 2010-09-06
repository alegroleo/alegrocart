<div class="headingpadded"><h1><?php echo $heading_title . $heading_info; ?></h1></div>
 <div class="module">
  <?php $column_count=0;
   if (!$columns){
    $columns = 3;}
	$column_width = (int)(100-$columns)/$columns;
	if ($columns > 3){
	 $font_size = 9;
	 $button_font = 9;
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
	 <div class="img">
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
	</div>
    <b><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><br></b>
    <div class="product_desc"<?php if($columns == 3){?> style="min-height: 50px;"<?php }?>>
      <?php echo "<span style=\"font-size: ".$font_size."px\">" . $product['description'] . "</span>"; ?>
	</div>
	<div class="onhand"><?php echo $onhand.$product['stock_level']; ?></div>
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
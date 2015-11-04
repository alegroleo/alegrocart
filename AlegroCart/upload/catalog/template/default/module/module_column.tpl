<?php 
  $head_def->setcss( $this->style . "/css/module_column.css");
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("ajax/jqueryadd2cart.js");
  if($image_display == 'thickbox'){
	$head_def->setcss($this->style . "/css/thickbox.css");  
	$head_def->set_javascript("thickbox/thickbox-compressed.js");
  } else if ($image_display == 'fancybox'){
	$head_def->setcss($this->style . "/css/jquery.fancybox.css\" media=\"screen" ); 
	$head_def->set_javascript("fancybox/jquery.fancybox.js");
  } else if ($image_display == 'lightbox'){
    $head_def->setcss($this->style . "/css/lightbox.css\"  media=\"screen" ); 
	$head_def->set_javascript("lightbox/lightbox.js");
  ?>
  <script>
	$(document).ready(function(){
		$(".lightbox").lightbox({
			fitToScreen: true,
			imageClickClose: true
		});
	});
  </script>
  <?php }
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';
  $columns = 0;
?>

<?php if (isset($products)) { ?>
<div class="headingcolumn"><h3><?php echo $heading_title; ?></h3></div>
<div class="module_column">
 <?php foreach ($products as $key =>$product) { ?>
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
  <div class="description"><b><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><br></b>
	<?php if (isset($product['average_rating'])) { ?>
    <div><img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" width="112" height="20" data-src="catalog/styles/<?php echo $this->style;?>/image/stars_<?php echo $product['average_rating'] . '.png'; ?>" alt="<?php echo $product['alt_rating']; ?>"></div>
	<?php } ?>
  <?php echo $product['description']; ?><br></div>

  <?php include $shared_path . 'product_price.tpl';?>
  <?php if ($add_enable && $addtocart) { ?>
   <?php $option = $product['options'];
   if ($option == TRUE) {?>
	<div class="options"><a href="<?php echo $product['href']; ?>">
	<?php echo $option; ?></a></div>
   <?php } else { ?>
	<?php include $shared_path . 'add_to_cart.tpl';?>
   <?php }?>
  <?php }?>
  <?php if ($product['vendor_name']) { ?>
	<div class="vendor"><?php echo $text_soldby; ?><?php echo $product['vendor_name']; ?></div>
  <?php } ?>
  <?php if(($show_stock  || $show_stock_icon ) && !$product['options']){?>
    <div class="onhand">
	  <?php echo $onhand.($show_stock ? $product['stock_level'] : ''); ?>
	  <?php if($show_stock_icon){?>
		<?php if($product['stock_level'] > 0 && $product['stock_level'] > $low_stock_warning){
		  $icon = 'catalog/styles/'.$this->style.'/image/stock_status_g.png';
		}else if($product['stock_level'] > 0 && $product['stock_level'] <= $low_stock_warning){
	      $icon = 'catalog/styles/'.$this->style.'/image/stock_status_o.png';
		} else {
		  $icon = 'catalog/styles/'.$this->style.'/image/stock_status_r.png';
		}?>
		<img id="stock_icon_<?php echo $this_controller. '_' . $product['product_id'];?>" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="<?php echo $icon;?>" width="11" height="11" alt="<?php echo $text_stock_icon;?>" title="<?php echo $text_stock_icon;?>">
	  <?php }?>
	</div>
  <?php }?>
  <input type="hidden" id="<?php echo $this_controller . '_cart_level_' . $product['product_id'];?>" name="<?php echo $this_controller . '_cart_level_' . $product['product_id'];?>" value="<?php echo ($product['cart_level'] ? $product['cart_level'] : 0); ?>">
  <?php if(($key + 1) < count($products)){
   echo "<div class=\"divider\"></div>";} ?>
  <?php } ?>
</div>
<?php } ?>
<div class="columnBottom"></div>

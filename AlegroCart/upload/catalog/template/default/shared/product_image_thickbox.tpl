<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb'];?>" id="<?php echo $this_controller.'_image'.$product['product_id']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"></a>

<div class="enlarge"><a id="<?php echo $this_controller.$product['product_id']; ?>" href="<?php echo $product['popup']; ?>" class="thickbox"><?php echo $text_enlarge; ?></a></div>

<?php if(isset($product['product_options']) && $product['product_options']){?>
  <script language="JavaScript">
	$(document).ready(function(){
	  UpdateImage(<?php echo $product['product_id'] . ',"' . $this_controller . '"';?>);
	});
  </script>
  <input type="hidden" id="<?php echo $this_controller.'_thumb_'.$product['product_id']; ?>" value="<?php echo $product['thumb'];?>">
  <input type="hidden" id="<?php echo $this_controller. '_popup_' . $product['product_id']; ?>" value="<?php echo $product['popup']; ?>">
  <?php foreach($product['product_options'] as $product_option){?>
    <input type="hidden" id="<?php echo $this_controller . '_thumb_' . $product_option['product_option'];?>" value="<?php echo $product_option['thumb'];?>">
	<input type="hidden" id="<?php echo $this_controller . '_popup_' . $product_option['product_option'];?>" value="<?php echo $product_option['popup'];?>">  
  <?php }?>
<?php }?>
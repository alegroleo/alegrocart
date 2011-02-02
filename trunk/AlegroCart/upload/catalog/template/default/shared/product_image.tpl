<?php if($image_display == 'thickbox'){?>
  <a href="<?php echo $product['popup']; ?>" class="thickbox" id="<?php echo $this_controller.$product['product_id']; ?>">
  <img src="<?php echo $product['thumb'];?>" id="<?php echo $this_controller.'_image'.$product['product_id']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>">
  <div class="enlarge"><?php echo $text_enlarge; ?></div></a>
<?php } elseif ($image_display == 'fancybox') {?>
<script type="text/javascript">$(document).ready(function(){$("a#<?php echo $this_controller.$product['product_id']; ?>").fancybox({'titleShow'  : false, 'transitionIn' : 'elastic',  'transitionOut' : 'elastic'}); });</script>
<a href="<?php echo $product['popup']; ?>" id="<?php echo $this_controller.$product['product_id']; ?>">
<img src="<?php echo $product['thumb'];?>" id="<?php echo $this_controller.'_image'.$product['product_id']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"><div class="enlarge"><?php echo $text_enlarge; ?></div></a>
<?php } elseif ($image_display == 'lightbox') { ?>
<a class="lightbox" href="<?php echo $product['popup']; ?>" id="<?php echo $this_controller.$product['product_id']; ?>">
<img src="<?php echo $product['thumb'];?>" id="<?php echo $this_controller.'_image'.$product['product_id']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"><div class="enlarge"><?php echo $text_enlarge; ?></div></a>
<?php } ?>

<?php if($product_options){?>
  <script language="JavaScript">
	$(document).ready(function(){
	  UpdateImage(<?php echo $product['product_id'] . ',"' . $this_controller . '"';?>);
	});
  </script>
  <input type="hidden" id="<?php echo $this_controller.'_thumb_'.$product['product_id']; ?>" value="<?php echo $product['thumb'];?>">
  <input type="hidden" id="<?php echo $this_controller. '_popup_' . $product['product_id']; ?>" value="<?php echo $product['popup']; ?>">
  <?php foreach($product_options as $product_option){?>
    <input type="hidden" id="<?php echo $this_controller . '_thumb_' . $product_option['product_option'];?>" value="<?php echo $product_option['thumb'];?>">
	<input type="hidden" id="<?php echo $this_controller . '_popup_' . $product_option['product_option'];?>" value="<?php echo $product_option['popup'];?>">  
  <?php }?>
<?php }?>
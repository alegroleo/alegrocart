<?php if($image_display == 'thickbox'){?>
<a href="<?php echo $product['popup']; ?>" class="thickbox">
<img src="<?php echo $product['thumb'];?>" id="<?php echo $this_controller.'_image'.$product['product_id']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>">
<div class="enlarge"><?php echo $text_enlarge; ?></div></a>
<?php } else {?>
<script type="text/javascript">$(document).ready(function(){$("a#<?php echo $this_controller.$product['product_id']; ?>").fancybox({'titleShow'  : false, 'transitionIn' : 'elastic',  'transitionOut' : 'elastic'}); });</script>
<a href="<?php echo $product['popup']; ?>" id="<?php echo $this_controller.$product['product_id']; ?>">
<img src="<?php echo $product['thumb'];?>" id="<?php echo $this_controller.'_image'.$product['product_id']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"><div class="enlarge"><?php echo $text_enlarge; ?></div></a>
<?php }?>
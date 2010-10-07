
<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb'];?>" id="<?php echo $this_controller.'_image'.$product['product_id']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"></a>

<div class="enlarge"> <a class="lightbox" id="<?php echo $this_controller.$product['product_id']; ?>" href="<?php echo $product['popup']; ?>"><?php echo $text_enlarge; ?></a></div>
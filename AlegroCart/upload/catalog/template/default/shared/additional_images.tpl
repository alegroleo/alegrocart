<?php if($image_display == 'thickbox'){?>
   <div class="images"><a href="<?php echo $image['popup']; ?>" title="<?php echo $image['title']; ?>" class="thickbox" rel="additional"><img src="<?php echo $image['thumb']; ?>" alt="<?php echo $image['title']; ?>"></a>
    </div>
<?php } elseif ($image_display == 'fancybox'){?>
   <script type="text/javascript">$(document).ready(function(){$("a.group").fancybox({openEffect : 'elastic', closeEffect : 'elastic'}); });</script>
   <div class="images">
      <a class="group" rel="additional" href="<?php echo $image['popup']; ?>" id="<?php echo "additional_".$image['image_id']; ?>"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $image['title']; ?>" alt="<?php echo $image['title']; ?>"></a>
   </div>
<?php } elseif ($image_display == 'lightbox') { ?>
 <div class="images">
      <a class="lightbox" rel="additional" href="<?php echo $image['popup']; ?>" id="<?php echo "additional_".$image['image_id']; ?>"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $image['title']; ?>" alt="<?php echo $image['title']; ?>"></a>
   </div>
   
<?php }?>

<?php if($image_display == 'thickbox'){?>
	<div class="images"><a href="<?php echo $image['popup']; ?>" title="<?php echo $image['title']; ?>" class="thickbox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $image['title']; ?>" alt="<?php echo $image['title']; ?>"></a>
    </div>
<?php } else {?>
	<script type="text/javascript">$(document).ready(function(){$("a#<?php echo "additional_".$image['image_id']; ?>").fancybox({'titleShow'  : false, 'transitionIn' : 'elastic',  'transitionOut' : 'elastic'}); });</script>
	<div class="images">
		<a href="<?php echo $image['popup']; ?>" id="<?php echo "additional_".$image['image_id']; ?>"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $image['title']; ?>" alt="<?php echo $image['title']; ?>"></a>
	</div>
<?php }?>
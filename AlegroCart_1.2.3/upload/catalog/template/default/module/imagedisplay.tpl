<?php 
  $head_def->setcss( $this->style . "/css/homepage.css");
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';
?>
<div class="image_display" id="image_display">
<?php if(isset($image_displays)){ ?>
  <?php foreach($image_displays as $image_display){ ?>
    <?php if(strlen($image_display['flash']) > 3){?>
	  <div class="flash">
		<object type="application/x=shockwave-flash" data="<?php echo $image_display['flash'];?>" width="<?php echo $image_display['flash_width']; ?>" height="<?php echo $image_display['flash_height']; ?>">
		<param name="movie" value="<?php echo $image_display['flash'];?>">
		<param name="loop" value="<?php echo $image_display['flash_loop'];?>">
		<embed src="<?php echo $image_display['flash'];?>" width="<?php echo $image_display['flash_width']; ?>" height="<?php echo $image_display['flash_height']; ?>" name="loop" value="<?php echo $image_display['flash_loop'];?>">
		</embed>
		</object>
	  </div>
    <?php } ?>
	<?php if($image_display['image']){?>
	  <div class="image_display_img">
		<img src="<?php echo $image_display['image'];?>" alt="Extra">
	  </div>
	<?php } ?>
  <?php } ?>
<?php } ?>
</div>
<?php 
  $head_def->setcss( $this->style . "/css/homepage.css");
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';
  if(isset($image_displays)){
	$head_def->setcss($this->style . "/css/slick.css");
	$head_def->setcss($this->style . "/css/slick-theme.css");
	$head_def->set_javascript("slider/slick.min.js");
  }
?>
<div class="image_display" id="image_display">
<?php if(isset($image_displays)){ ?>
  <?php foreach($image_displays as $image_display){ ?>
    <?php if($image_display['slides']){?>
      <div id="id_slider_<?php echo $image_display['id_id'] ;?>">
        <?php foreach ($image_display['slides'] as $slide) { ?>
        <div><img src="<?php echo $slide['filename'];?>" width="<?php echo $slide['width'];?>" height="<?php echo $slide['height'];?>" alt="<?php echo $slide['filename'];?>"></div>
        <?php } ?>
      </div>
      <?php echo $image_display['sliderjs'] ;?>
    <?php }?>
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
		<img src="<?php echo $image_display['image'];?>" width="<?php echo $image_display['image_width'];?>" height="<?php echo $image_display['image_height'];?>" alt="<?php echo $extra_image;?>">
	  </div>
	<?php } ?>
  <?php } ?>
<?php } ?>
</div>

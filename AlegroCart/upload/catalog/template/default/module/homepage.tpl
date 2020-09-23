<?php 
  $head_def->setcss( $this->style . "/css/homepage.css");
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("ajax/jqueryadd2cart.js");
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';
  if(isset($slides)){
	$head_def->setcss($this->style . "/css/slick.css");
	$head_def->setcss($this->style . "/css/slick-theme.css");
	$head_def->set_javascript("slider/slick.min.js");
  }
?>
<div class="homepage" id="homepage">
<div class="headingpadded"><h2><?php echo $heading_title; ?></h2>
<div class="right"><a href="<?php echo $close_homepage;?>"><?php echo $skip_intro;?></a></div>
</div>
<div class="module">
<?php if(isset($slides)){?>
  <div id="homeslider">
    <?php foreach ($slides as $slide) { ?>
    <div><img src="<?php echo $slide['filename'];?>" width="<?php echo $slide['width'];?>" height="<?php echo $slide['height'];?>" alt="<?php echo $slide['filename'];?>"></div>
    <?php } ?>
  </div>
  <?php echo $sliderjs;?>
<?php }?>
<?php if(isset($flash)){ ?>
  <div class="flash">
	<object type="application/x=shockwave-flash" data="<?php echo $flash;?>" width="<?php echo $flash_width; ?>" height="<?php echo $flash_height; ?>">
    <param name="movie" value="<?php echo $flash;?>">
	<param name="loop" value="<?php echo $flash_loop;?>">
    <embed src="<?php echo $flash;?>" width="<?php echo $flash_width; ?>" height="<?php echo $flash_height; ?>" name="loop" value="<?php echo $flash_loop;?>">
    </embed>
    </object>
  </div>
 <?php echo '<div class="divider"></div>';}?>
<?php if(isset($image)){?>
  <div class="home_image">
    <img src="<?php echo $image;?>" width="<?php echo $image_width;?>" height="<?php echo $image_height;?>" alt="<?php $welcome_image;?>">
  </div>
<?php }?>
<?php if(isset($welcome) || isset($description)){?>
<div class="clearfix"></div>
<?php if(isset($welcome)){
   echo '<div class="welcome">';
   echo $welcome . '</div>';
   echo '<div class="divider"></div>';
}?>
<?php if(isset($description)){
   echo '<div class="homepage_desc">';
  echo $description . '</div>';
}?>
<?php }?>
</div>
<div class="module_bottom"></div>
</div>

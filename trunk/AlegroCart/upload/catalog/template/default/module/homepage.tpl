<?php 
  $head_def->setcss( $this->style . "/css/homepage.css");
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("ajax/jqueryadd2cart.js");
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';
?>
<div class="homepage" id="homepage">
<div class="headingpadded"><?php echo $heading_title; ?>
<div class="right"><a href="<?php echo $close_homepage;?>"><?php echo $skip_intro;?></a></div>
</div>
<div class="module">
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
    <img src="<?php echo $image;?>" alt="Welcome Image">
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
</div>
<div class="module_bottom"></div>
<?php }?>
</div>
<?php 
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("ajax/validateforms.js");
 ?>
<div class="a" <?php echo 'style="width:' . $logo_width . 'px; height:'  . $logo_height . 'px; left:' . $logo_left . 'px; top:' . $logo_top . 'px;"'?>>
<img src="<?php echo $store_logo;?>" alt="<?php echo $store;?>">
</div>
<?php if(isset($rss_link)){?>
<div class="rss"><a href="<?php echo $rss_link;?>">RSS<img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" width="20" height="20" data-src="catalog/styles/<?php echo $this->style;?>/image/rss.png" alt="RSS Feeds"></a></div>
<?php }?>

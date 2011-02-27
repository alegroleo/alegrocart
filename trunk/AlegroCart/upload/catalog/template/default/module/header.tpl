<div class="a" <?php echo 'style="width:' . $logo_width . 'px; height:'  . $logo_height . 'px; left:' . $logo_left . 'px; top:' . $logo_top . 'px;"'?>>
<img src="<?php echo $store_logo;?>" alt="<?php echo $store;?>">
</div>
<?php if(isset($rss_link)){?>
<div class="rss"><a href="<?php echo $rss_link;?>"><img src="<?php echo $rss_image;?>" alt="RSS Feeds"></a></div>
<?php }?>
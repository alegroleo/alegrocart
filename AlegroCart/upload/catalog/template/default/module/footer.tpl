<div class="a">
<!-- <img src="catalog/styles/<?php echo $this->style?>/image/paypal.png" title="<?php echo $text_papal;?>" alt="<?php echo $text_papal;?>">&nbsp; -->
<!--   <img src="catalog/styles/<?php echo $this->style?>/image/visa.png" title="<?php echo $text_visa;?>" alt="<?php echo $text_visa;?>">&nbsp; -->
<!--   <img src="catalog/styles/<?php echo $this->style?>/image/master.png" title="<?php echo $text_mastercard;?>" alt="<?php echo $text_mastercard;?>">&nbsp; -->
</div>
<div class="b"><?php echo $todays_date.'<br>'; ?>&nbsp;&nbsp;&nbsp;<?php echo $text_powered_by; ?></div>
<?php if($w3c_status){?>
  <div class="a">
     <img src="catalog/styles/<?php echo $this->style?>/image/valid-html401.png" alt="<?php echo $text_wc3html;?>">&nbsp;
     <img src="catalog/styles/<?php echo $this->style?>/image/vcss.gif" alt="<?php echo $text_wc3css;?>">
  </div>
<?php }?>
<?php if ($flogo){?>
<div class="c" <?php echo 'style="width:' . $footer_logo_width . 'px; height:'  . $footer_logo_height . 'px; left:' . $footer_logo_left . 'px; margin-top:' . $footer_logo_top . 'px;"'?>>
	<img src="<?php echo $footer_logo;?>" title="<?php echo $text_footer_logo;?>" alt="<?php echo $text_footer_logo;?>">
</div>
<?php }?>
<?php if($show_version){?>
<div class="d"><?php echo $text_version . $version;?>
</div>
<?php }?>
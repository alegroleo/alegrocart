<div class="a">
<!-- <img src="catalog/styles/<?php echo $this->style?>/image/paypal.png" title="<?php echo $text_papal;?>" width="80" height="26" alt="<?php echo $text_papal;?>">&nbsp; -->
<!-- <img src="catalog/styles/<?php echo $this->style?>/image/visa.png" title="<?php echo $text_visa;?>" width="39" height="26" alt="<?php echo $text_visa;?>">&nbsp; -->
<!-- <img src="catalog/styles/<?php echo $this->style?>/image/master.png" title="<?php echo $text_mastercard;?>" width="39" height="26" alt="<?php echo $text_mastercard;?>">&nbsp; -->
</div>
<div class="b"><?php echo $todays_date.'<br>'; ?>&nbsp;&nbsp;&nbsp;<?php echo $text_powered_by; ?></div>
<?php if($w3c_status){?>
  <div class="a">
	<img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="catalog/styles/<?php echo $this->style?>/image/valid-html401.png" width="88" height="31" alt="<?php echo $text_wc3html;?>">&nbsp;
	<img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="catalog/styles/<?php echo $this->style?>/image/vcss.gif" width="88" height="31" alt="<?php echo $text_wc3css;?>">
  </div>
<?php }?>
<?php if ($flogo){?>
<div class="c" <?php echo 'style="left:' . $footer_logo_left . 'px; margin-top:' . $footer_logo_top . 'px;"'?>>
	<img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" title="<?php echo $text_footer_logo;?>" data-src="<?php echo $footer_logo;?>" width="<?php echo $footer_logo_width;?>" height="<?php echo $footer_logo_height;?>" alt="<?php echo $text_footer_logo;?>">
</div>
<?php }?>
<?php if($show_version){?>
<div class="d"><?php echo $text_version . $version;?>
</div>
<?php }?>
<script type="text/javascript">
    $(document).ready(function() {
	RegisterValidation();
    });
</script>

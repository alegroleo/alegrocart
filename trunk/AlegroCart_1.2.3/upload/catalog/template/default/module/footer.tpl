<div class="a"><img src="catalog/styles/<?php echo $this->style?>/image/paypal.png" alt="PayPal">&nbsp;
  <img src="catalog/styles/<?php echo $this->style?>/image/visa.png" alt="Visa">&nbsp;
  <img src="catalog/styles/<?php echo $this->style?>/image/master.png" alt="Master Card">&nbsp;
</div>

<div class="b"><?php echo date("F-d-Y").'<br>'; ?>&nbsp;&nbsp;&nbsp;<?php echo $text_powered_by; ?></div>
<?php if($w3c_status){?>
  <div class="a">
    <img src="catalog/styles/<?php echo $this->style?>/image/valid-html401.png" alt="html">&nbsp;
    <img src="catalog/styles/<?php echo $this->style?>/image/vcss.gif" alt="css">
  </div>
<?php }?>
<div class="c"><a href="http://www.alegrocart.com"><img src="catalog/styles/<?php echo $this->style?>/image/AlegroCart.png" alt="AlegroCart"></a></div>
<?php if($show_version){?>
<div class="d"><?php echo $text_version . $version;?>
</div>
<?php }?>
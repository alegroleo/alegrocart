<?php if($location == 'header'){?>
<div id="bar">
<?php } else if($location == 'column' || $location == 'columnright'){?>
<div id="bar" style="position: relative; width: 184px; left: 30px; top: 5px; height: 130px; padding-bottom: 10px;">
<?php } else {?>
<div id="bar" style="position: relative; left: 30px; top: 5px; padding-bottom: 10px;">
<?php }?>
<div class="b">
  <a href="<?php echo $home; ?>"><?php echo $text_home; ?></a>
  <a href="<?php echo $account; ?>"><?php echo $text_account; ?></a>
  <?php if (@$login) { ?>
  <a href="<?php echo $login; ?>"><?php echo $text_login; ?></a>
  <?php } else { ?>
  <a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a>
  <?php } ?>
  <a href="<?php echo $cart; ?>"><?php echo $text_cart; ?></a>
  <a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a>
</div>
</div>
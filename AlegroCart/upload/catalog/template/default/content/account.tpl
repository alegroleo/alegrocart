<?php 
  $head_def->setcss($this->style . "/css/account.css");
?>
<div class="headingbody"><h1><?php echo $heading_title; ?></h1></div>
<div class="contentBody">
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<div id="account">
  <div class="a"><?php echo $text_my_account; ?></div>
  <div class="b">
    <ul>
      <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
      <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
      <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
    </ul>
  </div>
  <div class="c"><?php echo $text_my_orders; ?></div>
  <div class="d">
    <ul>
      <li><a href="<?php echo $history; ?>"><?php echo $text_history; ?></a></li>
      <?php if ($enable_download) { ?>
      	<li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
      <?php } ?>
      <li><a href="<?php echo $bought; ?>"><?php echo $text_bought; ?></a></li>
    </ul>
  </div>
  <div class="c"><?php echo $text_my_newsletter; ?></div>
  <div class="d">
    <ul>
      <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
    </ul>
  </div>
</div></div>
<div class="contentBodyBottom"></div>

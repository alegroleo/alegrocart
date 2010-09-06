<?php 
  $head_def->setcss($this->style . "/css/checkout_shipping.css");
?>
<div class="headingbody"><?php echo $heading_title; ?></div>
<div class="contentBody">
<?php if (isset($message)) { ?> 
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<?php if ($error) { ?> 
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  
  <div id="shipping">
    <div class="a"><?php echo $text_shipping; ?></div>
    <div class="b">
      <table>
        <tr>
          <td width="50%" valign="top"><?php echo $text_shipping_to; ?></td>
          <td valign="top" rowspan="2"><b><?php echo $text_shipping_address; ?></b><br>
            <?php echo $address; ?></td>
        </tr>
        <tr>
          <td align="center" valign="top"><input type="button" value="<?php echo $button_change_address; ?>" onclick="location='<?php echo $change_address; ?>'"></td>
        </tr>
      </table>
    </div>
    <?php if ($methods) { ?>
    <div class="c"><?php echo $text_shipping_method; ?></div>
    <div class="d"><?php echo $text_shipping_methods; ?>
      <table>
        <?php foreach ($methods as $method) { ?>
        <tr>
          <td class="g" colspan="2"><b><?php echo $method['title']; ?></b></td>
        </tr>
        <?php if (!$method['error']) { ?>
        <?php foreach ($method['quote'] as $quote) { ?>
        <tr>
          <td class="g"><label for="<?php echo $quote['id']; ?>">
            <?php if ($quote['id'] == $default) { ?>
            <input type="radio" name="shipping" value="<?php echo $quote['id']; ?>" id="<?php echo $quote['id']; ?>" CHECKED>
            <?php } else { ?>
            <input type="radio" name="shipping" value="<?php echo $quote['id']; ?>" id="<?php echo $quote['id']; ?>">
            <?php } ?>
            <?php echo $quote['title']; ?></label></td>
          <td class="i"><label for="<?php echo $quote['id']; ?>"><?php echo $quote['text']; ?></label></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td colspan="2" class="g"><div class="warning"><?php echo $method['error']; ?></div></td>
        </tr>
        <?php } ?>
        <?php } ?>
      </table>
    </div>
    <?php } ?>
    <div class="e"><?php echo $text_comments; ?></div>
    <div class="f">
      <textarea name="comment" cols="89" rows="8"><?php echo $comment; ?></textarea>
    </div>
	<input type="hidden" name="account_validation" value="<?php echo $account_validation;?>">
  </div></div>
  <div class="contentBodyBottom"></div>
  <div class="buttons">
    <table>
      <tr>
        <td align="left"><input type="button" value="<?php echo $button_back; ?>" onclick="location='<?php echo $back; ?>'"></td>
        <td align="right"><input type="submit" value="<?php echo $button_continue; ?>"></td>
      </tr>
    </table>
  </div>
</form>

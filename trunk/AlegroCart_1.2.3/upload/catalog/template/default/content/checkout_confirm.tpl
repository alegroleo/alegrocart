<?php 
  $head_def->setcss($this->style . "/css/checkout_confirm.css");
?>
<div class="headingbody"><?php echo $heading_title; ?></div>
<div class="contentBody">
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>

<div id="checkout">
  <div class="a">
    <table>
      <tr>
        <td><?php if ($shipping_method) { ?>
          <b><?php echo $text_shipping_method; ?></b><br>
          <?php echo $shipping_method; ?><br>
          <a href="<?php echo $checkout_shipping; ?>"><?php echo $text_change; ?></a><br>
          <br>
          <?php } ?>
          <b><?php echo $text_payment_method; ?></b><br>
          <?php echo $payment_method; ?><br>
          <a href="<?php echo $checkout_payment; ?>"><?php echo $text_change; ?></a></td>
        <td><?php if ($shipping_address) { ?>
          <b><?php echo $text_shipping_address; ?></b><br>
          <?php echo $shipping_address; ?><br>
          <a href="<?php echo $checkout_shipping_address; ?>"><?php echo $text_change; ?></a>
          <?php } ?></td>
        <td><b><?php echo $text_payment_address; ?></b><br>
          <?php echo $payment_address; ?><br>
          <a href="<?php echo $checkout_payment_address; ?>"><?php echo $text_change; ?></a></td>
      </tr>
    </table>
  </div>
  <div class="b">
    <table class="c">
      <tr>
        <th class="left"><?php echo $text_product; ?></th>

        <th class="left"><?php echo $text_quantity; ?></th>
        <th class="right"><?php echo $text_price; ?></th>
        <th class="right"><?php echo $text_special; ?></th>
        <th class="right"><?php echo $text_total; ?></th>
      </tr>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php foreach ($product['option'] as $option) { ?>
          <br>
          &nbsp;<small> - <?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
          <?php } ?>
        </td>

        <td class="left"><?php echo $product['quantity']; ?></td>
        <td class="right"><?php if (!$product['discount']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="checkout_price_old "><?php echo $product['price']; ?></span><br>
          <span class="checkout_price_new "><?php echo $product['discount']; ?></span>
          <?php } ?>
        </td>
		<td class="right"><span class="checkout_price_new ">
		  <?php if ($product['special_price'] > "$0.00"){echo $product['special_price'];} ?>
		</span></td>
        <td class="right"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>
    </table>
    <br>
    <div class="f">
      <table>
        <?php foreach ($totals as $total) { ?>
        <tr>
          <td class="right" colspan="4"><?php echo $total['title']; ?></td>
          <td class="right"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
  </div>
  <?php if ($comment) { ?>
  <div class="d"><b><?php echo $text_your_comments; ?></b></div>
  <div class="e"><?php echo $comment; ?></div>
  <?php } ?>
  <div class="e">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
      <table width="100%">
        <tr>
          <td class="right"><?php echo $entry_coupon; ?></td>
          <td class="right" width="1"><input type="text" name="coupon" value="<?php echo $coupon; ?>"></td>
          <td class="right" width="1"><input type="submit" value="<?php echo $button_update; ?>"></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<form action="<?php echo $payment_url; ?>" method="post" enctype="<?php echo (isset($payment_form_enctype) && $payment_form_enctype)?$payment_form_enctype:'multipart/form-data'?>">
  <?php if ($fields) { ?>
  <div class="a"><?php echo $fields; ?></div>
  <?php } ?>
  <?php if (isset($agree)) { ?>
  <div class="buttons">
    <table>
      <tr>
        <td align="left"><input type="button" value="<?php echo $button_back; ?>" onclick="location='<?php echo $back; ?>'"></td>
        <td align="right"><?php echo $agree; ?></td>
        <td align="right" width="5"><input type="checkbox" name="agreed" value="1" onclick="document.getElementById('submit').disabled = (this.checked == true) ? false : true;"></td>
        <td align="right" width="5"><input type="submit" value="<?php echo $button_continue; ?>" id="submit" disabled></td>
      </tr>
    </table>
  </div>
  <?php } else { ?>
  <div class="buttons">
    <table>
      <tr>
        <td align="left"><input type="button" value="<?php echo $button_back; ?>" onclick="location='<?php echo $back; ?>'"></td>
        <td align="right"><input type="submit" value="<?php echo $button_continue; ?>"></td>
      </tr>
    </table>
  </div>
  <?php } ?>
</form></div>
<div class="contentBodyBottom"></div>
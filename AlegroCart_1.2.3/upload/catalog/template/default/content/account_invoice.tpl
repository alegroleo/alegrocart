<?php 
  $head_def->setcss($this->style . "/css/account_invoice.css");
?>
<div class="headingbody"><?php echo $heading_title; ?></div>
<div class="contentBody">
<div id="invoice">
  <div class="a">
    <table>
      <tr>
        <td><b><?php echo $text_order; ?></b><br>
          <?php echo $reference; ?><br>
          <br>
          <b><?php echo $text_email; ?></b><br>
          <?php echo $email; ?><br>
          <br>
          <b><?php echo $text_telephone; ?></b><br>
          <?php echo $telephone; ?><br>
          <br>
          <?php if ($fax) { ?>
          <b><?php echo $text_fax; ?></b><br>
          <?php echo $fax; ?><br>
          <br>
          <?php } ?>
          <?php if ($shipping_method) { ?>
          <b><?php echo $text_shipping_method; ?></b><br>
          <?php echo $shipping_method; ?><br>
          <br>
          <?php } ?>
          <b><?php echo $text_payment_method; ?></b><br>
          <?php echo $payment_method; ?></td>
        <td><?php if ($shipping_address) { ?>
          <b><?php echo $text_shipping_address; ?></b><br>
          <?php echo $shipping_address; ?><br>
          <?php } ?></td>
        <td><b><?php echo $text_payment_address; ?></b><br>
          <?php echo $payment_address; ?><br></td>
      </tr>
    </table>
  </div>
  <div class="b">
    <table class="c">
      <tr>
        <th class="left"><?php echo $text_product; ?></th>
        <th class="left"><?php echo $text_model_number; ?></th>
        <th class="right"><?php echo $text_quantity; ?></th>
        <th class="right"><?php echo $text_price; ?></th>
        <th class="right"><?php echo $text_total; ?></th>
      </tr>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="left"><?php echo $product['name']; ?>
          <?php foreach ($product['option'] as $option) { ?>
          <br>
          &nbsp;<small> - <?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
          <?php } ?>
        </td>
        <td class="left"><?php echo $product['model_number']; ?></td>
        <td class="right"><?php echo $product['quantity']; ?></td>
        <td class="right"><?php if (!$product['discount']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price_old"><?php echo $product['price']; ?></span><br>
          <span class="price_new"><?php echo $product['discount']; ?></span>
          <?php } ?></td>
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
  <div class="d"><b><?php echo $text_order_history; ?></b></div>
  <div class="e">
    <table class="g">
      <tr>
        <th class="left"><?php echo $column_date_added; ?></th>
        <th class="left"><?php echo $column_status; ?></th>
        <th class="left"><?php echo $column_comment; ?></th>
      </tr>
      <?php foreach ($historys as $history) { ?>
      <tr>
        <td><?php echo $history['date_added']; ?></td>
        <td><?php echo $history['status']; ?></td>
        <td><?php echo $history['comment']; ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
</div></div>
<div class="contentBodyBottom"></div>
<div class="buttons">
  <table>
    <tr>
      <td align="right"><input type="submit" value="<?php echo $button_continue; ?>" onclick="location='<?php echo $continue; ?>'"></td>
    </tr>
  </table>
</div>

<html dir="ltr" lang="en">
<head>
<title><?php echo $store; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
* {
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
body, td, th, input, textarea, select {
	font-size: 11px;
	color: #000000;
}
#checkout {
	width: 581px;
}
#checkout .a, #checkout .b, #checkout .e {
	border: 1px solid #EEEEEE;
	margin-bottom: 10px;
	padding: 5px;
}
#checkout .a td {
	width: 33.3%;
	vertical-align: top;
}
#checkout .d {
	padding-bottom: 3px;
}
#checkout .a table, #checkout .c {
	width: 100%;
}
#checkout .c td {
	vertical-align: top;
}
#checkout .f {
	text-align: right;
}
#checkout .left {
	text-align: left;
}
#checkout .right {
	text-align: right;
}
#checkout .center {
	text-align: center;
}
.price_old {
	text-decoration: line-through;
}
.price_new {
	color: #FF0000;
}
</style>
</head>
<body>
<div id="checkout">
  <p><?php echo $email_greeting; ?></p>
  <p><?php echo $email_thanks; ?></p>
  <?php echo $email_order; ?><br>
  <?php echo $email_date; ?><br>
  <?php echo $email_invoice; ?><br>
  <br>
  <br>
  <div class="a">
    <table>
      <tr>
        <td><b><?php echo $email_email; ?></b><br>
          <?php echo $email; ?><br>
          <br>
          <b><?php echo $email_telephone; ?></b><br>
          <?php echo $telephone; ?><br>
          <br>
          <?php if ($fax) { ?>
          <b><?php echo $email_fax; ?></b><br>
          <?php echo $fax; ?><br>
          <br>
          <?php } ?>
          <?php if ($shipping_method) { ?>
          <b><?php echo $email_shipping_method; ?></b><br>
          <?php echo $shipping_method; ?><br>
          <br>
          <?php } ?>
          <b><?php echo $email_payment_method; ?></b><br>
          <?php echo $payment_method; ?></td>
        <td><?php if ($shipping_address) { ?>
          <b><?php echo $email_shipping_address; ?></b><br>
          <?php echo $shipping_address; ?>
          <?php } ?></td>
        <td><b><?php echo $email_payment_address; ?></b><br>
          <?php echo $payment_address; ?></td>
      </tr>
    </table>
  </div>
  <div class="b">
    <table class="c">
      <tr>
        <th class="left"><?php echo $email_product; ?></th>
        <th class="left"><?php echo $email_model_number; ?></th>
        <th class="right"><?php echo $email_quantity; ?></th>
        <th class="right"><?php echo $email_price; ?></th>
        <th class="right"><?php echo $email_total; ?></th>
      </tr>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="left"><?php echo $product['name']; ?>
          <?php foreach ($product['option'] as $option) { ?>
          <br>
          &nbsp;<small> - <?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
          <?php } ?></td>
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
  <?php if ($comment) { ?>
  <div class="d"><b><?php echo $email_comment; ?></b></div>
  <div class="e"><?php echo $comment; ?></div>
  <?php } ?>
  <p><?php echo $email_thanks_again; ?></p>
</div>
</body>
</html>

<html dir="ltr" lang="en">
<head>
<title><?php echo $store; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
* {
	font-family:  Arial, Verdana, Helvetica, sans-serif;
}
body, td, th, input, textarea, select {
	font-size: 11px;
	color: #000000;
}
#checkout {
	width: 800px;
}
#checkout .a, #checkout .b, #checkout .e, #checkout .g {
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
#checkout .a table, #checkout .c, #checkout .cur {
	width: 100%;
}
#checkout .b table {
	border-collapse: collapse;
}
#checkout .c td {
	vertical-align: top;
	padding: 5px 0px;
}
#checkout .c tr:first-child {
	border-bottom: 1px solid #BBBBBB;
	height: 35px;
}
#checkout .ptotals, #checkout .stotals, #checkout .ftotals, #checkout .ctotals{
	border-top: 1px solid #BBBBBB;
	height: 35px;
}
#checkout .ctotals .right{
	border-bottom: 1px solid #BBBBBB;
}
#checkout .f {
	text-align: right;
}
#checkout .f table {
	width: 100%;
}
#checkout .h {
	padding-left: 5px;
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
#checkout .vendor{
	font-size: smaller;
	color: #0099FF;
}
#checkout .i{
	padding-left: 30px;
}
.price_old {
	text-decoration: line-through;
}
.price_new {
	color: #FF0000;
}
.tax{
	font-weight: bold;
	color: #CC0000;
}
.taxE {
	font-weight: bold;
}
</style>
</head>
<body>
<div id="checkout">
  <div class="i">
    <p><b><?php echo $store;?><br><?php echo $store_address; ?></b></p>
  </div>	
  <p><?php echo $email_greeting; ?></p>
  <p><?php echo $email_thanks; ?></p>
  <?php echo $email_order; ?><br>
  <?php echo $email_date; ?><br>
  <?php echo $email_invoice_number; ?><br>
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
          <?php echo $fax; ?>
          <?php } ?>
	</td>
        <td><?php if ($shipping_address) { ?>
          <b><?php echo $email_shipping_address; ?></b><br>
          <?php echo $shipping_address; ?><br>
	  <br>
          <?php } ?>
	  <?php if ($shipping_method) { ?>
          <b><?php echo $email_shipping_method; ?></b><br>
          <?php echo $shipping_method; ?><br>
          <br>
          <?php } ?>
	</td>
        <td><b><?php echo $email_payment_address; ?></b><br>
          <?php echo $payment_address; ?><br>
	  <br>
	<b><?php echo $email_payment_method; ?></b><br>
          <?php echo $payment_method; ?>
	</td>
      </tr>
    </table>
  </div>
  <div class="h">
    <table class="cur">
      <tr>
		<th class="left" width="80px"><?php echo $text_currency;?></th>
		<td class="left"><?php echo $currency['code'] . ' - ' . $currency['title'];?></td>
	  </tr>
    </table> 
  </div>  
  <div class="b">
    <table class="c">
      <tr>
        <th class="left"><?php echo $email_product; ?></th>
        <th class="right" width="12"><?php echo $email_quantity; ?></th>
        <th class="right"><?php echo $email_price; ?></th>
		<th class="right"><?php echo $email_specialprice; ?></th>
		<th class="right"><?php echo $email_extended; ?></th>
		<?php if($coupon_sort_order < $discount_sort_order){ ?>
		  <th class="right"><?php echo $email_coupon_value; ?></th>
		  <th class="right"><?php echo $email_discount_value; ?></th>
		<?php } else { ?>
		  <th class="right"><?php echo $email_discount_value; ?></th>
		  <th class="right"><?php echo $email_coupon_value; ?></th>
		<?php }?>
		<th class="right"><?php echo $email_net; ?></th>
		<th class="right"><?php echo $email_tax_rate; ?></th>
		<th class="right"><?php echo $email_tax_amount; ?></th>
		<th class="right"><?php echo $email_shipping; ?></th>
        <th class="right"><?php echo $email_total; ?></th>
      </tr>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="left"><?php echo $product['name']; ?>
          <?php foreach ($product['option'] as $option) { ?>
          <br>
          <small> - <?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
          <?php } ?>
          <br>
		<?php if (isset($product['vendor_name'])) { ?>
			<span class="vendor"><?php echo $email_soldby; ?><br><?php echo $product['vendor_name']; ?></span>
		<?php } ?>
	 </td>
        <td class="right"><?php echo $product['quantity']; ?></td>
		
        <td class="right"><?php if (!$product['discount']) { ?>
          <?php echo ($tax_included ? '<span class="tax">*</span>' : '') . $product['price']; ?>
          <?php } else { ?>
          <span class="price_old"><?php echo ($tax_included ? '<span class="tax">*</span>' : '') . $product['price']; ?></span><br>
          <span class="price_new"><?php echo ($tax_included ? '<span class="tax">*</span>' : '') . $product['discount']; ?></span>
          <?php } ?></td>
		<td class="right"><span class="price_new"><?php if ($product['special_price'] > "$0.00"){echo ($tax_included ? '<span class="tax">*</span>' : '') . $product['special_price'];} ?></span></td>
		<td class="right"><?php echo ($tax_included ? '<span class="tax">*</span>' : '') . $product['total']; ?></td>
		<?php if($coupon_sort_order < $discount_sort_order){ ?>
		    <td class="right"><?php echo ($tax_included && $product['coupon'] ? '<span class="tax">*</span>' : '') . $product['coupon'];?></td>
		    <td class="right"><?php echo ($tax_included && $product['general_discount'] ? '<span class="tax">*</span>' : '') . $product['general_discount'];?></td>
		  <?php } else { ?>
		    <td class="right"><?php echo ($tax_included && $product['general_discount'] ? '<span class="tax">*</span>' : '') . $product['general_discount'];?></td>
		    <td class="right"><?php echo ($tax_included && $product['coupon'] ? '<span class="tax">*</span>' : '') . $product['coupon'];?></td>
		  <?php }?>
		<td class="right"><?php echo ($tax_included ? '<span class="tax">*</span>' : '') . $product['net'];?></td>
		<td class="right"><?php echo $product['tax'] . '%';?></td>
		<td class="right"><?php echo $product['product_tax'];?></td>
		<td class="right">
		  <?php if($product['download']){
		    echo $email_download;
		  } else if($product['shipping']) { 
		    echo $email_ship;
		  }	else { 
		    echo $email_noship;
		  } ?>
		</td>
        <td class="right"><?php echo '<span class="tax">*</span>' . $product['total_discounted']; ?></td>
      </tr>
      <?php } ?>
	  <tr class="ptotals">
	    <th class="left"><?php echo $text_product_totals;?></th>
	    <td colspan="3"></td>
	    <td class="right"><?php echo ($tax_included ? '<span class="tax">* </span>' : '') . $extended_total;?></td>
	    <?php if($coupon_sort_order < $discount_sort_order){ ?>
	      <td class="right"><?php echo ($tax_included && $coupon_total ? '<span class="tax">* </span>' : '') . $coupon_total;?></td>
		  <td class="right"><?php echo ($tax_included && $discount_total ? '<span class="tax">* </span>' : '') . $discount_total;?></td>
		<?php } else {?>
		  <td class="right"><?php echo ($tax_included && $discount_total ? '<span class="tax">* </span>' : '') . $discount_total;?></td>
		  <td class="right"><?php echo ($tax_included && $coupon_total ? '<span class="tax">* </span>' : '') . $coupon_total;?></td>
		<?php }?>
	    <td class="right"><?php echo ($tax_included ? '<span class="tax">* </span>' : '') . $net_total;?></td>
		<td></td>
		<td class="right"><?php echo $tax_total;?></td>
		<td></td>
		<td class="right"><?php echo '<span class="tax">*</span>' . $totals_total;?></td>
	  </tr>
	  <?php if(isset($shipping_total)){?>
	    <tr class="stotals">
	      <th class="left"><?php echo $text_shipping_cost;?></th>
	      <td colspan="6"></td>
		  <td class="right"><?php echo ($tax_included ? '<span class="tax">* </span>' : '') . $shipping_net;?></td>
		  <td class="right"><?php echo $shipping_tax_rate;?></td>
		  <td class="right"><?php echo $shipping_tax;?></td>
		  <td></td>
		  <td class="right"><?php echo '<span class="tax">*</span>' . $shipping_total;?></td>
	    </tr>
	    <?php if(isset($freeshipping_total)){?>
		  <tr class="ftotals">
	        <th class="left"><?php echo $text_free_shipping;?></th>
	        <td colspan="6"></td>
		    <td class="right"><?php echo ($tax_included ? '<span class="tax">* </span>' : '') . $freeshipping_net;?></td>
		    <td class="right"><?php echo $shipping_tax_rate;?></td>
		    <td class="right"><?php echo $freeshipping_tax;?></td>
		    <td></td>
		    <td class="right"><?php echo '<span class="tax">*</span>' . $freeshipping_total;?></td>
	      </tr>
		<?php }?>
	  <?php }?>
	  <tr class="ctotals">
	    <th class="left"><?php echo $text_cart_totals;?></th>
		<td colspan="3"></td>
		<td class="right"><?php echo ($tax_included ? '<span class="tax">* </span>' : '') . $extended_total;?></td>
	    <?php if($coupon_sort_order < $discount_sort_order){ ?>
	      <td class="right"><?php echo ($tax_included && $coupon_total ? '<span class="tax">* </span>' : '') . $coupon_total;?></td>
		  <td class="right"><?php echo ($tax_included && $discount_total ? '<span class="tax">* </span>' : '') . $discount_total;?></td>
		<?php } else {?>
		  <td class="right"><?php echo ($tax_included && $discount_total ? '<span class="tax">* </span>' : '') . $discount_total;?></td>
		  <td class="right"><?php echo ($tax_included && $coupon_total ? '<span class="tax">* </span>' : '') . $coupon_total;?></td>
		<?php } ?>
		<td class="right"><?php echo ($tax_included ? '<span class="tax">* </span>' : '') . $cart_net_total;?></td>
		<td class="right"></td>
		<td class="right"><?php echo $cart_tax_total;?></td>
		<td class="right"></td>
		<td class="right"><?php echo '<span class="tax">*</span>' . $cart_totals_total;?></td>
	  </tr>
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
    <div class="left">
      <table><tr><td>
		<?php if($tax_included){?>
			<?php echo '<div class="taxE"><span class="tax">* </span>' . $text_tax.'<div>';?>
		<?php } ?>
      </td></tr></table>
    </div>
  </div>
<?php if ($email_banktr_message) { ?>
<div class="g">
    <?php echo $email_banktr_message; ?><br><br>
    <b><?php echo $email_banktr_charge; ?></b><br><br>
    <b><?php echo $email_banktr_owner_name; ?></b><?php echo $banktr_owner; ?><br>
    	<?php if ($same_country) { ?>
	    <b><?php echo $email_banktr_ban; ?></b><?php echo $banktr_ban; ?><br>
	<?php } else { ?>
	    <b><?php echo $email_banktr_iban; ?></b><?php echo $banktr_iban; ?><br>
	    <b><?php echo $email_banktr_swift; ?></b><?php echo $banktr_swift; ?><br>
	<?php } ?>
    <b><?php echo $email_banktr_bank_name; ?></b><br><?php echo $banktr_bank_name; ?><br><?php echo $banktr_address; ?><br>
</div>
<?php } ?>
  <?php if ($comment) { ?>
  <div class="d"><b><?php echo $email_comment; ?></b></div>
  <div class="e"><?php echo $comment; ?></div>
  <?php } ?>
  <p><?php echo $email_thanks_again; ?></p>
</div>
</body>
</html>

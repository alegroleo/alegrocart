<?php 
  $head_def->setcss($this->style . "/css/account_invoice.css");
  $head_def->set_javascript("ajax/jquery.js");
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
	  <b><?php echo $text_invoice_number; ?></b><br>
          <?php echo $invoice_number; ?><br>
          <br>
          <b><?php echo $text_email; ?></b><br>
          <?php echo $email; ?><br>
          <br>
          <b><?php echo $text_telephone; ?></b><br>
          <?php echo $telephone; ?><br>
          <br>
          <?php if ($fax) { ?>
          <b><?php echo $text_fax; ?></b><br>
          <?php echo $fax; ?>
          <?php } ?>
        </td>
        <td><?php if ($shipping_address) { ?>
          <b><?php echo $text_shipping_address; ?></b><br>
          <?php echo $shipping_address; ?><br>
          <br>
          <?php } ?>
          <?php if ($shipping_method) { ?>
          <b><?php echo $text_shipping_method; ?></b><br>
          <?php echo $shipping_method; ?><br>
          <br>
          <?php } ?>
	</td>
        <td><b><?php echo $text_payment_address; ?></b><br>
          <?php echo $payment_address; ?><br>
	  <br>
          <b><?php echo $text_payment_method; ?></b><br>
          <?php echo $payment_method; ?>
	</td>
      </tr>
    </table>
  </div>
  <div style="padding-left: 5px;">
    <table class="c">
      <tr>
		<th class="left" width="80px"><?php echo $text_currency;?></th>
		<td class="left"><?php echo $currency;?></td>
      </tr>
    </table>
  </div>
  <div class="b">
    <table class="c">
      <tr>
        <th class="left"><?php echo $text_product; ?></th>

        <th class="center"><?php echo $text_quantity; ?></th>
        <th class="right"><?php echo $text_price; ?></th>
        <th class="right"><?php echo $text_special; ?></th>
		<?php if($columns == 2){?>
		  <th class="right"><?php echo $text_extended; ?></th>
		  <?php if($coupon_sort_order < $discount_sort_order){ ?>
		    <th class="right"><?php echo $text_coupon_value; ?></th>
		    <th class="right"><?php echo $text_discount_value; ?></th>
		  <?php } else { ?>
		    <th class="right"><?php echo $text_discount_value; ?></th>
		    <th class="right"><?php echo $text_coupon_value; ?></th>
		  <?php }?>
		  <th class="right"><?php echo $text_net; ?></th>
		  <th class="right"><?php echo $text_tax_rate; ?></th>
		  <th class="right"><?php echo $text_tax_amount; ?></th>
		<?php }?>
		<th class="right"><?php echo $text_shipping; ?></th>
        <th class="right"><?php echo $text_total; ?></th>
      </tr>
	  <tr><td colspan="12"><hr></td></tr>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="left"><?php echo $product['name']; ?></a>
          <?php foreach ($product['option'] as $option) { ?>
          <br>
          &nbsp;<small> - <?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
          <?php } ?>
        </td>

        <td class="center"><?php echo $product['quantity']; ?></td>
        <td class="right"><?php if (!$product['discount']) { ?>
          <?php echo ($taxed ? '<span class="tax">*</span>' : '') . $product['price']; ?>
          <?php } else { ?>
          <span class="invoice_price_old "><?php echo ($taxed ? '<span class="tax">*</span>' : '') . $product['price']; ?></span><br>
          <span class="invoice_price_new "><?php echo ($taxed ? '<span class="tax">*</span>' : '') . $product['discount']; ?></span>
          <?php } ?>
        </td>
		<td class="right"><span class="invoice_price_new ">
		  <?php if ($product['special_price'] > "$0.00"){echo ($taxed ? '<span class="tax">*</span>' : '') . $product['special_price'];} ?>
		</span></td>
		<?php if($columns == 2){?>
		  <td class="right"><?php echo ($taxed ? '<span class="tax">*</span>' : '') . $product['total'];?></td>
		  <?php if($coupon_sort_order < $discount_sort_order){ ?>
		    <td class="right"><?php echo ($taxed && $product['coupon'] ? '<span class="tax">*</span>' : '') . $product['coupon'];?></td>
		    <td class="right"><?php echo ($taxed && $product['general_discount'] ? '<span class="tax">*</span>' : '') . $product['general_discount'] ;?></td>
		  <?php } else { ?>
		    <td class="right"><?php echo ($taxed && $product['general_discount'] ? '<span class="tax">*</span>' : '') . $product['general_discount'] ;?></td>
		    <td class="right"><?php echo ($taxed && $product['coupon'] ? '<span class="tax">*</span>' : '') . $product['coupon'];?></td>
		  <?php }?>
		  <td class="right"><?php echo ($taxed ? '<span class="tax">*</span>' : '') . $product['net'];?></td>
		  <td class="right"><?php echo $product['tax'] . '%';?></td>
		  <td class="right"><?php echo $product['product_tax'];?></td>
		  <td class="right">
		  <?php if ($product['download']) {?><img src="catalog/styles/<?php echo $this->style?>/image/downloadable.png" alt="<?php echo $text_downloadable; ?>" title="<?php echo $text_downloadable; ?>" >
		  <?php }else if ($product['shipping']) { ?><img src="catalog/styles/<?php echo $this->style?>/image/shippable.png" alt="<?php echo $text_shippable; ?>" title="<?php echo $text_shippable; ?>" ><?php } else { ?><img src="catalog/styles/<?php echo $this->style?>/image/non_shippable.png" alt="<?php echo $text_non_shippable; ?>" title="<?php echo $text_non_shippable; ?>"><?php  } ?>
		</td>
		  <td class="right"><?php echo '<span class="tax">*</span>' . $product['total_discounted']; ?></td>
          <?php } else { ?>
		<td class="right">
		  <?php if ($product['download']) {?><img src="catalog/styles/<?php echo $this->style?>/image/downloadable.png" alt="<?php echo $text_downloadable; ?>" title="<?php echo $text_downloadable; ?>" >
		  <?php }else if ($product['shipping']) { ?><img src="catalog/styles/<?php echo $this->style?>/image/shippable.png" alt="<?php echo $text_shippable; ?>" title="<?php echo $text_shippable; ?>" ><?php } else { ?><img src="catalog/styles/<?php echo $this->style?>/image/non_shippable.png" alt="<?php echo $text_non_shippable; ?>" title="<?php echo $text_non_shippable; ?>"><?php  } ?>
		</td>
        <td class="right"><?php echo ($taxed ? '<span class="tax">* </span>' : '') . $product['total']; ?></td>
		<?php }?>
      </tr>
      <?php } ?>
	  <?php if($columns == 2){?>
	  <tr><td colspan="12"><hr></td></tr>
	  <tr>
	    <th class="left"><?php echo $text_product_totals;?></th>
	    <td colspan="3"></td>
	    <td class="right"><?php echo ($taxed ? '<span class="tax">* </span>' : '') . $extended_total;?></td>
		<?php if($coupon_sort_order < $discount_sort_order){ ?>
	      <td class="right"><?php echo ($taxed && $coupon_total ? '<span class="tax">* </span>' : '') . $coupon_total;?></td>
		  <td class="right"><?php echo ($taxed && $discount_total ? '<span class="tax">* </span>' : '') . $discount_total;?></td>
		<?php } else {?>
		  <td class="right"><?php echo ($taxed && $discount_total ? '<span class="tax">* </span>' : '') . $discount_total;?></td>
		  <td class="right"><?php echo ($taxed && $coupon_total ? '<span class="tax">* </span>' : '') . $coupon_total;?></td>
		<?php }?>
	    <td class="right"><?php echo ($taxed ? '<span class="tax">* </span>' : '') . $net_total;?></td>
		<td></td>
		<td class="right"><?php echo $tax_total;?></td>
		<td></td>
		<td class="right"><?php echo '<span class="tax">*</span>' . $totals_total;?></td>
	  </tr>
	  <tr><td colspan="12"><hr></td></tr>
	  <?php if(isset($shipping_total)){?>
	    <tr>
	      <th class="left"><?php echo $text_shipping_cost;?></th>
	      <td colspan="6"></td>
		  <td class="right"><?php echo $shipping_net;?></td>
		  <td class="right"><?php echo $shipping_tax_rate;?></td>
		  <td class="right"><?php echo $shipping_tax;?></td>
		  <td></td>
		  <td class="right"><?php echo '<span class="tax">*</span>' . $shipping_total;?></td>
	    </tr>
	    <?php if(isset($freeshipping_total)){?>
		  <tr>
	        <th class="left"><?php echo $text_free_shipping;?></th>
	        <td colspan="6"></td>
		    <td class="right"><?php echo $freeshipping_net;?></td>
		    <td class="right"><?php echo $shipping_tax_rate;?></td>
		    <td class="right"><?php echo $freeshipping_tax;?></td>
		    <td></td>
		    <td class="right"><?php echo '<span class="tax">*</span>' . $freeshipping_total;?></td>
	      </tr>
		<?php }?>
		<tr><td colspan="12"><hr></td></tr>
	  <?php }?>
	  <tr>
	    <th class="left"><?php echo $text_cart_totals;?></th>
		<td colspan="3"></td>
		<td class="right"><?php echo ($taxed ? '<span class="tax">* </span>' : '') . $extended_total;?></td>
		<?php if($coupon_sort_order < $discount_sort_order){ ?>
	      <td class="right"><?php echo ($taxed && $coupon_total ? '<span class="tax">* </span>' : '') . $coupon_total;?></td>
		  <td class="right"><?php echo ($taxed && $discount_total ? '<span class="tax">* </span>' : '') . $discount_total;?></td>
		<?php } else {?>
		  <td class="right"><?php echo ($taxed && $discount_total ? '<span class="tax">* </span>' : '') . $discount_total;?></td>
		  <td class="right"><?php echo ($taxed && $coupon_total ? '<span class="tax">* </span>' : '') . $coupon_total;?></td>
		<?php } ?>
		<td class="right"><?php echo ($taxed ? '<span class="tax">* </span>' : '') . $cart_net_total;?></td>
		<td></td>
		<td class="right"><?php echo $cart_tax_total;?></td>
		<td></td>
		<td class="right"><?php echo '<span class="tax">*</span>' . $cart_totals_total;?></td>
	  </tr>
	  <tr><td colspan="6"></td><td colspan="6"><hr></td></tr>
	  <?php } ?>
    </table>
    <br>
    <div class="f">
      <table>
        <?php foreach ($totals as $total) { ?>
        <tr>
          <td class="right" colspan="4"><?php echo ($taxed ? '<span class="tax">*</span>' : '') . $total['title']; ?></td>
          <td class="right"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
    <div class="left">   
      <table><tr><td><?php echo '<span class="tax">* </span>' . $text_tax;?></td></tr></table>
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
<?php if(!$order_print){?>
<div class="buttons">
  <table>
    <tr>
      <td align="right"><input type="submit" value="<?php echo $button_continue; ?>" onclick="location='<?php echo $continue; ?>'"></td>
    </tr>
  </table>
</div>
<?php }?>

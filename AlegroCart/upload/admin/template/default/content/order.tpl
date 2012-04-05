<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/<?php echo $this->directory?>/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
  <?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="window.print();"><img src="template/<?php echo $this->directory?>/image/print_enabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
</div>
<link rel="stylesheet" type="text/css" href="template/<?php echo $this->directory?>/css/order.css">
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
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
		  <?php if ($product['download']) {?><img src="template/<?php echo $this->directory?>/image/downloadable.png" alt="<?php echo $text_downloadable; ?>" title="<?php echo $text_downloadable; ?>" >
		  <?php }else if ($product['shipping']) { ?><img src="template/<?php echo $this->directory?>/image/shippable.png" alt="<?php echo $text_shippable; ?>" title="<?php echo $text_shippable; ?>" ><?php } else { ?><img src="template/<?php echo $this->directory?>/image/non_shippable.png" alt="<?php echo $text_non_shippable; ?>" title="<?php echo $text_non_shippable; ?>"><?php  } ?>
		</td>
		  <td class="right"><?php echo '<span class="tax">*</span>' . $product['total_discounted']; ?></td>
	  </tr>
	  <?php if($product['barcode_url']){?>
	    <tr>
		  <td >
		    <img src="<?php echo $product['barcode_url']; ?>" title="<?php echo $product['barcode']; ?>" alt="<?php echo $product['barcode']; ?>">
		  </td>
	    </tr>
	  <?php } ?>
      <?php } ?>
	  <tr><td colspan="12"><hr></td></tr>
	  <tr>
	    <th class="left"><?php echo $text_product_totals;?></th>
	    <td colspan="3"></td>
	    <td class="right"><?php echo ($taxed ? '<span class="tax">* </span>' : '') . $extended_total;?></td>
		<?php if($coupon_sort_order < $discount_sort_order){ ?>
	      <td class="right"><?php echo ($taxed && $coupon_total ? '<span class="tax">* </span>' : '') . $coupon_total;?></td>
		  <td class="right"><?php echo ($taxed && $discount_total ? '<span class="tax">* </span>' : '') . $discount_total;?></td>
		<?php } else {?>
		  <td class="right"><?php echo ($taxed && $discount_total? '<span class="tax">* </span>' : '') . $discount_total;?></td>
		  <td class="right"><?php echo ($taxed && $coupon_total ? '<span class="tax">* </span>' : '') . $coupon_total;?></td>
		<?php }?>
	    <td class="right"><?php echo ($taxed ? '<span class="tax">* </span>' : '') . $net_total;?></td>
		<td></td>
		<td class="right"><?php echo $tax_total;?></td>
		<td></td>
		<td class="right"><?php echo '<span class="tax">*</span>' . $totals_total;?></td>
	  </tr>
	  <tr><td colspan="12"><hr></td></tr>
	  <?php if(!empty($shipping_net)){?>
	    <tr>
	      <th class="left"><?php echo $text_shipping_cost;?></th>
	      <td colspan="6"></td>
		  <td class="right"><?php echo $shipping_net;?></td>
		  <td class="right"><?php echo $shipping_tax_rate;?></td>
		  <td class="right"><?php echo $shipping_tax;?></td>
		  <td></td>
		  <td class="right"><?php echo $shipping_total ? '<span class="tax">*</span>' . $shipping_total : '';?></td>
	    </tr>
	    <?php if(!empty($freeshipping_net)){?>
		  <tr>
	        <th class="left"><?php echo $text_free_shipping;?></th>
	        <td colspan="6"></td>
		    <td class="right"><?php echo $freeshipping_net;?></td>
		    <td class="right"><?php echo $shipping_tax_rate;?></td>
		    <td class="right"><?php echo $freeshipping_tax;?></td>
		    <td></td>
		    <td class="right"><?php echo $freeshipping_total ? '<span class="tax">*</span>' . $freeshipping_total : '';?></td>
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
          <th class="right"><?php echo $column_comment; ?></th>
          <th class="center"><?php echo $column_notify; ?></th>
        </tr>
        <?php foreach ($historys as $history) { ?>
        <tr>
          <td class="left"><?php echo $history['date_added']; ?></td>
          <td class="left"><?php echo $history['status']; ?></td>
          <td class="right"><?php echo $history['comment']; ?></td>
          <td class="center"><?php if ($history['notify']) { ?>
            <img src="template/<?php echo $this->directory?>/image/enabled.png">
            <?php } else { ?>
            <img src="template/<?php echo $this->directory?>/image/disabled.png">
            <?php } ?></td>
        </tr>
        <?php } ?>
		<?php if(isset($modified)){?>
		  <tr><td colspan="4" class="left"><b><?php echo $modified;?></b></td></tr>  
		<?php } ?>
      </table>
    </div>
    <?php if ($downloads) { ?>
    <div class="d"><b><?php echo $text_order_download; ?></b></div>
    <div class="e">
      <table class="h">
        <tr>
          <th class="left"><?php echo $column_download; ?></th>
          <th class="left"><?php echo $column_filename; ?></th>
          <th class="right"><?php echo $column_remaining; ?></th>
        </tr>
        <?php foreach ($downloads as $download) { ?>
        <tr>
          <td class="left"><?php echo $download['name']; ?></td>
          <td class="left"><?php echo $download['filename']; ?></td>
          <td class="right"><?php echo $download['remaining']; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
    <?php } ?>
    <div class="d"><b><?php echo $text_order_update; ?></b></div>
    <div class="e">
      <table>
        <tr>
          <td width="185"><?php echo $entry_status; ?></td>
          <td><select name="order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
			  <?php if ($order_status['order_status_id'] == '99'){
			    $order_status['name'] .= '(A-NET)';
			  }?>
              <?php if ($order_status['order_status_id'] == $order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_comment; ?></td>
          <td><textarea name="comment" cols="80" rows="5"></textarea></td>
        </tr>
        <tr>
          <td><?php echo $entry_notify; ?></td>
          <td valign="top"><input type="checkbox" name="notify" value="1" checked></td>
        </tr>
      </table>
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
</form>
<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_css("template/".$this->directory."/css/order_create.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" width=32 height=32 alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" width=32 height=32 alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" width=32 height=32 alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" width=32 height=32 alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/save_disabled.png" width=32 height=32 alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" width=32 height=32 alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" width=32 height=32 alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
<?php if (@$last) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $last; ?>'"><img src="template/<?php echo $this->directory?>/image/last_enabled.png" width=32 height=32 alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/last_disabled.png" width=32 height=32 alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } ?>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?>
 <em><?php echo $order_id; ?></em>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" width=31 height=30 alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>

<div class="tab" id="tab">
  <div class="tabs">
	<a><div class="tab_text"><?php echo $tab_create; ?></div></a>
	<?php if($order_id){?>
	<a><div class="tab_text"><?php echo $tab_original; ?></div></a>
	<?php } ?>
  </div>
  <div class="pages">
	<div class="page">
	  <div class="pad">  
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<input name="order_status" value="<?php echo $order_cancelled;?>" type="hidden">
		<?php if($order_cancelled){?>
		  <input name="order_reference" value="<?php echo $reference;?>" type="hidden">
		<?php }?>
		  <div id="create">
		    <div class="a">
			  <table>
			    <tr>
				  <td id="order_information"><b><?php echo $text_order; ?></b><br>
				    <input name="new_reference" value="<?php echo $new_reference;?>" readonly="readonly"><br>
				    <br>
				    <b><?php echo $text_invoice_number; ?></b><br>
				    <input name="new_invoice_number" value="<?php echo $new_invoice_number;?>" readonly="readonly"><br>
				    <br>
					<span id="email">
				    <b><?php echo $text_email; ?></b><br>
					<input name="email" size="30" value="<?php echo $email;?>" readonly="readonly"><br>
				    </span>
				  </td>
				  <td id="shipping_address"><?php if (isset($new_shipping_address['shipping_firstname'])) { ?>
				    <b><?php echo $text_shipping_address; ?></b><br>
					
					<input name="shipping_firstname" value="<?php echo $new_shipping_address['shipping_firstname'];?>" readonly="readonly">
					<input name="shipping_lastname" value="<?php echo $new_shipping_address['shipping_lastname'];?>" readonly="readonly"><br>
					<input name="shipping_company" value="<?php echo $new_shipping_address['shipping_company'];?>" readonly="readonly"><br>
					<input name="shipping_address_1" value="<?php echo $new_shipping_address['shipping_address_1'];?>" readonly="readonly"><br>
					<input name="shipping_address_2" value="<?php echo $new_shipping_address['shipping_address_2'];?>" readonly="readonly"><br>
					<input name="shipping_city" value="<?php echo $new_shipping_address['shipping_city'];?>" readonly="readonly">
					<input name="shipping_zone" value="<?php echo $new_shipping_address['shipping_zone'];?>" readonly="readonly"><br>
					<input name="shipping_country" value="<?php echo $new_shipping_address['shipping_country'];?>" readonly="readonly">
					<input name="shipping_postcode" value="<?php echo $new_shipping_address['shipping_postcode'];?>" readonly="readonly"><br>
				    <?php }?>
				  </td>
				  <td id="payment_address">
				  <?php if(isset($new_payment_address['payment_firstname'])){?>
				  
				  <b><?php echo $text_payment_address; ?></b><br>
				    <input name="payment_firstname" value="<?php echo $new_payment_address['payment_firstname'];?>" readonly="readonly">
					<input name="payment_lastname" value="<?php echo $new_payment_address['payment_lastname'];?>" readonly="readonly"><br>
					<input name="payment_company" value="<?php echo $new_payment_address['payment_company'];?>" readonly="readonly"><br>
					<input name="payment_address_1" value="<?php echo $new_payment_address['payment_address_1'];?>" readonly="readonly"><br>
					<input name="payment_address_2" value="<?php echo $new_payment_address['payment_address_2'];?>" readonly="readonly"><br>
					<input name="payment_city" value="<?php echo $new_payment_address['payment_city'];?>" readonly="readonly">
					<input name="payment_zone" value="<?php echo $new_payment_address['payment_zone'];?>" readonly="readonly"><br>
					<input name="payment_country" value="<?php echo $new_payment_address['payment_country'];?>" readonly="readonly">
					<input name="payment_postcode" value="<?php echo $new_payment_address['payment_postcode'];?>" readonly="readonly"><br>
					<input name="customer_id" id="customer_id" value="<?php echo $customer_id;?>" type="hidden">
					<?php //if($payment_address){echo $payment_address;} ?><br>
				    <br>
				    <?php } else {?>
						<select name="customer_billing" id="customer_billing" onchange="$('#payment_address').load('index.php?controller=order_edit&action=get_customer&customer_id='+this.value),get_customer_info(this.value);">
							<option value="0"><?php echo $entry_customer;?></option>
							<?php foreach($customers as $customer){?>
								<option value="<?php echo $customer['customer_id'];?>"><?php echo $customer['firstname'] . ' ' . $customer['lastname'] .' - '. $customer['email'];?></option>
							<?php }?>
						</select>
					<?php }?>
				  </td>
			    </tr>
				<tr>
				  <td id="phonefax">
				      <b><?php echo $text_telephone; ?></b><br>
				      <input id="telephone" name="telephone" value="<?php echo $telephone;?>" readonly="readonly"><br>
				      <b><?php echo $text_fax; ?></b><br>
				      <input name="fax" value="<?php echo $fax;?>" readonly="readonly">
				  </td>
				  <td>
				      <b><?php echo $text_shipping_method; ?></b><br>
					<?php if($order_cancelled){?>
					  <input id="shipping_method" name="shipping_method" value="<?php echo $shipping_method; ?>" readonly="readonly">
					<?php } else {?>
					  <select id="shipping_method" name="shipping_method" onchange="open_body()">
						<option value=""><?php echo $text_select_shipping;?></option>
					    <?php foreach($shipping_methods as $shipping_meth){?>
						  <option value="<?php echo $shipping_meth['description'];?>"<?php if($shipping_meth['description'] == $shipping_method){ echo ' selected';}?>><?php echo $shipping_meth['description'];?></option>
						<?php }?>
					  </select>
					<?php }?>
				      <br>
				  </td>
				  <td>
					<b><?php echo $text_payment_method; ?></b><br>
					<?php if($order_cancelled){?>
					  <input id="payment_method" name="payment_method" value="<?php echo $payment_method; ?> " readonly="readonly">
					<?php } else {?>
					<select id="payment_method" name="payment_method" onchange="open_body()">
						<option value=""><?php echo $text_select_payment;?></option>
					    <?php foreach($payment_methods as $payment_meth){?>
						  <option value="<?php echo $payment_meth['description'];?>"<?php if($payment_meth['description'] == $payment_method){ echo ' selected';}?>><?php echo $payment_meth['description'];?></option>
						<?php }?>
					  </select>
					<?php }?> 
				    <br>
				  </td>
				</tr>
			  </table>
		    </div>
		    <div style="padding-left: 5px;">
			  <table class="c">
			    <tr>
				  <th class="left" width="80px"><?php echo $text_currency;?></th>
				  <td class="left">
				    <input name="currency" readonly="readonly" style="border: 0px;" value="<?php echo $currency;?>">
					<input name="exchange_value" value="<?php echo $exchange_value;?>" type="hidden">
				  </td>
			    </tr>
			  </table> 
		    </div>  
		    <div id="invoice_products" class="b">
			  <table class="c">
			    <tr>
				  <th class="left" style="width: 200px;"><?php echo $text_product; ?></th>
				  <th class="left" style="width: 100px;"><?php echo $text_quantity; ?></th>
				  <th class="left" style="width: 120px;"><?php echo $text_price; ?></th>
				  <th class="left" style="width: 100px;"><?php echo $text_tax_percent; ?></th>
				  <th class="left" style="width: 120px;"><?php echo $text_tax_amount; ?></th>
				  <th class="left" style="width: 200px;"><?php echo $text_total; ?></th>
			    </tr>
			    <tr><td colspan="6"><hr></td></tr>
			  
			  </table>
			  <table id="products" class="c">
			   
			    <?php if($order_cancelled){?>				
					<?php foreach ($order_products as $key => $order_product) { ?>
					  <tr id="row[<?php echo $key;?>]">
					  <input name="products[<?php echo $key;?>][product_id]" value="<?php echo $order_product['product_id'];?>" type="hidden">
					  <input name="products[<?php echo $key;?>][special_price]" value="<?php echo $order_product['special_price'];?>" type="hidden">
					  <input name="products[<?php echo $key;?>][discount]" value="<?php echo $order_product['discount'];?>" type="hidden">
					  <input name="products[<?php echo $key;?>][general_discount]" value="<?php echo $order_product['general_discount'];?>" type="hidden">
					  <input name="products[<?php echo $key;?>][coupon]" value="<?php  echo $order_product['coupon'];?>" type="hidden">
					  <input name="products[<?php echo $key;?>][shipping]" value="<?php echo $order_product['shipping'];?>" type="hidden">
					  <input name="products[<?php echo $key;?>][name]" value="<?php echo $order_product['name'];?>" type="hidden">
					  <input name="products[<?php echo $key;?>][model_number]" value="<?php echo $order_product['model_number'];?>" type="hidden">
					  <input name="products[<?php echo $key;?>][vendor_name]" value="<?php echo $order_product['vendor_name'];?>" type="hidden">
					  <input name="products[<?php echo $key;?>][vendor_id]" value="<?php echo $order_product['vendor_id'];?>" type="hidden">
					  <input name="products[<?php echo $key;?>][total]" value="<?php echo $order_product['total'];?>" type="hidden">
					  <input name="products[<?php echo $key;?>][barcode]" value="<?php echo $order_product['barcode'];?>" type="hidden">
					  
					  <td class="left" style="width: 200px;"><?php echo $order_product['name'];?></a>
					    <?php if($order_product['option']) {?>
						  <?php foreach ($order_product['option'] as $option) { ?>
					       <br>
					       &nbsp;<small> - <?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
					      <?php } ?>
						<?php }?>
					  </td>
					  <td class="left" style="width: 100px;">
					    <input name="products[<?php echo $key;?>][quantity]" size="6" value="<?php echo $order_product['quantity'];?>" id="quantity_<?php echo $key;?>" type="hidden">
						<?php echo $order_product['quantity'];?>
					  </td>
					  <td class="left" style="width: 120px;">
					    <input name="products[<?php echo $key;?>][price]" size="12" value="<?php echo $order_product['price'];?>" id="price_<?php echo $key;?>" type="hidden">
					    <?php echo $order_product['format_price'];?>
					  </td>
					    <input name="products[<?php echo $key;?>][tax]" value="<?php echo $order_product['tax'];?>" type="hidden">
					  <td class="left" style="width: 100px;">
					    <input name="products[<?php echo $key;?>][tax_rate]" size="10" value="<?php echo $order_product['tax'];?>" id="tax_rate_<?php echo $key;?>" type="hidden">
						<?php echo $order_product['format_tax'];?>
					  </td>
					  <td class="left" style="width: 120px;">
					    <input name="products[<?php echo $key;?>][tax_amount]" size="10" value="<?php echo $order_product['product_taxamount'];?>" id="tax_amount_<?php echo $key;?>" type="hidden">
						<?php echo $order_product['format_taxamount'];?>
					  </td>
					  <td class="left" style="width: 200px;">
					    <input name="products[<?php echo $key;?>][extended]" size="12" value="<?php echo number_format(($order_product['product_net']*"-1"), $decimal_place, '.', '');?>" id="extended_<?php echo $key;?>" type="hidden">
						<?php echo $order_product['format_net'];?>
					  </td>
					<?php }?>
				<?php }?>
				
			  </table>
			  <hr>
			  <table id="product_select">
			  
			  </table>
			  <?php if(!$order_cancelled){?>
			    <input type="button" class="button" value="<?php echo $button_add;?>" onclick="$('#product_select').load('index.php?controller=order_edit&action=get_products');">
			  <?php }?>	
			  <br>
			  <div class="f">
			  <hr>
			  <table style="width:100%">
				<tr><td style="width:600px;"></td><td class="right"></td><td></td></tr>
				<tr>
				  <td></td>
				  <td class="set"><?php echo $entry_subtotal;?></td>
				  <td class="right">
				    <?php if($order_cancelled){?>
					  <input name="totals_subtotal" id="totals_subtotal" value="<?php echo ($totals_subtotal * -1) ;?>" type="hidden">
				      <input name="subtotal" id="subtotal" value="<?php echo number_format((float)str_replace($decimal_point,'.',str_replace($symbols,'',$extended_total)*(-1)),$decimal_place,'.','');?>" readonly="readonly" style="border: 0px;"> 
					<?php } else {?>
					  <input name="subtotal" id="subtotal" value="" readonly="readonly">
					<?php }?>
				  </td>
				</tr>
				<tr id="shipping_method">
				  <td class="set">
					<?php echo $entry_shipping_tax;?>
					  <?php if($order_cancelled){?>
					    <input class="right" id="shipping_tax_rate"  name="shipping_tax_rate" value="<?php echo $ship_tax;?>" readonly="readonly" style="border: 0px;">
					  <?php } else {?>
				        <input class="validate_float" id="shipping_tax_rate" style="text-align: right;" name="shipping_tax_rate" value="<?php echo isset($shipping_tax_rate) ? str_replace('%','', $shipping_tax_rate) : '';?>" onchange="update_totals()">
					  <?php }?>
				  </td>
				  <td class="set"><?php echo $entry_shipping;?></td>
				  <td class="right">
				    <?php if($order_cancelled){?>
					  <input name="display_shipping" value="<?php echo $display_shipping;?>" readonly="readonly" style="border: 0px;">
					  <input name="totals_shipping" id="totals_shipping" value="<?php echo number_format(($totals_shipping*(-1)), $decimal_place, '.', '') ;?>" type="hidden">
					  <input name="shipping" id="shipping" value="<?php echo number_format(($ship_net*(-1)), $decimal_place, '.', '');?>" type="hidden">
					<?php } else {?>
				      <input class="validate_float_n" name="shipping" id="shipping" value="<?php echo $shipping_cost ? $shipping_cost : '';?>" onchange="update_totals()">
					<?php }?>
				  </td>
				  <?php if($order_cancelled){?>
				    <input id="shipping_tax" name="shipping_tax" value="<?php echo number_format(($ship_tax*(-1)), $decimal_place, '.', '');?>" type="hidden">
				  <?php } else {?>
				    <input id="shipping_tax" name="shipping_tax" value="" type="hidden">
				  <?php }?>	
				</tr>
				<?php if($order_cancelled && !empty($freeshipping_net)){?>
				<tr>
				  <td></td>
				  <td class="set"><?php echo $text_free_shipping;?></td>
				  <td class="right">
					<input name="display_freeshipping" value="<?php echo $display_freeshipping;?>" readonly="readonly" style="border: 0px;">
					<input name="totals_freeshipping" id="totals_freeshipping" value="<?php echo number_format(($e_freeshipping_net), $decimal_place, '.', '') ;?>" type="hidden">
				    <input name="freeshipping_net" value="<?php echo number_format((	$freeship_net*(-1)), $decimal_place, '.', '');?>" type="hidden">
				  </td>
				</tr>
				<?php }?>
				
				<?php if($order_cancelled && $discount_total){?>
				<tr>
				  <td></td>
				  <td class="set"><?php echo $text_discount_total;?></td>
				  <td class="right">
				    <input name="totals_discount" value="<?php echo $e_discount_total;?>" type="hidden">
				    <input name="discount_total" value="<?php echo number_format((float)str_replace($decimal_point,'.',str_replace($symbols,'',$discount_total)*(-1)),$decimal_place,'.','');?>" readonly="readonly" style="border: 0px;">
					<input name ="discount_sort_order" value="<?php echo $discount_sort_order;?>" type="hidden">
				  </td>
				</tr>
				<?php }?>
				<?php if($order_cancelled && $coupon_totals){?>
				<tr>
				  <td></td>
				  <td class="set"><?php echo $text_coupon_total;?></td>
				  <td class="right">
				    <input name="coupon_total" value="<?php echo number_format((float)str_replace($decimal_point,'.',str_replace($symbols,'',$coupon_total)*(-1)),$decimal_place,'.','');?>" readonly="readonly" style="border: 0px;">
					<input name ="coupon_sort_order" value="<?php echo $coupon_sort_order;?>" type="hidden">
					<input name="totals_coupon" value="<?php echo $e_coupon_total;?>" type="hidden">
				  </td>
				</tr>
				<?php }?>
				<tr>
				  <td></td>
				  <td class="set"><?php echo $entry_total_tax;?>
				  <td class="right">
				    <?php if($order_cancelled){?>
				      <input name="total_tax" id="total_tax" value="<?php echo number_format((float)str_replace($decimal_point,'.',str_replace($symbols,'',$cart_tax_total)*(-1)),$decimal_place,'.','');?>" readonly="readonly" style="border: 0px;">
					  <input name="totals_tax" value="<?php echo $e_tax_total;?>" type="hidden">
				    <?php } else {?>
				      <input name="total_tax" id="total_tax" value="" readonly="readonly" style="border: 0px;">
					<?php }?> 
				  </td>
				</tr>
				<tr>
				  <td></td>
				  <td class="set"><?php echo $entry_total_invoice;?>
				  <td class="right">
				    <?php if($order_cancelled){?>
					  <input name="total" id="total" value="<?php echo number_format(($display_cart_totals_total*(-1)), $decimal_place, '.', '');?>" readonly="readonly" style="border: 0px;">
					  <input name="total_invoice" id="total_invoice" value="<?php echo $total_invoice;?>" type="hidden">
					<?php } else {?>
				      <input name="total_invoice" id="total_invoice" value="" readonly="readonly" style="border: 0px;">
					<?php }?> 
				  </td>
				</tr>
				<input type="hidden" id="shipping_weight" name="shipping_weight" value="">
			  </table>
			  
			  <div class="left">   
			     <input type="button" class="button" value="<?php echo $button_create;?>" onclick="submit_form()">
			 </div>
			  
		    </div>
		  </div>
		  <?php if($order_cancelled){?>
			<input name ="total" value="<?php echo ($total *-1);?>" type="hidden">
		    <input name ="e_extended_total" value="<?php echo ($e_extended_total *-1);?>" type="hidden">
		    <input name ="e_coupon_total" value="<?php echo ($e_coupon_total);?>" type="hidden">
		    <input name ="e_discount_total" value="<?php echo ($e_discount_total);?>" type="hidden">
			<input name ="e_net_total" value="<?php echo ($e_net_total*-1);?>" type="hidden">
		    <input name ="e_tax_total" value="<?php echo ($e_tax_total*-1);?>" type="hidden">
			<input name ="e_totals_total" value="<?php echo ($e_totals_total*-1);?>" type="hidden">
			<input name ="e_cart_net_total" value="<?php echo ($e_cart_net_total*-1);?>" type="hidden">
			<input name ="e_cart_tax_total" value="<?php echo ($e_cart_tax_total*-1);?>" type="hidden">
			<input name ="e_cart_totals_total" value="<?php echo ($e_cart_totals_total*-1);?>" type="hidden">
			<input name ="e_shipping_net" value="<?php echo ($e_shipping_net*-1);?>" type="hidden">
			<input name ="e_freeshipping_net" value="<?php echo ($e_freeshipping_net);?>" type="hidden">
			<input name ="e_shipping_tax" value="<?php echo ($e_shipping_tax*-1);?>" type="hidden">
			<input name ="e_shipping_total" value="<?php echo ($e_shipping_total*-1);?>" type="hidden">
			<input name ="e_freeshipping_tax" value="<?php echo ($e_freeshipping_tax);?>" type="hidden">
			<input name ="e_freeshipping_total" value="<?php echo ($e_freeshipping_total);?>" type="hidden">
			<input name ="taxed" value="<?php echo $taxed;?>" type="hidden">
		  <?php }?> 
		  <input type="hidden" name="order_id" value="<?php echo $order_id;?>">
          <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
		</form>
      </div>
    </div>
  </div>
  <?php if($order_id){?>
	<div class="page">
	  <div class="pad">
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
					<?php if ($product['model_number']) { ?>
					<br>
					&nbsp;<small>(<?php echo $text_model_number; ?> <?php echo $product['model_number']; ?>)</small>
					<?php } ?>
					<br>
					<?php if ($product['vendor_name']) { ?>
						<span class="vendor"><?php echo $text_soldby; ?><br><?php echo $product['vendor_name']; ?></span>
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
				    <?php if ($product['download']) {?><img src="template/<?php echo $this->directory?>/image/downloadable.png" width=32 height=32 alt="<?php echo $text_downloadable; ?>" title="<?php echo $text_downloadable; ?>" >
				    <?php }else if ($product['shipping']) { ?><img src="template/<?php echo $this->directory?>/image/shippable.png" width=32 height=32 alt="<?php echo $text_shippable; ?>" title="<?php echo $text_shippable; ?>" ><?php } else { ?><img src="template/<?php echo $this->directory?>/image/non_shippable.png" width=32 height=32 alt="<?php echo $text_non_shippable; ?>" title="<?php echo $text_non_shippable; ?>"><?php  } ?>
				  </td>
				  <td class="right"><?php echo '<span class="tax">*</span>' . $product['total_discounted']; ?></td>
				</tr>
				<?php if($product['barcode_url']){?>
				  <tr>
					<td>
					  <img src="<?php echo $product['barcode_url']; ?>" title="<?php echo $product['barcode']; ?>" width="<?php echo $product['barcode_width'];?>" height="<?php echo $product['barcode_height'];?>" alt="<?php echo $product['barcode']; ?>">
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
				  <td class="right"><?php echo ($taxed ? '<span class="tax">* </span>' : '') . $shipping_net;?></td>
				  <td class="right"><?php echo $shipping_tax_rate;?></td>
				  <td class="right"><?php echo $shipping_tax;?></td>
				  <td></td>
				  <td class="right"><?php echo $shipping_total ? '<span class="tax">*</span>' . $shipping_total : '';?></td>
				</tr>
				<?php if(!empty($freeshipping_net)){?>
				  <tr>
					<th class="left"><?php echo $text_free_shipping;?></th>
					<td colspan="6"></td>
					<td class="right"><?php echo ($taxed ? '<span class="tax">* </span>' : '') . $freeshipping_net;?></td>
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
		  </div>
		</div>
	  </div>
	<?php } ?>  
</div>
  <script type="text/javascript">
  tabview_initialize('tab');
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#invoice_products').hide();
	  open_body();
   });
  </script>
  <script type="text/javascript">
  function open_body(){
	if($('#shipping_method').val()!="" && $('#payment_method').val()!=""){
	  $('#invoice_products').show();
	}
  }
  </script>
  <script type="text/javascript">
  function submit_form(){
	var customer_id = $('#customer_id').val();
	if(!customer_id > 0){
		alert('customer required');
		return
	}
	$('#form').submit();
  }
  </script>
  <script type="text/javascript">
  function add_product(product_id){
	var Product_id = product_id;
	var item = String(Product_id);
	var options = [];
	var row = $('#products tr').size();
	$('#product_select select :selected').each(function(i, selected){
		options[i] = $(selected).val();
	});
	if(options.length!=undefined){
		var i;
		for (i in options){
				if(i == 0){item +=":";}
				else {item +=".";}
				item += (options[i]);
		}
	}
	$.ajax({
		type: 'GET',
		url:'index.php?controller=order_edit&action=get_product&product_id='+item+'&row='+row,
		async: false,
		success: function(data) {
			$('#products').append(data);
			$('#product_select').empty();
			update_row(row);
		}
	});
  }
  </script>
  <script type="text/javascript">
  function update_row(row){
	var quantity = $('#quantity_'+row).val();
	var price = $('#price_'+row).val();
	var tax_rate = $('#tax_rate_'+row).val();
	var extended = quantity * price;
	extended = ((extended*1000)/1000).toFixed([4]);
	$('#extended_'+row).val(extended);
	if(tax_rate > 0){
		tax_rate = tax_rate/100;
		var tax_amount =  (((extended * tax_rate)*1000)/1000).toFixed([4]);
		$('#tax_amount_'+row).val(tax_amount);

	} else {
		$('#tax_amount_'+row).val('0.00');
	}
	update_totals();
	
  }
  </script>
  <script type="text/javascript">
  function get_shipping_weight(){
	var rows = $('#products tr').size();
	var total_weight = parseFloat(0);
	var weight = parseFloat(0);
	var quantity = parseFloat(0);
	var i=0;
	while(i < rows){
		weight = $('#weight_'+i).val();
		quantity = $('#quantity_'+i).val();
		total_weight += (+weight*+quantity);
		i++;
	}
	return total_weight;
  }
  </script>
  <script type="text/javascript">
  function update_totals(){
	var tax = update_tax();
	var subtotal = update_subtotal();
	var shipping = $('#shipping').val();
	if(shipping==undefined){
		shipping=0;
	}
	var total = (+tax) + (+subtotal) + (+shipping);
	$('#total_invoice').val(((total * 1000) / 1000).toFixed([4]));
	var weight = get_shipping_weight();
	$('#shipping_weight').val(weight);
  }
  </script>
  <script type="text/javascript">
  function update_subtotal(){
	var rows = $('#products tr').size();
	var subtotal = parseFloat(0);
	var extended = 0;
	var i=0;
	while(i < rows){
		extended = $('#extended_'+i).val();
		if(extended!=undefined){
			subtotal += +extended;
		}
		i++;
	}
	subtotal = ((subtotal * 1000) / 1000).toFixed([4]);
	$('#subtotal').val(subtotal);
	return subtotal;
  }
  </script>
  <script type="text/javascript">
  function update_tax(){
	var rows = $('#products tr').size();
	var total_tax = parseFloat(0);
	var tax_amount = 0;
	var i=0;
	while(i < rows){
		tax_amount = $('#tax_amount_'+i).val();
		if(tax_amount!=undefined){
			total_tax += +tax_amount;
		}
		i++;
	}
	var shipping = $('#shipping').val();
	if(shipping!=undefined){
		var shipping_tax = $('#shipping_tax_rate').val();
		if(shipping_tax!=undefined){
			if(shipping_tax>1){
				var rate = shipping_tax / 100;
				var shipping_amount = shipping * rate;
				total_tax += +shipping_amount;
				$('#shipping_tax').val(shipping_amount);
			}
		}
	}
	$('#shipping').val(((+shipping * 1000) / 1000).toFixed([4]));
	total_tax = ((total_tax * 1000) / 1000).toFixed([4]);
	$('#total_tax').val(total_tax);
	return total_tax;
  }
  </script>
  <script type="text/javascript">
  function get_customer_info(customer_id){
	$('#shipping_address').load('index.php?controller=order_edit&action=get_shipping_addresses&customer_id='+customer_id);
	$('#email').load('index.php?controller=order_edit&action=get_email&customer_id='+customer_id);
	$('#phonefax').load('index.php?controller=order_edit&action=get_phone&customer_id='+customer_id);
  }
  </script>
  <script type="text/javascript">
  $(document).ready(function() {
	$('.task').each(function(){
	$('.task .disabled').hide();
	});
	<?php if (!$help) { ?>
		$('.description').hide(0);
	<?php } ?>
  });
  function ShowDesc(){
	$.ajax({
		type:    'POST',
		url:     'index.php?controller=order_edit&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  </script>
  <script type="text/javascript">
  $(document).ready(function() {
	RegisterValidation();
  });
</script>

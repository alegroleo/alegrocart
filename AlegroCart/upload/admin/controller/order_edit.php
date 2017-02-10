<?php //Order Edit AlegroCart
class ControllerOrderEdit extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 			=& $locator->get('model');
		$this->address  	=& $locator->get('address');
		$this->config   	=& $locator->get('config');
		$this->image    	=& $locator->get('image');   
		$this->currency 	=& $locator->get('currency');
		$this->language 	=& $locator->get('language');
		$this->mail         =& $locator->get('mail');
		$this->module   	=& $locator->get('module');
		$this->order     	=& $locator->get('order');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session 		=& $locator->get('session');
		$this->tax		=  $locator->get('tax');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user'); 
		$this->weight   	=  $locator->get('weight');
		$this->modelOrder 	= $model->get('model_admin_order');
		$this->modelOrderEdit = $model->get('model_admin_orderedit');
		$this->barcode     	=& $locator->get('barcode'); 
		$this->decimal_place = $this->currency->currencies[$this->currency->code]['decimal_place'];
		$this->language->load('controller/order_edit.php');
	}
	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}
	function update(){
		$this->template->set('title', $this->language->get('heading_title'));
		if (($this->request->isPost()) && ($this->validateForm())) {
			$this->modify_order();
			$products = $this->get_product_values();
			$totals = $this->get_totals();
			$this->assign_order($products, $totals);
			$this->order->process();
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('order'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}
	function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));

		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_order', $this->language->get('text_order'));
		$view->set('text_invoice_number', $this->language->get('text_invoice_number'));
		$view->set('text_email', $this->language->get('text_email'));
		$view->set('text_telephone', $this->language->get('text_telephone'));
		$view->set('text_fax', $this->language->get('text_fax'));
		$view->set('text_shipping_address', $this->language->get('text_shipping_address'));
	    	$view->set('text_shipping_method', $this->language->get('text_shipping_method'));
	    	$view->set('text_payment_address', $this->language->get('text_payment_address'));
	    	$view->set('text_payment_method', $this->language->get('text_payment_method'));
	    	$view->set('text_currency', $this->language->get('text_currency'));
			$view->set('text_order_history', $this->language->get('text_order_history'));
		$view->set('text_order_download', $this->language->get('text_order_download'));
		$view->set('text_order_update', $this->language->get('text_order_update'));
		$view->set('text_product', $this->language->get('text_product'));
		$view->set('text_model_number', $this->language->get('text_model_number'));
		$view->set('text_soldby', $this->language->get('text_soldby'));
		$view->set('text_quantity', $this->language->get('text_quantity'));
		$view->set('text_price', $this->language->get('text_price'));
		$view->set('text_total', $this->language->get('text_total'));
		$view->set('text_special', $this->language->get('text_special'));
		$view->set('text_extended', $this->language->get('text_extended'));
		$view->set('text_coupon_value', $this->language->get('text_coupon_value'));
		$view->set('text_discount_value', $this->language->get('text_discount_value'));
		$view->set('text_net', $this->language->get('text_net'));
		$view->set('text_tax_rate', $this->language->get('text_tax_rate'));
		$view->set('text_tax', $this->language->get('text_tax'));
		$view->set('text_tax_amount', $this->language->get('text_tax_amount'));
		$view->set('text_discount_total', $this->language->get('text_discount_total'));
		$view->set('text_product_totals', $this->language->get('text_product_totals'));
		$view->set('text_shipping_cost', $this->language->get('text_shipping_cost'));
		$view->set('text_free_shipping', $this->language->get('text_free_shipping'));
		$view->set('text_cart_totals', $this->language->get('text_cart_totals'));
		$view->set('text_shipping', $this->language->get('text_shipping'));
		$view->set('text_shippable', $this->language->get('text_shippable'));
		$view->set('text_non_shippable', $this->language->get('text_non_shippable'));
		$view->set('text_downloadable', $this->language->get('text_downloadable'));
		$view->set('text_select_shipping', $this->language->get('text_select_shipping'));
		$view->set('text_select_payment', $this->language->get('text_select_payment'));
		$view->set('text_tax_percent', $this->language->get('text_tax_percent'));
		$view->set('text_coupon_total', $this->language->get('text_coupon_total'));

		$view->set('column_date_added', $this->language->get('column_date_added'));
	    	$view->set('column_status', $this->language->get('column_status'));
	    	$view->set('column_download', $this->language->get('column_download'));
	    	$view->set('column_filename', $this->language->get('column_filename'));
	    	$view->set('column_remaining', $this->language->get('column_remaining'));
	    	$view->set('column_notify', $this->language->get('column_notify'));
	    	$view->set('column_comment', $this->language->get('column_comment'));

	    	$view->set('entry_status', $this->language->get('entry_status'));
	    	$view->set('entry_comment', $this->language->get('entry_comment'));
	    	$view->set('entry_notify', $this->language->get('entry_notify'));
		$view->set('entry_customer', $this->language->get('entry_customer'));
		$view->set('entry_shipping_tax', $this->language->get('entry_shipping_tax'));
		$view->set('entry_shipping', $this->language->get('entry_shipping'));
		$view->set('entry_subtotal', $this->language->get('entry_subtotal'));
		$view->set('entry_total_tax', $this->language->get('entry_total_tax'));
		$view->set('entry_total_invoice', $this->language->get('entry_total_invoice'));

		$view->set('button_create', $this->language->get('button_create'));
		$view->set('button_add', $this->language->get('button_add'));
	    	$view->set('button_insert', $this->language->get('button_insert'));
	    	$view->set('button_update', $this->language->get('button_update'));
	    	$view->set('button_delete', $this->language->get('button_delete'));
	    	$view->set('button_save', $this->language->get('button_save'));
	    	$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));

		$view->set('help', $this->session->get('help'));
    		$view->set('tab_original', $this->language->get('tab_original'));
		$view->set('tab_create', $this->language->get('tab_create'));

		$view->set('error', @$this->error['message']);

    		$view->set('action', $this->url->ssl('order_edit', 'update', array('order_id' => $this->request->gethtml('order_id'))));

		$view->set('cancel', $this->url->ssl('order'));

		if (!$this->request->gethtml('order_id')) {
			$results = $this->modelOrderEdit->get_customers();
			$view->set('customers' , $results);
		}

		if ($this->request->gethtml('order_status_id') == "12"){
			$view->set('order_cancelled', TRUE);
		} else {
			$view->set('order_cancelled', FALSE);
		}

		/*if ($this->request->gethtml('order_id')) {	    
      		$view->set('update', 'update');
	  		$view->set('delete', $this->url->ssl('order', 'delete', array('order_id' => $this->request->gethtml('order_id'),'order_validation' => $this->session->get('order_validation'))));
    	}*/

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$order_info = $this->modelOrder->get_order();
		$view->set('reference', $order_info['reference']);
		$view->set('invoice_number', $order_info['invoice_number']);
		$view->set('new_reference', '');
		$view->set('new_invoice_number', '');
		if ($this->request->gethtml('order_id')) {
			$view->set('email', $order_info['email']);
			$view->set('telephone', $order_info['telephone']);
			$view->set('fax', $order_info['fax']);
			$view->set('customer_id', $this->modelOrderEdit->get_customer_id($order_info['email']));

				$name_last = $order_info['invoice_number'];
				if (strlen($name_last) > 26) {
					$name_last = substr($name_last , 0, 23) . '...';
				}
				$this->session->set('name_last_order', $name_last);
				$this->session->set('last_order', $this->url->ssl('order_edit', 'index', array('order_id' => $this->request->gethtml('order_id'))));
				$this->session->set('last_order_id', $this->request->gethtml('order_id'));

		} else {
			$view->set('email','');
			$view->set('telephone', '');
			$view->set('fax', '');
			$view->set('customer_id','');
		}

		if ($this->request->gethtml('order_id')) {
			$view->set('currency', $order_info['currency']);
			$this->decimal_place = $this->currency->currencies[$order_info['currency']]['decimal_place'];
			$this->currency->set($order_info['currency']);
			$currency_code = $order_info['currency'];
			$symbol_right = $this->currency->currencies[$currency_code]['symbol_right'];
			$symbol_left = $this->currency->currencies[$currency_code]['symbol_left'];
			$view->set('symbols', array($symbol_left,$symbol_right,$this->language->get('thousand_point')));
			$view->set('decimal_place', $this->decimal_place);
			$view->set('decimal_point', $this->language->get('decimal_point'));
			$view->set('exchange_value', $order_info['value']);
		} else {
			$this->currency_code = $this->config->get('config_currency');
			$view->set('currency', $this->currency_code);
			$this->decimal_place = $this->currency->currencies[$this->currency_code]['decimal_place'];
			$this->currency->set($this->config->get('config_currency'));
			$view->set('exchange_value', 1.00);
		}
		$view->set('coupon_sort_order', $order_info['coupon_sort_order']);
		$view->set('discount_sort_order', $order_info['discount_sort_order']);
		//$view->set('columns', $this->tpl_columns);

		if ($this->request->gethtml('order_id')) {
			$shipping_address = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'country'   => $order_info['shipping_country']
			);
			$new_shipping_address = array(
				'shipping_firstname' => $order_info['shipping_firstname'] ? $order_info['shipping_firstname'] : $order_info['payment_firstname'],
				'shipping_lastname'  => $order_info['shipping_lastname'] ? $order_info['shipping_lastname'] : $order_info['payment_lastname'],
				'shipping_company'   => $order_info['shipping_company'],
				'shipping_address_1' => $order_info['shipping_address_1'],
				'shipping_address_2' => $order_info['shipping_address_2'],
				'shipping_city'      => $order_info['shipping_city'],
				'shipping_postcode'  => $order_info['shipping_postcode'],
				'shipping_zone'      => $order_info['shipping_zone'],
				'shipping_country'   => $order_info['shipping_country']
			);

			$view->set('new_shipping_address', $new_shipping_address);
			if (array_filter($shipping_address)) {
				$view->set('shipping_address', $this->address->format($shipping_address, $order_info['shipping_address_format'], '<br />'));
				$view->set('address_shipping', $shipping_address);
			} else {
				$store_address = str_replace(array("\r\n", "\r", "\n"), '<br>', $this->config->get('warehouse_location') ? $this->config->get('warehouse_location') : $this->config->get('config_address'));
			$view->set('shipping_address', $this->config->get('config_store') . "<br />" . $store_address);
			}
		} else {
			$view->set('shipping_address','<br><br><br><br>');
		}

		if ($this->request->gethtml('order_id')) {
			$view->set('shipping_method', $order_info['shipping_method'] ? $order_info['shipping_method'] : '*');
		} else {
			$view->set('shipping_method','');
		}

    		if ($this->request->gethtml('order_id')) {
			$payment_address = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'country'   => $order_info['payment_country']
			);
			$new_payment_address = array(
				'payment_firstname' => $order_info['payment_firstname'],
				'payment_lastname'  => $order_info['payment_lastname'],
				'payment_company'   => $order_info['payment_company'],
				'payment_address_1' => $order_info['payment_address_1'],
				'payment_address_2' => $order_info['payment_address_2'],
				'payment_city'      => $order_info['payment_city'],
				'payment_postcode'  => $order_info['payment_postcode'],
				'payment_zone'      => $order_info['payment_zone'],
				'payment_country'   => $order_info['payment_country']
			);

			$view->set('new_payment_address', $new_payment_address);
			$view->set('payment_address', nl2br($this->address->format($payment_address, $order_info['payment_address_format'])));
		} else {
			$view->set('payment_address','<br><br><br><br>');
		}

    		if ($this->request->gethtml('order_id')) {
			$view->set('payment_method', $order_info['payment_method']);
		} else {
			$view->set('payment_method','');
		}

    		$products = $this->modelOrder->get_products();
		if($this->request->gethtml('order_status_id') == "12"){
			$order_product_data = array();
		}
    		$product_data = array();

		$tax_total = 0;	
		$coupon_total = 0;
		$net = 0;
		$net_total = 0;
		$producttax = 0;
		$discount_total = 0;
		$totals_total = 0;
		$shipping_tax = 0;
		$extended_total = 0;
		$freeshipping_tax = 0;
		$shipping_net = $order_info['shipping_net'];
		$freeshipping_net = $order_info['freeshipping_net'];
		$shipping_tax = roundDigits($order_info['shipping_tax_rate'] * $order_info['shipping_net'] / 100, $this->decimal_place);
		$view->set('ship_net', $shipping_net);
		$view->set('ship_tax', $order_info['shipping_tax_rate']);
		$freeshipping_tax = roundDigits($order_info['shipping_tax_rate'] * $order_info['freeshipping_net'] / 100, $this->decimal_place);
		$shipping_total = $order_info['shipping_net'] + $shipping_tax;
		$view->set('totals_shipping', ($order_info['taxed'] ? $shipping_tax : 0) + $shipping_net);
		$freeshipping_total = $order_info['freeshipping_net'] + $freeshipping_tax;
		$view->set('shipping_net', $this->currency->format($order_info['shipping_net'] + ($order_info['taxed'] ? $shipping_tax : 0), $order_info['currency'], $order_info['value']));
		$view->set('shipping_cost', $this->currency->format($order_info['shipping_net'], NULL, NULL, FALSE));
		$view->set('e_shipping_net', $order_info['shipping_net'] + ($order_info['taxed'] ? $shipping_tax : 0));
		$view->set('shipping_tax_rate', round($order_info['shipping_tax_rate'], $this->decimal_place). '%');
		if($order_info['freeshipping_net'] > 0){
			$view->set('freeshipping_net', $this->currency->format(($order_info['freeshipping_net'] * -1) + ($order_info['taxed'] ? ($freeshipping_tax *-1): 0), $order_info['currency'], $order_info['value']));
			$view->set('freeship_net', $order_info['freeshipping_net']);
			$view->set('e_freeshipping_net', $order_info['freeshipping_net']+ ($order_info['taxed'] ? ($freeshipping_tax): 0));
		} else {
			$view->set('freeshipping_net', NULL);
			$view->set('freeship_net', 0);
			$view->set('e_freeshipping_net', 0);
		}

		foreach ($products as $product) {
			$options = $this->modelOrder->get_options($product['order_product_id']);
			$option_data = array();
			foreach ($options as $option) {
				$option_data[] = array(
					'name'  => $option['name'],
					'value' => $option['value']
				);
			}

			if(!empty($option_data)){
				$product_id= $product['product_id'] . ':';
				foreach ($option_data as $option){
					$p2o_id = $this->modelOrderEdit->get_option_id($product['product_id'],trim($option['value']));
					$product_id .= $p2o_id . '.';
				}
				$product_id = rtrim($product_id, ".");
			} else {
				$product_id= $product['product_id'];
			}

			$download = $this->modelOrder->check_downloads($product['order_product_id']);
 			$vendor_data = $this->modelOrder->get_vendor($product['order_product_id']);

			$special_pr = $product['special_price'];
			$net = $product['total'] - ($product['coupon'] ? $product['coupon'] : NULL ) - ($product['general_discount'] ? $product['general_discount'] : NULL );
			$producttax = $order_info['taxed'] ? $net - roundDigits($net / ((100 + $product['tax'])/100), $this->decimal_place) : roundDigits($net * ($product['tax'] / 100), $this->decimal_place);
			$tax_total += $producttax;
			$coupon_total += $product['coupon'] ? $product['coupon'] : NULL;
			$discount_total += $product['general_discount'] ? $product['general_discount'] : NULL;
			$net_total += $net;
			$total_discounted = $order_info['taxed'] ? $net : $net + $producttax;
			$totals_total += $total_discounted;
			$extended_total += $product['total'];
			$cart_net_total = $net_total + ($shipping_net ? $shipping_net : NULL) - ($freeshipping_net ? $freeshipping_net : NULL);
			$cart_tax_total = $tax_total + ($shipping_net ? $shipping_tax : NULL) - ($freeshipping_net ? $freeshipping_tax : NULL);
			//$cart_totals_total = $order_info['taxed'] ? $cart_net_total : $cart_net_total + $cart_tax_total;
			$cart_totals_total = $order_info['taxed'] ? $cart_net_total + ($shipping_tax - $freeshipping_tax): $cart_net_total + $cart_tax_total;

			$product_data[] = array(
				'product_id'    => $product_id,
				'name'     		=> $product['name'],
				'model_number'		=> $product['model_number'],
				'vendor_name'		=> $vendor_data['vendor_id'] !='0' ? $vendor_data['vendor_name'] : NULL,
				'option'   		=> $option_data,
				'download'      => $download,
				'quantity' 		=> $product['quantity'],
				'barcode' 		=> $product['barcode'],
				'barcode_url' 		=> $product['barcode'] ? $this->barcode->show($product['barcode']) : NULL,
				'special_price'	=> $special_pr > 0 ? $this->currency->format($special_pr, $order_info['currency'], $order_info['value']) : NULL,
				'price'    		=> $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
				
				'discount' 		=> (ceil($product['discount']) ? $this->currency->format($product['discount'], $order_info['currency'], $order_info['value']) : NULL),
				'coupon' 		=> ($product['coupon'] > 0 ? '-' . $this->currency->format($product['coupon'], $order_info['currency'], $order_info['value']) : NULL),
				'general_discount' 	=> ($product['general_discount'] > 0 ? '-' . $this->currency->format($product['general_discount'], $order_info['currency'], $order_info['value']) : NULL),
				'tax'     		=> round($product['tax'], $this->decimal_place),
				'shipping'     		=> $product['shipping'],
				'total'   		=> $this->currency->format($product['total'],$order_info['currency'], $order_info['value']),
				'net'			=> $this->currency->format(($net), $order_info['currency'], $order_info['value']),
				'product_tax'		=> $this->currency->format($producttax, $order_info['currency'], $order_info['value']),
				'total_discounted'	=> $this->currency->format($total_discounted, $order_info['currency'], $order_info['value']),
				'product_net'       => $net,
				'product_taxamount' => $producttax
			);
			if($this->request->gethtml('order_status_id') == "12"){
				$order_product_data[] = array(
					'product_id'    => $product_id,
					'name'     		=> $product['name'],
					'option'   		=> $option_data,
					'model_number'	=> $product['model_number'] ? $product['model_number'] : NULL,
					'vendor_name'   => $product['vendor_name'] ? $product['vendor_name'] : NULL,
					'vendor_id'     => $product['vendor_id'] ? $product['vendor_id'] : 0,
					'price'         => $product['price'],
					'format_price'  => $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
					'discount'		=> $product['discount'] ? ($product['discount'] * -1) : 0 ,
					'special_price'	=> $product['special_price'] ? ($product['special_price'] * -1) : 0,
					'coupon' 		=> $product['coupon'] ? ($product['coupon'] * -1): 0,
					'general_discount' 	=> $product['general_discount'] ? ($product['general_discount'] * -1): 0,
					'total'   		=> ($product['total'] * -1),
					'tax'			=> $product['tax'] ? $product['tax'] : 0,
					'format_tax'     		=> round($product['tax'], $this->decimal_place),
					'quantity' 		=> ($product['quantity'] * -1),
					'barcode' 		=> $product['barcode'] ? $product['barcode'] : NULL,
					'shipping'		=> $product['shipping'],
					'product_taxamount' => $producttax,
					'format_taxamount'  => $this->currency->format(($producttax), $order_info['currency'], $order_info['value']),
					'format_net'	=> $this->currency->format(($net*-1), $order_info['currency'], $order_info['value']),
					'product_net'   => $net
				);
			}
		}
		if($this->request->gethtml('order_status_id') == "12"){
			$view->set('order_products', $order_product_data);

		}

		if($this->request->gethtml('order_id')){
			$view->set('order_id', $this->request->gethtml('order_id'));
#			$this->session->set('last_order_id', $this->request->gethtml('order_id'));
		} else {
			$view->set('order_id', ''); 
		}
		$view->set('taxed', $order_info['taxed']);
      		$view->set('products', $product_data);
		$view->set('totals',$this->modelOrder->get_totals($order_info['order_id']));
		$view->set('tax_total', $this->currency->format($tax_total, $order_info['currency'], $order_info['value']));
		$view->set('e_tax_total', $tax_total);
		$view->set('coupon_totals', $coupon_total);
		$view->set('e_coupon_total', $coupon_total);
		$view->set('coupon_total', $coupon_total ? '-' . $this->currency->format($coupon_total, $order_info['currency'], $order_info['value']) : NULL);
		$view->set('discount_total', $discount_total ? '-' . $this->currency->format($discount_total, $order_info['currency'], $order_info['value']) : NULL);
		$view->set('e_discount_total', $discount_total);
		$view->set('extended_total', $this->currency->format($extended_total, $order_info['currency'], $order_info['value']));
		$view->set('totals_subtotal', $extended_total);
		$view->set('e_extended_total', $extended_total);
		$view->set('net_total', $this->currency->format($net_total, $order_info['currency'], $order_info['value']));
		$view->set('e_net_total', $net_total);
		$view->set('cart_net_total', isset($cart_net_total) ? $this->currency->format($cart_net_total + ($order_info['taxed'] ? ($shipping_tax - $freeshipping_tax) : Null), $order_info['currency'], $order_info['value']) : '');
		$view->set('e_cart_net_total', isset($cart_net_total) ? $cart_net_total : '');
		$view->set('shipping_tax', $shipping_tax  ? $this->currency->format($shipping_tax, $order_info['currency'], $order_info['value']) : NULL);
		$view->set('e_shipping_tax', $shipping_tax  ? $shipping_tax : 0);
		$view->set('freeshipping_tax', $freeshipping_tax ?'-' . $this->currency->format($freeshipping_tax, $order_info['currency'], $order_info['value']) : NULL);
		$view->set('e_freeshipping_tax', $freeshipping_tax ? $freeshipping_tax : 0);
		$view->set('cart_tax_total', isset($cart_tax_total) ? $this->currency->format($cart_tax_total, $order_info['currency'], $order_info['value']) : '');
		$view->set('e_cart_tax_total', isset($cart_tax_total) ? $cart_tax_total : '');
		$view->set('totals_total', isset($totals_total) ? $this->currency->format($totals_total, $order_info['currency'], $order_info['value']) : '');
		$view->set('e_totals_total', isset($totals_total) ? $totals_total : '');
		$view->set('shipping_total', $shipping_total ? $this->currency->format($shipping_total, $order_info['currency'], $order_info['value']) : NULL);
		$view->set('e_shipping_total', $shipping_total ? $shipping_total : 0);
		$view->set('freeshipping_total', $freeshipping_total ? '-' . $this->currency->format($freeshipping_total, $order_info['currency'], $order_info['value']) : NULL);
		$view->set('display_shipping', $shipping_total ? $this->currency->format(($shipping_total* -1) , $order_info['currency'], $order_info['value']) : NULL);
		$view->set('display_freeshipping',  $freeshipping_total ? $this->currency->format($freeshipping_total, $order_info['currency'], $order_info['value']) : NULL);
		$view->set('e_freeshipping_total', $freeshipping_total ? $freeshipping_total : 0);
		$view->set('display_cart_totals_total', isset($cart_totals_total) ? $this->currency->format($cart_totals_total, $order_info['currency'], $order_info['value'], FALSE) : '');
		$view->set('cart_totals_total', isset($cart_totals_total) ? $this->currency->format($cart_totals_total, $order_info['currency'], $order_info['value']) : '');
		$view->set('e_cart_totals_total', isset($cart_totals_total) ? $cart_totals_total : '');
		$view->set('total', isset($cart_totals_total) ? $cart_totals_total : '');
		$view->set('total_invoice', isset($cart_totals_total) ? ($cart_totals_total * -1) : '');

	    	$history_data = array();
	    	$results = $this->modelOrder->get_history();
	    	foreach ($results as $result) {
	      		$history_data[] = array(
				'date_added' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'status'     => $result['status'],
				'comment'    => $result['comment'],
				'notify'     => $result['notify']
	      		);
	    	}
	    	$view->set('historys', $history_data);

	    	$download_data = array();
	    	$results = $this->modelOrder->get_downloads();
	    	foreach ($results as $result) {
	      		$download_data[] = array(
				'name'      => $result['name'],
				'filename'  => $result['filename'],
				'remaining' => $result['remaining']
	      		);
	    	}
	    	$view->set('downloads', $download_data);
	    	$view->set('order_status_id', $order_info['order_status_id']);
			$view->set('shipping_methods',$this->get_shipping_methods());
			$view->set('payment_methods',$this->get_payment_methods());

	    	$view->set('order_statuses', $this->modelOrder->get_order_statuses());

		return $view->fetch('content/order_edit.tpl');
	}

	function modify_order(){
		$order_id = $this->request->gethtml('order_id', 'post');
		if($order_id){
			$this->modelOrderEdit->modify_order($order_id,$this->order->getReference());
		}
		if($this->request->gethtml('order_status','post')){
			$this->modelOrderEdit->update_status('12', $this->request->gethtml('order_id','post'));
			$this->modelOrderEdit->update_status_history($order_id, '12', $this->language->get('text_comment_cancelled' ));
		}
	}
	function get_product_values(){
		$products = $this->request->gethtml('products', 'post');
		$this->product_tax = 0;
		$this->product_extended_total = 0;
		$product_data = array();
		if(!$products){ return $product_data;}
		foreach($products as $key => $product){
			$this->product_tax += $product['tax_amount'];
			$this->product_extended_total += $product['extended'] + ($this->config->get('config_tax') ? $product['tax_amount'] : 0);
			$array = explode(':', $product['product_id']);
			$product_id = $array[0];
			if (isset($array[1])){
				$option_ids = explode('.', $array[1]);
			} else {
				$option_ids = array();
			}
			$product_info = $this->modelOrderEdit->get_product($product_id);
			$option_price = 0;
			$option_weight = 0;
      		$option_data = array();
			foreach ($option_ids as $product_to_option_id) {
				$option = $this->modelOrderEdit->get_product_to_option($product_id, $product_to_option_id);
				if ($option['prefix'] == '+') {
          			$option_price = $option_price + (($product['quantity'] > 0) ? $option['price'] : ($option['price'] * -1));
        		} elseif ($option['prefix'] == '-') {
          			$option_price = $option_price - (($product['quantity'] > 0) ? $option['price'] : ($option['price'] * -1));
        		}
				$temp_option_weight = $this->weight->convert($option['option_weight'],$option['option_weightclass_id'],$product_info['weight_class_id']);
				$option_weight = $option_weight + $temp_option_weight;
				$option_data[] = array(
          			'product_to_option_id' => $product_to_option_id,
          			'name'           => $option['name'],
          			'value'          => $option['value'],
          			'prefix'         => $option['prefix'],
          			'price'          => roundDigits($option['price'], $this->decimal_place),
					'option_weight'	 => $temp_option_weight,
					'option_weightclass_id'=> $option['option_weightclass_id']
        		);
			}
			if($option_ids){
				$product_option = $this->modelOrderEdit->product_with_options($product['product_id']);
			} else {
				$product_option = array();
			}
			
			
			$downloads = $this->modelOrderEdit->get_downloads($product_id);
			$download_data = array();
			foreach ($downloads as $download) {
        		$download_data[] = array(
          			'download_id' => $download['download_id'],
					'name'        => $download['name'],
					'filename'    => $download['filename'],
					'mask'        => $download['mask'],
					'remaining'   => $download['remaining']
        		);			
			}
			$vendor_data = $this->modelOrderEdit->get_vendor($product_id);
			
			if(!$this->request->gethtml('order_status','post')){
				$product['discount'] = 0;
				$product['special_price'] = 0;
				$product['general_discount'] = 0;
				$product['coupon'] = 0;
			}
			
		  if($this->request->gethtml('order_status','post')){
			$product_data[] = array(
				'product_key'	=> $product['product_id'],
				'product_id' 	=> $product_id,
				'name'       	=> $product_info['name'],
				'model_number'  => (isset($product_option['model_number']) ? @$product_option['model_number'] : @$product_info['model_number']),
				'vendor_id'	=> $vendor_data['vendor_id'],
				'vendor_name'	=> $vendor_data['vendor_id'] !='0' ? $vendor_data['name'] : NULL,
				'option'     	=> $option_data,
				'download'		=> $download_data,
				'quantity'   	=> $product['quantity'],
				'barcode'       => isset($product_option['barcode']) ? @$product_option['barcode'] : @$product_info['barcode'],
				'price'      	=> $this->currency->format(($product['quantity'] > 0 ? $product['price'] : ($product['price']*-1)), '', 1, FALSE),
				'discount'   	=> $product['discount'] != 0 ? $this->currency->format($product['discount'], '', 1, FALSE) : 0,
				'coupon'     	=> $product['coupon'] != 0 ? $this->currency->format($product['coupon'], '', 1, FALSE) : 0,
				'special_price' => $product['special_price'] != 0 ? $this->currency->format($product['special_price'], '', 1, FALSE) : 0,
				'general_discount'=> $product['general_discount'] != 0 ? $product['general_discount'] : 0,
				'total'      	=> $this->currency->format($product['total'], '', 1, FALSE),
				'tax'        	=> $this->tax->getRate($product_info['tax_class_id']), 
				'shipping'   	=> $product_info['shipping']
			);
		  } else {
			$product_data[] = array(
				'product_key'	=> $product['product_id'],
				'product_id' 	=> $product_id,
				'name'       	=> $product_info['name'],
				'model_number'  => (isset($product_option['model_number']) ? @$product_option['model_number'] : @$product_info['model_number']),
				'vendor_id'		=> NULL,
				'vendor_name'	=> NULL,
				'option'     	=> $option_data,
				'download'		=> $download_data,
				'quantity'   	=> $product['quantity'],
				'barcode'       => isset($product_option['barcode']) ? @$product_option['barcode'] : @$product_info['barcode'],
				'price'      	=> $this->currency->format(($product['quantity'] > 0 ? $product['price'] : ($product['price']*-1)) , '', 1, FALSE),
				'discount'   	=> '0.0000',
				'coupon'     	=> '0.0000',
				'special_price' => '0.0000',
				'general_discount'=> '0.0000',
				'total'      	=> $this->currency->format($product['extended'] , '', 1, FALSE),
				'tax'        	=>  $this->currency->format($product['tax_rate'] , '', 1, FALSE),
				'shipping'   	=> $product_info['shipping']
			);
		  }
		}

		return $product_data;
	}
	function get_totals(){
		if($this->request->gethtml('order_status','post')){
			$methods = array('totals_subtotal','totals_coupon','totals_discount','totals_shipping','totals_freeshipping','totals_tax','total_invoice');
		} else {
			$methods = array('subtotal', 'shipping', 'total_tax', 'total_invoice');
		}
		$totals = array();
		$currency = $this->request->gethtml('currency','post') ? $this->request->gethtml('currency','post') : $this->currency->getCode();
		$value = $this->request->gethtml('exchange_value','post') ? $this->request->gethtml('exchange_value','post') : $this->currency->getValue($currency);
		foreach ($methods as $key => $method){
			if($this->request->gethtml($method,'post')){
				$totals[] = array(
					'title'		=> $this->language->get('entry_' . $method),
					'text'		=> $this->currency->format($this->request->gethtml( $method,'post'),$currency,$value),
					'value'		=> $this->request->gethtml($method,'post'),
					'sort_order'=> $key
				);
			}
		}
		return $totals;
	}
	function format_products(){
		$products = $this->request->gethtml('products', 'post');
		$this->product_tax = 0;
		$currency = $this->request->gethtml('currency','post') ? $this->request->gethtml('currency','post') : $this->currency->getCode();
		$value = $this->request->gethtml('exchange_value','post') ? $this->request->gethtml('exchange_value','post') : $this->currency->getValue($currency);
		$taxed = $this->request->gethtml('taxed','post') ? $this->request->gethtml('taxed','post') : $this->config->get('config_tax');
		$this->product_extended_total = 0;
		$product_data = array();
		if(!$products){ return $product_data;}
		foreach($products as $key => $product){
			$this->product_tax += $product['tax_amount'];
			$this->product_extended_total += $product['extended'] + ($taxed ? $product['tax_amount'] : 0);
			$array = explode(':', $product['product_id']);
			$product_id = $array[0];
			if (isset($array[1])){
				$option_ids = explode('.', $array[1]);
			} else {
				$option_ids = array();
			}
			$product_info = $this->modelOrderEdit->get_product($product_id);
			$option_price = 0;
			$option_weight = 0;
      		$option_data = array();
			foreach ($option_ids as $product_to_option_id) {
				$option = $this->modelOrderEdit->get_product_to_option($product_id, $product_to_option_id);
				if ($option['prefix'] == '+') {
          			$option_price = $option_price + $option['price'];
        		} elseif ($option['prefix'] == '-') {
          			$option_price = $option_price - $option['price'];
        		}
				$temp_option_weight = $this->weight->convert($option['option_weight'],$option['option_weightclass_id'],$product_info['weight_class_id']);
				$option_weight = $option_weight + $temp_option_weight;
				$option_data[] = array(
          			'product_to_option_id' => $product_to_option_id,
          			'name'           => $option['name'],
          			'value'          => $option['value'],
          			'prefix'         => $option['prefix'],
          			'price'          => roundDigits($option['price'], $this->decimal_place),
					'option_weight'	 => $temp_option_weight,
					'option_weightclass_id'=> $option['option_weightclass_id']
        		);
			}
			if($option_ids){
				$product_option = $this->modelOrderEdit->product_with_options($product['product_id']);
			} else {
				$product_option = array();
			}


			$downloads = $this->modelOrderEdit->get_downloads($product_id);
			$download_data = array();
			foreach ($downloads as $download) {
        		$download_data[] = array(
          			'download_id' => $download['download_id'],
					'name'        => $download['name'],
					'filename'    => $download['filename'],
					'mask'        => $download['mask'],
					'remaining'   => $download['remaining']
        		);			
			}
			$vendor_data = $this->modelOrderEdit->get_vendor($product_id);
			
			if(!$this->request->gethtml('order_status','post')){
				$product['discount'] = 0;
				$product['special_price'] = 0;
				$product['general_discount'] = 0;
				$product['coupon'] = 0;
			}
			$tax = $product['extended'] > 0 ? $product['tax_amount'] : ($product['tax_amount'] * -1);
			
		  if($this->request->gethtml('order_status','post')){
			$product_data[] = array(
				'product_key'	=> $product['product_id'],
				'product_id' 	=> $product_id,
				'href'       	=> $this->url->href('product', FALSE, array('product_id' => $product_id)),
				'name'       	=> $product_info['name'],
				'model_number'  => (isset($product_option['model_number']) ? @$product_option['model_number'] : @$product_info['model_number']),
				'vendor_id'	=> $vendor_data['vendor_id'],
				'vendor_name'	=> $vendor_data['vendor_id'] !='0' ? $vendor_data['name'] : NULL,
				'option'     	=> $option_data,
				'download'		=> $download_data,
				'quantity'   	=> $product['quantity'],
				'barcode'       => isset($product_option['barcode']) ? @$product_option['barcode'] : @$product_info['barcode'],
				'price'      	=> $this->currency->format(($product['quantity'] > 0 ? $product['price'] : ($product['price']*-1)),$currency,$value),
				'discount'   	=> $product['discount'] != 0 ? $this->currency->format($product['discount'],$currency,$value) : NULL,
				'special_price' => $product['special_price'] != 0 ? $this->currency->format($product['special_price'],$currency,$value) : NULL,
				'general_discount'=> ($product['general_discount'] != 0 ? $this->currency->format($product['general_discount']*-1,$currency,$value) : NULL),
				'product_tax' => $this->currency->format(($product['total'] > 0) ? $product['tax_amount'] : ($product['tax_amount'] *-1) ,$currency,$value),
				'net'        	=> $this->currency->format($product['extended'],$currency,$value),
				'coupon'     	=> $product['coupon'] !=0 ? $this->currency->format($product['coupon']*-1,$currency,$value) : NULL,
				'total'      	=> $this->currency->format($product['total'],$currency,$value),
				'total_discounted' => $this->currency->format($product['extended'] + ($taxed ? 0 : $tax),$currency,$value),
				'tax'        	=> $this->currency->format($product['tax_rate'], '', 1, FALSE),
				'shipping'   	=> $product_info['shipping']
			);
		  } else {
			$product_data[] = array(
				'product_key'	=> $product['product_id'],
				'product_id' 	=> $product_id,
				'href'       	=> $this->url->href('product', FALSE, array('product_id' => $product_id)),
				'name'       	=> $product_info['name'],
				'model_number'  => (isset($product_option['model_number']) ? @$product_option['model_number'] : @$product_info['model_number']),
				'vendor_id'		=> NULL,
				'vendor_name'	=> NULL,
				'option'     	=> $option_data,
				'download'		=> $download_data,
				'quantity'   	=> $product['quantity'],
				'barcode'       => isset($product_option['barcode']) ? @$product_option['barcode'] : @$product_info['barcode'],
				'price'      	=> $this->currency->format($product['quantity'] > 0 ? $product['price'] : $product['price'] * -1 ,$currency,$value),
				'discount'   	=> NULL,
				'coupon'     	=> NULL,
				'special_price' => NULL,
				'general_discount'=> NULL,
				'net'        	=> $this->currency->format($product['extended'], $currency, $value),
				'total'      	=> $this->currency->format($product['extended'], $currency, $value),
				'product_tax' 	=> $this->currency->format($product['tax_amount'], $currency, $value),
				'total_discounted' => $this->currency->format($product['extended'] + $product['tax_amount'],$currency,$value),
				'tax'        	=> $this->currency->format($this->tax->getRate($product['tax_rate']), '', 1, FALSE),
				'shipping'   	=> $product_info['shipping']
			);
		  }
		}
		
		return $product_data;
	}
	
	function assign_order($products, $totals){
		$currency = $this->request->gethtml('currency','post');
		$value = $this->request->gethtml('exchange_value','post');
		if($this->request->gethtml('order_id','post')){
			$order_info = $this->modelOrderEdit->cr_get_order($this->request->gethtml('order_id','post'));
		}
		$this->order->set('customer_id', $this->request->gethtml('customer_id','post'));
		$this->order->set('firstname', $this->request->gethtml('payment_firstname','post'));
		$this->order->set('lastname', $this->request->gethtml('payment_lastname','post'));
		$this->order->set('email', $this->request->gethtml('email','post'));
		$this->order->set('telephone', $this->request->gethtml('telephone','post'));
		$this->order->set('fax', $this->request->gethtml('fax','post'));
		if($this->request->gethtml('order_status','post')){
			$this->order->set('order_status_id','12');
		} else {
			$this->order->set('order_status_id', $this->config->get('config_order_status_id'));
		}
		$this->order->set('total', $this->request->gethtml('total_invoice','post'));
		$this->order->set('currency', $this->request->gethtml('currency','post'));
		$this->order->set('value', $this->request->gethtml('exchange_value','post'));
		$this->order->set('ip', $_SERVER['REMOTE_ADDR']);
		$this->order->set('coupon_sort_order', (int)$this->request->gethtml('coupon_sort_order','post'));
		$this->order->set('discount_sort_order', (int)$this->request->gethtml('discount_sort_order','post'));
		if($this->request->gethtml('order_status','post')){
			$this->order->set('shipping_net', $this->request->gethtml('shipping','post'));
			$this->order->set('shipping_tax_rate', $this->request->gethtml('shipping_tax_rate','post'));
			$this->order->set('freeshipping_net', $this->request->gethtml('freeshipping_net','post'));
		} else {
			$this->order->set('shipping_net', $this->request->gethtml('shipping','post'));
			$this->order->set('shipping_tax_rate', $this->request->gethtml('shipping_tax_rate','post'));
			$this->order->set('freeshipping_net', 0);
		}
		
		$this->order->set('taxed', $this->request->gethtml('taxed','post') ? $this->request->gethtml('taxed','post') : $this->config->get('config_tax'));
		
		$this->order->set('shipping_firstname', $this->request->gethtml('shipping_firstname','post'));
		$this->order->set('shipping_lastname', $this->request->gethtml('shipping_lastname','post'));
		$this->order->set('shipping_company', $this->request->gethtml('shipping_company','post'));
		$this->order->set('shipping_address_1', $this->request->gethtml('shipping_address_1','post'));
		$this->order->set('shipping_address_2', $this->request->gethtml('shipping_address_2','post'));
		$this->order->set('shipping_city', $this->request->gethtml('shipping_city','post'));
		$this->order->set('shipping_postcode', $this->request->gethtml('shipping_postcode','post'));
		$this->order->set('shipping_zone', $this->request->gethtml('shipping_zone','post'));
		$this->order->set('shipping_country', $this->request->gethtml('shipping_country','post'));
		$this->order->set('shipping_address_format', NULL);
		
		$this->order->set('shipping_method', $this->request->gethtml('shipping_method','post'));
		
		$this->order->set('payment_firstname', $this->request->gethtml('payment_firstname','post'));
		$this->order->set('payment_lastname', $this->request->gethtml('payment_lastname','post'));
		$this->order->set('payment_company', $this->request->gethtml('payment_company','post'));
		$this->order->set('payment_address_1', $this->request->gethtml('payment_address_1','post'));
		$this->order->set('payment_address_2', $this->request->gethtml('payment_address_2','post'));
		$this->order->set('payment_city', $this->request->gethtml('payment_city','post'));
		$this->order->set('payment_zone', $this->request->gethtml('payment_zone','post'));
		$this->order->set('payment_country', $this->request->gethtml('payment_country','post'));
		$this->order->set('payment_postcode', $this->request->gethtml('payment_postcode','post'));
		$this->order->set('payment_method', $this->request->gethtml('payment_method','post'));
		$this->order->set('payment_address_format', $this->address->getFormat($this->session->get('payment_address_id')));
		$this->order->set('discount_total', '0.00');
		$this->order->set('coupon_id', FALSE); 
		if($this->request->gethtml('order_status','post')){
			$this->order->set('comment', $this->language->get('text_comment_cancelled') . " : " . $this->request->gethtml('order_reference','post'));
		} else {
			$this->order->set('comment', '');
		}
		$this->order->set('products', $products);
		$this->order->set('totals', $totals);
		
		$email = $this->locator->create('template');

		$email->set('email_greeting', $this->language->get('email_greeting', $this->request->gethtml('payment_firstname','post')));
		$email->set('email_thanks', $this->language->get('email_thanks', $this->config->get('config_store')));
		$email->set('email_order', $this->language->get('email_order', $this->order->getReference()));
		$email->set('email_date', $this->language->get('email_date', $this->language->formatDate($this->language->get('date_format_long'))));
		$email->set('email_invoice_number', $this->language->get('email_invoice_number'));
		$email->set('email_invoice', $this->language->get('email_invoice', $this->get_invoice(), $this->get_invoice()));
		$email->set('email_shipping_address', $this->language->get('email_shipping_address'));
		$email->set('email_shipping_method', $this->language->get('email_shipping_method'));
    	$email->set('email_email', $this->language->get('email_email'));
    	$email->set('email_telephone', $this->language->get('email_telephone'));
		$email->set('email_fax', $this->language->get('email_fax'));
    	$email->set('email_payment_address', $this->language->get('email_payment_address'));
    	$email->set('email_payment_method', $this->language->get('email_payment_method'));
		$email->set('email_comment', $this->language->get('email_comment'));
    	$email->set('email_thanks_again', $this->language->get('email_thanks_again', $this->config->get('config_store')));
    	$email->set('email_product', $this->language->get('email_product'));
    	$email->set('email_model_number', $this->language->get('email_model_number'));
		$email->set('email_soldby', $this->language->get('email_soldby'));
    	$email->set('email_quantity', $this->language->get('text_quantity'));
    	$email->set('email_price', $this->language->get('text_price'));
		$email->set('email_specialprice', $this->language->get('text_special'));
		$email->set('email_extended', $this->language->get('text_extended'));
		$email->set('email_coupon_value', $this->language->get('text_coupon_value'));
		$email->set('email_discount_value', $this->language->get('text_discount_value'));
		$email->set('email_net', $this->language->get('text_net'));
		$email->set('email_tax_rate', $this->language->get('text_tax_rate'));
		$email->set('email_tax_amount', $this->language->get('text_tax_amount'));
		$email->set('email_shipping', $this->language->get('text_shipping'));
    	$email->set('email_total', $this->language->get('email_total'));
		$email->set('tax_included', $this->request->gethtml('taxed','post') ? $this->request->gethtml('taxed','post') : $this->config->get('config_tax'));	
		$email->set('email_ship', $this->language->get('email_ship'));
		$email->set('email_noship', $this->language->get('email_noship'));
		$email->set('email_download', $this->language->get('email_download'));
		$email->set('text_currency', $this->language->get('text_currency'));
		$email->set('currency', $this->modelOrderEdit->get_currency());
	 	$email->set('text_product_totals', $this->language->get('text_product_totals'));
		$email->set('text_shipping_cost', $this->language->get('text_shipping_cost'));
		$email->set('text_free_shipping', $this->language->get('text_free_shipping'));
		$email->set('text_cart_totals', $this->language->get('text_cart_totals'));
		$email->set('text_tax', $this->language->get('text_tax'));
		$email->set('store', $this->config->get('config_store'));
		$store_address = $this->config->get('config_address');
		$email->set('store_address', str_replace(array("\r\n", "\r", "\n"), '<br>', $store_address));
		$email->set('text_soldby', $this->language->get('email_soldby'));
		$email->set('email', $this->request->gethtml('email','post'));
		$email->set('telephone', $this->request->gethtml('telephone','post'));
		$email->set('fax', $this->request->gethtml('fax','post'));
		
		$email->set('shipping_method', $this->request->gethtml('shipping_method','post'));
		$email->set('payment_method', $this->request->gethtml('payment_method','post'));
	
		if($this->request->gethtml('order_status','post')){
			$shipping_address = array();
			$shipping_address['firstname'] = $this->request->gethtml('shipping_firstname','post');
			$shipping_address['lastname'] = $this->request->gethtml('shipping_lastname','post');
			$shipping_address['company'] = $this->request->gethtml('shipping_company','post');
			$shipping_address['address_1'] = $this->request->gethtml('shipping_address_1','post');
			$shipping_address['address_2'] = $this->request->gethtml('shipping_address_2','post');
			$shipping_address['city'] = $this->request->gethtml('shipping_city','post');
			$shipping_address['postcode'] = $this->request->gethtml('shipping_postcode','post');
			$shipping_address['zone'] = $this->request->gethtml('shipping_zone','post');
			$shipping_address['country'] = $this->request->gethtml('shipping_country','post');
			if (array_filter($shipping_address)) {
				$email->set('shipping_address', $this->address->format($shipping_address, $order_info['shipping_address_format'], '<br />'));
			} else {
				$store_address = str_replace(array("\r\n", "\r", "\n"), '<br>', $this->config->get('warehouse_location') ? $this->config->get('warehouse_location') : $this->config->get('config_address'));
				$email->set('shipping_address', $this->config->get('config_store') . "<br />" . $store_address);
			}	
			$payment_address = array();
			$payment_address['firstname'] = $this->request->gethtml('payment_firstname','post');
			$payment_address['lastname'] = $this->request->gethtml('payment_lastname','post');
			$payment_address['company'] = $this->request->gethtml('payment_company','post');
			$payment_address['address_1'] = $this->request->gethtml('payment_address_1','post');
			$payment_address['address_2'] = $this->request->gethtml('payment_address_2','post');
			$payment_address['city'] = $this->request->gethtml('payment_city','post');
			$payment_address['postcode'] = $this->request->gethtml('payment_postcode','post');
			$payment_address['zone'] = $this->request->gethtml('payment_zone','post');
			$payment_address['country'] = $this->request->gethtml('payment_country','post');
			$email->set('payment_address', nl2br($this->address->format($payment_address, $order_info['payment_address_format'])));
			
			
			$email->set('shipping_net', $this->currency->format($this->request->gethtml('e_shipping_net','post'), $currency, $value));
			$email->set('shipping_tax', $this->currency->format($this->request->gethtml('e_shipping_tax','post'), $currency, $value));
			$email->set('shipping_total', $this->currency->format($this->request->gethtml('e_shipping_total','post'), $currency, $value));
			if($this->request->gethtml('e_freeshipping_total','post')){
				$email->set('freeshipping_net', $this->currency->format($this->request->gethtml('e_freeshipping_net','post'), $currency, $value));
				$email->set('freeshipping_tax', $this->currency->format($this->request->gethtml('e_freeshipping_tax','post'), $currency, $value));
				$email->set('freeshipping_total', $this->currency->format($this->request->gethtml('e_freeshipping_total','post'), $currency, $value));
			}
			$email->set('extended_total', $this->currency->format($this->request->gethtml('e_extended_total','post'), $currency, $value));
			$email->set('coupon_total', $this->currency->format($this->request->gethtml('e_coupon_total','post'), $currency, $value));
			$email->set('discount_total', $this->currency->format($this->request->gethtml('e_discount_total','post'), $currency, $value));
			$email->set('net_total', $this->currency->format($this->request->gethtml('e_net_total','post'), $currency, $value));
			$email->set('tax_total', $this->currency->format($this->request->gethtml('e_tax_total','post'), $currency, $value));
			$email->set('totals_total', $this->currency->format($this->request->gethtml('e_totals_total', 'post'), $currency, $value));
			$email->set('cart_net_total', $this->currency->format($this->request->gethtml('e_cart_net_total','post'), $currency, $value));
			$email->set('cart_tax_total', $this->currency->format($this->request->gethtml('e_cart_tax_total','post'), $currency, $value));
			$email->set('cart_totals_total', $this->currency->format($this->request->gethtml('e_cart_totals_total','post'), $currency, $value));
			$email->set('coupon_sort_order', $this->request->gethtml('coupon_sort_order','post'));
			$email->set('discount_sort_order', $this->request->gethtml('discount_sort_order','post'));
			$email->set('comment', $this->language->get('text_comment_cancelled') . " : " . $this->request->gethtml('order_reference','post'));
			$email->set('shipping_tax_rate', $this->currency->format($this->request->gethtml('shipping_tax_rate','post'), '', 1, FALSE) . '%');
		} else {
			$shipping_address = array();
			$shipping_address['firstname'] = $this->request->gethtml('shipping_firstname','post');
			$shipping_address['lastname'] = $this->request->gethtml('shipping_lastname','post');
			$shipping_address['company'] = $this->request->gethtml('shipping_company','post');
			$shipping_address['address_1'] = $this->request->gethtml('shipping_address_1','post');
			$shipping_address['address_2'] = $this->request->gethtml('shipping_address_2','post');
			$shipping_address['city'] = $this->request->gethtml('shipping_city','post');
			$shipping_address['postcode'] = $this->request->gethtml('shipping_postcode','post');
			$shipping_address['zone'] = $this->request->gethtml('shipping_zone','post');
			$shipping_address['country'] = $this->request->gethtml('shipping_country','post');
			$email->set('shipping_address', $this->address->format($shipping_address, $this->config->get('config_address'), '<br />'));
			$payment_address = array();
			$payment_address['firstname'] = $this->request->gethtml('payment_firstname','post');
			$payment_address['lastname'] = $this->request->gethtml('payment_lastname','post');
			$payment_address['company'] = $this->request->gethtml('payment_company','post');
			$payment_address['address_1'] = $this->request->gethtml('payment_address_1','post');
			$payment_address['address_2'] = $this->request->gethtml('payment_address_2','post');
			$payment_address['city'] = $this->request->gethtml('payment_city','post');
			$payment_address['postcode'] = $this->request->gethtml('payment_postcode','post');
			$payment_address['zone'] = $this->request->gethtml('payment_zone','post');
			$payment_address['country'] = $this->request->gethtml('payment_country','post');
			$email->set('payment_address', nl2br($this->address->format($payment_address, $this->config->get('config_address'))));
			
			
			$shipping_taxamount = $this->request->gethtml('shipping','post') * ($this->request->gethtml('shipping_tax_rate','post') / 100);
			$email->set('shipping_net', $this->currency->format($this->request->gethtml('shipping','post') +($this->config->get('config_tax') ? $shipping_taxamount : 0), '', 1, True));
			$email->set('shipping_tax', $this->currency->format($shipping_taxamount, '', 1, True));
			$email->set('shipping_total', $this->currency->format($this->request->gethtml('shipping','post') + $shipping_taxamount, '', 1, True));
			$email->set('shipping_address', $this->address->getFormatted($this->session->get('shipping_address_id'), '<br />'));
			$email->set('payment_address', $this->address->getFormatted($this->session->get('payment_address_id'), '<br />'));
			$email->set('extended_total', $this->currency->format($this->product_extended_total, '', 1, True));
			$email->set('coupon_total', NULL);
			$email->set('discount_total', NULL);
			$email->set('net_total', $this->currency->format($this->request->gethtml('subtotal','post') + ($this->config->get('config_tax') ? $this->product_tax : 0), '', 1, True));
			$email->set('tax_total', $this->currency->format($this->product_tax, '', 1, True));
			$email->set('totals_total', $this->currency->format($this->request->gethtml('subtotal','post') + $this->product_tax, '', 1, True));
			$cart_tax = $this->currency->format($this->product_tax, '', 1, FALSE);
			$email->set('cart_net_total', $this->currency->format($this->request->gethtml('subtotal','post') + $this->request->gethtml('shipping','post') + ($this->config->get('config_tax') ? $cart_tax + $shipping_taxamount: 0), '', 1, True));
			$email->set('cart_tax_total', $this->currency->format($cart_tax + $shipping_taxamount, '', 1, True));
			$email->set('cart_totals_total', $this->currency->format($this->request->gethtml('subtotal','post') + $this->request->gethtml('shipping','post') + $cart_tax + $shipping_taxamount, '', 1, True));
			$email->set('coupon_sort_order', '');
			$email->set('discount_sort_order', '');
			$email->set('comment', $this->session->get('comment'));
			$email->set('shipping_tax_rate', $this->currency->format($this->request->gethtml('shipping_tax_rate','post'), '', 1, FALSE) . '%');
		}

		$email->set('products', $this->format_products());
		$email->set('totals', $totals);
		$this->order->set('email_subject', $this->language->get('email_subject', $this->order->getReference()));
		$this->order->set('email_html', $email->fetch('content/checkout_email.tpl'));
		$this->order->set('email_text', $this->language->get('email_message', $this->config->get('config_store'), $this->order->getReference(), $this->get_invoice(), $this->language->formatDate($this->language->get('date_format_long')), strip_tags($this->session->get('comment'))));
	}
	function get_invoice(){
		if (($this->config->get('config_ssl')) && (defined('HTTPS_CATALOG')) && (HTTPS_CATALOG)) {
			$server = HTTPS_CATALOG;
		} else {
			$server = HTTP_CATALOG;
		}
		return $server . '?controller=account_invoice&reference=' . $this->order->getReference();
	}
	function get_payment_methods(){
		$methods = array();
		$results = $this->modelOrderEdit->get_payment_ext();
		foreach($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$extension_info = $this->modelOrderEdit->get_extension_info($result['extension_id']);
				$methods[] = array(
					'extension_id' 	=> $result['extension_id'],
					'name'			=> $extension_info['name'],
					'description'	=> $extension_info['description']
				);
			}
		}
		return $methods;
	}
	
	function get_shipping_methods(){
		$methods = array();
		$results = $this->modelOrderEdit->get_shipping_ext();
		foreach ($results as $result) {
			if($this->config->get($result['code'] . '_status')){
				$extension_info = $this->modelOrderEdit->get_extension_info($result['extension_id']);
				$methods[] = array(
					'extension_id' 	=> $result['extension_id'],
					'name'			=> $extension_info['name'],
					'description'	=> $extension_info['description']
				);
			}
		}
		return $methods;
	}
	
	function get_product(){
		$array = explode(':', $this->request->gethtml('product_id'));
		$product_id = $array[0];
		if (isset($array[1])){
			$option_ids = explode('.', $array[1]);
			$option_model_number = $this->modelOrderEdit->get_model_number($this->request->gethtml('product_id'));
		} else {
			$option_ids = array();
		}
		$product_o = $this->request->gethtml('product_id');
		$product = $this->modelOrderEdit->get_product($product_id);
		
		$product_options = $this->modelOrderEdit->check_options($product_id);

		$vendor_data = $this->modelOrderEdit->get_vendor($product_id);

		if($product_options && !$option_ids || !$this->request->has('row')){
			$options = $this->modelOrderEdit->get_options($product_id, $product['tax_class_id']);
			$output = '<tr><td>' . $product['name'] . '</td></tr>';
			foreach($options as $key => $option){
				$output .= '<tr>';
				$output .= '<td>' . $option['name'] . '</td>';
				$output .= '<td><select name="option[' . ($key+1) . ']">';
				foreach($option['value'] as $option_value){
					$output .= '<option value="' . $option_value['product_to_option_id'] . '">';
					$output .= $option_value['name'];
					if($option_value['price']){
						$output .= $option_value['prefix'] . $this->currency->format($option_value['price'], '', '', FALSE);
					}
					$output .= '</option>';
				}
				$output .= '</select></td></tr>';
			}
			$output .= '<tr><td><input id="product_id" type="hidden" value="' . $product_id . '"></td></tr>';
			$output .= '<tr><td><input type="button" class="button" value="' . $this->language->get('button_submit') . '" onclick="add_product($(\'#product_id\').val())"></td></tr>';
			$output .= '<tr><td colspan="2"><hr></td></tr>';
		} else {
			$option_values = array();
			$option_price = 0;
			if($product_options){
				foreach($option_ids as $key => $option_id){
					$option_values[$key] = $this->modelOrderEdit->get_product_option($option_id, $product_id);
					if ($option_values[$key]['prefix'] == '+') {
						$option_price = $option_price + $this->currency->format($option_values[$key]['price'], '', '', FALSE);
					} elseif ($option_values[$key]['prefix'] == '-') {
						$option_price = $option_price - $this->currency->format($option_values[$key]['price'], '', '', FALSE);
					}
				}
				
			}
			$price = number_format(($this->currency->format($product['price'], '', '', FALSE) + $option_price),4,'.','');
			$tax = $this->tax->getRate($product['tax_class_id']);
			if($product['shipping']){
				$weight = $this->weight->convert($product['weight'],$product['weight_class_id'], $this->config->get('config_weight_class_id'));
			} else {
				$weight = 0;
			}
			$row = $this->request->gethtml('row');
			$output = '<tr id=row[' . $row . ']>'. "\n";
			$output .= '<input name="products[' . $row . '][product_id]" value="' . $product_o . '" type="hidden">'. "\n";
			$output .= '<input name="products[' . $row . '][weight]" value="' . $weight . '" id="weight_' . $row . '" type="hidden">'. "\n";
			$output .= '<td class="left" style="width: 200px;">' . $product['name'];
			if($option_values){
				foreach($option_values as $option_value){
					$output .= '<br><small>' . ' - ' . $option_value['name'] . ' : ' . $option_value['value'] . '</small>';
				}
				if($option_model_number['model_number']){
					$output .= '<br><small>(' . $this->language->get('text_model_number') . ' '.$option_model_number['model_number'] . ')</small>';
				}
			} else {
				if($product['model_number']){
						$output .= '<br><small>(' . $this->language->get('text_model_number') . ' '.$product['model_number'] . ')</small>';
				}
			}
			if($vendor_data['name']){
					$output .= '<br><span class="vendor">' . $this->language->get('text_soldby') . '<br>' . $vendor_data['name'] . '</span>';
			}

			$output .= '</td>'. "\n";
			$output .= '<td class="left" style="width: 100px;">' . '<input class="validate_int_n" name="products[' . $row . '][quantity]" size="6" value="1" id="quantity_' . $row . '" onfocus="RegisterValidation()" onchange="update_row(' . $row . ')"></td>'. "\n"; 
			$output .= '<td class="left" style="width: 120px;">' . '<input class="validate_float" name="products[' . $row . '][price]" size="12" value="' . $price . '" size="12" id="price_' . $row . '" onfocus="RegisterValidation()" onchange="update_row(' . $row . ')"></td>'. "\n";			
			$output .= '<td class="left" style="width: 100px;">' . '<input name="products[' . $row . '][tax_rate]" size="10" value="' . $tax . '" id="tax_rate_' . $row . '" readonly="readonly" style="border: 0px;"></td>'. "\n";
			$output .= '<td class="left" style="width: 120px;">' . '<input name="products[' . $row . '][tax_amount]" size="10" value="" id="tax_amount_' . $row . '" readonly="readonly" style="border: 0px;"></td>'. "\n";			
			$output .= '<td class="left" style="width: 200px;">' . '<input name="products[' . $row . '][extended]" size="12" value="' . $price . '" id="extended_' . $row . '" size="12"  readonly="readonly" style="border: 0px;"></td>'. "\n"; 
			
			
			$output .= '</td></tr>'. "\n";
		}
		$this->response->set($output);
	}
	
	function get_products(){
		$products = $this->modelOrderEdit->get_products();
		$output = '<script type="text/javascript" src="javascript/preview/preview.js"></script>';
		$output .= '<tr><td>';
		$output .= '<select id="get_products" onchange="' . "$('#product_select').load('index.php?controller=order_edit&action=get_product&product_id='+this.value);" . '">';
		$output .= '<option value="0" title="">' . $this->language->get('entry_select_product') . '</option>';
		foreach ($products as $product){
			$output .= '<option title="'. $this->image->resize($product['filename'], $this->config->get('config_image_width'), $this->config->get('config_image_height')) .'" value="' . $product['product_id'] . '">' . $product['name'] . '</option>';
		}
		$output .= '</select>';
		$output .= '</td></tr>';
		$output .= '<tr><td colspan="2"><hr></td></tr>';
		$this->response->set($output);
	}
	
	function get_phone(){
		$customer_id = (int)$this->request->gethtml('customer_id');
		$result = $this->modelOrderEdit->get_telephone($customer_id);
		$output = '<b>' . $this->language->get('text_telephone') . '</b><br>';
		$output .= '<input name="telephone" value="' . $result['telephone'] . '" readonly="readonly"><br>';
		$output .= '<b>' . $this->language->get('text_fax') . '</b><br>';
		$output .= '<input name="fax" value="' . $result['fax'] . '" readonly="readonly">';
		
		$this->response->set($output);
	}
	
	function get_email(){
		$customer_id = (int)$this->request->gethtml('customer_id');
		$email = $this->modelOrderEdit->get_email($customer_id);
		$output = '<b>' . $this->language->get('text_email') . '</b><br>';
		$output .= '<input name="email" size="30" value="' . $email . '" readonly="readonly"><br>';
		
		$this->response->set($output);
	}
	
	function get_shipping_address(){
		$address_id = (int)$this->request->gethtml('address_id');
		$this->session->set('shipping_address_id', $address_id);
		$result = $this->modelOrderEdit->get_shipping_address($address_id);
		$output = $this->shipping_address_fields($result);
		$this->response->set($output);
	}
	
	function shipping_address_fields($address){
		$output = '<b>' . $this->language->get('text_shipping_address') . '</b><br>';
		$output .= '<input name="shipping_firstname" value="' . $address['firstname'] . '" readonly="readonly">';
		$output .= '<input name="shipping_lastname" value="' . $address['lastname'] . '" readonly="readonly"><br>';
		$output .= '<input name="shipping_company" value="' . $address['company'] . '" readonly="readonly"><br>';
		$output .= '<input name="shipping_address_1" value="' . $address['address_1'] . '" readonly="readonly"><br>';
		$output .= '<input name="shipping_address_2" value="' . $address['address_2'] . '" readonly="readonly"><br>';
		$output .= '<input name="shipping_city" value="' . $address['city'] . '" readonly="readonly">';
		$output .= '<input name="shipping_zone" value="' . $address['zone'] . '" readonly="readonly"><br>';
		$output .= '<input name="shipping_country" value="' . $address['country'] . '" readonly="readonly">';
		$output .= '<input name="shipping_postcode" value="' . $address['postcode'] . '" readonly="readonly"><br>';
		return $output;
	}
	
	function get_shipping_addresses(){
		$customer_id = (int)$this->request->gethtml('customer_id');
		$results = $this->modelOrderEdit->get_addresses($customer_id);
		if(count($results) > 1){
			$output = '<select id="customer_shipping" onchange="' . "$('#shipping_address').load('index.php?controller=order_edit&action=get_shipping_address&address_id='+this.value);" . '">';
			$output .= '<option value="0">' . $this->language->get('entry_address') . '</option>';
			foreach($results as $result){
				$output .= '<option value="' . $result['address_id'] . '">' . $result['firstname'] . ' ' . $result['lastname'] . ' ' . $result['address_1'] . ' ' . $result['city'] . '</option>';
			}
			$output .= '</select>';
		} else {
			$output = $this->shipping_address_fields($results[0]);
		}
		
		
		$this->response->set($output);
	}
	
	function get_customer(){
		$customer_id = 	(int)$this->request->gethtml('customer_id');
		$this->session->set('customer_id', $customer_id);
		$customer = $this->modelOrderEdit->get_customer($customer_id);
		$this->session->set('payment_address_id', $customer['address_id']);
		if($customer){
			$output = '<b>' . $this->language->get('text_payment_address') . '</b><br>';
			$output .= '<input name="payment_firstname" value="' . $customer['firstname'] . '" readonly="readonly">';
			$output .= '<input name="payment_lastname" value="' . $customer['lastname'] . '" readonly="readonly"><br>';
			$output .= '<input name="payment_company" value="' . $customer['company'] . '" readonly="readonly"><br>';
			$output .= '<input name="payment_address_1" value="' . $customer['address_1'] . '" readonly="readonly"><br>';
			$output .= '<input name="payment_address_2" value="' . $customer['address_2'] . '" readonly="readonly"><br>';
			$output .= '<input name="payment_city" value="' . $customer['city'] . '" readonly="readonly">';
			$output .= '<input name="payment_zone" value="' . $customer['zone'] . '" readonly="readonly"><br>';
			$output .= '<input name="payment_country" value="' . $customer['country'] . '" readonly="readonly">';
			$output .= '<input name="payment_postcode" value="' . $customer['postcode'] . '" readonly="readonly"><br>';
			$output .= '<input name="customer_id" id="customer_id" value="' . $customer['customer_id'] . '" type="hidden">';
		} else {
			$output = '';
		}
		
		$this->response->set($output);
	}
	function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}
	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'order_edit')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>

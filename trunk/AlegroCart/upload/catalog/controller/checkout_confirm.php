<?php // Checkout Confirm AlegroCart
class ControllerCheckoutConfirm extends Controller { 
	var $error = array();
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->address  	=& $locator->get('address');
		$this->calculate 	=& $locator->get('calculate');
		$this->cart     	=& $locator->get('cart');
		$this->config  		=& $locator->get('config');
		$this->coupon   	=& $locator->get('coupon');
		$this->currency  	=& $locator->get('currency');
		$this->customer 	=& $locator->get('customer');
		$this->head_def 	=& $locator->get('HeaderDefinition');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->order     	=& $locator->get('order');
		$this->payment   	=& $locator->get('payment');
		$this->response 	=& $locator->get('response');
		$this->request  	=& $locator->get('request');
		$this->session  	=& $locator->get('session');
		$this->shipping  	=& $locator->get('shipping');
		$this->tax       	=& $locator->get('tax');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->validate 	=& $locator->get('validate');
		$this->modelCore 	= $model->get('model_core');
		$this->modelCheckout = $model->get('model_checkout');
		$this->tpl_manager 	= $this->modelCore->get_tpl_manager('checkout_confirm'); // Template Manager
		$this->locations 	= $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns 	= $this->modelCore->get_columns();// Template Manager
	}

	function index() {
    	if (!$this->customer->isLogged()) {
			$this->session->set('redirect', $this->url->ssl('checkout_shipping'));

	  		$this->response->redirect($this->url->ssl('account_login'));
    	}

    	if ((!$this->cart->hasProducts()) || ((!$this->cart->hasStock()) && (!$this->config->get('config_stock_checkout')))) {
	  		$this->response->redirect($this->url->ssl('cart'));
    	}

    	if ($this->cart->hasShipping()) {
			if (!$this->session->get('shipping_method')) {
	  			$this->response->redirect($this->url->ssl('checkout_shipping'));
    		}

			if (!$this->shipping->getQuote($this->session->get('shipping_method'))) {
	  			$this->response->redirect($this->url->ssl('checkout_shipping'));
			}

    		if (!$this->session->get('shipping_address_id')) {
	  			$this->response->redirect($this->url->ssl('checkout_address', 'shipping'));
    		}
		} else {
			$this->session->delete('shipping_address_id');
			$this->session->delete('shipping_method');
		}

		if (!$this->session->get('payment_method')) {
	  		$this->response->redirect($this->url->ssl('checkout_payment'));
    	}

    	if (!$this->payment->hasMethod($this->session->get('payment_method'))) {
	  		$this->response->redirect($this->url->ssl('checkout_payment')); 
    	}

    	if (!$this->address->has($this->session->get('payment_address_id'))) { 
	  		$this->response->redirect($this->url->ssl('checkout_address', 'payment'));
    	}

		$this->language->load('controller/checkout_confirm.php');

    	$this->template->set('title', $this->language->get('heading_title')); 
		
		if (($this->request->isPost()) && ($this->validate()) && $this->request->has('agreed')) {
			$this->session->set('message', $this->language->get('text_coupon'));
			
			$this->response->redirect($this->url->ssl('checkout_confirm'));
		}

		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
    	$view->set('heading_title', $this->language->get('heading_title'));

    	$view->set('text_shipping_address', $this->language->get('text_shipping_address'));
    	$view->set('text_shipping_method', $this->language->get('text_shipping_method'));
    	$view->set('text_payment_address', $this->language->get('text_payment_address'));
    	$view->set('text_payment_method', $this->language->get('text_payment_method'));
    	$view->set('text_your_comments', $this->language->get('text_your_comments'));
    	$view->set('text_change', $this->language->get('text_change'));
    	$view->set('text_product', $this->language->get('text_product'));
    	$view->set('text_model_number', $this->language->get('text_model_number'));
    	$view->set('text_quantity', $this->language->get('text_quantity'));
    	$view->set('text_price', $this->language->get('text_price'));
		$view->set('text_special', $this->language->get('text_special'));
    	$view->set('text_total', $this->language->get('text_total'));
		$view->set('text_cart_totals', $this->language->get('text_cart_totals'));
		$view->set('text_product_totals', $this->language->get('text_product_totals'));
		$view->set('text_extended', $this->language->get('text_extended'));
		$view->set('text_coupon_value', $this->language->get('text_coupon_value'));
		$view->set('text_discount_value', $this->language->get('text_discount_value'));
		$view->set('text_tax_rate', $this->language->get('text_tax_rate'));
		$view->set('text_tax_amount', $this->language->get('text_tax_amount'));
		$view->set('text_net', $this->language->get('text_net'));
		$view->set('text_tax', $this->language->get('text_tax'));
		$view->set('text_shipping_cost', $this->language->get('text_shipping_cost'));
		$view->set('text_free_shipping', $this->language->get('text_free_shipping'));
		$view->set('text_shipping', $this->language->get('text_shipping'));
		$view->set('text_shippable', $this->language->get('text_shippable'));
		$view->set('text_non_shippable', $this->language->get('text_non_shippable'));
		$view->set('text_warehouse_pickup', $this->language->get('text_warehouse_pickup'));
		$view->set('text_currency', $this->language->get('text_currency'));
		$view->set('text_downloadable', $this->language->get('text_downloadable'));

		$view->set('entry_coupon', $this->language->get('entry_coupon'));

    	$view->set('button_continue', $this->language->get('button_continue'));
		$view->set('button_back', $this->language->get('button_back'));
    	$view->set('button_update', $this->language->get('button_update'));

		$view->set('error', @$this->error['message']);

		if ($this->session->has('error')) {
            $view->set('error', $this->session->get('error'));
            $this->session->delete('error');
        }

		$view->set('action', $this->url->ssl('checkout_confirm'));

		if ($this->request->has('coupon', 'post')) {
			$view->set('coupon', $this->request->gethtml('coupon', 'post'));
		} else {
			$view->set('coupon', $this->coupon->getCode());
		}

    	$view->set('message', $this->session->get('message'));
    
		$this->session->delete('message');
        
        $this->session->delete('payment_form_enctype');

		$view->set('payment_url', $this->payment->getActionUrl($this->session->get('payment_method')));

        if ($this->session->get('payment_form_enctype')) {
			$view->set('payment_form_enctype', $this->session->get('payment_form_enctype'));
		}

    	if ($this->session->get('shipping_method') != 'warehouse_warehouse') {
	$view->set('shipping_address', $this->address->getFormatted($this->session->get('shipping_address_id'), '<br />'));
	} else {
	$store_address = str_replace(array("\r\n", "\r", "\n"), '<br>', $this->config->get('warehouse_location') ? $this->config->get('warehouse_location') : $this->config->get('config_address'));
	$view->set('shipping_address', $this->config->get('config_store') . "<br />" . $store_address);
	}

	$view->set('shipping_method', $this->shipping->getDescription($this->session->get('shipping_method')));
		
    	$view->set('checkout_shipping', $this->url->ssl('checkout_shipping'));

    	$view->set('checkout_shipping_address', $this->url->ssl('checkout_address', 'shipping'));
		
	$view->set('payment_address', $this->address->getFormatted($this->session->get('payment_address_id'), '<br />'));
    
    	$view->set('payment_method', $this->payment->getTitle($this->session->get('payment_method')));
	
    	$view->set('checkout_payment', $this->url->ssl('checkout_payment'));

    	$view->set('checkout_payment_address', $this->url->ssl('checkout_address', 'payment'));
		
		$currency = $this->modelCheckout->get_currency();
		$view->set('currency', $currency);
		
		$totals = $this->calculate->getTotals();
		if (!$this->cart->moreThanMinov($this->cart->getNetTotal())){
			$this->response->redirect($this->url->ssl('cart'));
		}
		$this->decimal_place = $this->currency->currencies[$this->currency->code]['decimal_place'];
    	$product_data = array();
		
		$tax_total = 0;
		$extended_total = 0;
		$coupon_total = NULL;
		$discount_total = 0;
		$net_total = 0;
		$totals_total = 0;
		
    	foreach ($this->cart->getProducts() as $product) {
      		$option_data = array();

      		foreach ($product['option'] as $option) {
        		$option_data[] = array(
          			'name'  => $option['name'],
          			'value' => $option['value']
        		);
      		}
			$view->set('hasnoshipping', $this->cart->hasNoShipping());
			$tax_total += $product['product_tax'];
			$extended_total += $this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax'));
			$coupon_total += $product['coupon'] ? $product['coupon'] : NULL;
			$discount_total += $product['general_discount'] ? $product['general_discount'] : NULL;
			$net_total += $product['total_discounted'] + ($this->config->get('config_tax') ? $product['product_tax'] : 0);
			$totals_total += $product['total_discounted'] + $product['product_tax'];
			$special_price = $product['special_price'] ? $product['special_price'] - $product['discount'] : 0 ;
      		$product_data[] = array(
				'product_id' => $product['product_id'],
        		'href'       => $this->url->href('product', FALSE, array('product_id' => $product['product_id'])),
        		'name'       => $product['name'],
        		'model_number'=> $product['model_number'],
				'shipping'   => ($this->session->get('shipping_method') == 'warehouse_warehouse' ? FALSE : $product['shipping']),
				'download'   => $product['download'],
        		'option'     => $option_data,
        		'quantity'   => $product['quantity'],
				'tax'        => round($this->tax->getRate($product['tax_class_id']), $this->decimal_place),
        		'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
				'base_price' => $this->currency->format($product['price']),
				'special_price' => $special_price ? $this->currency->format($this->tax->calculate($special_price, $product['tax_class_id'], $this->config->get('config_tax'))) : NULL,
				'base_special_price' => $special_price ? $this->currency->format($special_price) : NULL,
				'discount'   => ($product['discount'] ? $this->currency->format($this->tax->calculate($product['price'] - $product['discount'], $product['tax_class_id'], $this->config->get('config_tax'))) : NULL),
				'coupon'     =>  ($product['coupon'] ? '-' . $this->currency->format($product['coupon']) : NULL),
				'general_discount' => ($product['general_discount'] ? '-' . $this->currency->format($product['general_discount']) : NULL),
				'net'        => $this->currency->format($product['total_discounted'] + ($this->config->get('config_tax') ? $product['product_tax'] : 0)),
				'product_tax' => $this->currency->format($product['product_tax']),
				'total_discounted' => $this->currency->format($product['total_discounted'] + $product['product_tax']),
        		'total'      => $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')))
				
      		); 
    	}
		
		if ($this->cart->hasShipping()) {
			$shipping_net = $this->shipping->getCost($this->session->get('shipping_method'));
			$shipping_tax = roundDigits($shipping_net /100 * $this->tax->getRate($this->shipping->getTaxClassId($this->session->get('shipping_method'))),$this->decimal_place);
			$shipping_total = $shipping_net + $shipping_tax;
			$shipping_tax_rate = round($this->tax->getRate($this->shipping->getTaxClassId($this->session->get('shipping_method'))), $this->decimal_place);
			$view->set('shipping_tax_rate', $shipping_tax_rate . '%');
			$view->set('shipping_net', $this->currency->format($shipping_net));
			$view->set('shipping_tax', $this->currency->format($shipping_tax));
			$view->set('shipping_total', $this->currency->format($shipping_total));
			if($this->coupon->getShipping()){
				$freeshipping_net = $shipping_net;
				$freeshipping_tax = $shipping_tax;
				$freeshipping_total = $shipping_total;
				$view->set('freeshipping_net', '-' . $this->currency->format($freeshipping_net));
				$view->set('freeshipping_tax', '-' . $this->currency->format($freeshipping_tax));
				$view->set('freeshipping_total', '-' . $this->currency->format($freeshipping_total));
			}
		}
		
		$cart_net_total = $net_total + (isset($shipping_net) ? $shipping_net : 0) - (isset($freeshipping_net) ? $freeshipping_net : 0);
		$cart_tax_total = $tax_total + (isset($shipping_tax) ? $shipping_tax : 0) - (isset($freeshipping_tax) ? $freeshipping_tax : 0);
		$cart_totals_total = $totals_total + (isset($shipping_total) ? $shipping_total : 0) - (isset($freeshipping_total) ? $freeshipping_total : 0);
		
		$coupon_sort_order = $this->config->get('coupon_sort_order');
		$discount_sort_order = $this->config->get('discount_sort_order');
		
		$view->set('tax_included', $this->config->get('config_tax'));
    	$view->set('products', $product_data);
		$view->set('columns', $this->tpl_columns);

		$view->set('extended_total', $this->currency->format($extended_total));
		$view->set('coupon_total', $coupon_total ? '-' . $this->currency->format($coupon_total) : NULL);
		$view->set('discount_total', $discount_total ? '-' . $this->currency->format($discount_total) : NULL);
		$view->set('net_total', $this->currency->format($net_total));
		$view->set('tax_total', $this->currency->format($tax_total));
		$view->set('totals_total', $this->currency->format($totals_total));
		
		$view->set('cart_net_total', $this->currency->format($cart_net_total));
		$view->set('cart_tax_total', $this->currency->format($cart_tax_total));
		$view->set('cart_totals_total', $this->currency->format($cart_totals_total));
		
		$view->set('totals', $totals);
		
		$view->set('coupon_sort_order', $coupon_sort_order);
		$view->set('discount_sort_order', $discount_sort_order);

		$view->set('comment', $this->session->get('comment'));
		$view->set('back', $this->url->ssl('checkout_payment'));

		$this->order->set('customer_id', $this->customer->getId());
		$this->order->set('firstname', $this->customer->getFirstName());
		$this->order->set('lastname', $this->customer->getLastName());
		$this->order->set('email', $this->customer->getEmail());
		$this->order->set('telephone', $this->customer->getTelephone());
		$this->order->set('fax', $this->customer->getFax());
		$this->order->set('order_status_id', $this->config->get('config_order_status_id'));
		$this->order->set('total', $this->cart->getTotal());
		$this->order->set('currency', $this->currency->getCode());
		$this->order->set('value', $this->currency->getValue($this->currency->getCode()));
		$this->order->set('ip', $_SERVER['REMOTE_ADDR']);
		$this->order->set('coupon_sort_order', $coupon_sort_order);
		$this->order->set('discount_sort_order', $discount_sort_order);
		$this->order->set('shipping_net', isset($shipping_net) ? $shipping_net : 0);
		$this->order->set('shipping_tax_rate', isset($shipping_tax_rate) ? $shipping_tax_rate : 0 );
		$this->order->set('freeshipping_net', isset($freeshipping_net) ? $freeshipping_net : 0 );
		$this->order->set('taxed',$this->config->get('config_tax'));

		$this->order->set('shipping_firstname', $this->session->get('shipping_method') != 'warehouse_warehouse' ? $this->address->getFirstName($this->session->get('shipping_address_id')) : NULL);
		$this->order->set('shipping_lastname', $this->session->get('shipping_method') != 'warehouse_warehouse' ? $this->address->getLastName($this->session->get('shipping_address_id')) : NULL);
		$this->order->set('shipping_company', $this->session->get('shipping_method') != 'warehouse_warehouse' ? $this->address->getCompany($this->session->get('shipping_address_id')) : NULL);
		$this->order->set('shipping_address_1', $this->session->get('shipping_method') != 'warehouse_warehouse' ? $this->address->getAddress1($this->session->get('shipping_address_id')) : NULL);
		$this->order->set('shipping_address_2', $this->session->get('shipping_method') != 'warehouse_warehouse' ? $this->address->getAddress2($this->session->get('shipping_address_id')) :NULL);
		$this->order->set('shipping_city', $this->session->get('shipping_method') != 'warehouse_warehouse' ? $this->address->getCity($this->session->get('shipping_address_id')) : NULL);
		$this->order->set('shipping_postcode', $this->session->get('shipping_method') != 'warehouse_warehouse' ? $this->address->getPostCode($this->session->get('shipping_address_id')) : NULL);
		$this->order->set('shipping_zone', $this->session->get('shipping_method') != 'warehouse_warehouse' ? $this->address->getZone($this->session->get('shipping_address_id')) : NULL);
		$this->order->set('shipping_country', $this->session->get('shipping_method') != 'warehouse_warehouse' ? $this->address->getCountry($this->session->get('shipping_address_id')) : NULL);
		$this->order->set('shipping_address_format', $this->session->get('shipping_method') != 'warehouse_warehouse' ? $this->address->getFormat($this->session->get('shipping_address_id')) : NULL);

		$this->order->set('shipping_method', $this->shipping->getDescription($this->session->get('shipping_method')));

		$this->order->set('payment_firstname', $this->address->getFirstName($this->session->get('payment_address_id')));
		$this->order->set('payment_lastname', $this->address->getLastName($this->session->get('payment_address_id')));
		$this->order->set('payment_company', $this->address->getCompany($this->session->get('payment_address_id')));
		$this->order->set('payment_address_1', $this->address->getAddress1($this->session->get('payment_address_id')));
		$this->order->set('payment_address_2', $this->address->getAddress2($this->session->get('payment_address_id')));
		$this->order->set('payment_city', $this->address->getCity($this->session->get('payment_address_id')));
		$this->order->set('payment_postcode', $this->address->getPostCode($this->session->get('payment_address_id')));
		$this->order->set('payment_zone', $this->address->getZone($this->session->get('payment_address_id')));
		$this->order->set('payment_country', $this->address->getCountry($this->session->get('payment_address_id')));
		$this->order->set('payment_address_format', $this->address->getFormat($this->session->get('payment_address_id')));
	
		$this->order->set('payment_method', $this->payment->getTitle($this->session->get('payment_method')));
	
		$email = $this->locator->create('template');

		$email->set('email_greeting', $this->language->get('email_greeting', $this->customer->getFirstName()));
    	$email->set('email_thanks', $this->language->get('email_thanks', $this->config->get('config_store')));
    	$email->set('email_order', $this->language->get('email_order', $this->order->getReference()));
   		$email->set('email_date', $this->language->get('email_date', $this->language->formatDate($this->language->get('date_format_long'))));
		$email->set('email_invoice_number', $this->language->get('email_invoice_number'));
    	$email->set('email_invoice', $this->language->get('email_invoice', $this->url->ssl('account_invoice', FALSE, array('reference' => $this->order->getReference())), $this->url->ssl('account_invoice', FALSE, array('reference' => $this->order->getReference()))));
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
		$email->set('tax_included', $this->config->get('config_tax'));
		$email->set('email_ship', $this->language->get('email_ship'));
		$email->set('email_noship', $this->language->get('email_noship'));
		$email->set('email_download', $this->language->get('email_download'));
		$email->set('text_currency', $this->language->get('text_currency'));
		
		if ($this->cart->hasShipping()){
			$email->set('shipping_net', $this->currency->format($shipping_net));
			$email->set('shipping_tax', $this->currency->format($shipping_tax));
			$email->set('shipping_total', $this->currency->format($shipping_total));
			$email->set('shipping_tax_rate', $shipping_tax_rate . '%');
			if($this->coupon->getShipping()){
				$email->set('freeshipping_net', '-' . $this->currency->format($freeshipping_net));
				$email->set('freeshipping_tax', '-' . $this->currency->format($freeshipping_tax));
				$email->set('freeshipping_total', '-' . $this->currency->format($freeshipping_total));
			}
		}
		
		$email->set('currency', $currency);
	 	$email->set('text_product_totals', $this->language->get('text_product_totals'));
		$email->set('text_shipping_cost', $this->language->get('text_shipping_cost'));
		$email->set('text_free_shipping', $this->language->get('text_free_shipping'));
		$email->set('text_cart_totals', $this->language->get('text_cart_totals'));
		$email->set('text_tax', $this->language->get('text_tax'));
		$email->set('store', $this->config->get('config_store'));
		$email->set('email', $this->customer->getEmail());
		$email->set('telephone', $this->customer->getTelephone());
		$email->set('fax', $this->customer->getFax());

		if ($this->session->get('shipping_method') != 'warehouse_warehouse') {
			$email->set('shipping_address', $this->address->getFormatted($this->session->get('shipping_address_id'), '<br />'));
		} else {
			$store_address = str_replace(array("\r\n", "\r", "\n"), '<br>', $this->config->get('warehouse_location') ? $this->config->get('warehouse_location') : $this->config->get('config_address'));
			$email->set('shipping_address', $this->config->get('config_store') . "<br />" . $store_address);
		}

		$email->set('shipping_method', $this->shipping->getDescription($this->session->get('shipping_method')));
		$email->set('payment_address', $this->address->getFormatted($this->session->get('payment_address_id'), '<br />'));
		$email->set('payment_method', $this->payment->getTitle($this->session->get('payment_method')));
		$email->set('products', $product_data);
		$email->set('totals', $totals);
		$email->set('extended_total', $this->currency->format($extended_total));
		$email->set('coupon_total', $coupon_total ? '-' . $this->currency->format($coupon_total) : NULL);
		$email->set('discount_total', $discount_total ? '-' . $this->currency->format($discount_total) : NULL);
		$email->set('net_total', $this->currency->format($net_total));
		$email->set('tax_total', $this->currency->format($tax_total));
		$email->set('totals_total', $this->currency->format($totals_total));
		$email->set('coupon_sort_order', $coupon_sort_order);
		$email->set('discount_sort_order', $discount_sort_order);
		
		$email->set('cart_net_total', $this->currency->format($cart_net_total));
		$email->set('cart_tax_total', $this->currency->format($cart_tax_total));
		$email->set('cart_totals_total', $this->currency->format($cart_totals_total));
		$email->set('comment', $this->session->get('comment'));
    
		$product_data = array();
	
		foreach ($this->cart->getProducts() as $product) {
      		$option_data = array();

      		foreach ($product['option'] as $option) {
        		$option_data[] = array(
          			'name'   => $option['name'],
          			'value'  => $option['value'],
		  			'prefix' => $option['prefix']
        		);
      		}
			
      		$product_data[] = array(
				'product_key'=> $product['key'],
        		'product_id' => $product['product_id'],
				'name'       => $product['name'],
        		'model_number' => $product['model_number'],
        		'option'     => $option_data,
				'download'   => $product['download'],
				'quantity'   => $product['quantity'],
				'barcode'    => $product['barcode'],
				'price'      => $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')),
				'discount'   => $product['discount'] ? $this->tax->calculate($product['price'] - $product['discount'], $product['tax_class_id'], $this->config->get('config_tax')) : NULL,
				'special_price'  => $product['special_price'] ? $this->tax->calculate($product['special_price'], $product['tax_class_id'], $this->config->get('config_tax')) : 0 ,
				'coupon'   => $product['coupon'],
				'general_discount'   => $product['general_discount'],
        		'total'      => $this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')),
				'tax'        => $this->tax->getRate($product['tax_class_id']),
				'shipping'   => ($this->session->get('shipping_method') == 'warehouse_warehouse' ? FALSE : $product['shipping'])
      		); 
    	}
		$this->order->set('discount_total',$discount_total);
		$this->order->set('products', $product_data);
		$this->order->set('totals', $totals);
		$this->order->set('coupon_id', $this->coupon->getId());
		$this->order->set('comment', $this->session->get('comment'));
		$this->order->set('email_subject', $this->language->get('email_subject', $this->order->getReference()));

		$this->order->set('email_html', $email->fetch('content/checkout_email.tpl'));
		$this->order->set('email_text', $this->language->get('email_message', $this->config->get('config_store'), $this->order->getReference(), $this->url->ssl('account_invoice', FALSE, array('reference' => $this->order->getReference())), $this->language->formatDate($this->language->get('date_format_long')), strip_tags($this->session->get('comment'))));

    	$view->set('fields', $this->payment->getFields($this->session->get('payment_method')));

		if ($this->config->get('config_checkout_id')) {
			$information_info = $this->modelCheckout->get_information($this->config->get('config_checkout_id'));
		
			$view->set('agree', $this->language->get('text_agree', $this->url->href('information', FALSE, array('information_id' => $this->config->get('config_checkout_id'))), $information_info['title']));
		}
		
		$this->order->save($this->order->getReference());

    	$this->template->set('content', $view->fetch('content/checkout_confirm.tpl'));
		$this->template->set('head_def',$this->head_def);
		
		$this->load_modules();  // Template Manager
		$this->set_tpl_modules(); // Template Manager
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
  	}
	
	function load_modules(){ // Template Manager
		$modules = $this->modelCore->merge_modules($this->get_modules_extra());
		foreach ($this->locations as $location){
			if($modules[$location['location']]){
				foreach($modules[$location['location']] as $module){
					$this->template->set($this->module->load($module));
				}
			}
		}
	}
	function get_modules_extra(){// Template Manager (Default Modules specific to current controller)
		foreach($this->locations as $location){
			$modules_extra[$location['location']] = array();
		}
		$modules_extra['column'] = array('manufacturer', 'popular');
		$modules_extra['columnright'] = array('specials');
		return $modules_extra;
	}

	function set_tpl_modules(){ // Template Manager
		if($this->modelCore->tpl){
			if(isset($this->modelCore->tpl['tpl_headers'])){$this->template->set('tpl_headers',$this->modelCore->tpl['tpl_headers']);}
			if(isset($this->modelCore->tpl['tpl_extras'])){$this->template->set('tpl_extras',$this->modelCore->tpl['tpl_extras']);}
			if(isset($this->modelCore->tpl['tpl_left_columns'])){$this->template->set('tpl_left_columns',$this->modelCore->tpl['tpl_left_columns']);}
			if(isset($this->modelCore->tpl['tpl_contents'])){$this->template->set('tpl_contents',$this->modelCore->tpl['tpl_contents']);}
			if(isset($this->modelCore->tpl['tpl_right_columns'])){$this->template->set('tpl_right_columns',$this->modelCore->tpl['tpl_right_columns']);}
			if(isset($this->modelCore->tpl['tpl_footers'])){$this->template->set('tpl_footers',$this->modelCore->tpl['tpl_footers']);}
			if(isset($this->modelCore->tpl['tpl_bottom'])){$this->template->set('tpl_bottom',$this->modelCore->tpl['tpl_bottom']);}
		}
		if(isset($this->tpl_manager['tpl_color']) && $this->tpl_manager['tpl_color']){$this->template->set('template_color',$this->tpl_manager['tpl_color']);}
		$this->template->set('tpl_columns', $this->modelCore->tpl_columns);
	}
	
	function validate() {
		if (!$this->coupon->set($this->request->gethtml('coupon', 'post'))) {
			$this->error['message'] = $this->language->get('error_coupon');
			
			if (!$this->coupon->hasProduct()) {
				$this->error['message'] = $this->language->get('error_product'); 
			}
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>

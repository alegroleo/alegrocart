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

		$view->set('entry_coupon', $this->language->get('entry_coupon'));

    	$view->set('button_continue', $this->language->get('button_continue'));
		$view->set('button_back', $this->language->get('button_back'));
    	$view->set('button_update', $this->language->get('button_update'));

		$view->set('error', @$this->error['message']);

		if ($this->session->has('error')) {
            $view->set('error', $this->session->get('error'));
            $this->session->delete('error');
        }

		$view->set('action', $this->url->href('checkout_confirm'));

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

    	$view->set('shipping_address', $this->address->getFormatted($this->session->get('shipping_address_id'), '<br />'));
		    	
		$view->set('shipping_method', $this->shipping->getTitle($this->session->get('shipping_method')));
		
    	$view->set('checkout_shipping', $this->url->ssl('checkout_shipping'));

    	$view->set('checkout_shipping_address', $this->url->ssl('checkout_address', 'shipping'));
		
		$view->set('payment_address', $this->address->getFormatted($this->session->get('payment_address_id'), '<br />'));
    
    	$view->set('payment_method', $this->payment->getTitle($this->session->get('payment_method')));
	
    	$view->set('checkout_payment', $this->url->ssl('checkout_payment'));

    	$view->set('checkout_payment_address', $this->url->ssl('checkout_address', 'payment'));

    	$product_data = array();

    	foreach ($this->cart->getProducts() as $product) {
      		$option_data = array();

      		foreach ($product['option'] as $option) {
        		$option_data[] = array(
          			'name'  => $option['name'],
          			'value' => $option['value']
        		);
      		} 
			$special_price = $product['special_price'] ? $product['special_price'] - $product['discount'] : 0;
      		$product_data[] = array(
				'product_id' => $product['product_id'],
        		'href'       => $this->url->href('product', FALSE, array('product_id' => $product['product_id'])),
        		'name'       => $product['name'],
        		'model_number'=> $product['model_number'],
        		'option'     => $option_data,
        		'quantity'   => $product['quantity'],
				'tax'        => $this->tax->getRate($product['tax_class_id']),
        		'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
				'special_price' => $this->currency->format($this->tax->calculate($special_price, $product['tax_class_id'], $this->config->get('config_tax'))),
				'discount'   => ($product['discount'] ? $this->currency->format($this->tax->calculate($product['price'] - $product['discount'], $product['tax_class_id'], $this->config->get('config_tax'))) : NULL),
        		'total'      => $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')))
      		); 
    	} 

    	$view->set('products', $product_data);

		$totals = $this->calculate->getTotals();

		$view->set('totals', $totals);
	
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

		$this->order->set('shipping_firstname', $this->address->getFirstName($this->session->get('shipping_address_id')));
		$this->order->set('shipping_lastname', $this->address->getLastName($this->session->get('shipping_address_id')));
		$this->order->set('shipping_company', $this->address->getCompany($this->session->get('shipping_address_id')));
		$this->order->set('shipping_address_1', $this->address->getAddress1($this->session->get('shipping_address_id')));
		$this->order->set('shipping_address_2', $this->address->getAddress2($this->session->get('shipping_address_id')));
		$this->order->set('shipping_city', $this->address->getCity($this->session->get('shipping_address_id')));
		$this->order->set('shipping_postcode', $this->address->getPostCode($this->session->get('shipping_address_id')));
		$this->order->set('shipping_zone', $this->address->getZone($this->session->get('shipping_address_id')));
		$this->order->set('shipping_country', $this->address->getCountry($this->session->get('shipping_address_id')));
		$this->order->set('shipping_address_format', $this->address->getFormat($this->session->get('shipping_address_id')));

		$this->order->set('shipping_method', $this->shipping->getTitle($this->session->get('shipping_method')));

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
    	$email->set('email_quantity', $this->language->get('email_quantity'));
    	$email->set('email_price', $this->language->get('email_price'));
    	$email->set('email_total', $this->language->get('email_total'));
	 	
		$email->set('store', $this->config->get('config_store'));
		$email->set('email', $this->customer->getEmail());
		$email->set('telephone', $this->customer->getTelephone());
		$email->set('fax', $this->customer->getFax());
		$email->set('shipping_address', $this->address->getFormatted($this->session->get('shipping_address_id'), '<br />'));
		$email->set('shipping_method', $this->shipping->getTitle($this->session->get('shipping_method')));
		$email->set('payment_address', $this->address->getFormatted($this->session->get('payment_address_id'), '<br />'));
		$email->set('payment_method', $this->payment->getTitle($this->session->get('payment_method')));
		$email->set('products', $product_data);
		$email->set('totals', $totals);
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
        		'product_id' => $product['product_id'],
				'name'       => $product['name'],
        		'model_number' => $product['model_number'],
        		'option'     => $option_data,
				'download'   => $product['download'],
				'quantity'   => $product['quantity'],
				'price'      => $product['special_price'] ? $product['special_price'] : $product['price'],
				'discount'   => $product['discount'],
        		'total'      => $product['total'],
				'tax'        => $this->tax->getRate($product['tax_class_id']),
      		); 
    	}
		
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

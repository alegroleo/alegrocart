<?php //Account Invoice AlegroCart
class ControllerAccountInvoice extends Controller {
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->address  	=& $locator->get('address');
		$this->config  		=& $locator->get('config');
		$this->currency 	=& $locator->get('currency');
		$this->customer 	=& $locator->get('customer');
		$this->head_def 	=& $locator->get('HeaderDefinition');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->response 	=& $locator->get('response');
		$this->request  	=& $locator->get('request');
		$this->session  	=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->modelCore 	= $model->get('model_core');
		$this->modelAccountInvoice = $model->get('model_accountinvoice');
		$this->tpl_manager 	= $this->modelCore->get_tpl_manager('account_invoice'); // Template Manager
		$this->locations 	= $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns 	= $this->modelCore->get_columns();// Template Manager
	}

	function index() { 
    	if (!$this->customer->isLogged()) {
			if ($this->request->has('order_id')) {
				$this->session->set('redirect', $this->url->ssl('account_invoice', FALSE, array('order_id' => $this->request->gethtml('order_id'))));
			} else {
				$this->session->set('redirect', $this->url->ssl('account_invoice', FALSE, array('reference' => $this->request->gethtml('reference'))));
			}
      		
			$this->response->redirect($this->url->ssl('account_login'));
    	}
	
    	$this->language->load('controller/account_invoice.php');
    	$this->template->set('title', $this->language->get('heading_title'));
		$view = $this->locator->create('template');
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('head_def',$this->head_def);    // New Header
		$this->template->set('head_def',$this->head_def);    // New Header	
		$view->set('button_continue', $this->language->get('button_continue'));
		$view->set('continue', $this->url->ssl('account_history'));
		
		if ($this->request->has('order_id')) {
			$order_info = $this->modelAccountInvoice->get_order($this->request->gethtml('order_id'));
    	} else {
			$order_info = $this->modelAccountInvoice->get_order_ref($this->request->gethtml('reference'));
		}
		
		if ($order_info) {
    		$view->set('text_order', $this->language->get('text_order'));
			$view->set('text_email', $this->language->get('text_email'));
			$view->set('text_telephone', $this->language->get('text_telephone'));
			$view->set('text_fax', $this->language->get('text_fax'));
      		$view->set('text_shipping_address', $this->language->get('text_shipping_address'));
      		$view->set('text_shipping_method', $this->language->get('text_shipping_method'));
      		$view->set('text_payment_address', $this->language->get('text_payment_address'));
      		$view->set('text_payment_method', $this->language->get('text_payment_method'));
      		$view->set('text_order_history', $this->language->get('text_order_history'));
      		$view->set('text_product', $this->language->get('text_product'));
      		$view->set('text_model_number', $this->language->get('text_model_number'));
      		$view->set('text_quantity', $this->language->get('text_quantity'));
      		$view->set('text_price', $this->language->get('text_price'));
      		$view->set('text_total', $this->language->get('text_total'));
      		$view->set('column_date_added', $this->language->get('column_date_added'));
      		$view->set('column_status', $this->language->get('column_status'));
      		$view->set('column_comment', $this->language->get('column_comment'));
			$view->set('reference', $order_info['reference']);
			$view->set('email', $order_info['email']);
			$view->set('telephone', $order_info['telephone']);
			$view->set('fax', $order_info['fax']);
		
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

      		$view->set('shipping_address', $this->address->format($shipping_address, $order_info['shipping_address_format'], '<br />'));
			$view->set('shipping_method', $order_info['shipping_method']);

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

      		$view->set('payment_address', $this->address->format($payment_address, $order_info['payment_address_format'], '<br />'));
      		$view->set('payment_method', $order_info['payment_method']);
			
			$products = $this->modelAccountInvoice->get_order_products($order_info['order_id']);
      		$product_data = array();
      		foreach ($products as $product) {
				$options = $this->modelAccountInvoice->get_options($order_info['order_id'],$product['order_product_id']);
        		$option_data = array();
        		foreach ($options as $option) {
          			$option_data[] = array(
            			'name'  => $option['name'],
            			'value' => $option['value'],
          			);
        		}

				if ($this->config->get('config_tax')) {
					$product_data[] = array(
						'name'     		=> $product['name'],
						'model_number'  => $product['model_number'],
						'option'   		=> $option_data,
						'quantity' 		=> $product['quantity'],
						'price'   		=> $this->currency->format(($product['price'] + ($product['price'] * $product['tax'] / 100)), $order_info['currency'], $order_info['value']),
						'discount' 		=> (ceil($product['discount']) ? $this->currency->format(($product['price'] - $product['discount']) + (($product['price'] - $product['discount']) * $product['tax'] / 100), $order_info['currency'], $order_info['value']) : NULL),
						'total'    		=> $this->currency->format($product['total'] + ($product['total'] * $product['tax'] / 100), $order_info['currency'], $order_info['value'])
					);
				}
				else {
					$product_data[] = array(
						'name'     		=> $product['name'],
						'model_number'	=> $product['model_number'],
						'option'   		=> $option_data,
						'quantity' 		=> $product['quantity'],
						'price'    		=> $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
						'discount' 		=> (ceil($product['discount']) ? $this->currency->format(($product['price'] - $product['discount']), $order_info['currency'], $order_info['value']) : NULL),
						'total'   		=> $this->currency->format($product['total'], $order_info['currency'], $order_info['value'])
					);
				}
      		}

      		$view->set('products', $product_data);
			$view->set('totals',$this->modelAccountInvoice->get_totals($order_info['order_id']));
      		$history_data = array();
			$results = $this->modelAccountInvoice->get_order_history($order_info['order_id']);
      		foreach ($results as $result) {
        		$history_data[] = array(
          			'date_added' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_added'])),
          			'status'     => $result['status'],
          			'comment'    => $result['comment']
        		);
      		}
      		$view->set('historys', $history_data);

	  		$this->template->set('content', $view->fetch('content/account_invoice.tpl'));
    	} else {
      		$view->set('text_error', $this->language->get('text_error'));
	  		$this->template->set('content', $view->fetch('content/error.tpl'));
    	}
		
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
}
?>

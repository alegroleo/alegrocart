<?php //Checkout Shipping AlegroCart
class ControllerCheckoutShipping extends Controller {
	var $error = array(); 
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->address  	=& $locator->get('address');
		$this->calculate 	=& $locator->get('calculate');
		$this->cart     	=& $locator->get('cart');
		$this->config  		=& $locator->get('config');
		$this->coupon   	=& $locator->get('coupon');
		$this->customer 	=& $locator->get('customer');
		$this->head_def 	=& $locator->get('HeaderDefinition');
		$this->image    	=& $locator->get('image');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->response 	=& $locator->get('response');
		$this->request  	=& $locator->get('request');
		$this->session  	=& $locator->get('session');
		$this->shipping  	=& $locator->get('shipping');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->validate 	=& $locator->get('validate');
		$this->modelCore 	= $model->get('model_core');
		$this->modelCheckout = $model->get('model_checkout');
		$this->tpl_manager 	= $this->modelCore->get_tpl_manager('checkout_shipping'); // Template Manager
		$this->locations 	= $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns 	= $this->modelCore->get_columns();// Template Manager
	}
	
  	function index() {
		if (!$this->customer->isLogged()) {
			$this->session->set('redirect', $this->url->ssl('checkout_shipping'));

	  		$this->response->redirect($this->url->ssl('account_login'));
    	} 
		if ($this->cart->hasProducts()) {
			$this->calculate->getTotals(); 
		}
    	if ((!$this->cart->hasProducts()) || ((!$this->cart->hasStock()) && (!$this->config->get('config_stock_checkout'))) || (!$this->cart->moreThanMinov($this->cart->getNetTotal()))) {
	  		$this->response->redirect($this->url->ssl('cart'));
    	}

    	if (!$this->cart->hasShipping()) {
			$this->session->delete('shipping_address_id');
			$this->session->delete('shipping_method');
			
			$this->response->redirect($this->url->ssl('checkout_payment'));
    	}

    	if (!$this->address->has($this->session->get('shipping_address_id'))) {
	  		$this->session->set('shipping_address_id', $this->customer->getAddressId());
    	}

    	if (!$this->address->has($this->session->get('shipping_address_id'))) {
	  		$this->response->redirect($this->url->ssl('checkout_address', 'shipping'));
		}

    	$this->language->load('controller/checkout_shipping.php');

		$this->template->set('title', $this->language->get('heading_title')); 

    	if ($this->request->isPost() && $this->request->has('shipping', 'post') && $this->validate()) {
			if(($this->session->get('account_validation') == $this->request->gethtml('account_validation', 'post')) && (strlen($this->session->get('account_validation')) > 10)){
				$this->session->set('shipping_method', $this->request->gethtml('shipping', 'post'));
				$this->session->set('comment', $this->request->sanitize('comment', 'post'));
				$this->session->delete('message');
				$this->session->delete('account_validation');
				$this->response->redirect($this->url->ssl('checkout_payment'));
			} else {
				$this->session->set('message',$this->language->get('error_referer'));
				$this->session->delete('account_validation');
				$this->response->redirect($this->url->ssl('checkout_shipping'));
			}	
    	}
		
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));

    	$view->set('text_shipping', $this->language->get('text_shipping'));
    	$view->set('text_shipping_to', $this->language->get('text_shipping_to'));
    	$view->set('text_shipping_address', $this->language->get('text_shipping_address'));
    	$view->set('text_shipping_method', $this->language->get('text_shipping_method'));
    	$view->set('text_shipping_methods', $this->language->get('text_shipping_methods'));
    	$view->set('text_comments', $this->language->get('text_comments'));
 
        if ($this->cart->hasNoShipping()) {
		$view->set('text_nonshippable', $this->language->get('text_nonshippable'));

		$product_data = array();
			
     		foreach ($this->cart->getProducts() as $result) {
        		
			if (!$result['shipping']) {   

			$option_data = array();

        		foreach ($result['option'] as $option) {
          			$option_data[] = array(
            			'name'  => $option['name'],
            			'value' => $option['value'],
          			);
        		}

			$product_data[] = array(
          			'key'           => $result['key'],
          			'name'          => $result['name'],
          			'model_number'  => $result['model_number'],
				'shipping'   	=> $result['shipping'],
          			'thumb'         => $this->image->resize($result['image'], 40, 40),
          			'option'        => $option_data,
          			'quantity'      => $result['quantity'],
				'stock'         => $result['stock'],
				'href'          => $this->url->href('product', FALSE, array('product_id' => $result['product_id']))
        		);
			}
			}

		$view->set('products', $product_data);
		$view->set('text_choose', $this->language->get('text_choose'));
		$view->set('text_name', $this->language->get('text_name'));
		$view->set('text_image', $this->language->get('text_image'));
		$view->set('text_ship', $this->language->get('text_ship'));
		$view->set('text_non_shippable', $this->language->get('text_non_shippable'));
		$view->set('text_quantity', $this->language->get('text_quantity'));

		}

		$view->set('hasnoshipping', $this->cart->hasNoShipping());

		$view->set('button_change_address', $this->language->get('button_change_address'));
    	$view->set('button_back', $this->language->get('button_back'));
    	$view->set('button_continue', $this->language->get('button_continue'));
    
		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		$view->set('action', $this->url->ssl('checkout_shipping'));
    	$this->session->set('account_validation', md5(time()));
		$view->set('account_validation', $this->session->get('account_validation'));

    	$view->set('address', $this->address->getFormatted($this->session->get('shipping_address_id'), '<br />'));
    
    	$view->set('change_address', $this->url->ssl('checkout_address', 'shipping'));

    	$view->set('methods', $this->shipping->getQuotes());

    	$view->set('default', $this->session->get('shipping_method'));

    	$view->set('comment', $this->session->get('comment'));

    	$view->set('back', $this->url->href('cart'));
		$view->set('head_def',$this->head_def);
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $view->fetch('content/checkout_shipping.tpl'));

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
    	if (!$this->request->gethtml('shipping', 'post')) {
	  		$this->error['message'] = $this->language->get('error_shipping');
		}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
}
?>
<?php //Checkout Pending AlegroCart
class ControllerCheckoutPending extends Controller {
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->config  		=& $locator->get('config');
		$this->customer 	=& $locator->get('customer');
		$this->head_def 	=& $locator->get('HeaderDefinition');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->order    	=& $locator->get('order');
		$this->payment  	=& $locator->get('payment');
		$this->response 	=& $locator->get('response');
		$this->request  	=& $locator->get('request');
		$this->session  	=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->modelCore 	= $model->get('model_core');
		$this->modelCheckout = $model->get('model_checkout');
		$this->tpl_manager 	= $this->modelCore->get_tpl_manager('checkout_pending'); // Template Manager
		$this->locations 	= $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns 	= $this->modelCore->get_columns();// Template Manager
	}

	function index() {
    	if (!$this->customer->isLogged()) {
      		$this->session->set('redirect',  $this->url->ssl('checkout_pending'));

	  		$this->response->redirect($this->url->ssl('account_login'));
    	}
		
   		$this->language->load('controller/checkout_pending.php');

    	$this->template->set('title', $this->language->get('heading_title')); 
		
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));
//
    	$view->set('text_pending', $this->language->get('text_pending', $this->url->href('contact')));

    	$view->set('button_click_to_complete', $this->language->get('button_click_to_complete'));

    	$view->set('continue', $this->url->href('home'));
        
        $view->set('text_redirect', $this->language->get('text_redirect'));

        $view->set('error', @$this->error['message']);
        
        $view->set('message', $this->session->get('message'));
    
        $this->session->delete('message');
        
        $this->session->delete('payment_form_enctype');
        
        $this->session->delete('confirm_id');
                            
        $view->set('payment_url', $this->session->get('payment_offsite_url'));
              
        if ($this->session->get('payment_form_enctype')) {
            $view->set('payment_form_enctype', $this->session->get('payment_form_enctype'));
        }
        
        $this->order->set('payment_method', $this->payment->getTitle($this->session->get('payment_method')));
        
        $view->set('fields', $this->session->get('fields'));
		$view->set('head_def',$this->head_def); 
		$this->template->set('head_def',$this->head_def);
    	$this->template->set('content', $view->fetch('content/checkout_pending.tpl'));
	
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
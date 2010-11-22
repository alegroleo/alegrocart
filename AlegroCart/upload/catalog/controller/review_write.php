<?php //ReviewWrite AlegroCart
class ControllerReviewWrite extends controller { 
	var $error = array();
		function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->config  		=& $locator->get('config');
		$this->config->set('config_tax', $this->config->get('config_tax_store'));
		$this->address  	=& $locator->get('address');
		$this->currency 	=& $locator->get('currency');
		$this->customer 	=& $locator->get('customer');
		$this->head_def 	=& $locator->get('HeaderDefinition');
		$this->image    	=& $locator->get('image');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->response 	=& $locator->get('response');
		$this->request  	=& $locator->get('request');
		$this->session  	=& $locator->get('session');
		$this->tax      	=& $locator->get('tax');
		$this->template 	=& $locator->get('template');
		$this->url     	 	=& $locator->get('url');
		$this->validate 	=& $locator->get('validate');
		$this->modelCore 	= $model->get('model_core');
		$this->modelAccountAddress = $model->get('model_accountaddress');
		$this->modelReview 	= $model->get('model_review');
		$this->tpl_manager = $this->modelCore->get_tpl_manager('review_write'); // Template Manager
		$this->locations = $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns = $this->modelCore->get_columns();// Template Manager
	}

  	function index() {

    	if (!$this->customer->isLogged()) {
	  		$query = array(
	    		'product_id' => $this->request->gethtml('product_id'),
				'review_id'  => $this->request->gethtml('review_id')
	  		);
	
      		$this->session->set('redirect', $this->url->ssl('review_write', FALSE, $query));

      		$this->response->redirect($this->url->ssl('account_login'));
    	} 
	
    	$this->language->load('controller/review_write.php');
	
		if ($this->request->isPost() && $this->request->has('product_id') && $this->validate()) {
      		$this->modelReview->insert_review($this->request->gethtml('product_id'));
	  		$this->response->redirect($this->url->ssl('review_success'));
    	}
    	
		$product_info = $this->modelReview->get_product($this->request->gethtml('product_id'));
		
		if ($product_info) {	  
	  		$this->template->set('title', $this->language->get('heading_title'));
	  
	  		$view = $this->locator->create('template');
    
	  		$view->set('heading_title', $this->language->get('heading_title'));
			
			$view->set('tax_included', $this->config->get('config_tax'));

      		$view->set('text_enlarge', $this->language->get('text_enlarge'));
      		$view->set('text_author', $this->language->get('text_author'));
      		$view->set('text_note', $this->language->get('text_note'));
      		$view->set('text_product', $this->language->get('text_product'));

      		$view->set('entry_review', $this->language->get('entry_review'));
      		$view->set('entry_rating', $this->language->get('entry_rating'));
      		$view->set('entry_good', $this->language->get('entry_good'));
      		$view->set('entry_bad', $this->language->get('entry_bad'));

      		$view->set('button_continue', $this->language->get('button_continue'));
      		$view->set('button_back', $this->language->get('button_back'));
    
	  		$view->set('error', @$this->error['message']);

	  		$query = array(
	    		'product_id' => $this->request->gethtml('product_id'),
				'review_id'  => $this->request->gethtml('review_id')
	  		);
	      
	  		$view->set('action', $this->url->ssl('review_write', FALSE, $query));
      
	  		$view->set('product', $product_info['name']);
	  		$view->set('price', $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))));
			$view->set('special_price', $product_info['special_price']>0 ? $this->currency->format($this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax'))): ""); // New
	  		$view->set('popup', $this->image->href($product_info['filename']));
	  		$view->set('thumb', $this->image->resize($product_info['filename'], 160,160));
	  		$view->set('author', $this->customer->getFirstName() . ' ' . $this->customer->getLastName());
	  		$view->set('text', $this->request->sanitize('text', 'post'));
	  		$view->set('rating', $this->request->gethtml('rating', 'post'));
      
	  		$query = array(
	    		'product_id' => $this->request->gethtml('product_id')
	  		);
	  
	  		$view->set('back', $this->url->href('review', FALSE, $query));
			$view->set('head_def',$this->head_def);
			$this->template->set('head_def',$this->head_def);
	  		$this->template->set('content', $view->fetch('content/review_write.tpl'));

    	} else {
      		$this->template->set('title', $this->language->get('text_error'));

      		$view = $this->locator->create('template');
      		$view->set('heading_title', $this->language->get('text_error'));
      		$view->set('text_error', $this->language->get('text_error'));

      		$view->set('button_continue', $this->language->get('button_continue'));
      		$view->set('continue', $this->url->href('home'));
			$view->set('head_def',$this->head_def);
			$this->template->set('head_def',$this->head_def);
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
		$modules_extra['column'] = array('manufacturer', 'popular', 'review');
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
    	if ((strlen($this->request->sanitize('text', 'post')) < 25) || (strlen($this->request->sanitize('text', 'post')) > 1000)) {
      		$this->error['message'] = $this->language->get('error_text');
    	}
    	if (!$this->request->gethtml('rating', 'post')) {
      		$this->error['message'] = $this->language->get('error_rating');
    	}
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}	
	}
}
?>

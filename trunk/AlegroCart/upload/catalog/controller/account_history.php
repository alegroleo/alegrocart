<?php //Account History AlegroCart
class ControllerAccountHistory extends Controller {	
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
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
		$this->modelAccountHistory = $model->get('model_accounthistory');
		$this->tpl_manager 	= $this->modelCore->get_tpl_manager('account_history'); // Template Manager
		$this->locations 	= $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns 	= $this->modelCore->get_columns();// Template Manager
	}

	function index() {
    	if (!$this->customer->isLogged()) {
      		$this->session->set('redirect', $this->url->ssl('account_history'));

	  		$this->response->redirect($this->url->ssl('account_login'));
    	}
 
		//pagination
        $this->session->set('account_history.page', $this->request->has('page') && ($this->request->gethtml('page') > 0) ? abs((int)$this->request->gethtml('page')) : 1);

    	$this->language->load('controller/account_history.php');

    	$this->template->set('title', $this->language->get('heading_title'));
		$view = $this->locator->create('template');
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('continue', $this->url->ssl('account'));
	  	$view->set('head_def',$this->head_def); 
		$this->template->set('head_def',$this->head_def);
		
		$results = $this->modelAccountHistory->get_orders($this->customer->getId());
		if ($results) {
      		$view->set('text_order', $this->language->get('text_order'));
      		$view->set('text_invoice_number', $this->language->get('text_invoice_number'));
      		$view->set('text_status', $this->language->get('text_status'));
     		$view->set('text_date_added', $this->language->get('text_date_added'));
      		$view->set('text_customer', $this->language->get('text_customer'));
      		$view->set('text_products', $this->language->get('text_products'));
      		$view->set('text_total', $this->language->get('text_total'));
     		$view->set('text_results',$this->modelAccountHistory->get_text_results());
			$view->set('text_print', $this->language->get('text_print'));
			
			$view->set('first_page', $this->language->get('first_page'));
			$view->set('last_page', $this->language->get('last_page'));
      		$view->set('entry_page', $this->language->get('entry_page'));
			$view->set('previous' , $this->language->get('previous_page'));
			$view->set('next' , $this->language->get('next_page'));
      		$view->set('button_view', $this->language->get('button_view'));
      		$view->set('button_continue', $this->language->get('button_continue'));

			$view->set('action', $this->url->href('account_history', 'page'));

			$order_data = array();
      		foreach ($results as $result) {
        		$product_info = $this->modelAccountHistory->get_product_count($result['order_id']);

        		$order_data[] = array(
          			'reference'  => $result['reference'],
          			'href'       => $this->url->ssl('account_invoice', FALSE, array('order_id' => $result['order_id'])),
					'print'      => $this->url->ssl('account_invoice', FALSE, array('order_id' => $result['order_id'], 'order_print' => TRUE)),
          			'name'       => $result['firstname'] . ' ' . $result['lastname'],
					'invoice_number' => $result['invoice_number'] ? $result['invoice_number'] : 0,
          			'status'     => $result['status'],
          			'date_added' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_added'])),
          			'products'   => $product_info['products'],
          			'total'      => $this->currency->format($result['total'], $result['currency'], $result['value'])
        		);
      		}

      		$view->set('orders', $order_data);

      		$view->set('page', $this->session->get('account_history.page'));
		  	$view->set('pages', $this->modelAccountHistory->get_pagination());
			$view->set('total_pages', $this->modelAccountHistory->get_pages());
	  		$this->template->set('content', $view->fetch('content/account_history.tpl'));
    	} else {
      		$view->set('text_error', $this->language->get('text_error'));
      		$view->set('button_continue', $this->language->get('button_continue'));
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

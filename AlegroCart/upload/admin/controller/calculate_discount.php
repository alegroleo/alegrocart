<?php //Calculate Discount AlegroCart
class ControllerCalculateDiscount extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 			=& $locator->get('model');
		$this->language 	=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->template 	=& $locator->get('template');
		$this->session  	=& $locator->get('session');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user');
		$this->modelDiscount 	= $model->get('model_admin_calculatediscount');
		
		$this->language->load('controller/calculate_discount.php');
	}
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));
				
		if ($this->request->isPost() && $this->request->has('global_discount_status', 'post') && $this->validate()) {
			$this->modelDiscount->delete_discount();
			$this->modelDiscount->update_discount();
			$this->session->set('message', $this->language->get('text_message'));
            
		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'calculate')));
		}   
		    
		$view = $this->locator->create('template');
		    
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_calculate', $this->language->get('heading_calculate'));
		$view->set('heading_description', $this->language->get('heading_description'));		

		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));
		
		$view->set('entry_discount_status', $this->language->get('entry_discount_status'));
		$view->set('entry_discount_sort_order', $this->language->get('entry_discount_sort_order'));
		$view->set('entry_discount_lprice', $this->language->get('entry_discount_lprice'));
		$view->set('entry_discount_lprice_percent', $this->language->get('entry_discount_lprice_percent'));
		$view->set('entry_discount_gprice', $this->language->get('entry_discount_gprice'));
		$view->set('entry_discount_gprice_percent', $this->language->get('entry_discount_gprice_percent'));

		$view->set('explanation_entry_discount_status', $this->language->get('explanation_entry_discount_status'));
		$view->set('explanation_entry_discount_sort_order', $this->language->get('explanation_entry_discount_sort_order'));
		$view->set('explanation_entry_discount_lprice', $this->language->get('explanation_entry_discount_lprice'));
		$view->set('explanation_entry_discount_lprice_percent', $this->language->get('explanation_entry_discount_lprice_percent'));
		$view->set('explanation_entry_discount_gprice', $this->language->get('explanation_entry_discount_gprice'));
		$view->set('explanation_entry_discount_gprice_percent', $this->language->get('explanation_entry_discount_gprice_percent'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('action', $this->url->ssl('calculate_discount'));
		$view->set('list', $this->url->ssl('extension', FALSE, array('type' => 'calculate')));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'calculate')));	

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (!$this->request->isPost()) {
			$results = $this->modelDiscount->get_discount();
			
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}
		        			
		if ($this->request->has('global_discount_status', 'post')) {
			$view->set('globaldiscount_status', $this->request->gethtml('global_discount_status', 'post'));
		} else {
			$view->set('global_discount_status', @$setting_info['global']['discount_status']);
		}
		
		if ($this->request->has('global_discount_sort_order', 'post')) {
			$view->set('globaldiscount_sort_order', $this->request->gethtml('global_discount_sort_order', 'post'));
		} else {
			$view->set('global_discount_sort_order', @$setting_info['global']['discount_sort_order']);
		}
		
		if ($this->request->has('global_discount_lprice', 'post')) {
			$view->set('globaldiscount_lprice', $this->request->gethtml('global_discount_lprice', 'post'));
		} else {
			$view->set('global_discount_lprice', @$setting_info['global']['discount_lprice']);
		}
		
		if ($this->request->has('global_discount_lprice_percent', 'post')) {
			$view->set('globaldiscount_lprice_percent', $this->request->gethtml('global_discount_lprice_percent', 'post'));
		} else {
			$view->set('global_discount_lprice_percent', @$setting_info['global']['discount_lprice_percent']);
		}

		if ($this->request->has('global_discount_gprice', 'post')) {
			$view->set('globaldiscount_gprice', $this->request->gethtml('global_discount_gprice', 'post'));
		} else {
			$view->set('global_discount_gprice', @$setting_info['global']['discount_gprice']);
		}
		
		if ($this->request->has('global_discount_gprice_percent', 'post')) {
			$view->set('globaldiscount_gprice_percent', $this->request->gethtml('global_discount_gprice_percent', 'post'));
		} else {
			$view->set('global_discount_gprice_percent', @$setting_info['global']['discount_gprice_percent']);
		}
				
		$this->template->set('content', $view->fetch('content/calculate_discount.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'calculate_discount')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
	function install() {
		if ($this->user->hasPermission('modify', 'calculate_discount')) {
			$this->modelDiscount->delete_discount();
			$this->modelDiscount->install_discount();
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'calculate')));				
	}
	
	function uninstall() {
		if ($this->user->hasPermission('modify', 'calculate_discount')) {
			$this->modelDiscount->delete_discount();
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'calculate')));
	}
}
?>

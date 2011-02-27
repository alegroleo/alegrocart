<?php // Maintenance AlegroCart
class ControllerMaintenance extends Controller{
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request 	 	=& $locator->get('request');
		$this->response	 	=& $locator->get('response');
		$this->session 	 	=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url     		=& $locator->get('url');
		$this->user    	 	=& $locator->get('user');
		$this->modelMaintenance = $model->get('model_admin_maintenance');
		$this->language->load('controller/maintenance.php');
	}
	function index(){
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->isPost()) && ($this->validate())) {
			$this->modelMaintenance->delete_maintenance();
			$this->modelMaintenance->update_maintenance();
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('home'));
		}
	
		$view = $this->locator->create('template');
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));
		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));
		$view->set('entry_status', $this->language->get('entry_status'));
		
		$view->set('explanation_entry_status', $this->language->get('explanation_entry_status'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		
		$this->session->delete('message');
		$view->set('action', $this->url->ssl('maintenance'));
		$view->set('cancel', $this->url->ssl('maintenance'));
		
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		if (!$this->request->isPost()) {
			$results = $this->modelMaintenance->get_maintenance();	
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}
		if ($this->request->has('catalog_maintenance_status', 'post')) {
			$view->set('catalog_maintenance_status', $this->request->get('catalog_maintenance_status', 'post'));
		} else {
			$view->set('catalog_maintenance_status', @$setting_info['catalog']['maintenance_status']);
		}	
		
		$this->template->set('content', $view->fetch('content/maintenance.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));		
		
	}

  	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
    	if (!$this->user->hasPermission('modify', 'maintenance')) {
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
<?php // Developer AlegroCart
class ControllerModuleExtraDeveloper extends Controller {
	var $error = array();  	// All References change to module_extra_ due to new module loader  
 	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->cache    	=& $locator->get('cache');
		$this->config   	=& $locator->get('config');
		$this->currency 	=& $locator->get('currency');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session 		=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user'); 
		$this->modelDeveloper = $model->get('model_admin_developer');
		
		$this->language->load('controller/module_extra_developer.php');
	}
	function index(){
		$this->template->set('title', $this->language->get('heading_title'));
		if (($this->request->isPost()) && ($this->validate())) {
			$this->modelDeveloper->delete_developer();
			$this->modelDeveloper->update_developer();
			
			$this->session->set('message', $this->language->get('text_message'));
			
			$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
		}
		
		$view = $this->locator->create('template');
		
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));
		
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_developer', $this->language->get('entry_developer'));
		$view->set('entry_link', $this->language->get('entry_link'));
		
		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		
		$view->set('tab_general', $this->language->get('tab_general'));
		
		$view->set('error', @$this->error['message']);
		
		$view->set('action', $this->url->ssl('module_extra_developer'));
		
		$view->set('list', $this->url->ssl('extension', FALSE, array('type' => 'module')));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'module')));
		
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
		if (!$this->request->isPost()) {
			$results = $this->modelDeveloper->get_developer();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}
		
		if ($this->request->has('global_developer_status', 'post')) {
			$view->set('global_developer_status', $this->request->gethtml('global_developer_status', 'post'));
		} else {
			$view->set('global_developer_status', @$setting_info['global']['developer_status']);
		}
		if ($this->request->has('global_developer_developer', 'post')) {
			$view->set('global_developer_developer', $this->request->gethtml('global_developer_developer', 'post'));
		} else {
			$view->set('global_developer_developer', @$setting_info['global']['developer_developer']);
		}
		if ($this->request->has('global_developer_link', 'post')) {
			$view->set('global_developer_link', $this->request->gethtml('global_developer_link', 'post'));
		} else {
			$view->set('global_developer_link', @$setting_info['global']['developer_link']);
		}
		
		
		$this->template->set('content', $view->fetch('content/module_extra_developer.tpl'));
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'module_extra_developer')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function install() {
		if ($this->user->hasPermission('modify', 'module_extra_developer')) {
			$this->modelDeveloper->delete_developer();
			$this->modelDeveloper->install_developer();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));	
	}

	function uninstall() {
		if ($this->user->hasPermission('modify', 'module_extra_developer')) {
			$this->modelDeveloper->delete_developer();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
	}
}
?>
<?php //Currency Module AlegroCart
class ControllerModuleCatalogcurrency extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->language 	=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->template 	=& $locator->get('template');
		$this->session  	=& $locator->get('session');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user');
		$this->modelCurrency = $model->get('model_admin_currencymodule');

		$this->language->load('controller/module_catalog_currency.php');
	}
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('catalog_currency_status', 'post') && $this->validate()) {
			$this->modelCurrency->delete_currency();
			$this->modelCurrency->update_currency();
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
		}

		$view = $this->locator->create('template');
		
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));		

		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));
		
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));
		
		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('action', $this->url->ssl('module_catalog_currency'));
		$view->set('list', $this->url->ssl('extension', FALSE, array('type' => 'module')));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'module')));
		
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (!$this->request->isPost()) {
			$results = $this->modelCurrency->get_currency();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}
		
		if ($this->request->has('catalog_currency_status', 'post')) {
			$view->set('catalog_currency_status', $this->request->gethtml('catalog_currency_status', 'post'));
		} else {
			$view->set('catalog_currency_status', @$setting_info['catalog']['currency_status']);
		}

		$this->template->set('content', $view->fetch('content/module_catalog_currency.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'module_catalog_currency')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
	function install() {
		if ($this->user->hasPermission('modify', 'module_catalog_currency')) {
			$this->modelCurrency->delete_currency();
			$this->modelCurrency->install_currency();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));			
	}
	
	function uninstall() {
		if ($this->user->hasPermission('modify', 'module_catalog_currency')) {
			$this->modelCurrency->delete_currency();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));	
	}
}
?>
<?php //ManufacturerModule AlegroCart
class ControllerModuleExtraManufacturer extends Controller {
	var $error = array();
	// All References change to module_extra_ due to new module loader  
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
		$this->modelManufacturer = $model->get('model_admin_manufactmodule');
		
		$this->language->load('controller/module_extra_manufacturer.php');
	}
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));
		
		if (($this->request->isPost()) && ($this->validate())) {
			$this->modelManufacturer->delete_manufacturer();
			$this->modelManufacturer->update_manufacturer();
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
		}
		$view = $this->locator->create('template');
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_module', $this->language->get('heading_module'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));
		$view->set('text_select', $this->language->get('text_select'));
		$view->set('text_radio', $this->language->get('text_radio'));
		$view->set('text_default_rows', $this->language->get('text_default_rows'));
		
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_addtocart', $this->language->get('entry_addtocart')); 
		$view->set('text_image', $this->language->get('text_image'));
		$view->set('entry_height', $this->language->get('entry_height'));
		$view->set('entry_width', $this->language->get('entry_width'));
		$view->set('entry_columns', $this->language->get('entry_columns'));
		$view->set('entry_display_lock', $this->language->get('entry_display_lock'));
		$view->set('entry_options_select',$this->language->get('entry_options_select'));
		$view->set('entry_items_per_page', $this->language->get('entry_items_per_page'));
		
		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('action', $this->url->ssl('module_extra_manufacturer'));
		$view->set('list', $this->url->ssl('extension', FALSE, array('type' => 'module')));

		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'module')));
		
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
		if (!$this->request->isPost()) {
			$results = $this->modelManufacturer->get_manufacturer();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}			
		if ($this->request->has('catalog_manufacturer_status', 'post')) {
			$view->set('catalog_manufacturer_status', $this->request->gethtml('catalog_manufacturer_status', 'post'));
		} else {
			$view->set('catalog_manufacturer_status', @$setting_info['catalog']['manufacturer_status']);
		}
		if ($this->request->has('catalog_manufacturer_addtocart', 'post')) {
			$view->set('catalog_manufacturer_addtocart', $this->request->gethtml('catalog_manufacturer_addtocart', 'post'));
		} else {
			$view->set('catalog_manufacturer_addtocart', @$setting_info['catalog']['manufacturer_addtocart']);
		}
		if ($this->request->has('catalog_manufacturer_image_width', 'post')) {
			$view->set('catalog_manufacturer_image_width', $this->request->gethtml('catalog_manufacturer_image_width', 'post'));
		} else {
			$view->set('catalog_manufacturer_image_width', @$setting_info['catalog']['manufacturer_image_width']);
		}
		if ($this->request->has('catalog_manufacturer_image_height', 'post')) {
			$view->set('catalog_manufacturer_image_height', $this->request->gethtml('catalog_manufacturer_image_height', 'post'));
		} else {
			$view->set('catalog_manufacturer_image_height', @$setting_info['catalog']['manufacturer_image_height']);
		}
		if ($this->request->has('catalog_manufacturer_columns', 'post')) {
			$view->set('catalog_manufacturer_columns', $this->request->gethtml('catalog_manufacturer_columns', 'post'));
		} else {
			$view->set('catalog_manufacturer_columns', @$setting_info['catalog']['manufacturer_columns']);
		}
		if ($this->request->has('catalog_manufacturer_rows', 'post')) {
			$view->set('catalog_manufacturer_rows', $this->request->gethtml('catalog_manufacturer_rows', 'post'));
		} else {
			$view->set('catalog_manufacturer_rows', @$setting_info['catalog']['manufacturer_rows']);
		}
		if ($this->request->has('catalog_manufacturer_display_lock', 'post')) {
			$view->set('catalog_manufacturer_display_lock', $this->request->gethtml('catalog_manufacturer_display_lock', 'post'));
		} else {
			$view->set('catalog_manufacturer_display_lock', @$setting_info['catalog']['manufacturer_display_lock']);
		}
		if ($this->request->has('catalog_manufacturer_options_select', 'post')) {
			$view->set('catalog_manufacturer_options_select', $this->request->gethtml('catalog_manufacturer_options_select', 'post'));
		} else {
			$view->set('catalog_manufacturer_options_select', @$setting_info['catalog']['manufacturer_options_select']);
		}
		$columns = array(1, 2, 3, 4, 5);
		$view->set('columns', $columns);
		
		$this->template->set('content', $view->fetch('content/module_extra_manufacturer.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}			
			
	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'module_extra_manufacturer')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function install() {
		if ($this->user->hasPermission('modify', 'module_extra_manufacturer')) {
			$this->modelManufacturer->delete_manufacturer();
			$this->modelManufacturer->install_manufacturer();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	
				
		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));	
	}	
	
	function uninstall() {
		if ($this->user->hasPermission('modify', 'module_extra_manufacturer')) {
			$this->modelManufacturer->delete_manufacturer();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
	}
}	
?>

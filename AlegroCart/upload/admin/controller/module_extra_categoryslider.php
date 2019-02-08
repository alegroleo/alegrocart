<?php //CategorySliderModule AlegroCart
class ControllerModuleExtraCategorySlider extends Controller {
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
		$this->modelCategorySlider = $model->get('model_admin_categoryslider');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController = $this->template->set_controller('module_extra_categoryslider');

		$this->language->load('controller/module_extra_categoryslider.php');
	}
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));
		
		if (($this->request->isPost()) && ($this->validate())) {
			$this->modelCategorySlider->delete_categoryslider();
			$this->modelCategorySlider->update_categoryslider();
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('module_extra_categoryslider'));
			} else {
				$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
			}
		}
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_module', $this->language->get('heading_module'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));
		
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('text_image', $this->language->get('text_image'));
		$view->set('entry_height', $this->language->get('entry_height'));
		$view->set('entry_width', $this->language->get('entry_width'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));

		$view->set('help', $this->session->get('help'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('action', $this->url->ssl('module_extra_categoryslider'));

		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'module')));

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$this->session->set('name_last_module', $this->language->get('heading_title'));
		$this->session->set('last_module', 'module_extra_categoryslider');
		$this->session->set('last_extension_id', $this->modelCategorySlider->get_extension_id('module_extra_categoryslider'));

		if (!$this->request->isPost()) {
			$results = $this->modelCategorySlider->get_categoryslider();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}			
		if ($this->request->has('catalog_categoryslider_status', 'post')) {
			$view->set('catalog_categoryslider_status', $this->request->gethtml('catalog_categoryslider_status', 'post'));
		} else {
			$view->set('catalog_categoryslider_status', @$setting_info['catalog']['categoryslider_status']);
		}
		if ($this->request->has('catalog_categoryslider_image_width', 'post')) {
			$view->set('catalog_categoryslider_image_width', $this->request->gethtml('catalog_categoryslider_image_width', 'post'));
		} else {
			$view->set('catalog_categoryslider_image_width', @$setting_info['catalog']['categoryslider_image_width']);
		}
		if ($this->request->has('catalog_categoryslider_image_height', 'post')) {
			$view->set('catalog_categoryslider_image_height', $this->request->gethtml('catalog_categoryslider_image_height', 'post'));
		} else {
			$view->set('catalog_categoryslider_image_height', @$setting_info['catalog']['categoryslider_image_height']);
		}

		$this->template->set('content', $view->fetch('content/module_extra_categoryslider.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}			
			
	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'module_extra_categoryslider')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}
	function install() {
		if ($this->user->hasPermission('modify', 'module_extra_categoryslider')) {
			$this->modelCategorySlider->delete_categoryslider();
			$this->modelCategorySlider->install_categoryslider();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}
		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
	}	
	function uninstall() {
		if ($this->user->hasPermission('modify', 'module_extra_categoryslider')) {
			$this->modelCategorySlider->delete_categoryslider();
			if ($this->session->get('last_module') == 'module_extra_categoryslider') {
				$this->session->delete('name_last_module');
				$this->session->delete('last_module');
			}
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}
		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
	}
}
?>

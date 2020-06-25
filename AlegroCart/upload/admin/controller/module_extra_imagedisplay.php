<?php  //ImageDisplay Page Module
class ControllerModuleExtraImageDisplay extends Controller {
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
		$this->modelImageDisplay = $model->get('model_admin_displaymodule');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController = $this->template->set_controller('module_extra_imagedisplay');

		$this->language->load('controller/module_extra_imagedisplay.php');
	}
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));
	
		if (($this->request->isPost()) && ($this->validate())) {
			$this->modelImageDisplay->delete_imagedisplay();
			$this->modelImageDisplay->update_imagedisplay();
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('module_extra_imagedisplay'));
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
		$view->set('text_imagedisplay', $this->language->get('text_imagedisplay'));
		$view->set('text_save', $this->language->get('text_save'));
		
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_home', $this->language->get('button_home'));
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('tab_module', $this->language->get('tab_module'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));

		$view->set('error', @$this->error['message']);
		$view->set('action', $this->url->ssl('module_extra_imagedisplay'));
		$view->set('action_home', $this->url->ssl('image_display'));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'module')));
		$view->set('last', $this->url->getLast('extension_module'));

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$this->session->set('name_last_module', $this->language->get('heading_title'));
		$this->session->set('last_module', 'module_extra_imagedisplay');
		$this->session->set('last_extension_id', $this->modelImageDisplay->get_extension_id('module_extra_imagedisplay'));

		if (!$this->request->isPost()) {
			$results = $this->modelImageDisplay->get_imagedisplay();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}
	
		if ($this->request->has('catalog_imagedisplay_status', 'post')) {
			$view->set('catalog_imagedisplay_status', $this->request->gethtml('catalog_imagedisplay_status', 'post'));
		} else {
			$view->set('catalog_imagedisplay_status', @$setting_info['catalog']['imagedisplay_status']);
		}

		$this->template->set('content', $view->fetch('content/module_extra_imagedisplay.tpl'));
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}
	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'module_extra_imagedisplay')) {
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
		if ($this->user->hasPermission('modify', 'module_extra_imagedisplay')) {
			$this->modelImageDisplay->delete_imagedisplay();
			$this->modelImageDisplay->install_imagedisplay();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}
		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
	}
	function uninstall() {
		if ($this->user->hasPermission('modify', 'module_extra_imagedisplay')) {
			$this->modelImageDisplay->delete_imagedisplay();
			if ($this->session->get('last_module') == 'module_extra_imagedisplay') {
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

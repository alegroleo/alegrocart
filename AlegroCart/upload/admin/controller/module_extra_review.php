<?php //Review Module AlegroCart
class ControllerModuleExtraReview extends Controller {
	var $error = array();
	// All References change to module_extra_ due to new module loader  
	function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->language		=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request		=& $locator->get('request');
		$this->response		=& $locator->get('response');
		$this->template		=& $locator->get('template');
		$this->session		=& $locator->get('session');
		$this->url		=& $locator->get('url');
		$this->user		=& $locator->get('user');
		$this->modelReview	= $model->get('model_admin_reviewmodule');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController = $this->template->set_controller('module_extra_review');

		$this->language->load('controller/module_extra_review.php');
	}

	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('catalog_review_status', 'post') && $this->validate()) {
			$this->modelReview->delete_review();
			$this->modelReview->update_review();
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('module_extra_review'));
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
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));
		$view->set('entry_image_display', $this->language->get('entry_image_display'));
		$view->set('image_displays_review',array('thickbox', 'fancybox', 'lightbox'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('action', $this->url->ssl('module_extra_review'));
		$view->set('last', $this->url->getLast('extension_module'));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'module')));

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$this->session->set('name_last_module', $this->language->get('heading_title'));
		$this->session->set('last_module', 'module_extra_review');
		$this->session->set('last_extension_id', $this->modelReview->get_extension_id('module_extra_review'));

		if (!$this->request->isPost()) {
			$results = $this->modelReview->get_review();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}

		if ($this->request->has('catalog_review_status', 'post')) {
			$view->set('catalog_review_status', $this->request->gethtml('catalog_review_status', 'post'));
		} else {
			$view->set('catalog_review_status', @$setting_info['catalog']['review_status']);
		}
		if ($this->request->has('catalog_review_image_display', 'post')) {
			$view->set('catalog_review_image_display', $this->request->gethtml('catalog_review_image_display', 'post'));
		} else {
			$view->set('catalog_review_image_display', @$setting_info['catalog']['review_image_display']);
		}

		$this->template->set('content', $view->fetch('content/module_extra_review.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'module_extra_review')) {
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
		if ($this->user->hasPermission('modify', 'module_extra_review')) {
			$this->modelReview->delete_review();
			$this->modelReview->install_review();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}
		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
	}
	function uninstall() {
		if ($this->user->hasPermission('modify', 'module_extra_review')) {
			$this->modelReview->delete_review();
			if ($this->session->get('last_module') == 'module_extra_review') {
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

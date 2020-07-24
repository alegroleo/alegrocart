<?php //BoughtProductsModule AlegroCart
class ControllerModuleExtraBought extends Controller {
	public $error = array();
	// All References change to module_extra_ due to new module loader  
	public function __construct(&$locator){
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
		$this->modelBought	= $model->get('model_admin_boughtmodule');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('module_extra_bought');

		$this->language->load('controller/module_extra_bought.php');
	}

	protected function index() { 
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->isPost()) && ($this->validate())) {
			$this->modelBought->delete_bought();
			$this->modelBought->update_bought();
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('module_extra_bought'));
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
		$view->set('text_select', $this->language->get('text_select'));
		$view->set('text_radio', $this->language->get('text_radio'));

		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_height', $this->language->get('entry_height'));
		$view->set('entry_width', $this->language->get('entry_width'));
		$view->set('entry_ratings',$this->language->get('entry_ratings'));
		$view->set('entry_columns', $this->language->get('entry_columns'));
		$view->set('entry_display_lock', $this->language->get('entry_display_lock'));
		$view->set('entry_items_per_page', $this->language->get('entry_items_per_page'));

		$view->set('explanation_entry_ratings', $this->language->get('explanation_entry_ratings'));
		$view->set('explanation_default_rows', $this->language->get('explanation_default_rows'));
		$view->set('explanation_image', $this->language->get('explanation_image'));

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
		$view->set('action', $this->url->ssl('module_extra_bought'));
		$view->set('last', $this->url->getLast('extension_module'));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'module')));

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$this->session->set('name_last_module', $this->language->get('heading_title'));
		$this->session->set('last_module', 'module_extra_bought');
		$this->session->set('last_extension_id', $this->modelBought->get_extension_id('module_extra_bought'));

		if (!$this->request->isPost()) {
			$results = $this->modelBought->get_bought();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}
		if ($this->request->has('catalog_bought_status', 'post')) {
			$view->set('catalog_bought_status', $this->request->gethtml('catalog_bought_status', 'post'));
		} else {
			$view->set('catalog_bought_status', @$setting_info['catalog']['bought_status']);
		}
		if ($this->request->has('catalog_bought_image_width', 'post')) {
			$view->set('catalog_bought_image_width', $this->request->gethtml('catalog_bought_image_width', 'post'));
		} else {
			$view->set('catalog_bought_image_width', @$setting_info['catalog']['bought_image_width']);
		}
		if ($this->request->has('catalog_bought_image_height', 'post')) {
			$view->set('catalog_bought_image_height', $this->request->gethtml('catalog_bought_image_height', 'post'));
		} else {
			$view->set('catalog_bought_image_height', @$setting_info['catalog']['bought_image_height']);
		}
		if ($this->request->has('catalog_bought_ratings', 'post')) {
			$view->set('catalog_bought_ratings', $this->request->gethtml('catalog_bought_ratings', 'post'));
		} else {
			$view->set('catalog_bought_ratings', @$setting_info['catalog']['bought_ratings']);
		}
		if ($this->request->has('catalog_bought_columns', 'post')) {
			$view->set('catalog_bought_columns', $this->request->gethtml('catalog_bought_columns', 'post'));
		} else {
			$view->set('catalog_bought_columns', @$setting_info['catalog']['bought_columns']);
		}
		if ($this->request->has('catalog_bought_rows', 'post')) {
			$view->set('catalog_bought_rows', $this->request->gethtml('catalog_bought_rows', 'post'));
		} else {
			$view->set('catalog_bought_rows', @$setting_info['catalog']['bought_rows']);
		}
		if ($this->request->has('catalog_bought_display_lock', 'post')) {
			$view->set('catalog_bought_display_lock', $this->request->gethtml('catalog_bought_display_lock', 'post'));
		} else {
			$view->set('catalog_bought_display_lock', @$setting_info['catalog']['bought_display_lock']);
		}

		$columns = array(1, 2, 3, 4, 5);
		$view->set('columns', $columns);
		
		$this->template->set('content', $view->fetch('content/module_extra_bought.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	private function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'module_extra_bought')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	protected function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}

	protected function install() {
		if ($this->user->hasPermission('modify', 'module_extra_bought')) {
			$this->modelBought->delete_bought();
			$this->modelBought->install_bought();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}
		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
	}

	protected function uninstall() {
		if ($this->user->hasPermission('modify', 'module_extra_bought')) {
			$this->modelBought->delete_bought();
			if ($this->session->get('last_module') == 'module_extra_bought') {
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

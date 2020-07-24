<?php //BoughtOptions AlegroCart
class ControllerModuleExtraBoughtoptions extends Controller {
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
		$this->modelBoughtOptions = $model->get('model_admin_boughtoptions');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('module_extra_boughtoptions');

		$this->language->load('controller/module_extra_boughtoptions.php');
	}

	protected function index() { 
		$this->template->set('title', $this->language->get('heading_title'));
		
		if (($this->request->isPost()) && ($this->validate())) {
			$this->modelBoughtOptions->delete_bought_options();
			$this->modelBoughtOptions->update_bought_options();
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('module_extra_boughtoptions'));
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
		$view->set('action', $this->url->ssl('module_extra_boughtoptions'));
		$view->set('last', $this->url->getLast('extension_module'));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'module')));

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$this->session->set('name_last_module', $this->language->get('heading_title'));
		$this->session->set('last_module', 'module_extra_boughtoptions');
		$this->session->set('last_extension_id', $this->modelBoughtOptions->get_extension_id('module_extra_boughtoptions'));

		if (!$this->request->isPost()) {
			$results = $this->modelBoughtOptions->get_bought_options();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}
		if ($this->request->has('catalog_bought_options_status', 'post')) {
			$view->set('catalog_bought_options_status', $this->request->gethtml('catalog_bought_options_status', 'post'));
		} else {
			$view->set('catalog_bought_options_status', @$setting_info['catalog']['bought_options_status']);
		}

		$this->template->set('content', $view->fetch('content/module_extra_boughtoptions.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	private function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'module_extra_boughtoptions')) {
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
		if ($this->user->hasPermission('modify', 'module_extra_boughtoptions')) {
			$this->modelBoughtOptions->delete_bought_options();
			$this->modelBoughtOptions->install_bought_options();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}
		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
	}

	protected function uninstall() {
		if ($this->user->hasPermission('modify', 'module_extra_boughtoptions')) {
			$this->modelBoughtOptions->delete_bought_options();
			if ($this->session->get('last_module') == 'module_extra_boughtoptions') {
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

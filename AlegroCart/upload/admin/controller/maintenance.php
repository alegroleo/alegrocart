<?php // Maintenance AlegroCart
class ControllerMaintenance extends Controller{
	var $error = array();
	function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->language		=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request		=& $locator->get('request');
		$this->response		=& $locator->get('response');
		$this->session		=& $locator->get('session');
		$this->template		=& $locator->get('template');
		$this->url		=& $locator->get('url');
		$this->user		=& $locator->get('user');
		$this->modelMaintenance	= $model->get('model_admin_maintenance');
		$this->language->load('controller/maintenance.php');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('maintenance');
	}
	function index(){
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->isPost()) && ($this->validate())) {
			$this->modelMaintenance->delete_maintenance();
			$this->modelMaintenance->update_maintenance();
			$this->modelMaintenance->delete_description();
			$this->modelMaintenance->update_description();
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('maintenance'));
			} else {
				$this->response->redirect($this->url->ssl('home'));
			}
		}
	
		$view = $this->locator->create('template');
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));
		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_description', $this->language->get('entry_description'));
		$view->set('entry_header', $this->language->get('entry_header'));

		$view->set('explanation_entry_status', $this->language->get('explanation_entry_status'));

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
		$view->set('tab_description', $this->language->get('tab_description'));

		$view->set('error_update', $this->language->get('error_update'));
		$view->set('error', @$this->error['message']);
		$view->set('error_header', @$this->error['header']);
		$view->set('error_description', @$this->error['description']);

		$view->set('message', $this->session->get('message'));

		$this->session->delete('message');
		$view->set('action', $this->url->ssl('maintenance'));
		$view->set('cancel', $this->url->ssl('maintenance'));
		$view->set('last', $this->url->getLast('maintenance'));

		$view->set('tab', $this->session->has('maintenance_tab') ? $this->session->get('maintenance_tab') : 0);
		$view->set('tabmini', $this->session->has('maintenance_tabmini') ? $this->session->get('maintenance_tabmini') : 0);

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$maintenance_description_data = array();
		$results = $this->modelMaintenance->get_languages();

		foreach ($results as $result) {
			if($result['language_status'] =='1'){
				if (!$this->request->isPost()) {
					$maintenance_description_info = $this->modelMaintenance->get_descriptions($result['language_id']);
				}
				$header = $this->request->get('header', 'post');
				$description = $this->request->get('description', 'post');

				$maintenance_description_data[] = array(
					'language_id'	=> $result['language_id'],
					'language'	=> $result['name'],
					'header'	=> (isset($header[$result['language_id']]) ? $header[$result['language_id']] : @$maintenance_description_info['header']),
					'description'	=> (isset($description[$result['language_id']]) ? $description[$result['language_id']] : @$maintenance_description_info['description'])
				);
			}
		}

		$view->set('maintenance_descriptions', $maintenance_description_data);

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
		$view->set('head_def',$this->head_def); 
		$this->template->set('content', $view->fetch('content/maintenance.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));

	}

	function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
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

		foreach ($this->request->get('header', 'post', array()) as $key =>$value) {
			if (!$value) {
				$this->error['header'][$key] = $this->language->get('error_header');
			}
		}
		foreach ($this->request->get('description', 'post', array()) as $key =>$value) {
			if (!$value) {
				$this->error['description'][$key] = $this->language->get('error_description');
			}
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function tab() {
		if ($this->request->isPost()) {
			if ($this->request->has('activeTab', 'post')) {
				$this->session->set('maintenance_tab', $this->request->sanitize('activeTab', 'post'));
				if ($this->request->has('activeTabmini', 'post')) {
					$this->session->set('maintenance_tabmini', $this->request->sanitize('activeTabmini', 'post'));
				}
				$output = array('status' => true);
			} else {
				$output = array('status' => false);
			}
			echo json_encode($output);
		}
	}
}
?>

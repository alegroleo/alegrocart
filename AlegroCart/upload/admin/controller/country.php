<?php  // Country AlegroCart
class ControllerCountry extends Controller {
	var $error = array();
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
		$this->validate 	=& $locator->get('validate');
		$this->modelCountry = $model->get('model_admin_country');
		
		$this->language->load('controller/country.php');
	}
	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function insert() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('name', 'post') && $this->validateForm()) {
			$this->modelCountry->insert_country();
			$this->cache->delete('country');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('country'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('name', 'post') && $this->validateForm()) {
			$this->modelCountry->update_country();
			$this->cache->delete('country');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('country'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function enableDisable(){
		$this->template->set('title', $this->language->get('heading_title'));
		
		if($this->validateEnable()){
			if ($this->modelCountry->check_status()){
				$status = 0;
			} else {
				$status = 1;
			}
			$this->modelCountry->set_status($status);
			$this->cache->delete('country');	
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('country'));
		} else {
			$this->session->set('message', @$this->error['message']);
		}
		$this->response->redirect($this->url->ssl('country'));
	}
 
	function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('country_id')) && ($this->validateDelete())) {
			$this->modelCountry->delete_country();
			$this->cache->delete('country');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('country'));
		}
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function getList() {
		$this->session->set('country_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_name'),
			'sort'  => 'name',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_country_status'),
			'sort'  => 'country_status',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_iso_code_2'),
			'sort'  => 'iso_code_2',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_iso_code_3'),
			'sort'  => 'iso_code_3',
			'align' => 'left'
		);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'right'
    	);

		$results = $this->modelCountry->get_page();
		$rows = array();
		foreach ($results as $result) {
			$cell = array();
			$cell[] = array(
				'value'   => $result['name'],
				'align'   => 'left',
				'default' => ($result['country_id'] == $this->config->get('config_country_id'))
			);
			$cell[] = array(
        		'icon'  => ($result['country_status'] ? 'enabled.png' : 'disabled.png'),
        		'align' => 'left'
      		);
			$cell[] = array(
				'value' => $result['iso_code_2'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $result['iso_code_3'],
				'align' => 'left'
			);
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('country', 'update', array('country_id' => $result['country_id']))
      		);

			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('country', 'delete', array('country_id' => $result['country_id'],'country_validation' =>$this->session->get('country_validation')))
				);
			}
			
      		$cell[] = array(
        		'action' => $action,
        		'align'  => 'right'
      		);
			$rows[] = array('cell' => $cell);
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_default', $this->language->get('text_default'));
		$view->set('text_results', $this->modelCountry->get_text_results());

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
 		$view->set('button_refresh', $this->language->get('button_enable_disable'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete')); // In English.php
		
		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('country', 'page'));
		$view->set('action_refresh', $this->url->ssl('country', 'enableDisable'));
		$view->set('action_delete', $this->url->ssl('country', 'enableDelete'));

		$view->set('search', $this->session->get('country.search'));
		$view->set('sort', $this->session->get('country.sort'));
		$view->set('order', $this->session->get('country.order'));
		$view->set('page', $this->session->get('country.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $this->url->ssl('country'));
		$view->set('insert', $this->url->ssl('country', 'insert'));

		$view->set('pages', $this->modelCountry->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

    	$view->set('text_enabled', $this->language->get('text_enabled'));
    	$view->set('text_disabled', $this->language->get('text_disabled'));
		$view->set('entry_name', $this->language->get('entry_name'));
		$view->set('entry_country_status', $this->language->get('entry_country_status'));
		$view->set('entry_iso_code_2', $this->language->get('entry_iso_code_2'));
		$view->set('entry_iso_code_3', $this->language->get('entry_iso_code_3'));
		$view->set('entry_address_format', $this->language->get('entry_address_format'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_name', @$this->error['name']);

		$view->set('action', $this->url->ssl('country', $this->request->gethtml('action'), array('country_id' => $this->request->gethtml('country_id'))));

		$view->set('list', $this->url->ssl('country'));
		$view->set('insert', $this->url->ssl('country', 'insert'));
		$view->set('cancel', $this->url->ssl('country'));
		
		if ($this->request->gethtml('country_id')) {
			$view->set('update', 'update');
			$view->set('delete', $this->url->ssl('country', 'delete', array('country_id' => $this->request->gethtml('country_id'),'country_validation' => $this->session->get('country_validation'))));
		}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (($this->request->gethtml('country_id')) && (!$this->request->isPost())) {
			$country_info = $this->modelCountry->get_country_info();
		}

		if ($this->request->has('name', 'post')) {
			$view->set('name', $this->request->gethtml('name', 'post'));
		} else {
			$view->set('name', @$country_info['name']);
		}
		
		if ($this->request->has('country_status', 'post')) {
      		$view->set('country_status', $this->request->gethtml('country_status', 'post'));
    	} else {
      		$view->set('country_status', @$country_info['country_status']);
    	}		

		if ($this->request->has('iso_code_2', 'post')) {
			$view->set('iso_code_2', $this->request->gethtml('iso_code_2', 'post'));
		} else {
			$view->set('iso_code_2', @$country_info['iso_code_2']);
		}

		if ($this->request->has('iso_code_3', 'post')) {
			$view->set('iso_code_3', $this->request->gethtml('iso_code_3', 'post'));
		} else {
			$view->set('iso_code_3', @$country_info['iso_code_3']);
		}

		if ($this->request->has('address_format', 'post')) {
			$view->set('address_format', $this->request->gethtml('address_format', 'post'));
		} else {
			$view->set('address_format', @$country_info['address_format']);
		}

		return $view->fetch('content/country.tpl');
	}

	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'country')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
        if (!$this->validate->strlen($this->request->gethtml('name', 'post'),1,32)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		if ($this->config->get('config_country_id') == $this->request->gethtml('country_id') && $this->request->gethtml('country_status', 'post') == FALSE) {
			$this->error['message'] = $this->language->get('error_default');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	function validateEnable() {
		if (!$this->user->hasPermission('modify', 'country')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	function enableDelete(){
		$this->template->set('title', $this->language->get('heading_title'));
		if($this->validateEnableDelete()){
			if($this->session->get('enable_delete')){
				$this->session->delete('enable_delete');
			} else {
				$this->session->set('enable_delete', TRUE);
			}
			$this->response->redirect($this->url->ssl('country'));//**
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('country'));//**
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'country')) {//**
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}
	function validateDelete() {
		if(($this->session->get('country_validation') != $this->request->sanitize('country_validation')) || (strlen($this->session->get('country_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('country_validation');
		if (!$this->user->hasPermission('modify', 'country')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if ($this->config->get('config_country_id') == $this->request->gethtml('country_id')) {
			$this->error['message'] = $this->language->get('error_default');
		}
		$address_info = $this->modelCountry->check_address();
		if ($address_info['total']) {
			$this->error['message'] = $this->language->get('error_address', $address_info['total']);
		}
		$zone_info = $this->modelCountry->check_zone();
		if ($zone_info['total']) {
			$this->error['message'] = $this->language->get('error_zone', $zone_info['total']);
		}
		$zone_to_geo_zone_info = $this->modelCountry->check_zone_to_geo();
		if ($zone_to_geo_zone_info['total']) {
			$this->error['message'] = $this->language->get('error_zone_to_geo_zone', $zone_to_geo_zone_info['total']);
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function page() {
		if ($this->request->has('search', 'post')) {
			$this->session->set('country.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('country.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('country.order', (($this->session->get('country.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('country.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('country.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('country'));
	}	
}
?>
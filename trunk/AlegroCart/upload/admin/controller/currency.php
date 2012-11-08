<?php // Currency AlegroCart
class ControllerCurrency extends Controller {
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
		$this->modelCurrency = $model->get('model_admin_currency');
		
		$this->language->load('controller/currency.php');
	}
	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function insert() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('title', 'post') && $this->validateForm()) {
			$this->modelCurrency->insert_currency();
			$this->cache->delete('currency');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('currency'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('title', 'post') && $this->validateForm()) {
			$this->modelCurrency->update_currency();
			$this->cache->delete('currency');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('currency'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function updateRates() {
		if($this->validateUpdate()){
			set_time_limit(90);
			$start_time = microtime(true);
			$from = $this->config->get('config_currency');
			$results = $this->modelCurrency->get_codes();
			$base_rate = 1.00 + $this->config->get('config_currency_surcharge');
			
			$status_all = ($this->request->gethtml('refresh_all','post') == $this->language->get('checkbox_value')) ? TRUE : FALSE;
				
			foreach ($results as $to) {		
				if($status_all || $to['status']){
					$rate = $this->currency->currency_converter($base_rate, $from, $to['code']);
					$rate = str_replace(',','.',$rate); // Fix Comma
					if ($rate > 0 && $to['lock_rate'] == FALSE ){
						$this->modelCurrency->update_rates($rate, $to['code']);
					}
				}
				if((microtime(true)-$start_time)>88){
					$this->session->set('message', $this->language->get('error_time'));
					$this->cache->delete('currency');
					$this->response->redirect($this->url->ssl('currency'));
				}
			}		
			$this->cache->delete('currency');		
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('message', @$this->error['message']);
		}
		$this->response->redirect($this->url->ssl('currency'));
		
	}

	function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('currency_id')) && ($this->validateDelete())) {
			$this->modelCurrency->delete_currency();
			$this->cache->delete('currency');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('currency'));
		}
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function changeStatus() { 
		
		if (($this->request->has('stat_id')) && ($this->request->has('stat')) && $this->validateChangeStatus()) {

			$this->modelCurrency->change_currency_status($this->request->gethtml('stat'), $this->request->gethtml('stat_id'));
			$this->cache->delete('currency');
		}
	
	}

	function getList() {
		$this->session->set('currency_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_title'),
			'sort'  => 'title',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_code'),
			'sort'  => 'code',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_value'),
			'sort'  => 'value',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_status'),
			'sort'  => 'status',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_lock_rate'),
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_date_modified'),
			'sort'  => 'date_modified',
			'align' => 'right'
		);
		$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'action'
    	);
		
		$results = $this->modelCurrency->get_page();
		$rows = array();
		foreach ($results as $result) {
			$cell = array();
			$cell[] = array(
				'value'   => $result['title'],
				'align'   => 'left',
				'default' => ($result['code'] == $this->config->get('config_currency'))
			);
			$cell[] = array(
				'value' => $result['code'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $result['value'],
				'align' => 'center'
			);
			if ($this->validateChangeStatus() && $this->config->get('config_currency') !== $result['code']) {
			$cell[] = array(
				'status'  => $result['status'],
				'text' => $this->language->get('button_status'),
				'align' => 'center',
				'status_id' => $result['currency_id'],
				'status_controller' => 'currency'
			);

			} else {
			$cell[] = array(
				'icon'  => ($result['status'] ? 'enabled.png' : 'disabled.png'),
				'align' => 'center'
			);
			}
			$cell[] = array(
				'icon'  => ($result['lock_rate'] || ($result['code'] == $this->config->get('config_currency')) ? 'disable_update.png' : 'enable_update.png'),
				'align'  => 'center'
			);
			$cell[] = array(
				'value' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'align' => 'right'
			);
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('currency', 'update', array('currency_id' => $result['currency_id']))
      		);
			
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('currency', 'delete', array('currency_id' => $result['currency_id'],'currency_validation' =>$this->session->get('currency_validation')))
				);
			}
			
      		$cell[] = array(
        		'action' => $action,
        		'align'  => 'action'
      		);
			$rows[] = array('cell' => $cell);
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description', $this->config->get('config_currency_surcharge')));

		$view->set('text_default', $this->language->get('text_default'));
		$view->set('text_results', $this->modelCurrency->get_text_results());

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_refresh', $this->language->get('button_rate'));
		$view->set('checkbox_name', 'refresh_all');
		$view->set('checkbox_value', $this->language->get('checkbox_value'));
		$view->set('button_enable_disable', $this->language->get('button_enable_disable'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_status', $this->language->get('button_status'));

		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('currency', 'page'));
		$view->set('action_enable_disable', $this->url->ssl('currency', 'enableDisable'));
		$view->set('action_refresh', $this->url->ssl('currency', 'updateRates'));
		$view->set('action_delete', $this->url->ssl('currency', 'enableDelete'));
		
		$view->set('search', $this->session->get('currency.search'));
		$view->set('sort', $this->session->get('currency.sort'));
		$view->set('order', $this->session->get('currency.order'));
		$view->set('page', $this->session->get('currency.page'));
 
		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $this->url->ssl('currency'));
		$view->set('insert', $this->url->ssl('currency', 'insert'));

		$view->set('pages', $this->modelCurrency->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_form_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_lock_rate', $this->language->get('text_lock_rate'));
		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));
		$view->set('text_default_rate', $this->language->get('text_default_rate'));
		
		$view->set('entry_title', $this->language->get('entry_title'));
		$view->set('entry_code', $this->language->get('entry_code'));
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_lock_rate', $this->language->get('entry_lock_rate'));
		$view->set('entry_value', $this->language->get('entry_value'));
		$view->set('entry_symbol_left', $this->language->get('entry_symbol_left'));
		$view->set('entry_symbol_right', $this->language->get('entry_symbol_right'));
		$view->set('entry_decimal_place', $this->language->get('entry_decimal_place'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_title', @$this->error['title']);
		$view->set('error_code', @$this->error['code']);
		$view->set('error_default', @$this->error['default']);

		$view->set('action', $this->url->ssl('currency', $this->request->gethtml('action'), array('currency_id' => $this->request->gethtml('currency_id'))));
		$view->set('list', $this->url->ssl('currency'));
		$view->set('insert', $this->url->ssl('currency', 'insert'));
		$view->set('cancel', $this->url->ssl('currency'));

		if ($this->request->gethtml('currency_id')) {
			$view->set('update', $this->url->ssl('currency', 'update', array('currency_id' => $this->request->gethtml('currency_id'))));
			$view->set('delete', $this->url->ssl('currency', 'delete', array('currency_id' => $this->request->gethtml('currency_id'),'currency_validation' =>$this->session->get('currency_validation'))));
		}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (($this->request->gethtml('currency_id')) && (!$this->request->isPost())) {
			$currency_info = $this->modelCurrency->get_currency();
		}

		if ($this->request->has('title', 'post')) {
			$view->set('title', $this->request->gethtml('title', 'post'));
		} else {
			$view->set('title', @$currency_info['title']);
		}

		if ($this->request->has('code', 'post')) {
			$view->set('code', $this->request->gethtml('code', 'post'));
		} else {
			$view->set('code', @$currency_info['code']);
		}
		
		if ($this->request->has('status', 'post')) {
			$view->set('status', $this->request->gethtml('status', 'post'));
		} else {
			$view->set('status', @$currency_info['status']);
		}
		
		if ($this->request->has('lock_rate', 'post')) {
			$view->set('lock_rate', $this->request->gethtml('lock_rate', 'post'));
		} else {
			$view->set('lock_rate', @$currency_info['lock_rate']);
		}

		if ($this->request->has('symbol_left', 'post')) {
			$view->set('symbol_left', $this->request->gethtml('symbol_left', 'post'));
		} else {
			$view->set('symbol_left', @$currency_info['symbol_left']);
		}

		if ($this->request->has('symbol_right', 'post')) {
			$view->set('symbol_right', $this->request->gethtml('symbol_right', 'post'));
		} else {
			$view->set('symbol_right', @$currency_info['symbol_right']);
		}

		if ($this->request->has('decimal_place', 'post')) {
			$view->set('decimal_place', $this->request->gethtml('decimal_place', 'post'));
		} else {
			$view->set('decimal_place', @$currency_info['decimal_place']);
		}

		if ($this->request->has('value', 'post')) {
			$view->set('value', $this->request->gethtml('value', 'post'));
		} else {
			$view->set('value', @$currency_info['value']);
		}

		return $view->fetch('content/currency.tpl');
	}
	
	function enableDisable(){
		if($this->validateUpdate()){
			if($this->modelCurrency->check_status()){
				$status = 0;
			} else {
				$status = 1;
			}
			$this->modelCurrency->set_status($status);
			$this->cache->delete('currency');
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('message', @$this->error['message']);
		}
		$this->response->redirect($this->url->ssl('currency'));
	}
	
	function validateUpdate(){
		if (!$this->user->hasPermission('modify', 'currency')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}		
	}
	
	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'currency')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->validate->strlen($this->request->gethtml('title', 'post'),1,32)) {
			$this->error['title'] = $this->language->get('error_title');
		}

        if (!$this->validate->strlen($this->request->gethtml('code', 'post'),3,3)) {
			$this->error['code'] = $this->language->get('error_code');
		}
		$result = $this->modelCurrency->check_default();
		if ($this->config->get('config_currency') == $result['code'] && $this->request->gethtml('status', 'post') == FALSE){
			$this->error['default'] = $this->language->get('error_disable');
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
			$this->response->redirect($this->url->ssl('currency'));//**
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('currency'));//**
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'currency')) {//**
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}

	function validateDelete() {
		if(($this->session->get('currency_validation') != $this->request->sanitize('currency_validation')) || (strlen($this->session->get('currency_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('currency_validation');
		if (!$this->user->hasPermission('modify', 'currency')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		$result = $this->modelCurrency->check_default();
		if ($this->config->get('config_currency') == $result['code']) {
			$this->error['message'] = $this->language->get('error_default');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}	

	function validateChangeStatus(){
				
		if (!$this->user->hasPermission('modify', 'currency')) {
	      		return FALSE;
	    	}  else {
			return TRUE;
		}
	}

	function page() {
		if ($this->request->has('search', 'post')) {
			$this->session->set('currency.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('currency.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('currency.order', (($this->session->get('currency.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('currency.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('currency.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('currency'));
	}		
}
?>

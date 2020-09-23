<?php // Currency AlegroCart
class ControllerCurrency extends Controller {

	public $error = array();

	public function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->cache		=& $locator->get('cache');
		$this->config		=& $locator->get('config');
		$this->currency		=& $locator->get('currency');
		$this->language		=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request		=& $locator->get('request');
		$this->response		=& $locator->get('response');
		$this->session		=& $locator->get('session');
		$this->template		=& $locator->get('template');
		$this->url		=& $locator->get('url');
		$this->user		=& $locator->get('user'); 
		$this->validate		=& $locator->get('validate');
		$this->modelCurrency	= $model->get('model_admin_currency');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('currency');

		$this->language->load('controller/currency.php');
	}

	protected function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function insert() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('title', 'post') && $this->validateForm()) {
			$this->modelCurrency->insert_currency();
			$insert_id = $this->modelCurrency->get_last_id();
			$this->session->set('last_currency_id', $insert_id);
			$this->cache->delete('currency');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('currency'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('title', 'post') && $this->validateForm()) {
			$this->modelCurrency->update_currency();
			$this->cache->delete('currency');
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('currency', 'update', array('currency_id' => $this->request->gethtml('currency_id'))));
			} else {
				$this->response->redirect($this->url->ssl('currency'));
			}

		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function updateRates() {
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

	protected function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('currency_id')) && ($this->validateDelete())) {
			$this->modelCurrency->delete_currency();
			$this->cache->delete('currency');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('currency'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function changeStatus() { 
		if (($this->request->has('stat_id')) && ($this->request->has('stat')) && $this->validateChangeStatus()) {
			$this->modelCurrency->change_currency_status($this->request->gethtml('stat'), $this->request->gethtml('stat_id'));
			$this->cache->delete('currency');
		}
	}

	private function getList() {
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
		$currencies = $this->modelCurrency->get_BankAccountCurrencies();
			$bacurrencies = array();
			foreach ($currencies as $currency){
				$bacurrencies[] = $currency['currency'];
			}
		$rows = array();

		foreach ($results as $result) {
			$last = $result['currency_id'] == $this->session->get('last_currency_id') ? 'last_visited': '';
			$cell = array();
			$cell[] = array(
				'value'   => $result['title'],
				'align'   => 'left',
				'default' => ($result['code'] == $this->config->get('config_currency')),
				'bank_account'  => $bacurrencies ? ($bacurrencies[0] === '0' ? ($bacurrencies[0] === '0' && $result['status']) : in_array($result['code'], $bacurrencies)) : NULL,
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['code'],
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['value'],
				'align' => 'center',
				'last' => $last
			);
			if ($this->validateChangeStatus() && $this->config->get('config_currency') !== $result['code'] && ($bacurrencies ? ($bacurrencies[0] === '0' ? !($bacurrencies[0] === '0' && $result['status']) : !in_array($result['code'], $bacurrencies)) : true)) {
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
				'align' => 'center',
				'text'  => $this->language->get('button_status')
			);
			}
			$cell[] = array(
				'icon'  => ($result['lock_rate'] || ($result['code'] == $this->config->get('config_currency')) ? 'disable_update.png' : 'enable_update.png'),
				'align'  => 'center',
				'text'  => ($result['lock_rate'] || ($result['code'] == $this->config->get('config_currency')) ? $this->language->get('text_update_disabled') : $this->language->get('text_update_enabled'))
			);
			$cell[] = array(
				'value' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'align' => 'right',
				'last' => $last
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
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description', $this->config->get('config_currency_surcharge')));

		if($bacurrencies){
			$view->set('allcurr', $bacurrencies[0] === '0' ? 1 : 0);
		}

		$view->set('text_default', $this->language->get('text_default'));
		$view->set('text_results', $this->modelCurrency->get_text_results());
		$view->set('text_bank_account', $this->language->get('text_bank_account'));
		$view->set('text_asc', $this->language->get('text_asc'));
		$view->set('text_desc', $this->language->get('text_desc'));

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

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
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));
		$view->set('controller', 'currency');
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

		$view->set('insert', $this->url->ssl('currency', 'insert'));
		$view->set('last', $this->url->getLast('currency'));

		$view->set('pages', $this->modelCurrency->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	private function getForm() {
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
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
		$view->set('error_title', @$this->error['title']);
		$view->set('error_code', @$this->error['code']);
		$view->set('error_default', @$this->error['default']);
		$view->set('error_bank_account', @$this->error['bank_account']);

		$view->set('action', $this->url->ssl('currency', $this->request->gethtml('action'), array('currency_id' => $this->request->gethtml('currency_id'))));
		$view->set('insert', $this->url->ssl('currency', 'insert'));
		$view->set('cancel', $this->url->ssl('currency'));
		$view->set('last', $this->url->getLast('currency'));

		if ($this->request->gethtml('currency_id')) {
			$view->set('update', $this->url->ssl('currency', 'update', array('currency_id' => $this->request->gethtml('currency_id'))));
			$view->set('delete', $this->url->ssl('currency', 'delete', array('currency_id' => $this->request->gethtml('currency_id'),'currency_validation' =>$this->session->get('currency_validation'))));
		}

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('currency_id', $this->request->gethtml('currency_id'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$this->session->set('last_currency_id', $this->request->gethtml('currency_id'));

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

	protected function enableDisable(){
		if($this->validateUpdate()){ //permission check
			if($this->modelCurrency->check_status()){
				$status = 0;
			} else {
				$status = 1;
			}
			$currencies = $this->modelCurrency->get_BankAccountCurrencies();
			$bacurrencies = array();
			foreach ($currencies as $currency){
				$bacurrencies[] = $currency['currency'];
			}
			if ($bacurrencies && $bacurrencies[0] ==='0') { //if All currencies selected
				$enabledCurrencies = $this->modelCurrency->get_enabledCurrencies();
				$bacurrencies = array();
				foreach ($enabledCurrencies as $enabledCurrency){
					$bacurrencies[] = $enabledCurrency['code'];
				}
			}
			$this->modelCurrency->set_status($status, $bacurrencies);
			$this->cache->delete('currency');
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('message', @$this->error['message']);
		}
		$this->response->redirect($this->url->ssl('currency'));
	}

	private function validateUpdate(){
		if (!$this->user->hasPermission('modify', 'currency')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateForm() {
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

		if($this->request->has('status', 'post') && !$this->request->gethtml('status','post') && (int)$this->request->gethtml('currency_id') > 0){ //if currency_id is zero, it is insertion
			$currencies = $this->modelCurrency->get_BankAccountCurrencies();
			$bacurrencies = array();
			foreach ($currencies as $currency){
				$bacurrencies[] = $currency['currency'];
			}
			if ($bacurrencies && $bacurrencies[0] === '0' || in_array($this->request->gethtml('code','post'), $bacurrencies)) {
				$this->error['message'] = $this->language->get('error_ba_disable');
				if ($bacurrencies[0] === '0') {
					$ba_list = $this-> modelCurrency->get_currencyToBAccounts();
				} else {
					$ba_list = $this-> modelCurrency->get_currencyToBAccount();
				}
					$this->error['message'] .= '<br>';
					foreach ($ba_list as $ba) {
						$this->error['message'] .= '<a href="' . $this->url->ssl('bank_account', 'update', array('bank_account_id' => $ba['bank_account_id'])) . '">' . $ba['ban'] .'</a>&nbsp;';
					}
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	protected function enableDelete(){
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

	private function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'currency')) {//**
		$this->error['message'] = $this->language->get('error_permission');  
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
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
		$currencies = $this->modelCurrency->get_BankAccountCurrencies();
			$bacurrencies = array();
			foreach ($currencies as $currency){
				$bacurrencies[] = $currency['currency'];
			}
		$currency_info=$this->modelCurrency->get_currency();
		if ($bacurrencies && $bacurrencies[0] === '0' && $currency_info['status'] || in_array($currency_info['code'], $bacurrencies)) {
			$this->error['message'] = $this->language->get('error_bank_account');
			if ($bacurrencies[0] === '0') {
				$ba_list = $this-> modelCurrency->get_currencyToBAccounts();
			} else {
				$ba_list = $this-> modelCurrency->get_currencyToBAccount();
			}
				$this->error['message'] .= '<br>';
				foreach ($ba_list as $ba) {
					$this->error['message'] .= '<a href="' . $this->url->ssl('bank_account', 'update', array('bank_account_id' => $ba['bank_account_id'])) . '">' . $ba['ban'] .'</a>&nbsp;';
				}
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateChangeStatus(){
		if (!$this->user->hasPermission('modify', 'currency')) {
			return FALSE;
		}  else {
			return TRUE;
		}
	}

	protected function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}

	protected function page() {
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

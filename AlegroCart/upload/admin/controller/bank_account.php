<?php // BankAccount AlegroCart
class ControllerBankAccount extends Controller {
	public $error = array();
	public function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->language		=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->config		=& $locator->get('config');
		$this->request		=& $locator->get('request');
		$this->response		=& $locator->get('response');
		$this->session		=& $locator->get('session');
		$this->template		=& $locator->get('template');
		$this->url		=& $locator->get('url');
		$this->user		=& $locator->get('user'); 
		$this->validate		=& $locator->get('validate');
		$this->modelBankAccount	= $model->get('model_admin_bankaccount');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('bank_account');

		$this->language->load('controller/bank_account.php');
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

		if ($this->request->isPost() && $this->request->has('currency', 'post') && $this->validateForm()) {
			$this->modelBankAccount->insert_bankaccount();
			$insert_id = $this->modelBankAccount->get_last_id();

			$this->session->set('last_bank_account_id', $insert_id);

			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('bank_account'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('currency', 'post') && $this->validateForm()) {
			$this->modelBankAccount->update_bankaccount();
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('bank_account', 'update', array('bank_account_id' => $this->request->gethtml('bank_account_id', 'post'))));
			} else {
				$this->response->redirect($this->url->ssl('bank_account'));
			}

		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function delete() { 
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('bank_account_id')) && ($this->validateDelete())) {
			$this->modelBankAccount->delete_bankaccount();
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('bank_account'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	private function getList() {
		$this->session->set('bank_account_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_currency'),
			'sort'  => 'currency',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_ban'),
			'sort'  => 'ban',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_action'),
			'align' => 'action'
		);

		$results = $this->modelBankAccount->get_page();
		$rows = array();
		foreach ($results as $result) {
			$last = $result['bank_account_id'] == $this->session->get('last_bank_account_id') ? 'last_visited': '';
			$cell = array();
			$cell[] = array(
				'value' => $result['currency'] ? $result['currency'] : $this->language->get('text_all_currencies'),
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['ban'],
				'align' => 'left',
				'last' => $last
			);

			$action = array();
			$action[] = array(
				'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('bank_account', 'update', array('bank_account_id' => $result['bank_account_id']))
			);
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('bank_account', 'delete', array('bank_account_id' => $result['bank_account_id'],'bank_account_validation' =>$this->session->get('bank_account_validation')))
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
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_results', $this->modelBankAccount->get_text_results());

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));
 
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));
		$view->set('controller', 'bank_account');
		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('bank_account', 'page'));
		$view->set('action_delete', $this->url->ssl('bank_account', 'enableDelete'));

		$view->set('search', $this->session->get('bank_account.search'));
		$view->set('sort', $this->session->get('bank_account.sort'));
		$view->set('order', $this->session->get('bank_account.order'));
		$view->set('page', $this->session->get('bank_account.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('insert', $this->url->ssl('bank_account', 'insert'));
		$view->set('last', $this->url->getLast('bank_account'));

		$view->set('pages', $this->modelBankAccount->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	private function getForm() {
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_form_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_all_currencies', $this->language->get('text_all_currencies'));

		$view->set('entry_currency', $this->language->get('entry_currency'));
		$view->set('entry_bank_name', $this->language->get('entry_bank_name'));
		$view->set('entry_bank_address', $this->language->get('entry_bank_address'));
		$view->set('entry_owner', $this->language->get('entry_owner'));
		$view->set('entry_ban', $this->language->get('entry_ban'));
		$view->set('entry_iban', $this->language->get('entry_iban'));
		$view->set('entry_swift', $this->language->get('entry_swift'));
		$view->set('entry_charges', $this->language->get('entry_charges'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('explanation_bank_name', $this->language->get('explanation_bank_name'));
		$view->set('explanation_bank_address', $this->language->get('explanation_bank_address'));
		$view->set('explanation_owner', $this->language->get('explanation_owner'));
		$view->set('explanation_ban', $this->language->get('explanation_ban'));
		$view->set('explanation_iban', $this->language->get('explanation_iban'));
		$view->set('explanation_swift', $this->language->get('explanation_swift'));
		$view->set('explanation_charges', $this->language->get('explanation_charges'));
		$view->set('explanation_currency', $this->language->get('explanation_currency'));

		$view->set('help', $this->session->get('help'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_bank_name', @$this->error['bank_name']);
		$view->set('error_owner', @$this->error['owner']);
		$view->set('error_ban', @$this->error['ban']);

		$view->set('action', $this->url->ssl('bank_account', $this->request->gethtml('action'), array('bank_account_id' => $this->request->gethtml('bank_account_id'))));
		$view->set('last', $this->url->getLast('bank_account'));
		$view->set('insert', $this->url->ssl('bank_account', 'insert'));
		$view->set('cancel', $this->url->ssl('bank_account'));

		if ($this->request->gethtml('bank_account_id')) {
			$view->set('update', 'update');
			$view->set('delete', $this->url->ssl('bank_account', 'delete', array('bank_account_id' => (int)$this->request->gethtml('bank_account_id'),'bank_account_validation' =>$this->session->get('bank_account_validation'))));
		}

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('bank_account_id', $this->request->gethtml('bank_account_id'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (($this->request->gethtml('bank_account_id')) && (!$this->request->isPost())) {
			$bank_account_info = $this->modelBankAccount->get_bankaccount();
		}

		$this->session->set('last_bank_account_id', $this->request->gethtml('bank_account_id'));

		if ($this->request->has('currency', 'post')) {
			$view->set('currency', $this->request->gethtml('currency', 'post'));
		} else {
			$view->set('currency', @$bank_account_info['currency']);
		}
		$view->set('enabled_currencies', $this->modelBankAccount->get_currencies());

		if ($this->request->has('bank_name', 'post')) {
			$view->set('bank_name', $this->request->gethtml('bank_name', 'post'));
		} else {
			$view->set('bank_name', @$bank_account_info['bank_name']);
		}

		if ($this->request->has('bank_address', 'post')) {
			$view->set('bank_address', $this->request->gethtml('bank_address', 'post'));
		} else {
			$view->set('bank_address', @$bank_account_info['bank_address']);
		}

		if ($this->request->has('owner', 'post')) {
			$view->set('owner', $this->request->gethtml('owner', 'post'));
		} else {
			$view->set('owner', @$bank_account_info['owner']);
		}

		if ($this->request->has('ban', 'post')) {
			$view->set('ban', $this->request->gethtml('ban', 'post'));
		} else {
			$view->set('ban', @$bank_account_info['ban']);
		}

		if ($this->request->has('iban', 'post')) {
			$view->set('iban', $this->request->gethtml('iban', 'post'));
		} else {
			$view->set('iban', @$bank_account_info['iban']);
		}

		if ($this->request->has('swift', 'post')) {
			$view->set('swift', $this->request->gethtml('swift', 'post'));
		} else {
			$view->set('swift', @$bank_account_info['swift']);
		}

		if ($this->request->has('charge', 'post')) {
			$view->set('charge', $this->request->gethtml('charge', 'post'));
		} else {
			$view->set('charge', @$bank_account_info['charge']);
		}

		$view->set('charges', array('OUR', 'SHA', 'BEN'));

		return $view->fetch('content/bank_account.tpl');
	}

	private function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'bank_account')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->validate->strlen($this->request->gethtml('bank_name', 'post'),2,64)) {
			$this->error['bank_name'] = $this->language->get('error_bank_name');
		}
		if (!$this->validate->strlen($this->request->gethtml('owner', 'post'),2,64)) {
			$this->error['owner'] = $this->language->get('error_owner');
		}
		if (!$this->request->get('ban', 'post')) {
			$this->error['ban'] = $this->language->get('error_ban');
		}

		if ($this->request->gethtml('currency', 'post') === '0') { // if All currencies
			$currency_info = $this->modelBankAccount->check_allCurrencies();
		} else {
			$currency_info = $this->modelBankAccount->check_currencies();
		}
		if ($currency_info['total'] && (int)$this->request->gethtml('bank_account_id') == 0) { // if bank_account_id is zero, it is insertion. Update is permitted.
			$this->error['message'] = $this->language->get('error_currency');
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
			$this->response->redirect($this->url->ssl('bank_account'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('bank_account'));
		}
	}

	private function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'bank_account')) {
			$this->error['message'] = $this->language->get('error_permission');  
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if(($this->session->get('bank_account_validation') != $this->request->sanitize('bank_account_validation')) || (strlen($this->session->get('bank_account_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('bank_account_validation');
		if (!$this->user->hasPermission('modify', 'bank_account')) {
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

	protected function page() {
		if ($this->request->has('search', 'post')) {
			$this->session->set('bank_account.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('bank_account.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('bank_account.order', (($this->session->get('bank_account.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('bank_account.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('bank_account.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('bank_account'));
	}
}
?>

<?php //Order Status AlegroCart
class ControllerOrderStatus extends Controller {

	public $error = array();

	public function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->cache		=& $locator->get('cache');
		$this->config		=& $locator->get('config');
		$this->language		=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request		=& $locator->get('request');
		$this->response		=& $locator->get('response');
		$this->session		=& $locator->get('session');
		$this->template		=& $locator->get('template');
		$this->url		=& $locator->get('url');
		$this->user		=& $locator->get('user'); 
		$this->validate		=& $locator->get('validate');
		$this->modelOrderStatus	= $model->get('model_admin_orderstatus');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController = $this->template->set_controller('order_status');

		$this->language->load('controller/order_status.php');
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

		if ($this->request->isPost() && $this->request->has('language', 'post') && $this->validateForm()) {
			$this->modelOrderStatus->insert_status();
			$this->cache->delete('order_status');
			$this->session->set('message', $this->language->get('text_message'));
			$this->session->set('last_order_status_id', $this->modelOrderStatus->get_last_id());

		$this->response->redirect($this->url->ssl('order_status'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('language', 'post') && $this->validateForm() && $this->validateModification()) {
			$this->modelOrderStatus->delete_status();
			$this->modelOrderStatus->update_status();
			$this->cache->delete('order_status');
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('order_status', 'update', array('order_status_id' => $this->request->gethtml('order_status_id', 'post'))));
			} else {
				$this->response->redirect($this->url->ssl('order_status'));
			}
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch()); 

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('order_status_id')) && ($this->validateDelete())) {
			$this->modelOrderStatus->delete_status();
			$this->cache->delete('order_status');
			$this->session->set('message', $this->language->get('text_message'));
			
			$this->response->redirect($this->url->ssl('order_status'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	private function getList() {
		$this->session->set('order_status_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_name'),
			'sort'  => 'name',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_action'),
			'align' => 'action'
		);

		$results = $this->modelOrderStatus->get_page();

		$rows = array();
		foreach ($results as $result) {
			$last = $result['order_status_id'] == $this->session->get('last_order_status_id') ? 'last_visited': '';
			$cell = array();
			$cell[] = array(
				'value'   => $result['name'],
				'align'   => 'left',
				'default' => ($result['order_status_id'] == $this->config->get('config_order_status_id')),
				'last' => $last
			);
			$action = array();
			$action[] = array(
				'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('order_status', 'update', array('order_status_id' => $result['order_status_id']))
			);
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('order_status', 'delete', array('order_status_id' => $result['order_status_id'],'order_status_validation' =>$this->session->get('order_status_validation')))
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

		$view->set('text_default', $this->language->get('text_default'));
		$view->set('text_results', $this->modelOrderStatus->get_text_results());
		$view->set('text_asc', $this->language->get('text_asc'));
		$view->set('text_desc', $this->language->get('text_desc'));

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));
		$view->set('controller', 'order_status');
		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');

		$view->set('action', $this->url->ssl('order_status', 'page'));
		$view->set('action_delete', $this->url->ssl('order_status', 'enableDelete'));
		$view->set('last', $this->url->getLast('order_status'));

		$view->set('search', $this->session->get('order_status.search'));
		$view->set('sort', $this->session->get('order_status.sort'));
		$view->set('order', $this->session->get('order_status.order'));
		$view->set('page', $this->session->get('order_status.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('insert', $this->url->ssl('order_status', 'insert'));

		$view->set('pages', $this->modelOrderStatus->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	private function getForm() {
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_form_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('entry_name', $this->language->get('entry_name'));

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
		$view->set('error_name', @$this->error['name']);

		if(!@$this->error['message']){
			$view->set('error', @$this->error['warning']);
		}

		$view->set('action', $this->url->ssl('order_status', $this->request->gethtml('action'), array('order_status_id' => $this->request->gethtml('order_status_id'))));
		$view->set('last', $this->url->getLast('order_status'));
		$view->set('insert', $this->url->ssl('order_status', 'insert'));
		$view->set('cancel', $this->url->ssl('order_status'));

		if ($this->request->gethtml('order_status_id')) {  
			$view->set('update', 'update');
			$view->set('delete', $this->url->ssl('order_status', 'delete', array('order_status_id' => (int)$this->request->gethtml('order_status_id'),'order_status_validation' =>$this->session->get('order_status_validation'))));
		}

		$view->set('tabmini', $this->session->has('order_status_tabmini') && $this->session->get('order_status_id') == $this->request->gethtml('order_status_id') ? $this->session->get('order_status_tabmini') : 0);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('order_status_id', $this->request->gethtml('order_status_id'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$this->session->set('last_order_status_id', $this->request->gethtml('order_status_id'));

		$post_info = $this->request->gethtml('post');
		$order_status_data = array();

		$results = $this->modelOrderStatus->get_languages();
		foreach ($results as $result) {
			if($result['language_status'] =='1'){
				if (($this->request->gethtml('order_status_id')) && (!$this->request->isPost())) {
					$order_status_description_info = $this->modelOrderStatus->get_description($result['language_id']);
					$this->session->set('order_status_date_modified_' . $result['language_id'], $order_status_description_info['date_modified']);
				} else {
					$order_status_description_info = $this->request->gethtml('language', 'post');
				}

				$order_status_data[] = array(
					'language_id' => $result['language_id'],
					'language'    => $result['name'],
					'name'        => (isset($order_status_description_info[$result['language_id']]) ? $order_status_description_info[$result['language_id']]['name'] : @$order_status_description_info['name']),
				);
			}
		}

		$view->set('order_statuses', $order_status_data);  

		return $view->fetch('content/order_status.tpl');
	}

	private function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'order_status')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		foreach ($this->request->gethtml('language', 'post') as $key => $value) {
			if (!$this->validate->strlen($value['name'],1,32)) {
				$this->error['name'][$key] = $this->language->get('error_name');
			}
		}
		if (@$this->error && !@$this->error['message']){
			$this->error['warning'] = $this->language->get('error_warning');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateModification() {
		foreach ($this->request->gethtml('language', 'post') as $key => $value) {
			if ($order_status_data = $this->modelOrderStatus->get_description($key)) {
				if ($order_status_data['date_modified'] != $this->session->get('order_status_date_modified_'.$key)) {
					$order_status_data_log = $this->modelOrderStatus->get_modified_log($key, $order_status_data['date_modified']);
					if ($order_status_data_log['name'] != $value['name']) {
						$this->error['name'][$key] = $this->language->get('error_modified', $order_status_data_log['name']);
					}
					$this->session->set('order_status_date_modified_'.$key, $order_status_data_log['date_modified']);
				}
			} else {
				$order_status_data_log = $this->modelOrderStatus->get_deleted_log();
				$this->session->set('message', $this->language->get('error_deleted', $order_status_data_log['modifier']));
				$this->response->redirect($this->url->ssl('order_status'));
			}
		}
		if (@$this->error){
			$this->error['warning'] = $this->language->get('error_modifier', $order_status_data_log['modifier']);
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
			$this->response->redirect($this->url->ssl('order_status'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('order_status'));
		}
	}

	private function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'order_status')) {
			$this->error['message'] = $this->language->get('error_permission');  
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if(($this->session->get('order_status_validation') != $this->request->sanitize('order_status_validation')) || (strlen($this->session->get('order_status_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('order_status_validation');
		if (!$this->user->hasPermission('modify', 'order_status')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if ($this->config->get('config_order_status_id') == $this->request->gethtml('order_status_id')) {
			$this->error['message'] = $this->language->get('error_default');
		}

		$order_info = $this->modelOrderStatus->check_orders();
		if ($order_info['total']) {
			$this->error['message'] = $order_info['total'] ==1 ? $this->language->get('error_order') : $this->language->get('error_orders', $order_info['total']);
			$order_list = $this-> modelOrderStatus->get_orderstatusToOrders();
				$this->error['message'] .= '<br>';
				foreach ($order_list as $order) {
					$this->error['message'] .= '<a href="' . $this->url->ssl('order', 'update', array('order_id' => $order['order_id'])) . '">' . $order['invoice_number'] . '</a>&nbsp;';
				}
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
			$this->session->set('order_status.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('order_status.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('order_status.order', (($this->session->get('order_status.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('order_status.order') == 'asc') ? 'desc' : 'asc'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('order_status.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('order_status'));
	}

	protected function tab() {
		if ($this->request->isPost()) {
			if ($this->request->has('activeTabmini', 'post')) {
				$this->session->set('order_status_tabmini', $this->request->sanitize('activeTabmini', 'post'));
				$this->session->set('order_status_id', $this->request->sanitize('id', 'post'));
				$output = array('status' => true);
			} else {
				$output = array('status' => false);
			}
			echo json_encode($output);
		}
	}
}
?>

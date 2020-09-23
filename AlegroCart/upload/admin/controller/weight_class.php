<?php //WeightClass AlegroCart
class ControllerWeightClass extends Controller {

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
		$this->modelWeightClass	= $model->get('model_admin_weight_class');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('weight_class');

		$this->language->load('controller/weight_class.php');
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
			foreach ($this->request->gethtml('language', 'post') as $key => $value) {
				$this->modelWeightClass->insert_weight_class(@$insert_id, $key, $value['title'], $value['unit']);
				$insert_id = $this->modelWeightClass->get_last_id();
			}
			foreach ($this->request->gethtml('rule', 'post', array()) as $key => $value) {
				$this->modelWeightClass->insert_weight_rule($insert_id, $key, $value);
			}
			$this->cache->delete('weight_class');
			$this->session->set('last_weight_class_id', $insert_id);
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('weight_class'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('language', 'post') && $this->validateForm()) {
			$this->modelWeightClass->delete_weight_class();
			foreach ($this->request->gethtml('language', 'post') as $key => $value) {
				$this->modelWeightClass->insert_weight_class(@$this->request->gethtml('weight_class_id'), $key, $value['title'], $value['unit']);
			}
			$this->modelWeightClass->delete_weight_rule();
			foreach ($this->request->gethtml('rule', 'post', array()) as $key => $value) {
				$this->modelWeightClass->insert_weight_rule($this->request->gethtml('weight_class_id'), $key, $value);
			}
			$this->cache->delete('weight_class');
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('weight_class', 'update', array('weight_class_id' => $this->request->gethtml('weight_class_id', 'post'))));
			} else {
				$this->response->redirect($this->url->ssl('weight_class'));
			}
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function delete() {
		$this->template->set('title', $this->language->get('heading_title'));
 
		if (($this->request->gethtml('weight_class_id')) && ($this->validateDelete())) {
			$this->modelWeightClass->delete_weight_class();
			$this->modelWeightClass->delete_weight_rule();
			$this->cache->delete('weight_class');
			$this->session->set('message', $this->language->get('text_message'));
			
			$this->response->redirect($this->url->ssl('weight_class'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	private function getList() {
		$this->session->set('weight_class_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_title'),
			'sort'  => 'title',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_unit'),
			'sort'  => 'unit',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_action'),
			'align' => 'action'
		);

		$results = $this->modelWeightClass->get_page();

		$rows = array();
		foreach ($results as $result) {
			$last = $result['weight_class_id'] == $this->session->get('last_weight_class_id') ? 'last_visited': '';
			$cell = array();
			$cell[] = array(
				'value'   => $result['title'],
				'align'   => 'left',
				'default' => ($result['weight_class_id'] == $this->config->get('config_weight_class_id')),
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['unit'],
				'align' => 'left',
				'last' => $last
			);

			$action = array();

			$action[] = array(
				'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('weight_class', 'update', array('weight_class_id' => $result['weight_class_id']))
			);
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('weight_class', 'delete', array('weight_class_id' => $result['weight_class_id'],'weight_class_validation' =>$this->session->get('weight_class_validation')))
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
		$view->set('text_results', $this->modelWeightClass->get_text_results());
		$view->set('text_asc', $this->language->get('text_asc'));
		$view->set('text_desc', $this->language->get('text_desc'));

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
		$view->set('controller', 'weight_class');
		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('weight_class', 'page'));
		$view->set('action_delete', $this->url->ssl('weight_class', 'enableDelete'));
		$view->set('last', $this->url->getLast('weight_class'));

		$view->set('search', $this->session->get('weight_class.search'));
		$view->set('sort', $this->session->get('weight_class.sort'));
		$view->set('order', $this->session->get('weight_class.order'));
		$view->set('page', $this->session->get('weight_class.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('insert', $this->url->ssl('weight_class', 'insert'));

		$view->set('pages', $this->modelWeightClass->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	private function getForm() {
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_form_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('entry_title', $this->language->get('entry_title'));
		$view->set('entry_unit', $this->language->get('entry_unit'));

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
		$view->set('tab_data', $this->language->get('tab_data'));

		$view->set('error_update', $this->language->get('error_update'));
		$view->set('error', @$this->error['message']);
		$view->set('error_title', @$this->error['title']);
		$view->set('error_unit', @$this->error['unit']);

		if(!@$this->error['message']){
			$view->set('error', @$this->error['warning']);
		}

		$view->set('action', $this->url->ssl('weight_class', $this->request->gethtml('action'), array('weight_class_id' => $this->request->gethtml('weight_class_id'))));
		$view->set('last', $this->url->getLast('weight_class'));
		$view->set('insert', $this->url->ssl('weight_class', 'insert'));
		$view->set('cancel', $this->url->ssl('weight_class'));

		if ($this->request->gethtml('weight_class_id')) {
			$view->set('update', 'enable');
			$view->set('delete', $this->url->ssl('weight_class', 'delete', array('weight_class_id' => $this->request->gethtml('weight_class_id'),'weight_class_validation' =>$this->session->get('weight_class_validation'))));
		}

		$view->set('tab', $this->session->has('weight_class_tab') && $this->session->get('weight_class_id') == $this->request->gethtml('weight_class_id') ? $this->session->get('weight_class_tab') : 0);
		$view->set('tabmini', $this->session->has('weight_class_tabmini') && $this->session->get('weight_class_id') == $this->request->gethtml('weight_class_id') ? $this->session->get('weight_class_tabmini') : 0);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('weight_class_id', $this->request->gethtml('weight_class_id'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$this->session->set('last_weight_class_id', $this->request->gethtml('weight_class_id'));

		$weight_class_data = array();
		$results = $this->modelWeightClass->get_languages();
		foreach ($results as $result) {
			if($result['language_status'] =='1'){
			if (($this->request->gethtml('weight_class_id')) && (!$this->request->isPost())) {
				$weight_description_info = $this->modelWeightClass->get_weight_class($result['language_id']);
			} else {
				$weight_description_info = $this->request->gethtml('language', 'post');
			}

			$weight_class_data[] = array(
				'language_id' => $result['language_id'],
				'language'    => $result['name'],
				'title'       => (isset($weight_description_info[$result['language_id']]) ? $weight_description_info[$result['language_id']]['title'] : @$weight_description_info['title']),
				'unit'        => (isset($weight_description_info[$result['language_id']]) ? $weight_description_info[$result['language_id']]['unit'] : @$weight_description_info['unit']),
			);
			}
		}

		$view->set('weight_classes', $weight_class_data);

		$weight_rule_data = array();

		$results = $this->modelWeightClass->get_weight_classes();
		foreach ($results as $result) {
			if (($this->request->gethtml('weight_class_id')) && (!$this->request->isPost())) {
				$weight_rule_info = $this->modelWeightClass->get_weight_rule($result['weight_class_id']);
			}
			$rule = $this->request->gethtml('rule', 'post');
			if ($result['weight_class_id'] != $this->request->gethtml('weight_class_id')) {
				$weight_rule_data[] = array(
					'title' => $result['title'] . ':',
					'to_id' => $result['weight_class_id'],
					'rule'  => (isset($rule[$result['weight_class_id']]) ? $rule[$result['weight_class_id']] : @$weight_rule_info['rule'])
				);
			}
		}
		
		$view->set('weight_rules', $weight_rule_data);

		return $view->fetch('content/weight_class.tpl');
	}

	private function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'weight_class')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		foreach ($this->request->gethtml('language', 'post') as $value) {
			if (!$this->validate->strlen($value['title'],1,32)) {
				$this->error['title'] = $this->language->get('error_title');
			}
		} 
		foreach ($this->request->gethtml('language', 'post') as $value) {
			if ((!$value['unit']) || (strlen($value['unit']) > 4)) {
				$this->error['unit'] = $this->language->get('error_unit');
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

	protected function enableDelete(){
		$this->template->set('title', $this->language->get('heading_title'));
		if($this->validateEnableDelete()){
			if($this->session->get('enable_delete')){
				$this->session->delete('enable_delete');
			} else {
				$this->session->set('enable_delete', TRUE);
			}
			$this->response->redirect($this->url->ssl('weight_class'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('weight_class'));
		}
	}

	private function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'weight_class')) {
			$this->error['message'] = $this->language->get('error_permission');  
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if(($this->session->get('weight_class_validation') != $this->request->sanitize('weight_class_validation')) || (strlen($this->session->get('weight_class_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('weight_class_validation');
		if (!$this->user->hasPermission('modify', 'weight_class')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if ($this->config->get('config_weight_class_id') == $this->request->gethtml('weight_class_id')) {
			$this->error['message'] = $this->language->get('error_default');
		}

		$product_info = $this->modelWeightClass->check_products();
		if ($product_info['total']) {
			$this->error['message'] = $product_info['total'] == 1 ? $this->language->get('error_product') : $this->language->get('error_products', $product_info['total']) ;
			$product_list = $this-> modelWeightClass->get_weightclassToProducts();
				$this->error['message'] .= '<br>';
				foreach ($product_list as $product) {
					$this->error['message'] .= '<a href="' . $this->url->ssl('product', 'update', array('product_id' => $product['product_id'])) . '">' . $product['name'] . '</a>&nbsp;';
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
			$this->session->set('weight_class.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('weight_class.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('weight_class.order', (($this->session->get('weight_class.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('weight_class.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('weight_class.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('weight_class'));
	}

	protected function tab() {
		if ($this->request->isPost()) {
			if ($this->request->has('activeTab', 'post')) {
				$this->session->set('weight_class_tab', $this->request->sanitize('activeTab', 'post'));
				$this->session->set('weight_class_id', $this->request->sanitize('id', 'post'));
				if ($this->request->has('activeTabmini', 'post')) {
					$this->session->set('weight_class_tabmini', $this->request->sanitize('activeTabmini', 'post'));
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

<?php //DimensionClass AlegroCart
class ControllerDimensionClass extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->cache    	=& $locator->get('cache');
		$this->config   	=& $locator->get('config');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session 		=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user'); 
		$this->validate 	=& $locator->get('validate');
		$this->modelDimensions = $model->get('model_admin_dimension_class');
		
		$this->language->load('controller/dimension_class.php');
	}
	
	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function insert() {
		$this->template->set('title', $this->language->get('heading_title'));
		
		if ($this->request->isPost() && $this->request->has('language', 'post') && $this->validateForm()) {
			foreach ($this->request->gethtml('language', 'post') as $key => $value) {
				$this->modelDimensions->insert_dimension(@$insert_id, $key, $value['title'], $value['unit']);
				$insert_id = $this->modelDimensions->get_last_id();
			}
			foreach ($this->request->gethtml('rule', 'post', array()) as $key => $value) {
				$this->modelDimensions->insert_dimension_rule($insert_id, $key, $value);
			}
			$this->cache->delete('dimension_class');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('dimension_class'));
		}
	
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('language', 'post') && $this->validateForm()) {
			$this->modelDimensions->delete_dimension_class();
			foreach ($this->request->gethtml('language', 'post') as $key => $value) {
				$this->modelDimensions->insert_dimension($this->request->gethtml('dimension_id'), $key, $value['title'], $value['unit']);
			}
			$this->modelDimensions->delete_dimension_rule();
			foreach ($this->request->gethtml('rule', 'post', array()) as $key => $value) {
				$this->modelDimensions->insert_dimension_rule($this->request->gethtml('dimension_id'), $key, $value);
			}
			$this->cache->delete('dimension_class');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('dimension_class'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function delete() {
		$this->template->set('title', $this->language->get('heading_title'));
 
		if (($this->request->gethtml('dimension_id')) && ($this->validateDelete())) {
			$this->modelDimensions->delete_dimension_class();
			$this->modelDimensions->delete_dimension_rule();
			$this->cache->delete('dimension_class');
			$this->session->set('message', $this->language->get('text_message'));
			
			$this->response->redirect($this->url->ssl('dimension_class'));
		}
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function getList() {
		$this->session->set('dimension_class_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_title'),
			'sort'  => 'd.title',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_unit'),
			'sort'  => 'd.unit',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_type'),
			'sort'  => 'dt.type_name',
			'align' => 'left'
		);
		$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'right'
    	);
		
		$results = $this->modelDimensions->get_page();
		$rows = array();
		foreach ($results as $result) {
			$cell = array();
			$cell[] = array(
				'value'   => $result['title'],
				'align'   => 'left',
				'default' => ($result['dimension_id'] == $this->config->get('config_dimension_'. $result['type_id'] . '_id'))
			);
			$cell[] = array(
				'value' => $result['unit'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $this->language->get('text_'. $result['type_name']),
				'align' => 'left'
			);
			
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('dimension_class', 'update', array('dimension_id' => $result['dimension_id'], 'type_id' => $result['type_id']))
      		);
			if($this->session->get('enable_delete')){	
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('dimension_class', 'delete', array('dimension_id' => $result['dimension_id'],'dimension_class_validation' =>$this->session->get('dimension_class_validation')))
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
		$view->set('text_results', $this->modelDimensions->get_text_results());
		
		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete'));

		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('dimension_class', 'page'));
		$view->set('action_delete', $this->url->ssl('dimension_class', 'enableDelete'));
		
		$view->set('search', $this->session->get('dimension.search'));
		$view->set('sort', $this->session->get('dimension.sort'));
		$view->set('order', $this->session->get('dimension.order'));
		$view->set('page', $this->session->get('dimension.page'));
		
		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $this->url->ssl('dimension_class'));
		$view->set('insert', $this->url->ssl('dimension_class', 'insert'));
		
		$view->set('pages', $this->modelDimensions->get_pagination());
		
		return $view->fetch('content/list.tpl');
	}
	
	function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));
		
		$view->set('entry_title', $this->language->get('entry_title'));
		$view->set('entry_unit', $this->language->get('entry_unit'));
		$view->set('entry_type', $this->language->get('entry_type'));
		
		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));

		$view->set('tab_general', $this->language->get('tab_general'));
		$view->set('tab_data', $this->language->get('tab_data'));
		
		$view->set('error', @$this->error['message']);
		$view->set('error_title', @$this->error['title']);
		$view->set('error_unit', @$this->error['unit']);
		
		$view->set('action', $this->url->ssl('dimension_class', $this->request->gethtml('action'), array('dimension_id' => $this->request->gethtml('dimension_id'))));
		
		$view->set('list', $this->url->ssl('dimension_class'));
		$view->set('insert', $this->url->ssl('dimension_class', 'insert'));
		$view->set('cancel', $this->url->ssl('dimension_class'));
		
		if ($this->request->gethtml('dimension_id')) {
			$view->set('update', 'enable');
			$view->set('delete', $this->url->ssl('dimension_class', 'delete', array('dimension_id' => $this->request->gethtml('dimension_id'),'dimension_class_validation' =>$this->session->get('dimension_class_validation'))));
		}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
		$dimension_class_data = array();
		$results = $this->modelDimensions->get_languages();
		foreach ($results as $result) {
			if (($this->request->gethtml('dimension_id')) && (!$this->request->isPost())) {
				$dimension_info = $this->modelDimensions->get_dimension_class($result['language_id']);
				$type_id = isset($dimension_info[$result['language_id']]) ? $dimension_info[$result['language_id']]['type_id'] : '';
			} else {
				$dimension_info = $this->request->gethtml('language', 'post');
				$type_id = (int)$this->request->gethtml('type_id','post');
			}
			$dimension_class_data[] = array(
				'language_id' => $result['language_id'],
				'language'    => $result['name'],
				'title'       => (isset($dimension_info[$result['language_id']]) ? $dimension_info[$result['language_id']]['title'] : @$dimension_info['title']),
	    		'unit'        => (isset($dimension_info[$result['language_id']]) ? $dimension_info[$result['language_id']]['unit'] : @$dimension_info['unit']),
				'type_id'     => @$type_id,
			);
		}
		$view->set('dimension_classes', $dimension_class_data);
		
		if($this->request->gethtml('type_id', 'post') || $this->request->gethtml('type_id')){
			$type_id = $this->request->gethtml('type_id', 'post') ? $this->request->gethtml('type_id', 'post') : $this->request->gethtml('type_id');
			$dimension_rule_data = array();
			$results = $this->modelDimensions->get_dimension_classes($type_id);
			foreach ($results as $result) {
				if (($this->request->gethtml('dimension_id')) && (!$this->request->isPost())) {
					$dimension_rule_info = $this->modelDimensions->get_dimension_rule($result['dimension_id']);
				}
				$rule = $this->request->gethtml('rule', 'post');
				if ($result['dimension_id'] != $this->request->gethtml('dimension_id')) {
					$dimension_rule_data[] = array(
						'title' => $result['title'] . ':',
						'to_id' => $result['dimension_id'],
						'rule'  => (isset($rule[$result['dimension_id']]) ? $rule[$result['dimension_id']] : @$dimension_rule_info['rule'])
					);
				}
			}
			$view->set('dimension_rules', $dimension_rule_data);
			$result = $this->modelDimensions->get_type($type_id);
			$type_data = array(
				'type_id'   => $result['type_id'],
				'type_text' => $this->language->get('text_'. $result['type_name'])
			);
			$view->set('type', $type_data);
			
		} else {
			$dimension_rule_data = array();
			$results = $this->modelDimensions->get_dimension_classes($this->config->get('config_dimension_type_id') ? $this->config->get('config_dimension_type_id') : 1);
			foreach ($results as $result) {
				$dimension_rule_data[] = array(
					'title' => $result['title'] . ':',
					'to_id' => $result['dimension_id'],
					'rule'  => ''
				);
			}
			$view->set('dimension_rules', $dimension_rule_data);
			$type_data = array();
			$results = $this->modelDimensions->get_types();
			foreach ($results as $result) {
				$type_data[] = array(
					'type_id'   => $result['type_id'],
					'type_text' => $this->language->get('text_'. $result['type_name'])
				);
			}
			$view->set('types', $type_data);
			$view->set('default_type', $this->config->get('config_dimension_type_id'));
			$view->set('type_id', 0);
		}
		return $view->fetch('content/dimension_class.tpl');
	}
	
	function dimensionClasses(){
		$type_id = $this->request->gethtml('type_id');
		$output = '';
		$results = $this->modelDimensions->get_dimension_classes($type_id);
		if($results){
			foreach ($results as $result) {
				$output .= '<tr><td width="185">' . $result['title'] . ':</td>' . "\n";
				$output .= '<td><input type="text" name="rule[' . $result['dimension_id'] . '] value=" "></td></tr>' . "\n";
			}
		}
			$this->response->set($output);
	}
	
	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'dimension_class')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		foreach ($this->request->gethtml('language', 'post') as $value) {
			if (!$this->validate->strlen($value['title'],1,32)) {
				$this->error['title'] = $this->language->get('error_title');
			}
		} 
		foreach ($this->request->gethtml('language', 'post') as $value) {
			if ((!$value['unit']) || (strlen($value['unit']) > 24)) {
				$this->error['unit'] = $this->language->get('error_unit');
			}
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
			$this->response->redirect($this->url->ssl('dimension_class'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('dimension_class'));
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'dimension_class')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}
	function validateDelete() {
		if(($this->session->get('dimension_class_validation') != $this->request->sanitize('dimension_class_validation')) || (strlen($this->session->get('dimension_class_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('dimension_class_validation');
		if (!$this->user->hasPermission('modify', 'dimension_class')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if ($this->config->get('config_dimension_id') == $this->request->gethtml('dimension_id')) {
			$this->error['message'] = $this->language->get('error_default');
		}

		$product_info = $this->modelDimensions->check_products();
		if ($product_info['total']) {
			$this->error['message'] = $this->language->get('error_product', $product_info['total']);
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function page() {
		if ($this->request->has('search', 'post')) {
			$this->session->set('dimension.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('dimension.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('dimension.order', (($this->session->get('dimension.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('dimension.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('dimension.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('dimension_class'));
	}	
	
}
?>
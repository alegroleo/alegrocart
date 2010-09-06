<?php //TaxClass AlegroCart
class ControllerTaxClass extends Controller {
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
		$this->modelTaxClass = $model->get('model_admin_tax_class');
		
		$this->language->load('controller/tax_class.php');
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
			$this->modelTaxClass->insert_tax_class();
			$this->cache->delete('tax_class');
			$this->session->set('message', $this->language->get('text_message'));
			
			$this->response->redirect($this->url->ssl('tax_class'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('title', 'post') && $this->validateForm()) {
			$this->modelTaxClass->update_tax_class();
			$this->cache->delete('tax_class');
			$this->session->set('message', $this->language->get('text_message'));
			
			$this->response->redirect($this->url->ssl('tax_class'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function delete() {
		$this->template->set('title', $this->language->get('heading_title'));
 
		if (($this->request->gethtml('tax_class_id')) && ($this->validateDelete())) {
			$this->modelTaxClass->delete_tax_class();
			$this->cache->delete('tax_class');
			$this->session->set('message', $this->language->get('text_message'));
			
			$this->response->redirect($this->url->ssl('tax_class'));
		}
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function getList() {
		$this->session->set('tax_class_validation', md5(time()));
		$cols = array();
		$cols[] = array('align' => 'center');
		$cols[] = array(
			'name'  => $this->language->get('column_title'),
			'sort'  => 'title',
			'align' => 'left'
		);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'right'
    	);
		
		$results = $this->modelTaxClass->get_page();
		$rows = array();
		foreach ($results as $result) {
			$cell = array();
      		$cell[] = array(
        		'icon'  => 'folder.png',
        		'align' => 'center',
				'path'  => $this->url->ssl('tax_rate', FALSE, array('tax_class_id' => $result['tax_class_id']))
		  	);
			$cell[] = array(
				'value' => $result['title'],
				'align' => 'left'
			);
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('tax_class', 'update', array('tax_class_id' => $result['tax_class_id']))
      		);
			
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('tax_class', 'delete', array('tax_class_id' => $result['tax_class_id'],'tax_class_validation' =>$this->session->get('tax_class_validation')))
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

		$view->set('text_results', $this->modelTaxClass->get_text_results());
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
		
		$view->set('action', $this->url->ssl('tax_class', 'page'));
		$view->set('action_delete', $this->url->ssl('tax_class', 'enableDelete'));

		$view->set('search', $this->session->get('tax_class.search'));
		$view->set('sort', $this->session->get('tax_class.sort'));
		$view->set('order', $this->session->get('tax_class.order'));
		$view->set('page', $this->session->get('tax_class.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $this->url->ssl('tax_class'));
		$view->set('insert', $this->url->ssl('tax_class', 'insert'));

		$view->set('pages', $this->modelTaxClass->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('entry_title', $this->language->get('entry_title'));
		$view->set('entry_description', $this->language->get('entry_description'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_title', @$this->error['title']);
		$view->set('error_description', @$this->error['description']);

		$view->set('action', $this->url->ssl('tax_class', $this->request->gethtml('action'), array('tax_class_id' => $this->request->gethtml('tax_class_id'))));

		$view->set('list', $this->url->ssl('tax_class'));
		$view->set('insert', $this->url->ssl('tax_class', 'insert'));
		$view->set('cancel', $this->url->ssl('tax_class'));

		if ($this->request->gethtml('tax_class_id')) {
			$view->set('update', $this->url->ssl('tax_class', 'update', array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
			$view->set('delete', $this->url->ssl('tax_class', 'delete', array('tax_class_id' => $this->request->gethtml('tax_class_id'),'tax_class_validation' =>$this->session->get('tax_class_validation'))));
		}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (($this->request->gethtml('tax_class_id')) && (!$this->request->isPost())) {
			$tax_class_info = $this->modelTaxClass->get_tax_class();
		}

		if ($this->request->has('title', 'post')) {
			$view->set('title', $this->request->gethtml('title', 'post'));
		} else {
			$view->set('title', @$tax_class_info['title']);
		}

		if ($this->request->has('description', 'post')) {
			$view->set('description', $this->request->gethtml('description', 'post'));
		} else {
			$view->set('description', @$tax_class_info['description']);
		}

		return $view->fetch('content/tax_class.tpl');
	}

	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'tax_class')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
        if (!$this->validate->strlen($this->request->gethtml('title', 'post'),1,32)) {
			$this->error['title'] = $this->language->get('error_title');
		}
        if (!$this->validate->strlen($this->request->gethtml('description', 'post'),1,255)) {
			$this->error['description'] = $this->language->get('error_description');
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
			$this->response->redirect($this->url->ssl('tax_class'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('tax_class'));
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'tax_class')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}

	function validateDelete() {
		if(($this->session->get('tax_class_validation') != $this->request->sanitize('tax_class_validation')) || (strlen($this->session->get('tax_class_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('tax_class_validation');
		if (!$this->user->hasPermission('modify', 'tax_class')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		$product_info = $this->modelTaxClass->check_products();
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
			$this->session->set('tax_class.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('tax_class.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('tax_class.order', (($this->session->get('tax_class.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('tax_class.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('tax_class.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('tax_class'));
	}	
}
?>
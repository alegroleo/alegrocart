<?php // Extension AlegroCart
class ControllerExtension extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request 	 	=& $locator->get('request');
		$this->response	 	=& $locator->get('response');
		$this->session 	 	=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url     		=& $locator->get('url');
		$this->user    	 	=& $locator->get('user');
		$this->validate 	=& $locator->get('validate');
		$this->modelExtension = $model->get('model_admin_extension');
		$this->language->load('controller/extension.php');
	}
	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function insert() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('code', 'post') && $this->validateForm()) {
			$this->modelExtension->insert_extension();
			$insert_id = $this->modelExtension->get_insert_id();
			$this->modelExtension->insert_description($insert_id);
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => $this->request->gethtml('type'))));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('code', 'post') && $this->validateForm()) {
			$this->modelExtension->update_extension();
			$this->modelExtension->delete_description();
			$this->modelExtension->insert_description((int)$this->request->gethtml('extension_id'));
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => $this->request->gethtml('type'))));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('extension_id')) && ($this->validateDelete())) {
			$this->modelExtension->delete_extension();
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => $this->request->gethtml('type'))));
		}
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
 
	function getList() {
		$this->session->set('extension_validation', md5(time()));
		if($this->session->get('extension_type') != $this->request->gethtml('type')){
			$this->session->delete('extension.search');
		}
		$this->session->set('extension_type', $this->request->gethtml('type'));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_name'),
			'sort'  => 'ed.name',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_description'),
			'sort'  => 'ed.description',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_code'),
			'sort'  => 'e.code',
			'align' => 'left'
		);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'right'
    	);
		
		$results = $this->modelExtension->get_page();

		$rows = array();

		foreach ($results as $result) {
			$cell = array();
			$cell[] = array(
				'value' => $result['name'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $result['description'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $result['code'],
				'align' => 'left'
			);
			$query = array(
				'type'         => $this->request->gethtml('type'),
				'extension_id' => $result['extension_id']
			);
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('extension', 'update', $query)
      		);
			if($this->session->get('enable_delete')){
				$query = array(
					'type'         => $this->request->gethtml('type'),
					'extension_id' => $result['extension_id'],
					'extension_validation' =>$this->session->get('extension_validation')
				);
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('extension', 'delete', $query)
				);
			}
			
			$setting_info = $this->modelExtension->check_setting($result);
			if ($setting_info) {	
				$action[] = array(
        			'icon' => 'uninstall.png',
					'text' => $this->language->get('button_uninstall'),
					'href' => $this->url->ssl($result['controller'], 'uninstall')
      			);
				$action[] = array(
        			'icon' => 'configure.png',
					'text' => $this->language->get('button_configure'),
					'href' => $this->url->ssl($result['controller'])
      			);	
			} else {
				$action[] = array(
        			'icon' => 'install.png',
					'text' => $this->language->get('button_install'),
					'href' => $this->url->ssl($result['controller'], 'install')
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

		$view->set('text_results', $this->modelExtension->get_text_results());

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete'));
		
		if ($this->session->has('error')) {
			$view->set('error', $this->session->get('error'));
			$this->session->delete('error');
 		} else {
			$view->set('error', @$this->error['message']);
		}

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');

		$view->set('action', $this->url->ssl('extension', 'page', array('type' => $this->request->gethtml('type'))));
		$view->set('action_delete', $this->url->ssl('extension', 'enableDelete', array('type' => $this->request->gethtml('type'))));

		$view->set('search', $this->session->get('extension.search'));
		$view->set('sort', $this->session->get('extension.sort'));
		$view->set('order', $this->session->get('extension.order'));
		$view->set('page', $this->session->get('extension.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $this->url->ssl('extension', FALSE, array('type' => $this->request->gethtml('type'))));
		$view->set('insert', $this->url->ssl('extension', 'insert', array('type' => $this->request->gethtml('type'))));

		$view->set('pages', $this->modelExtension->get_pagination());

		return $view->fetch('content/list.tpl');
	}
	
	function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

    	$view->set('text_enabled', $this->language->get('text_enabled'));
    	$view->set('text_disabled', $this->language->get('text_disabled'));
		
		$view->set('entry_name', $this->language->get('entry_name'));
		$view->set('entry_description', $this->language->get('entry_description'));
		$view->set('entry_code', $this->language->get('entry_code'));
		$view->set('entry_directory', $this->language->get('entry_directory'));
		$view->set('entry_filename', $this->language->get('entry_filename'));
		$view->set('entry_controller', $this->language->get('entry_controller'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));

		$view->set('tab_general', $this->language->get('tab_general'));
		$view->set('tab_data', $this->language->get('tab_data'));
		
  		$view->set('error', @$this->error['message']);	
		$view->set('error_name', @$this->error['name']);
		$view->set('error_description', @$this->error['description']);
		$view->set('error_code', @$this->error['code']);
		$view->set('error_directory', @$this->error['directory']);
		$view->set('error_filename', @$this->error['filename']);
		$view->set('error_controller', @$this->error['controller']);
		
		$query = array(
			'type'         => $this->request->gethtml('type'),
			'extension_id' => $this->request->gethtml('extension_id')
		);
			
		$view->set('action', $this->url->ssl('extension', $this->request->gethtml('action'), $query));
		

		$view->set('list', $this->url->ssl('extension', FALSE, array('type' => $this->request->gethtml('type'))));
		$view->set('insert', $this->url->ssl('extension', 'insert'));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => $this->request->gethtml('type'))));

		if ($this->request->gethtml('extension_id')) {
			$query = array(
				'type'         => $this->request->gethtml('type'),
				'extension_id' => $this->request->gethtml('extension_id'),
				'extension_validation' =>$this->session->get('extension_validation')
			);	
			$view->set('update', 'enable');
			$view->set('delete', $this->url->ssl('extension', 'delete', $query));
		}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$extension_data = array();

		$results = $this->modelExtension->get_languages();
		foreach ($results as $result) {
			if (($this->request->gethtml('extension_id')) && (!$this->request->isPost())) {	
				$extension_description_info = $this->modelExtension->get_description($result['language_id']);
			} else {
				$extension_description_info = $this->request->gethtml('extension_language', 'post');
			}
			
			$extension_data[] = array(
				'language_id' => $result['language_id'],
				'language'    => $result['name'],
	    		'name'        => (isset($extension_description_info[$result['language_id']]) ? $extension_description_info[$result['language_id']]['name'] : @$extension_description_info['name']),
				'description' => (isset($extension_description_info[$result['language_id']]) ? $extension_description_info[$result['language_id']]['description'] : @$extension_description_info['description'])
			);
		}

		$view->set('extensions', $extension_data);

		if (($this->request->gethtml('extension_id')) && (!$this->request->isPost())) {
			$extension_info = $this->modelExtension->get_extension();
		}

		if ($this->request->has('code', 'post')) {
			$view->set('code', $this->request->gethtml('code', 'post'));
		} else {
			$view->set('code', @$extension_info['code']);
		}

		if ($this->request->has('directory', 'post')) {
			$view->set('directory', $this->request->gethtml('directory', 'post'));
		} else {
			$view->set('directory', @$extension_info['directory']);
		}

		if ($this->request->has('filename', 'post')) {
			$view->set('filename', $this->request->gethtml('filename', 'post'));
		} else {
			$view->set('filename', @$extension_info['filename']);
		}

		if ($this->request->has('controller', 'post')) {
			$view->set('controller', $this->request->gethtml('controller', 'post'));
		} else {
			$view->set('controller', @$extension_info['controller']);
		}

		return $view->fetch('content/extension.tpl');
	}

	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'extension')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		foreach ($this->request->gethtml('extension_language', 'post') as $value) {
			if (!$this->validate->strlen($value['name'],1,128)) {
				$this->error['name'] = $this->language->get('error_name');
			}
		}
		foreach ($this->request->gethtml('extension_language', 'post') as $value) {
			if (!$this->validate->strlen($value['description'],1,255)) {
				$this->error['description'] = $this->language->get('error_description');
			}
		}
    	if (!$this->validate->strlen($this->request->gethtml('code', 'post'),1,32)) {
      		$this->error['code'] = $this->language->get('error_code');
    	}
        if (!$this->validate->strlen($this->request->gethtml('directory', 'post'),1,32)) {
      		$this->error['directory'] = $this->language->get('error_directory');
    	}
        if (!$this->validate->strlen($this->request->gethtml('filename', 'post'),1,128)) {
      		$this->error['filename'] = $this->language->get('error_filename');
    	}
        if (!$this->validate->strlen($this->request->gethtml('controller', 'post'),1,128)) {
      		$this->error['controller'] = $this->language->get('error_controller');
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
			$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => $this->request->gethtml('type'))));//**
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => $this->request->gethtml('type'))));//**
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'extension')) {//**
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}

	function validateDelete() {
		if(($this->session->get('extension_validation') != $this->request->sanitize('extension_validation')) || (strlen($this->session->get('extension_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('extension_validation');
		if (!$this->user->hasPermission('modify', 'extension')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function page() {
		if ($this->request->has('search', 'post')) {
			$this->session->set('extension.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('extension.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('extension.order', (($this->session->get('extension.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('extension.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('extension.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => $this->request->gethtml('type'))));
	}
}
?>

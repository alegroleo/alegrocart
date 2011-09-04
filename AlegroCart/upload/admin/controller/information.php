<?php // Information AlegroCart
class ControllerInformation extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->cache    	=& $locator->get('cache');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request 	 	=& $locator->get('request');
		$this->response	 	=& $locator->get('response');
		$this->session 	 	=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url     		=& $locator->get('url');
		$this->user    	 	=& $locator->get('user');
		$this->validate 	=& $locator->get('validate');
		$this->modelInformation = $model->get('model_admin_information');
		$this->language->load('controller/information.php');
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
			$this->modelInformation->insert_information();
			$insert_id = $this->modelInformation->get_insert_id();
			$this->modelInformation->insert_description($insert_id);
			$this->cache->delete('information');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('information'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('language', 'post') && $this->validateForm()) {
			$this->modelInformation->update_information();
			$this->modelInformation->delete_description();
			$this->modelInformation->insert_description((int)$this->request->gethtml('information_id'));
			$this->cache->delete('information');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('information'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
 
	function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('information_id')) && ($this->validateDelete())) {
			$this->modelInformation->delete_information();
			$this->modelInformation->delete_description();
			$this->cache->delete('information');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('information'));
		}
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function getList() {
		$this->session->set('information_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_title'),
			'sort'  => 'id.title',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_sort_order'),
			'sort'  => 'i.sort_order',
			'align' => 'right'
		);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'action'
    	);
		
		$results = $this->modelInformation->get_page();

		$rows = array();
		foreach ($results as $result) {
			$cell = array();
			$cell[] = array(
				'value' => $result['title'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $result['sort_order'],
				'align' => 'right'
			);
			
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('information', 'update', array('information_id' => $result['information_id']))
      		);
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('information', 'delete', array('information_id' => $result['information_id'],'information_validation' =>$this->session->get('information_validation')))
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
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_results', $this->modelInformation->get_text_results());

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		 
		$view->set('action', $this->url->ssl('information', 'page'));
		$view->set('action_delete', $this->url->ssl('information', 'enableDelete'));

		$view->set('search', $this->session->get('information.search'));
		$view->set('sort', $this->session->get('information.sort'));
		$view->set('order', $this->session->get('information.order'));
		$view->set('page', $this->session->get('information.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $this->url->ssl('information'));
		$view->set('insert', $this->url->ssl('information', 'insert'));
		$view->set('pages', $this->modelInformation->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('entry_title', $this->language->get('entry_title'));
		$view->set('entry_description', $this->language->get('entry_description'));
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_general', $this->language->get('tab_general'));
		$view->set('tab_data', $this->language->get('tab_data'));

		$view->set('error', @$this->error['message']);
		$view->set('error_title', @$this->error['title']);
		$view->set('error_description', @$this->error['description']);

		$view->set('action', $this->url->ssl('information', $this->request->gethtml('action'), array('information_id' => $this->request->gethtml('information_id'))));

		$view->set('list', $this->url->ssl('information'));
		$view->set('insert', $this->url->ssl('information', 'insert'));
		$view->set('cancel', $this->url->ssl('information'));

		if ($this->request->gethtml('information_id')) {
			$view->set('update', 'update');
			$view->set('delete', $this->url->ssl('information', 'delete', array('information_id' => $this->request->gethtml('information_id'),'information_validation' =>$this->session->get('information_validation'))));
		}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
		$information_data = array();
		$results = $this->modelInformation->get_languages();
		foreach ($results as $result) {
			if (($this->request->gethtml('information_id')) && (!$this->request->isPost())) {		
				$information_description_info = $this->modelInformation->get_description($result['language_id']);
			} else {
				$information_description_info = $this->request->gethtml('language', 'post');
			}
			
			$information_data[] = array(
				'language_id' => $result['language_id'],
				'language'    => $result['name'],
				'title'       => (isset($information_description_info[$result['language_id']]) ? $information_description_info[$result['language_id']]['title'] : @$information_description_info['title']),
	    		'description' => (isset($information_description_info[$result['language_id']]) ? $information_description_info[$result['language_id']]['description'] : @$information_description_info['description'])
			);
		}

		$view->set('informations', $information_data);

		if (($this->request->gethtml('information_id')) && (!$this->request->isPost())) {
			$information_info = $this->modelInformation->get_information();
		}

		if ($this->request->has('sort_order', 'post')) {
			$view->set('sort_order', $this->request->gethtml('sort_order', 'post'));
		} else {
			$view->set('sort_order', @$information_info['sort_order']);
		}

		return $view->fetch('content/information.tpl');
	}

	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'information')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		foreach ($this->request->gethtml('language', 'post') as $value) {
            if (!$this->validate->strlen($value['title'],1,32)) {
				$this->error['title'] = $this->language->get('error_title');
			}
            if (!$this->validate->strlen($value['description'],1)) {
                $this->error['description'] = $this->language->get('error_description');
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
			$this->response->redirect($this->url->ssl('information'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('information'));
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'information')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}

	function validateDelete() {
		if(($this->session->get('information_validation') != $this->request->sanitize('information_validation')) || (strlen($this->session->get('information_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('information_validation');
		if (!$this->user->hasPermission('modify', 'information')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function page() {
		$this->request  =& $this->locator->get('request');
		$this->response =& $this->locator->get('response');
		$this->url      =& $this->locator->get('url');
		$this->session  =& $this->locator->get('session');

		if ($this->request->has('search', 'post')) {
			$this->session->set('information.search', $this->request->gethtml('search', 'post'));
		}

		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('information.page', $this->request->gethtml('page', 'post'));
		}

		if ($this->request->has('sort', 'post')) {
			$this->session->set('information.order', (($this->session->get('information.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('information.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($this->request->has('sort', 'post')) {
			$this->session->set('information.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('information'));
	}
}
?>

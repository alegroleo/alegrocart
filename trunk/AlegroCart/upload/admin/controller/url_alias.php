<?php //Admin Url Alias AlegroCart
class ControllerUrlAlias extends Controller {
	var $error = array();
 	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->cache    	=& $locator->get('cache');
		$this->config   	=& $locator->get('config');
		$this->generate_seo =& $locator->get('generateseo');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request 	 	=& $locator->get('request');
		$this->response	 	=& $locator->get('response');
		$this->session 	 	=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url     		=& $locator->get('url');
		$this->user    	 	=& $locator->get('user');
		$this->validate 	=& $locator->get('validate');
		$this->modelAdminAlias = $model->get('model_admin_urlalias');
		
		$this->language->load('controller/url_alias.php');
	}

	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('content', $this->getList());

		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function insert() {
		$this->language->load('controller/url_alias.php');
		$this->template->set('title', $this->language->get('heading_title'));
		if ($this->request->isPost() && $this->request->has('query', 'post') && $this->validateForm()) {
			$this->modelAdminAlias->insert_url();
			$this->cache->delete('url');
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('url_alias'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function update() {
		$this->template->set('title', $this->language->get('heading_title'));
		if ($this->request->isPost() && $this->request->has('query', 'post') && $this->validateForm()) {
			$this->modelAdminAlias->update_url();
			$this->cache->delete('url');
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('url_alias'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function delete() {
		$this->template->set('title', $this->language->get('heading_title'));
		if (($this->request->get('url_alias_id')) && ($this->validateDelete())) {
			$this->modelAdminAlias->delete_url();
			$this->cache->delete('url');
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('url_alias'));
		}
		$this->template->set('content', $this->getList());
		$this->template->set($module->fetch());
		$this->response->set($template->fetch('layout.tpl'));
	}

	function getList() {
		$this->session->set('urlalias_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_query'),
			'sort'  => 'query',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_alias'),
			'sort'  => 'alias',
			'align' => 'left'
		);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'action'
    	);
		
		$results = $this->modelAdminAlias->get_page();
		$rows = array();
		foreach ($results as $result) {
			$cell = array();

			$cell[] = array(
				'value' => $result['query'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $result['alias'],
				'align' => 'left'
			);
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('url_alias', 'update', array('url_alias_id' => $result['url_alias_id']))
      		);
			
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('url_alias', 'delete', array('url_alias_id' => $result['url_alias_id'],'urlalias_validation' =>$this->session->get('urlalias_validation')))
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

		$view->set('text_results', $this->modelAdminAlias->get_text_results());

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_refresh', $this->language->get('button_generate_seo'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('url_alias', 'page'));
		$view->set('action_refresh', $this->url->ssl('generate_url_alias'));
		$view->set('action_delete', $this->url->ssl('url_alias', 'enableDelete'));
		
		$view->set('search', $this->session->get('url_alias.search'));
		$view->set('sort', $this->session->get('url_alias.sort'));
		$view->set('order', $this->session->get('url_alias.order'));
		$view->set('page', $this->session->get('url_alias.page'));
		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $this->url->ssl('url_alias'));
		$view->set('insert', $this->url->ssl('url_alias', 'insert'));
		$view->set('pages', $this->modelAdminAlias->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('entry_query', $this->language->get('entry_query'));
		$view->set('entry_alias', $this->language->get('entry_alias'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_query', @$this->error['query']);

		$view->set('action', $this->url->ssl('url_alias', $this->request->get('action'), array('url_alias_id' => $this->request->get('url_alias_id'))));

		$view->set('list', $this->url->ssl('url_alias'));
		$view->set('insert', $this->url->ssl('url_alias', 'insert'));
		$view->set('cancel', $this->url->ssl('url_alias'));
		
		if ($this->request->get('url_alias_id')) {
			$view->set('update', $this->url->ssl('url_alias', 'update', array('url_alias_id' => $this->request->get('url_alias_id'))));
			$view->set('delete', $this->url->ssl('url_alias', 'delete', array('url_alias_id' => $this->request->get('url_alias_id'),'urlalias_validation' =>$this->session->get('urlalias_validation'))));
		}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (($this->request->get('url_alias_id')) && (!$this->request->isPost())) {
			$url_alias_info = $this->modelAdminAlias->get_url_alias();
		}
		if ($this->request->has('query', 'post')) {
			$view->set('query', $this->request->get('query', 'post'));
		} else {
			$view->set('query', @$url_alias_info['query']);
		}
		if ($this->request->has('alias', 'post')) {
			$view->set('alias', $this->request->get('alias', 'post'));
		} else {
			$view->set('alias', @$url_alias_info['alias']);
		}

		return $view->fetch('content/url_alias.tpl');
	}

	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'url_alias')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if (!$this->validate->strlen($this->request->get('query', 'post'),1,128)) {
			$this->error['query'] = $this->language->get('error_query');
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
			$this->response->redirect($this->url->ssl('url_alias'));//**
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('url_alias'));//**
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'url_alias')) {//**
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}
	
	function validateDelete() {
		if(($this->session->get('urlalias_validation') != $this->request->sanitize('urlalias_validation')) || (strlen($this->session->get('urlalias_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('urlalias_validation');
		if (!$this->user->hasPermission('modify', 'url_alias')) {
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
			$this->session->set('url_alias.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('url_alias.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('url_alias.order', (($this->session->get('url_alias.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('url_alias.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('url_alias.sort', $this->request->gethtml('sort', 'post'));
		}
		$this->response->redirect($this->url->ssl('url_alias'));
	}	
}
?>

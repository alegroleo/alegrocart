<?php //Admin Usergroup AlegroCart
class ControllerUserGroup extends Controller {
	var $error = array();
 	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->config   	=& $locator->get('config');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session  	=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->user    	 	=& $locator->get('user');
		$this->validate 	=& $locator->get('validate');
		$this->modelAdminUsergroup = $model->get('model_admin_usergroup');
		
		$this->language->load('controller/user_group.php');
	}
	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
 
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function insert() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('access', 'post') && $this->validateForm()) {
			$permission = array(
				'access' => $this->request->gethtml('access', 'post'),
				'modify' => $this->request->gethtml('modify', 'post')
			);

			if ($this->request->gethtml('all_access', 'post')) {  //'all' over-ride
				$permission['access']=array('*');
			}
			if ($this->request->gethtml('all_modify', 'post')) {
				$permission['modify']=array('*');
			}

			$this->modelAdminUsergroup->insert_usergroup($permission);
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('usergroup'));
		}

		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('access', 'post') && $this->validateForm()) {
			$permission = array(
				'access' => $this->request->gethtml('access', 'post'),
				'modify' => $this->request->gethtml('modify', 'post')
			);

			if ($this->request->gethtml('all_access', 'post')) {  //'all' over-ride
				$permission['access']=array('*');
			}
			if ($this->request->gethtml('all_modify', 'post')) {
				$permission['modify']=array('*');
			}

			$this->modelAdminUsergroup->update_usergroup($permission);
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('usergroup'));
		}

		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function delete() { 
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('user_group_id')) && ($this->validateDelete())) {
			$this->modelAdminUsergroup->delete_usergroup($this->request->gethtml('user_group_id'));
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('usergroup'));
		}

		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function getList() {
		$this->session->set('user_validation', md5(time()));
		
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
		
		if (!$this->session->get('user_group.search')) {
			$sql = "select user_group_id, name from user_group";
		} else {
			$sql = "select user_group_id, name from user_group where name like '?'";
		}

		if (in_array($this->session->get('user_group.sort'), array('name'))) {
			$sql .= " order by " . $this->session->get('user_group.sort') . " " . (($this->session->get('user_group.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by name asc";
		}

		$results = $this->modelAdminUsergroup->get_page($sql);
		
		$rows = array();

		foreach ($results as $result) {
			$cell = array();

			$cell[] = array(
				'value' => $result['name'],
				'align' => 'left'
			);
			
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('usergroup', 'update', array('user_group_id' => $result['user_group_id']))
      		);
			
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('usergroup', 'delete', array('user_group_id' => $result['user_group_id'],'user_validation' =>$this->session->get('user_validation')))
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

		$view->set('text_results', $this->modelAdminUsergroup->get_text_results());

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete'));
		
		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);
		
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('usergroup', 'page'));
		$view->set('action_delete', $this->url->ssl('usergroup', 'enableDelete'));
 
		$view->set('search', $this->session->get('user_group.search'));
		$view->set('sort', $this->session->get('user_group.sort'));
		$view->set('order', $this->session->get('user_group.order'));
		$view->set('page', $this->session->get('user_group.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $this->url->ssl('usergroup'));
		$view->set('insert', $this->url->ssl('usergroup', 'insert'));

		$view->set('pages', $this->modelAdminUsergroup->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('entry_name', $this->language->get('entry_name'));
		$view->set('entry_all_access', $this->language->get('entry_all_access'));
		$view->set('entry_access', $this->language->get('entry_access'));
		$view->set('entry_all_modify', $this->language->get('entry_all_modify'));
		$view->set('entry_modify', $this->language->get('entry_modify'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_name', @$this->error['name']);

		$view->set('action', $this->url->ssl('usergroup', $this->request->gethtml('action'), array('user_group_id' => $this->request->gethtml('user_group_id'))));

		$view->set('list', $this->url->ssl('usergroup'));

		$view->set('insert', $this->url->ssl('usergroup', 'insert'));
		
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if ($this->request->gethtml('user_group_id')) {
			$view->set('update', $this->url->ssl('usergroup', 'update', array('user_group_id' => $this->request->gethtml('user_group_id'))));
			$view->set('delete', $this->url->ssl('usergroup', 'delete', array('user_group_id' => $this->request->gethtml('user_group_id'),'user_validation' =>$this->session->get('user_validation'))));
		}

		$view->set('cancel', $this->url->ssl('usergroup'));

		if (($this->request->gethtml('user_group_id')) && (! $this->request->isPost())) {
			$user_group_info = $this->modelAdminUsergroup->get_usergroup($this->request->gethtml('user_group_id'));
		}

		if ($this->request->has('name', 'post')) {
			$view->set('name', $this->request->gethtml('name', 'post'));
		} else {
			$view->set('name', @$user_group_info['name']);
		}

		if (isset($user_group_info['permission'])) {
			$permission = unserialize($user_group_info['permission']);
		}

		if ($this->request->has('access', 'post')) {
			$permission['access'] = $this->request->gethtml('access', 'post');
		}
		
		if (!isset($permission['access'])) { 
			$permission['access'] = array();
		}

		if ($this->request->has('modify', 'post')) {
			$permission['modify'] = $this->request->gethtml('modify', 'post');
		}

		if (!isset($permission['modify'])) {
			$permission['modify'] = array();
		}

		$permission_data = array();

		foreach (glob(DIR_CONTROLLER . '*') as $controller) {
			$permission_data[] = array(
				'name'   => basename($controller, '.php'),
				'access' => in_array(basename($controller, '.php'), $permission['access']),
				'modify' => in_array(basename($controller, '.php'), $permission['modify'])
			);
		}

		$view->set('permissions', $permission_data);

		if (in_array('*', $permission['access'])) { $view->set('all_access', 1); }
		if (in_array('*', $permission['modify'])) { $view->set('all_modify', 1); }

		return $view->fetch('content/user_group.tpl');
	}

	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		
		if (!$this->user->hasPermission('modify', 'usergroup')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
    
        if (!$this->validate->strlen($this->request->gethtml('name', 'post'),1,64)) {
			$this->error['name'] = $this->language->get('error_name');
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
			$this->response->redirect($this->url->ssl('usergroup'));//**
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('usergroup'));//**
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'usergroup')) {//**
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}

	function validateDelete() {
		if(($this->session->get('user_validation') != $this->request->sanitize('user_validation')) || (strlen($this->session->get('user_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('user_validation');
		
		if (!$this->user->hasPermission('modify', 'usergroup')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		$user_info = $this->modelAdminUsergroup->check_user($this->request->gethtml('user_group_id'));
		if ($user_info['total']) {
			$this->error['message'] = $this->language->get('error_user', $user_info['total']);
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function page() {
		if ($this->request->has('search', 'post')) {
			$this->session->set('user_group.search', $this->request->gethtml('search', 'post'));
		}

		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('user_group.page', $this->request->gethtml('page', 'post'));
		}

		if ($this->request->has('sort', 'post')) {
			$this->session->set('user_group.order', (($this->session->get('user_group.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('user_group.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($this->request->has('sort', 'post')) {
			$this->session->set('user_group.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('usergroup'));
	}	
}
?>

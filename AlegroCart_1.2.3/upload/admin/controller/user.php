<?php  //Admin User AlegroCart
class ControllerUser extends Controller { 
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
		$this->user    		=& $locator->get('user');
		$this->validate 	=& $locator->get('validate');
		$this->modelAdminUser = $model->get('model_admin_user');
		
		$this->language->load('controller/user.php');
	}
  	function index() {
    	$this->template->set('title', $this->language->get('heading_title'));
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
		
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}
  
  	function insert() {
    	$this->template->set('title', $this->language->get('heading_title'));
		
    	if ($this->request->isPost() && $this->request->has('username', 'post') && $this->validateForm()) {
			$this->modelAdminUser->insert_user();
			$this->session->set('message', $this->language->get('text_message'));
	  		$this->response->redirect($this->url->ssl('user'));
    	}
    
    	$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function update() {
    	$this->template->set('title', $this->language->get('heading_title'));
		
    	if ($this->request->isPost() && $this->request->has('username', 'post') && $this->validateForm()) {
			$this->modelAdminUser->update_user($this->request->gethtml('user_id'));
			if ($this->request->sanitize('password', 'post')) { 
				$this->modelAdminUser->update_password($this->request->gethtml('user_id'));
			}
			$this->session->set('message', $this->language->get('text_message'));
	  		$this->response->redirect($this->url->ssl('user'));
    	}
    
    	$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}
 
  	function delete() { 
    	$this->template->set('title', $this->language->get('heading_title'));
	
    	if (($this->request->gethtml('user_id')) && ($this->validateDelete())) {
			$this->modelAdminUser->delete_user($this->request->gethtml('user_id'));
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('user'));
    	}
    
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function getList() {
		$this->session->set('user_validation', md5(time()));
		
    	$cols = array();

    	$cols[] = array(
      		'name'  => $this->language->get('column_username'),
      		'sort'  => 'username',
      		'align' => 'left'
    	);

    	$cols[] = array(
      		'name'  => $this->language->get('column_date_added'),
      		'sort'  => 'date_added',
      		'align' => 'left'
    	);

    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'right'
    	);
		
    	if (!$this->session->get('user.search')) {
      		$sql = "select user_id, username, date_added from user";
    	} else {
      		$sql = "select user_id, username, date_added from user where username like '?'";
    	}
    
		$sort = array(
	  		'username', 
	  		'date_added'
		);
	
    	if (in_array($this->session->get('user.sort'), $sort)) {
      		$sql .= " order by " . $this->session->get('user.sort') . " " . (($this->session->get('user.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by username asc";
    	}
		$results = $this->modelAdminUser->get_page($sql);

    	$rows = array();

    	foreach ($results as $result) {
      		$cell = array();
 
      		$cell[] = array(
        		'value' => $result['username'],
        		'align' => 'left'
      		);

      		$cell[] = array(
        		'value' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_added'])),
        		'align' => 'left'
      		);
			
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('user', 'update', array('user_id' => $result['user_id']))
      		);
			
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('user', 'delete', array('user_id' => $result['user_id'],'user_validation' =>$this->session->get('user_validation')))
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

    	$view->set('text_results', $this->modelAdminUser->get_text_results());

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
		  
    	$view->set('action', $this->url->ssl('user', 'page'));
		$view->set('action_delete', $this->url->ssl('user', 'enableDelete'));
  
    	$view->set('search', $this->session->get('user.search'));
    	$view->set('sort', $this->session->get('user.sort'));
    	$view->set('order', $this->session->get('user.order'));
    	$view->set('page', $this->session->get('user.page'));
    	$view->set('cols', $cols);
    	$view->set('rows', $rows);
   
    	$view->set('list', $this->url->ssl('user'));
    	$view->set('insert', $this->url->ssl('user', 'insert'));
    	$view->set('pages', $this->modelAdminUser->get_pagination());

		return $view->fetch('content/list.tpl');
  	}

  	function getForm() {
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('heading_description', $this->language->get('heading_description'));

    	$view->set('entry_username', $this->language->get('entry_username'));
    	$view->set('entry_password', $this->language->get('entry_password'));
    	$view->set('entry_confirm', $this->language->get('entry_confirm'));
    	$view->set('entry_firstname', $this->language->get('entry_firstname'));
    	$view->set('entry_lastname', $this->language->get('entry_lastname'));
    	$view->set('entry_email', $this->language->get('entry_email'));
    	$view->set('entry_user_group', $this->language->get('entry_user_group'));

    	$view->set('button_list', $this->language->get('button_list'));
    	$view->set('button_insert', $this->language->get('button_insert'));
    	$view->set('button_update', $this->language->get('button_update'));
    	$view->set('button_delete', $this->language->get('button_delete'));
    	$view->set('button_save', $this->language->get('button_save'));
    	$view->set('button_cancel', $this->language->get('button_cancel'));

    	$view->set('tab_general', $this->language->get('tab_general'));
    
		$view->set('error', @$this->error['message']);
    	$view->set('error_username', @$this->error['username']);
    	$view->set('error_password', @$this->error['password']);
    	$view->set('error_confirm', @$this->error['confirm']);
    	$view->set('error_firstname', @$this->error['firstname']);
    	$view->set('error_lastname', @$this->error['lastname']);
	
    	$view->set('action', $this->url->ssl('user', $this->request->gethtml('action'), array('user_id' => $this->request->gethtml('user_id'))));
      
    	$view->set('list', $this->url->ssl('user'));
    	$view->set('insert', $this->url->ssl('user', 'insert'));
		
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

    	if ($this->request->gethtml('user_id')) {
      		$view->set('update', $this->url->ssl('user', 'update', array('user_id' => (int)$this->request->gethtml('user_id'))));
      		$view->set('delete', $this->url->ssl('user', 'delete', array('user_id' => (int)$this->request->gethtml('user_id'),'user_validation' =>$this->session->get('user_validation'))));
    	}
  
    	$view->set('cancel', $this->url->ssl('user'));

    	if (($this->request->gethtml('user_id')) && (!$this->request->isPost())) {
			$user_info = $this->modelAdminUser->get_user($this->request->gethtml('user_id'));
    	}

    	if ($this->request->has('username', 'post')) {
      		$view->set('username', $this->request->sanitize('username', 'post'));
    	} else {
      		$view->set('username', @$user_info['username']);
    	}
  
    	$view->set('password', $this->request->sanitize('password', 'post'));

    	$view->set('confirm', $this->request->sanitize('confirm', 'post'));
  
    	if ($this->request->has('firstname', 'post')) {
      		$view->set('firstname', $this->request->sanitize('firstname', 'post'));
    	} else {
      		$view->set('firstname', @$user_info['firstname']);
    	}

    	if ($this->request->has('lastname', 'post')) {
      		$view->set('lastname', $this->request->sanitize('lastname', 'post'));
    	} else {
      		$view->set('lastname', @$user_info['lastname']);
   		}
  
    	if ($this->request->has('email', 'post')) {
      		$view->set('email', $this->request->sanitize('email', 'post'));
    	} else {
      		$view->set('email', @$user_info['email']);
    	}

    	if ($this->request->has('user_group_id', 'post')) {
      		$view->set('user_group_id', $this->request->gethtml('user_group_id', 'post'));
    	} else {
      		$view->set('user_group_id', @$user_info['user_group_id']);
    	}

    	$view->set('user_groups', $this->modelAdminUser->get_user_groups());
 
 		return $view->fetch('content/user.tpl');	
  	}

  	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->url->validate_referer()) {
			$this->session->set('message', $this->language->get('error_referer'));
			$this->response->redirect($this->url->ssl('user'));
		}

    	if (!$this->user->hasPermission('modify', 'user')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}

        if (!$this->validate->strlen($this->request->sanitize('username', 'post'),1,20)) {
      		$this->error['username'] = $this->language->get('error_username');
    	}

    	if (!$this->validate->strlen($this->request->sanitize('firstname', 'post'),1,32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

        if (!$this->validate->strlen($this->request->sanitize('lastname', 'post'),1,32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if (($this->request->sanitize('password', 'post')) || ($this->request->gethtml('action') == 'insert')) {
      		if (!$this->validate->strlen($this->request->sanitize('password', 'post'),1,20)) {
        		$this->error['password'] = $this->language->get('error_password');
      		}
	  		if ($this->request->sanitize('password', 'post') != $this->request->sanitize('confirm', 'post')) {
	    		$this->error['confirm'] = $this->language->get('error_confirm');
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
			$this->response->redirect($this->url->ssl('user'));//**
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('user'));//**
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'user')) {//**
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
		if (!$this->url->validate_referer()) {
			$this->error['message'] = $this->language->get('error_referer');
		}

    	if ($this->user->getId() == $this->request->gethtml('user_id')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	} //User is unable to delete them self

    	if ($this->user->isSuperAdmin($this->request->gethtml('user_id'))) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}//User is unable to delete the super admin

    	if (!$this->user->hasPermission('modify', 'user')) {
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
			$this->session->set('user.search', $this->request->gethtml('search', 'post'));
		}

		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('user.page', $this->request->gethtml('page', 'post'));
		}

		if ($this->request->has('sort', 'post')) {
			$this->session->set('user.order', (($this->session->get('user.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('user.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($this->request->has('sort', 'post')) {
			$this->session->set('user.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('user'));
	}  
}
?>
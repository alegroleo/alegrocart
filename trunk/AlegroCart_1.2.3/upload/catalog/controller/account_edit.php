<?php // Account Edit AlegroCart
class ControllerAccountEdit extends Controller {
	var $error = array();
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->config  		=& $locator->get('config');
		$this->customer 	=& $locator->get('customer');
		$this->head_def 	=& $locator->get('HeaderDefinition'); 
		$this->language 	=& $locator->get('language');
		$this->mail     	=& $locator->get('mail');
		$this->mail_check  	=& $locator->get('mail_check_mx');
		$this->module   	=& $locator->get('module');
		$this->response 	=& $locator->get('response');
		$this->request  	=& $locator->get('request');
		$this->session  	=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->validate 	=& $locator->get('validate');
		$this->modelCore 	= $model->get('model_core');
		$this->modelAccountCreate = $model->get('model_accountcreate');
		$this->tpl_manager 	= $this->modelCore->get_tpl_manager('account_edit'); // Template Manager
		$this->locations 	= $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns 	= $this->modelCore->get_columns();// Template Manager
	}

	function index() {
		if (!$this->customer->isLogged()) {
			$this->session->set('redirect', $this->url->ssl('account_edit'));

			$this->response->redirect($this->url->ssl('account_login'));
		}

		$this->language->load('controller/account_edit.php');

		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('firstname', 'post') && $this->validate()) {
			if(($this->session->get('account_validation') == $this->request->gethtml('account_validation','post')) && (strlen($this->session->get('account_validation')) > 10)){
				$this->modelAccountCreate->update_customer($this->customer->getId());
				$this->session->set('message', $this->language->get('text_message'));
			} else {
				$this->session->set('message',$this->language->get('error_referer'));
			}
			$this->session->delete('account_validation');
			$this->response->redirect($this->url->ssl('account'));
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('text_your_details', $this->language->get('text_your_details'));
		$view->set('text_your_password', $this->language->get('text_your_password'));

		$view->set('entry_firstname', $this->language->get('entry_firstname'));
		$view->set('entry_lastname', $this->language->get('entry_lastname'));
		$view->set('entry_date_of_birth', $this->language->get('entry_date_of_birth'));
		$view->set('entry_email', $this->language->get('entry_email'));
		$view->set('entry_telephone', $this->language->get('entry_telephone'));
		$view->set('entry_fax', $this->language->get('entry_fax'));
		$view->set('entry_password', $this->language->get('entry_password'));
		$view->set('entry_confirm', $this->language->get('entry_confirm'));

		$view->set('button_continue', $this->language->get('button_continue'));
		$view->set('button_back', $this->language->get('button_back'));

		$view->set('error', @$this->error['message']);
		$view->set('error_firstname', @$this->error['firstname']);
		$view->set('error_lastname', @$this->error['lastname']);
		$view->set('error_email', @$this->error['email']);
		$view->set('error_telephone', @$this->error['telephone']);
		$view->set('error_password', @$this->error['password']);
		$view->set('error_confirm', @$this->error['confirm']);

		$view->set('action', $this->url->ssl('account_edit'));
		
		if($this->session->get('message')){
			$view->set('message', $this->session->get('message'));
			$this->session->delete('message');
		}
		
		$this->session->set('account_validation', md5(time()));
		$view->set('account_validation', $this->session->get('account_validation'));
		
		if (($this->customer->getId()) && (!$this->request->isPost())) {
			$customer_info = $this->modelAccountCreate->get_customer($this->customer->getId());
		}

		if ($this->request->has('firstname', 'post')) {
			$view->set('firstname', $this->request->sanitize('firstname', 'post'));
		} else {
			$view->set('firstname', @$customer_info['firstname']);
		}

		if ($this->request->has('lastname', 'post')) {
			$view->set('lastname', $this->request->sanitize('lastname', 'post'));
		} else {
			$view->set('lastname', @$customer_info['lastname']);
		}

		if ($this->request->has('email', 'post')) {
			$view->set('email', $this->request->gethtml('email', 'post'));
		} else {
			$view->set('email',@$customer_info['email']);
		}

		if ($this->request->has('telephone', 'post')) {
			$view->set('telephone', $this->request->sanitize('telephone', 'post'));
		} else {
			$view->set('telephone',@$customer_info['telephone']);
		}

		if ($this->request->has('fax', 'post')) {
			$view->set('fax', $this->request->sanitize('fax', 'post'));
		} else {
			$view->set('fax',@$customer_info['fax']);
		}

		$view->set('password', $this->request->gethtml('password', 'post'));
		$view->set('confirm', $this->request->gethtml('confirm', 'post'));

		$view->set('back', $this->url->ssl('account'));
		$view->set('head_def',$this->head_def);	
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $view->fetch('content/account_edit.tpl'));

		$this->load_modules();  // Template Manager
		$this->set_tpl_modules(); // Template Manager
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function load_modules(){ // Template Manager
		$modules = $this->modelCore->merge_modules($this->get_modules_extra());
		foreach ($this->locations as $location){
			if($modules[$location['location']]){
				foreach($modules[$location['location']] as $module){
					$this->template->set($this->module->load($module));
				}
			}
		}
	}
	function get_modules_extra(){// Template Manager (Default Modules specific to current controller)
		foreach($this->locations as $location){
			$modules_extra[$location['location']] = array();
		}
		$modules_extra['column'] = array('manufacturer', 'popular');
		$modules_extra['columnright'] = array('specials');
		return $modules_extra;
	}

	function set_tpl_modules(){ // Template Manager
		if($this->modelCore->tpl){
			if(isset($this->modelCore->tpl['tpl_headers'])){$this->template->set('tpl_headers',$this->modelCore->tpl['tpl_headers']);}
			if(isset($this->modelCore->tpl['tpl_extras'])){$this->template->set('tpl_extras',$this->modelCore->tpl['tpl_extras']);}
			if(isset($this->modelCore->tpl['tpl_left_columns'])){$this->template->set('tpl_left_columns',$this->modelCore->tpl['tpl_left_columns']);}
			if(isset($this->modelCore->tpl['tpl_contents'])){$this->template->set('tpl_contents',$this->modelCore->tpl['tpl_contents']);}
			if(isset($this->modelCore->tpl['tpl_right_columns'])){$this->template->set('tpl_right_columns',$this->modelCore->tpl['tpl_right_columns']);}
			if(isset($this->modelCore->tpl['tpl_footers'])){$this->template->set('tpl_footers',$this->modelCore->tpl['tpl_footers']);}
			if(isset($this->modelCore->tpl['tpl_bottom'])){$this->template->set('tpl_bottom',$this->modelCore->tpl['tpl_bottom']);}
		}
		if(isset($this->tpl_manager['tpl_color']) && $this->tpl_manager['tpl_color']){$this->template->set('template_color',$this->tpl_manager['tpl_color']);}
		$this->template->set('tpl_columns', $this->modelCore->tpl_columns);
	}
	
	function validate() {
		if ((strlen($this->request->sanitize('firstname', 'post')) < 3) || (strlen($this->request->sanitize('firstname', 'post')) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}
		if ((strlen($this->request->sanitize('lastname', 'post')) < 3) || (strlen($this->request->sanitize('lastname', 'post')) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}
		if ((!$this->validate->strlen($this->request->gethtml('email', 'post'), 6, 32)) || (!$this->validate->email($this->request->gethtml('email', 'post'))) || $this->mail_check->final_mail_check($this->request->gethtml('email', 'post')) == FALSE) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
		if ($this->modelAccountCreate->check_email($this->customer->getId())) {
			$this->error['message'] = $this->language->get('error_exists');
		}
		if ((strlen($this->request->sanitize('telephone', 'post')) < 3) || (strlen($this->request->sanitize('telephone', 'post')) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>
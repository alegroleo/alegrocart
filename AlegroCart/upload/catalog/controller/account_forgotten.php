<?php //Account Forgotten AlegroCart
class ControllerAccountForgotten extends Controller {
	var $error = array();
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->config  		=& $locator->get('config');
		$this->customer 	=& $locator->get('customer');
		$this->head_def 	=& $locator->get('HeaderDefinition');
		$this->language 	=& $locator->get('language');
		$this->mail         =& $locator->get('mail');
		$this->mail_check   =& $locator->get('mail_check_mx');
		$this->module   	=& $locator->get('module');
		$this->response 	=& $locator->get('response');
		$this->request 		=& $locator->get('request');
		$this->session  	=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->validate 	=& $locator->get('validate');
		$this->modelCore 	= $model->get('model_core');
		$this->modelAccountCreate = $model->get('model_accountcreate');
		$this->tpl_manager 	= $this->modelCore->get_tpl_manager('account_forgotten'); // Template Manager
		$this->locations 	= $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns 	= $this->modelCore->get_columns();// Template Manager
	}

	function index() {
		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->ssl('account'));
		}

		$this->language->load('controller/account_forgotten.php');

		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->isPost()) && ($this->validate()) && $this->request->has('email', 'post')) {
			if(($this->session->get('account_validation') == $this->request->gethtml('account_validation','post')) && (strlen($this->session->get('account_validation')) > 10)){
				
				$password = substr(md5(rand()), 0, 7);
				$this->mail->setTo($this->request->gethtml('email', 'post'));
				$this->mail->setFrom($this->config->get('config_email'));
				$this->mail->setSender($this->config->get('config_store'));
				$this->mail->setSubject($this->language->get('email_subject', $this->config->get('config_store')));
				$this->mail->setText($this->language->get('email_message', $this->config->get('config_store'), $this->config->get('config_store'), $password, $this->url->ssl('account_login')));
				$this->mail->send();
				$this->modelAccountCreate->reset_password($password);
				$this->session->set('message', $this->language->get('text_message'));
			} else {
				$this->session->set('message',$this->language->get('error_referer'));
			}
			$this->session->delete('account_validation');
			$this->response->redirect($this->url->ssl('account_login'));
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));

		$view->set('text_your_email', $this->language->get('text_your_email'));
		$view->set('text_email', $this->language->get('text_email'));
		$view->set('entry_email', $this->language->get('entry_email'));
		$view->set('button_continue', $this->language->get('button_continue'));
		$view->set('button_back', $this->language->get('button_back'));
		$view->set('error', @$this->error['message']);

		$view->set('action', $this->url->ssl('account_forgotten'));

		$this->session->set('account_validation', md5(time()));
		$view->set('account_validation', $this->session->get('account_validation'));
 
		$view->set('back', $this->url->ssl('account'));
		$view->set('head_def',$this->head_def);
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $view->fetch('content/account_forgotten.tpl'));

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
		if (!$this->request->gethtml('email', 'post')) {
			$this->error['message'] = $this->language->get('error_email');
		}  elseif (!$this->modelAccountCreate->check_customer($this->request->gethtml('email', 'post'))){
			$this->error['message'] = $this->language->get('error_email');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>
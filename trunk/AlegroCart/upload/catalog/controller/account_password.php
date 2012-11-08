<?php //Account Password AlegroCart
class ControllerAccountPassword extends Controller {
	var $error = array();
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->config  		=& $locator->get('config');
		$this->customer 	=& $locator->get('customer');
		$this->head_def 	=& $locator->get('HeaderDefinition');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->response 	=& $locator->get('response');
		$this->request  	=& $locator->get('request');
		$this->session  	=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->validate 	=& $locator->get('validate');
		$this->modelCore 	= $model->get('model_core');
		$this->modelAccountCreate = $model->get('model_accountcreate');
		$this->tpl_manager 	= $this->modelCore->get_tpl_manager('account_password'); // Template Manager
		$this->locations 	= $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns 	= $this->modelCore->get_columns();// Template Manager
	}

  	function index() {
    	if (!$this->customer->isLogged()) {
      		$this->session->set('redirect', $this->url->ssl('account_password'));

      		$this->response->redirect($this->url->ssl('account_login'));
    	}

		$this->language->load('controller/account_password.php');
    	$this->template->set('title', $this->language->get('heading_title'));
			  
    	if ($this->request->isPost() && $this->request->has('password', 'post') && $this->validate()) {
			if(($this->session->get('account_validation') == $this->request->gethtml('account_validation','post')) && (strlen($this->session->get('account_validation')) > 10)){
				$this->modelAccountCreate->update_password($this->customer->getId());
				$this->session->delete('account_validation');
				$this->session->set('message', $this->language->get('text_message'));
			} else {
				$this->session->set('message',$this->language->get('error_referer'));
			}
	  		$this->response->redirect($this->url->ssl('account'));
    	}
	
    	$view = $this->locator->create('template');
    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('text_password', $this->language->get('text_password'));
    	$view->set('entry_password', $this->language->get('entry_password'));
    	$view->set('entry_confirm', $this->language->get('entry_confirm'));
    	$view->set('button_continue', $this->language->get('button_continue'));
    	$view->set('button_back', $this->language->get('button_back'));
		$view->set('error_password', @$this->error['password']);
    	$view->set('error_confirm', @$this->error['confirm']);
    	$view->set('action', $this->url->ssl('account_password'));

		$this->session->set('account_validation', md5(time()));
		$view->set('account_validation', $this->session->get('account_validation'));
		
    	$view->set('password', $this->request->sanitize('password', 'post'));
    	$view->set('confirm', $this->request->sanitize('confirm', 'post'));

    	$view->set('back', $this->url->ssl('account'));
		$view->set('head_def',$this->head_def); 
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $view->fetch('content/account_password.tpl'));
	
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
    	if ((strlen($this->request->sanitize('password', 'post')) < 5) || (strlen($this->request->sanitize('password', 'post')) > 20)) {
      		$this->error['password'] = $this->language->get('error_password');
    	}
    	if ($this->request->sanitize('confirm', 'post') != $this->request->sanitize('password', 'post')) {
      		$this->error['confirm'] = $this->language->get('error_confirm');
    	}  
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
}
?>

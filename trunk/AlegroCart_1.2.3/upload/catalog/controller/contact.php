<?php //Contact AlegroCart
class ControllerContact extends Controller {
	var $error = array(); 
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->config  		=& $locator->get('config');
		$this->config->set('config_tax', $this->config->get('config_tax_store'));
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
		$this->validate		=& $locator->get('validate');
		require_once('library/application/string_modify.php'); 
		$this->language->load('controller/contact.php');
		$this->modelCore 	= $model->get('model_core');
		$this->tpl_manager = $this->modelCore->get_tpl_manager('contact'); // Template Manager
		$this->locations = $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns = $this->modelCore->get_columns();// Template Manager
	}

  	function index() {
    	$this->template->set('title', $this->language->get('heading_title'));  
	 
    	if ($this->request->isPost() && $this->request->has('email', 'post') && $this->validate()) {
	  		
			$this->mail->setTo($this->config->get('config_email_contact') ? $this->config->get('config_email_contact') : $this->config->get('config_email'));
	  		$this->mail->setFrom($this->request->sanitize('email', 'post'));
	  		$this->mail->setSender($this->request->sanitize('name', 'post'));
	  		$this->mail->setSubject($this->language->get('email_subject', $this->request->sanitize('name', 'post')));
	  		$this->mail->setText($this->request->sanitize('enquiry', 'post'));
      		$this->mail->send();

	  		$this->response->redirect($this->url->ssl('contact', 'success'));
    	}

    	$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);    // New Header

    	$view->set('heading_title', $this->language->get('heading_title'));

    	$view->set('text_address', $this->language->get('text_address'));
    	$view->set('text_telephone', $this->language->get('text_telephone'));
    	$view->set('text_fax', $this->language->get('text_fax'));

    	$view->set('entry_name', $this->language->get('entry_name'));
    	$view->set('entry_email', $this->language->get('entry_email'));
    	$view->set('entry_enquiry', $this->language->get('entry_enquiry'));

    	$view->set('error_name', @$this->error['name']);
    	$view->set('error_email', @$this->error['email']);
    	$view->set('error_enquiry', @$this->error['enquiry']);

    	$view->set('button_continue', $this->language->get('button_continue'));
    
		$view->set('action', $this->url->href('contact'));
    
		$view->set('store', $this->config->get('config_store'));

    	$view->set('address', nl2br($this->config->get('config_address')));

    	$view->set('telephone', $this->config->get('config_telephone'));

    	$view->set('fax', $this->config->get('config_fax'));

    	$view->set('name', $this->request->sanitize('name', 'post'));

    	$view->set('email', $this->request->sanitize('email', 'post'));

    	$view->set('enquiry', $this->request->sanitize('enquiry', 'post'));
		
		$this->template->set('head_def',$this->head_def);    // New Header
		$this->template->set('content', $view->fetch('content/contact.tpl'));
		$this->load_modules();  // Template Manager
		$this->set_tpl_modules(); // Template Manager
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function success() {
    	$this->template->set('title', $this->language->get('heading_title'));  
		
    	$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);    // New Header
    	$view->set('heading_title', $this->language->get('heading_title'));

    	$view->set('text_success', $this->language->get('text_success'));

    	$view->set('button_continue', $this->language->get('button_continue'));

    	$view->set('continue', $this->url->href('home'));
		$this->template->set('head_def',$this->head_def);    // New Header
		$this->template->set('content', $view->fetch('content/success.tpl'));
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
		$modules_extra['column'] = array('popular');
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
		if (!$this->validate->strlen($this->request->sanitize('name', 'post'),3,32)) {
      		$this->error['name'] = $this->language->get('error_name');
    	}

    	if ((!$this->validate->strlen($this->request->sanitize('email', 'post'), 6, 32)) || (!$this->validate->email($this->request->sanitize('email', 'post'))) || $this->mail_check->final_mail_check($this->request->sanitize('email', 'post')) == FALSE) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if (!$this->validate->strlen($this->request->sanitize('enquiry', 'post'),10,1000)) {
      		$this->error['enquiry'] = $this->language->get('error_enquiry');
    	}
	 
		if (!$this->error){
			return TRUE;
		} else {
      		return FALSE;
    	}
  	}
}
?>

<?php 
class ControllerAccountCreate extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->config   	=& $locator->get('config');
		$this->customer 	=& $locator->get('customer');
		$this->cart    		=& $locator->get('cart');
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
		$this->modelAccountAddress = $model->get('model_accountaddress');
		$this->modelAccountCreate = $model->get('model_accountcreate');
		$this->modelCore 	= $model->get('model_core');
		$this->tpl_manager = $this->modelCore->get_tpl_manager('account_create'); // Template Manager
		$this->locations = $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns = $this->modelCore->get_columns();// Template Manager
	}
  	function index() {
		 
		if ($this->customer->isLogged()) {
	  		$this->response->redirect($this->url->ssl('account'));
    	}

    	$this->language->load('controller/account_create.php');

    	$this->template->set('title', $this->language->get('heading_title'));
		
    	if ($this->request->isPost() && $this->request->has('firstname', 'post') && $this->validate()) {
			if(($this->session->get('account_validation') == $this->request->gethtml('account_validation','post')) && (strlen($this->session->get('account_validation')) > 10)){
				$this->modelAccountCreate->insert_customer();
				$this->customer->login($this->request->gethtml('email', 'post'), $this->request->gethtml('password', 'post'));
				$this->modelAccountAddress->insert_address($this->customer->getId());
				$this->modelAccountCreate->set_default_address($this->customer->getId());

				if ($this->config->get('config_email_send')) {
					$this->mail->setTo($this->request->sanitize('email', 'post'));
					$this->mail->setFrom($this->config->get('config_email'));
					$this->mail->setSender($this->config->get('config_store'));
					$this->mail->setSubject($this->language->get('email_subject', $this->config->get('config_store')));
					$this->mail->setCharacterSet($this->language->get('charset'));
					$this->mail->setText($this->language->get('email_message', $this->request->sanitize('firstname', 'post'), $this->config->get('config_store'), $this->url->ssl('account_login'), $this->config->get('config_store')));
					if(!$this->session->get('guest_account')){
						$this->mail->send();
					}
					$this->mail->setTo($this->config->get('config_email_accounts') ? $this->config->get('config_email_accounts') : $this->config->get('config_email'));
					$this->mail->send();
				}
				
				$this->session->delete('account_validation');
				if($this->session->get('guest_account')){
					if ($this->cart->hasProducts()) {
						$this->response->redirect($this->url->ssl('checkout_confirm'));
					} else {
						$this->response->redirect($this->url->href('home'));
					}
				} else {
					$this->response->redirect($this->url->ssl('account_success'));
				}
			} else {
				$this->session->set('message',$this->language->get('error_referer'));
				$this->session->delete('account_validation');
				$this->response->redirect($this->url->ssl('account_create'));
			}
    	} 
		
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->session->get('guest_account') ? $this->language->get('heading_guest') : $this->language->get('heading_title'));

		$view->set('text_yes', $this->language->get('text_yes'));
		$view->set('text_no', $this->language->get('text_no'));
    	$view->set('text_account_already', $this->language->get('text_account_already', $this->url->ssl('account_login')));
    	$view->set('text_your_details', $this->language->get('text_your_details'));
    	$view->set('text_your_address', $this->language->get('text_your_address'));
    	$view->set('text_your_password', $this->language->get('text_your_password'));
		$view->set('text_newsletter', $this->language->get('text_newsletter'));
		$view->set('text_no_postal', $this->language->get('text_no_postal'));

    	$view->set('entry_firstname', $this->language->get('entry_firstname'));
    	$view->set('entry_lastname', $this->language->get('entry_lastname'));
    	$view->set('entry_email', $this->language->get('entry_email'));
    	$view->set('entry_telephone', $this->language->get('entry_telephone'));
    	$view->set('entry_fax', $this->language->get('entry_fax'));
    	$view->set('entry_company', $this->language->get('entry_company'));
    	$view->set('entry_address_1', $this->language->get('entry_address_1'));
    	$view->set('entry_address_2', $this->language->get('entry_address_2'));
    	$view->set('entry_postcode', $this->language->get('entry_postcode'));
    	$view->set('entry_city', $this->language->get('entry_city'));
    	$view->set('entry_country', $this->language->get('entry_country'));
    	$view->set('entry_zone', $this->language->get('entry_zone'));
		$view->set('entry_newsletter', $this->language->get('entry_newsletter'));
    	$view->set('entry_password', $this->language->get('entry_password'));
    	$view->set('entry_confirm', $this->language->get('entry_confirm'));

		$view->set('button_continue', $this->language->get('button_continue'));
    
		$view->set('error', @$this->error['message']);
		$view->set('error_firstname', @$this->error['firstname']);
    	$view->set('error_lastname', @$this->error['lastname']);
    	$view->set('error_email', @$this->error['email']);
    	$view->set('error_telephone', @$this->error['telephone']);
    	$view->set('error_password', @$this->error['password']);
    	$view->set('error_confirm', @$this->error['confirm']);
    	$view->set('error_address_1', @$this->error['address_1']);
    	$view->set('error_city', @$this->error['city']);
		$view->set('error_postcode', @$this->error['postcode']);

    	$view->set('action', $this->url->ssl('account_create'));
		
		if($this->session->get('message')){
			$view->set('message', $this->session->get('message'));
			$this->session->delete('message');
		}
		
		$this->session->set('account_validation', md5(time()));
		$view->set('account_validation', $this->session->get('account_validation'));
		
		$view->set('guest', $this->session->get('guest_account'));

    	$view->set('firstname', $this->request->sanitize('firstname', 'post'));

    	$view->set('lastname', $this->request->sanitize('lastname', 'post'));

    	$view->set('email', $this->request->sanitize('email', 'post'));

    	$view->set('telephone', $this->request->sanitize('telephone', 'post'));

    	$view->set('fax', $this->request->sanitize('fax', 'post'));

    	$view->set('company', $this->request->sanitize('company', 'post'));

    	$view->set('address_1', $this->request->sanitize('address_1', 'post'));

    	$view->set('address_2', $this->request->sanitize('address_2', 'post'));

    	$view->set('postcode', $this->request->sanitize('postcode', 'post'));

    	$view->set('city', $this->request->sanitize('city', 'post'));

    	if ($this->request->has('country_id', 'post')) {
      		$view->set('country_id', $this->request->gethtml('country_id', 'post'));
    	} else {
      		$view->set('country_id', $this->config->get('config_country_id'));
    	}

    	if ($this->request->has('zone_id', 'post')) {
      		$view->set('zone_id', $this->request->gethtml('zone_id', 'post'));
    	} else {
      		$view->set('zone_id', $this->config->get('config_zone_id'));
    	}

      	$view->set('agreed', $this->request->gethtml('agreed', 'post'));
		
		$view->set('countries',$this->modelAccountAddress->get_countries());
		$view->set('zones', $this->modelAccountAddress->get_zones());

    	$view->set('password', $this->request->sanitize('password', 'post'));

    	$view->set('confirm', $this->request->sanitize('confirm', 'post'));
		
		$view->set('newsletter', $this->request->gethtml('newsletter', 'post'));

		if ($this->config->get('config_account_id')) {
			$information_info = $this->modelAccountCreate->get_information();

			$view->set('agree', $this->language->get('text_agree', $this->url->href('information', FALSE, array('information_id' => $this->config->get('config_account_id'))), $information_info['title']));
		}
		
		$view->set('head_def',$this->head_def);
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $view->fetch('content/account_create.tpl'));

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
		if (!$this->validate->strlen($this->request->sanitize('firstname', 'post'),2,32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}
		if (!$this->validate->strlen($this->request->sanitize('lastname', 'post'),2,32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}
    	if ((!$this->validate->strlen($this->request->sanitize('email', 'post'), 6, 32)) || (!$this->validate->email($this->request->sanitize('email', 'post'))) || $this->mail_check->final_mail_check($this->request->sanitize('email', 'post')) == FALSE) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
		if($this->modelAccountCreate->check_customer($this->request->sanitize('email', 'post'))){
      		$this->error['message'] = $this->language->get('error_exists');
    	}
		if(!$this->session->get('guest_account')){
			if (!$this->validate->strlen($this->request->sanitize('password', 'post'),4,20)) {
				$this->error['password'] = $this->language->get('error_password');
			}
			if ($this->request->sanitize('confirm', 'post') != $this->request->sanitize('password', 'post')) {
				$this->error['confirm'] = $this->language->get('error_confirm');
			}
		}
		if (!$this->validate->strlen($this->request->sanitize('address_1', 'post'),3,64)) {
      		$this->error['address_1'] = $this->language->get('error_address_1');
    	}
		if (!$this->validate->strlen($this->request->sanitize('city', 'post'),3,32)) {
      		$this->error['city'] = $this->language->get('error_city');
    	}
		if (!$this->validate->strlen($this->request->sanitize('postcode', 'post'),4,10)){
			$this->error['postcode'] = $this->language->get('error_postcode');
		}
		if (!$this->validate->strlen($this->request->sanitize('telephone', 'post'),7,32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}
  
  	function zone() {
    	$output = '<select name="zone_id">';
		$results = $this->modelAccountAddress->return_zones($this->request->gethtml('country_id'));
        
      	foreach ($results as $result) {
        	$output .= '<option value="' . $result['zone_id'] . '"';
	
	    	if ($this->request->gethtml('zone_id') == $result['zone_id']) {
	      		$output .= ' SELECTED';
	    	}
	
	    	$output .= '>' . $result['name'] . '</option>';
    	} 
		
		if (!$results) {
		  	$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
    	}

    	$output .= '</select>';
	
		$this->response->set($output);
  	}  
}
?>
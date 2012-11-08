<?php // Account Address AlegroCart
class ControllerAccountAddress extends Controller {
	var $error = array();
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->address  	=& $locator->get('address');
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
		$this->modelAccountAddress = $model->get('model_accountaddress');
		$this->tpl_manager 	= $this->modelCore->get_tpl_manager('account_address'); // Template Manager
		$this->locations 	= $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns 	= $this->modelCore->get_columns();// Template Manager
	}

  	function index() {
    	if (!$this->customer->isLogged()) {
	  		$this->session->set('redirect', $this->url->ssl('account_address'));

	  		$this->response->redirect($this->url->ssl('account_login')); 
    	}
	
    	$this->language->load('controller/account_address.php');

		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());

    	$this->load_modules();  // Template Manager
		$this->set_tpl_modules(); // Template Manager
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));	
  	}

  	function insert() {
    	if (!$this->customer->isLogged()) {
	  		$this->session->set('redirect', $this->url->ssl('account_address'));
	  		$this->response->redirect($this->url->ssl('account_login')); 
    	} 

    	$this->language->load('controller/account_address.php');
		$this->template->set('title', $this->language->get('heading_title'));

    	if ($this->request->isPost() && $this->request->has('firstname', 'post') && $this->validateForm()) {
			if(($this->session->get('account_validation') == $this->request->gethtml('account_validation', 'post')) && (strlen($this->session->get('account_validation')) > 10)){
				$this->modelAccountAddress->insert_address($this->customer->getId());
				if ($this->request->get('default', 'post')) {
					$this->modelAccountAddress->insert_default_address($this->customer->getId());
				}
      		$this->session->set('message', $this->language->get('text_insert'));
	  		
			} else {
				$this->session->set('message',$this->language->get('error_referer'));
			}
			$this->session->delete('account_validation');
			$this->response->redirect($this->url->ssl('account_address'));
    	} 
		$this->session->delete('account_validation');
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());

		$this->load_modules();  // Template Manager
		$this->set_tpl_modules(); // Template Manager
      	$this->template->set($this->module->fetch());
      	$this->response->set($this->template->fetch('layout.tpl'));	
  	}

  	function update() {
    	if (!$this->customer->isLogged()) {
	  		$this->session->set('redirect', $this->url->ssl('account_address'));

	  		$this->response->redirect($this->url->ssl('account_login')); 
    	} 
		
    	$this->language->load('controller/account_address.php');

		$this->template->set('title', $this->language->get('heading_title'));
		
    	if ($this->request->isPost() && $this->request->has('firstname', 'post') && $this->validateForm()) {
			if(($this->session->get('account_validation') == $this->request->gethtml('account_validation','post')) && (strlen($this->session->get('account_validation')) > 10)){
				$this->modelAccountAddress->update_address($this->customer->getId());
				if ($this->request->get('default', 'post')) {
					$this->modelAccountAddress->update_default_address($this->customer->getId());
				}
				$this->session->set('message', $this->language->get('text_update'));
			} else {
				$this->session->set('message',$this->language->get('error_referer'));
			}
			$this->session->delete('account_validation');
	  		$this->response->redirect($this->url->ssl('account_address'));
    	} 
		$this->session->delete('account_validation');
		$this->template->set('head_def',$this->head_def);		  	
		$this->template->set('content', $this->getForm());

		$this->load_modules();  // Template Manager
		$this->set_tpl_modules(); // Template Manager
		$this->template->set($this->module->fetch());
      	$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function delete() {
    	if (!$this->customer->isLogged()) {
	  		$this->session->set('redirect', $this->url->ssl('account_address'));

	  		$this->response->redirect($this->url->ssl('account_login')); 
    	} 
			
    	$this->language->load('controller/account_address.php');

		$this->template->set('title', $this->language->get('heading_title'));

    	if (($this->request->gethtml('address_id')) && ($this->validateDelete())) {
			if(($this->session->get('account_validation') == $this->request->gethtml('account_validation')) && (strlen($this->session->get('account_validation')) > 10)){
				$this->modelAccountAddress->delete_address($this->customer->getId());
				$this->session->set('message', $this->language->get('text_delete'));
			} else {
				$this->session->set('message',$this->language->get('error_referer'));
			}
			$this->session->delete('account_validation');
	  		$this->response->redirect($this->url->ssl('account_address'));
    	}
		$this->session->delete('account_validation');
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());

		$this->load_modules();  // Template Manager
		$this->set_tpl_modules(); // Template Manager
		$this->template->set($this->module->fetch());
    	$this->response->set($this->template->fetch('layout.tpl'));	
  	}

  	function getList() {
		$this->session->set('account_validation', md5(time()));
    	$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def); 	

    	$view->set('heading_title', $this->language->get('heading_title'));

    	$view->set('text_address_book', $this->language->get('text_address_book'));
   
    	$view->set('button_new_address', $this->language->get('button_new_address'));
    	$view->set('button_edit', $this->language->get('button_edit'));
    	$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_back', $this->language->get('button_back'));

    	$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		
    	$this->session->delete('message');

    	$address_data = array();
		$results = $this->modelAccountAddress->get_addresses($this->customer->getId());

    	foreach ($results as $result) {
      		$address_data[] = array(
        		'address_id' => $result['address_id'],
        		'address'    => $this->address->format($result, $result['address_format'], '<br />'),
        		'update'     => $this->url->ssl('account_address', 'update', array('address_id' => $result['address_id'])),
				'delete'     => $this->url->ssl('account_address', 'delete', array('address_id' => $result['address_id'], 'account_validation' => $this->session->get('account_validation')))
      		);
    	}

    	$view->set('addresses', $address_data);

    	$view->set('insert', $this->url->ssl('account_address', 'insert'));

		$view->set('back', $this->url->ssl('account'));

    	return $view->fetch('content/account_addresses.tpl');
  	}

  	function getForm() {	
    	$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);

    	$view->set('heading_title', $this->language->get('heading_title'));

    	$view->set('entry_firstname', $this->language->get('entry_firstname'));
    	$view->set('entry_lastname', $this->language->get('entry_lastname'));
    	$view->set('entry_company', $this->language->get('entry_company'));
    	$view->set('entry_address_1', $this->language->get('entry_address_1'));
    	$view->set('entry_address_2', $this->language->get('entry_address_2'));
    	$view->set('entry_postcode', $this->language->get('entry_postcode'));
    	$view->set('entry_city', $this->language->get('entry_city'));
    	$view->set('entry_country', $this->language->get('entry_country'));
    	$view->set('entry_zone', $this->language->get('entry_zone'));
    	$view->set('entry_default', $this->language->get('entry_default'));

    	$view->set('text_new_address', $this->language->get('text_new_address'));
		$view->set('text_no_postal', $this->language->get('text_no_postal'));
    	$view->set('text_yes', $this->language->get('text_yes'));
    	$view->set('text_no', $this->language->get('text_no'));

    	$view->set('button_continue', $this->language->get('button_continue'));
    	$view->set('button_back', $this->language->get('button_back'));

    	$view->set('error_firstname', @$this->error['firstname']);
    	$view->set('error_lastname', @$this->error['lastname']);
    	$view->set('error_address_1', @$this->error['address_1']);
    	$view->set('error_city', @$this->error['city']);
		$view->set('error_postcode', @$this->error['postcode']);

    	$view->set('action', $this->url->ssl('account_address', $this->request->gethtml('action'), array('address_id' => $this->request->gethtml('address_id'))));
		$this->session->set('account_validation', md5(time()));
		$view->set('account_validation', $this->session->get('account_validation'));
		
    	if (($this->request->gethtml('address_id')) && (!$this->request->isPost())) {
      		$address_info = $this->modelAccountAddress->get_address($this->request->gethtml('address_id'),$this->customer->getId());
		}
	
    	if ($this->request->has('firstname', 'post')) {
      		$view->set('firstname', $this->request->sanitize('firstname', 'post'));
    	} else {
      		$view->set('firstname', @$address_info['firstname']);
    	}

    	if ($this->request->has('lastname', 'post')) {
      		$view->set('lastname', $this->request->sanitize('lastname', 'post'));
    	} else {
      		$view->set('lastname', @$address_info['lastname']);
    	}

    	if ($this->request->has('company', 'post')) {
      		$view->set('company', $this->request->sanitize('company', 'post'));
    	} else {
      		$view->set('company', @$address_info['company']);
    	}

    	if ($this->request->has('address_1', 'post')) {
      		$view->set('address_1', $this->request->sanitize('address_1', 'post'));
    	} else {
      		$view->set('address_1', @$address_info['address_1']);
    	}

    	if ($this->request->has('address_2', 'post')) {
      		$view->set('address_2', $this->request->sanitize('address_2', 'post'));
    	} else {
      		$view->set('address_2', @$address_info['address_2']);
    	}	

    	if ($this->request->has('postcode', 'post')) {
      		$view->set('postcode', $this->request->sanitize('postcode', 'post'));
    	} else {
      		$view->set('postcode', @$address_info['postcode']);
    	}

    	if ($this->request->has('city', 'post')) {
      		$view->set('city', $this->request->sanitize('city', 'post'));
    	} else {
      		$view->set('city', @$address_info['city']);
    	}

    	if ($this->request->has('country_id', 'post')) {
      		$view->set('country_id', $this->request->gethtml('country_id', 'post'));
    	}  elseif (isset($address_info['country_id'])) {
      		$view->set('country_id', $address_info['country_id']);
    	} else {
      		$view->set('country_id', $this->config->get('config_country_id'));
    	}

    	if ($this->request->has('zone_id', 'post')) {
      		$view->set('zone_id', $this->request->gethtml('zone_id', 'post'));
    	}  elseif (isset($address_info['zone_id'])) {
      		$view->set('zone_id', $address_info['zone_id']);
    	} else {
      		$view->set('zone_id', $this->config->get('config_zone_id'));
    	}
		
		$view->set('countries',$this->modelAccountAddress->get_countries());
		$view->set('zones', $this->modelAccountAddress->get_zones());

    	if ($this->request->has('default', 'post')) {
      		$view->set('default', $this->request->gethtml('default', 'post'));
    	} else {
      		$view->set('default', ($this->customer->getAddressId() == $this->request->gethtml('address_id')));
    	}

    	$view->set('back', $this->url->ssl('account_address'));

    	return $view->fetch('content/account_address.tpl');
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
	
  	function validateForm() {
		if (!$this->url->validate_referer()) {
			$this->session->set('message', $this->language->get('error_referer'));
			$this->response->redirect($this->url->ssl('account_address'));
		}
    	if ((strlen($this->request->sanitize('firstname', 'post')) < 2) || (strlen($this->request->sanitize('firstname', 'post')) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}
    	if ((strlen($this->request->sanitize('lastname', 'post')) < 2) || (strlen($this->request->sanitize('lastname', 'post')) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}
    	if ((strlen($this->request->sanitize('address_1', 'post')) < 3) || (strlen($this->request->sanitize('address_1', 'post')) > 64)) {
      		$this->error['address_1'] = $this->language->get('error_address_1');
    	}
    	if ((strlen($this->request->sanitize('city', 'post')) < 3) || (strlen($this->request->sanitize('city', 'post')) > 32)) {
      		$this->error['city'] = $this->language->get('error_city');
    	}
		if (!$this->validate->strlen($this->request->sanitize('postcode', 'post'),4,10)){
			$this->error['postcode'] = $this->language->get('error_postcode');
		}

    	if (!$this->error) {
      		return TRUE;
		} else {
      		return FALSE;
    	}
  	}

  	function validateDelete() {
		if (!$this->url->validate_referer()) {
			$this->error['message'] = $this->language->get('error_referere');
			$this->session->delete('account_validation');
		}
		$result = $this->modelAccountAddress->check_address_count($this->customer->getId());
    	if ($result['total'] == 1) {
      		$this->error['message'] = $this->language->get('error_delete');
    	}
    	if ($this->customer->getAddressId() == $this->request->gethtml('address_id')) {
      		$this->error['message'] = $this->language->get('error_default');
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

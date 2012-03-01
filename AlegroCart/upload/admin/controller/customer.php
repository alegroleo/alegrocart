<?php  //Customer AlegroCart
class ControllerCustomer extends Controller { 
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->config   	=& $locator->get('config');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session 		=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user'); 
		$this->validate 	=& $locator->get('validate');
		$this->modelCustomer = $model->get('model_admin_customer');
		
		$this->language->load('controller/customer.php');
	}
  	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}
  
  	function insert() {
    	$this->template->set('title', $this->language->get('heading_title'));
			
		if ($this->request->isPost() && $this->request->has('firstname', 'post') && $this->validateForm()) {
			$this->modelCustomer->insert_customer();
      		$customer_id = $this->modelCustomer->get_insert_id();
			$this->modelCustomer->insert_address($customer_id);
			$this->modelCustomer->insert_default_address($customer_id);
			
			$this->session->set('message', $this->language->get('text_message'));
		  
	  		$this->response->redirect($this->url->ssl('customer'));
		}
    	$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	} 
   
  	function update() {
    	$this->template->set('title', $this->language->get('heading_title'));
		
    	if ($this->request->isPost() && $this->request->has('firstname', 'post') && $this->validateForm()) {
      		$this->modelCustomer->update_customer();
			$this->modelCustomer->update_address();
			$this->session->set('message', $this->language->get('text_message'));
	  
	  		$this->response->redirect($this->url->ssl('customer'));
		}
    	$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}   

  	function delete() {
    	$this->template->set('title', $this->language->get('heading_title'));
			
    	if (($this->request->gethtml('customer_id')) && ($this->validateDelete())) {
			$this->modelCustomer->delete_customer();
			$this->session->set('message', $this->language->get('text_message'));

	  		$this->response->redirect($this->url->ssl('customer'));
    	}
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}  
    
  	function getList() {
    	$this->session->set('customer_validation', md5(time()));
    	$cols = array();
  
    	$cols[] = array(
      		'name'  => $this->language->get('column_lastname'),
      		'sort'  => 'lastname',
      		'align' => 'left'
    	);

    	$cols[] = array(
      		'name'  => $this->language->get('column_firstname'),
      		'sort'  => 'firstname',
      		'align' => 'left'
    	);

	$cols[] = array(
      		'name'  => $this->language->get('column_date_added'),
      		'sort'  => 'date_added',
      		'align' => 'left'
    	);

    	$cols[] = array(
      		'name'  => $this->language->get('column_status'),
      		'sort'  => 'status',
      		'align' => 'center'
    	);
    	
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'action'
    	);

		$results = $this->modelCustomer->get_page();

    	$rows = array();
    	foreach ($results as $result) {
      		$cell = array();
      		$cell[] = array(
        		'value' => $result['lastname'],
        		'align' => 'left'
      		);
      		$cell[] = array(
        		'value' => $result['firstname'],
        		'align' => 'left'
      		);
      		$cell[] = array(
        		'value' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_added'])),
        		'align' => 'left'
      		);
		$cell[] = array(
        		'icon'  => ($result['status'] ? 'enabled.png' : 'disabled.png'),
        		'align' => 'center'
      		);

			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('customer', 'update', array('customer_id' => $result['customer_id']))
      		);
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('customer', 'delete', array('customer_id' => $result['customer_id'],'customer_validation' =>$this->session->get('customer_validation')))
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

    	$view->set('text_results', $this->modelCustomer->get_text_results());

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
		
    	$view->set('action', $this->url->ssl('customer', 'page'));
		$view->set('action_delete', $this->url->ssl('customer', 'enableDelete'));
  
    	$view->set('search', $this->session->get('customer.search'));
    	$view->set('sort', $this->session->get('customer.sort'));
    	$view->set('order', $this->session->get('customer.order'));
    	$view->set('page', $this->session->get('customer.page'));
  
    	$view->set('cols', $cols);
    	$view->set('rows', $rows);
  
		$view->set('list', $this->url->ssl('customer'));
    	$view->set('insert', $this->url->ssl('customer', 'insert'));
  
    	$view->set('pages', $this->modelCustomer->get_pagination());

		return $view->fetch('content/list.tpl');
  	}
  
  	function getForm() {
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('heading_description', $this->language->get('heading_description'));
 
    	$view->set('text_enabled', $this->language->get('text_enabled'));
    	$view->set('text_disabled', $this->language->get('text_disabled'));
		$view->set('text_no_postal', $this->language->get('text_no_postal'));

    	$view->set('entry_firstname', $this->language->get('entry_firstname'));
    	$view->set('entry_lastname', $this->language->get('entry_lastname'));
		$view->set('entry_company', $this->language->get('entry_company'));
		$view->set('entry_address_1', $this->language->get('entry_address_1'));
    	$view->set('entry_address_2', $this->language->get('entry_address_2'));
    	$view->set('entry_postcode', $this->language->get('entry_postcode'));
    	$view->set('entry_city', $this->language->get('entry_city'));
    	$view->set('entry_country', $this->language->get('entry_country'));
    	$view->set('entry_zone', $this->language->get('entry_zone'));
    	$view->set('entry_email', $this->language->get('entry_email'));
    	$view->set('entry_telephone', $this->language->get('entry_telephone'));
    	$view->set('entry_fax', $this->language->get('entry_fax'));
    	$view->set('entry_password', $this->language->get('entry_password'));
    	$view->set('entry_confirm', $this->language->get('entry_confirm'));
		$view->set('entry_newsletter', $this->language->get('entry_newsletter'));
    	$view->set('entry_status', $this->language->get('entry_status'));
  
    	$view->set('button_list', $this->language->get('button_list'));
    	$view->set('button_insert', $this->language->get('button_insert'));
    	$view->set('button_update', $this->language->get('button_update'));
    	$view->set('button_delete', $this->language->get('button_delete'));
    	$view->set('button_save', $this->language->get('button_save'));
    	$view->set('button_cancel', $this->language->get('button_cancel'));
	$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_customer', $this->language->get('tab_customer'));
		$view->set('tab_address', $this->language->get('tab_address'));
	  
    	$view->set('error', @$this->error['message']);
    	$view->set('error_firstname', @$this->error['firstname']);
    	$view->set('error_lastname', @$this->error['lastname']);
		$view->set('error_address_1', @$this->error['address_1']);
    	$view->set('error_city', @$this->error['city']);
		$view->set('error_postcode', @$this->error['postcode']);
    	$view->set('error_email', @$this->error['email']);
    	$view->set('error_telephone', @$this->error['telephone']);
    	$view->set('error_password', @$this->error['password']);
    	$view->set('error_confirm', @$this->error['confirm']);
    
    	$view->set('action', $this->url->ssl('customer', $this->request->gethtml('action'), array('customer_id' => $this->request->gethtml('customer_id'))));
      
    	$view->set('list', $this->url->ssl('customer'));
    	$view->set('insert', $this->url->ssl('customer', 'insert'));
		$view->set('cancel', $this->url->ssl('customer'));
  
    	if ($this->request->gethtml('customer_id')) {
      		$view->set('update', 'enable');
	  		$view->set('delete', $this->url->ssl('customer', 'delete', array('customer_id' => $this->request->gethtml('customer_id'),'customer_validation' =>$this->session->get('customer_validation'))));
    	}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

    	if (($this->request->gethtml('customer_id')) && (!$this->request->isPost())) {
			$customer_info = $this->modelCustomer->get_customer();
      		$address_info = $this->modelCustomer->get_address(@$customer_info['address_id']);
    	}
		
		if ($this->request->has('address_id', 'post')) {
      		$view->set('address_id', $this->request->gethtml('address_id', 'post'));
    	} else {
      		$view->set('address_id', @$customer_info['address_id']);
    	}
		
    	if ($this->request->has('firstname', 'post')) {
      		$view->set('firstname', $this->request->gethtml('firstname', 'post'));
    	} else {
      		$view->set('firstname', @$customer_info['firstname']);
    	}

    	if ($this->request->has('lastname', 'post')) {
      		$view->set('lastname', $this->request->gethtml('lastname', 'post'));
    	} else {
      		$view->set('lastname', @$customer_info['lastname']);
    	}

    	if ($this->request->has('email', 'post')) {
      		$view->set('email', $this->request->gethtml('email', 'post'));
    	} else {
      		$view->set('email', @$customer_info['email']);
    	}

    	if ($this->request->has('telephone', 'post')) {
      		$view->set('telephone', $this->request->gethtml('telephone', 'post'));
    	} else {
      		$view->set('telephone', @$customer_info['telephone']);
    	}

    	if ($this->request->has('fax', 'post')) {
      		$view->set('fax', $this->request->gethtml('fax', 'post'));
    	} else {
      		$view->set('fax', @$customer_info['fax']);
    	}

    	if ($this->request->has('newsletter', 'post')) {
      		$view->set('newsletter', $this->request->gethtml('newsletter', 'post'));
    	} else {
      		$view->set('newsletter', @$customer_info['newsletter']);
    	}
		
    	if ($this->request->has('status', 'post')) {
      		$view->set('status', $this->request->gethtml('status', 'post'));
    	} else {
      		$view->set('status', @$customer_info['status']);
    	}
		
		if ($this->request->has('company', 'post')) { // New
      		$view->set('company', $this->request->gethtml('company', 'post'));
    	} else {
      		$view->set('company', @$address_info['company']);
    	}
		
		if ($this->request->has('address_1', 'post')) {  // New
      		$view->set('address_1', $this->request->gethtml('address_1', 'post'));
    	} else {
      		$view->set('address_1', @$address_info['address_1']);
    	}
		
		if ($this->request->has('address_2', 'post')) {// New
      		$view->set('address_2', $this->request->sanitize('address_2', 'post'));
    	} else {
      		$view->set('address_2', @$address_info['address_2']);
    	}	

		if ($this->request->has('postcode', 'post')) {// New
      		$view->set('postcode', $this->request->sanitize('postcode', 'post'));
    	} else {
      		$view->set('postcode', @$address_info['postcode']);
    	}

		if ($this->request->has('city', 'post')) {// New
      		$view->set('city', $this->request->sanitize('city', 'post'));
    	} else {
      		$view->set('city', @$address_info['city']);
    	}

		if ($this->request->has('country_id', 'post')) {// New
      		$view->set('country_id', $this->request->gethtml('country_id', 'post'));
    	}  elseif (isset($address_info['country_id'])) {
      		$view->set('country_id', $address_info['country_id']);
    	} else {
      		$view->set('country_id', $this->config->get('config_country_id'));
    	}
		
		if ($this->request->has('zone_id', 'post')) {// New
      		$view->set('zone_id', $this->request->gethtml('zone_id', 'post'));
    	}  elseif (isset($address_info['zone_id'])) {
      		$view->set('zone_id', $address_info['zone_id']);
    	} else {
      		$view->set('zone_id', $this->config->get('config_zone_id'));
    	}
		
		$view->set('countries',$this->modelCustomer->get_countries());
		$view->set('zones', $this->modelCustomer->get_zones());

    	$view->set('password', $this->request->gethtml('password', 'post'));
    	$view->set('confirm', $this->request->gethtml('confirm', 'post'));

		return $view->fetch('content/customer.tpl');
	}  
	 
  	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
    	if (!$this->user->hasPermission('modify', 'customer')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}

    	if (!$this->validate->strlen($this->request->gethtml('firstname', 'post'),1,32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if (!$this->validate->strlen($this->request->gethtml('lastname', 'post'),1,32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if ((!$this->validate->strlen($this->request->gethtml('email', 'post'), 1, 32)) || (!$this->validate->email($this->request->gethtml('email', 'post')))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
		
		if ((strlen($this->request->gethtml('address_1', 'post')) < 3) || (strlen($this->request->gethtml('address_1', 'post')) > 64)) {
      		$this->error['address_1'] = $this->language->get('error_address_1');
    	}
    	if ((strlen($this->request->gethtml('city', 'post')) < 3) || (strlen($this->request->gethtml('city', 'post')) > 32)) {
      		$this->error['city'] = $this->language->get('error_city');
    	}
		if (!$this->validate->strlen($this->request->gethtml('postcode', 'post'),4,10)){
			$this->error['postcode'] = $this->language->get('error_postcode');
		}

    	if (!$this->validate->strlen($this->request->gethtml('telephone', 'post'),1,32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}

    	if (($this->request->gethtml('password', 'post')) || ($this->request->gethtml('action') == 'insert')) {
      		if (!$this->validate->strlen($this->request->gethtml('password', 'post'),4,20)) {
        		$this->error['password'] = $this->language->get('error_password');
      		}
	
	  		if ($this->request->gethtml('password', 'post') != $this->request->gethtml('confirm', 'post')) {
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
			$this->response->redirect($this->url->ssl('customer'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('customer'));
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'customer')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}

  	function validateDelete() {
		if(($this->session->get('customer_validation') != $this->request->sanitize('customer_validation')) || (strlen($this->session->get('customer_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('customer_validation');
    	if (!$this->user->hasPermission('modify', 'customer')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}	
	  	 
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}  
  	}

	function zone() {
    	$output = '<select name="zone_id">';
		$results = $this->modelCustomer->return_zones($this->request->gethtml('country_id'));
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

  	function page() {
		if ($this->request->has('search', 'post')) {
	  		$this->session->set('customer.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
	  		$this->session->set('customer.page', $this->request->gethtml('page', 'post'));
		} 
		if ($this->request->has('sort', 'post')) {
			$this->session->set('customer.order', (($this->session->get('customer.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('customer.order') == 'asc') ? 'desc' : 'asc'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('customer.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('customer'));
  	} 	
}
?>
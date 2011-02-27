<?php //Coupon AlegroCart
class ControllerCoupon extends Controller {
	var $error = array();
    function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		//$this->cache    	=& $locator->get('cache');
		//$this->config   	=& $locator->get('config');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session 		=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user'); 
		$this->validate 	=& $locator->get('validate');
		$this->modelCoupon = $model->get('model_admin_coupon');
		
		$this->language->load('controller/coupon.php');
	}
  	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
    	
		$this->response->set($this->template->fetch('layout.tpl'));
  	}
  
  	function insert() {
    	$this->template->set('title', $this->language->get('heading_title'));
		
    	if ($this->request->isPost() && $this->request->has('code', 'post') && $this->validateForm()) {
			$this->modelCoupon->insert_coupon();
      		$insert_id = $this->modelCoupon->get_insert_id();
      		$this->modelCoupon->insert_description($insert_id);
			$this->modelCoupon->insert_product($insert_id);
			
			$this->session->set('message', $this->language->get('text_message'));

	  		$this->response->redirect($this->url->ssl('coupon'));
    	}
    
    	$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function update() {
    	$this->template->set('title', $this->language->get('heading_title'));
	
    	if ($this->request->isPost() && $this->request->has('code', 'post') && $this->validateForm()) {
			$this->modelCoupon->update_coupon();
			$this->modelCoupon->delete_description();
			$this->modelCoupon->insert_description((int)$this->request->gethtml('coupon_id'));
		  	$this->modelCoupon->delete_product();
      		$this->modelCoupon->insert_product((int)$this->request->gethtml('coupon_id'));

			$this->session->set('message', $this->language->get('text_message'));
	  
	  		$this->response->redirect($this->url->ssl('coupon'));
		}
    
    	$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function delete() {

    	$this->template->set('title', $this->language->get('heading_title'));
	
    	if (($this->request->gethtml('coupon_id')) && ($this->validateDelete())) { 
      		$this->modelCoupon->delete_coupon();
      		$this->modelCoupon->delete_description();
      		$this->modelCoupon->delete_product();
			$this->modelCoupon->delete_redeem();
			
			$this->session->set('message', $this->language->get('text_message'));
	  
	  		$this->response->redirect($this->url->ssl('coupon'));
    	}
    
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function getList() {
    	$this->session->set('coupon_validation', md5(time()));
    	$cols = array();

    	$cols[] = array(
      		'name'  => $this->language->get('column_name'),
      		'sort'  => 'cd.name',
      		'align' => 'left'
    	);
		
    	$cols[] = array(
      		'name'  => $this->language->get('column_code'),
      		'sort'  => 'c.code',
      		'align' => 'left'
    	);
    	
		$cols[] = array(
      		'name'  => $this->language->get('column_discount'),
      		'sort'  => 'c.discount',
      		'align' => 'right'
    	);
		
    	$cols[] = array(
      		'name'  => $this->language->get('column_prefix'),
      		'sort'  => 'c.prefix',
      		'align' => 'left'
    	);
		
		$cols[] = array(
      		'name'  => $this->language->get('column_date_start'),
      		'sort'  => 'c.date_start',
      		'align' => 'left'
    	);

		$cols[] = array(
      		'name'  => $this->language->get('column_date_end'),
      		'sort'  => 'c.date_end',
      		'align' => 'left'
    	);

		$cols[] = array(
      		'name'  => $this->language->get('column_status'),
      		'sort'  => 'c.status',
      		'align' => 'center'
    	);
						
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'right'
    	);

		$results = $this->modelCoupon->get_page();

    	$rows = array();
    	foreach ($results as $result) {
      		$cell = array();

      		$cell[] = array(
        		'value' => $result['name'],
        		'align' => 'left'
		  	);

      		$cell[] = array(
        		'value' => $result['code'],
        		'align' => 'left'
      		);
			
      		$cell[] = array(
        		'value' => $result['discount'],
        		'align' => 'right'
      		);			
			
      		$cell[] = array(
        		'value' => $result['prefix'],
        		'align' => 'left'
      		);			

      		$cell[] = array(
        		'value' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_start'])),
        		'align' => 'left'
      		);			

      		$cell[] = array(
        		'value' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_end'])),
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
				'href' => $this->url->ssl('coupon', 'update', array('coupon_id' => $result['coupon_id']))
      		);
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('coupon', 'delete', array('coupon_id' => $result['coupon_id'], 'coupon_validation' =>$this->session->get('coupon_validation')))
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

    	$view->set('text_results', $this->modelCoupon->get_text_results());

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
		  
    	$view->set('action', $this->url->ssl('coupon', 'page'));
		$view->set('action_delete', $this->url->ssl('coupon', 'enableDelete'));

    	$view->set('search', $this->session->get('coupon.search'));
    	$view->set('sort', $this->session->get('coupon.sort'));
    	$view->set('order', $this->session->get('coupon.order'));
    	$view->set('page', $this->session->get('coupon.page'));
 
    	$view->set('cols', $cols);
    	$view->set('rows', $rows);

    	$view->set('list', $this->url->ssl('coupon'));
    	$view->set('insert', $this->url->ssl('coupon', 'insert'));
  
    	$view->set('pages', $this->modelCoupon->get_pagination());

		return $view->fetch('content/list.tpl');
  	}

  	function getForm() {
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('heading_description', $this->language->get('heading_description'));

    	$view->set('text_enabled', $this->language->get('text_enabled'));
    	$view->set('text_disabled', $this->language->get('text_disabled'));
    	$view->set('text_yes', $this->language->get('text_yes'));
    	$view->set('text_no', $this->language->get('text_no'));
    	$view->set('text_percent', $this->language->get('text_percent'));
    	$view->set('text_minus', $this->language->get('text_minus'));
		  
    	$view->set('entry_name', $this->language->get('entry_name'));
    	$view->set('entry_description', $this->language->get('entry_description'));
    	$view->set('entry_code', $this->language->get('entry_code'));
		$view->set('entry_discount', $this->language->get('entry_discount'));
    	$view->set('entry_prefix', $this->language->get('entry_prefix'));
		$view->set('entry_minimum_order', $this->language->get('entry_minimum_order'));
    	$view->set('entry_date_start', $this->language->get('entry_date_start'));
    	$view->set('entry_date_end', $this->language->get('entry_date_end'));
    	$view->set('entry_shipping', $this->language->get('entry_shipping'));
    	$view->set('entry_uses_total', $this->language->get('entry_uses_total'));
		$view->set('entry_uses_customer', $this->language->get('entry_uses_customer'));
		$view->set('entry_product', $this->language->get('entry_product'));
		$view->set('entry_status', $this->language->get('entry_status'));

	$view->set('explanation_entry_product', $this->language->get('explanation_entry_product'));

    	$view->set('button_list', $this->language->get('button_list'));
    	$view->set('button_insert', $this->language->get('button_insert'));
    	$view->set('button_update', $this->language->get('button_update'));
    	$view->set('button_delete', $this->language->get('button_delete'));
    	$view->set('button_save', $this->language->get('button_save'));
    	$view->set('button_cancel', $this->language->get('button_cancel'));

    	$view->set('tab_general', $this->language->get('tab_general'));
    	$view->set('tab_data', $this->language->get('tab_data'));

    	$view->set('error', @$this->error['message']);
    	$view->set('error_name', @$this->error['name']);
    	$view->set('error_description', @$this->error['description']);
    	$view->set('error_code', @$this->error['code']);
    	$view->set('error_date_start', @$this->error['date_start']);
		$view->set('error_date_end', @$this->error['date_end']);
		$view->set('error_discount',$this->language->get('error_discount'));
		
    	$view->set('action', $this->url->ssl('coupon', $this->request->gethtml('action'), array('coupon_id' => $this->request->gethtml('coupon_id'))));
  
    	$view->set('list', $this->url->ssl('coupon'));
    	$view->set('insert', $this->url->ssl('coupon', 'insert'));
		$view->set('cancel', $this->url->ssl('coupon'));
  
    	if ($this->request->gethtml('coupon_id')) {
     		$view->set('update', 'enable');
      		$view->set('delete', $this->url->ssl('coupon', 'delete', array('coupon_id' => $this->request->gethtml('coupon_id'),'coupon_validation' =>$this->session->get('coupon_validation'))));
    	}
  
    	$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		

    	$coupon_data = array();
     	$results = $this->modelCoupon->get_languages();

    	foreach ($results as $result) {
			if (($this->request->gethtml('coupon_id')) && (!$this->request->isPost())) {
	  			$coupon_description_info = $this->modelCoupon->get_description($result['language_id']);
			} else {
				$coupon_description_info = $this->request->gethtml('language', 'post');
			}
			
			$coupon_data[] = array(
	    		'language_id' => $result['language_id'],
	    		'language'    => $result['name'],
	    		'name'        => (isset($coupon_description_info[$result['language_id']]) ? $coupon_description_info[$result['language_id']]['name'] : @$coupon_description_info['name']),
	    		'description' => (isset($coupon_description_info[$result['language_id']]) ? $coupon_description_info[$result['language_id']]['description'] : @$coupon_description_info['description'])
	  		);
    	}

    	$view->set('coupons', $coupon_data);

    	if (($this->request->gethtml('coupon_id')) && (!$this->request->isPost())) {
      		$coupon_info = $this->modelCoupon->get_coupon();
    	}

    	if ($this->request->has('code', 'post')) {
      		$view->set('code', $this->request->gethtml('code', 'post'));
    	} else {
      		$view->set('code', @$coupon_info['code']);
    	}

    	if ($this->request->has('discount', 'post')) {
      		$view->set('discount', $this->request->gethtml('discount', 'post'));
    	} else {
      		$view->set('discount', @$coupon_info['discount']);
    	}

    	if ($this->request->has('discount', 'post')) {
      		$view->set('discount', $this->request->gethtml('discount', 'post'));
    	} else {
      		$view->set('discount', @$coupon_info['discount']);
    	}
				
    	if ($this->request->has('prefix', 'post')) {
      		$view->set('prefix', $this->request->gethtml('prefix', 'post'));
    	} else {
      		$view->set('prefix', @$coupon_info['prefix']);
    	}
		
		if ($this->request->has('minimum_order', 'post')) {
      		$view->set('minimum_order', $this->request->gethtml('minimum_order', 'post'));
    	} else {
      		$view->set('minimum_order', @$coupon_info['minimum_order']);
    	}

    	if ($this->request->has('shipping', 'post')) {
      		$view->set('shipping', $this->request->gethtml('shipping', 'post'));
    	} else {
      		$view->set('shipping', @$coupon_info['shipping']);
    	}
		
    	$month_data = array();

    	$month_data[] = array(
      		'value' => '01',
      		'text'  => $this->language->get('text_january')
    	);

    	$month_data[] = array(
      		'value' => '02',
      		'text'  => $this->language->get('text_february')
    	);

    	$month_data[] = array(
      		'value' => '03',
      		'text'  => $this->language->get('text_march')
    	);

    	$month_data[] = array(
      		'value' => '04',
      		'text'  => $this->language->get('text_april')
    	);

    	$month_data[] = array(
      		'value' => '05',
      		'text'  => $this->language->get('text_may')
    	);

    	$month_data[] = array(
      		'value' => '06',
      		'text'  => $this->language->get('text_june')
    	);

    	$month_data[] = array(
      		'value' => '07',
      		'text'  => $this->language->get('text_july')
    	);

    	$month_data[] = array(
      		'value' => '08',
      		'text'  => $this->language->get('text_august')
    	);

    	$month_data[] = array(
      		'value' => '09',
      		'text'  => $this->language->get('text_september')
    	);

    	$month_data[] = array(
      		'value' => '10',
      		'text'  => $this->language->get('text_october')
    	);

    	$month_data[] = array(
      		'value' => '11',
      		'text'  => $this->language->get('text_november')
    	);

    	$month_data[] = array(
      		'value' => '12',
      		'text'  => $this->language->get('text_december')
    	);

    	$view->set('months', $month_data);
      	
		if (isset($coupon_info['date_start'])) {
        	$date = explode('/', date('d/m/Y', strtotime($coupon_info['date_start'])));
      	} else {
        	$date = explode('/', date('d/m/Y', time()));
      	}
			
    	if ($this->request->has('date_start_day', 'post')) {
      		$view->set('date_start_day', $this->request->gethtml('date_start_day', 'post'));
    	} else {
      		$view->set('date_start_day', $date[0]);
    	}			
			
    	if ($this->request->has('date_start_month', 'post')) {
      		$view->set('date_start_month', $this->request->gethtml('date_start_month', 'post'));
    	} else {
      		$view->set('date_start_month', $date[1]);
    	}

    	if ($this->request->has('date_start_year', 'post')) {
      		$view->set('date_start_year', $this->request->gethtml('date_start_year', 'post'));
    	} else {
      		$view->set('date_start_year', $date[2]);
    	}					

		if (isset($coupon_info['date_end'])) {
        	$date = explode('/', date('d/m/Y', strtotime($coupon_info['date_end'])));
      	} else {
        	$date = explode('/', date('d/m/Y', time()));
      	}
			
    	if ($this->request->has('date_end_day', 'post')) {
      		$view->set('date_end_day', $this->request->gethtml('date_end_day', 'post'));
    	} else {
      		$view->set('date_end_day', $date[0]);
    	}			
			
    	if ($this->request->has('date_end_month', 'post')) {
      		$view->set('date_end_month', $this->request->gethtml('date_end_month', 'post'));
    	} else {
      		$view->set('date_end_month', $date[1]);
    	}

    	if ($this->request->has('date_end_year', 'post')) {
      		$view->set('date_end_year', $this->request->gethtml('date_end_year', 'post'));
    	} else {
      		$view->set('date_end_year', $date[2]);
    	}

    	if ($this->request->has('uses_total', 'post')) {
      		$view->set('uses_total', $this->request->gethtml('uses_total', 'post'));
    	} else {
      		$view->set('uses_total', @$coupon_info['uses_total']);
    	}
  
    	if ($this->request->has('uses_customer', 'post')) {
      		$view->set('uses_customer', $this->request->gethtml('uses_customer', 'post'));
    	} else {
      		$view->set('uses_customer', @$coupon_info['uses_customer']);
    	}

    	$product_data = array();

    	$results = $this->modelCoupon->get_products();
    	foreach ($results as $result) {
			if (($this->request->gethtml('coupon_id')) && (!$this->request->isPost())) {  
	  			$coupon_product_info = $this->modelCoupon->get_coupon_product($result['product_id']);
			}

      		$product_data[] = array(
        		'product_id' => $result['product_id'],
        		'name'       => $result['name'],
        		'coupon_id'  => (isset($coupon_product_info) ? $coupon_product_info : in_array($result['product_id'], $this->request->gethtml('product', 'post', array())))
      		);
    	}

    	$view->set('products', $product_data);

    	if ($this->request->has('status', 'post')) {
      		$view->set('status', $this->request->gethtml('status', 'post'));
    	} else {
      		$view->set('status', @$coupon_info['status']);
    	}
		 
 		return $view->fetch('content/coupon.tpl');
  	}
	
  	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
    	if (!$this->user->hasPermission('modify', 'coupon')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}
	      
    	foreach ($this->request->gethtml('language', 'post') as $value) {
      		if (!$this->validate->strlen($value['name'],1,64)) {
        		$this->error['name'] = $this->language->get('error_name');
      		}
    	}
		
		foreach ($this->request->gethtml('language', 'post') as $value) {
      		if (!$this->validate->strlen($value['description'],1)) {
        		$this->error['description'] = $this->language->get('error_description');
      		}
    	}

    	if (!$this->validate->strlen($this->request->gethtml('code', 'post'),1,10)) {
      		$this->error['code'] = $this->language->get('error_code');
    	}

    	if (!checkdate($this->request->gethtml('date_start_month', 'post'), $this->request->gethtml('date_start_day', 'post'), $this->request->gethtml('date_start_year', 'post'))) {
	  		$this->error['date_start'] = $this->language->get('error_date_start');
		}

    	if (!checkdate($this->request->gethtml('date_end_month', 'post'), $this->request->gethtml('date_end_day', 'post'), $this->request->gethtml('date_end_year', 'post'))) {
	  		$this->error['date_end'] = $this->language->get('error_date_end');
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
			$this->response->redirect($this->url->ssl('coupon'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('coupon'));
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'coupon')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}

  	function validateDelete() {
		if(($this->session->get('coupon_validation') != $this->request->sanitize('coupon_validation')) || (strlen($this->session->get('coupon_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('coupon_validation');
    	if (!$this->user->hasPermission('modify', 'coupon')) {
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
	  		$this->session->set('coupon.search', $this->request->gethtml('search', 'post'));
		}
	 
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
	  		$this->session->set('coupon.page', $this->request->gethtml('page', 'post'));
		} 
	
		if ($this->request->has('sort', 'post')) {
	  		$this->session->set('coupon.order', (($this->session->get('coupon.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('coupon.order') == 'asc') ? 'desc' : 'asc'));
		}
		
		if ($this->request->has('sort', 'post')) {
			$this->session->set('coupon.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('coupon'));
  	} 	
}
?>

<?php //Order AlegroCart
class ControllerOrder extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->address  	=& $locator->get('address');
		$this->config   	=& $locator->get('config');
		$this->currency 	=& $locator->get('currency');
		$this->language 	=& $locator->get('language');
		$this->mail         =& $locator->get('mail');
		$this->module   	=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session 		=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user'); 
		$this->modelOrder = $model->get('model_admin_order');
		
		$this->language->load('controller/order.php');
	}
  	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));	
  	}

  	function update() {	
		$this->template->set('title', $this->language->get('heading_title'));

    	if ($this->request->isPost() && $this->request->has('order_status_id', 'post') && $this->validateForm()) {  
      		$this->modelOrder->update_order();
			$this->modelOrder->insert_order_history();
      		if (($this->config->get('config_email_send')) && ($this->request->gethtml('notify', 'post'))) {
        		$order_info = $this->modelOrder->get_order_info();
	    		$order_id = $order_info['reference'];
				$invoice  = $this->url->create(HTTP_CATALOG, 'account_invoice', FALSE, array('order_id' => $this->request->gethtml('order_id')));
	    		$date     = $this->language->formatDate($this->language->get('date_format_long'),strtotime($order_info['date_added']));
	    		$status   = $order_info['status'];
	    		$comment  = $this->request->gethtml('comment', 'post');
		
	    		$this->mail->setTo($order_info['email']);
				$this->mail->setFrom($this->config->get('config_email'));
	    		$this->mail->setSender($this->config->get('config_store'));
	    		$this->mail->setSubject($this->language->get('email_subject', $order_id));
	    		$this->mail->setText($this->language->get('email_message', $order_id, $invoice, $date, $status, $comment));
	    		$this->mail->send();
      		}
			
			$this->session->set('message', $this->language->get('text_message'));
	  		
			$this->response->redirect($this->url->ssl('order'));
    	}
    	$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}
  
  	function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

    	if (($this->request->gethtml('order_id')) && ($this->validateDelete())) {
			$this->modelOrder->delete_order();
			$this->session->set('message', $this->language->get('text_message'));
	  		
			$this->response->redirect($this->url->ssl('order'));
    	}
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}
      
  	function getList() {	
		$this->session->set('order_validation', md5(time()));
    	$cols = array();
    	$cols[] = array(
      		'name'  => $this->language->get('column_order_id'),
      		'sort'  => 'o.order_id',
      		'align' => 'left'
    	);
    	$cols[] = array(
      		'name'  => $this->language->get('column_reference'),
      		'sort'  => 'o.reference',
      		'align' => 'left'
    	);
    	$cols[] = array(
      		'name'  => $this->language->get('column_name'),
      		'sort'  => 'o.firstname',
      		'align' => 'left'
    	);
    	$cols[] = array(
      		'name'  => $this->language->get('column_status'),
      		'sort'  => 'os.name',
      		'align' => 'left'
    	);
    	$cols[] = array(
      		'name'  => $this->language->get('column_date_added'),
      		'sort'  => 'o.date_added',
      		'align' => 'left'
    	);
    	$cols[] = array(
      		'name'  => $this->language->get('column_total'),
      		'sort'  => 'o.total',
      		'align' => 'right'
    	);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'right'
    	);
		
		$results = $this->modelOrder->get_page();
    	$rows = array();
    	foreach ($results as $result) {
      		$cell = array();
      		$cell[] = array(
        		'value' => $result['order_id'],
        		'align' => 'left'
      		);
      		$cell[] = array(
        		'value' => $result['reference'],
        		'align' => 'left'
      		);			
      		$cell[] = array(
        		'value' => $result['firstname'] . ' ' . $result['lastname'],
        		'align' => 'left'
      		);
      		$cell[] = array(
        		'value' => $result['status'],
        		'align' => 'left'
      		);
      		$cell[] = array(
        		'value' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_added'])),
        		'align' => 'left'
      		);
      		$cell[] = array(
        		'value' => $this->currency->format($result['total'], $result['currency'], $result['value']),
        		'align' => 'right'
      		);
			
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('order', 'update', array('order_id' => $result['order_id']))
      		);
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('order', 'delete', array('order_id' => $result['order_id'],'order_validation' => $this->session->get('order_validation')))
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

   		$view->set('text_results', $this->modelOrder->get_text_results());

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
			    
    	$view->set('action', $this->url->ssl('order', 'page'));
		$view->set('action_delete', $this->url->ssl('order', 'enableDelete'));
 
    	$view->set('search', $this->session->get('order.search'));
    	$view->set('sort', $this->session->get('order.sort'));
    	$view->set('order', $this->session->get('order.order'));
    	$view->set('page', $this->session->get('order.page'));

    	$view->set('cols', $cols);
    	$view->set('rows', $rows);

    	$view->set('list', $this->url->ssl('order'));

    	$view->set('pages', $this->modelOrder->get_pagination());

		return $view->fetch('content/list.tpl');
  	}
  
  	function getForm() {
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title') . ' #' . $this->request->gethtml('order_id'));
    	$view->set('heading_description', $this->language->get('heading_description'));

    	$view->set('text_order', $this->language->get('text_order'));
		$view->set('text_email', $this->language->get('text_email'));
		$view->set('text_telephone', $this->language->get('text_telephone'));
		$view->set('text_fax', $this->language->get('text_fax'));
		$view->set('text_shipping_address', $this->language->get('text_shipping_address'));
    	$view->set('text_shipping_method', $this->language->get('text_shipping_method'));
    	$view->set('text_payment_address', $this->language->get('text_payment_address'));
    	$view->set('text_payment_method', $this->language->get('text_payment_method'));
    	$view->set('text_order_history', $this->language->get('text_order_history'));
    	$view->set('text_order_download', $this->language->get('text_order_download'));
    	$view->set('text_order_update', $this->language->get('text_order_update'));
    	$view->set('text_product', $this->language->get('text_product'));
    	$view->set('text_model_number', $this->language->get('text_model_number'));
    	$view->set('text_quantity', $this->language->get('text_quantity'));
    	$view->set('text_price', $this->language->get('text_price'));
    	$view->set('text_total', $this->language->get('text_total'));

    	$view->set('column_date_added', $this->language->get('column_date_added'));
    	$view->set('column_status', $this->language->get('column_status'));
    	$view->set('column_download', $this->language->get('column_download'));
    	$view->set('column_filename', $this->language->get('column_filename'));
    	$view->set('column_remaining', $this->language->get('column_remaining'));
    	$view->set('column_notify', $this->language->get('column_notify'));
    	$view->set('column_comment', $this->language->get('column_comment'));

    	$view->set('entry_status', $this->language->get('entry_status'));
    	$view->set('entry_comment', $this->language->get('entry_comment'));
    	$view->set('entry_notify', $this->language->get('entry_notify'));

    	$view->set('button_list', $this->language->get('button_list'));
    	$view->set('button_insert', $this->language->get('button_insert'));
    	$view->set('button_update', $this->language->get('button_update'));
    	$view->set('button_delete', $this->language->get('button_delete'));
    	$view->set('button_save', $this->language->get('button_save'));
    	$view->set('button_cancel', $this->language->get('button_cancel'));

    	$view->set('tab_general', $this->language->get('tab_general'));
	
		$view->set('error', @$this->error['message']);

    	$view->set('action', $this->url->ssl('order', 'update', array('order_id' => $this->request->gethtml('order_id'))));
      
    	$view->set('list', $this->url->ssl('order'));
		$view->set('cancel', $this->url->ssl('order'));
   
    	if ($this->request->gethtml('order_id')) {	    
      		$view->set('update', 'update');
	  		$view->set('delete', $this->url->ssl('order', 'delete', array('order_id' => $this->request->gethtml('order_id'),'order_validation' => $this->session->get('order_validation'))));
    	}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$order_info = $this->modelOrder->get_order();
		$view->set('reference', $order_info['reference']);
		$view->set('email', $order_info['email']);
		$view->set('telephone', $order_info['telephone']);
		$view->set('fax', $order_info['fax']);

    	$shipping_address = array(
      		'firstname' => $order_info['shipping_firstname'],
      		'lastname'  => $order_info['shipping_lastname'],
      		'company'   => $order_info['shipping_company'],
      		'address_1' => $order_info['shipping_address_1'],
      		'address_2' => $order_info['shipping_address_2'],
      		'city'      => $order_info['shipping_city'],
      		'postcode'  => $order_info['shipping_postcode'],
      		'zone'      => $order_info['shipping_zone'],
      		'country'   => $order_info['shipping_country']
    	);
    	$view->set('shipping_address', $this->address->format($shipping_address, $order_info['shipping_address_format'], '<br />'));
    	$view->set('shipping_method', $order_info['shipping_method']);

    	$payment_address = array(
      		'firstname' => $order_info['payment_firstname'],
      		'lastname'  => $order_info['payment_lastname'],
      		'company'   => $order_info['payment_company'],
      		'address_1' => $order_info['payment_address_1'],
      		'address_2' => $order_info['payment_address_2'],
      		'city'      => $order_info['payment_city'],
      		'postcode'  => $order_info['payment_postcode'],
      		'zone'      => $order_info['payment_zone'],
      		'country'   => $order_info['payment_country']
    	);
    	$view->set('payment_address', nl2br($this->address->format($payment_address, $order_info['payment_address_format'])));
    	$view->set('payment_method', $order_info['payment_method']);

    	$products = $this->modelOrder->get_products();
    	$product_data = array();
    	foreach ($products as $product) {
      		$options = $this->modelOrder->get_options($product['order_product_id']);
      		$option_data = array();
      		foreach ($options as $option) {
        		$option_data[] = array(
          			'name'  => $option['name'],
          			'value' => $option['value'],
        		);
      		}
      	  
			if ($this->config->get('config_tax')) {
				$product_data[] = array(
					'name'         => $product['name'],
					'model_number' => $product['model_number'],
					'option'       => $option_data,
					'quantity'     => $product['quantity'],
					'price'        => $this->currency->format(($product['price'] + ($product['price'] * $product['tax'] / 100)), $order_info['currency'], $order_info['value']),
					'discount'     => (ceil($product['discount']) ? $this->currency->format(($product['price'] - $product['discount']) + (($product['price'] - $product['discount']) * $product['tax'] / 100), $order_info['currency'], $order_info['value']) : NULL),
					'total'        => $this->currency->format($product['total'] + ($product['total'] * $product['tax'] / 100), $order_info['currency'], $order_info['value'])
				);
			}
			else {
				$product_data[] = array(
					'name'         => $product['name'],
					'model_number' => $product['model_number'],
					'option'       => $option_data,
					'quantity'     => $product['quantity'],
					'price'        => $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
					'discount'     => (ceil($product['discount']) ? $this->currency->format(($product['price'] - $product['discount']) , $order_info['currency'], $order_info['value']) : NULL),
					'total'        => $this->currency->format($product['total'], $order_info['currency'], $order_info['value'])
				);
			}
    	}
    	$view->set('products', $product_data);
    	$view->set('totals', $this->modelOrder->get_totals());

    	$history_data = array();
    	$results = $this->modelOrder->get_history();
    	foreach ($results as $result) {
      		$history_data[] = array(
        		'date_added' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_added'])),
        		'status'     => $result['status'],
        		'comment'    => $result['comment'],
        		'notify'     => $result['notify']
      		);
    	}
    	$view->set('historys', $history_data);
  
    	$download_data = array();
    	$results = $this->modelOrder->get_downloads();
    	foreach ($results as $result) {
      		$download_data[] = array(
        		'name'      => $result['name'],
        		'filename'  => $result['filename'],
        		'remaining' => $result['remaining']
      		);
    	}
    	$view->set('downloads', $download_data);
    	$view->set('order_status_id', $order_info['order_status_id']);
    	$view->set('order_statuses', $this->modelOrder->get_order_statuses());

		return $view->fetch('content/order.tpl');
  	}
  	
	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
    	if (!$this->user->hasPermission('modify', 'order')) {
      		$this->error['message'] = $this->language->get('error_permission'); 
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
			$this->response->redirect($this->url->ssl('order'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('order'));
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'order')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}
	
	function validateDelete() {
		if(($this->session->get('order_validation') != $this->request->sanitize('order_validation')) || (strlen($this->session->get('order_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('order_validation');
		if (!$this->user->hasPermission('modify', 'order')) {
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
	  		$this->session->set('order.search', $this->request->gethtml('search', 'post'));
		}
	
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
	  		$this->session->set('order.page', $this->request->gethtml('page', 'post'));
		} 
	
		if ($this->request->has('sort', 'post')) {
	  		$this->session->set('order.order', (($this->session->get('order.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('order.order') == 'asc') ? 'desc' : 'asc'));
		}
		
		if ($this->request->has('sort', 'post')) {
			$this->session->set('order.sort', $this->request->gethtml('sort', 'post'));
		}
				
		$this->response->redirect($this->url->ssl('order'));
  	}     
}
?>
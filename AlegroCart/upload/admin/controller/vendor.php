<?php   //Admin Vendor AlegroCart
class ControllerVendor extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->cache		=& $locator->get('cache');
		$this->config		=& $locator->get('config');
		$this->currency		=& $locator->get('currency');
		$this->generate_seo	=& $locator->get('generateseo');
		$this->image		=& $locator->get('image');   
		$this->language		=& $locator->get('language');
		$this->mail_check	=& $locator->get('mail_check_mx');
		$this->module		=& $locator->get('module');
		$this->request		=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session 		=& $locator->get('session');
		$this->template		=& $locator->get('template');
		$this->url		=& $locator->get('url');
		$this->user		=& $locator->get('user'); 
		$this->validate		=& $locator->get('validate');
		$this->modelVendor	= $model->get('model_admin_vendor');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController = $this->template->set_controller('vendor');

		$this->language->load('controller/vendor.php');
	}

	function index(){
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function insert(){
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('name', 'post') && $this->validateForm()) {
			$this->modelVendor->insert_vendor();
			$vendor_id = $this->modelVendor->get_last_id();
			$this->modelVendor->insert_address($vendor_id);
			$this->modelVendor->insert_default_address($vendor_id);
			foreach ($this->request->gethtml('productdata', 'post', array()) as $product_id) {
				$this->modelVendor->write_product($product_id, $vendor_id);
			}

			$name_last = $this->request->get('name', 'post');
			if (strlen($name_last) > 26) {
				$name_last = substr($name_last , 0, 23) . '...';
			}
			$this->session->set('name_last_vendor', $name_last);
			$this->session->set('last_vendor', $this->url->ssl('vendor', 'update', array('vendor_id' => $vendor_id)));
			$this->session->set('last_vendor_id', $vendor_id);

			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('vendor'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());

		$this->session->delete('name_last_vendor');
		$this->session->delete('last_vendor');

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function update(){
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('name', 'post') && $this->validateForm()) {

			$this->modelVendor->update_vendor();
			$this->modelVendor->update_address();
			$this->modelVendor->delete_vendorToProduct();
			foreach ($this->request->gethtml('productdata', 'post', array()) as $product_id) {
				$this->modelVendor->update_product($product_id);
			}

			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('vendor', 'update', array('vendor_id' => $this->request->gethtml('vendor_id', 'post'))));
			} else {
				$this->response->redirect($this->url->ssl('vendor'));
			}

		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	function delete(){
		$this->template->set('title', $this->language->get('heading_title'));
		if (($this->request->gethtml('vendor_id')) && ($this->validateDelete())) {
			$this->modelVendor->delete_vendor();
			$this->session->set('message', $this->language->get('text_message'));
			$this->session->delete('name_last_vendor');
			$this->session->delete('last_vendor');
			$this->response->redirect($this->url->ssl('vendor'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	function changeStatus() { 
		if (($this->request->has('stat_id')) && ($this->request->has('stat')) && $this->validateChangeStatus()) {
			$this->modelVendor->change_vendor_status($this->request->gethtml('stat'), $this->request->gethtml('stat_id'));
		}
	}
	function getList(){
		$this->session->set('vendor_validation', md5(time()));

		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_name'),
			'sort'  => 'v.name',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_image'),
			'sort'  => 'i.filename',
			'align' => 'right'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_status'),
			'sort'  => 'v.status',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_action'),
			'align' => 'action'
		);

		$results = $this->modelVendor->get_page();
		$rows = array();
		foreach ($results as $result) {
			$cell = array();
			$last = $result['vendor_id'] == $this->session->get('last_vendor_id') ? 'last_visited': '';
			$cell[] = array(
				'value' => $result['name'],
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'image'		=> $this->image->resize($result['filename'], '26', '26'),
				'previewimage'	=> $this->image->resize($result['filename'], $this->config->get('config_image_width'), $this->config->get('config_image_height')),
				'title'		=> $result['filename'],
				'align'		=> 'right'
			);
			if ($this->validateChangeStatus()) {
			$cell[] = array(
				'status'		=> $result['status'],
				'text'			=> $this->language->get('button_status'),
				'align'			=> 'center',
				'status_id'		=> $result['vendor_id'],
				'status_controller'	=> 'vendor'
				);

			} else {

			$cell[] = array(
				'icon'  => ($result['status'] ? 'enabled.png' : 'disabled.png'),
				'align' => 'center'
			);
			}

			$action = array();
			$action[] = array(
				'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('vendor', 'update', array('vendor_id' => $result['vendor_id']))
			);

			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('vendor', 'delete', array('vendor_id' => $result['vendor_id'],'vendor_validation' =>$this->session->get('vendor_validation')))
				);
			}

			$cell[] = array(
				'action'=> $action,
				'align'	=> 'action'
			);
			$rows[] = array('cell' => $cell);
		}

		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def); 
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));
		$view->set('text_results', $this->modelVendor->get_text_results());

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));

		$view->set('help', $this->session->get('help'));
		$view->set('controller', 'vendor');
		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');

		$view->set('action', $this->url->ssl('vendor', 'page'));
		$view->set('action_delete', $this->url->ssl('vendor', 'enableDelete'));

		$view->set('search', $this->session->get('vendor.search'));
		$view->set('sort', $this->session->get('vendor.sort'));
		$view->set('order', $this->session->get('vendor.order'));
		$view->set('page', $this->session->get('vendor.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('insert', $this->url->ssl('vendor', 'insert'));

		$view->set('pages', $this->modelVendor->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	function getForm(){
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def); 
		$view->set('heading_title', $this->language->get('heading_form_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));

		$view->set('entry_name', $this->language->get('entry_name'));
		$view->set('entry_image', $this->language->get('entry_image'));
		$view->set('entry_product', $this->language->get('entry_product'));
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
		$view->set('entry_website', $this->language->get('entry_website'));
		$view->set('entry_trade', $this->language->get('entry_trade'));
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_description', $this->language->get('entry_description'));
		$view->set('entry_discount', $this->language->get('entry_discount'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));

		$view->set('help', $this->session->get('help'));
		$view->set('tab_general', $this->language->get('tab_general'));
		$view->set('tab_product', $this->language->get('tab_product'));
		$view->set('tab_vendor', $this->language->get('tab_vendor'));
		$view->set('tab_address', $this->language->get('tab_address'));

		$view->set('explanation_multiselect', $this->language->get('explanation_multiselect'));
		$view->set('explanation_description', $this->language->get('explanation_description'));
		$view->set('explanation_discount', $this->language->get('explanation_discount'));
		$view->set('text_no_postal', $this->language->get('text_no_postal'));

		$view->set('error', @$this->error['message']);
		$view->set('error_name', @$this->error['name']);
		$view->set('error_email', @$this->error['email']);

	if(!@$this->error['message']){
	$view->set('error', @$this->error['warning']);
	}

	$view->set('action', $this->url->ssl('vendor', $this->request->gethtml('action'), array('vendor_id' => $this->request->gethtml('vendor_id'))));

	$view->set('insert', $this->url->ssl('vendor', 'insert'));
	$view->set('cancel', $this->url->ssl('vendor'));

	if ($this->request->gethtml('vendor_id')) {
		$view->set('update', $this->url->ssl('vendor', 'update', array('vendor_id' => $this->request->gethtml('vendor_id'))));
		$view->set('delete', $this->url->ssl('vendor', 'delete', array('vendor_id' => $this->request->gethtml('vendor_id'),'vendor_validation' =>$this->session->get('vendor_validation'))));
	}

		$view->set('tab', $this->session->has('vendor_tab') && $this->session->get('vendor_id') == $this->request->gethtml('vendor_id') ? $this->session->get('vendor_tab') : 0);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('vendor_id', $this->request->gethtml('vendor_id'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

	if (($this->request->gethtml('vendor_id')) && (!$this->request->isPost())) {
		$vendor_info = $this->modelVendor->get_vendor();
		$address_info = $this->modelVendor->get_address(@$vendor_info['address_id']);
	}

		if ($this->request->has('name', 'post')) {
			if ($this->request->gethtml('name', 'post') != NULL) {
				$name_last = $this->request->has('name', 'post');
			} else {
				$name_last = $this->session->get('name_last_vendor');
			}
		} else {
			$name_last = @$vendor_info['name'];
		}
		if (strlen($name_last) > 26) {
			$name_last = substr($name_last , 0, 23) . '...';
		}
		$this->session->set('name_last_vendor', $name_last);
		$this->session->set('last_vendor', $this->url->ssl('vendor', 'update', array('vendor_id' => $this->request->gethtml('vendor_id'))));
		$this->session->set('last_vendor_id', $this->request->gethtml('vendor_id'));

	if ($this->request->has('name', 'post')) {
		$view->set('name', $this->request->gethtml('name', 'post'));
	} else {
		$view->set('name', @$vendor_info['name']);
	}

	$image_data = array();
	$results = $this->modelVendor->get_images();
	foreach ($results as $result) {
		$image_data[] = array(
			'image_id'	=> $result['image_id'],
			'previewimage'	=> $this->image->resize($result['filename'], $this->config->get('config_image_width'), $this->config->get('config_image_height')),
			'title'		=> $result['title']
		);
	}
	$view->set('images', $image_data);

	if ($this->request->has('image_id', 'post')) {
		$view->set('image_id', $this->request->gethtml('image_id', 'post'));
	} else {
		$view->set('image_id', @$vendor_info['image_id']);
	}

	$product_data = array();
	$results = $this->modelVendor->get_products();
	foreach ($results as $result) {
			if (($this->request->gethtml('vendor_id')) && (!$this->request->isPost())) {
				$product_info = $this->modelVendor->get_vendorToProduct($result['product_id']);
			}
			$product_data[] = array(
				'product_id'	=> $result['product_id'],
				'previewimage'	=> $this->image->resize($result['filename'], $this->config->get('config_image_width'), $this->config->get('config_image_height')),
				'name'		=> $result['name'],
			'productdata'		=> (isset($product_info) ? $product_info : in_array($result['product_id'], $this->request->gethtml('productdata', 'post', array()))));
	}
	$view->set('productdata', $product_data);

	if ($this->request->has('description', 'post')) {
		$view->set('description', $this->request->gethtml('description', 'post'));
	} else {
		$view->set('description', @$vendor_info['description']);
	}

	if ($this->request->has('discount', 'post')) {
		$view->set('discount', $this->request->gethtml('discount', 'post'));
	} else {
		$view->set('discount', @$vendor_info['discount']);
	}

	if ($this->request->has('website', 'post')) {
		$view->set('website', $this->request->gethtml('website', 'post'));
	} else {
		$view->set('website', @$vendor_info['website']);
	}

	if ($this->request->has('trade', 'post')) {
		$view->set('trade', $this->request->gethtml('trade', 'post'));
	} else {
		$view->set('trade', @$vendor_info['trade']);
	}

	if ($this->request->has('address_id', 'post')) {
		$view->set('address_id', $this->request->gethtml('address_id', 'post'));
	} else {
		$view->set('address_id', @$vendor_info['address_id']);
	}

	if ($this->request->has('firstname', 'post')) {
		$view->set('firstname', $this->request->gethtml('firstname', 'post'));
	} else {
		$view->set('firstname', @$address_info['firstname']);
	}

	if ($this->request->has('lastname', 'post')) {
		$view->set('lastname', $this->request->gethtml('lastname', 'post'));
	} else {
		$view->set('lastname', @$address_info['lastname']);
	}

	if ($this->request->has('email', 'post')) {
		$view->set('email', $this->request->gethtml('email', 'post'));
	} else {
		$view->set('email', @$vendor_info['email']);
	}

	if ($this->request->has('telephone', 'post')) {
		$view->set('telephone', $this->request->gethtml('telephone', 'post'));
	} else {
		$view->set('telephone', @$vendor_info['telephone']);
	}

	if ($this->request->has('fax', 'post')) {
		$view->set('fax', $this->request->gethtml('fax', 'post'));
	} else {
		$view->set('fax', @$vendor_info['fax']);
	}

	if ($this->request->has('status', 'post')) {
		$view->set('status', $this->request->gethtml('status', 'post'));
	} else {
		$view->set('status', @$vendor_info['status']);
	}

	if ($this->request->has('company', 'post')) {
		$view->set('company', $this->request->gethtml('company', 'post'));
	} else {
		$view->set('company', @$address_info['company']);
	}

		if ($this->request->has('address_1', 'post')) {
		$view->set('address_1', $this->request->gethtml('address_1', 'post'));
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
	} elseif (isset($address_info['country_id'])) {
		$view->set('country_id', $address_info['country_id']);
	} else {
		$view->set('country_id', $this->config->get('config_country_id'));
	}

	if ($this->request->has('zone_id', 'post')) {
		$view->set('zone_id', $this->request->gethtml('zone_id', 'post'));
	} elseif (isset($address_info['zone_id'])) {
		$view->set('zone_id', $address_info['zone_id']);
	} else {
		$view->set('zone_id', $this->config->get('config_zone_id'));
	}

	$view->set('countries',$this->modelVendor->get_countries());
	$view->set('zones', $this->modelVendor->get_zones());

	return $view->fetch('content/vendor.tpl');
	}

	function validateForm(){
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');

		if (!$this->user->hasPermission('modify', 'vendor')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->validate->strlen($this->request->gethtml('name', 'post'),1,64)) {
		$this->error['name'] = $this->language->get('error_name');
		}

		if (((!$this->validate->strlen($this->request->sanitize('email', 'post'), 6, 96)) || (!$this->validate->email($this->request->sanitize('email', 'post'))) || $this->mail_check->final_mail_check($this->request->sanitize('email', 'post')) == FALSE) && $this->request->sanitize('email', 'post')) {
		$this->error['email'] = $this->language->get('error_email');
		}

		if (@$this->error && !@$this->error['message']){
			$this->error['warning'] = $this->language->get('error_warning');
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
			$this->response->redirect($this->url->ssl('vendor'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('vendor'));
		}
	}

	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'vendor')) {
		$this->error['message'] = $this->language->get('error_permission');  
	}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function validateDelete(){
		if(($this->session->get('vendor_validation') != $this->request->sanitize('vendor_validation')) || (strlen($this->session->get('vendor_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('vendor_validation');
		if (!$this->user->hasPermission('modify', 'vendor')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		$product_info = $this->modelVendor->check_products();
		if ($product_info['total']) {
			$this->error['message'] = $product_info['total'] == 1 ? $this->language->get('error_product') : $this->language->get('error_products', $product_info['total']) ;
			$product_list = $this-> modelVendor->get_vendorToProducts();
				$this->error['message'] .= '<br>';
				foreach ($product_list as $product) {
					$this->error['message'] .= '<a href="' . $this->url->ssl('product', 'update', array('product_id' => $product['product_id'])) . '">' . $product['name'] . '</a>&nbsp;';
				}
		}
		if (!$this->error){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function validateChangeStatus(){
		if (!$this->user->hasPermission('modify', 'vendor')) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function zone(){
		$output = '<select name="zone_id">';
		$results = $this->modelVendor->return_zones($this->request->gethtml('country_id'));
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
	function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}
	function page(){
		if ($this->request->has('search', 'post')) {
			$this->session->set('vendor.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('vendor.page', $this->request->gethtml('page', 'post'));
		} 
		if ($this->request->has('sort', 'post')) {
			$this->session->set('vendor.order', (($this->session->get('vendor.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('vendor.order') == 'asc') ? 'desc' : 'asc'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('vendor.sort', $this->request->gethtml('sort', 'post'));
		}
		$this->response->redirect($this->url->ssl('vendor'));
	}
	function tab() {
		if ($this->request->isPost()) {
			if ($this->request->has('activeTab', 'post')) {
				$this->session->set('vendor_tab', $this->request->sanitize('activeTab', 'post'));
				$this->session->set('vendor_id', $this->request->sanitize('id', 'post'));
			}
		}
	}
}
?>

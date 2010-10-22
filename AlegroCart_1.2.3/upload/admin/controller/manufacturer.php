<?php   //Admin Manufacturer AlegroCart 
class ControllerManufacturer extends Controller { 
	var $error = array();
 	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->cache    	=& $locator->get('cache');
		$this->config   	=& $locator->get('config');
		$this->currency 	=& $locator->get('currency');
		$this->generate_seo =& $locator->get('generateseo');
		$this->image    	=& $locator->get('image');   
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session 		=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user'); 
		$this->validate 	=& $locator->get('validate');
		$this->modelManufacturer = $model->get('model_admin_manufacturer');
		
		$this->language->load('controller/manufacturer.php');
	} 
  	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}
  
  	function insert() {
    	$this->template->set('title', $this->language->get('heading_title'));
			
		if ($this->request->isPost() && $this->request->has('name', 'post') && $this->validateForm()) {
			$url_alias = $this->config->get('config_url_alias');
			$url_seo = $this->config->get('config_seo');
      		$this->modelManufacturer->insert_manufacturer();
			if($url_alias && $url_seo){
				$manufacturer_id = $this->modelManufacturer->get_last_id();
				$this->manufacturer_seo($manufacturer_id,$this->request->gethtml('name', 'post'));
				$this->cache->delete('url');
			}
			$this->cache->delete('manufacturer');
			$this->session->set('message', $this->language->get('text_message'));
			
	  		$this->response->redirect($this->url->ssl('manufacturer'));
		}
    	$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	} 

  	function update() {
    	$this->template->set('title', $this->language->get('heading_title'));
		
    	if ($this->request->isPost() && $this->request->has('name', 'post') && $this->validateForm()) {
			$url_alias = $this->config->get('config_url_alias');
			$url_seo = $this->config->get('config_seo');
			$this->modelManufacturer->update_manufacturer();
			if($url_alias && $url_seo){
				$this->delete_manufacturer_seo($this->request->gethtml('manufacturer_id'));
				$this->manufacturer_seo($this->request->gethtml('manufacturer_id'),$this->request->gethtml('name', 'post'));
				$this->cache->delete('url');
			}
			$this->cache->delete('manufacturer');
			$this->session->set('message', $this->language->get('text_message'));

	  		$this->response->redirect($this->url->ssl('manufacturer'));
		}
    	$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}   

  	function delete() {
    	$this->template->set('title', $this->language->get('heading_title'));
			
    	if (($this->request->gethtml('manufacturer_id')) && ($this->validateDelete())) {
			$url_alias = $this->config->get('config_url_alias');
			$url_seo = $this->config->get('config_seo');
      		$this->modelManufacturer->delete_manufacturer();
			if($url_alias && $url_seo){
				$this->delete_manufacturer_seo($this->request->gethtml('manufacturer_id'));
				$this->cache->delete('url');
			}
			$this->cache->delete('manufacturer');
			$this->session->set('message', $this->language->get('text_message'));
			
	  		$this->response->redirect($this->url->ssl('manufacturer'));
    	}
    
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}  
    
  	function getList() {
		$this->session->set('manufacturer_validation', md5(time()));
    	$cols = array();
    	$cols[] = array(
      		'name'  => $this->language->get('column_name'),
      		'sort'  => 'm.name',
      		'align' => 'left'
    	);
		$cols[] = array(
             'name'  => $this->language->get('column_image'),
             'sort'  => 'i.filename',
             'align' => 'right'
       );
    	$cols[] = array(
      		'name'  => $this->language->get('column_sort_order'),
      		'sort'  => 'm.sort_order',
      		'align' => 'right'
    	);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'right'
    	);
		
		$results = $this->modelManufacturer->get_page();
    	$rows = array();
    	foreach ($results as $result) {
      		$cell = array();
      		$cell[] = array(
        		'value' => $result['name'],
        		'align' => 'left'
      		);
			$cell[] = array(
               'image' => $this->image->resize($result['filename'], '22', '22'),
               'align' => 'right'
            );
      		$cell[] = array(
        		'value' => $result['sort_order'],
        		'align' => 'right'
      		);
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('manufacturer', 'update', array('manufacturer_id' => $result['manufacturer_id']))
      		);
			
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('manufacturer', 'delete', array('manufacturer_id' => $result['manufacturer_id'],'manufacturer_validation' =>$this->session->get('manufacturer_validation')))
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
    	$view->set('text_results', $this->modelManufacturer->get_text_results());

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
		
    	$view->set('action', $this->url->ssl('manufacturer', 'page'));
		$view->set('action_delete', $this->url->ssl('manufacturer', 'enableDelete'));
  
    	$view->set('search', $this->session->get('manufacturer.search'));
    	$view->set('sort', $this->session->get('manufacturer.sort'));
    	$view->set('order', $this->session->get('manufacturer.order'));
    	$view->set('page', $this->session->get('manufacturer.page'));
  
    	$view->set('cols', $cols);
    	$view->set('rows', $rows);
  
		$view->set('list', $this->url->ssl('manufacturer'));
    	$view->set('insert', $this->url->ssl('manufacturer', 'insert'));
  
    	$view->set('pages', $this->modelManufacturer->get_pagination());

		return $view->fetch('content/list.tpl');
  	}
  
  	function getForm() {
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('heading_description', $this->language->get('heading_description'));

    	$view->set('text_enabled', $this->language->get('text_enabled'));
    	$view->set('text_disabled', $this->language->get('text_disabled'));

    	$view->set('entry_name', $this->language->get('entry_name'));
    	$view->set('entry_image', $this->language->get('entry_image'));
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));
  
    	$view->set('button_list', $this->language->get('button_list'));
    	$view->set('button_insert', $this->language->get('button_insert'));
    	$view->set('button_update', $this->language->get('button_update'));
    	$view->set('button_delete', $this->language->get('button_delete'));
    	$view->set('button_save', $this->language->get('button_save'));
    	$view->set('button_cancel', $this->language->get('button_cancel'));
	
		$view->set('tab_general', $this->language->get('tab_general'));
	  
    	$view->set('error', @$this->error['message']);
    	$view->set('error_name', @$this->error['name']);
    
    	$view->set('action', $this->url->ssl('manufacturer', $this->request->gethtml('action'), array('manufacturer_id' => $this->request->gethtml('manufacturer_id'))));
      
    	$view->set('list', $this->url->ssl('manufacturer'));
 
    	$view->set('insert', $this->url->ssl('manufacturer', 'insert'));
		$view->set('cancel', $this->url->ssl('manufacturer'));
  
    	if ($this->request->gethtml('manufacturer_id')) {
      		$view->set('update', $this->url->ssl('manufacturer', 'update', array('manufacturer_id' => $this->request->gethtml('manufacturer_id'))));
	  		$view->set('delete', $this->url->ssl('manufacturer', 'delete', array('manufacturer_id' => $this->request->gethtml('manufacturer_id'),'manufacturer_validation' =>$this->session->get('manufacturer_validation'))));
    	}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
    	

    	if (($this->request->gethtml('manufacturer_id')) && (!$this->request->isPost())) {
      		$manufacturer_info = $this->modelManufacturer->get_manufacturer();
    	}

    	if ($this->request->has('name', 'post')) {
      		$view->set('name', $this->request->gethtml('name', 'post'));
    	} else {
      		$view->set('name', @$manufacturer_info['name']);
    	}

    	$image_data = array();
    	$results = $this->modelManufacturer->get_images();
    	foreach ($results as $result) {
      		$image_data[] = array(
        		'image_id'        => $result['image_id'],
        		'title'           => $result['title']
      		);
    	}
    	$view->set('images', $image_data);

    	if ($this->request->has('image_id', 'post')) {
      		$view->set('image_id', $this->request->gethtml('image_id', 'post'));
    	} else {
      		$view->set('image_id', @$manufacturer_info['image_id']);
    	}
						
    	if ($this->request->has('sort_order', 'post')) {
      		$view->set('sort_order', $this->request->gethtml('sort_order', 'post'));
    	} else {
      		$view->set('sort_order', @$manufacturer_info['sort_order']);
    	}

		return $view->fetch('content/manufacturer.tpl');
	}  
	 
  	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
    	if (!$this->user->hasPermission('modify', 'manufacturer')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}
        if (!$this->validate->strlen($this->request->gethtml('name', 'post'),1,64)) {
      		$this->error['name'] = $this->language->get('error_name');
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
			$this->response->redirect($this->url->ssl('manufacturer'));//**
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('manufacturer'));//**
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'manufacturer')) {//**
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}

  	function validateDelete() {
		if(($this->session->get('manufacturer_validation') != $this->request->sanitize('manufacturer_validation')) || (strlen($this->session->get('manufacturer_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('manufacturer_validation');
    	if (!$this->user->hasPermission('modify', 'manufacturer')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}	
  		$product_info = $this->modelManufacturer->check_products();
		if ($product_info['total']) {
	  		$this->error['message'] = $this->language->get('error_product', $product_info['total']);	
		}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}

  	function page() {
		if ($this->request->has('search', 'post')) {
	  		$this->session->set('manufacturer.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
	  		$this->session->set('manufacturer.page', $this->request->gethtml('page', 'post'));
		} 
		if ($this->request->has('sort', 'post')) {
			$this->session->set('manufacturer.order', (($this->session->get('manufacturer.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('manufacturer.order') == 'asc') ? 'desc' : 'asc'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('manufacturer.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('manufacturer'));
  	}
	function manufacturer_seo($manufacturer_id,$manufacturer_name){
		$query_path = 'controller=manufacturer&manufacturer_id=' . $manufacturer_id;
		$alias = '';
		$alias .= $this->generate_seo->clean_alias($manufacturer_name);
		$alias .= '.html';
		$this->generate_seo->_insert_url_alias($query_path, $alias);
	}
	function delete_manufacturer_seo($manufacturer_id){
		$query_path = 'controller=manufacturer&manufacturer_id=' . $manufacturer_id;
		$this->modelManufacturer->delete_SEO($query_path);
	}
}
?>
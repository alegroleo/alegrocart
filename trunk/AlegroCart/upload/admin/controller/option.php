<?php  //Admin Option AlegroCart
class ControllerOption extends Controller { 
  	var $error = array();
 	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->cache    	=& $locator->get('cache');
		$this->config   	=& $locator->get('config');
		$this->currency 	=& $locator->get('currency');
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
		$this->modelOptions = $model->get('model_admin_options');
		
		$this->language->load('controller/option.php');
	}  
  	function index() {
    	$this->template->set('title', $this->language->get('heading_title'));
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}
              
  	function insert() {
    	$this->template->set('title', $this->language->get('heading_title'));
		if ($this->request->isPost() && $this->request->has('language', 'post') && $this->validateForm()) {
      		foreach ($this->request->gethtml('language', 'post') as $key => $value) {
        		$this->modelOptions->insert_option(@$insert_id, $key, $value['name']);
				$insert_id = $this->modelOptions->get_last_id();
      		}
			$this->session->set('message', $this->language->get('text_message'));
	  		$this->response->redirect($this->url->ssl('option'));
		}
    	$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	} 

  	function update() {
    	$this->template->set('title', $this->language->get('heading_title'));
    	if ($this->request->isPost() && $this->request->has('language', 'post') && $this->validateForm()) {
			$this->modelOptions->delete_option();
      		foreach ($this->request->gethtml('language', 'post') as $key => $value) {
				$this->modelOptions->insert_option($this->request->gethtml('option_id'), $key, $value['name']);
      		}
			$this->session->set('message', $this->language->get('text_message'));
	  		$this->response->redirect($this->url->ssl('option'));
    	}
    	$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function delete() {
	
    	$this->template->set('title', $this->language->get('heading_title')); 
	
    	if (($this->request->gethtml('option_id')) && ($this->validateDelete())) {
			$this->modelOptions->delete_option();
			$this->modelOptions->delete_option_value();
			$this->modelOptions->delete_product_to_option();
			$this->session->set('message', $this->language->get('text_message'));
      		$this->response->redirect($this->url->ssl('option'));
    	}
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
		$this->response->set($this->template->fetch('layout.tpl'));
  	}
     
  	function getList() {
		$this->session->set('option_validation', md5(time()));
    	$cols = array();
	$cols[] = array(
		'name'  => $this->language->get('column_values'),
		'folder_help' => $this->language->get('text_folder_help'),
		'align' => 'option'
		);
	$cols[] = array(
      		'name'  => $this->language->get('column_name'),
      		'sort'  => 'name',
      		'align' => 'left'
    	);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'action'
    	);
 
		$results = $this->modelOptions->get_page_option();
    	$rows = array();

    	foreach ($results as $result) {
     		$cell = array();
      		$cell[] = array(
        		'icon'  => $this->modelOptions->check_children($result['option_id']) ? 'folderO.png' : 'folder.png',
        		'align' => 'option',
				'path'  => $this->url->ssl('option_value', FALSE, array('option_id' => $result['option_id']))
		  	);
      		$cell[] = array(
        		'value' => $result['name'],
        		'align' => 'left'
      		);
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('option', 'update', array('option_id' => $result['option_id']))
      		);
			
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('option', 'delete', array('option_id' => $result['option_id'],'option_validation' =>$this->session->get('option_validation')))
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

    	$view->set('text_results', $this->modelOptions->get_text_results());

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

	$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

    	$view->set('error', @$this->error['message']);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		  
    	$view->set('action', $this->url->ssl('option', 'page'));
		$view->set('action_delete', $this->url->ssl('option', 'enableDelete'));

    	$view->set('search', $this->session->get('option.search'));
    	$view->set('sort', $this->session->get('option.sort'));
    	$view->set('order', $this->session->get('option.order'));
    	$view->set('page', $this->session->get('option.page'));
  
    	$view->set('cols', $cols);
    	$view->set('rows', $rows);
  
    	$view->set('list', $this->url->ssl('option'));
    	$view->set('insert', $this->url->ssl('option', 'insert'));

    	$view->set('pages', $this->modelOptions->get_pagination());

		return $view->fetch('content/list.tpl');
  	}
  
  	function getForm() {
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('heading_description', $this->language->get('heading_description'));

    	$view->set('entry_name', $this->language->get('entry_name'));

		$view->set('button_list', $this->language->get('button_list'));
    	$view->set('button_insert', $this->language->get('button_insert'));
    	$view->set('button_update', $this->language->get('button_update'));
    	$view->set('button_delete', $this->language->get('button_delete'));
    	$view->set('button_save', $this->language->get('button_save'));
    	$view->set('button_cancel', $this->language->get('button_cancel'));
	$view->set('button_print', $this->language->get('button_print'));

    	$view->set('tab_general', $this->language->get('tab_general'));
    
		$view->set('error', @$this->error['message']);
    	$view->set('error_name', @$this->error['name']);
	
    	$view->set('action', $this->url->ssl('option', $this->request->gethtml('action'), array('option_id' => $this->request->gethtml('option_id'))));
      
    	$view->set('list', $this->url->ssl('option'));
    	$view->set('insert', $this->url->ssl('option', 'insert'));
		$view->set('cancel', $this->url->ssl('option'));
  
    	if ($this->request->gethtml('option_id')) {  
      		$view->set('update', 'enable');
	  		$view->set('delete', $this->url->ssl('option', 'delete', array('option_id' => $this->request->gethtml('option_id'),'option_validation' =>$this->session->get('option_validation'))));
    	}
    	$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
    	$option_data = array();
    	$results = $this->modelOptions->get_languages();
    	foreach ($results as $result) {
	  		if (($this->request->gethtml('option_id')) && (!$this->request->isPost())) {
	    		$option_description_info = $this->modelOptions->get_option_description($result['language_id'] );
	  		} else {
				$option_description_info = $this->request->gethtml('language', 'post');
			}
	  		$option_data[] = array(
	    		'language_id' => $result['language_id'],
	    		'language'    => $result['name'],
	    		'name'        => (isset($option_description_info[$result['language_id']]) ? $option_description_info[$result['language_id']]['name'] : @$option_description_info['name']),
	  		);
    	}
    	$view->set('options', $option_data);

 		return $view->fetch('content/option.tpl');
  	}
  
  	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
    	if (!$this->user->hasPermission('modify', 'option')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}
    	foreach ($this->request->gethtml('language', 'post') as $value) {
      		if (!$this->validate->strlen($value['name'],1,32)) {
        		$this->error['name'] = $this->language->get('error_name');
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
			$this->response->redirect($this->url->ssl('option'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('option'));
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'option')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}
  
  	function validateDelete() {
		if(($this->session->get('option_validation') != $this->request->sanitize('option_validation')) || (strlen($this->session->get('option_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('option_validation');
    	if (!$this->user->hasPermission('modify', 'option')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}	
  		
		$product_info = $this->modelOptions->check_product_to_option();
		if ($product_info['total']) {
			$this->error['message'] = $product_info['total'] == 1 ? $this->language->get('error_product') : $this->language->get('error_products', $product_info['total']) ;
			$product_list = $this-> modelOptions->get_optionToProducts();
				$this->error['message'] .= '<br>';
				foreach ($product_list as $product) {
					$this->error['message'] .= '<a href="' . $this->url->ssl('product_option', '', array('product_id' => $product['product_id'])) . '">' . $product['name'] . '</a>&nbsp;';
				}
		}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		} 
  	} 
     
  	function page() {
		if ($this->request->has('search', 'post')) {
	  		$this->session->set('option.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
	  		$this->session->set('option.page', $this->request->gethtml('page', 'post'));
		} 
		if ($this->request->has('sort', 'post')) {
	  		$this->session->set('option.order', (($this->session->get('option.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('option.order') == 'asc') ? 'desc' : 'asc'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('option.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('option'));
  	}
}
?>

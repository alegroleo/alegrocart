<?php // Admin Download AlegroCart
class ControllerDownload extends Controller {  
	var $error = array();
  	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request 	 	=& $locator->get('request');
		$this->response	 	=& $locator->get('response');
		$this->session 	 	=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->upload   	=& $locator->get('upload');
		$this->url     		=& $locator->get('url');
		$this->user    	 	=& $locator->get('user');
		$this->validate 	=& $locator->get('validate');
		$this->modelDownload = $model->get('model_admin_download');
		$this->language->load('controller/download.php');
	}
  	function index() {
    	$this->template->set('title', $this->language->get('heading_title'));
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}
  	        
  	function insert() {
    	$this->template->set('title', $this->language->get('heading_title'));
			
		if ($this->request->isPost() && $this->upload->has('download') && $this->validateForm()) {
			$this->modelDownload->insert_download();
      		$insert_id = $this->modelDownload->get_insert_id();
      		foreach ($this->request->gethtml('language', 'post') as $key => $value) {
				$this->modelDownload->insert_description($insert_id, $key, $value['name']);
      		}     
			$this->session->set('message', $this->language->get('text_message'));
	  		$this->response->redirect($this->url->ssl('download'));
		}
    	$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function update() {
    	$this->template->set('title', $this->language->get('heading_title'));
			
    	if ($this->request->isPost() && $this->request->has('download_id') && $this->validateForm()) {
        	$this->modelDownload->update_download();
			$this->modelDownload->delete_description();
      		foreach ($this->request->gethtml('language', 'post') as $key => $value) {
				$this->modelDownload->insert_description((int)$this->request->gethtml('download_id'), $key, $value['name']);
      		}
			$this->session->set('message', $this->language->get('text_message'));
	  		$this->response->redirect($this->url->ssl('download'));
    	}
    	$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function delete() {
    	$this->template->set('title', $this->language->get('heading_title'));
			
    	if (($this->request->gethtml('download_id')) && ($this->validateDelete())) {
			$this->modelDownload->delete_download();
	  		$this->modelDownload->delete_description();
			$this->session->set('message', $this->language->get('text_message'));
      		$this->response->redirect($this->url->ssl('download'));
    	}
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}
    
  	function getList() {
		$this->session->set('download_validation', md5(time()));
    	$cols = array();
    	$cols[] = array(
      		'name'  => $this->language->get('column_name'),
      		'sort'  => 'dd.name',
      		'align' => 'left'
    	);
    	$cols[] = array(
      		'name'  => $this->language->get('column_filename'),
      		'sort'  => 'd.filename',
      		'align' => 'left'
    	);
    	$cols[] = array(
      		'name'  => $this->language->get('column_mask'),
      		'sort'  => 'd.mask',
      		'align' => 'left'
    	);
    	$cols[] = array(
      		'name'  => $this->language->get('column_remaining'),
      		'sort'  => 'd.remaining',
      		'align' => 'right'
    	);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'action'
    	);  
		$results = $this->modelDownload->get_page();

    	$rows = array();
    	foreach ($results as $result) {
      		$cell = array();
      		$cell[] = array(
        		'value' => $result['name'],
        		'align' => 'left'
      		);
     		$cell[] = array(
        		'value' => $result['filename'],
        		'align' => 'left'
      		);
     		$cell[] = array(
        		'value' => $result['mask'],
        		'align' => 'left'
      		);
      		$cell[] = array(
        		'value' => $result['remaining'],
       			'align' => 'right'
      		);
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('download', 'update', array('download_id' => $result['download_id']))
      		);
			
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('download', 'delete', array('download_id' => $result['download_id'],'download_validation' =>$this->session->get('download_validation')))
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

    	$view->set('text_results',$this->modelDownload->get_text_results());

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
		   
    	$view->set('action', $this->url->ssl('download', 'page'));
		$view->set('action_delete', $this->url->ssl('download', 'enableDelete'));

    	$view->set('search', $this->session->get('download.search'));
    	$view->set('sort', $this->session->get('download.sort'));
    	$view->set('order', $this->session->get('download.order'));
    	$view->set('page', $this->session->get('download.page'));
    	$view->set('cols', $cols);
    	$view->set('rows', $rows);

    	$view->set('list', $this->url->ssl('download'));
    	$view->set('insert', $this->url->ssl('download', 'insert'));

    	$view->set('pages', $this->modelDownload->get_pagination());

		return $view->fetch('content/list.tpl');
  	}
  
  	function getForm() {
    	$view = $this->locator->create('template');
  
    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('heading_description', $this->language->get('heading_description'));
   
    	$view->set('entry_name', $this->language->get('entry_name'));
    	$view->set('entry_filename', $this->language->get('entry_filename'));
		$view->set('entry_mask', $this->language->get('entry_mask'));
    	$view->set('entry_remaining', $this->language->get('entry_remaining'));
  	$view->set('text_browse', $this->language->get('text_browse'));

    	$view->set('button_list', $this->language->get('button_list'));
    	$view->set('button_insert', $this->language->get('button_insert'));
    	$view->set('button_update', $this->language->get('button_update'));
    	$view->set('button_delete', $this->language->get('button_delete'));
    	$view->set('button_save', $this->language->get('button_save'));
    	$view->set('button_cancel', $this->language->get('button_cancel'));
	$view->set('button_print', $this->language->get('button_print'));

    	$view->set('tab_general', $this->language->get('tab_general'));
    	$view->set('tab_data', $this->language->get('tab_data'));
        
	$view->set('explanation_name', $this->language->get('explanation_name'));
	$view->set('explanation_mask', $this->language->get('explanation_mask'));
	$view->set('explanation_remaining', $this->language->get('explanation_remaining'));

		$view->set('error', @$this->error['message']);
    	$view->set('error_name', @$this->error['name']);
    	$view->set('error_file', @$this->error['file']);
		$view->set('error_mask', @$this->error['mask']);
	  
    	$view->set('action', $this->url->ssl('download', $this->request->gethtml('action'), array('download_id' => $this->request->gethtml('download_id'))));

    	$view->set('list', $this->url->ssl('download'));
    	$view->set('insert', $this->url->ssl('download', 'insert'));
		$view->set('cancel', $this->url->ssl('download'));
  
    	if ($this->request->gethtml('download_id')) {	  
      		$view->set('update', 'enable');
	  		$view->set('delete', $this->url->ssl('download', 'delete', array('download_id' => $this->request->gethtml('download_id'),'download_validation' =>$this->session->get('download_validation'))));
    	}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$download_data = array();
		$results = $this->modelDownload->get_languages();
    	foreach ($results as $result) {
		 	if (($this->request->gethtml('download_id')) && (!$this->request->isPost())) {
	    		$download_description_info = $this->modelDownload->get_descriptions($this->request->gethtml('download_id'), $result['language_id']);
	  		} else {
				$download_description_info = $this->request->gethtml('language', 'post');
			}
			$name = $this->request->gethtml('name', 'post');
	  		$download_data[] = array(
	    		'language_id' => $result['language_id'],
	    		'language'    => $result['name'],
	    		'name'        => (isset($download_description_info[$result['language_id']]) ? $download_description_info[$result['language_id']]['name'] : @$download_description_info['name']),
	  		);
    	}

    	$view->set('downloads', $download_data);

    	if (($this->request->gethtml('download_id')) && (!$this->request->isPost())) {
			$download_info = $this->modelDownload->getRow_download_info();
    	}
  
    	if ($this->request->has('mask', 'post')) {
      		$view->set('mask', $this->request->gethtml('mask', 'post'));
    	} else {
      		$view->set('mask', @$download_info['mask']);
    	}

		$view->set('filename', @$download_info['filename']);
		
		if ($this->request->has('remaining', 'post')) {
      		$view->set('remaining', $this->request->gethtml('remaining', 'post'));
    	} elseif (@$download_info['remaining']) {
      		$view->set('remaining', $download_info['remaining']);
    	} else {
      		$view->set('remaining', 1);
    	}
 
 		return $view->fetch('content/download.tpl');
  	}

  	function validateForm() { 
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
    	if (!$this->user->hasPermission('modify', 'download')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}
    	if ($this->upload->has('download')) {
	  		if (!$this->upload->save('download', DIR_DOWNLOAD . $this->upload->getName('download'))) {
	    		$this->error['file'] = $this->language->get('error_upload');
	  		}
            if (!$this->validate->strlen($this->upload->getName('download'),1,128)) {
        		$this->error['file'] = $this->language->get('error_filename');
	  		}
			if (substr(strrchr($this->upload->getName('filename'), '.'), 1) == 'php') {
          		$this->error['file'] = $this->language->get('error_filetype');
        	}
			if ($this->upload->hasError('download')) {
				$this->error['message'] = $this->upload->getError('download');
			}
		}
    	foreach ($this->request->gethtml('language', 'post') as $value) {
      		if (!$this->validate->strlen($value['name'],1,64)) {
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
			$this->response->redirect($this->url->ssl('download'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('download'));
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'download')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}

  	function validateDelete() {
		if(($this->session->get('download_validation') != $this->request->sanitize('download_validation')) || (strlen($this->session->get('download_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('download_validation');
    	if (!$this->user->hasPermission('modify', 'download')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}	
		$product_info = $this->modelDownload->check_products();
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
			$this->session->set('download.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('download.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('download.order', (($this->session->get('download.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('download.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('download.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('download'));
	}	    
}
?>
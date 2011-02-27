<?php //Admin ImageDisplay AlegroCart
class ControllerImageDisplay extends Controller {
	var $error = array();
	var $types=array('swf','FXG','as','mxml','flv');
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->cache    	=& $locator->get('cache');
		$this->config   	=& $locator->get('config');
		$this->image   		=& $locator->get('image');
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
		$this->modelImageDisplay = $model->get('model_admin_image_display');
		$this->language->load('controller/image_display.php');
		$this->language->load('controller/layout_locations.php');
	}
  	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
    	
		$this->response->set($this->template->fetch('layout.tpl'));
  	}	
	function insert() {
    	$this->template->set('title', $this->language->get('heading_title'));

    	if (($this->request->isPost()) && ($this->validateForm())) {
			$this->modelImageDisplay->insert_image_display();
			$this->modelImageDisplay->insert_description();
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('image_display'));
		}
		
    	$this->template->set('content', $this->getForm());	
		$this->template->set($this->module->fetch());
		
    	$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function update() {
    	$this->template->set('title', $this->language->get('heading_title'));
    	if (($this->request->isPost()) && ($this->validateForm())) {
			$this->modelImageDisplay->update_image_display();
			$this->modelImageDisplay->delete_description($this->request->gethtml('image_display_id'));
			$this->modelImageDisplay->update_description();
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('image_display'));
		}
    	$this->template->set('content', $this->getForm());	
		$this->template->set($this->module->fetch());
    	$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function delete() {
    	$this->template->set('title', $this->language->get('heading_title'));
    	if (($this->request->gethtml('image_display_id')) && ($this->validateDelete())) {
			$this->modelImageDisplay->delete_image_display($this->request->gethtml('image_display_id'));
			$this->modelImageDisplay->delete_description($this->request->gethtml('image_display_id'));
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('image_display'));
		}
    	$this->template->set('content', $this->getList());	
		$this->template->set($this->module->fetch());
    	$this->response->set($this->template->fetch('layout.tpl'));
	}

	function getList() {
		$this->session->set('image_display_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_name'),
      		'sort'  => 'id.name',
      		'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_location'),
      		'sort'  => 'id.location_id',
      		'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_sort_order'),
      		'sort'  => 'id.sort_order',
      		'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_status'),
			'align' => 'right'
		);
		$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'right'
    	);
		
		$results = $this->modelImageDisplay->get_page();
		
		$rows = array();
    	foreach ($results as $result) {
			$cell = array();
      		$cell[] = array(
        		'value' => $result['name'],
        		'align' => 'left'
		  	);
			$cell[] = array(
        		'value' => $this->language->get('text_location_' .$result['location']),
        		'align' => 'left'
		  	);
			$cell[] = array(
        		'value' => $result['sort_order'],
        		'align' => 'center'
		  	);
			$cell[] = array(
        		'icon'  => ($result['status'] ? 'enabled.png' : 'disabled.png'),
        		'align' => 'right'
      		);
			
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('image_display', 'update', array('image_display_id' => $result['image_display_id']))
      		);
			
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('image_display', 'delete', array('image_display_id' => $result['image_display_id'],'image_display_validation' =>$this->session->get('image_display_validation')))
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
		$view->set('text_results', $this->modelImageDisplay->get_text_results());
		
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
		
		$view->set('action', $this->url->ssl('image_display', 'page'));
		$view->set('action_delete', $this->url->ssl('image_display', 'enableDelete'));
		
		$view->set('search', $this->session->get('imagedisplay.search'));
		$view->set('sort', $this->session->get('imagedisplay.sort'));
    	$view->set('order', $this->session->get('imagedisplay.order'));
    	$view->set('page', $this->session->get('imagedisplay.page'));
    	$view->set('cols', $cols);
    	$view->set('rows', $rows);
		
		$view->set('list', $this->url->ssl('image_display'));
    	$view->set('insert', $this->url->ssl('image_display', 'insert'));
		
		$view->set('pages', $this->modelImageDisplay->get_pagination());
		
		return $view->fetch('content/list.tpl');
	}
	
	function getForm() {
		$view = $this->locator->create('template');
		
		$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('heading_description', $this->language->get('heading_description'));
		
		$view->set('text_enabled', $this->language->get('text_enabled'));
    	$view->set('text_disabled', $this->language->get('text_disabled'));
		$view->set('text_noflash', $this->language->get('text_noflash'));
		$view->set('text_noimage', $this->language->get('text_noimage'));
		$view->set('text_browse', $this->language->get('text_browse'));

		$view->set('entry_name', $this->language->get('entry_name'));
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));
		$view->set('entry_location', $this->language->get('entry_location'));
		
		$view->set('entry_image', $this->language->get('entry_image'));
		$view->set('entry_image_width', $this->language->get('entry_image_width'));
		$view->set('entry_image_height', $this->language->get('entry_image_height'));
		$view->set('entry_flash', $this->language->get('entry_flash'));
		$view->set('entry_flash_width', $this->language->get('entry_flash_width'));
		$view->set('entry_flash_height', $this->language->get('entry_flash_height'));
		$view->set('entry_flash_loop', $this->language->get('entry_flash_loop'));
		$view->set('entry_filename', $this->language->get('entry_filename'));
		
		$view->set('explanation_entry_name', $this->language->get('explanation_entry_name'));
		$view->set('explanation_entry_status', $this->language->get('explanation_entry_status'));
		$view->set('explanation_entry_sort_order', $this->language->get('explanation_entry_sort_order'));
		$view->set('explanation_entry_location', $this->language->get('explanation_entry_location'));
		
		$view->set('explanation_entry_image_width', $this->language->get('explanation_entry_image_width'));
		$view->set('explanation_entry_image_height', $this->language->get('explanation_entry_image_height'));
		$view->set('explanation_entry_flash_width', $this->language->get('explanation_entry_flash_width'));
		$view->set('explanation_entry_flash_height', $this->language->get('explanation_entry_flash_height'));
		$view->set('explanation_entry_flash_loop', $this->language->get('explanation_entry_flash_loop'));
		$view->set('explanation_entry_filename', $this->language->get('explanation_entry_filename'));

		$view->set('button_upload', $this->language->get('button_upload'));
    	$view->set('button_list', $this->language->get('button_list'));
    	$view->set('button_insert', $this->language->get('button_insert'));
    	$view->set('button_update', $this->language->get('button_update'));
    	$view->set('button_delete', $this->language->get('button_delete'));
    	$view->set('button_save', $this->language->get('button_save'));
    	$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_add', $this->language->get('button_add'));
		$view->set('button_remove', $this->language->get('button_remove'));
		
		$view->set('tab_name', $this->language->get('tab_name'));
		$view->set('tab_description', $this->language->get('tab_description'));
		
		$view->set('error_name', @$this->error['name']);	
    	$view->set('error', @$this->error['message']);
		$view->set('error_file', "");
		
		$view->set('action', $this->url->ssl('image_display', $this->request->gethtml('action'), array('image_display_id' => (int)$this->request->gethtml('image_display_id'))));
    	$view->set('list', $this->url->ssl('image_display'));
    	$view->set('insert', $this->url->ssl('image_display', 'insert'));
    	$view->set('cancel', $this->url->ssl('image_display'));
		$view->set('action_flash', $this->url->ssl('image_display', 'flash_upload',array('image_display_id' => (int)$this->request->gethtml('image_display_id'))));
		
		if ($this->request->gethtml('image_display_id')) {
     		$view->set('update', 'enabled');
      		$view->set('delete', $this->url->ssl('image_display', 'delete', array('image_display_id' => (int)$this->request->gethtml('image_display_id'),'image_display_validation' =>$this->session->get('image_display_validation'))));
    	}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
		$image_description_data = array();
		$results = $this->modelImageDisplay->get_languages();
		$view->set('languages', $results); 
		
		foreach ($results as $result) {
			if(($this->request->gethtml('image_display_id')) && (!$this->request->isPost())) {
				$image_description_info = $this->modelImageDisplay->get_descriptions($this->request->gethtml('image_display_id'),$result['language_id']);
			}
			$flash = $this->request->get('flash', 'post');
			$flash_width = $this->request->gethtml('flash_width', 'post');
			$flash_height = $this->request->gethtml('flash_height','post');
			$flash_loop = $this->request->gethtml('flash_loop','post');
			$image_id = $this->request->gethtml('image_id', 'post');
			$image_width = $this->request->gethtml('image_width', 'post');
			$image_height = $this->request->gethtml('image_height','post');
			
			$image_description_data[] = array(
				'language_id' 	=> $result['language_id'],
	    		'language'    	=> $result['name'],
				'flash'			=> (isset($flash[$result['language_id']]) ? $flash[$result['language_id']] : @ $image_description_info['flash']),
				'flash_width'	=> (isset($flash_width[$result['language_id']]) ? $flash_width[$result['language_id']] : @ $image_description_info['flash_width']),
				'flash_height'	=> (isset($flash_height[$result['language_id']]) ? $flash_height[$result['language_id']] : @ $image_description_info['flash_height']),
				'flash_loop'	=> (isset($flash_loop[$result['language_id']]) ? $flash_loop[$result['language_id']] : @ $image_description_info['flash_loop']),
				'image_id'	=> (isset($image_id[$result['language_id']]) ? $image_id[$result['language_id']] : @ $image_description_info['image_id']),
				'image_width'	=> (isset($image_width[$result['language_id']]) ? $image_width[$result['language_id']] : @ $image_description_info['image_width']),
				'image_height'	=> (isset($image_height[$result['language_id']]) ? $image_height[$result['language_id']] : @ $image_description_info['image_height'])
			);	
		}
		$view->set('image_display_descriptions', $image_description_data);
		
		If(($this->request->gethtml('image_display_id')) && (!$this->request->isPost())){
			$image_display_info = $this->modelImageDisplay->getRow_image_display_info($this->request->gethtml('image_display_id'));
		}
		
		if ($this->request->has('name', 'post')){
			$view->set('name', $this->request->get('name', 'post'));
		} else {
			$view->set('name', @$image_display_info['name']);
		}
		
		if ($this->request->has('status', 'post')) {
      		$view->set('status', $this->request->gethtml('status', 'post'));
    	} else {
      		$view->set('status', @$image_display_info['status']);
    	}
		
		if ($this->request->has('sort_order', 'post')) {
      		$view->set('sort_order', $this->request->gethtml('sort_order', 'post'));
    	} else {
      		$view->set('sort_order', @$image_display_info['sort_order']);
    	}
		
		if ($this->request->has('location_id', 'post')) {
      		$view->set('location_id', $this->request->gethtml('location_id', 'post'));
    	} else {
      		$view->set('location_id', @$image_display_info['location_id']);
    	}
		
		$image_data = array();
    	$results = $this->modelImageDisplay->get_images();
		foreach ($results as $result) {
			$image_data[] = array(
        		'image_id'   => $result['image_id'],
        		'title'      => $result['title'],
				'filename'   => $result['filename']
			);
		}
		$view->set('images', $image_data);
		$view->set('flash_files', $this->checkFiles());
		$results = $this->modelImageDisplay->get_locations();
		$locations = array();
		foreach($results as $result){
			$locations[] = array(
			'location_id'	=> $result['location_id'],
			'location'      => $this->language->get('text_location_' .$result['location'])
			);
		}
		$view->set('locations' , $locations);
		
		return $view->fetch('content/image_display.tpl');
	}
	
	function checkFiles() {
		$flash_data = array();
		$files=glob(DIR_FLASH.'*.*');
		if (!$files) { return; }
		foreach ($files as $file) {
			$pattern='/\.('.implode('|',$this->types).')$/';
			if (preg_match($pattern,$file)) {
				$filename = basename($file);
				$flash_data[] = array(
					'flash'   => $filename
				);	
			}
		}
		return $flash_data;
	}
	function viewFlash(){
		if($this->request->gethtml('flash')){
			$flash = HTTP_FLASH . $this->request->gethtml('flash');
			$output = '<object type="application/x=shockwave-flash"';
			$output .= ' data="' . $flash . '" width="300" height="150">' . "\n";
			$output .= '<param name="movie" value="' . $flash . '">' . "\n";
			$output .= '<param name="loop" value="true">' . "\n";
			$output .= '<embed src="' . $flash . '" width="300" height="150" name="loop" value="false"/>' . "\n";
			$output .= '</object>' . "\n";
		} else {
			$output = '';
		}
		$this->response->set($output);
	}
	
  	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
    	if (!$this->user->hasPermission('modify', 'image_display')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}
		if(!$this->validate->strlen($this->request->get('name', 'post'),1,64)){
			$this->error['name'] = $this->language->get('error_name');
		}
		if(!$this->request->get('location_id', 'post')) {
                $this->error['location'] = $this->language->get('error_location');
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
			$this->response->redirect($this->url->ssl('image_display'));//**
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('image_display'));//**
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'image_display')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}

	function validateDelete() {
		if(($this->session->get('image_display_validation') != $this->request->sanitize('image_display_validation')) || (strlen($this->session->get('image_display_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('image_display_validation');
    
    	if (!$this->user->hasPermission('modify', 'image_display')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
	  	
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}

	function flash_upload(){
		if ($this->user->hasPermission('modify', 'image_display')) {
		  if($this->upload->has('flashimage') && !$this->upload->hasError('flashimage') ){
		    $pattern='/\.('.implode('|',$this->types).')$/';
		    if (preg_match($pattern,$this->upload->getName('flashimage'))) {
			  $this->upload->save('flashimage',DIR_FLASH . $this->upload->getName('flashimage'));
		    }
		  }
		}
		if($this->request->has('image_display_id')){
		$this->response->redirect($this->url->ssl('image_display', 'update', array('image_display_id' => (int)$this->request->gethtml('image_display_id'))));	
		} else {
		$this->response->redirect($this->url->ssl('image_display', 'insert'));	
		}
	}

	function page() {
		if ($this->request->has('search', 'post')) {
	  		$this->session->set('imagedisplay.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
	  		$this->session->set('imagedisplay.page', (int)$this->request->gethtml('page', 'post'));
		} 
		if ($this->request->has('sort', 'post')) {
	  		$this->session->set('imagedisplay.order', (($this->session->get('imagedisplay.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('imagedisplay.order') == 'asc') ? 'desc' : 'asc'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('imagedisplay.sort', $this->request->gethtml('sort', 'post'));
		}
		$this->response->redirect($this->url->ssl('image_display'));	
	}
}
?>
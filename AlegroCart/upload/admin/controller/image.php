<?php // Admin Image AlegroCart
class ControllerImage extends Controller {
	var $html='<img src="%s" alt="%s" title="%s" width="%s" height="%s">';
	var $size=100;
	var $types=array('jpg','gif','jpeg','png');
  	var $error = array(); 
	var $wm_method = 'auto';
	var $wm_active = FALSE;

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
		$this->upload   	=& $locator->get('upload');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user'); 
		$this->validate 	=& $locator->get('validate');
		$this->modelImage = $model->get('model_admin_image');
		$this->watermark  	=& $locator->get('watermark');

		$this->language->load('controller/image.php');
	}

  	function index() {
	   	$this->template->set('title', $this->language->get('heading_title'));
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}

	function checkFiles() {
		$files=glob(DIR_IMAGE.'*.*');
		if (!$files) { return; }
		foreach ($files as $file) {
			$pattern='/\.('.implode('|',$this->types).')$/';
			if (preg_match($pattern,$file)) {
				$filename=basename($file);
				$result = $this->modelImage->check_image($filename);
				if (!$result) { $this->init($filename); }
			}
		}
		$this->cache->delete('image');
	}

	function init($filename) {
		$this->modelImage->insert_image($filename);
		$insert_id = $this->modelImage->get_insert_id();
		$title = $this->getTitle($filename);
		$key = $this->language->languages[$this->config->get('config_language')]['language_id'];
		$this->modelImage->insert_description($insert_id, $key, $title);
	}

	function getTitle($file) {
		$str=$file;
		$str=pathinfo_filename($file);
		$str=str_replace(array('_','-'),' ',$str);
		$str=ucwords($str);
		return $str;
	}

  	function insert() {

    	$this->template->set('title', $this->language->get('heading_title'));
    
		if ($this->request->isPost() && $this->upload->has('image') && $this->validateForm() ) {
			if ($this->upload->save('image', DIR_IMAGE . $this->upload->getName('image'))) {
				$this->modelImage->insert_image($this->upload->getName('image'));
				$insert_id = $this->modelImage->get_insert_id();
                foreach ($this->request->gethtml('language', 'post') as $key => $value) {
				    if (empty($value['title'])) { $value['title']=$this->getTitle($this->upload->getName('image')); }
        		    $this->modelImage->insert_description($insert_id, $key, $value['title']);
      		    }
		if ($this->watermark->check_status($this->wm_method)) {
		$this->watermark->merge( DIR_IMAGE . $this->upload->getName('image'), $this->wm_method);
		}
	  		    $this->cache->delete('image');
			    $this->session->set('message', $this->language->get('text_message'));
			    
	  		    $this->response->redirect($this->url->ssl('image'));
            }
            $this->error['file'] = $this->language->get('error_upload');
		}
    	$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function update() {
	
		$this->template->set('title', $this->language->get('heading_title'));
    
		if ($this->request->isPost() && $this->request->has('image_id') && $this->validateForm() ) {
      		if ($this->upload->has('image') && ($this->upload->save('image', DIR_IMAGE . $this->upload->getName('image')))) {
					$this->modelImage->update_image($this->upload->getName('image'));
      		}
			$this->modelImage->delete_description();
      		foreach ($this->request->gethtml('language', 'post') as $key => $value) {
				$this->modelImage->insert_description( $this->request->gethtml('image_id'), $key, $value['title']);
      		} 
	  		$this->cache->delete('image');
			$this->session->set('message', $this->language->get('text_message'));
	  
	  		$this->response->redirect($this->url->ssl('image'));
    	}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
		$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function delete () {
		$this->template->set('title', $this->language->get('heading_title'));
		 
		if (($this->request->gethtml('image_id')) && ($this->validateDelete())) {
      		$result = $this->modelImage->get_image();
			$result = array_shift($result); // Only delete the actual file if there's 1 database entry remaining
			$rows = $this->modelImage->check_filename($result['filename']);
			if (count($rows) <= 1) {
				$this->image->delete($result['filename']);
			}
			$this->modelImage->delete_image();
      		$this->modelImage->delete_description();
	  		$this->cache->delete('image');
			$this->session->set('message', $this->language->get('text_message'));
	  
		  	$this->response->redirect($this->url->ssl('image'));
    	}
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}
  
  	function getList() {
		$this->session->set('image_validation', md5(time()));
		$this->checkFiles();
 		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_title'),
			'sort'  => 'id.title',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_filename'),
			'sort'  => 'i.filename',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_date_added'),
			'sort'  => 'i.date_added',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_image'),
			'align' => 'center'
		);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'action'
    	);

		$results = $this->modelImage->get_page();
		$rows = array();
		foreach ($results as $result) {
			$cell = array();
			$cell[] = array(
				'value' => $result['title'],
				'align' => 'left',
			);
			$cell[] = array(
				'value' => $result['filename'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'align' => 'left'
			);
			$cell[] = array(
			       'image' => $this->image->resize($result['filename'], '26', '26'),
			       'previewimage' => $this->image->resize($result['filename'], $this->config->get('config_image_width'), $this->config->get('config_image_height')),
			       'title' => $result['filename'],
			       'align' => 'center'
			);
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('image', 'update', array('image_id' => $result['image_id']))
      		);
			
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('image', 'delete', array('image_id' => $result['image_id'],'image_validation' =>$this->session->get('image_validation')))
				);
			}
			
      		$cell[] = array(
        		'action' => $action,
        		'align'  => 'action'
      		);
			$rows[] = array(
				'cell' => $cell
			);
		}
		
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('heading_description', $this->language->get('heading_description'));

    	$view->set('text_results', $this->modelImage->get_text_results());
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
	$view->set('button_status', $this->language->get('button_status'));

	$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

    	$view->set('error', @$this->error['message']);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		     
    	$view->set('action', $this->url->ssl('image', 'page'));
		$view->set('action_delete', $this->url->ssl('image', 'enableDelete'));
		
		$view->set('search', $this->session->get('image.search'));
		$view->set('sort', $this->session->get('image.sort'));
		$view->set('order', $this->session->get('image.order'));
		$view->set('page', $this->session->get('image.page'));
		
		$view->set('cols', $cols);
		$view->set('rows', $rows);
			    	
    	$view->set('list', $this->url->ssl('image'));
    	$view->set('insert', $this->url->ssl('image', 'insert'));

    	$view->set('pages', $this->modelImage->get_pagination());

		return $view->fetch('content/list.tpl');
  	}
  
  	function getForm() {	
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('heading_description', $this->language->get('heading_description'));

    	$view->set('text_image_filename', $this->language->get('text_image_filename'));
	$view->set('text_browse', $this->language->get('text_browse'));
	$view->set('text_autowm_warning', $this->language->get('text_autowm_warning'));

	$view->set('entry_filename', $this->language->get('entry_filename'));
    	$view->set('entry_title', $this->language->get('entry_title'));
	
    	$view->set('button_list', $this->language->get('button_list'));
    	$view->set('button_insert', $this->language->get('button_insert'));
    	$view->set('button_update', $this->language->get('button_update'));
	$view->set('button_delete', $this->language->get('button_delete'));
	$view->set('button_save', $this->language->get('button_save'));
    	$view->set('button_cancel', $this->language->get('button_cancel'));
	$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_general', $this->language->get('tab_general'));
    	$view->set('tab_data', $this->language->get('tab_data'));

    	$view->set('error', @$this->error['message']);
    	$view->set('error_title', @$this->error['title']);
		$view->set('error_file', @$this->error['file']);
    	$view->set('error_delete', @$this->error['message']);

    	$view->set('action', $this->url->ssl('image', $this->request->gethtml('action'), array('image_id' => $this->request->gethtml('image_id'))));
  
    	$view->set('list', $this->url->ssl('image'));
    	$view->set('insert', $this->url->ssl('image', 'insert'));
		$view->set('cancel', $this->url->ssl('image'));
  
    	if ($this->request->gethtml('image_id')) {	  
      		$view->set('update', $this->url->ssl('image', 'update', array('image_id' => $this->request->gethtml('image_id'))));
	  		$view->set('delete', $this->url->ssl('image', 'delete', array('image_id' => $this->request->gethtml('image_id'),'image_validation' =>$this->session->get('image_validation'))));
    	}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

	if ($this->watermark->check_status($this->wm_method)) {
		$this->wm_active = TRUE;
	}
	$view->set('wm_active', $this->wm_active);

		$image_data = array();
    	$results = $this->modelImage->get_languages();
    	foreach ($results as $result) {	  
			if (($this->request->gethtml('image_id')) && (!$this->request->isPost())) {
	  			$image_description_info = $this->modelImage->get_description($result['language_id']);
			} else {
				$image_description_info = $this->request->gethtml('language', 'post');
			}
	  		$image_data[] = array(
	    		'language_id' => $result['language_id'],
	    		'language'    => $result['name'],
	    		'title'       => (isset($image_description_info[$result['language_id']]) ? $image_description_info[$result['language_id']]['title'] : @$image_description_info['title']),	  		
			);
    	}
    	$view->set('images', $image_data);
		if (($this->request->gethtml('image_id')) && (!$this->request->isPost())) {
			$result = $this->modelImage->get_image();
			$image_photo[] = array(
				'filename' => $result[0]['filename'],
				'thumb'    => $this->image->resize($result[0]['filename'], $this->config->get('config_image_width')?$this->config->get('config_image_width'):$this->size, $this->config->get('config_image_height')?$this->config->get('config_image_height'):$this->size)
			);
			$view->set('image_data', $image_photo);
		}
  
 		return $view->fetch('content/image.tpl');
  	}

  	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
    	if (!$this->user->hasPermission('modify', 'image')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}
		if ($this->upload->has('image'))  {
	  		if ($this->upload->hasError('image')) {
	    		$this->error['file'] = $this->language->get('error_upload');
	  		}
	  		if (!$this->validate->strlen($this->upload->getName('image'),1,128)) {
        		$this->error['file'] = $this->language->get('error_filename');
			}
	    	$allowed = array(
	      		'image/jpeg',
	      		'image/pjpeg',
		  		'image/gif', 
		  		'image/png',
				'image/x-png'
	    	);
	    	if (!in_array($this->upload->getType('image'), $allowed)) {
          		$this->error['file'] = $this->language->get('error_filetype');
        	}
			if ($this->upload->hasError('image')) {
				$this->error['message'] = $this->upload->getError('image');
			}
    	} elseif ($this->request->get('action') == 'insert') {
	    	$this->error['file'] = $this->language->get('error_filename');
		}
		foreach ($this->request->gethtml('language', 'post') as $value) {
			if (empty($value['title'])) { $value['title']=$this->getTitle($this->upload->getName('image')); }
      		if (!$this->validate->strlen($value['title'],1,64)) {
        		$this->error['title'] = $this->language->get('error_title');
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
			$this->response->redirect($this->url->ssl('image'));//**
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('image'));//**
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'image')) {//**
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}

  	function validateDelete() {
		if(($this->session->get('image_validation') != $this->request->sanitize('image_validation')) || (strlen($this->session->get('image_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('image_validation');
    		if (!$this->user->hasPermission('modify', 'image')) {
      			$this->error['message'] = $this->language->get('error_permission');
    		}	
  		$product_info = $this->modelImage->check_product();
		if ($product_info['total']) {
			$this->error['message'] = $product_info['total'] ==1 ? $this->language->get('error_product') : $this->language->get('error_products', $product_info['total']);
			$product_list = $this-> modelImage->get_imageToProducts();
				$this->error['message'] .= '<br>';
				foreach ($product_list as $product) {
					$this->error['message'] .= '<a href="' . $this->url->ssl('product', 'update', array('product_id' => $product['product_id'])) . '">' . $product['name'] . '</a>&nbsp;';
				}
		}	 	
		$category_info = $this->modelImage->check_category();
		if ($category_info['total']) {
			$this->error['message'] = $category_info['total'] ==1 ? $this->language->get('error_category') : $this->language->get('error_categories', $category_info['total']);
			$category_list = $this-> modelImage->get_imageToCategory();
				$this->error['message'] .= '<br>';
				foreach ($category_list as $category) {
					if ($category['parent_id']) {
						$path = explode('_', $category['path']);
						array_pop($path);
						$path = implode('_', $path);
					$this->error['message'] .= '<a href="' . $this->url->ssl('category', 'update', array('category_id' => $category['category_id'], 'path' => $path)) . '">' . $category['name'] . '</a>&nbsp;';
					} else {
					$this->error['message'] .= '<a href="' . $this->url->ssl('category', 'update', array('category_id' => $category['category_id'])) . '">' . $category['name'] . '</a>&nbsp;';
					}
				}	
		}
		$manufacturer_info = $this->modelImage->check_manufacturer();
		if ($manufacturer_info['total']) {
			$this->error['message'] = $manufacturer_info['total'] ==1 ? $this->language->get('error_manufacturer') : $this->language->get('error_manufacturers', $manufacturer_info['total']);
			$manufacturer_list = $this-> modelImage->get_imageToManufacturer();
				$this->error['message'] .= '<br>';
				foreach ($manufacturer_list as $manufacturer) {
					$this->error['message'] .= '<a href="' . $this->url->ssl('manufacturer', 'update', array('manufacturer_id' => $manufacturer['manufacturer_id'])) . '">' . $manufacturer['name'] . '</a>&nbsp;';
				}
		}

		$imagedisplay_info = $this->modelImage->check_imagedisplay();
		if ($imagedisplay_info['total']) {
			$this->error['message'] = $imagedisplay_info['total'] ==1 ? $this->language->get('error_imagedisplay') : $this->language->get('error_imagedisplays', $imagedisplay_info['total']);
			$imagedisplay_list = $this-> modelImage->get_imageToImageDisplay();
				$this->error['message'] .= '<br>';
				foreach ($imagedisplay_list as $imagedisplay) {
					$this->error['message'] .= '<a href="' . $this->url->ssl('image_display', 'update', array('image_display_id' => $imagedisplay['image_display_id'])) . '">' . $imagedisplay['name'] . '</a>&nbsp;';
				}
		}

		$homepage_info = $this->modelImage->check_homepage();
		if ($homepage_info['total']) {
			$this->error['message'] = $homepage_info['total'] ==1 ? $this->language->get('error_homepage') : $this->language->get('error_homepages', $homepage_info['total']);
			$homepage_list = $this-> modelImage->get_imageToHomePage();
				$this->error['message'] .= '<br>';
				foreach ($homepage_list as $homepage) {
					$this->error['message'] .= '<a href="' . $this->url->ssl('homepage', 'update', array('home_id' => $homepage['home_id'])) . '">' . $homepage['name'] . '</a>&nbsp;';
				}
		}

		$additional_info = $this->modelImage->check_additional();
		if ($additional_info['total']) {
			$this->error['message'] = $additional_info['total'] ==1 ? $this->language->get('error_additional') : $this->language->get('error_additionals', $additional_info['total']);
			$additional_list = $this-> modelImage->get_imageToAdditional();
				$this->error['message'] .= '<br>';
				foreach ($additional_list as $additional) {
					$this->error['message'] .= '<a href="' . $this->url->ssl('product', 'update', array('product_id' => $additional['product_id'])) . '">' . $additional['name'] . '</a>&nbsp;';
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
			$this->session->set('image.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('image.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('image.order', (($this->session->get('image.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('image.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('image.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('image'));
	}	
	    
	function view() {
		$image_info = $this->modelImage->get_image_data();

		$this->response->set(sprintf($this->html,$this->image->resize($image_info['filename'], $this->config->get('config_image_width')?$this->config->get('config_image_width'):$this->size, $this->config->get('config_image_height')?$this->config->get('config_image_height'):$this->size), $image_info['title'], $image_info['title'], $this->config->get('config_image_width')?$this->config->get('config_image_width'):$this->size, $this->config->get('config_image_height')?$this->config->get('config_image_height'):$this->size));
	}	
}
?>

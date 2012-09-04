<?php // Admin Category AlegroCart
class ControllerCategory extends Controller {
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
		$this->modelCategory = $model->get('model_admin_category');
		
		$this->language->load('controller/category.php');
	}
	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function insert() {
		$this->template->set('title', $this->language->get('heading_title'));
		if ($this->request->isPost() && $this->request->has('image_id', 'post') && $this->validateForm() ) {
			$url_alias = $this->config->get('config_url_alias');
			$url_seo = $this->config->get('config_seo');
			$this->modelCategory->insert_category();
			$insert_id = $this->modelCategory->get_insert_id();
			if ($this->request->gethtml('path')) {
				$path = $this->request->gethtml('path') . '_' . $insert_id;
			} else {
				$path = $insert_id;
			}
			$this->modelCategory->update_path($path, $insert_id);
			$this->modelCategory->get_description_post();
			foreach ($this->request->gethtml('language', 'post') as $key => $value) {
				$this->modelCategory->write_description($insert_id,$key,$value['name']);
			}
			if($url_alias && $url_seo){
				$this->category_seo($insert_id, $path);
				$this->cache->delete('url');
			}
			$this->cache->delete('category');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('category', FALSE, array('path' => $this->request->gethtml('path'))));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function update() {
		$this->template->set('title', $this->language->get('heading_title'));
		if ($this->request->isPost() && $this->request->has('image_id', 'post') && $this->validateForm()) {
			$url_alias = $this->config->get('config_url_alias');
			$url_seo = $this->config->get('config_seo');
			$this->modelCategory->update_category();
			$this->modelCategory->delete_description();
			$this->modelCategory->get_description_post();
			foreach ($this->request->gethtml('language', 'post') as $key => $value) {
				$this->modelCategory->write_description($this->request->gethtml('category_id'),$key,$value['name']);
			}
			if($url_alias && $url_seo){
				$category_info = $this->modelCategory->get_path($this->request->gethtml('category_id'));
				$this->delete_category_seo($category_info['path']);
				$this->category_seo($this->request->gethtml('category_id'), $category_info['path']);
				$this->cache->delete('url');
			}
			$this->cache->delete('category');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('category', FALSE, array('path' => $this->request->gethtml('path'))));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function delete() {
		$this->template->set('title', $this->language->get('heading_title'));
		if (($this->request->gethtml('category_id')) && ($this->validateDelete())) {
			$url_alias = $this->config->get('config_url_alias');
			$url_seo = $this->config->get('config_seo');
			$this->modelCategory->delete_category();
			$this->modelCategory->delete_description();
			if ($this->request->gethtml('path')) {
				$path = $this->request->gethtml('path') . '_' . $this->request->gethtml('category_id');
			} else {
				$path = $this->request->gethtml('category_id');
			}
			$this->modelCategory->delete_subcats($path);
			if($url_alias && $url_seo){
				$this->delete_category_seo($path);
				$this->cache->delete('url');
			}
			$this->cache->delete('category');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('category', FALSE, array('path' => $this->request->gethtml('path'))));
		}
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
  
	function getList() {
		$this->session->set('category_validation', md5(time()));
		if($this->session->get('category_path') != $this->request->gethtml('path')){
			$this->session->delete('category.search');
			$this->session->delete('category.order');
			$this->session->delete('category.sort');
		}	
		$this->session->set('category_path',$this->request->gethtml('path'));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_category'),
			'folder_help' => $this->language->get('text_folder_help'),
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_name'),
			'sort'  => 'cd.name',
			'align' => 'left'
		);
		$cols[] = array(
             'name'  => $this->language->get('column_image'),
             'sort'  => 'i.filename',
             'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_sort_order'),
			'sort'  => 'c.sort_order',
			'align' => 'right'
		);
		$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'action'
    	);
		
		$results = $this->modelCategory->get_page();

		$rows = array();
		foreach ($results as $result) {
			$cell = array();
			if ($this->request->gethtml('path')) {
				$path = $this->request->gethtml('path') . '_' . $result['category_id'];
			} else {
				$path = $result['category_id'];
			}
			$cell[] = array(
				'icon'  => $this->modelCategory->check_children($result['category_id']) ? 'folderO.png' : 'folder.png',
				'align' => 'center',
				'path'  => $this->url->ssl('category', FALSE, array('path' => $path)),
			);
			$cell[] = array(
				'value' => $result['name'],
				'align' => 'left'
			);
		   	$cell[] = array(
		       'image' => $this->image->resize($result['filename'], '26', '26'),
		       'previewimage' => $this->image->resize($result['filename'], $this->config->get('config_image_width'), $this->config->get('config_image_height')),
		       'title' => $result['filename'],
		       'align' => 'center'
		        );
			$cell[] = array(
				'value' => $result['sort_order'],
				'align' => 'right'
			);
			$query = array(
				'category_id' => $result['category_id'],
				'path'        => $this->request->gethtml('path'),
			);
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('category', 'update', $query)
      		);
			
			if($this->session->get('enable_delete')){
				$query = array(
					'category_id' => $result['category_id'],
					'path'        => $this->request->gethtml('path'),
					'category_validation' =>$this->session->get('category_validation')
				);
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('category', 'delete', $query)
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

		$view->set('text_previous', $this->language->get('text_previous'));
		$view->set('text_results',$this->modelCategory->get_text_results());

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete'));

		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);
		
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('category', 'page', array('path' => $this->request->gethtml('path'))));
		if ($this->request->gethtml('path')) {
			$path = explode('_', $this->request->gethtml('path'));
			if (count($path) > 1) {
				array_pop($path);
				$view->set('previous', $this->url->ssl('category', FALSE, array('path' => implode('_', $path))));
			} else {
				$view->set('previous', $this->url->ssl('category'));
			}
		} 
		$view->set('action_delete', $this->url->ssl('category', 'enableDelete', array('path' => $this->request->gethtml('path'))));
		$view->set('search', $this->session->get('category.search'));
		$this->session->delete('category.search');
		$view->set('sort', $this->session->get('category.sort'));
		$view->set('order', $this->session->get('category.order'));
		$view->set('page', ($this->request->has('path') ? $this->session->get('category.' . $this->request->gethtml('path') . 'page') : $this->session->get('category.page')));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $this->url->ssl('category', FALSE, array('path' => $this->request->gethtml('path'))));
		$view->set('insert', $this->url->ssl('category', 'insert', array('path' => $this->request->gethtml('path'))));
		$view->set('pages', $this->modelCategory->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

        $view->set('entry_name', $this->language->get('entry_name'));
        $view->set('entry_description', $this->language->get('entry_description'));
		$view->set('entry_meta_title', $this->language->get('entry_meta_title'));
		$view->set('entry_meta_description', $this->language->get('entry_meta_description'));
		$view->set('entry_meta_keywords', $this->language->get('entry_meta_keywords'));	
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));
		$view->set('entry_image', $this->language->get('entry_image'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_general', $this->language->get('tab_general'));
		$view->set('tab_data', $this->language->get('tab_data'));
		$view->set('tab_image', $this->language->get('tab_image'));

		$view->set('error_description', @$this->error['message']);

        $view->set('error', @$this->error['message']);
		$view->set('error_name', @$this->error['name']);

		$query = array(
			'category_id' => $this->request->gethtml('category_id'),
			'path'        => $this->request->gethtml('path')
		);
		$view->set('action', $this->url->ssl('category', $this->request->gethtml('action'), $query));
		$view->set('list', $this->url->ssl('category', FALSE, array('path' => $this->request->gethtml('path'))));
		$view->set('insert', $this->url->ssl('category', 'insert', array('path' => $this->request->gethtml('path'))));
		$view->set('cancel', $this->url->ssl('category', FALSE, array('path' => $this->request->gethtml('path'))));
		
		if ($this->request->gethtml('category_id')) {
			$query = array(
				'category_id' => $this->request->gethtml('category_id'),
				'path'        => $this->request->gethtml('path'),
				'category_validation' =>$this->session->get('category_validation')
			);
			$view->set('update', 'enabled');
			$view->set('delete', $this->url->ssl('category', 'delete', $query));
		}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));	
	
		$category_data = array();
		$results = $this->modelCategory->get_languages();
		foreach ($results as $result) {
			if (($this->request->gethtml('category_id')) && (!$this->request->isPost())) {
				$category_description_info = $this->modelCategory->get_category_description($result['language_id']);
			} else {
				$category_description_info = $this->request->gethtml('language', 'post');
			}
			
			$description = $this->request->get('description', 'post');
			$meta_title = $this->request->gethtml('meta_title', 'post');
			$meta_description = $this->request->gethtml('meta_description', 'post');
			$meta_keywords = $this->request->gethtml('meta_keywords', 'post');
			$category_data[] = array(
				'language_id' => $result['language_id'],
				'language'    => $result['name'],
	    		'name'        => (isset($category_description_info[$result['language_id']]) ? $category_description_info[$result['language_id']]['name'] : @$category_description_info['name']),
                'description' => (isset($description[$result['language_id']]) ? $description[$result['language_id']] : @$category_description_info['description']),
	    		'meta_title' 	=> (isset($meta_title[$result['language_id']]) ? $meta_title[$result['language_id']] : @$category_description_info['meta_title']),			
	    		'meta_description'=> (isset($meta_description[$result['language_id']]) ? $meta_description[$result['language_id']] : @$category_description_info['meta_description']),
	    		'meta_keywords' => (isset($meta_keywords[$result['language_id']]) ? $meta_keywords[$result['language_id']] : @$category_description_info['meta_keywords'])					
			);
		}
		$view->set('categories', $category_data);
		
		if (($this->request->gethtml('category_id')) && (! $this->request->isPost())) {
			$category_info = $this->modelCategory->get_category();
		}

		if ($this->request->has('sort_order', 'post')) {
			$view->set('sort_order', $this->request->gethtml('sort_order', 'post'));
		} else {
			$view->set('sort_order', @$category_info['sort_order']);
		}

		if ($this->request->has('image_id', 'post')) {
			$view->set('image_id', $this->request->gethtml('image_id', 'post'));
		} else {
			$view->set('image_id', @$category_info['image_id']);
		}

		$image_data = array();
		$results = $this->modelCategory->get_images();
		foreach ($results as $result) {
			$image_data[] = array(
				'image_id' => $result['image_id'],
				'title'    => $result['title']
			);
		}
		$view->set('images', $image_data);

		return $view->fetch('content/category.tpl');
	}

	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'category')) {
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
			$this->response->redirect($this->url->ssl('category', FALSE, array('path' => $this->request->gethtml('path'))));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('category', FALSE, array('path' => $this->request->gethtml('path'))));
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'category')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}
	
	function validateDelete() {
		if(($this->session->get('category_validation') != $this->request->sanitize('category_validation')) || (strlen($this->session->get('category_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('category_validation');
		if (!$this->user->hasPermission('modify', 'category')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if($this->modelCategory->check_products()){
			$this->error['message'] = $this->language->get('error_has_products');
		}
		if (!$this->error) {
			return TRUE; 
		} else {
			return FALSE;
		}
	}
	
	function category_seo($category_id, $path){
		$this->language_id = (int)$this->language->getId();
		$categories = explode('_', $path);
		$alias = '';
		foreach ($categories as $category){
			$row = $this->modelCategory->get_category_name($category,$this->language_id);
			$alias .= $this->generate_seo->clean_alias($row['name']);
			$alias .= '/';
		}
		$alias = rtrim($alias, '/');
		$alias .= '.html';
		$query_path = 'controller=category&path=' . $path;
		$this->generate_seo->_insert_url_alias($query_path, $alias);
	}
	function delete_category_seo($path){
		$query_path = 'controller=category&path=' . $path;
		$this->modelCategory->delete_SEO($query_path);
	}
	
	  	function page() {
		$this->session->delete('category.search');
		if ($this->request->has('search', 'post') && $this->request->gethtml('search','post') != '') {
	  		$this->session->set('category.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set(($this->request->has('path') ? 'category.' . $this->request->gethtml('path') . '.page' : 'category.page'), $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('category.order', (($this->session->get('category.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('category.order') == 'asc') ? 'desc' : 'asc'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('category.sort', $this->request->gethtml('sort', 'post'));
		} 
		
		$this->response->redirect($this->url->ssl('category', FALSE, array('path' => $this->request->gethtml('path'))));
  	} 
}
?>

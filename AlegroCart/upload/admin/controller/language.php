<?php // Language AlegroCart
class ControllerLanguage extends Controller {
	var $error = array();
	var $types = array('gif', 'jpg', 'png');
	var $image_path;
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 			=& $locator->get('model');
		$this->cache    	=& $locator->get('cache');
		$this->config   	=& $locator->get('config');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request 	 	=& $locator->get('request');
		$this->response	 	=& $locator->get('response');
		$this->session 	 	=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url     		=& $locator->get('url');
		$this->user    	 	=& $locator->get('user');
		$this->validate 	=& $locator->get('validate');
		$this->modelLanguage = $model->get('model_admin_language');
		$this->language->load('controller/language.php');
		$this->image_path = HTTP_ADMIN . 'template' . '/'  . $this->template->directory . '/' . 'image' . '/' . 'language' . '/';
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
			$this->modelLanguage->insert_language();
			$insert_id = $this->modelLanguage->get_last_id();

			$this->modelLanguage->duplicate_category_description();
			$this->modelLanguage->duplicate_coupon_description();
			$this->modelLanguage->duplicate_dimension();
			$this->modelLanguage->duplicate_download_description();
			$this->modelLanguage->duplicate_extension_description();
			$this->modelLanguage->duplicate_home_description();
			$this->modelLanguage->duplicate_image_description();
			$this->modelLanguage->duplicate_image_display_description();
			$this->modelLanguage->duplicate_information_description();
			$this->modelLanguage->duplicate_option();
			$this->modelLanguage->duplicate_option_value();
			$this->modelLanguage->duplicate_order_status();
			$this->modelLanguage->duplicate_product_description();
			$this->modelLanguage->duplicate_weight_class();

			$this->session->set('last_language_id', $insert_id);
			$this->cache->delete('language');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('language'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('name', 'post') && $this->validateForm()) {
			$this->modelLanguage->update_language();
			$this->cache->delete('language');
			$this->session->set('message', $this->language->get('text_message'));
			$this->session->delete('catalog_language');
			$this->session->delete('admin_language');
			setcookie('admin_language','', time() - 86400 * 2,'/');
			setcookie('catalog_language','', time() - 86400 * 2,'/');

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('language', 'update', array('language_id' => $this->request->gethtml('language_id'))));
			} else {
				$this->response->redirect($this->url->ssl('language'));
			}

		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('language_id')) && ($this->validateDelete())) {
			$this->modelLanguage->delete_language();
			$this->cache->delete('language');

			$this->response->redirect($this->url->ssl('language'));
		}
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	function changeStatus() { 
		
		if (($this->request->has('stat_id')) && ($this->request->has('stat')) && $this->validateChangeStatus()) {

			$this->modelLanguage->change_language_status($this->request->gethtml('stat'), $this->request->gethtml('stat_id'));
			$this->cache->delete('language');
			$this->session->delete('catalog_language');
			$this->session->delete('admin_language');
			setcookie('admin_language','', time() - 86400 * 2,'/');
			setcookie('catalog_language','', time() - 86400 * 2,'/');

		}
	
	}
	function getList() {
		$this->session->set('language_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_name'),
			'sort'  => 'name',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_language_status'),
			'sort'  => 'language_status',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_code'),
			'sort'  => 'code',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_sort_order'),
			'sort'  => 'sort_order',
			'align' => 'right'
		);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'action'
    	);
		
		$results = $this->modelLanguage->get_page();
		$catalog_language = $this->modelLanguage->get_catalog_language();
		$rows = array();
		foreach ($results as $result) {
			$last = $result['language_id'] == $this->session->get('last_language_id') ? 'last_visited': '';
			$cell = array();
			$cell[] = array(
				'value'   => $result['name'],
				'align'   => 'left',
				'default_development' => ($result['code'] == 'en'),
				'default_admin' => ($result['code'] == $this->config->get('config_language')),
				'default_catalog' => ($result['code'] == $catalog_language),
				'last' => $last
			);
			if ($this->validateChangeStatus() && $result['code'] != $this->config->get('config_language') && $result['code'] != $catalog_language) {
			$cell[] = array(
				'status'  => $result['language_status'],
				'text' => $this->language->get('button_status'),
				'align' => 'center',
				'status_id' => $result['language_id'],
				'status_controller' => 'language'
			);

			} else {
			$cell[] = array(
				'icon'  => ($result['language_status'] ? 'enabled.png' : 'disabled.png'),
				'align' => 'center'
			);
			}
			$cell[] = array(
				'value' => $result['code'],
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['sort_order'],
				'align' => 'right',
				'last' => $last
			);
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('language', 'update', array('language_id' => $result['language_id']))
      		);
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('language', 'delete', array('language_id' => $result['language_id'],'language_validation' =>$this->session->get('language_validation')))
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

		$view->set('text_default_development', $this->language->get('text_default_development'));
		$view->set('text_default_catalog', $this->language->get('text_default_catalog'));
		$view->set('text_default_admin', $this->language->get('text_default_admin'));
		$view->set('text_results', $this->modelLanguage->get_text_results());

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete'));
 		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_status', $this->language->get('button_status'));
		$view->set('button_help', $this->language->get('button_help'));

		$view->set('help', $this->session->get('help'));
		$view->set('controller', 'language');
		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('language', 'page'));
		$view->set('action_delete', $this->url->ssl('language', 'enableDelete'));

		$view->set('search', $this->session->get('language.search'));
		$view->set('sort', $this->session->get('language.sort'));
		$view->set('order', $this->session->get('language.order'));
		$view->set('page', $this->session->get('language.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('insert', $this->url->ssl('language', 'insert'));

		$view->set('pages', $this->modelLanguage->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_form_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));

		$view->set('entry_name', $this->language->get('entry_name'));
		$view->set('entry_code', $this->language->get('entry_code'));
		$view->set('entry_image', $this->language->get('entry_image'));
		$view->set('entry_directory', $this->language->get('entry_directory'));
		$view->set('entry_filename', $this->language->get('entry_filename'));
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));
		$view->set('entry_language_status', $this->language->get('entry_language_status'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));

		$view->set('help', $this->session->get('help'));
		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_name', @$this->error['name']);
		$view->set('error_code', @$this->error['code']);
		$view->set('error_image', @$this->error['image']);
		$view->set('error_directory', @$this->error['directory']);
		$view->set('error_filename', @$this->error['filename']);

		$view->set('action', $this->url->ssl('language', $this->request->gethtml('action'), array('language_id' => $this->request->gethtml('language_id'))));

		$view->set('insert', $this->url->ssl('language', 'insert'));
		$view->set('cancel', $this->url->ssl('language'));

		if ($this->request->gethtml('language_id')) {
			$view->set('update', 'enable');
			$view->set('delete', $this->url->ssl('language', 'delete', array('language_id' => $this->request->gethtml('language_id'),'language_validation' =>$this->session->get('language_validation'))));
		}

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('language_id', $this->request->gethtml('language_id'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$this->session->set('last_language_id', $this->request->gethtml('language_id'));

		if (($this->request->gethtml('language_id')) && (! $this->request->isPost())) {
			$language_info = $this->modelLanguage->get_language();
		}

		if ($this->request->has('name', 'post')) {
			$view->set('name', $this->request->gethtml('name', 'post'));
		} else {
			$view->set('name', @$language_info['name']);
		}

		if ($this->request->has('code', 'post')) {
			$view->set('code', $this->request->gethtml('code', 'post'));
		} else {
			$view->set('code', @$language_info['code']);
		}

		if ($this->request->has('image', 'post')) {
			$view->set('image', $this->request->gethtml('image', 'post'));
			$view->set('image_thumb', $this->image_path . $this->request->gethtml('image', 'post'));
		} else {
			$view->set('image', @$language_info['image']);
			$view->set('image_thumb', $this->image_path . @$language_info['image']);
		}

		if ($this->request->has('directory', 'post')) {
			$view->set('directory', $this->request->gethtml('directory', 'post'));
		} else {
			$view->set('directory', @$language_info['directory']);
		}

		if ($this->request->has('filename', 'post')) {
			$view->set('filename', $this->request->gethtml('filename', 'post'));
		} else {
			$view->set('filename', @$language_info['filename']);
		}
		if ($this->request->has('language_status', 'post')) {
			$view->set('language_status', $this->request->gethtml('language_status', 'post'));
		} else {
			$view->set('language_status', @$language_info['language_status']);
		}
		if ($this->request->has('sort_order', 'post')) {
			$view->set('sort_order', $this->request->gethtml('sort_order', 'post'));
		} else {
			$view->set('sort_order', @$language_info['sort_order']);
		}
		
		$results = $this->checkFiles();
		$flag_data = array();
		foreach ($results as $result){
			$flag_data[] = array(
				'filename'	=> $result['filename'],
				'name'		=> $result['name'],
				'country'	=> $this->language->get('country_' . $result['name'])
			);
		}
		$view->set('flags', $flag_data);
		
		return $view->fetch('content/language.tpl');
	}

	function view_image(){
		if($this->request->gethtml('flag_image')){
			$output = '<img src="' . $this->image_path . $this->request->gethtml('flag_image') . '" ';
			$output .= 'alt="" title="Flag" width="32" height="22">';
		} else {
			$output = '';
		}
		$this->response->set($output);
	}

	function checkFiles() {
		$flag_data = array();
		$path = DIR_ADMIN . 'template' . D_S  . 'default' . D_S . 'image' . D_S . 'language' . D_S . '*.*';
		$files=glob($path);
		if (!$files) { return; }
		foreach ($files as $file) {
			$pattern='/\.('.implode('|',$this->types).')$/';
			if (preg_match($pattern,$file)) {
				$filename = basename($file);
				$name = str_replace($this->types, '', $filename);
				$name = str_replace('.', '', $name);
				$flag_data[] = array(
					'filename'   => $filename,
					'name'       => $name
				);	
			}
		}
		return $flag_data;
	}

	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'language')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if (!$this->validate->strlen($this->request->gethtml('name', 'post'),1,32)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		if (!$this->validate->strlen($this->request->gethtml('code', 'post'),2,5)) {
			$this->error['code'] = $this->language->get('error_code');
		}
		if (!$this->request->gethtml('directory', 'post')) {
			$this->error['directory'] = $this->language->get('error_directory');
		}
		if (!$this->request->gethtml('image', 'post')) {
			$this->error['image'] = $this->language->get('error_image');
		}
		if (!$this->request->gethtml('filename', 'post')) {
			$this->error['filename'] = $this->language->get('error_filename');
		}
		if ($this->request->gethtml('code', 'post') == $this->modelLanguage->get_catalog_language() && $this->request->gethtml('language_status', 'post') == FALSE) {
			$this->error['message'] = $this->language->get('error_default_catalog');
		}
		if ($this->request->gethtml('code', 'post') == $this->config->get('config_language') && $this->request->gethtml('language_status', 'post') == FALSE) {
			$this->error['message'] = $this->language->get('error_default_admin');
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
			$this->response->redirect($this->url->ssl('language'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('language'));
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'language')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	function validateChangeStatus(){
		if (!$this->user->hasPermission('modify', 'language')) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	function validateDelete() {
		if(($this->session->get('language_validation') != $this->request->sanitize('language_validation')) || (strlen($this->session->get('language_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('language_validation');
		if (!$this->user->hasPermission('modify', 'language')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		$language_info = $this->modelLanguage->check_language_code();
		$catalog_language = $this->modelLanguage->get_catalog_language();
		if ($language_info['code'] == "en") {
			$this->error['message'] = $this->language->get('error_default_development');
		}
		if ($language_info['code'] == $this->config->get('config_language')) {
			$this->error['message'] = $this->language->get('error_default_admin');
		}
		if ($language_info['code'] == $catalog_language) {
			$this->error['message'] = $this->language->get('error_default_catalog');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}
	function page() {
		if ($this->request->has('search', 'post')) {
			$this->session->set('language.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('language.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('language.order', (($this->session->get('language.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('language.order') == 'asc') ? 'desc' : 'asc'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('language.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('language'));
	}
}
?>

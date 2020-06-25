<?php // Admin Download AlegroCart
class ControllerDownload extends Controller {
	var $error = array();
	var $prohibited_types = array('php');
	function __construct(&$locator){
		$this->locator		=& $locator;
		$this->config		=& $locator->get('config');
		$this->download		=& $locator->get('download');
		$model			=& $locator->get('model');
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
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController = $this->template->set_controller('download');

		$this->language->load('controller/download.php');
	}
	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function checkFiles() {
		$files=glob(DIR_DOWNLOAD.'*.*');
		if (!$files) { return; }
		foreach ($files as $file) {
			$pattern='/\.('.implode('|',$this->prohibited_types).')$/';
			$filename=basename($file);
			if (!preg_match($pattern,$file) && $this->validate->strlen($filename,1,128)) {
				$result = $this->modelDownload->check_download($filename);
				if (!$result) { $this->init($filename); }
			}
		}
	}

	function init($filename) {
		$mask = $this->getMask($filename);
		$this->modelDownload->insert_downloads($filename, $mask);
		$insert_id = $this->modelDownload->get_insert_id();
		$downloadname = $this->getDownloadName($filename);
		$results = $this->modelDownload->get_languages();
			foreach ($results as $result) {
				if($result['language_status'] =='1'){
					$key = $result['language_id'];
					$this->modelDownload->insert_description($insert_id, $key, $downloadname);
				}
			}
			
	}

	function getDownloadName($file) {
		$str=$file;
		$str=pathinfo_filename($file);
		$str=str_replace(array('_','-'),' ',$str);
		$str=ucwords($str);
		return $str;
	}

	function getMask($file) {
		$seed=pathinfo_filename($file);
		$ext=pathinfo_extension($file);
		$filecount = strlen($seed);
		$NumberSeed = '';
		for($i = 0; $i < $filecount; ++$i){
		$NumberSeed += $this->uniord($seed{$i});
		}
		list($sec, $msec) = explode('.', microtime(true));
		$mask = $msec*(int)$NumberSeed;
		return $mask.'.'.$ext;
	}

	function uniord($char) {
		$h = ord($char{0});
		if (!isset($char{1})) $char{1}='';
		if (!isset($char{2})) $char{2}='';
		if (!isset($char{3})) $char{3}='';
		if ($h <= 0x7F) {
			return $h;
		} else if ($h < 0xC2) {
			return false;
		} else if ($h <= 0xDF) {
			return ($h & 0x1F) << 6 | (ord($char{1}) & 0x3F);
		} else if ($h <= 0xEF) {
			return ($h & 0x0F) << 12 | (ord($char{1}) & 0x3F) << 6 | (ord($char{2}) & 0x3F);
		} else if ($h <= 0xF4) {
			return ($h & 0x0F) << 18 | (ord($char{1}) & 0x3F) << 12 | (ord($char{2}) & 0x3F) << 6 | (ord($char{3}) & 0x3F);
		} else {
			return false;
		}
	}

	function insert() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->validateForm() && $this->upload->has('download')) {
			$this->modelDownload->insert_download();
			$insert_id = $this->modelDownload->get_insert_id();
			foreach ($this->request->gethtml('language', 'post') as $key => $value) {
				$this->modelDownload->insert_description($insert_id, $key, $value['name']);
				if ($key == $this->language->getId()) {
					$name_last = $value['name'];
					if (strlen($name_last) > 26) {
						$name_last = substr($name_last , 0, 23) . '...';
					}
					$this->session->set('name_last_download', $name_last);
					$this->session->set('last_download', $this->url->ssl('download', 'update', array('download_id' => $insert_id)));
				}
			}
			$this->session->set('last_download_id', $insert_id);
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('download'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());

		$this->session->delete('name_last_download');
		$this->session->delete('last_download');

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

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('download', 'update', array('download_id' => $this->request->gethtml('download_id', 'post'))));
			} else {
				$this->response->redirect($this->url->ssl('download'));
			}
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('download_id')) && ($this->validateDelete())) {
			$result = $this->modelDownload->get_download();
			$result = array_shift($result); // Only delete the actual file if there's 1 database entry remaining
			$rows = $this->modelDownload->check_filename($result['filename']);
			if (count($rows) <= 1) {
				$this->download->delete($result['filename']);
			}
			$this->modelDownload->delete_download();
			$this->modelDownload->delete_description();
			$this->session->set('message', $this->language->get('text_message'));
			$this->session->delete('name_last_download');
			$this->session->delete('last_download');
			$this->response->redirect($this->url->ssl('download'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function getList() {
	$this->session->set('download_validation', md5(time()));
	$this->checkFiles();
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
		$last = $result['download_id'] == $this->session->get('last_download_id') ? 'last_visited': '';
      		$cell = array();
      		$cell[] = array(
        		'value' => $result['name'],
        		'align' => 'left',
			'last' => $last
      		);
     		$cell[] = array(
        		'value' => $result['filename'],
        		'align' => 'left',
			'last' => $last
      		);
     		$cell[] = array(
        		'value' => $result['mask'],
        		'align' => 'left',
			'last' => $last
      		);
      		$cell[] = array(
        		'value' => $result['remaining'],
       			'align' => 'right',
			'last' => $last
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
		$view->set('head_def',$this->head_def);
	$view->set('heading_title', $this->language->get('heading_title'));
	$view->set('heading_description', $this->language->get('heading_description', $this->get_uploadable()));

	$view->set('text_results',$this->modelDownload->get_text_results());

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
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));
		$view->set('controller', 'download');
	$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

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

	$view->set('insert', $this->url->ssl('download', 'insert'));
		$view->set('last', $this->url->getLast('download'));

	$view->set('pages', $this->modelDownload->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	function getForm() {
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_form_title'));
		$view->set('heading_description', $this->language->get('heading_description', $this->get_uploadable()));

		$view->set('entry_name', $this->language->get('entry_name'));
		$view->set('entry_filename', $this->language->get('entry_filename'));
			$view->set('entry_mask', $this->language->get('entry_mask'));
		$view->set('entry_remaining', $this->language->get('entry_remaining'));
		$view->set('text_browse', $this->language->get('text_browse'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
	    	$view->set('button_save', $this->language->get('button_save'));
	    	$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));
    		$view->set('tab_general', $this->language->get('tab_general'));
    		$view->set('tab_data', $this->language->get('tab_data'));

		$view->set('explanation_name', $this->language->get('explanation_name'));
		$view->set('explanation_mask', $this->language->get('explanation_mask'));
		$view->set('explanation_remaining', $this->language->get('explanation_remaining'));

		$view->set('error_update', $this->language->get('error_update'));
		$view->set('error', @$this->error['message']);
		$view->set('error_name', @$this->error['name']);
		$view->set('error_file', @$this->error['file']);
		$view->set('error_mask', @$this->error['mask']);

		if(!@$this->error['message']){
			$view->set('error', @$this->error['warning']);
		}

		$view->set('action', $this->url->ssl('download', $this->request->gethtml('action'), array('download_id' => $this->request->gethtml('download_id'))));

		$view->set('insert', $this->url->ssl('download', 'insert'));
		$view->set('cancel', $this->url->ssl('download'));
		$view->set('last', $this->url->getLast('download'));

    		if ($this->request->gethtml('download_id')) {
      			$view->set('update', 'enable');
	  		$view->set('delete', $this->url->ssl('download', 'delete', array('download_id' => $this->request->gethtml('download_id'),'download_validation' =>$this->session->get('download_validation'))));
    		}

		$view->set('tab', $this->session->has('download_tab') && $this->session->get('download_id') == $this->request->gethtml('download_id') ? $this->session->get('download_tab') : 0);
		$view->set('tabmini', $this->session->has('download_tabmini') && $this->session->get('download_id') == $this->request->gethtml('download_id') ? $this->session->get('download_tabmini') : 0);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('download_id', $this->request->gethtml('download_id'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$download_data = array();
		$results = $this->modelDownload->get_languages();
	foreach ($results as $result) {
		if($result['language_status'] =='1'){
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

				if ($result['language_id'] == $this->language->getId()) {
					if (isset($download_description_info[$result['language_id']])) {
						if ($download_description_info[$result['language_id']]['name'] != NULL) {
							$name_last = $download_description_info[$result['language_id']]['name'];
						} else {
							$name_last = $this->session->get('name_last_download');
						}
					} else {
						$name_last = @$download_description_info['name'];
					}

					if (strlen($name_last) > 26) {
						$name_last = substr($name_last , 0, 23) . '...';
					}
					$this->session->set('name_last_download', $name_last);
					$this->session->set('last_download', $this->url->ssl('download', 'update', array('download_id' => $this->request->gethtml('download_id'))));
				}
		}
	}

	$view->set('downloads', $download_data);

	$this->session->set('last_download_id', $this->request->gethtml('download_id'));

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
		} else {
			foreach ($this->request->gethtml('language', 'post') as $value) {
				if (!$this->validate->strlen($value['name'],1,64)) {
					$this->error['name'] = $this->language->get('error_name');
				}
			}
			if ($this->upload->has('download')) {
				$filename = $this->request->gethtml('fileName', 'post');
				
				if (!$this->validate->strlen($this->upload->getName('download'),1,128)) {
					$this->error['file'] = $this->language->get('error_filename');
				}
				if (substr(strrchr($this->upload->getName('download'), '.'), 1) == 'php') {
					$this->error['file'] = $this->language->get('error_filetype');
				}
				if ($this->upload->hasError('download')) {
					$this->error['message'] = $this->upload->getError('download');
				}
				if (!$this->error) {
					if (!$this->upload->save('download', DIR_DOWNLOAD . $filename)) {
						$this->error['file'] = $this->language->get('error_upload');
					}
				}
			}
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
			$this->error['message'] = $product_info['total'] == 1 ? $this->language->get('error_product') : $this->language->get('error_products', $product_info['total']) ;
			$product_list = $this-> modelDownload->get_downloadToProducts();
				$this->error['message'] .= '<br>';
				foreach ($product_list as $product) {
					$this->error['message'] .= '<a href="' . $this->url->ssl('product', 'update', array('product_id' => $product['product_id'])) . '">' . $product['name'] . '</a>&nbsp;';
				}
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		} 
	}
	function get_uploadable(){
		$upload_max = convert_bytes(ini_get('upload_max_filesize'));
		$post_max = convert_bytes(ini_get('post_max_size'));
		$memory_limit = convert_bytes(ini_get('memory_limit'));
		return $uploadable = floor(min($upload_max, $post_max, $memory_limit)/1048576).'MB';
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
	function tab() {
		if ($this->request->isPost()) {
			if ($this->request->has('activeTab', 'post')) {
				$this->session->set('download_tab', $this->request->sanitize('activeTab', 'post'));
				$this->session->set('download_id', $this->request->sanitize('id', 'post'));
				if ($this->request->has('activeTabmini', 'post')) {
					$this->session->set('download_tabmini', $this->request->sanitize('activeTabmini', 'post'));
				}
				$output = array('status' => true);
			} else {
				$output = array('status' => false);
			}
			echo json_encode($output);
		}
	}
}
?>

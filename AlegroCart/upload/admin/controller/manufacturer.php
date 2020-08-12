<?php   //Admin Manufacturer AlegroCart 
class ControllerManufacturer extends Controller {

	public $error = array();
	public function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->cache		=& $locator->get('cache');
		$this->config		=& $locator->get('config');
		$this->currency		=& $locator->get('currency');
		$this->generate_seo	=& $locator->get('generateseo');
		$this->image		=& $locator->get('image');   
		$this->language		=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request		=& $locator->get('request');
		$this->response		=& $locator->get('response');
		$this->session		=& $locator->get('session');
		$this->template		=& $locator->get('template');
		$this->url		=& $locator->get('url');
		$this->user		=& $locator->get('user'); 
		$this->validate		=& $locator->get('validate');
		$this->modelManufacturer= $model->get('model_admin_manufacturer');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('manufacturer');

		$this->language->load('controller/manufacturer.php');
	}

	protected function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function insert() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('name', 'post') && $this->validateForm()) {
			$url_alias = $this->config->get('config_url_alias');
			$url_seo = $this->config->get('config_seo');
			$this->modelManufacturer->insert_manufacturer();
			$manufacturer_id = $this->modelManufacturer->get_last_id();
			if($url_alias && $url_seo){
					$this->manufacturer_seo($manufacturer_id,$this->request->gethtml('name', 'post'));
					$this->cache->delete('url');
				}
			foreach ($this->request->gethtml('productdata', 'post', array()) as $product_id) {
					$this->modelManufacturer->write_product($product_id, $manufacturer_id);
				}
			$name_last = $this->request->get('name', 'post');
			if (strlen($name_last) > 26) {
				$name_last = substr($name_last , 0, 23) . '...';
			}
			$this->session->set('name_last_manufacturer', $name_last);
			$this->session->set('last_manufacturer', $this->url->ssl('manufacturer', 'update', array('manufacturer_id' => $manufacturer_id)));
			$this->session->set('last_manufacturer_id', $manufacturer_id);
			$this->cache->delete('manufacturer');
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('manufacturer'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());

		$this->session->delete('name_last_manufacturer');
		$this->session->delete('last_manufacturer');

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('name', 'post') && $this->validateForm() && $this->validateModification()) {
			$url_alias = $this->config->get('config_url_alias');
			$url_seo = $this->config->get('config_seo');
			$this->modelManufacturer->update_manufacturer();
			if($url_alias && $url_seo){
				$this->delete_manufacturer_seo($this->request->gethtml('manufacturer_id'));
				$this->manufacturer_seo($this->request->gethtml('manufacturer_id'),$this->request->gethtml('name', 'post'));
				$this->cache->delete('url');
			}
			$this->modelManufacturer->delete_manufacturerToProduct();
			foreach ($this->request->gethtml('productdata', 'post', array()) as $product_id) {
				$this->modelManufacturer->update_product($product_id);
			}
			$this->cache->delete('manufacturer');
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('manufacturer', 'update', array('manufacturer_id' => $this->request->gethtml('manufacturer_id', 'post'))));
			} else {
				$this->response->redirect($this->url->ssl('manufacturer'));
			}

		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function delete() {
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
			$this->session->delete('name_last_manufacturer');
			$this->session->delete('last_manufacturer');
			$this->response->redirect($this->url->ssl('manufacturer'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	private function getList() {
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
			'align' => 'action'
		);

		$results = $this->modelManufacturer->get_page();
		$rows = array();
		foreach ($results as $result) {
			$last = $result['manufacturer_id'] == $this->session->get('last_manufacturer_id') ? 'last_visited': '';
			$cell = array();
			$cell[] = array(
				'value' => $result['name'],
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'image' => $this->image->resize($result['filename'], '26', '26'),
				'previewimage' => $this->image->resize($result['filename'], $this->config->get('config_image_width'), $this->config->get('config_image_height')),
				'title' => $result['filename'],
				'align' => 'right'
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
				'align'  => 'action'
			);
			$rows[] = array('cell' => $cell);
		}

		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def); 
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));
		$view->set('text_results', $this->modelManufacturer->get_text_results());

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
		$view->set('controller', 'manufacturer');
		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

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

		$view->set('insert', $this->url->ssl('manufacturer', 'insert'));
		$view->set('last', $this->url->getLast('manufacturer'));

		$view->set('pages', $this->modelManufacturer->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	private function getForm() {
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_form_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));

		$view->set('entry_name', $this->language->get('entry_name'));
		$view->set('entry_image', $this->language->get('entry_image'));
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));
		$view->set('entry_product', $this->language->get('entry_product'));

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
		$view->set('tab_product', $this->language->get('tab_product'));
		$view->set('explanation_multiselect', $this->language->get('explanation_multiselect'));

		$view->set('error', @$this->error['message']);
		$view->set('error_name', @$this->error['name']);
		$view->set('error_sort_order', @$this->error['sort_order']);
		$view->set('error_image', @$this->error['image']);
		$view->set('error_assigned', @$this->error['assigned']);

		if(!@$this->error['message']){
			$view->set('error', @$this->error['warning']);
		}

		$view->set('action', $this->url->ssl('manufacturer', $this->request->gethtml('action'), array('manufacturer_id' => $this->request->gethtml('manufacturer_id'))));

		$view->set('insert', $this->url->ssl('manufacturer', 'insert'));
		$view->set('cancel', $this->url->ssl('manufacturer'));
		$view->set('last', $this->url->getLast('manufacturer'));

		if ($this->request->gethtml('manufacturer_id')) {
			$view->set('update', $this->url->ssl('manufacturer', 'update', array('manufacturer_id' => $this->request->gethtml('manufacturer_id'))));
				$view->set('delete', $this->url->ssl('manufacturer', 'delete', array('manufacturer_id' => $this->request->gethtml('manufacturer_id'),'manufacturer_validation' =>$this->session->get('manufacturer_validation'))));
		}

		$view->set('tab', $this->session->has('manufacturer_tab') && $this->session->get('manufacturer_id') == $this->request->gethtml('manufacturer_id') ? $this->session->get('manufacturer_tab') : 0);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('manufacturer_id', $this->request->gethtml('manufacturer_id'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (($this->request->gethtml('manufacturer_id')) && (!$this->request->isPost())) {
			$manufacturer_info = $this->modelManufacturer->get_manufacturer();
			$this->session->set('manufacturer_date_modified', $manufacturer_info['date_modified']);
		}

		if ($this->request->has('name', 'post')) {
			if ($this->request->gethtml('name', 'post') != NULL) {
				$name_last = $this->request->get('name', 'post');
			} else {
				$name_last = $this->session->get('name_last_manufacturer');
			}
		} else {
			$name_last = @$manufacturer_info['name'];
		}
		if (strlen($name_last) > 26) {
			$name_last = substr($name_last , 0, 23) . '...';
		}
		$this->session->set('name_last_manufacturer', $name_last);
		$this->session->set('last_manufacturer', $this->url->ssl('manufacturer', 'update', array('manufacturer_id' => $this->request->gethtml('manufacturer_id'))));
		$this->session->set('last_manufacturer_id', $this->request->gethtml('manufacturer_id'));

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
				'previewimage'    => $this->image->resize($result['filename'], $this->config->get('config_image_width'), $this->config->get('config_image_height')),
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

		$product_data = array();
		$results = $this->modelManufacturer->get_products();
		$affected_products = $this-> modelManufacturer->get_manufacturerToValidProducts();
		$affected_product_list = array();
		foreach ($affected_products as $affected_product) {
			$affected_product_list[] = $affected_product['product_id'];
		}
		foreach ($results as $result) {
			if ($this->request->gethtml('manufacturer_id') && !$this->request->isPost()) {
				if(in_array($result['product_id'], $affected_product_list)){
					$product_info = $this->modelManufacturer->get_manufacturerToProduct($result['product_id']);
				} else {
					$product_info = NULL;
				}
			}
			$product_data[] = array(
				'product_id'	=> $result['product_id'],
				'previewimage'	=> $this->image->resize($result['filename'], $this->config->get('config_image_width'), $this->config->get('config_image_height')),
				'name'		=> $result['name'],
				'productdata'	=> (isset($product_info) ? $product_info : in_array($result['product_id'], $this->request->gethtml('productdata', 'post', array()))));
		}
		$view->set('productdata', $product_data);

		return $view->fetch('content/manufacturer.tpl');
	}

	private function validateForm() {
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

	private function validateModification() {

		if ($manufacturer_info = $this->modelManufacturer->get_manufacturer()) {
			if ($manufacturer_info['date_modified'] != $this->session->get('manufacturer_date_modified')) {
				$manufacturer_info_log = $this->modelManufacturer->get_modified_log($manufacturer_info['date_modified']);

				if ($manufacturer_info_log['name'] != $this->request->gethtml('name', 'post')) {
					$this->error['name'] = $this->language->get('error_modified', $manufacturer_info_log['name']);
				}

				if ($manufacturer_info_log['sort_order'] != $this->request->gethtml('sort_order', 'post')) {
					$this->error['sort_order'] = $this->language->get('error_modified', $manufacturer_info_log['sort_order']);
				}

				if ($manufacturer_info_log['image_id'] != $this->request->gethtml('image_id', 'post')) {
					$this->error['image'] = $this->language->get('error_modified', $manufacturer_info_log['title']);
				}

				$products_to_manufacturer_log = $this->modelManufacturer->get_assigned_log($manufacturer_info['date_modified']);
				$assigned = array();
				$assigned_name = array();
				foreach ($products_to_manufacturer_log as $product_to_manufacturer_log) {
					$assigned[] = $product_to_manufacturer_log['product_id'];
					$assigned_name[] = $product_to_manufacturer_log['name'];
				}
				if ($assigned != $this->request->gethtml('productdata', 'post', array())) {
					$this->error['assigned'] = $this->language->get('error_modified', $assigned_name ? implode(', ', $assigned_name) : $this->language->get('error_none'));
				}
				$this->session->set('manufacturer_date_modified', $manufacturer_info_log['date_modified']);
			}
		} else {
			$manufacturer_info_log = $this->modelManufacturer->get_deleted_log();
			$this->session->set('message', $this->language->get('error_deleted', $manufacturer_info_log['modifier']));
			$this->response->redirect($this->url->ssl('manufacturer'));
		}

		if (@$this->error){
			$this->error['warning'] = $this->language->get('error_modifier', $manufacturer_info_log['modifier']);
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function enableDelete(){
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

	private function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'manufacturer')) {//**
			$this->error['message'] = $this->language->get('error_permission');  
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if(($this->session->get('manufacturer_validation') != $this->request->sanitize('manufacturer_validation')) || (strlen($this->session->get('manufacturer_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('manufacturer_validation');
		if (!$this->user->hasPermission('modify', 'manufacturer')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		$product_info = $this->modelManufacturer->check_products();
		if ($product_info['total']) {
			$this->error['message'] = $product_info['total'] == 1 ? $this->language->get('error_product') : $this->language->get('error_products', $product_info['total']) ;
			$product_list = $this-> modelManufacturer->get_manufacturerToProducts();
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

	protected function page() {
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

	private function manufacturer_seo($manufacturer_id,$manufacturer_name){
		$query_path = 'controller=manufacturer&manufacturer_id=' . $manufacturer_id;
		$alias = '';
		$alias .= $this->generate_seo->clean_alias($manufacturer_name);
		$alias .= '.html';
		$this->generate_seo->_insert_url_alias($query_path, $alias);
	}

	private function delete_manufacturer_seo($manufacturer_id){
		$query_path = 'controller=manufacturer&manufacturer_id=' . $manufacturer_id;
		$this->modelManufacturer->delete_SEO($query_path);
	}

	protected function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}

	protected function tab() {
		if ($this->request->isPost()) {
			if ($this->request->has('activeTab', 'post')) {
				$this->session->set('manufacturer_tab', $this->request->sanitize('activeTab', 'post'));
				$this->session->set('manufacturer_id', $this->request->sanitize('id', 'post'));
			}
		}
	}
}
?>

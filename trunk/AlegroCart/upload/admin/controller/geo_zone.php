<?php // GeoZone AlegroCart
class ControllerGeoZone extends Controller {
	var $error = array();
 	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->cache    	=& $locator->get('cache');
		$this->config   	=& $locator->get('config');
		$this->currency 	=& $locator->get('currency');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session 		=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user'); 
		$this->validate 	=& $locator->get('validate');
		$this->modelGeoZone = $model->get('model_admin_geozone');
		
		$this->language->load('controller/geo_zone.php');
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
			$this->modelGeoZone->insert_geozone();
			$this->cache->delete('geo_zone');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('geo_zone'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('name', 'post') && $this->validateForm()) {
			$this->modelGeoZone->update_geozone();
			$this->cache->delete('geo_zone');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('geo_zone'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('geo_zone_id')) && ($this->validateDelete())) {
			$this->modelGeoZone->delete_geozone();
			$this->cache->delete('geo_zone');
			$this->session->set('message', $this->language->get('text_message'));
 
			$this->response->redirect($this->url->ssl('geo_zone'));
		}
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function getList() {
		$this->session->set('geo_zone_validation', md5(time()));
		$cols = array();
		
		$cols[] = array(
			'name'  => $this->language->get('column_zones'),
			'folder_help' => $this->language->get('text_folder_help'),
			'align' => 'center'
		);
		
		$cols[] = array(
			'name'  => $this->language->get('column_name'),
			'sort'  => 'name',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_description'),
			'sort'  => 'description',
			'align' => 'left'
		);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'action'
    	);
		
		$results = $this->modelGeoZone->get_page();
		$rows = array();
		foreach ($results as $result) {
			$cell = array();
      		$cell[] = array(
        		'icon'  => $this->modelGeoZone->check_children($result['geo_zone_id']) ? 'folderO.png' : 'folder.png',
        		'align' => 'center',
				'path'  => $this->url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $result['geo_zone_id']))
		  	);
			$cell[] = array(
				'value' => $result['name'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $result['description'],
				'align' => 'left'
			);
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('geo_zone', 'update', array('geo_zone_id' => $result['geo_zone_id']))
      		);
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('geo_zone', 'delete', array('geo_zone_id' => $result['geo_zone_id'],'geo_zone_validation' =>$this->session->get('geo_zone_validation')))
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

		$view->set('text_results', $this->modelGeoZone->get_text_results());

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
		 
		$view->set('action', $this->url->ssl('geo_zone', 'page'));
		$view->set('action_delete', $this->url->ssl('geo_zone', 'enableDelete'));

		$view->set('search', $this->session->get('geo_zone.search'));
		$view->set('sort', $this->session->get('geo_zone.sort'));
		$view->set('order', $this->session->get('geo_zone.order'));
		$view->set('page', $this->session->get('geo_zone.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $this->url->ssl('geo_zone'));
		$view->set('insert', $this->url->ssl('geo_zone', 'insert'));

		$view->set('pages', $this->modelGeoZone->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('entry_name', $this->language->get('entry_name'));
		$view->set('entry_description', $this->language->get('entry_description'));
		
		$view->set('explanation_name', $this->language->get('explanation_name'));
		$view->set('explanation_description', $this->language->get('explanation_description'));

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
		$view->set('error_description', @$this->error['description']);

		$view->set('action', $this->url->ssl('geo_zone', $this->request->gethtml('action'), array('geo_zone_id' => $this->request->gethtml('geo_zone_id'))));

		$view->set('list', $this->url->ssl('geo_zone'));
		$view->set('insert', $this->url->ssl('geo_zone', 'insert'));
		$view->set('cancel', $this->url->ssl('geo_zone'));
		
		if ($this->request->gethtml('geo_zone_id')) {
			$view->set('update', $this->url->ssl('geo_zone', 'update', array('geo_zone_id' => $this->request->gethtml('geo_zone_id'))));
			$view->set('delete', $this->url->ssl('geo_zone', 'delete', array('geo_zone_id' => $this->request->gethtml('geo_zone_id'),'geo_zone_validation' =>$this->session->get('geo_zone_validation'))));
		}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (($this->request->gethtml('geo_zone_id')) && (!$this->request->isPost())) {
			$geo_zone_info = $this->modelGeoZone->get_geozone();
		}

		if ($this->request->has('name', 'post')) {
			$view->set('name', $this->request->gethtml('name', 'post'));
		} else {
			$view->set('name', @$geo_zone_info['name']);
		}

		if ($this->request->has('description', 'post')) {
			$view->set('description', $this->request->gethtml('description', 'post'));
		} else {
			$view->set('description', @$geo_zone_info['description']);
		}

		return $view->fetch('content/geo_zone.tpl');
	}
	
	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'geo_zone')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
        if (!$this->validate->strlen($this->request->gethtml('name', 'post'),1,32)) {
			$this->error['name'] = $this->language->get('error_name');
		}
        if (!$this->validate->strlen($this->request->gethtml('description', 'post'),1,255)) {
			$this->error['description'] = $this->language->get('error_description');
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
			$this->response->redirect($this->url->ssl('geo_zone'));//**
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('geo_zone'));//**
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'geo_zone')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}
	function validateDelete() {
		if(($this->session->get('geo_zone_validation') != $this->request->sanitize('geo_zone_validation')) || (strlen($this->session->get('geo_zone_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('geo_zone_validation');
		if (!$this->user->hasPermission('modify', 'geo_zone')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		$tax_rate_info = $this->modelGeoZone->check_tax();
		if ($tax_rate_info['total']) {
			$this->error['message'] = $this->language->get('error_tax_rate', $tax_rate_info['total']);
		}

		$zone_to_geo_zone_info = $this->modelGeoZone->check_zoneToGeozone();
		if ($zone_to_geo_zone_info['total']) {
			$this->error['message'] = $this->language->get('error_zone_to_geo_zone', $zone_to_geo_zone_info['total']);
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}	
	
	function page() {
		if ($this->request->has('search', 'post')) {
			$this->session->set('geo_zone.search', $this->request->gethtml('search', 'post'));
		}

		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('geo_zone.page', $this->request->gethtml('page', 'post'));
		}

		if ($this->request->has('sort', 'post')) {
			$this->session->set('geo_zone.order', (($this->session->get('geo_zone.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('geo_zone.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($this->request->has('sort', 'post')) {
			$this->session->set('geo_zone.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('geo_zone'));
	}	
}
?>

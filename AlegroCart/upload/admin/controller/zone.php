<?php // Zone AlegroCart
class ControllerZone extends Controller {
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
		$this->modelZone	= $model->get('model_admin_zone');
		
		$this->language->load('controller/zone.php');
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
			$this->modelZone->insert_zone();
			$this->cache->delete('zone');
			$this->session->set('message', $this->language->get('text_message'));
			
			$this->response->redirect($this->url->ssl('zone'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('name', 'post') && $this->validateForm()) {
			$this->modelZone->update_zone();
			$this->cache->delete('zone');
			$this->session->set('message', $this->language->get('text_message'));
			
			$this->response->redirect($this->url->ssl('zone'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('zone_id')) && ($this->validateDelete())) {
			$this->modelZone->delete_zone();
			$this->cache->delete('zone');
			$this->session->set('message', $this->language->get('text_message'));
			
			$this->response->redirect($this->url->ssl('zone'));
		}
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function changeStatus() { 
		
		if (($this->request->has('stat_id')) && ($this->request->has('stat')) && $this->validateChangeStatus()) {

			$this->modelZone->change_zone_status($this->request->gethtml('stat'), $this->request->gethtml('stat_id'));
			$this->cache->delete('zone');
		}
	
	}

	function getList() {
		$this->session->set('zone_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_country'),
			'sort'  => 'c.name',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_name'),
			'sort'  => 'z.name',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_code'),
			'sort'  => 'z.code',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_zone_status'),
			'sort'  => 'z.zone_status',
			'align' => 'right'
		);		
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'action'
    	);
		
		$results = $this->modelZone->get_page();
		$rows = array();
		foreach ($results as $result) {
			$cell = array();
			$cell[] = array(
				'value' => $result['country'],
				'align' => 'left'
			);
			$cell[] = array(
				'value'   => $result['name'],
				'align'   => 'left',
				'default' => ($result['zone_id'] == $this->config->get('config_zone_id'))
			);
			$cell[] = array(
				'value' => $result['code'],
				'align' => 'center'
			);
			if ($this->validateChangeStatus() && $this->config->get('config_zone_id') !== $result['zone_id']) {
			$cell[] = array(
				'status'  => $result['zone_status'],
				'text' => $this->language->get('button_status'),
				'align' => 'right',
				'status_id' => $result['zone_id'],
				'status_controller' => 'zone'
			);

			} else {

			$cell[] = array(
				'icon'  => ($result['zone_status'] ? 'enabled.png' : 'disabled.png'),
				'align' => 'right'
			);
			}		
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('zone', 'update', array('zone_id' => $result['zone_id']))
      		);
			
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('zone', 'delete', array('zone_id' => $result['zone_id'],'zone_validation' =>$this->session->get('zone_validation')))
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

		$view->set('text_default', $this->language->get('text_default'));
		$view->set('text_results', $this->modelZone->get_text_results());

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
		
		$view->set('action', $this->url->ssl('zone', 'page'));
		$view->set('action_delete', $this->url->ssl('zone', 'enableDelete'));
		
		$view->set('search', $this->session->get('zone.search'));
		$view->set('sort', $this->session->get('zone.sort'));
		$view->set('order', $this->session->get('zone.order'));
		$view->set('page', $this->session->get('zone.page'));
 
		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $this->url->ssl('zone'));
		$view->set('insert', $this->url->ssl('zone', 'insert'));

		$view->set('pages', $this->modelZone->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_form_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

    	$view->set('text_enabled', $this->language->get('text_enabled'));
    	$view->set('text_disabled', $this->language->get('text_disabled'));
		$view->set('entry_name', $this->language->get('entry_name'));
		$view->set('entry_zone_status', $this->language->get('entry_zone_status'));
		$view->set('entry_code', $this->language->get('entry_code'));
		$view->set('entry_country', $this->language->get('entry_country'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('error', @$this->error['message']);
		$view->set('error_name', @$this->error['name']);

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('action', $this->url->ssl('zone', $this->request->gethtml('action'), array('zone_id' => $this->request->gethtml('zone_id'))));
 
		$view->set('list', $this->url->ssl('zone'));
		$view->set('insert', $this->url->ssl('zone', 'insert'));
		$view->set('cancel', $this->url->ssl('zone'));
		
		if ($this->request->gethtml('zone_id')) {
			$view->set('update', $this->url->ssl('zone', 'update', array('zone_id' => $this->request->gethtml('zone_id'))));
			$view->set('delete', $this->url->ssl('zone', 'delete', array('zone_id' => $this->request->gethtml('zone_id'),'zone_validation' =>$this->session->get('zone_validation'))));
		}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (($this->request->gethtml('zone_id')) && (!$this->request->isPost())) {
			$zone_info = $this->modelZone->get_zone();
		}

		if ($this->request->has('name', 'post')) {
			$view->set('name', $this->request->gethtml('name', 'post'));
		} else {
			$view->set('name', @$zone_info['name']);
		}

		if ($this->request->has('zone_status', 'post')) {
      		$view->set('zone_status', $this->request->gethtml('zone_status', 'post'));
    	} else {
      		$view->set('zone_status', @$zone_info['zone_status']);
    	}				
		
		if ($this->request->has('code', 'post')) {
			$view->set('code', $this->request->gethtml('code', 'post'));
		} else {
			$view->set('code', @$zone_info['code']);
		}

		if ($this->request->has('country_id', 'post')) {
			$view->set('country_id', $this->request->gethtml('country_id', 'post'));
		} else {
			$view->set('country_id', @$zone_info['country_id']);
		}

		$view->set('countries', $this->modelZone->get_countries());

		return $view->fetch('content/zone.tpl');
	}

	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'zone')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
        if (!$this->validate->strlen($this->request->gethtml('name', 'post'),1,32)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		if($this->request->has('zone_status', 'post') && !$this->request->gethtml('zone_status','post')){
			$address_info = $this->modelZone->check_address();
			if ($address_info['total']) {
				$this->error['message'] = $this->language->get('error_address', $address_info['total']);
			}
			$zone_to_geo_zone_info = $this->modelZone->check_zone_to_geo();
			if ($zone_to_geo_zone_info['total']) {
				$this->error['message'] = $this->language->get('error_zone_to_geo_zone', $zone_to_geo_zone_info['total']);
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
			$this->response->redirect($this->url->ssl('zone'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('zone'));
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'zone')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}
	function validateDelete() {
		if(($this->session->get('zone_validation') != $this->request->sanitize('zone_validation')) || (strlen($this->session->get('zone_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('zone_validation');
		if (!$this->user->hasPermission('modify', 'zone')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if ($this->config->get('config_zone_id') == $this->request->gethtml('zone_id')) {
			$this->error['message'] = $this->language->get('error_default');
		}
		$address_info = $this->modelZone->check_address();
		if ($address_info['total']) {
			$this->error['message'] = $address_info['total'] ==1 ? $this->language->get('error_address') : $this->language->get('error_addresses', $address_info['total']);
			$address_list = $this-> modelZone->get_zoneToAddress();
				$this->error['message'] .= '<br>';
				foreach ($address_list as $address) {
					$this->error['message'] .= '<a href="' . $this->url->ssl('customer', 'update', array('customer_id' => $address['customer_id'])) . '">' . $address['firstname'] . '&nbsp;' . $address['lastname'] .'</a>&nbsp;';
				}
		}
		$zone_to_geo_zone_info = $this->modelZone->check_zone_to_geo();
		if ($zone_to_geo_zone_info['total']) {
			$this->error['message'] = $zone_to_geo_zone_info['total'] ==1 ? $this->language->get('error_zone_to_geo_zone') : $this->language->get('error_zone_to_geo_zones', $zone_to_geo_zone_info['total']);
			$zone_to_geo_zone_list = $this-> modelZone->get_zoneToZoneToGeoZone();
				$this->error['message'] .= '<br>';
				foreach ($zone_to_geo_zone_list as $geo_zone) {
					$this->error['message'] .= '<a href="' . $this->url->ssl('zone_to_geo_zone', '', array('geo_zone_id' => $geo_zone['geo_zone_id'])) . '">' . $geo_zone['name'] . '</a>&nbsp;';
				}
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function validateChangeStatus(){
		if (!$this->user->hasPermission('modify', 'zone')) {
	      		return FALSE;
	    	}  else {
			return TRUE;
		}
	}

	function page() {
		if ($this->request->has('search', 'post')) {
			$this->session->set('zone.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('zone.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('zone.order', (($this->session->get('zone.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('zone.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('zone.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('zone'));
	}	
}
?>

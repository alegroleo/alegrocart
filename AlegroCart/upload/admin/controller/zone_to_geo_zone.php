<?php  // ZoneToGeoZone AlergoCart
class ControllerZoneToGeoZone extends Controller {
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
		$this->modelZonetoGeo = $model->get('model_admin_zone_geozone');
		
		$this->language->load('controller/zone_to_geo_zone.php');
	}   
  	function index() {
    	$this->template->set('title', $this->language->get('heading_title'));
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
		
    	$this->response->set($this->template->fetch('layout.tpl'));	
  	}

  	function insert() {
    	$this->template->set('title', $this->language->get('heading_title'));
		
    	if ($this->request->isPost() && $this->request->has('country_id', 'post') && $this->validateForm()) {
	  		$this->modelZonetoGeo->insert_zoneToGeozone();
			$this->session->set('message', $this->language->get('text_message'));

	  		$this->response->redirect($this->url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $this->request->gethtml('geo_zone_id'))));
    	}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function update() {
    	$this->template->set('title', $this->language->get('heading_title'));
	
    	if ($this->request->isPost() && $this->request->has('country_id', 'post') && $this->validateForm()) {
	  		$this->modelZonetoGeo->update_zoneToGeozone();
			$this->session->set('message', $this->language->get('text_message'));
	  		$query = array(
	    		'zone_to_geo_zone_id' => $this->request->gethtml('zone_to_geo_zone_id'),
	    		'geo_zone_id'         => $this->request->gethtml('geo_zone_id')
	  		);
	  	
	  		$this->response->redirect($this->url->ssl('zone_to_geo_zone', FALSE, $query));	  
    	}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));  
  	}

  	function delete() {
    	$this->template->set('title', $this->language->get('heading_title'));
		
    	if (($this->request->gethtml('zone_to_geo_zone_id')) && ($this->validateDelete())) {
      		$this->modelZonetoGeo->delete_zoneToGeozone();
			$this->session->set('message', $this->language->get('text_message'));

	  		$this->response->redirect($this->url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $this->request->gethtml('geo_zone_id'))));
    	}
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}
	
	function addDeleteAll(){
		if ($this->request->has('geo_zone_id') && $this->validateEnableAll()){
			$geo_zone_id = $this->request->gethtml('geo_zone_id');
			$countrys = $this->modelZonetoGeo->checkCountries($geo_zone_id);
			$country_info = $this->modelZonetoGeo->get_countries();
			if (!$countrys){
				foreach($country_info as $country){
					$this->modelZonetoGeo->insertCountry($geo_zone_id, $country['country_id']);
				}
			} else {
				$this->modelZonetoGeo->deleteAllZones($this->request->gethtml('geo_zone_id'));
			}
		} else {
			$this->session->set('message', @$this->error['message']);
		}

		$this->response->redirect($this->url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $this->request->gethtml('geo_zone_id'))));
	}
	
	function validateEnableALL(){
		if (!$this->user->hasPermission('modify', 'zone_to_geo_zone')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}
	
  	function getList() {
		$this->session->set('zone_geo_validation', md5(time()));
		if($this->session->get('geo_zone_id') != $this->request->gethtml('geo_zone_id')){
			$this->session->delete('zone_to_geo_zone.search');
		}
		$this->session->set('geo_zone_id', $this->request->gethtml('geo_zone_id'));
    	$cols = array();
    	$cols[] = array(
      		'name'  => $this->language->get('column_country'),
      		'sort'  => 'c.name',
      		'align' => 'left'
    	);
    	$cols[] = array(
      		'name'  => $this->language->get('column_zone'),
      		'sort'  => 'z.name',
      		'align' => 'left'
    	);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'action'
    	);

		$results = $this->modelZonetoGeo->get_page();
    	$rows = array();
    	foreach ($results as $result) {
      		$cell = array();
      		$cell[] = array(
        		'value' => $result['name'],
        		'align' => 'left'
      		);
      		$cell[] = array(
        		'value' => ($result['zone'] ? $result['zone'] : $this->language->get('text_all_zones')),
        		'align' => 'left'
      		);
			$query = array(
	    		'zone_to_geo_zone_id' => $result['zone_to_geo_zone_id'],
        		'geo_zone_id'         => $this->request->gethtml('geo_zone_id')
      		);
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('zone_to_geo_zone', 'update', $query)
      		);
			if($this->session->get('enable_delete')){
				$query = array(
					'zone_to_geo_zone_id' => $result['zone_to_geo_zone_id'],
					'geo_zone_id'         => $this->request->gethtml('geo_zone_id'),
					'zone_geo_validation' =>$this->session->get('zone_geo_validation')
				);
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('zone_to_geo_zone', 'delete', $query)
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
    	$view->set('text_results', $this->modelZonetoGeo->get_text_results());

    	$view->set('entry_page', $this->language->get('entry_page'));
    	$view->set('entry_search', $this->language->get('entry_search'));

    	$view->set('button_list', $this->language->get('button_list'));
    	$view->set('button_insert', $this->language->get('button_insert'));
    	$view->set('button_update', $this->language->get('button_update'));
    	$view->set('button_delete', $this->language->get('button_delete'));
    	$view->set('button_save', $this->language->get('button_save'));
    	$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_refresh', $this->language->get('button_enable_disable')); 
		$view->set('button_enable_delete', $this->language->get('button_enable_delete'));
	$view->set('button_print', $this->language->get('button_print'));

		$view->set('error', @$this->error['message']);
		
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action_refresh', $this->url->ssl('zone_to_geo_zone', 'addDeleteAll', array('geo_zone_id' => $this->request->gethtml('geo_zone_id')))); 
    	$view->set('action', $this->url->ssl('zone_to_geo_zone', 'page', array('geo_zone_id' => $this->request->gethtml('geo_zone_id'))));
		$view->set('action_delete', $this->url->ssl('zone_to_geo_zone', 'enableDelete', array('geo_zone_id' => $this->request->gethtml('geo_zone_id'))));
		
    	$view->set('previous', $this->url->ssl('geo_zone', FALSE, array('geo_zone_id' => $this->request->gethtml('geo_zone_id'))));
   
    	$view->set('search', $this->session->get('zone_to_geo_zone.search'));
    	$view->set('sort', $this->session->get('zone_to_geo_zone.sort'));
    	$view->set('order', $this->session->get('zone_to_geo_zone.order'));
  		$view->set('page', $this->session->get('zone_to_geo_zone.' . $this->request->gethtml('geo_zone_id') . '.page'));
    	
		$view->set('cols', $cols);
    	$view->set('rows', $rows);
  
    	$view->set('list', $this->url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $this->request->gethtml('geo_zone_id'))));
    	$view->set('insert', $this->url->ssl('zone_to_geo_zone', 'insert', array('geo_zone_id' => $this->request->gethtml('geo_zone_id'))));
    	$view->set('pages', $this->modelZonetoGeo->get_pagination());
    	
		return $view->fetch('content/list.tpl');
  	}

  	function getForm() {
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('heading_description', $this->language->get('heading_description'));

    	$view->set('text_select', $this->language->get('text_select'));

    	$view->set('entry_country', $this->language->get('entry_country'));
    	$view->set('entry_zone', $this->language->get('entry_zone'));

    	$view->set('button_list', $this->language->get('button_list'));
    	$view->set('button_insert', $this->language->get('button_insert'));
    	$view->set('button_update', $this->language->get('button_update'));
    	$view->set('button_delete', $this->language->get('button_delete'));
    	$view->set('button_save', $this->language->get('button_save'));
    	$view->set('button_cancel', $this->language->get('button_cancel'));
	$view->set('button_print', $this->language->get('button_print'));

    	$view->set('tab_general', $this->language->get('tab_general'));
    
		$view->set('error', @$this->error['message']);
    
		$query = array(
      		'zone_to_geo_zone_id' => $this->request->gethtml('zone_to_geo_zone_id'),
	  		'geo_zone_id'         => $this->request->gethtml('geo_zone_id')
    	); 
  
    	$view->set('action', $this->url->ssl('zone_to_geo_zone', $this->request->gethtml('action'), $query));
  
    	$view->set('list', $this->url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $this->request->gethtml('geo_zone_id'))));
    	$view->set('insert', $this->url->ssl('zone_to_geo_zone', 'insert', array('geo_zone_id' => $this->request->gethtml('geo_zone_id'))));
		$view->set('cancel', $this->url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $this->request->gethtml('geo_zone_id'))));
  
    	if ($this->request->gethtml('zone_to_geo_zone_id')) {
      		$query = array(
        		'zone_to_geo_zone_id' => $this->request->gethtml('zone_to_geo_zone_id'),
	    		'geo_zone_id'         => $this->request->gethtml('geo_zone_id'),
				'zone_geo_validation' =>$this->session->get('zone_geo_validation')
      		);
	  
      		$view->set('update', 'enable');
	  		$view->set('delete', $this->url->ssl('zone_to_geo_zone', 'delete', $query));
    	}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

    	if (($this->request->gethtml('zone_to_geo_zone_id')) && (!$this->request->isPost())) {
      		$zone_to_geo_zone_info = $this->modelZonetoGeo->get_zoneToGeoID();
    	}
		if(!isset($zone_to_geo_zone_info)){ // Add to get a default country when adding new geo zone
			$geo_zone_id_info = $this->modelZonetoGeo->get_geo_zone_id();
		}
		
    	if ($this->request->has('country_id', 'post')) {
      		$view->set('country_id', $this->request->gethtml('country_id', 'post'));
		} else if(!isset($zone_to_geo_zone_info)){
			$view->set('country_id', @$geo_zone_id_info['country_id']);		// Default when adding new geo zone
		} else {			
      		$view->set('country_id', @$zone_to_geo_zone_info['country_id']);			
    	}
		
    	if ($this->request->has('zone_id', 'post')) {
      		$view->set('zone_id', $this->request->gethtml('zone_id', 'post'));
		} else {
      		$view->set('zone_id', @$zone_to_geo_zone_info['zone_id']);
    	}
    	$view->set('countries', $this->modelZonetoGeo->get_countries());
    	$view->set('zones', $this->modelZonetoGeo->get_zones());
 
 		return $view->fetch('content/zone_to_geo_zone.tpl');
  	}
  
  	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
    	if (!$this->user->hasPermission('modify', 'zone_to_geo_zone')) {
      		$this->error['message'] = $this->language->get('error_permission');
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
			$this->response->redirect($this->url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $this->request->gethtml('geo_zone_id'))));//**
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $this->request->gethtml('geo_zone_id'))));//**
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'zone_to_geo_zone')) {//**
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}
	
	function validateDelete(){
		if(($this->session->get('zone_geo_validation') != $this->request->sanitize('zone_geo_validation')) || (strlen($this->session->get('zone_geo_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('zone_geo_validation');
		
		if (!$this->user->hasPermission('modify', 'zone_to_geo_zone')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}
 
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
	}
    
	function page() {
		if ($this->request->has('search', 'post')) {
			$this->session->set('zone_to_geo_zone.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('zone_to_geo_zone.' . $this->request->gethtml('geo_zone_id') . '.page', $this->request->gethtml('page', 'post'));			
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('zone_to_geo_zone.order', (($this->session->get('zone_to_geo_zone.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('zone_to_geo_zone.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('zone_to_geo_zone.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $this->request->gethtml('geo_zone_id'))));
	}    
  	
	function zone() {
		$output = '<select name="zone_id">';
		$output .= '<option value="0">' . $this->language->get('text_all_zones') . '</option>';
		$results = $this->modelZonetoGeo->get_country_zones();
		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if ($this->request->gethtml('zone_id') == $result['zone_id']) {
				$output .= ' SELECTED';
			}
			$output .= '>' . $result['name'] . '</option>';
		}
		$output .= '</select>';

		$this->response->set($output);
	} 	
}
?>
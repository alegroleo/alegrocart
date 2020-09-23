<?php  // Country AlegroCart
class ControllerCountry extends Controller {

	public $error = array();

	public function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->cache		=& $locator->get('cache');
		$this->config		=& $locator->get('config');
		$this->currency		=& $locator->get('currency');
		$this->language		=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request		=& $locator->get('request');
		$this->response		=& $locator->get('response');
		$this->session		=& $locator->get('session');
		$this->template		=& $locator->get('template');
		$this->url		=& $locator->get('url');
		$this->user		=& $locator->get('user'); 
		$this->validate		=& $locator->get('validate');
		$this->modelCountry	= $model->get('model_admin_country');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('country');

		$this->language->load('controller/country.php');
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
			$this->modelCountry->insert_country();
			$this->cache->delete('country');
			$this->cache->delete('zone');
			$insert_id = $this->modelCountry->get_last_id();
			$this->session->set('last_country_id', $insert_id);
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('country'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('name', 'post') && $this->validateForm() && $this->validateModification()) {
			$this->modelCountry->update_country();
			// if status is updated, it automatically updates zones to matching status
			$this->modelCountry->update_zones();
			$this->cache->delete('country');
			$this->cache->delete('zone');
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('country', 'update', array('country_id' => $this->request->gethtml('country_id'))));
			} else {
				$this->response->redirect($this->url->ssl('country'));
			}

		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function enableDisable(){
		$this->template->set('title', $this->language->get('heading_title'));

		if($this->validateEnable()){ //permission check
			if ($this->modelCountry->check_status()){
				$status = 0;
			} else {
				$status = 1;
			}
			$this->modelCountry->set_status($status);
			$this->cache->delete('country');
			$this->cache->delete('zone');
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('country'));
		} else {
			$this->session->set('message', @$this->error['message']);
		}
		$this->response->redirect($this->url->ssl('country'));
	}
 
	protected function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('country_id')) && ($this->validateDelete())) {
			$this->modelCountry->delete_country();
			$this->cache->delete('country');
			$this->cache->delete('zone');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('country'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function changeStatus() { 
		if (($this->request->has('stat_id')) && ($this->request->has('stat')) && $this->validateChangeStatus()) {
			$this->modelCountry->change_country_status($this->request->gethtml('stat'), $this->request->gethtml('stat_id'));
			$this->cache->delete('country');
			$this->cache->delete('zone');
		}
	}

	private function getList() {
		$this->session->set('country_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_name'),
			'sort'  => 'name',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_country_status'),
			'sort'  => 'country_status',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_iso_code_2'),
			'sort'  => 'iso_code_2',
			'align' => 'right'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_iso_code_3'),
			'sort'  => 'iso_code_3',
			'align' => 'right'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_action'),
			'align' => 'action'
		);

		$results = $this->modelCountry->get_page();
		$vendors = $this->modelCountry->get_vendorCountries();
			$vendorcountry = array();
			foreach ($vendors as $vendor){
				$vendorcountry[] = $vendor['country_id'];
			}
		$geos = $this->modelCountry->get_zone_to_geo_zoneCountries();
			$geozcountry = array();
			foreach ($geos as $geo){
				$geozcountry[] = $geo['country_id'];
			}
		$rows = array();
		foreach ($results as $result) {
			$last = $result['country_id'] == $this->session->get('last_country_id') ? 'last_visited': '';
			$cell = array();
			$cell[] = array(
				'value'		=> $result['name'],
				'align'		=> 'left',
				'default'	=> ($result['country_id'] == $this->config->get('config_country_id')),
				'vendor'	=> in_array($result['country_id'], $vendorcountry),
				'geo'		=> in_array($result['country_id'], $geozcountry),
				'last'		=> $last
			);
			if ($this->validateChangeStatus() && !in_array($result['country_id'], $geozcountry) && $this->config->get('config_country_id') !== $result['country_id']) {
				$cell[] = array(
					'status'  => $result['country_status'],
					'text' => $this->language->get('button_status'),
					'align' => 'center',
					'status_id' => $result['country_id'],
					'status_controller' => 'country'
				);

			} else {
				$cell[] = array(
					'icon'  => ($result['country_status'] ? 'enabled.png' : 'disabled.png'),
					'align' => 'center',
					'text' => $this->language->get('button_status')
			);
			}
			$cell[] = array(
				'value' => $result['iso_code_2'],
				'align' => 'right',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['iso_code_3'],
				'align' => 'right',
				'last' => $last
			);
			$action = array();
			$action[] = array(
				'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('country', 'update', array('country_id' => $result['country_id']))
		);

			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('country', 'delete', array('country_id' => $result['country_id'],'country_validation' =>$this->session->get('country_validation')))
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

		$view->set('text_default', $this->language->get('text_default'));
		$view->set('text_vendor', $this->language->get('text_vendor'));
		$view->set('text_geo', $this->language->get('text_geo'));
		$view->set('text_results', $this->modelCountry->get_text_results());
		$view->set('text_asc', $this->language->get('text_asc'));
		$view->set('text_desc', $this->language->get('text_desc'));

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
 		$view->set('button_refresh', $this->language->get('button_enable_disable'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete')); // In English.php
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_status', $this->language->get('button_status'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));


		$view->set('help', $this->session->get('help'));
		$view->set('controller', 'country');

		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');

		$view->set('action', $this->url->ssl('country', 'page'));
		$view->set('action_refresh', $this->url->ssl('country', 'enableDisable'));
		$view->set('action_delete', $this->url->ssl('country', 'enableDelete'));
		$view->set('last', $this->url->getLast('country'));

		$view->set('search', $this->session->get('country.search'));
		$view->set('sort', $this->session->get('country.sort'));
		$view->set('order', $this->session->get('country.order'));
		$view->set('page', $this->session->get('country.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('insert', $this->url->ssl('country', 'insert'));

		$view->set('pages', $this->modelCountry->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	private function getForm() {
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_form_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));
		$view->set('text_address_explantion', $this->language->get('text_address_explantion'));
		$view->set('text_address_help', $this->language->get('text_address_help'));
		
		$view->set('entry_name', $this->language->get('entry_name'));
		$view->set('entry_country_status', $this->language->get('entry_country_status'));
		$view->set('entry_iso_code_2', $this->language->get('entry_iso_code_2'));
		$view->set('entry_iso_code_3', $this->language->get('entry_iso_code_3'));
		$view->set('entry_address_format', $this->language->get('entry_address_format'));

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

		$view->set('error', @$this->error['message']);
		$view->set('error_name', @$this->error['name']);
		$view->set('error_code_2', @$this->error['code_2']);
		$view->set('error_code_3', @$this->error['code_3']);
		$view->set('error_status', @$this->error['status']);
		$view->set('error_address', @$this->error['address']);

		if(!@$this->error['message']){
			$view->set('error', @$this->error['warning']);
		}

		$view->set('action', $this->url->ssl('country', $this->request->gethtml('action'), array('country_id' => $this->request->gethtml('country_id'))));

		$view->set('insert', $this->url->ssl('country', 'insert'));
		$view->set('cancel', $this->url->ssl('country'));
		$view->set('last', $this->url->getLast('country'));

		if ($this->request->gethtml('country_id')) {
			$view->set('update', 'update');
			$view->set('delete', $this->url->ssl('country', 'delete', array('country_id' => $this->request->gethtml('country_id'),'country_validation' => $this->session->get('country_validation'))));
		}

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('country_id', $this->request->gethtml('country_id'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$this->session->set('last_country_id', $this->request->gethtml('country_id'));

		if (($this->request->gethtml('country_id')) && (!$this->request->isPost())) {
			$country_info = $this->modelCountry->get_country_info();
			$this->session->set('country_date_modified', $country_info['date_modified']);
		}

		if ($this->request->has('name', 'post')) {
			$view->set('name', $this->request->gethtml('name', 'post'));
		} else {
			$view->set('name', @$country_info['name']);
		}

		if ($this->request->has('country_status', 'post')) {
			$view->set('country_status', $this->request->gethtml('country_status', 'post'));
		} else {
			$view->set('country_status', @$country_info['country_status']);
		}

		if ($this->request->has('iso_code_2', 'post')) {
			$view->set('iso_code_2', $this->request->gethtml('iso_code_2', 'post'));
		} else {
			$view->set('iso_code_2', @$country_info['iso_code_2']);
		}

		if ($this->request->has('iso_code_3', 'post')) {
			$view->set('iso_code_3', $this->request->gethtml('iso_code_3', 'post'));
		} else {
			$view->set('iso_code_3', @$country_info['iso_code_3']);
		}

		if ($this->request->has('address_format', 'post')) {
			$view->set('address_format', $this->request->gethtml('address_format', 'post'));
		} else {
			$view->set('address_format', @$country_info['address_format']);
		}

		return $view->fetch('content/country.tpl');
	}

	private function validateForm() { //update or insert
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'country')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if (!$this->validate->strlen($this->request->gethtml('name', 'post'),1,32)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		if ($this->config->get('config_country_id') == $this->request->gethtml('country_id') && $this->request->gethtml('country_status', 'post') == FALSE) {
			$this->error['message'] = $this->language->get('error_default');
		}
		if($this->request->has('country_status', 'post') && !$this->request->gethtml('country_status', 'post')){ //if we want to disable status

			// vendor has nothing to do with countries
			// permit status change even if customer's address is affected
			$zone_to_geo_zone_info = $this->modelCountry->check_zone_to_geo();
			if ($zone_to_geo_zone_info['total']) {
				$this->error['message'] = $zone_to_geo_zone_info['total'] ==1 ? $this->language->get('error_disable_zone_to_geo_zone') : $this->language->get('error_disable_zone_to_geo_zones', $zone_to_geo_zone_info['total']);
				$zone_to_geo_zone_list = $this-> modelCountry->get_countryToZoneToGeoZone();
					$this->error['message'] .= '<br>';
					foreach ($zone_to_geo_zone_list as $geo_zone) {
						$this->error['message'] .= '<a href="' . $this->url->ssl('zone_to_geo_zone', '', array('geo_zone_id' => $geo_zone['geo_zone_id'])) . '">' . $geo_zone['name'] . '</a>&nbsp;';
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

	private function validateModification() {
		if ($country_data = $this->modelCountry->get_country_info()) {
			if ($country_data['date_modified'] != $this->session->get('country_date_modified')) {
				$country_data_log = $this->modelCountry->get_modified_log($country_data['date_modified']);

				if ($country_data_log['name'] != $this->request->gethtml('name', 'post')) {
					$this->error['name'] = $this->language->get('error_modified', $country_data_log['name']);
				}

				if ($country_data_log['iso_code_2'] != $this->request->gethtml('iso_code_2', 'post')) {
					$this->error['code_2'] = $this->language->get('error_modified', $country_data_log['iso_code_2']);
				}

				if ($country_data_log['iso_code_3'] != $this->request->gethtml('iso_code_3', 'post')) {
					$this->error['code_3'] = $this->language->get('error_modified', $country_data_log['iso_code_3']);
				}

				if ($country_data_log['country_status'] != $this->request->gethtml('country_status', 'post')) {
					$this->error['status'] = $this->language->get('error_modified', $country_data_log['country_status'] ? $this->language->get('text_enabled'): $this->language->get('text_disabled'));
				}

				if ($country_data_log['address_format'] != $this->request->gethtml('address_format', 'post')) {
					$this->error['address'] = $this->language->get('error_modified', $country_data_log['address_format']);
				}

				$this->session->set('country_date_modified', $country_data_log['date_modified']);
			}
		} else {
			$country_data_log = $this->modelCountry->get_deleted_log();
			$this->session->set('message', $this->language->get('error_deleted', $country_data_log['modifier']));
			$this->response->redirect($this->url->ssl('country'));
		}
		if (@$this->error){
			$this->error['warning'] = $this->language->get('error_modifier', $country_data_log['modifier']);
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateEnable() {
		if (!$this->user->hasPermission('modify', 'country')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	protected function enableDelete(){
		$this->template->set('title', $this->language->get('heading_title'));
		if($this->validateEnableDelete()){
			if($this->session->get('enable_delete')){
				$this->session->delete('enable_delete');
			} else {
				$this->session->set('enable_delete', TRUE);
			}
			$this->response->redirect($this->url->ssl('country'));//**
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('country'));//**
		}
	}

	private function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'country')) {//**
			$this->error['message'] = $this->language->get('error_permission');  
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() { //deletion
		if(($this->session->get('country_validation') != $this->request->sanitize('country_validation')) || (strlen($this->session->get('country_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('country_validation');
		if (!$this->user->hasPermission('modify', 'country')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if ($this->config->get('config_country_id') == $this->request->gethtml('country_id')) {
			$this->error['message'] = $this->language->get('error_default');
		}
		$address_info = $this->modelCountry->check_address();
		if ($address_info['total']) {
			$this->error['message'] = $address_info['total'] ==1 ? $this->language->get('error_address') : $this->language->get('error_addresses', $address_info['total']);
			$address_list = $this-> modelCountry->get_countryToAddress();
				$this->error['message'] .= '<br>';
				foreach ($address_list as $address) {
					$this->error['message'] .= '<a href="' . $this->url->ssl('customer', 'update', array('customer_id' => $address['customer_id'])) . '">' . $address['firstname'] . '&nbsp;' . $address['lastname'] .'</a>&nbsp;';
				}
		}
		$zone_info = $this->modelCountry->check_zone();
		if ($zone_info['total']) {
			$this->error['message'] = $zone_info['total'] ==1 ? $this->language->get('error_zone') : $this->language->get('error_zones', $zone_info['total']);
			$zone_list = $this-> modelCountry->get_countryToZone();
				$this->error['message'] .= '<br>';
				foreach ($zone_list as $zone) {
					$this->error['message'] .= '<a href="' . $this->url->ssl('zone', 'update', array('zone_id' => $zone['zone_id'])) . '">' . $zone['name'] . '</a>&nbsp;';
				}
		}

		$zone_to_geo_zone_info = $this->modelCountry->check_zone_to_geo();
		if ($zone_to_geo_zone_info['total']) {
			$this->error['message'] = $zone_to_geo_zone_info['total'] ==1 ? $this->language->get('error_zone_to_geo_zone') : $this->language->get('error_zone_to_geo_zones', $zone_to_geo_zone_info['total']);
			$zone_to_geo_zone_list = $this-> modelCountry->get_countryToZoneToGeoZone();
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

	private function validateChangeStatus(){
		if (!$this->user->hasPermission('modify', 'country')) {
			return FALSE;
		}  else {
			return TRUE;
		}
	}

	protected function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}

	protected function page() {
		if ($this->request->has('search', 'post')) {
			$this->session->set('country.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('country.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('country.order', (($this->session->get('country.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('country.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('country.sort', $this->request->gethtml('sort', 'post'));
		}
		$this->response->redirect($this->url->ssl('country'));
	}
}
?>

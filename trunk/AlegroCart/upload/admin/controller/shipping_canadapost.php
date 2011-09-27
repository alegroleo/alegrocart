<?php //Shipping Canada Post Alegrocart
class ControllerShippingCanadaPost extends Controller{
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->language 	=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->template 	=& $locator->get('template');
		$this->session  	=& $locator->get('session');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user');
		$this->modelCANpost = $model->get('model_admin_shippingcanpost');
		
		$this->language->load('controller/shipping_canadapost.php');
	}
	
	function index() {  
		$this->template->set('title', $this->language->get('heading_title'));
		
		if ($this->request->isPost() && $this->request->has('global_canadapost_status', 'post') && $this->validate()) {
			$this->modelCANpost->delete_CANpost();
			$this->modelCANpost->update_CANpost();
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'shipping')));
		}
		
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));		
		
		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));
		$view->set('text_all_zones', $this->language->get('text_all_zones'));
		$view->set('text_none', $this->language->get('text_none'));
		$view->set('text_yes', $this->language->get('text_yes'));
		$view->set('text_no', $this->language->get('text_no'));
		
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_geo_zone', $this->language->get('entry_geo_zone'));
		$view->set('entry_tax', $this->language->get('entry_tax'));
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));
		$view->set('entry_postcode', $this->language->get('entry_postcode'));
		$view->set('entry_canadapost_ip', $this->language->get('entry_canadapost_ip'));
		$view->set('entry_canadapost_port', $this->language->get('entry_canadapost_port'));
		$view->set('entry_merchant_id', $this->language->get('entry_merchant_id'));
		$view->set('entry_language', $this->language->get('entry_language'));
		$view->set('entry_turnaround', $this->language->get('entry_turnaround'));
		$view->set('entry_readytoship', $this->language->get('entry_readytoship'));
		$view->set('entry_package', $this->language->get('entry_package'));
		
		$view->set('explanation_postcode', $this->language->get('explanation_postcode'));
		$view->set('explanation_geo_zone', $this->language->get('explanation_geo_zone'));
		$view->set('explanation_tax', $this->language->get('explanation_tax'));
		$view->set('explanation_canadapost_ip', $this->language->get('explanation_canadapost_ip'));
		$view->set('explanation_canadapost_port', $this->language->get('explanation_canadapost_port'));
		$view->set('explanation_merchant_id', $this->language->get('explanation_merchant_id'));
		$view->set('explanation_sort_order', $this->language->get('explanation_sort_order'));
		$view->set('explanation_language', $this->language->get('explanation_language'));
		$view->set('explanation_turnaround', $this->language->get('explanation_turnaround'));
		$view->set('explanation_readytoship', $this->language->get('explanation_readytoship'));
		$view->set('explanation_package', $this->language->get('explanation_package'));
		
		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));

		$view->set('tab_required', $this->language->get('tab_required'));
		$view->set('tab_optional', $this->language->get('tab_optional'));
		
		$view->set('error', @$this->error['message']);
		$view->set('action', $this->url->ssl('shipping_canadapost'));
		$view->set('list', $this->url->ssl('extension', FALSE, array('type' => 'shipping')));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'shipping')));	

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
		if (!$this->request->isPost()) {
			$results = $this->modelCANpost->get_CANpost();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}
		
		if ($this->request->has('global_canadapost_tax_class_id', 'post')) {
			$view->set('global_canadapost_tax_class_id', $this->request->gethtml('global_canadapost_tax_class_id', 'post'));
		} else {
			$view->set('global_canadapost_tax_class_id', @$setting_info['global']['canadapost_tax_class_id']);
		}
		
		if ($this->request->has('global_canadapost_sort_order', 'post')) {
			$view->set('global_canadapost_sort_order', $this->request->gethtml('global_canadapost_sort_order', 'post'));
		} else {
			$view->set('global_canadapost_sort_order', @$setting_info['global']['canadapost_sort_order']);
		}
		
		if ($this->request->has('global_canadapost_status', 'post')) {
			$view->set('global_canadapost_status', $this->request->gethtml('global_canadapost_status', 'post'));
		} else {
			$view->set('global_canadapost_status', @$setting_info['global']['canadapost_status']);
		}
		
		if ($this->request->has('global_canadapost_postcode', 'post')) {
			$view->set('global_canadapost_postcode', $this->request->gethtml('global_canadapost_postcode', 'post'));
		} else {
			$view->set('global_canadapost_postcode', @$setting_info['global']['canadapost_postcode']);
		}
		
		if ($this->request->has('global_canadapost_geo_zone_id', 'post')) {
			$view->set('global_canadapost_geo_zone_id', $this->request->gethtml('global_canadapost_geo_zone_id', 'post'));
		} else {
			$view->set('global_canadapost_geo_zone_id', @$setting_info['global']['canadapost_geo_zone_id']);
		}
		
		if ($this->request->has('global_canadapost_ip', 'post')) {
			$view->set('global_canadapost_ip', $this->request->gethtml('global_canadapost_ip', 'post'));
		} else {
			$view->set('global_canadapost_ip', @$setting_info['global']['canadapost_ip']);
		}
		
		if ($this->request->has('global_canadapost_port', 'post')) {
			$view->set('global_canadapost_port', $this->request->gethtml('global_canadapost_port', 'post'));
		} else {
			$view->set('global_canadapost_port', @$setting_info['global']['canadapost_port']);
		}
		
		if ($this->request->has('global_canadapost_merchant_id', 'post')) {
			$view->set('global_canadapost_merchant_id', $this->request->gethtml('global_canadapost_merchant_id', 'post'));
		} else {
			$view->set('global_canadapost_merchant_id', @$setting_info['global']['canadapost_merchant_id']);
		}
		
		if ($this->request->has('global_canadapost_language', 'post')) {
			$view->set('global_canadapost_language', $this->request->gethtml('global_canadapost_language', 'post'));
		} else {
			$view->set('global_canadapost_language', @$setting_info['global']['canadapost_language']);
		}
		
		if ($this->request->has('global_canadapost_turnaround', 'post')) {
			$view->set('global_canadapost_turnaround', $this->request->gethtml('global_canadapost_turnaround', 'post'));
		} else {
			$view->set('global_canadapost_turnaround', @$setting_info['global']['canadapost_turnaround']);
		}
		
		if ($this->request->has('global_canadapost_readytoship', 'post')) {
			$view->set('global_canadapost_readytoship', $this->request->gethtml('global_canadapost_readytoship', 'post'));
		} else {
			$view->set('global_canadapost_readytoship', @$setting_info['global']['canadapost_readytoship']);
		}
		
		if ($this->request->has('global_canadapost_package', 'post')) {
			$view->set('global_canadapost_package', $this->request->gethtml('global_canadapost_package', 'post'));
		} else {
			$view->set('global_canadapost_package', @$setting_info['global']['canadapost_package']);
		}
		
		$cp_languages[] = array(
			'code'	=> 'en',
			'name'	=> 'English'
		);
		$cp_languages[] = array(
			'code'	=> 'fr',
			'name'	=> 'French'
		);
		
		$view->set('cp_languages', $cp_languages);
		$view->set('tax_classes', $this->modelCANpost->get_tax_classes());
		$view->set('geo_zones', $this->modelCANpost->get_geo_zones());
		$this->template->set('content', $view->fetch('content/shipping_canadapost.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'shipping_canadapost')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
	function install() {
		if ($this->user->hasPermission('modify', 'shipping_canadapost')) {		
			$this->modelCANpost->delete_CANpost();
			$this->modelCANpost->install_CANpost();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'shipping')));		
	}
	
	function uninstall() {
		if ($this->user->hasPermission('modify', 'shipping_canadapost')) {
			$this->modelCANpost->delete_CANpost();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'shipping')));
	}
	
	
	
}
?>
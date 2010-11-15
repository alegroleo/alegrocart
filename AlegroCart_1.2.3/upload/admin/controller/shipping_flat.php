<?php // Shipping Flat AlegroCart
class ControllerShippingFlat extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->config   	=& $locator->get('config');
		$this->currency 	=& $locator->get('currency'); 
		$this->language 	=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->template 	=& $locator->get('template');
		$this->session  	=& $locator->get('session');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user');
		$this->modelFlatRate = $model->get('model_admin_shippingflat');

		$this->language->load('controller/shipping_flat.php');
	}
	function index() {  
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('global_flat_status', 'post') && $this->validate()) {
			$this->modelFlatRate->delete_flatrate();
			$this->modelFlatRate->update_flatrate();
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
				
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_geo_zone', $this->language->get('entry_geo_zone'));
		$view->set('entry_cost', $this->language->get('entry_cost'));
		$view->set('entry_max', $this->language->get('entry_max'));
		$view->set('entry_tax', $this->language->get('entry_tax'));
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));

		$view->set('explanation_entry_status', $this->language->get('explanation_entry_status'));
		$view->set('explanation_entry_geo_zone', $this->language->get('explanation_entry_geo_zone'));
		$view->set('explanation_entry_cost', $this->language->get('explanation_entry_cost'));
		$view->set('explanation_entry_max', $this->language->get('explanation_entry_max'));
		$view->set('explanation_entry_tax', $this->language->get('explanation_entry_tax'));
		$view->set('explanation_entry_sort_order', $this->language->get('explanation_entry_sort_order'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));

		$view->set('tab_general', $this->language->get('tab_general'));
		
		$currency_code = $this->config->get('config_currency');
		$decimal_place = $this->currency->currencies[$currency_code]['decimal_place'];

		$view->set('error', @$this->error['message']);
		$view->set('action', $this->url->ssl('shipping_flat'));
		$view->set('list', $this->url->ssl('extension', FALSE, array('type' => 'shipping')));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'shipping')));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (!$this->request->isPost()) {
			$results = $this->modelFlatRate->get_flatrate();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}

		if ($this->request->has('global_flat_status', 'post')) {
			$view->set('global_flat_status', $this->request->gethtml('global_flat_status', 'post'));
		} else {
			$view->set('global_flat_status', @$setting_info['global']['flat_status']);
		}

		if ($this->request->has('global_flat_geo_zone_id', 'post')) {
			$view->set('global_flat_geo_zone_id', $this->request->gethtml('global_flat_geo_zone_id', 'post'));
		} else {
			$view->set('global_flat_geo_zone_id', @$setting_info['global']['flat_geo_zone_id']);
		}

		if ($this->request->has('global_flat_cost', 'post')) {
			$view->set('global_flat_cost', number_format($this->request->gethtml('global_flat_cost', 'post'),$decimal_place,'.',''));
		} else {
			$view->set('global_flat_cost', @number_format($setting_info['global']['flat_cost'],$decimal_place,'.',''));
		}
		
		if ($this->request->has('global_flat_max', 'post')) {
			$view->set('global_flat_max', number_format($this->request->gethtml('global_flat_max', 'post'),$decimal_place,'.',''));
		} else {
			$view->set('global_flat_max', @number_format($setting_info['global']['flat_max'],$decimal_place,'.',''));
		}

		if ($this->request->has('global_flat_tax_class_id', 'post')) {
			$view->set('global_flat_tax_class_id', $this->request->gethtml('global_flat_tax_class_id', 'post'));
		} else {
			$view->set('global_flat_tax_class_id', @$setting_info['global']['flat_tax_class_id']);
		}

		if ($this->request->has('global_flat_sort_order', 'post')) {
			$view->set('global_flat_sort_order', $this->request->gethtml('global_flat_sort_order', 'post'));
		} else {
			$view->set('global_flat_sort_order', @$setting_info['global']['flat_sort_order']);
		}				

		$view->set('tax_classes', $this->modelFlatRate->get_tax_classes());
		$view->set('geo_zones', $this->modelFlatRate->get_geo_zones());

		$this->template->set('content', $view->fetch('content/shipping_flat.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
		function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'shipping_flat')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
	function install() {
		if ($this->user->hasPermission('modify', 'shipping_flat')) {		
			$this->modelFlatRate->delete_flatrate();
			$this->modelFlatRate->install_flatrate();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'shipping')));		
	}
	
	function uninstall() {
		if ($this->user->hasPermission('modify', 'shipping_flat')) {
			$this->modelFlatRate->delete_flatrate();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'shipping')));
	}
}
?>
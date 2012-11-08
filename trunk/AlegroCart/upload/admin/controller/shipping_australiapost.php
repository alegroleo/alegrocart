<?php //Shipping Australia Post Alegrocart
class ControllerShippingAustraliaPost extends Controller{
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
		$this->modelAUPost = $model->get('model_admin_shippingaup');
		
		$this->language->load('controller/shipping_australiapost.php');
	}
	
	function index() {  
		$this->template->set('title', $this->language->get('heading_title'));
		
		if ($this->request->isPost() && $this->request->has('global_australiapost_status', 'post') && $this->validate()) {
			$this->modelAUPost->delete_AUP();
			$this->modelAUPost->update_AUP();
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'shipping')));
		}
		
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_shipping', $this->language->get('heading_shipping'));
		$view->set('heading_description', $this->language->get('heading_description'));
		
		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));
		$view->set('text_all_zones', $this->language->get('text_all_zones'));
		$view->set('text_none', $this->language->get('text_none'));
		
		
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_geo_zone', $this->language->get('entry_geo_zone'));
		$view->set('entry_tax', $this->language->get('entry_tax'));
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));
		$view->set('entry_weight_class', $this->language->get('entry_weight_class'));
		$view->set('entry_dimension_class', $this->language->get('entry_dimension_class'));
		$view->set('entry_default_method', $this->language->get('entry_default_method'));
		$view->set('entry_postcode', $this->language->get('entry_postcode'));
		
		$view->set('explanation_entry_status', $this->language->get('explanation_entry_status'));
		$view->set('explanation_entry_geo_zone', $this->language->get('explanation_entry_geo_zone'));
		$view->set('explanation_entry_sort_order', $this->language->get('explanation_entry_sort_order'));
		$view->set('explanation_entry_tax', $this->language->get('explanation_entry_tax'));
		$view->set('explanation_entry_method', $this->language->get('explanation_entry_method'));
		$view->set('explanation_weight', $this->language->get('explanation_weight'));
		$view->set('explanation_dimension', $this->language->get('explanation_dimension'));
		$view->set('explanation_postcode', $this->language->get('explanation_postcode'));
		
		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('action', $this->url->ssl('shipping_australiapost'));
		$view->set('list', $this->url->ssl('extension', FALSE, array('type' => 'shipping')));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'shipping')));	

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
		if (!$this->request->isPost()) {
			$results = $this->modelAUPost->get_AUPost();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}
		
		if ($this->request->has('global_australiapost_tax_class_id', 'post')) {
			$view->set('global_australiapost_tax_class_id', $this->request->gethtml('global_zone_tax_class_id', 'post'));
		} else {
			$view->set('global_australiapost_tax_class_id', @$setting_info['global']['australiapost_tax_class_id']);
		}
		
		if ($this->request->has('global_australiapost_sort_order', 'post')) {
			$view->set('global_australiapost_sort_order', $this->request->gethtml('global_australiapost_sort_order', 'post'));
		} else {
			$view->set('global_australiapost_sort_order', @$setting_info['global']['australiapost_sort_order']);
		}
		
		if ($this->request->has('global_australiapost_status', 'post')) {
			$view->set('global_australiapost_status', $this->request->gethtml('global_australiapost_status', 'post'));
		} else {
			$view->set('global_australiapost_status', @$setting_info['global']['australiapost_status']);
		}
		
		if ($this->request->has('global_australiapost_postcode', 'post')) {
			$view->set('global_australiapost_postcode', $this->request->gethtml('global_australiapost_postcode', 'post'));
		} else {
			$view->set('global_australiapost_postcode', @$setting_info['global']['australiapost_postcode']);
		}
		
		if ($this->request->has('global_australiapost_weight_class', 'post')) {
			$view->set('global_australiapost_weight_class', $this->request->gethtml('global_australiapost_weight_class', 'post'));
		} else {
			$view->set('global_australiapost_weight_class', @$setting_info['global']['australiapost_weight_class']);
		}
		
		if ($this->request->has('global_australiapost_dimension_class', 'post')) {
			$view->set('global_australiapost_dimension_class', $this->request->gethtml('global_australiapost_dimension_class', 'post'));
		} else {
			$view->set('global_australiapost_dimension_class', @$setting_info['global']['australiapost_dimension_class']);
		}
		
		if ($this->request->has('global_australiapost_default_method', 'post')) {
			$view->set('global_australiapost_default_method', $this->request->gethtml('global_australiapost_default_method', 'post'));
		} else {
			$view->set('global_australiapost_default_method', @$setting_info['global']['australiapost_default_method']);
		}

		if ($this->request->has('global_australiapost_geo_zone_id', 'post')) {
			$view->set('global_australiapost_geo_zone_id', $this->request->gethtml('global_australiapost_geo_zone_id', 'post'));
		} else {
			$view->set('global_australiapost_geo_zone_id', @$setting_info['global']['australiapost_geo_zone_id']);
		}
		
		$view->set('weight_classes', $this->modelAUPost->get_weight_classes());
		$view->set('dimension_classes', $this->modelAUPost->get_dimension_classes(1));
		$view->set('tax_classes', $this->modelAUPost->get_tax_classes());
		$view->set('geo_zones', $this->modelAUPost->get_geo_zones());
		$methods = $this->language->get('default_methods');
		$default_methods = explode(',', $methods);
		$view->set('default_methods', $default_methods);
		
		$this->template->set('content', $view->fetch('content/shipping_australiapost.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'shipping_australiapost')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
	function install() {
		if ($this->user->hasPermission('modify', 'shipping_australiapost')) {		
			$this->modelAUPost->delete_AUP();
			$this->modelAUPost->install_AUP();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'shipping')));		
	}
	
	function uninstall() {
		if ($this->user->hasPermission('modify', 'shipping_australiapost')) {
			$this->modelAUPost->delete_AUP();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'shipping')));
	}
}
?>

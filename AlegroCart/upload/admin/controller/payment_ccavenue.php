<?php // Payment ccAvenue AlegroCart
class ControllerPaymentccAvenue extends Controller {
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
		$this->modelccAvenue = $model->get('model_admin_paymentccavenue');
		
		$this->language->load('controller/payment_ccavenue.php');
	}
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));
		
		if ($this->request->isPost() && $this->request->has('global_ccavenue_status', 'post') && $this->validate()) {
			$this->modelccAvenue->delete_ccAvenue();
			$this->modelccAvenue->update_ccAvenue();
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'payment')));
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
		$view->set('text_merchant_id', $this->language->get('text_merchant_id'));
		$view->set('text_working_key', $this->language->get('text_working_key'));
		$view->set('text_currency', $this->language->get('text_currency'));
		
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_geo_zone', $this->language->get('entry_geo_zone'));
		$view->set('entry_merchant_id', $this->language->get('entry_merchant_id'));
		$view->set('entry_working_key', $this->language->get('entry_working_key'));
		$view->set('entry_currency', $this->language->get('entry_currency'));
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));
		
		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_general', $this->language->get('tab_general'));
		
		$view->set('error', @$this->error['message']);
		
		$view->set('action', $this->url->ssl('payment_ccavenue'));
		$view->set('list', $this->url->ssl('extension', FALSE, array('type' => 'payment')));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'payment')));	
		
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
		if (!$this->request->isPost()) {
			$results = $this->modelccAvenue->get_ccAvenue();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}
		
		if ($this->request->has('global_ccavenue_status', 'post')) {
			$view->set('global_ccavenue_status', $this->request->gethtml('global_ccavenue_status', 'post'));
		} else {
			$view->set('global_ccavenue_status', @$setting_info['global']['ccavenue_status']);
		}
		
		if ($this->request->has('global_ccavenue_geo_zone_id', 'post')) {
			$view->set('geo_zone_id', $this->request->gethtml('global_ccavenue_geo_zone_id', 'post'));
		} else {
			$view->set('geo_zone_id', @$setting_info['global']['ccavenue_geo_zone_id']); 
		} 
		
		if ($this->request->has('global_ccavenue_merchant_id', 'post')) {
			$view->set('global_ccavenue_merchant_id', $this->request->gethtml('global_paypal_test', 'post'));
		} else {
			$view->set('global_ccavenue_merchant_id', @$setting_info['global']['ccavenue_merchant_id']);
		}
		
		if ($this->request->has('global_ccavenue_working_key', 'post')) {
			$view->set('global_ccavenue_working_key', $this->request->gethtml('global_ccavenue_working_key', 'post'));
		} else {
			$view->set('global_ccavenue_working_key', @$setting_info['global']['ccavenue_working_key']);
		}
		
		if ($this->request->has('global_ccavenue_currency', 'post')) {
			$payment_info = $this->request->gethtml('global_ccavenue_currency', 'post');
		} else {
			$payment_info = explode(',', @$setting_info['global']['ccavenue_currency']);
		}
		
		if ($this->request->has('global_ccavenue_sort_order', 'post')) {
			$view->set('global_ccavenue_sort_order', $this->request->gethtml('global_ccavenue_sort_order', 'post'));
		} else {
			$view->set('global_ccavenue_sort_order', @$setting_info['global']['ccavenue_sort_order']);
		}
		
		$currency_data = array();
		$currency_data[] = array(
			'text'     => $this->language->get('text_usd'),
			'value'    => 'USD',
			'selected' => in_array('USD', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_inr'),
			'value'    => 'INR',
			'selected' => in_array('INR', $payment_info)
		);
		$view->set('currencies', $currency_data);
		
		$view->set('geo_zones', $this->modelccAvenue->get_geo_zones());
		
		$this->template->set('content', $view->fetch('content/payment_ccavenue.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'payment_ccavenue')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function install() {
		if ($this->user->hasPermission('modify', 'payment_ccavenue')) {
			$this->modelccAvenue->delete_ccAvenue();
			$this->modelccAvenue->install_ccAvenue();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'payment')));
	}
	
	function uninstall() {
		if ($this->user->hasPermission('modify', 'payment_ccavenue')) {
			$this->modelccAvenue->delete_ccAvenue();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'payment')));
	}
}
?>
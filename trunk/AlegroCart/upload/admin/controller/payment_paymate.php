<?php // Payemnt PayMate AlegroCart
class ControllerPaymentPayMate extends Controller{
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
		$this->modelPaymate = $model->get('model_admin_paymentpaymate');
		
		$this->language->load('controller/payment_paymate.php');
	}
	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
	
		if ($this->request->isPost() && $this->request->has('global_paymate_status', 'post') && $this->validate()) {
			$this->modelPaymate->delete_paymate();
			$this->modelPaymate->update_paymate();
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

		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_geo_zone', $this->language->get('entry_geo_zone'));
		$view->set('entry_mid', $this->language->get('entry_mid'));
		$view->set('entry_test', $this->language->get('entry_test'));
		$view->set('entry_currency', $this->language->get('entry_currency'));
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));

		//  Some extra explanations for the user.
		$view->set('explanation_entry_status', $this->language->get('explanation_entry_status'));
		$view->set('explanation_entry_test', $this->language->get('explanation_entry_test'));
		$view->set('explanation_entry_mid', $this->language->get('explanation_entry_mid'));
		$view->set('explanation_entry_geo_zone', $this->language->get('explanation_entry_geo_zone'));
		$view->set('explanation_entry_currency', $this->language->get('explanation_entry_currency'));
		$view->set('explanation_entry_sort_order', $this->language->get('explanation_entry_sort_order'));
		$view->set('explanation_entry_order_status', $this->language->get('explanation_entry_order_status'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_mid', @$this->error['mid']);

		$view->set('action', $this->url->ssl('payment_paymate'));
		$view->set('list', $this->url->ssl('extension', FALSE, array('type' => 'payment')));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'payment')));
		
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
		if (!$this->request->isPost()) {
			$results = $this->modelPaymate->get_paymate();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}

		if ($this->request->has('global_paymate_status', 'post')) {
			$view->set('global_paymate_status', $this->request->gethtml('global_paymate_status', 'post'));
		} else {
			$view->set('global_paymate_status', @$setting_info['global']['paymate_status']);
		}
		if ($this->request->has('global_paymate_geo_zone_id', 'post')) {
			$view->set('geo_zone_id', $this->request->gethtml('global_paymate_geo_zone_id', 'post'));
		} else {
			$view->set('geo_zone_id', @$setting_info['global']['paymate_geo_zone_id']);
		}
		if ($this->request->has('global_paymate_mid', 'post')) {
			$view->set('global_paymate_mid', $this->request->gethtml('global_paymate_mid', 'post'));
		} else {
			$view->set('global_paymate_mid', @$setting_info['global']['paymate_mid']);
		}
		if ($this->request->has('global_paymate_test', 'post')) {
			$view->set('global_paymate_test', $this->request->gethtml('global_paymate_test', 'post'));
		} else {
			$view->set('global_paymate_test', @$setting_info['global']['paymate_test']);
		}
		if ($this->request->has('global_paymate_currency', 'post')) {
			$payment_info = $this->request->gethtml('global_paymate_currency', 'post');
		} else {
			$payment_info = explode(',', @$setting_info['global']['paymate_currency']);
		}
		if ($this->request->has('global_paymate_sort_order', 'post')) {
			$view->set('global_paymate_sort_order', $this->request->gethtml('global_paymate_sort_order', 'post'));
		}
		else {
			$view->set('global_paymate_sort_order', @$setting_info['global']['paymate_sort_order']);
		}

		//  Get the available Order Status Values
		$view->set('order_statuses', $this->modelPaymate->get_order_status());

		//  Confirmed Payment
		$view->set('entry_order_status', $this->language->get('entry_order_status'));
		if ($this->request->has('paymate_order_status', 'post')) {
			$view->set('paymate_order_status', $this->request->gethtml('paymate_order_status', 'post'));
		} else {
			$view->set('paymate_order_status', @$setting_info['global']['paymate_order_status']);
		}

		$currency_data = array();
		$currency_data[] = array(
			'text'     => $this->language->get('text_aud'),
			'value'    => 'AUD',
			'selected' => in_array('AUD', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_eur'),
			'value'    => 'EUR',
			'selected' => in_array('EUR', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_gbp'),
			'value'    => 'GBP',
			'selected' => in_array('GBP', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_nzd'),
			'value'    => 'NZD',
			'selected' => in_array('NZD', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_usd'),
			'value'    => 'USD',
			'selected' => in_array('USD', $payment_info)
		);
		$view->set('currencies', $currency_data);
		
		$view->set('geo_zones', $this->modelPaymate->get_geo_zones());
		
		$this->template->set('content', $view->fetch('content/payment_paymate.tpl'));
		$this->template->set($this->module->fetch());
		
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'payment_paymate')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->gethtml('global_paymate_mid', 'post')) {
			$this->error['mid'] = $this->language->get('error_mid');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function install() {
		if ($this->user->hasPermission('modify', 'payment_paymate')) {
			$this->modelPaymate->delete_paymate();
			$this->modelPaymate->install_paymate();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'payment')));
	}

	function uninstall() {
		if ($this->user->hasPermission('modify', 'payment_paymate')) {
			$this->modelPaymate->delete_paymate();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'payment')));
	}
}
?>
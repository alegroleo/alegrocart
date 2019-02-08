<?php // Payment MoneyOrder AlegroCart
class ControllerPaymentMoneyOrder extends Controller {
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
		$this->modelMoneyOrder = $model->get('model_admin_paymentmoneyorder');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController = $this->template->set_controller('payment_moneyorder');

		$this->language->load('controller/payment_moneyorder.php');
	}
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('global_moneyorder_status', 'post') && $this->validate()) {
			$this->modelMoneyOrder->delete_moneyorder();
			$this->modelMoneyOrder->update_moneyorder();
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('payment_moneyorder'));
			} else {
				$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'payment')));
			}
		}

		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_payment', $this->language->get('heading_payment'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));
		$view->set('text_all_zones', $this->language->get('text_all_zones'));
		$view->set('text_none', $this->language->get('text_none'));
		$view->set('text_yes', $this->language->get('text_yes'));
		$view->set('text_no', $this->language->get('text_no'));

		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_geo_zone', $this->language->get('entry_geo_zone'));
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));

		$view->set('explanation_moneyorder_status', $this->language->get('explanation_moneyorder_status'));
        	$view->set('explanation_moneyorder_geo_zone', $this->language->get('explanation_moneyorder_geo_zone'));
        	$view->set('explanation_moneyorder_sort_order', $this->language->get('explanation_moneyorder_sort_order'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));

		$view->set('help', $this->session->get('help'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('action', $this->url->ssl('payment_moneyorder'));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'payment')));	

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$this->session->set('name_last_payment', $this->language->get('heading_title'));
		$this->session->set('last_payment', 'payment_moneyorder');
		$this->session->set('last_extension_id', $this->modelMoneyOrder->get_extension_id('payment_moneyorder'));

		if (!$this->request->isPost()) {
			$results = $this->modelMoneyOrder->get_moneyorder();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}

		if ($this->request->has('global_moneyorder_status', 'post')) {
			$view->set('status', $this->request->gethtml('global_moneyorder_status', 'post'));
		} else {
			$view->set('status', @$setting_info['global']['moneyorder_status']);
		}

		if ($this->request->has('global_moneyorder_geo_zone_id', 'post')) {
			$view->set('geo_zone_id', $this->request->gethtml('global_moneyorder_geo_zone_id', 'post'));
		} else {
			$view->set('geo_zone_id', @$setting_info['global']['moneyorder_geo_zone_id']); 
		} 

		if ($this->request->has('global_moneyorder_sort_order', 'post')) {
			$view->set('global_moneyorder_sort_order', $this->request->gethtml('global_moneyorder_sort_order', 'post'));
		} else {
			$view->set('global_moneyorder_sort_order', @$setting_info['global']['moneyorder_sort_order']);
		}

		$view->set('geo_zones', $this->modelMoneyOrder->get_geo_zones());

		$this->template->set('content', $view->fetch('content/payment_moneyorder.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'payment_moneyorder')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}
	function install() {
		if ($this->user->hasPermission('modify', 'payment_moneyorder')) {
			$this->modelMoneyOrder->delete_moneyorder();
			$this->modelMoneyOrder->install_moneyorder();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}
		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'payment')));
	}
	function uninstall() {
		if ($this->user->hasPermission('modify', 'payment_moneyorder')) {
			$this->modelMoneyOrder->delete_moneyorder();
			if ($this->session->get('last_payment') == 'payment_moneyorder') {
				$this->session->delete('name_last_payment');
				$this->session->delete('last_payment');
			}
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}
		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'payment')));
	}
}
?>

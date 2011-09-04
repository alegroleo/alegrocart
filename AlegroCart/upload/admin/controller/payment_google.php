<?php //Payment Google AlegroCart
class ControllerPaymentGoogle extends Controller {
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
		$this->modelGoogle = $model->get('model_admin_paymentgoogle');
		
		$this->language->load('controller/payment_google.php');
	}
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->isPost()) && ($this->validate())) {
			$this->modelGoogle->delete_google();
			$this->modelGoogle->update_google();
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
		$view->set('entry_merchantid', $this->language->get('entry_merchantid'));
		$view->set('entry_merchantkey', $this->language->get('entry_merchantkey'));
		$view->set('entry_test', $this->language->get('entry_test'));
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
		$view->set('error_merchantid', @$this->error['merchantid']);
		$view->set('error_merchantkey', @$this->error['merchantkey']);
		
		$view->set('action', $this->url->ssl('payment_google'));
		$view->set('list', $this->url->ssl('extension', FALSE, array('type' => 'payment')));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'payment')));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (!$this->request->isPost()) {
			$results = $this->modelGoogle->get_google();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}
		
		if ($this->request->has('global_google_status', 'post')) {
			$view->set('global_google_status', $this->request->get('global_google_status', 'post'));
		} else {
			$view->set('global_google_status', @$setting_info['global']['google_status']);
		}
		
		if ($this->request->has('global_google_geo_zone_id', 'post')) {
			$view->set('geo_zone_id', $this->request->get('global_google_geo_zone_id', 'post'));
		} else {
			$view->set('geo_zone_id', @$setting_info['global']['google_geo_zone_id']); 
		} 

		if ($this->request->has('global_google_merchantid', 'post')) {
			$view->set('global_google_merchantid', $this->request->get('global_google_merchantid', 'post'));
		} else {
			$view->set('global_google_merchantid', @$setting_info['global']['google_merchantid']);
		}
		
		if ($this->request->has('global_google_merchantkey', 'post')) {
			$view->set('global_google_merchantkey', $this->request->get('global_google_merchantkey', 'post'));
		} else {
			$view->set('global_google_merchantkey', @$setting_info['global']['google_merchantkey']);
		}
		
		if ($this->request->has('global_google_test', 'post')) {
			$view->set('global_google_test', $this->request->get('global_google_test', 'post'));
		} else {
			$view->set('global_google_test', @$setting_info['global']['google_test']);
		}
		
		if ($this->request->has('global_google_currency', 'post')) {
			$payment_info = $this->request->get('global_google_currency', 'post');
		} else {
			$payment_info = $setting_info['global']['google_currency'];
		}

		$currency_data = array();
		$currency_data[] = array(
			'text'     => $this->language->get('text_gbp'),
			'value'    => 'GBP',
			'selected' => ('GBP'==$payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_usd'),
			'value'    => 'USD',
			'selected' => ('USD'==$payment_info)
		);

		$view->set('currencies', $currency_data);
		
		if ($this->request->has('global_google_sort_order', 'post')) {
			$view->set('global_google_sort_order', $this->request->get('global_google_sort_order', 'post'));
		} else {
			$view->set('global_google_sort_order', @$setting_info['global']['google_sort_order']);
		}

		$view->set('geo_zones', $this->modelGoogle->get_geo_zones());

		$this->template->set('content', $view->fetch('content/payment_google.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'payment_google')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if (!$this->request->get('global_google_merchantid', 'post')) {
			$this->error['merchantid'] = $this->language->get('error_merchantid');
		}
		if (!$this->request->get('global_google_merchantkey', 'post')) {
			$this->error['merchantkey'] = $this->language->get('error_merchantkey');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
	function install() {
		if ($this->user->hasPermission('modify', 'payment_google')) {
			$this->modelGoogle->delete_google();
			$this->modelGoogle->install_google();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'payment')));
	}
	
	function uninstall() {
		if ($this->user->hasPermission('modify', 'payment_google')) {
			$this->modelGoogle->delete_google();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'payment')));
	}
}
?>
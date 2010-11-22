<?php // Payment Paypal AlegroCart
class ControllerPaymentPayPal extends Controller {
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
		$this->modelPaypal = $model->get('model_admin_paymentpaypal');
		
		$this->language->load('controller/payment_paypal.php');
	}
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('global_paypal_status', 'post') && $this->validate()) {
			$this->modelPaypal->delete_paypal();
			$this->modelPaypal->update_paypal();
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
		$view->set('entry_email', $this->language->get('entry_email'));
		$view->set('entry_test', $this->language->get('entry_test'));
		$view->set('entry_currency', $this->language->get('entry_currency'));
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));
        $view->set('entry_auth_type', $this->language->get('entry_auth_type'));
        $view->set('entry_pdt_token', $this->language->get('entry_pdt_token'));
        $view->set('entry_itemized', $this->language->get('entry_itemized'));
        $view->set('entry_ipn_debug', $this->language->get('entry_ipn_debug'));

        $view->set('extra_auth_type', $this->language->get('extra_auth_type'));
        $view->set('extra_pdt_token', $this->language->get('extra_pdt_token'));
        $view->set('extra_itemized', $this->language->get('extra_itemized'));
        $view->set('extra_ipn_debug', $this->language->get('extra_ipn_debug'));
        $view->set('text_support', $this->language->get('text_support'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));

        $view->set('text_authorization', $this->language->get('text_authorization'));
        $view->set('text_sale', $this->language->get('text_sale'));
        $view->set('text_order', $this->language->get('text_order'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_email', @$this->error['email']);
        $view->set('error_pdt_token', @$this->error['pdt_token']);
		
		$view->set('action', $this->url->ssl('payment_paypal'));
		$view->set('list', $this->url->ssl('extension', FALSE, array('type' => 'payment')));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'payment')));	

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
		if (!$this->request->isPost()) {
			$results = $this->modelPaypal->get_paypal();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}
		
		if ($this->request->has('global_paypal_status', 'post')) {
			$view->set('global_paypal_status', $this->request->gethtml('global_paypal_status', 'post'));
		} else {
			$view->set('global_paypal_status', @$setting_info['global']['paypal_status']);
		}
		
		if ($this->request->has('global_paypal_geo_zone_id', 'post')) {
			$view->set('geo_zone_id', $this->request->gethtml('global_paypal_geo_zone_id', 'post'));
		} else {
			$view->set('geo_zone_id', @$setting_info['global']['paypal_geo_zone_id']); 
		} 

		if ($this->request->has('global_paypal_email', 'post')) {
			$view->set('global_paypal_email', $this->request->gethtml('global_paypal_email', 'post'));
		} else {
			$view->set('global_paypal_email', @$setting_info['global']['paypal_email']);
		}
        
        if ($this->request->has('global_paypal_pdt_token', 'post')) {
            $view->set('global_paypal_pdt_token', $this->request->gethtml('global_paypal_pdt_token', 'post'));
        } else {
            $view->set('global_paypal_pdt_token', @$setting_info['global']['paypal_pdt_token']);
        }
		
		if ($this->request->has('global_paypal_test', 'post')) {
			$view->set('global_paypal_test', $this->request->gethtml('global_paypal_test', 'post'));
		} else {
			$view->set('global_paypal_test', @$setting_info['global']['paypal_test']);
		}
        
		if ($this->request->has('global_paypal_currency', 'post')) {
			$payment_info = $this->request->gethtml('global_paypal_currency', 'post');
		} else {
			$payment_info = explode(',', @$setting_info['global']['paypal_currency']);
		}

		$view->set('currencies', $this->get_currencies($payment_info));
		
		if ($this->request->has('global_paypal_sort_order', 'post')) {
			$view->set('global_paypal_sort_order', $this->request->gethtml('global_paypal_sort_order', 'post'));
		} else {
			$view->set('global_paypal_sort_order', @$setting_info['global']['paypal_sort_order']);
		}
        
        if ($this->request->has('global_paypal_auth_type', 'post')) {
            $view->set('global_paypal_auth_type', $this->request->gethtml('global_paypal_auth_type', 'post'));
        } else {
            $view->set('global_paypal_auth_type', @$setting_info['global']['paypal_auth_type']);
        }
        
        if ($this->request->has('global_paypal_itemized', 'post')) {
            $view->set('global_paypal_itemized', $this->request->gethtml('global_paypal_itemized', 'post'));
        } else {
            $view->set('global_paypal_itemized', @$setting_info['global']['paypal_itemized']);
        }
        
        if ($this->request->has('global_paypal_ipn_debug', 'post')) {
            $view->set('global_paypal_ipn_debug', $this->request->gethtml('global_paypal_ipn_debug', 'post'));
        } else {
            $view->set('global_paypal_ipn_debug', @$setting_info['global']['paypal_ipn_debug']);
        }

		$view->set('geo_zones', $this->modelPaypal->get_geo_zones());

		$this->template->set('content', $view->fetch('content/payment_paypal.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function get_currencies($payment_info){
		$currency_data = array();
		$currency_data[] = array(
			'text'     => $this->language->get('text_cad'),
			'value'    => 'CAD',
			'selected' => in_array('CAD', $payment_info)
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
			'text'     => $this->language->get('text_usd'),
			'value'    => 'USD',
			'selected' => in_array('USD', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_jpy'),
			'value'    => 'JPY',
			'selected' => in_array('JPY', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_aud'),
			'value'    => 'AUD',
			'selected' => in_array('AUD', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_nzd'),
			'value'    => 'NZD',
			'selected' => in_array('NZD', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_chf'),
			'value'    => 'CHF',
			'selected' => in_array('CHF', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_hkd'),
			'value'    => 'HKD',
			'selected' => in_array('HKD', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_sgd'),
			'value'    => 'SGD',
			'selected' => in_array('SGD', $payment_info)
		);	
		$currency_data[] = array(
			'text'     => $this->language->get('text_sek'),
			'value'    => 'SEK',
			'selected' => in_array('SEK', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_dkk'),
			'value'    => 'DKK',
			'selected' => in_array('DKK', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_pln'),
			'value'    => 'PLN',
			'selected' => in_array('PLN', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_nok'),
			'value'    => 'NOK',
			'selected' => in_array('NOK', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_huf'),
			'value'    => 'HUF',
			'selected' => in_array('HUF', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $this->language->get('text_czk'),
			'value'    => 'CZK',
			'selected' => in_array('CZK', $payment_info)
		);
		return $currency_data;
	}
	
	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'payment_paypal')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if (!$this->request->gethtml('global_paypal_email', 'post')) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
	function install() {
		if ($this->user->hasPermission('modify', 'payment_paypal')) {
			$this->modelPaypal->delete_paypal();
			$this->modelPaypal->install_paypal();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'payment')));
	}
	
	function uninstall() {
		if ($this->user->hasPermission('modify', 'payment_paypal')) {
			$this->modelPaypal->delete_paypal();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'payment')));
	}
}
?>
<?php //AlegroCart
class PaymentPayMate extends Payment {

	function __construct(&$locator) {
		$this->address   =& $locator->get('address');
        $this->cart      =& $locator->get('cart');
        $this->config    =& $locator->get('config');
        $this->coupon    =& $locator->get('coupon');
        $this->currency  =& $locator->get('currency');
        $this->customer  =& $locator->get('customer');
        $this->database  =& $locator->get('database');
        $this->language  =& $locator->get('language');
        $this->mail      = $locator->create('mail');
        $this->order     =& $locator->get('order');
        $this->request   =& $locator->get('request');
        $this->response  =& $locator->get('response');
        $this->session   =& $locator->get('session');
        $this->shipping  =& $locator->get('shipping');
        $this->tax       =& $locator->get('tax');
        $this->url       =& $locator->get('url');   
		$model 			=& $locator->get('model');
		$this->modelPayment = $model->get('model_payment');
        
        $this->language->load('extension/payment/paymate.php');
	}

	function get_Title() {
        return $this->language->get('text_paymate_title');
    }

	function getMethod() {
        if ($this->config->get('paymate_status')) {
            if (!$this->config->get('paymate_geo_zone_id')) {
                $status = true;
            } elseif ($this->modelPayment->get_paymatestatus()){
                $status = true;
            } else {
                $status = false;
            }    
        } else {
            $status = false;
        }
        
        // If the member id is empty, the status is false
        if (!$this->config->get('paymate_mid')) {
    		$status = false;
		}
		
        $method_data = array();
    
        if ($status) {  
            $method_data = array( 
                'id'         => 'paymate',
                'title'      => $this->language->get('text_paymate_title'),
                'sort_order' => $this->config->get('paymate_sort_order')
            );
        }
   
        return $method_data;
    }
    
    function get_ActionUrl() {
    	$this->session->set('payment_form_enctype', 'none');
        if (!$this->config->get('paymate_test')) {
            return 'https://www.paymate.com/PayMate/GenExpressPayment';
        } else {
            return 'https://www.paymate.com/PayMate/TestExpressPayment';
        }
    }

	function fields() {
		
		// Validate the current currency is supported, else use default currency.
        $currency_data = explode(',', $this->config->get('paymate_currency'));
        if (in_array($this->currency->getCode(), $currency_data)) {
            $currency = $this->currency->getCode();
        } else {
            $currency = $this->config->get('config_currency');
        }

        $fields=array();
        $fields['mid']=$this->config->get('paymate_mid');
        $fields['amt']=$this->currency->format($this->order->get('total'), $currency, FALSE, FALSE);
        $fields['amt_editable']='N';
        $fields['currency']=$currency;
        $fields['pmt_contact_firstname']=$this->order->get('firstname');
        $fields['pmt_contact_surname']=$this->order->get('lastname');
        $fields['pmt_contact_phone']=$this->order->get('telephone');
        $fields['regindi_address1']=$this->order->get('payment_address_1');
        $fields['regindi_address2']=$this->order->get('payment_address_2');
        $fields['regindi_sub']=$this->order->get('payment_city');
        //$fields['regindi_state']=$this->order->get('payment_zone');
        $fields['regindi_state']=$this->address->getZoneCode($this->session->get('payment_address_id'));
        $fields['regindi_pcode']=$this->order->get('payment_postcode');
        $fields['pmt_country']=$this->address->getIsoCode2($this->session->get('payment_address_id'));
        $fields['pmt_sender_email']=$this->order->get('email');
        $fields['ref']=$this->order->getReference();
        $fields['return']=$this->url->rawssl('checkout_process', 'index', array('method' => 'return', 'ref' => $this->order->getReference()));
        $fields['popup']='false';
      
        $output=array();
        foreach ($fields as $key => $value) {
            $output[]='<input type="hidden" name="'.$key.'" value="'.$value.'">';
        }

        $output=implode("\n",$output);
        
        return $output;
	}
	
	// Process the order on return
	function process() {
		if ($this->request->gethtml('method') == 'return') { 
			$this->order->load($this->request->gethtml('ref'));
			$order_status = ($this->config->get('paymate_order_status') != '') ? $this->config->get('paymate_order_status') : $this->config->get('config_order_status_id');
        	$this->order->process($order_status);
        	return TRUE;
		} else {
            die('Unknown process method error: No return method was specified. Please contact store owner.');
        }
        
	}

}
?>
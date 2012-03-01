<?php 
class PaymentBanktr extends Payment {
	function __construct(&$locator) {
		$this->address  =& $locator->get('address');
		$this->config   =& $locator->get('config');
		$this->language =& $locator->get('language');
		$this->order    =& $locator->get('order');
		$this->session  =& $locator->get('session');
		$this->url      =& $locator->get('url');	    
		$model 		=& $locator->get('model');
		$this->modelPayment = $model->get('model_payment');
		
		$this->language->load('extension/payment/banktr.php');
  	}
  
	function get_Title() {
		return $this->language->get('text_banktr_title');
  	}
   
  	function getMethod() {
		if ($this->config->get('banktr_status')) {
      		if (!$this->config->get('banktr_geo_zone_id')) {
        		$status = true;
			} elseif ($this->modelPayment->get_banktrstatus()) {
        		$status = true;
      		} else {
        		$status = false;
      		}
		} else {
			$status = false;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'id'         => 'banktr',
        		'title'      => $this->language->get('text_banktr_title'),
			'message'	 => $this->language->get('text_banktr_message'),
			'sort_order' => $this->config->get('banktr_sort_order')
      		);
    	}
   
    	return $method_data;
  	}

  	function get_ActionUrl() {
    	return $this->url->ssl('checkout_process');
	}

  	function process() {
		$this->order->load($this->order->getReference());
		$this->order->process();
		return TRUE;
  	}
}
?>
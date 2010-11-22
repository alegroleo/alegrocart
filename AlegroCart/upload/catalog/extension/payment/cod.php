<?php 
class PaymentCod extends Payment {
	function __construct(&$locator) {
		$this->address  =& $locator->get('address');
		$this->config   =& $locator->get('config');
		$this->language =& $locator->get('language');
		$this->order    =& $locator->get('order');
		$this->session  =& $locator->get('session');
		$this->url      =& $locator->get('url');	    
		$model 			=& $locator->get('model');
		$this->modelPayment = $model->get('model_payment');
		
		$this->language->load('extension/payment/cod.php');
  	}
  
	function get_Title() {
		return $this->language->get('text_cod_title');
  	}
   
  	function getMethod() {
		if ($this->config->get('cod_status')) {
      		if (!$this->config->get('cod_geo_zone_id')) {
        		$status = true;
			} elseif ($this->modelPayment->get_codstatus()) {
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
        		'id'         => 'cod',
        		'title'      => $this->language->get('text_cod_title'),
				'sort_order' => $this->config->get('cod_sort_order')
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
  	}
}
?>
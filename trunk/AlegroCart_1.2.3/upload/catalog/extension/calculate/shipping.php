<?php
class CalculateShipping extends Calculate {  
	function __construct(&$locator) {		
		$this->cart     =& $locator->get('cart');
		$this->config   =& $locator->get('config');
		$this->currency =& $locator->get('currency');
		$this->language =& $locator->get('language');
		$this->session  =& $locator->get('session');
		$this->shipping =& $locator->get('shipping');
		$this->tax      =& $locator->get('tax');
		
		$this->language->load('extension/calculate/shipping.php');
  	}
			
  	function calculate() {
		$total_data = array();
		
		if (($this->config->get('shipping_status')) && ($this->cart->hasShipping())) { 
			$total_data[] = array(
        		'title' => $this->shipping->getTitle($this->session->get('shipping_method')) . ':',
        		'text'  => $this->currency->format($this->tax->calculate($this->shipping->getCost($this->session->get('shipping_method')), $this->shipping->getTaxClassId($this->session->get('shipping_method')), $this->config->get('config_tax'))),
        		'value' => $this->tax->calculate($this->shipping->getCost($this->session->get('shipping_method')), $this->shipping->getTaxClassId($this->session->get('shipping_method')), $this->config->get('config_tax'))
      		);
			
			$this->cart->increaseTotal($this->tax->calculate($this->shipping->getCost($this->session->get('shipping_method')), $this->shipping->getTaxClassId($this->session->get('shipping_method'))));
			$this->cart->addTax($this->shipping->getTaxClassId($this->session->get('shipping_method')), $this->shipping->getCost($this->session->get('shipping_method')) / 100 * $this->tax->getRate($this->shipping->getTaxClassId($this->session->get('shipping_method'))));
    	}
    
		return $total_data;
  	}

	function getSortOrder() {
		return $this->config->get('shipping_sort_order');
	}	
}
?>

<?php
class CalculateTotal extends Calculate {
	function __construct(&$locator) {
		$this->cart     =& $locator->get('cart');
		$this->config   =& $locator->get('config');
		$this->currency =& $locator->get('currency');
		$this->language =& $locator->get('language');

    	$this->language->load('extension/calculate/total.php');
  	}
	
	function calculate() {
		$total_data = array();
		
		if ($this->config->get('total_status')) {
      		$total_data[] = array(
	    		'title' => $this->language->get('text_total_title'), 
	    		'text'  => $this->currency->format($this->cart->getTotal()),
	    		'value' => $this->cart->getTotal()
	  		);
		}
		
		return $total_data;
  	}

	function getSortOrder() {
		return $this->config->get('total_sort_order');
	}	
}
?>
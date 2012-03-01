<?php
class CalculateSubtotal extends Calculate {
	function __construct(&$locator) {		
		$this->cart     =& $locator->get('cart');
		$this->config   =& $locator->get('config');
		$this->currency =& $locator->get('currency');
		$this->language =& $locator->get('language');
	
		$this->language->load('extension/calculate/subtotal.php');
  	}

	function calculate() {
		$total_data = array();

		if ($this->config->get('subtotal_status')) {
      		$total_data[] = array(
        		'title' => $this->language->get('text_subtotal_title'),
	    		'text'  => $this->currency->format($this->cart->getSubtotal()),
        		'value' => $this->cart->getSubtotal()
      		);
		}
		
    	return $total_data;
	}

	function getSortOrder() {
		return $this->config->get('subtotal_sort_order');
	}	
}
?>
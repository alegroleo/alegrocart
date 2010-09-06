<?php
class CalculateTax extends Calculate {
	function __construct(&$locator) {
		$this->cart     =& $locator->get('cart');
		$this->config   =& $locator->get('config');
		$this->currency =& $locator->get('currency');
		$this->language =& $locator->get('language');
		$this->tax      =& $locator->get('tax');
		
		$this->language->load('extension/calculate/tax.php');
  	}
	
  	function calculate() {	
		$total_data = array();
		
		if ($this->config->get('tax_status')) {
			foreach ($this->cart->getTaxes() as $key => $value) {
				if ($this->tax->has($key)) {
          			$total_data[$key] = array(
	        			'title' => $this->language->get('text_tax_title', $this->tax->getDescription($key)), 
	        			'text'  => $this->currency->format($value),
	        			'value' => $value
	      			);
				}
			}
		}
		
		return $total_data;
  	}

	function getSortOrder() {
		return $this->config->get('tax_sort_order');
	}	
}
?>
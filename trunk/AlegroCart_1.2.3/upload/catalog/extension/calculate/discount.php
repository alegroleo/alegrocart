<?php
class CalculateDiscount extends Calculate {  
	function __construct(&$locator) {		
		$this->cart     =& $locator->get('cart');
		$this->config   =& $locator->get('config');
		$this->currency =& $locator->get('currency');
		$this->language =& $locator->get('language');
		$this->session  =& $locator->get('session');
		$this->shipping =& $locator->get('shipping');
		$this->tax      =& $locator->get('tax');
		
		$this->language->load('extension/calculate/discount.php');
  	}
			
  	function calculate() {
		$total_data = array();
		$disctot = 0;
        
       // discount1
        if (($this->config->get('discount_lprice') > 0) && ($this->cart->getSubtotal() > $this->config->get('discount_lprice')) && ($this->config->get('discount_gprice') > 0 ? $this->cart->getSubtotal() < $this->config->get('discount_gprice') : True)) {
            $dispercent = $this->config->get('discount_lprice_percent');
            $disctot = ($dispercent/100) * ($this->cart->getSubtotal());
        }

        // discount2
        if (($this->config->get('discount_gprice') > 0) && ($this->cart->getSubtotal() > $this->config->get('discount_gprice'))) { 
			$dispercent = $this->config->get('discount_gprice_percent');
			$disctot = ($dispercent/100) * ($this->cart->getSubtotal());
        }
           
        if ($disctot != 0) {
            $total_data[] = array(
                'title' => $this->language->get('text_discount_title') . ' ' . $dispercent . '%',
                'text'  => '-' . $this->currency->format($disctot),
                'value' => $disctot
            );
            $this->cart->decreaseTotal($disctot);
        }
        return $total_data;
  	}

	function getSortOrder() {
		return $this->config->get('discount_sort_order');
	}	
}
?>

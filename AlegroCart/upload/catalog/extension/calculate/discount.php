<?php //AlegroCart General Discount
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
		$this->decimal_place = $this->currency->currencies[$this->currency->code]['decimal_place'];
		$rawtaxvalue = 0;
		$rawdiscountvalue = 0;
		$disctot = 0;
		$this->dispercent = 0;
        $net_total = $this->cart->getNetTotal();

       // discount1
        if (($this->config->get('discount_lprice') > 0) && ($net_total > $this->config->get('discount_lprice')) && ($this->config->get('discount_gprice') > 0 ? $net_total < $this->config->get('discount_gprice') : True)) {
            $this->dispercent = $this->config->get('discount_lprice_percent');
        // discount2
        } else if (($this->config->get('discount_gprice') > 0) && ($net_total > $this->config->get('discount_gprice'))) { 
			$this->dispercent = $this->config->get('discount_gprice_percent');
        }
		if($this->dispercent){
			foreach($this->cart->getProducts() as $product) {
				$discount = $this->getDiscount($product['total_discounted']);
				if($discount){
					$old_tax = roundDigits($product['total_discounted'] / 100 * $this->tax->getRate($product['tax_class_id']),$this->decimal_place);
					$this->cart->products[$product['key']]['total_discounted'] = $product['total_discounted'] - $discount;
					$new_tax = roundDigits($this->cart->products[$product['key']]['total_discounted'] / 100 * $this->tax->getRate($product['tax_class_id']),$this->decimal_place);
					$tax_decrease = $old_tax-$new_tax;
					$this->cart->products[$product['key']]['general_discount'] = $discount + ($this->config->get('config_tax') ? $tax_decrease : 0);
					$rawtaxvalue += $tax_decrease;
					$this->cart->decreaseTaxes($product['tax_class_id'], $tax_decrease);
					$this->cart->decreaseProductTax($product['key'], $tax_decrease);
					$rawdiscountvalue += $discount;
				}
			}
			if($this->cart->total <= ($rawdiscountvalue + $rawtaxvalue)){
				$this->cart->decreaseTotal($this->cart->total);
			} else {
				$this->cart->decreaseTotal($rawdiscountvalue + $rawtaxvalue);
			}
		}
        return $this->totalData($rawdiscountvalue, $rawtaxvalue);
  	}
	function getDiscount($value){
		if($value){
			return roundDigits(($value * $this->dispercent / 100), $this->decimal_place);
		}
	}

	function totalData($disctot, $taxtot){
		$total_data = array();
		if ($disctot != 0) {
			$total_data[] = array(
               'title' => $this->language->get('text_discount_title') . ' ' . $this->dispercent . '%',
               'text'  => '-' . $this->currency->format($this->config->get('config_tax') ? ($disctot + $taxtot) : $disctot),
               'value' => $disctot
            );
		}
		return $total_data;
	}
	function getSortOrder() {
		return $this->config->get('discount_sort_order');
	}	
}
?>

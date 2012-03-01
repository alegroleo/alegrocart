<?php //AlegroCart Coupon
class CalculateCoupon extends Calculate {
	function __construct(&$locator) {		
		$this->cart     =& $locator->get('cart');
		$this->coupon   =& $locator->get('coupon');
		$this->config   =& $locator->get('config');
		$this->currency =& $locator->get('currency');
		$this->language =& $locator->get('language');
		$this->session  =& $locator->get('session');
		$this->shipping =& $locator->get('shipping');
		$this->tax      =& $locator->get('tax');
	
		$this->language->load('extension/calculate/coupon.php');
  	}

    // Modified for proper coupon usage
	function calculate() {
		$this->decimal_place = $this->currency->currencies[$this->currency->code]['decimal_place'];
		$total_data = array();
		$rawcouponvalue = 0; 
        $freeshipavail = 0;
		$rawtaxvalue = 0;
        
		if ($this->coupon->get_minimum() > $this->cart->subtotal){
			return $total_data;
		}
		if (($this->config->get('coupon_status')) && ($this->coupon->getId())) {
            if (!empty($this->coupon->product)) { // peritem code if empty
                foreach ($this->coupon->product as $result) {// get Coupon to Product
                    $data[] = $result['product_id'];
                }
                foreach ($this->cart->getProducts() as $product) {
                    if (in_array($product['product_id'], $data)) {
                        $discount = $this->coupon->getDiscount($product['total_discounted']);
						$coupon_value = $product['total_discounted'] >= $discount ?  $discount : $product['total_discounted'];
						$old_tax = roundDigits($product['total_discounted'] / 100 * $this->tax->getRate($product['tax_class_id']),$this->decimal_place);
						$this->cart->products[$product['key']]['total_discounted'] = $product['total_discounted'] - $coupon_value;
						if($this->cart->products[$product['key']]['total_discounted'] > 0){
							$new_tax = roundDigits($this->cart->products[$product['key']]['total_discounted'] / 100 * $this->tax->getRate($product['tax_class_id']),$this->decimal_place);
							$tax_decrease = $old_tax-$new_tax;
						} else {
							$tax_decrease = $old_tax;
						}
						$this->cart->products[$product['key']]['coupon'] = $coupon_value + ($this->config->get('config_tax') ? $tax_decrease : 0);
						$rawtaxvalue += $tax_decrease;
						$this->cart->decreaseTaxes($product['tax_class_id'], $tax_decrease);
						$this->cart->decreaseProductTax($product['key'], $tax_decrease);
						$rawcouponvalue += $coupon_value;
                        $freeshipavail++; //freeshipping flag
                    }
                }
			    if ($rawcouponvalue > 0) { // If Discount
                    $total_data[] = array(
                        'title' => $this->language->get('text_coupon_title', $this->coupon->getName()),
                        'text'  => '-' . $this->currency->format($this->config->get('config_tax') ? ($rawcouponvalue + $rawtaxvalue) : $rawcouponvalue),
                        'value' => $rawcouponvalue
                    );
                }
                if($this->cart->total <= $rawcouponvalue + $rawtaxvalue){
					$this->cart->decreaseTotal($this->cart->total);
                } else {
					$this->cart->decreaseTotal($rawcouponvalue + $rawtaxvalue);
				}
                if ($freeshipavail) { // Free shipping set
                    if (($this->coupon->getShipping()) && ($this->cart->hasShipping())) {
                        $total_data[] = array(
                            'title' => $this->language->get('text_coupon_shipping'),
                            'text'  => '-' . $this->currency->format($this->tax->calculate($this->shipping->getCost($this->session->get('shipping_method')), $this->shipping->getTaxClassId($this->session->get('shipping_method')), $this->config->get('config_tax'))),
                            'value' => $this->tax->calculate($this->shipping->getCost($this->session->get('shipping_method')), $this->shipping->getTaxClassId($this->session->get('shipping_method')),$this->config->get('config_tax'))
                        );
						$shipping_total = $this->tax->calculate($this->shipping->getCost($this->session->get('shipping_method')), $this->shipping->getTaxClassId($this->session->get('shipping_method')),$this->config->get('config_tax'));
						if(!$this->config->get('config_tax')){
							$shipping_tax = roundDigits($this->shipping->getCost($this->session->get('shipping_method')) / 100 * $this->tax->getRate($this->shipping->getTaxClassId($this->session->get('shipping_method'))),$this->decimal_place);
						} else {
							$shipping_tax = 0;
						}
						
						if($this->cart->total <= $shipping_total + $shipping_tax){
							$this->cart->decreaseTotal($this->cart->total);
							$this->cart->decreaseTaxes($this->shipping->getTaxClassId($this->session->get('shipping_method'), $this->cart->taxes[$this->shipping->getTaxClassId($this->session->get('shipping_method'))]));
						} else {
							$this->cart->decreaseTotal($shipping_total + $shipping_tax);
							$this->cart->decreaseTaxes($this->shipping->getTaxClassId($this->session->get('shipping_method')), roundDigits($this->shipping->getCost($this->session->get('shipping_method')) / 100 * $this->tax->getRate($this->shipping->getTaxClassId($this->session->get('shipping_method'))),$this->decimal_place));
						}
                    }
                }
            } else { // per cart code
                foreach ($this->cart->getProducts() as $product) {
					$discount = $this->coupon->getDiscount($product['total_discounted']);
					$coupon_value = $product['total_discounted'] >= $discount ?  $discount : $product['total_discounted'];
					
					$old_tax = roundDigits($product['total_discounted'] / 100 * $this->tax->getRate($product['tax_class_id']), $this->decimal_place);
					$this->cart->products[$product['key']]['total_discounted'] = $product['total_discounted'] - $coupon_value;
					if($this->cart->products[$product['key']]['total_discounted'] > 0){
						$new_tax = roundDigits($this->cart->products[$product['key']]['total_discounted'] / 100 * $this->tax->getRate($product['tax_class_id']),$this->decimal_place);
						$tax_decrease = $old_tax-$new_tax;
					} else {
						$tax_decrease = $old_tax;
					}
					$this->cart->products[$product['key']]['coupon'] = $coupon_value + ($this->config->get('config_tax') ? $tax_decrease : 0);
					
					$rawtaxvalue += $tax_decrease;
					
					$this->cart->decreaseTaxes($product['tax_class_id'], $tax_decrease);
					$this->cart->decreaseProductTax($product['key'], $tax_decrease);
					$rawcouponvalue += ($coupon_value);
                }
			    if ($rawcouponvalue > 0) { // If Discount
      			    $total_data[] = array(
        			    'title' => $this->language->get('text_coupon_title', $this->coupon->getName()),
						'text'  => '-' . $this->currency->format($this->config->get('config_tax') ? ($rawcouponvalue + $rawtaxvalue) : $rawcouponvalue),
        			    'value' => $rawcouponvalue
      			    );
				    if($this->cart->total <= ($rawcouponvalue + $rawtaxvalue)){
						$this->cart->decreaseTotal($this->cart->total);
					} else {
						$this->cart->decreaseTotal($rawcouponvalue + $rawtaxvalue);
					}
			    }
			    if (($this->coupon->getShipping()) && ($this->cart->hasShipping())) {
      			    $total_data[] = array(
        			    'title' => $this->language->get('text_coupon_shipping'),
	    			    'text'  => '-' . $this->currency->format($this->tax->calculate($this->shipping->getCost($this->session->get('shipping_method')), $this->shipping->getTaxClassId($this->session->get('shipping_method')), $this->config->get('config_tax'))),
                            'value' => $this->tax->calculate($this->shipping->getCost($this->session->get('shipping_method')), $this->shipping->getTaxClassId($this->session->get('shipping_method')),$this->config->get('config_tax'))
      			    );
					
				    $shipping_total = $this->tax->calculate($this->shipping->getCost($this->session->get('shipping_method')), $this->shipping->getTaxClassId($this->session->get('shipping_method')),$this->config->get('config_tax'));
					if(!$this->config->get('config_tax')){
						$shipping_tax = roundDigits($this->shipping->getCost($this->session->get('shipping_method')) / 100 * $this->tax->getRate($this->shipping->getTaxClassId($this->session->get('shipping_method'))),$this->decimal_place);
					} else {
						$shipping_tax = 0;
					}
					
				    if($this->cart->total <= $shipping_total + $shipping_tax){
						$this->cart->decreaseTotal($this->cart->total);
						$this->cart->decreaseTaxes($this->shipping->getTaxClassId($this->session->get('shipping_method'), $this->cart->taxes[$this->shipping->getTaxClassId($this->session->get('shipping_method'))]));
					} else {
						$this->cart->decreaseTotal($shipping_total + $shipping_tax);
						$this->cart->decreaseTaxes($this->shipping->getTaxClassId($this->session->get('shipping_method')), roundDigits($this->shipping->getCost($this->session->get('shipping_method')) / 100 * $this->tax->getRate($this->shipping->getTaxClassId($this->session->get('shipping_method'))),$this->decimal_place)) ;
					}
			    }
            }
    	}

    	return $total_data;
	}
	function getSortOrder() {
		return $this->config->get('coupon_sort_order');
	}
}
?>
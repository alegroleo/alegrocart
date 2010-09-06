<?php //AlegroCart
class ShippingZone extends Shipping {    
	function __construct(&$locator) { 
		$this->address  =& $locator->get('address');
		$this->cart     =& $locator->get('cart');
		$this->config   =& $locator->get('config');
		$this->currency =& $locator->get('currency');
		$this->language =& $locator->get('language');
		$this->session  =& $locator->get('session');
		$this->tax      =& $locator->get('tax');
		$model 			=& $locator->get('model');
		$this->modelShipping = $model->get('model_shipping');
				
		$this->language->load('extension/shipping/zone.php');
  	}
  	
  	function quote() {
		$quote_data = array();
		
		$results = $this->modelShipping->get_geo_zones();
		
		foreach ($results as $result) {
   			if ($this->config->get('zone_' . $result['geo_zone_id'] . '_status')) {
   				if($this->modelShipping->get_zonestatus($result['geo_zone_id'])){
       				$status = true;
   				} else {
       				$status = false;
   				}
			} else {
				$status = false;
			}
			
			if ($status) {
				$cost = 0;
				
				$rates = explode(',', $this->config->get('zone_' . $result['geo_zone_id'] . '_cost'));

				foreach ($rates as $rate) {
  					$array = explode(':', $rate);
  					
					if ($this->cart->getWeight() <= $array[0]) {
    					$cost = @$array[1];
						
   						break;
  					}
				}
			
      			$quote_data[$result['geo_zone_id']] = array(
        			'id'    => 'zone_' . $result['geo_zone_id'],
        			'title' => $result['name'],
        			'cost'  => $cost,
        			'text'  => $this->currency->format($this->tax->calculate($cost, $this->config->get('zone_tax_class_id'), $this->config->get('config_tax')))
      			);			
			}
		}
		
		$method_data = array();
	
		if ($quote_data) {
      		$method_data = array(
        		'id'           => 'zone',
        		'title'        => $this->language->get('text_zone_title'),
        		'quote'        => $quote_data,
        		'tax_class_id' => $this->config->get('zone_tax_class_id'),
				'sort_order'   => $this->config->get('zone_sort_order'),
        		'error'        => false
      		);
		}
	
		return $method_data;
  	}
}
?>

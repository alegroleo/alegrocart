<?php //AlegroCart Zone Shipping
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
		
		if(!isset($this->results)){
			$this->results = $this->modelShipping->get_geo_zones();
		}
		
		foreach ($this->results as $result) {
   			if ($this->config->get('zone_' . $result['geo_zone_id'] . '_status')) {
				if(!isset($this->zonestatus[$result['geo_zone_id']])){
					$this->zonestatus[$result['geo_zone_id']] = $this->modelShipping->get_zonestatus($result['geo_zone_id']);
				}
				if($this->zonestatus[$result['geo_zone_id']]){
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
				$top_rate = explode(':', end($rates));
				$cart_total = $this->cart->getNetTotal();
				$free_amount = $this->config->get('zone_' . $result['geo_zone_id'] . '_free_amount');
				if(!isset($this->zonerate[$result['geo_zone_id']])){
					if ($top_rate[0] >= $this->cart->getWeight()){
						if($free_amount == 0 || ($free_amount >= $cart_total)){
							foreach ($rates as $rate) {
								$array = explode(':', $rate);
								if ($this->cart->getWeight() <= $array[0]) {
									$cost = @$array[1];
									break;
								}
							}
						}
					} else {
						$this->quote_error[$result['geo_zone_id']] = $this->language->get('error_weight', $this->cart->formatWeight($top_rate[0]));
					}
					$this->zonerate[$result['geo_zone_id']] = $cost;
				}
				
      			$quote_data[$result['geo_zone_id']] = array(
        			'id'    => 'zone_' . $result['geo_zone_id'],
        			'title' => $result['name'],
        			'cost'  => $this->zonerate[$result['geo_zone_id']],
					'shipping_form'=> !isset($this->quote_error[$result['geo_zone_id']]) ? $this->form_fields($this->config->get('zone_' . $result['geo_zone_id'] . '_message')) : '',
        			'text'  => $this->currency->format($this->tax->calculate($this->zonerate[$result['geo_zone_id']], $this->config->get('zone_tax_class_id'), $this->config->get('config_tax'))),
					'error' => isset($this->quote_error[$result['geo_zone_id']]) ? $result['name'] . ' : ' . $this->quote_error[$result['geo_zone_id']] : FALSE
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
        		'error'        => isset($this->weight_error) ? $this->weight_error : false
      		);
		}
	
		return $method_data;
  	}
	function form_fields($message){
		if ($message){
			$output = '<tr>';
			$output .= '<td class="g">';
			$output .= $message;
			$output .= '</td>';
			$output .= '</tr>';
		} else {
			$output = '';
		}
		return $output;
	}
}
?>
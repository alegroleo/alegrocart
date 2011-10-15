<?php //AlegroCart ZonePlus Shipping
class ShippingZonePlus extends Shipping { 
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
		
		$this->language->load('extension/shipping/zoneplus.php');
  	}

	function quote() {
		$quote_data = array();
		
		if(!isset($this->results)){
			$this->results = $this->modelShipping->get_geo_zones();
		}
		
		foreach ($this->results as $result) {
			if ($this->config->get('zoneplus_' . $result['geo_zone_id'] . '_data')) {
				$data = unserialize($this->config->get('zoneplus_' . $result['geo_zone_id'] . '_data'));
				
				
				if(!isset($this->zonestatus[$result['geo_zone_id']])){
					$this->zonestatus[$result['geo_zone_id']] = $this->modelShipping->get_zonestatus($result['geo_zone_id']);
				}
				if($this->zonestatus[$result['geo_zone_id']] && $data['status']){
					$status = true;
				} else {
					$status = false;
				}
				
			} else {
				$status = false;
			}
			
			if ($status) {
				$cost = 0;
				$cart_weight = $this->cart->getWeight();
				$cart_total = $this->cart->getNetTotal();
				if(!isset($this->zonerate[$result['geo_zone_id']])){
					if($cart_weight <= $data['max_weight']){
						$added_cost = 0;
						if($data['free_amount'] == 0 || ($data['free_amount'] >= $cart_total)){
							if($cart_weight > $data['base_weight']){
								$calc_weight = $cart_weight - $data['base_weight'];
								$weight_factor = ceil($calc_weight / (int)$data['added_weight']);
								$added_cost = $weight_factor * $data['added_cost'];
							}
							$cost = $data['base_cost'] + $added_cost;
						}
					} else {
						$this->quote_error[$result['geo_zone_id']] = $this->language->get('error_weight', $this->cart->formatWeight($data['max_weight']));
					}
					$this->zonerate[$result['geo_zone_id']] = $cost;
				}
				
				$quote_data[$result['geo_zone_id']] = array(
					'id'    => 'zoneplus_' . $result['geo_zone_id'],
					'title' => $result['name'],
					'cost'  => $this->zonerate[$result['geo_zone_id']],
					'shipping_form'=> !isset($this->quote_error[$result['geo_zone_id']]) ? $this->form_fields($data['message']) : '',
					'text'  => $this->currency->format($this->tax->calculate($this->zonerate[$result['geo_zone_id']], $this->config->get('zone_tax_class_id'), $this->config->get('config_tax'))),
					'error' => isset($this->quote_error[$result['geo_zone_id']]) ? $result['name'] . ' : ' . $this->quote_error[$result['geo_zone_id']] : FALSE
				);	
			}
		}
		
		$method_data = array();
		
		if ($quote_data) {
      		$method_data = array(
        		'id'           => 'zoneplus',
        		'title'        => $this->language->get('text_zoneplus_title'),
        		'quote'        => $quote_data,
        		'tax_class_id' => $this->config->get('zoneplus_tax_class_id'),
				'sort_order'   => $this->config->get('zoneplus_sort_order'),
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
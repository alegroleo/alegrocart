<?php
class Tax {
	var $taxes = array();
	
	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->currency =& $locator->get('currency');
		$this->database =& $locator->get('database');
		$this->session  =& $locator->get('session');
		$this->decimal_place = $this->currency->currencies[$this->currency->code]['decimal_place'];

		$address_info = $this->database->getRow("select country_id, zone_id from address where address_id = '" . (int)$this->session->get('payment_address_id') . "' and customer_id = '" . (int)$this->session->get('customer_id') . "'");
		
		if ($address_info) {
			$country_id = $address_info['country_id'];
			$zone_id    = $address_info['zone_id'];
		} else {
			$country_id = $this->config->get('config_country_id');
			$zone_id    = $this->config->get('config_zone_id');
		}
				
		$results = $this->database->getRows("select tr.tax_class_id, sum(tr.rate) as rate, tr.description from tax_rate tr left join zone_to_geo_zone z2gz on (tr.geo_zone_id = z2gz.geo_zone_id) left join geo_zone gz on (tr.geo_zone_id = gz.geo_zone_id) where (z2gz.country_id = '0' or z2gz.country_id = '" . (int)$country_id . "') and (z2gz.zone_id = '0' or z2gz.zone_id = '" . (int)$zone_id . "') group by tr.tax_class_id");
	
		foreach ($results as $result) {
      		$this->taxes[$result['tax_class_id']] = array(
        		'rate'        => $result['rate'],
        		'description' => $result['description']
      		);
    	}	
  	}
	
  	function calculate($value, $tax_class_id, $calculate = TRUE) {	
		if (($calculate) && (isset($this->taxes[$tax_class_id])))  {
      		return $value + roundDigits(($value * $this->taxes[$tax_class_id]['rate'] / 100), $this->decimal_place);
    	} else {
      		return $value;
    	}
  	}
        
  	function getRate($tax_class_id) {
    	return (isset($this->taxes[$tax_class_id]) ? $this->taxes[$tax_class_id]['rate'] : NULL);
  	}
  
  	function getDescription($tax_class_id) {
		return (isset($this->taxes[$tax_class_id]) ? $this->taxes[$tax_class_id]['description'] : NULL);
  	}
  
  	function has($tax_class_id) {
		return isset($this->taxes[$tax_class_id]);
  	}
}
?>
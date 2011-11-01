<?php //Library Weight AlegroCart
class Weight {
	var $classes = array();
	var $rules   = array();
	
	function __construct(&$locator) {
    	$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->config  	=& $locator->get('config');
	
		$results = $this->database->cache('weight', "select * from weight_class");
		foreach ($results as $result) {
			if($result['language_id'] == 1){
				$this->classes[$result['weight_class_id']] = array(
					'unit'  => $result['unit'],
					'title' => $result['title']
				);
			}
    	}
		if((int)$this->language->getId() != 1){
			foreach ($results as $result) {
				if($result['language_id'] == (int)$this->language->getId()){
					$this->classes[$result['weight_class_id']] = array(
						'unit'  => $result['unit'],
						'title' => $result['title']
					);
				}
			}
		}


    	$results = $this->database->cache('weight_rule', "select * from weight_rule");
	
    	foreach ($results as $result) {
      		$this->rules[$result['from_id']][$result['to_id']] = $result['rule'];
    	}
  	}
	  
  	function convert($value, $from, $to) {
    	if ($from != $to) { $value=($value * (float)$this->rules[$from][$to]); }
		return $value;
  	}

	function format($value, $weight_class_id) {
    	return number_format($value, $this->config->get('config_weight_decimal'), $this->language->get('decimal_point'), $this->language->get('thousand_point')) . " " . $this->classes[$weight_class_id]['unit'];
  	}
	
	function getClass($weight_class_id){
		return $this->classes[$weight_class_id]['unit'];
	}
}
?>

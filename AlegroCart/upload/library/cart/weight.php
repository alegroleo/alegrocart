<?php //Library Weight AlegroCart
class Weight {
	var $classes = array();
	var $rules   = array();
	
	function __construct(&$locator) {
    	$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
	
    	$results = $this->database->cache('weight', "select * from weight_class where language_id = '" . (int)$this->language->getId() . "'");
    
    	foreach ($results as $result) {
      		$this->classes[$result['weight_class_id']] = array(
        		'unit'  => $result['unit'],
        		'title' => $result['title']
      		);
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
    	return number_format($value, 2, $this->language->get('decimal_point'), $this->language->get('thousand_point')) . " " . $this->classes[$weight_class_id]['unit'];
  	}
}
?>

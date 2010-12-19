<?php //Library Dimension AlegroCart
class Dimension {
	var $classes = array();
	var $rules   = array();
	var $types   = array();
	var $type_class = array();
	var $default_dimension = array();
	
	function __construct(&$locator) {
    	$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->config   =& $locator->get('config');
		
		$results = $this->database->cache('dimension_type', "select * from dimension_type");
		foreach ($results as $result) {
			$this->types[$result['type_id']] = array(
				'type_name' => $result['type_name']
			);
			$this->default_dimension[$result['type_id']] = $this->config->get( 'config_dimension_' . $result['type_id'] . '_id');
		}
		$results = $this->database->cache('dimension', "select * from dimension where language_id = '" . (int)$this->language->getId() . "'");
		foreach ($results as $result) {
			$this->classes[$result['dimension_id']] = array(
				'unit'	=> $result['unit'],
				'title'	=> $result['title']
			);
			$this->type_class[$result['type_id']][$result['dimension_id']] = array(
				'dimension_id' => $result['dimension_id']
			);
		}
		$results = $this->database->cache('dimension_rule', "select * from dimension_rule");
		foreach ($results as $result) {
			$this->rules[$result['from_id']][$result['to_id']] = $result['rule'];
		}
	}
	function getValues( $dimension_value, $type_id, $dimension_id){
		$dimensions = explode(':', $dimension_value);

		switch($type_id){
			case '1':
				if (count($dimensions) == 3 && $this->checkZero($dimensions)){
					$values = array();
					foreach($dimensions as $dimension){
						$values[] = $this->format($this->convert($dimension,$dimension_id, $this->default_dimension[$type_id]), $this->default_dimension[$type_id]);
					}
				} else {
					return FALSE;
				}
				break;
			case '2':
				if($dimensions){
					if ($dimensions[0] > 0){
						$values[] =  $this->format($this->convert($dimensions[0],$dimension_id, $this->default_dimension[$type_id]), $this->default_dimension[$type_id]);
					} else {
						return FALSE;
					}
					if(count($dimensions) == 7 && $this->checkZero($dimensions)){
						$values[] = $this->format($this->convert($dimensions[1],$dimensions[2], $this->default_dimension[1]), $this->default_dimension[1]);
						$values[] = $this->format($this->convert($dimensions[3],$dimensions[4], $this->default_dimension[1]), $this->default_dimension[1]);
						$values[] = $this->format($this->convert($dimensions[5],$dimensions[6], $this->default_dimension[1]), $this->default_dimension[1]);
					}
				} else {
					return FALSE;
				}
				
				break;
			case '3':
				if($dimensions){
					if ($dimensions[0] > 0){
						$values[] =  $this->format($this->convert($dimensions[0],$dimension_id, $this->default_dimension[$type_id]), $this->default_dimension[$type_id]);
					} else {
						return FALSE;
					}
					if(count($dimensions) == 7 && $this->checkZero($dimensions)){
						$values[] = $this->format($this->convert($dimensions[1],$dimensions[2], $this->default_dimension[1]), $this->default_dimension[1]);
						$values[] = $this->format($this->convert($dimensions[3],$dimensions[4], $this->default_dimension[1]), $this->default_dimension[1]);
						$values[] = $this->format($this->convert($dimensions[5],$dimensions[6], $this->default_dimension[1]), $this->default_dimension[1]);
					}
				} else {
					return FALSE;
				}
				break;
			default:
				return FALSE;
				break;
		}
		return $values;
	}
	function checkZero($dimensions){
		foreach($dimensions as $dimension){
			if (!$dimension > 0){
				return FALSE;
			}
		}
		return TRUE;
	}
	function convert($value, $from, $to) {
    	if ($from != $to && $from > 0 ) { $value=($value * (float)$this->rules[$from][$to]); }
		return $value;
  	}
	function format($value, $dimension_id) {
    	return number_format($value, $this->config->get('config_dimension_decimal'), $this->language->get('decimal_point'), $this->language->get('thousand_point')) . " " . $this->classes[$dimension_id]['unit'];
  	}
}
?>
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
				'title'	=> $result['title'],
				'type_id' => $result['type_id']
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
	function convert_raw($dimension_value,$dimension_id,$method_class){
		$dimensions = explode(':', $dimension_value);
		if(!$dimension_id){return FALSE;}
		switch($this->classes[$dimension_id]['type_id']){
			case '1':
				if (count($dimensions) == 3 && $this->checkZero($dimensions)){
					$values = array();
					foreach($dimensions as $dimension){
						$values[] = $this->convert($dimension,$dimension_id, $method_class);
					}
				} else {
					return FALSE;
				}
				break;
			case '2':
				if(count($dimensions) == 7 && $this->checkZero($dimensions)){
					$values[] = $this->convert($dimensions[1],$dimensions[2], $method_class);
					$values[] = $this->convert($dimensions[3],$dimensions[4], $method_class);
					$values[] = $this->convert($dimensions[5],$dimensions[6], $method_class);
				} else {
					return FALSE;
				}
				
				break;
			case '3':
				if(count($dimensions) == 7 && $this->checkZero($dimensions)){
					$values[] = $this->convert($dimensions[1],$dimensions[2], $method_class);
					$values[] = $this->convert($dimensions[3],$dimensions[4], $method_class);
					$values[] = $this->convert($dimensions[5],$dimensions[6], $method_class);
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
	
	function get_dimension_class($dimension_id){
		$result = $this->database->getRow("select title, unit, type_id from dimension where dimension_id = '" . (int)$dimension_id . "' and language_id = '" . (int)$this->language->getId() . "'");
		return $result;
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
    	if ($from != $to && $from > 0 && isset($this->rules[$from][$to])) { $value=($value * (float)$this->rules[$from][$to]); }
		return $value;
  	}
	function format($value, $dimension_id) {
    	return number_format($value, $this->config->get('config_dimension_decimal'), $this->language->get('decimal_point'), $this->language->get('thousand_point')) . " " . $this->classes[$dimension_id]['unit'];
  	}
	function package($products, $max_weight, $max_length, $max_width, $max_height, $max_circumference = FALSE, $minimum_d = ''){
		$package = 1;
		$packages = array();
		$minimum_dim = $minimum_d ? explode(':',$minimum_d) : '';
		while($products){
			$package_weight = 0;
			$package_height = 0;
			$height_row = array();
			$weight = array();
			$area_row = array();
			$length = array();
			$width = array();
			$height = array();
			$product_keys = array();
			$row = 1;
			$area = 0;
			$item_count = 0;
			$row_count = 0;
			$error_area = FALSE;
			$error_length = FALSE;
			$error_height = FALSE;
			$error_weight = FALSE;
			foreach($products as $key => $product){
				$row_count ++;
				if($row == 1 && $minimum_dim){
					if ($product['length'] < $minimum_dim[0]){
						$length[$row][$key] = $minimum_dim[0];
					} else {
						$length[$row][$key] = $product['length'];
					}
					if ($product['width'] < $minimum_dim[1]){
						$width[$row][$key] = $minimum_dim[1];
					} else {
						$width[$row][$key] = $product['width'];
					}
				} else {
					$length[$row][$key] = $product['length'];
					$width[$row][$key] = $product['width'];
				}
				$product_keys[$item_count] = $key;
				$weight[$row][$key] = $product['weight'];
				$package_weight += $product['weight'];
				
				$height[$row][$key] = $product['height'];
				$package_height = ($height[$row][$key] > $package_height)? $height[$row][$key] : $package_height;
				$height_row[$row] = $package_height;
				$area = $this->getArea($length[$row][$key], $width[$row][$key]);
				$area_row[$row] = isset($area_row[$row])? $area_row[$row] + $area : $area;
				
				if ($row > 1){
					if ($package_weight > $max_weight){
						$error_weight = TRUE;
					} else {
						$error_weight = FALSE;
					}
					$error_area = FALSE;
					if ($area_row[$row] < $area_row[1]){
						$row_length = 0;
						foreach($length[$row] as $lengths){
							$row_length += $lengths;
						}
						$error_length = FALSE;
						if ($row_length > $length[1][$product_keys[0]]){
							$error_length = TRUE;
						}
					} else {
						$error_area = TRUE;
					}
					$total_height = 0;
					foreach($height_row as $heights){
						$total_height += $heights;
					}
					$max_height = $max_height ? $max_height : ($max_circumference - ($width[1][$product_keys[0]] * 2)) / 2;
					if($total_height > $max_height){
						$error_height = TRUE;
					} else {
						$error_height = FALSE;
					}
					if($error_length || $error_area || $error_weight || $error_height){
						$weight[$row+1][$key] = $weight[$row][$key];
						$weight[$row][$key] = NULL;
						if($error_weight || $error_height){
							$package_weight = $package_weight -$product['weight'];
						}
						$length[$row+1][$key] = $length[$row][$key];
						$length[$row][$key] = NULL;
						$width[$row+1][$key] = $width[$row][$key];
						$width[$row][$key] = NULL;
						$package_height = 0;
						$height[$row+1][$key] = $height[$row][$key];
						$height[$row][$key] = NULL;
						foreach($height[$row] as $mykey => $heights){
							$package_height = $package_height >$heights ? $package_height : $heights;
							
						}
						$area_row[$row+1]= $area;
						$area_row[$row] = $area_row[$row] - $area;
						if(($error_length || $error_area) && (!$error_weight && !$error_height)){
							$row ++;
							$row_count = 0;
						}
					}
				}
				if($row == 1){
					$row ++;
					$package_height = 0;
				}
				if($error_weight || $error_height){
					if($row_count == 1){
						unset($height_row[$row]);
					} else {
						$height_row[$row] = $package_height;
					}
					unset($product_keys[$item_count]);
					break;
				}
				$item_count ++;
			}
			$package_height = 0;
			foreach($height_row as $row){
				$package_height += $row;
			}
			if(isset($length[1][$product_keys[0]]) && isset($width[1][$product_keys[0]])){
				if($length[1][$product_keys[0]] < $package_height){
					$pkg_length = $package_height;
					$pkg_height = $length[1][$product_keys[0]];
				}else{
					$pkg_length = $length[1][$product_keys[0]];
					$pkg_height = $package_height;
				}
				$packages[$package] = array(
					'length' 	=> $pkg_length,
					'width'  	=> $width[1][$product_keys[0]],
					'height' 	=> $pkg_height,
					'weight'	=> $package_weight,
					'pieces'	=> count($product_keys)
				);
			}
			foreach($product_keys as $key){
				unset($products[$key]);
			}
			$package ++;
		}
		return $packages;
	}
	function getArea($length, $width){
		return $length * $width;
	}
}
?>
<?php 
class Shipping {
	var $data = array();
	
	function __construct(&$locator) {
		$this->database =& $locator->get('database');
		
		$results = $this->database->getRows("select * from extension where type = 'shipping'");
		
		foreach ($results as $result) {
			$file  = DIR_EXTENSION . $result['directory'] . '/' . $result['filename'];
			$class = 'Shipping' . str_replace('_', '', $result['code']);
			
			if (file_exists($file)) {
				require_once($file);

				$this->data[$result['code']] = new $class($locator);
			}
		}
	}
	
	function getQuotes() {
		$quote_data = array();
		
		foreach (array_keys($this->data) as $key) { 
			$quote = $this->data[$key]->quote();
			
			if ($quote) {
				$quote_data[$quote['id']] = array(
					'title'        => $quote['title'],
					'quote'        => $quote['quote'], 
					'tax_class_id' => $quote['tax_class_id'],
					'sort_order'   => $quote['sort_order'],
					'error'        => $quote['error']
				);
			}
		}

		$sort_order = array();
	  
		foreach ($quote_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}

    	array_multisort($sort_order, SORT_ASC, $quote_data);
						
		return $quote_data;
	}

	function getQuote($key) {
		$key = explode('_', $key);
				
		if (isset($this->data[$key[0]])) {
			$quote = $this->data[$key[0]]->quote();
				
			if (isset($quote['quote'][$key[1]])) {
				$quote_data = array(
					'title'        => $quote['quote'][$key[1]]['title'],
					'cost'         => $quote['quote'][$key[1]]['cost'],
					'tax_class_id' => $quote['tax_class_id']
				);
				
				return $quote_data;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	function getTitle($key) {
		$data = $this->getQuote($key);
		
		return (isset($data['title']) ? $data['title'] : NULL);
	}
	
	function getCost($key) {
		$data = $this->getQuote($key);
		
		return (isset($data['cost']) ? $data['cost'] : NULL);
	}
	
	function getTaxClassId($key) {
		$data = $this->getQuote($key);
		
		return (isset($data['tax_class_id']) ? $data['tax_class_id'] : NULL);
	}
}
?>
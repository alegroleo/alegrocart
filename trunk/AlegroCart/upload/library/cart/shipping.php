<?php 
class Shipping {
	var $data = array();
	var $quotes = array();
	function __construct(&$locator) {
		$this->database =& $locator->get('database');
		$this->config   =& $locator->get('config');
		
		$results = $this->database->getRows("select * from extension where type = 'shipping'");

		foreach ($results as $result) {
			$file  = DIR_EXTENSION . $result['directory'] . '/' . $result['filename'];
			$class = 'Shipping' . str_replace('_', '', $result['code']);
			
			if (file_exists($file) && $this->config->get($result['code'] . '_status')) {
				require_once($file);

				$this->data[$result['code']] = new $class($locator);
			}
		}
	}
	
	function getQuotes() {
		foreach (array_keys($this->data) as $key) { 
			if(!isset($this->quotes[$key])){
				$quote = $this->data[$key]->quote();
				if ($quote) {
					$this->quotes[$quote['id']] = array(
						'title'        => $quote['title'],
						'quote'        => $quote['quote'], 
						'tax_class_id' => $quote['tax_class_id'],
						'sort_order'   => $quote['sort_order'],
						'error'        => $quote['error']
					);
				}
			}
		}

		$sort_order = array();
		foreach ($this->quotes as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}
		array_multisort($sort_order, SORT_ASC, $this->quotes);

		return $this->quotes;
	}

	function getQuote($key) {
		$key = explode('_', $key);
		if(!isset($this->quotes[$key[0]])){
			if (isset($this->data[$key[0]])) {
				$quote = $this->data[$key[0]]->quote();
				if ($quote) {
					$this->quotes[$quote['id']] = array(
						'title'        => $quote['title'],
						'quote'        => $quote['quote'], 
						'tax_class_id' => $quote['tax_class_id'],
						'sort_order'   => $quote['sort_order'],
						'error'        => $quote['error']
					);
				}
			}
		}
		
		if($this->quotes && isset($this->quotes[$key[0]])){
			$quote = $this->quotes[$key[0]];
			$quote_data = array(
				'description'  => $quote['title'],
				'title'        => $quote['quote'][$key[1]]['title'],
				'cost'         => $quote['quote'][$key[1]]['cost'],
				'tax_class_id' => $quote['tax_class_id']
			);
			return $quote_data;
		} else {
			return FALSE;
		}
	}
	
	function getTitle($key) {
		$data = $this->getQuote($key);
		
		return (isset($data['title']) ? $data['title'] : NULL);
	}
	function getDescription($key) {
		$data = $this->getQuote($key);
		
		return (isset($data['description']) ? $data['description'] : NULL);
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
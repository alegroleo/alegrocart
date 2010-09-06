<?php
class Module {
	var $data = array();
	 
	function __construct(&$locator) {
		$this->locator  =& $locator;
		$this->database =& $locator->get('database');
	
    	$this->load();
	}
	
	function load($code='') {		
		if ($code) {
			$results = $this->database->getRows("select * from extension where code = '" . $code . "'");
		} else if(APP == 'ADMIN'){
			$results = $this->database->getRows("select * from extension where type = 'module' and controller LIKE 'module_" . strtolower(APP) . "_%' group by code");
		}
		if(isset($results)){
			foreach ($results as $result) {
				$file  = DIR_EXTENSION . $result['directory'] . '/' . $result['filename'];
				$class = 'Module' . str_replace('_', '', $result['code']);

				if (file_exists($file)) {
					require_once($file);
					$this->data[$result['code']] = new $class($this->locator);
				}
			}
		}
  	}
	
  	function fetch() {
		$module_data = array();
		
		foreach (array_keys($this->data) as $key) {
			$module = $this->data[$key]->fetch();
			
			if ($module) {
				$module_data[$key] = $module;
			}
		}

		return $module_data;
  	}
}
?>
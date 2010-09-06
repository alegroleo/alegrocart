<?php //AlegroCart
include_once(DIR_MODEL . 'autoloader2.php');
class Model {
	private $modeldata = array();
	
	function __construct(&$locator) {
		$this->locator =& $locator;
	}
	
	function get($modelkey) {
			if (!isset($this->modeldata[$modelkey])) $this->modeldata[$modelkey] = $this->create($modelkey);
		return $this->modeldata[$modelkey];
	}
	
	function create($modelkey) {
		$method = 'create'.$modelkey;
		if (method_exists($this, $method)) { return $this->$method(); }
		if (class_exists($modelkey)) return new $modelkey($this->locator);
	}
	
	function createModel_Category(){
		require_once(DIR_MODEL.'product/model_category.php');
		return new Model_Category($this->locator);
	}
	
	function createModel_Products(){
		require_once(DIR_MODEL.'product/model_products.php'); 
		return new Model_Products($this->locator);
	}
	
	function createModel_Manufacturer(){
		require_once(DIR_MODEL.'product/model_manufacturer.php');
		return new Model_Manufacturer($this->locator);
	}
	
	function createModel_Search(){
		require_once(DIR_MODEL.'product/model_search.php');
		return new Model_Search($this->locator);
	}
	
	function createModel_Core(){
		require_once(DIR_MODEL.'core/model_core.php');
		return new Model_Core($this->locator);
	}
	
}
?>
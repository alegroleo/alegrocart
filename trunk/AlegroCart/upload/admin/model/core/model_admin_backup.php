<?php //AdminModelBackup AlegroCart
class Model_Admin_Backup extends Model {
	
	function __construct(&$locator) {
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
	}
	function import_file($name){
		$this->database->import($name);
	}
	function export_file(){
		$result = $this->database->export();
		return $result;
	}
}
?>
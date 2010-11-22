<?php //AdminModelMaintenance AlegroCart
class Model_Admin_Maintenance extends Model {
	function __construct(&$locator) {	
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request 	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}
	function delete_maintenance(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'maintenance'");
	}
	function update_maintenance(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'maintenance', `key` = 'maintenance_status', `value` = '" . (int)$this->request->gethtml('catalog_maintenance_status', 'post') . "'");
	}
	function get_maintenance(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'maintenance'");
		return $results;
	}
}
?>
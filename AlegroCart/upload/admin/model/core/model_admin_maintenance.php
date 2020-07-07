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
	function delete_description(){
		$this->database->query("DELETE FROM maintenance_description");
	}
	function update_description(){
		$description = $this->request->get('description', 'post');
		$header = $this->request->get('header', 'post');

		foreach($this->request->get('header', 'post', array()) as $key => $value){
			$sql = "INSERT INTO maintenance_description SET maintenance_id = '?', language_id = '?', header = '?', description = '?'";
			$this->database->query($this->database->parse($sql, $key, $key, $header[$key], $description[$key]));
		}
	}
	function get_maintenance(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'maintenance'");
		return $results;
	}
	function get_descriptions($language_id){
		$result = $this->database->getRow("SELECT header, description FROM maintenance_description WHERE language_id = '" . (int)$language_id . "'");
		return $result;
	}
	function get_languages(){
		$results = $this->database->cache('language', "SELECT * FROM language ORDER BY sort_order");
		return $results;
	}
}
?>

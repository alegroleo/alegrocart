<?php //AdminModelCategoryMenuModule AlegroCart
class Model_Admin_CategoryMenuModule extends Model {
	function __construct(&$locator) {
		$this->config		=& $locator->get('config');
		$this->database		=& $locator->get('database');
		$this->language		=& $locator->get('language');
		$this->request		=& $locator->get('request');
		$this->session		=& $locator->get('session');
	}
	function delete_categorymenu(){
		$this->database->query("DELETE FROM setting WHERE type = 'catalog' AND `group` = 'categorymenu'");
	}
	function update_categorymenu(){
		$this->database->query($this->database->parse("INSERT INTO setting SET type = 'catalog', `group` = 'categorymenu', `key` = 'categorymenu_status', `value` = '?'", (int)$this->request->gethtml('catalog_categorymenu_status', 'post')));
		$this->database->query($this->database->parse("INSERT INTO setting SET type = 'catalog', `group` = 'categorymenu', `key` = 'categorymenu_mcount', `value` = '?'", (int)$this->request->gethtml('catalog_categorymenu_mcount', 'post')));
		$this->database->query($this->database->parse("INSERT INTO setting SET type = 'catalog', `group` = 'categorymenu', `key` = 'categorymenu_catimage', `value` = '?'", (int)$this->request->gethtml('catalog_categorymenu_catimage', 'post')));
		$this->database->query($this->database->parse("INSERT INTO setting SET type = 'catalog', `group` = 'categorymenu', `key` = 'categorymenu_subcatimage', `value` = '?'", (int)$this->request->gethtml('catalog_categorymenu_subcatimage', 'post')));
	}
	function get_categorymenu(){
		$results = $this->database->getRows("SELECT * FROM setting WHERE type = 'catalog' AND `group` = 'categorymenu'");
		return $results;
	}
	function install_categorymenu(){
		$this->database->query("INSERT INTO setting SET type = 'catalog', `group` = 'categorymenu', `key` = 'categorymenu_status', value = '1'");
		$this->database->query("INSERT INTO setting SET type = 'catalog', `group` = 'categorymenu', `key` = 'categorymenu_mcount', value = '1'");
		$this->database->query("INSERT INTO setting SET type = 'catalog', `group` = 'categorymenu', `key` = 'categorymenu_catimage', value = '1'");
		$this->database->query("INSERT INTO setting SET type = 'catalog', `group` = 'categorymenu', `key` = 'categorymenu_subcatimage', value = '1'");
	}
	function get_extension_id($controller) {
		$result = $this->database->getRow("SELECT extension_id FROM extension WHERE controller ='" . $controller . "'");
		return $result['extension_id'];
	}
}
?>

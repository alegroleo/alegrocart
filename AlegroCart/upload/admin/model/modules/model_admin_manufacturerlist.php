<?php //AdminModelManufacturerList AlegroCart
class Model_Admin_ManufacturerList extends Model {
	function __construct(&$locator) {
		$this->config		=& $locator->get('config');
		$this->database		=& $locator->get('database');
		$this->language		=& $locator->get('language');
		$this->request		=& $locator->get('request');
		$this->session		=& $locator->get('session');
	}
	function delete_manufacturerlist(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'manufacturerlist'");
	}
	function install_manufacturerlist(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_status', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_limit', value = '3'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_image_width', value = '160'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_image_height', value = '160'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_addtocart', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_total', value = '10'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_columns', value = '3'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_image_display', value = 'image_link'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_ratings', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_lines_multi', value = '4'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_lines_char', value = '108'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_slimit', value = '4'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_scolumns', value = '6'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_slider', value = '0'");
	}
	function update_manufacturerlist(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_status', `value` = '" . $this->request->gethtml('catalog_manufacturerlist_status', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_limit', `value` = '" . $this->request->gethtml('catalog_manufacturerlist_limit', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_image_width', `value` = '" . $this->request->gethtml('catalog_manufacturerlist_image_width', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_image_height', `value` = '" . $this->request->gethtml('catalog_manufacturerlist_image_height', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_addtocart', `value` = '" . $this->request->gethtml('catalog_manufacturerlist_addtocart', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_total', `value` = '" . $this->request->gethtml('catalog_manufacturerlist_total', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_columns', `value` = '" . $this->request->gethtml('catalog_manufacturerlist_columns', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_image_display', `value` = '" . $this->request->gethtml('catalog_manufacturerlist_image_display', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_ratings', `value` = '" . $this->request->gethtml('catalog_manufacturerlist_ratings', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_lines_multi', `value` = '" . $this->request->gethtml('catalog_manufacturerlist_lines_multi', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_lines_char', `value` = '" . $this->request->gethtml('catalog_manufacturerlist_lines_char', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_slimit', `value` = '" . $this->request->gethtml('catalog_manufacturerlist_slimit', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_scolumns', `value` = '" . $this->request->gethtml('catalog_manufacturerlist_scolumns', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerlist', `key` = 'manufacturerlist_slider', `value` = '" . $this->request->gethtml('catalog_manufacturerlist_slider', 'post') . "'");
	}
	function get_manufacturerlist(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'manufacturerlist'");
		return $results;
	}
	function get_extension_id($controller) {
		$result = $this->database->getRow("SELECT extension_id FROM extension WHERE controller ='" . $controller . "'");
		return $result['extension_id'];
	}
}
?>

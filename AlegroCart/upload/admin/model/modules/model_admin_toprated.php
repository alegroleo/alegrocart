<?php //AdminModelToprated AlegroCart
class Model_Admin_Toprated extends Model {
	function __construct(&$locator) {
		$this->config		=& $locator->get('config');
		$this->database		=& $locator->get('database');
		$this->language		=& $locator->get('language');
		$this->request		=& $locator->get('request');
		$this->session		=& $locator->get('session');
	}
	function delete_toprated(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'toprated'");
	}
	function install_toprated(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_status', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_rating', value = '4.5'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_limit', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_image_width', value = '160'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_image_height', value = '160'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_addtocart', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_total', value = '10'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_columns', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_image_display', value = 'image_link'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_ratings', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_lines_single', value = '6'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_lines_multi', value = '4'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_lines_char', value = '108'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_slimit', value = '4'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_scolumns', value = '6'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_slider', value = '0'");
	}
	function update_toprated(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_status', `value` = '" . $this->request->gethtml('catalog_toprated_status', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_rating', `value` = '" . $this->request->gethtml('catalog_toprated_rating', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_limit', `value` = '" . $this->request->gethtml('catalog_toprated_limit', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_image_width', `value` = '" . $this->request->gethtml('catalog_toprated_image_width', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_image_height', `value` = '" . $this->request->gethtml('catalog_toprated_image_height', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_addtocart', `value` = '" . $this->request->gethtml('catalog_toprated_addtocart', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_total', `value` = '" . $this->request->gethtml('catalog_toprated_total', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_columns', `value` = '" . $this->request->gethtml('catalog_toprated_columns', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_image_display', `value` = '" . $this->request->gethtml('catalog_toprated_image_display', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_ratings', `value` = '" . $this->request->gethtml('catalog_toprated_ratings', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_lines_single', `value` = '" . $this->request->gethtml('catalog_toprated_lines_single', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_lines_multi', `value` = '" . $this->request->gethtml('catalog_toprated_lines_multi', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_lines_char', `value` = '" . $this->request->gethtml('catalog_toprated_lines_char', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_slimit', `value` = '" . $this->request->gethtml('catalog_toprated_slimit', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_scolumns', `value` = '" . $this->request->gethtml('catalog_toprated_scolumns', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'toprated', `key` = 'toprated_slider', `value` = '" . $this->request->gethtml('catalog_toprated_slider', 'post') . "'");
	}
	function get_toprated(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'toprated'");
		return $results;
	}
}
?>

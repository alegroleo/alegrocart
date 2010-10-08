<?php //AdminModelImageDisplayModule AlegroCart
class Model_Admin_DisplayModule extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_imagedisplay(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'imagedisplay'");
	}
	function install_imagedisplay(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'imagedisplay', `key` = 'imagedisplay_status', value = '1'");
	}
	function update_imagedisplay(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'imagedisplay', `key` = 'imagedisplay_status', `value` = '?'", (int)$this->request->gethtml('catalog_imagedisplay_status', 'post')));
	}
	function get_imagedisplay(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'imagedisplay'");
		return $results;
	}
}
?>
<?php //AdminModelReviewModule AlegroCart
class Model_Admin_ReviewModule extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_review(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'review'");
	}
	function install_review(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'review', `key` = 'review_status', value = '1'");
	}
	function update_review(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'review', `key` = 'review_status', `value` = '?'", (int)$this->request->gethtml('catalog_review_status', 'post')));
	}
	function get_review(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'review'");
		return $results;
	}
}
?>
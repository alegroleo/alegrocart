<?php //AdminModelSEO AlegroCart
class Model_Admin_SEO extends Model {
	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
	}
	function get_product_names($language_id){
		$results = $this->database->getRows("select product_id, name as product_name, language_id from product_description where language_id = '".(int)$language_id."'");
		return $results;
	}
	function get_product_name($product_id,$language_id){
		$result = $this->database->getRow("select name as product_name from product_description where product_id ='".$product_id."' and language_id='". $language_id ."'");
		return $result;
	}
	function category_name($category_id, $language_id){
		$result = $this->database->getRow("select name from category_description where category_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
		return $result;
	}
	function get_gategories($language_id){
		$results = $this->database->getRows("select c.path, cd.language_id from category c inner join category_description cd on c.category_id = cd.category_id where cd.language_id = '". (int)$language_id . "'");
		return $results;
	}
	function category_product($language_id){
		$results = $this->database->getRows("select c.path, cd.language_id, ptoc.product_id from category c inner join category_description cd on c.category_id = cd.category_id inner join product_to_category ptoc on ptoc.category_id = cd.category_id where cd.language_id = '".(int)$language_id."'");
		return $results;
	}
	function get_manufacturers(){
		$results = $this->database->getRows("select m.manufacturer_id, m.name, p.manufacturer_id, p.product_id from manufacturer m inner join product p on m.manufacturer_id = p.manufacturer_id");
		return $results;
	}
	function get_reviews($product_id){
		$results = $this->database->getRows("select review_id from review where product_id='" . $product_id . "'");
		return $results;
	}
	function review_product_id(){
		$results = $this->database->getRows("select distinct product_id from review");
		return $results;
	}
	function delete(){
		$this->database->query("delete from url_alias");
	}
}
?>
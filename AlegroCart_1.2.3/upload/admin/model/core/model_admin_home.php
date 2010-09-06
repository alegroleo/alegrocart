<?php //AdminModelHome AlegroCart
class Model_Admin_Home extends Model {
	function __construct(&$locator) {
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
	}
	function get_people_online(){
		$sql = "select count(distinct ip) as total from `session` where `expire` > '?'";
        $parsed = $this->database->parse($sql, time());
        $results = $this->database->getRow($parsed);
		return $results;
	}
	function get_customers(){
		$results = $this->database->getRow("select count(*) as total from customer");
		return $results;
	}
	function get_orders(){
		$results = $this->database->getRow("select count(*) as total from `order`");
		return $results;
	}
	function get_products(){
		$results = $this->database->getRow("select count(*) as total from product where status = '1'");
		return $results;
	}
	function get_images(){
		$results = $this->database->getRow("select count(*) as total from image");
		return $results;
	}
	function get_reviews(){
		$results = $this->database->getRow("select count(*) as total from review");
		return $results;
	}
	function get_languages(){
		$results = $this->database->getRow("select count(*) as total from language");
		return $results;
	}
	function get_currencies(){
		$results = $this->database->getRow("select count(*) as total from currency");
		return $results;
	}
	function get_countries(){
		$results = $this->database->getRow("select count(*) as total from country where country_status = '1'");
		return $results;
	}
	function get_latest_orders(){
		$results = $this->database->getRows("select o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency, o.value from `order` o left join order_status os on o.order_status_id = os.order_status_id where os.language_id = '" . (int)$this->language->getId() . "' order by o.order_id desc limit 5");
		return $results;
	}
	function get_latest_reviews(){
		$results = $this->database->getRows("select r.review_id, pd.name as product, r.author, r.rating, r.status from review r left join product_description pd on (r.product_id = pd.product_id) where pd.language_id = '" . (int)$this->language->getId() . "' limit 5");
		return $results;
	}
}
?>
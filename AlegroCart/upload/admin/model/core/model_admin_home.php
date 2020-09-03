<?php //AdminModelHome AlegroCart
class Model_Admin_Home extends Model {

	function __construct(&$locator) {
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->config		=& $locator->get('config');
	}

	function get_customers(){
		$results = $this->database->getRow("select count(*) as total, sum(date_added >= date(NOW()) - INTERVAL 7 DAY) as new from customer");
		return $results;
	}

	function get_orders(){
		$results = $this->database->getRow("select count(*) as total, sum(order_status_id ='". (int)$this->config->get('config_order_status_id')."') as new from `order`");
		return $results;
	}

	function get_defaultStatus(){
		$result = $this->database->getRow("select name from order_status where order_status_id ='". (int)$this->config->get('config_order_status_id')."' and language_id = '" . (int)$this->language->getId() . "'");
		return $result['name'];
}

	function get_products(){
		$results = $this->database->getRow("select count(*) as total, sum(status = '1') as active from product");
		return $results;
	}

	function get_images(){
		$results = $this->database->getRow("select count(*) as total from image");
		return $results;
	}

	function get_reviews(){
		$results = $this->database->getRow("select count(*) as total, sum(status = '1') as active from review");
		return $results;
	}

	function get_languages(){
		$results = $this->database->getRow("select count(*) as total, sum(language_status = '1') as active from language");
		return $results;
	}

	function get_currencies(){
		$results = $this->database->getRow("select count(*) as total, sum(status = '1') as active from currency");
		return $results;
	}

	function get_countries(){
		$results = $this->database->getRow("select count(*) as total, sum(country_status = '1') as active from country");
		return $results;
	}

	function get_latest_orders(){
		$results = $this->database->getRows("select o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency, o.value from `order` o left join order_status os on o.order_status_id = os.order_status_id where os.language_id = '" . (int)$this->language->getId() . "' order by o.order_id desc limit 5");
		return $results;
	}

	function get_latest_reviews(){
		$results = $this->database->getRows("select r.review_id, pd.name as product, r.author, r.rating1, r.rating2, r.rating3, r.rating4, r.status from review r left join product_description pd on (r.product_id = pd.product_id) where pd.language_id = '" . (int)$this->language->getId() . "' limit 5");
		return $results;
	}
}
?>

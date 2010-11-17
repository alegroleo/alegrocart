<?php //ModelProducts AlegroCart
class Model_Products extends Model {
	function __construct(&$locator) {
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->tax      =& $locator->get('tax');
		$this->currency =& $locator->get('currency');
		$this->config   =& $locator->get('config');
	}
	function get_bestseller($bestseller_total){
		$results = $this->database->getRows("SELECT product.*, order_product.order_product_id, order_product.order_id, SUM(order_product.quantity) as TotalOrdered, product_description.*, image.* FROM product, order_product, order_history, product_description, image WHERE product.product_id = product_description.product_id AND product_description.name = order_product.name AND product.image_id = image.image_id AND product.status ='1' AND product_description.language_id = '" . (int)$this->language->getId() . "' GROUP BY order_product.name ORDER BY TotalOrdered DESC". $bestseller_total);
		return $results;
	}
	function get_popular($popular_total){
		$results = $this->database->getRows("select * from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.status = '1' and pd.language_id = '" . (int)$this->language->getId() . "' and p.date_available <= now() order by viewed DESC" . $popular_total);
		return $results;
	}
	function get_featured(){
		$results = $this->database->getRows("select * from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.status = '1' and pd.language_id = '" . (int)$this->language->getId() . "' and p.date_available < now() and p.featured = '1' order by name asc");  
		return $results;
	}
	function get_specials(){
		$results = $this->database->getRows("select * from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.status = '1' and pd.language_id = '" . (int)$this->language->getId() . "' and p.date_available < now() and p.special_offer = '1' order by p.date_added desc"); 
		return $results;
	}
	function get_latest($latest_total){
		$results = $this->database->getRows("select * from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.status = '1' and pd.language_id = '" . (int)$this->language->getId() . "' and p.date_available < now() and p.status = '1' order by p.date_added desc" . $latest_total); 
		return $results;
	}
	function get_related($product_id){
		$results = $this->database->getRows( "SELECT * FROM related_products r INNER JOIN product_description pd ON r.related_product_id = pd.product_id INNER JOIN product p ON p.product_id = r.related_product_id LEFT JOIN image i ON i.image_id = p.image_id	WHERE r.product_id = '". (int)$product_id . "' and r.related_product_id = pd.product_id and pd.language_id = '" . (int)$this->language->getId() . "'");
		return $results;
	}
	function getRow_product($product_id){
		$result = $this->database->getRow("select * from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.product_id = '" . (int)$product_id . "' and pd.language_id = '" . (int)$this->language->getId() . "' and p.date_available < now() and p.status = '1'");
		return $result;
	}
	function get_product_discount($product_id){
		$results = $this->database->getRows("select * from product_discount where product_id = '" . (int)$product_id . "' order by quantity asc");
		return $results;
	}
	function getRow_manufacturer($manufacturer_id){
		$result = $this->database->getRow("select * from manufacturer where manufacturer_id = '" . (int)$manufacturer_id . "'");
		return $result;
	}
	function get_additional_images($product_id){
		$results = $this->database->getRows("select i.image_id, i.filename, id.title from image i left join image_description id on (i.image_id = id.image_id) left join product_to_image p2i on (i.image_id = p2i.image_id) where p2i.product_id = '" . (int)$product_id . "' and id.language_id = '" . (int)$this->language->getId() . "'");
		return $results;
	}
	function get_reviews($product_id){
		$results = $this->database->getRows("select r.review_id, r.author, r.rating, r.text, p.product_id, pd.name, p.price, i.filename, r.date_added from review r left join product p on(r.product_id = p.product_id) left join product_description pd on(p.product_id = pd.product_id) left join image i on(p.image_id = i.image_id) where p.product_id = '" . (int)$product_id . "' and p.date_available < now() and p.status = '1' and r.status = '1' and pd.language_id = '" . (int)$this->language->getId() . "' order by r.date_added desc limit 10");
		return $results;
	}
	function check_options($product_id){   // Check for Products with Options
	    $results = $this->database->getRows("select * from product_to_option where product_id = '" . (int)$product_id . "'");
		$option_status = $results ? TRUE : FALSE;
		return $option_status;
	}
	function get_options($product_id,$tax_class_id){  // Get product Options
		$options = array();
      		$results = $this->database->getRows("select * from product_to_option where product_id = '" . (int)$product_id . "' order by sort_order");
      		foreach ($results as $result) {
        		$options[$result['option_id']][] = array(
          			'product_to_option_id' => $result['product_to_option_id'],
          			'option_value_id'      => $result['option_value_id'],
          			'price'                => $result['price'],
          			'prefix'               => $result['prefix']
        		);
      		}
      		$option_data = array();
      		foreach ($options as $key => $values) {
        		$option_value_data = array();
        		foreach ($values as $value) {
          			$option_value_info = $this->database->getRow("select * from option_value where option_value_id = '" . (int)$value['option_value_id'] . "' and option_id = '" . (int)$key . "' and language_id = '" . (int)$this->language->getId() . "'");
          			$option_value_data[] = array(
            			'product_to_option_id' => $value['product_to_option_id'],
            			'option_value_id'      => $value['option_value_id'],
            			'name'                 => $option_value_info['name'],
            			'price'                => (($value['price'] != '0.00') ? $this->currency->format($this->tax->calculate($value['price'], $tax_class_id, $this->config->get('config_tax'))) : null),
            			'prefix'               => $value['prefix']
          			);
        		}
        		$option = $this->database->getRow("select * from `option` where option_id = '" . (int)$key . "' and language_id = '" . (int)$this->language->getId() . "'");
        		$option_data[] = array(
          			'option_id' => $key,
          			'name'      => $option['name'],
          			'value'     => $option_value_data
        		);
      		}
      	return $option_data;
	}
	function update_viewed($product_id){
		$this->database->query("update product set viewed = viewed + 1 where product_id = '" . (int)$product_id . "'");
	}
	function currentpage($current_page){  
		switch($current_page){
			case '':
			case 'home':
			case 'information':
			case 'sitemap':
			case 'search':
			case 'contact':
			case 'category':
			case 'product':
			case 'manufacturer';
			$add_enable = true;
			break;
		default:
			$add_enable = false;
			break;
		}
		return $add_enable;
	}
}
?>
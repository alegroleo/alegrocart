<?php //AdminModelOrderEdit AlegroCart
class Model_Admin_OrderEdit extends Model {
	function __construct(&$locator) {	
		$this->config   =& $locator->get('config');
		$this->currency =  $locator->get('currency');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request 	=& $locator->get('request');
		$this->tax		= $locator->get('tax');
		$this->session 	=& $locator->get('session');
	}
	
	function get_currency(){
		$result = $this->database->getRow("select code, title from currency where code = '" . $this->currency->getCode() . "'");
		return $result;
	}
	
	function modify_order($order_id, $reference){
		$this->database->query("update `order` set `modified` = '1', `new_reference` = '" . $reference . "' where order_id = '" . $order_id . "'");
	}
	
	function get_products(){
		$results = $this->database->getRows("select p.product_id, pd.name, i.filename from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (i.image_id=p.image_id) where pd.language_id = '" . (int)$this->language->getId() . "' and p.date_available < now() and p.status = '1'");
		return $results;
	}
	
	function get_product($product_id){
		$result = $this->database->getRow("select * from product p left join product_description pd on (p.product_id = pd.product_id) where p.product_id = '" . (int)$product_id . "' and pd.language_id = '" . (int)$this->language->getId() . "' and p.date_available < now() and p.status = '1'");
		return $result;
	}
	
	function get_product_to_option($product_id, $product_to_option_id){
		$option = $this->database->getRow("select o.name as name, ov.name as `value`, p2o.price, p2o.prefix, p2o.option_weight, p2o.option_weightclass_id from product_to_option p2o left join `option` o on p2o.option_id = o.option_id left join option_value ov on p2o.option_value_id = ov.option_value_id where p2o.product_to_option_id = '" . (int)$product_to_option_id . "' and product_id = '" . (int)$product_id . "' and o.language_id = '" . (int)$this->language->getId() . "' and ov.language_id = '" . (int)$this->language->getId() . "'");
		return $option;
	}
	
	function get_downloads($product_id){
		$downloads = $this->database->getRows("select * from product_to_download p2d left join download d on (p2d.download_id = d.download_id) left join download_description dd on (d.download_id = dd.download_id) where p2d.product_id = '" . (int)$product_id . "' and p2d.free = ' 0 ' and dd.language_id = '" . (int)$this->language->getId() . "'");
		return $downloads;
	}
	
	function check_options($product_id){   // Check for Products with Options
	    $results = $this->database->getRows("select * from product_to_option where product_id = '" . (int)$product_id . "'");
		$option_status = $results ? TRUE : FALSE;
		return $option_status;
	}
	
	function product_with_options($key){
		$product_option = $this->database->getRow("select * from product_options po left join image i on (po.image_id = i.image_id) where product_option = '" . $key . "'");
		return $product_option;
	}
	
	function get_product_option($option_id, $product_id){
		$option = $this->database->getRow("select o.name as name, ov.name as `value`, p2o.price, p2o.prefix, p2o.option_weight, p2o.option_weightclass_id from product_to_option p2o left join `option` o on p2o.option_id = o.option_id left join option_value ov on p2o.option_value_id = ov.option_value_id where p2o.product_to_option_id = '" . (int)$option_id . "' and product_id = '" . (int)$product_id . "' and o.language_id = '" . (int)$this->language->getId() . "' and ov.language_id = '" . (int)$this->language->getId() . "'");
		return $option;
	}
	
	function get_options($product_id, $tax_class_id){  // Get product Options
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
            			'price'                => (($value['price'] != '0.00') ? $this->currency->format($value['price'],'','', false) : null),
						//'price'                => (($value['price'] != '0.00') ? $this->currency->format($this->tax->calculate($value['price'], $tax_class_id, $this->config->get('config_tax'))) : null),
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
	function get_addresses($customer_id){
		$results = $this->database->getRows("select *, c.name as country, z.name as zone from address a left join country c on a.country_id = c.country_id left join zone z on a.zone_id = z.zone_id where a.customer_id = '" . (int)$customer_id . "'");
		return $results;
	}
	function get_customers(){ // only use what is needed
		$results = $this->database->getRows("select c.customer_id, c.lastname, c.firstname, c.status, c.email, c.telephone, c.fax, c.password, c.address_id, a.address_1, a.address_2, a.city, a.country_id, a.zone_id, co.name as country, z.name as zone from customer c left join address a on(c.address_id = a.address_id) left join country co on(co.country_id = a.country_id) left join zone z on(z.zone_id = a.zone_id)");
		return $results;
	}
	function get_email($customer_id){
		$result = $this->database->getRow("select email from customer where customer_id = '" . (int)$customer_id . "'");
		return $result['email'];
	}
	
	function get_telephone($customer_id){
		$result = $this->database->getRow("select telephone, fax from customer where customer_id = '" . (int)$customer_id . "'");
		return $result;
	}
	
	function get_customer($customer_id){
		$result = $this->database->getRow("select c.customer_id, c.lastname, c.firstname, c.status, c.email, c.telephone, c.fax, c.password, c.address_id, a.company, a.address_1, a.address_2, a.postcode, a.city, a.country_id, a.zone_id, co.name as country, z.name as zone from customer c left join address a on(c.address_id = a.address_id) left join country co on(co.country_id = a.country_id) left join zone z on(z.zone_id = a.zone_id) where c.customer_id = '" . (int)$customer_id . "'");
		return $result;
	}
	function get_address($address_id){
		$result = $this->database->getRow("select distinct * from address where address_id = '" . (int)$address_id . "' and customer_id = '" . (int)$this->request->gethtml('customer_id') . "'");
		return $result;
	}
	function get_shipping_address($address_id){
		$result = $this->database->getRow("select *, c.name as country, z.name as zone from address a left join country c on a.country_id = c.country_id left join zone z on a.zone_id = z.zone_id where a.address_id = '" . (int)$address_id . "'");
		return $result;
	}

	function get_customer_id($email){
		$result = $this->database->getRow("select customer_id from customer where email = '" . $email . "'");
		return $result['customer_id'];
	}
	
	function get_extension_info($extension_id){
		$result = $this->database->getRow("select name, description from extension_description where language_id ='" .  (int)$this->language->getId() . "' and extension_id = '" . $extension_id . "'");
		return $result;
	}
	
	function get_payment_ext(){
		$results = $this->database->getRows("select * from extension where type = 'payment'");
		return $results;
	}
	
	function get_shipping_ext(){
		$results = $this->database->getRows("select * from extension where type = 'shipping'");
		return $results;
	}
}
?>

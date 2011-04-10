<?php //ModelAccountInvoice AlegroCart
class Model_AccountInvoice extends Model{
	function __construct(&$locator) {
		$this->customer =& $locator->get('customer');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->session  =& $locator->get('session');
		$this->config   =& $locator->get('config');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');
		$this->url      =& $locator->get('url');
	}
	function get_order_products($order_id){
		$results = $this->database->getRows("select * from order_product where order_id = '" . (int)$order_id . "'");
		return $results;
	}
	function get_options($order_id,$order_product_id){
		$results = $this->database->getRows("select * from order_option where order_id = '" . (int)$order_id . "' and order_product_id = '" . (int)$order_product_id . "'");
		return $results;
	}
	function get_downloads($order_id,$order_product_id){
		$results = $this->database->getRows("select * from order_download where order_id = '" . (int)$order_id . "' and order_product_id = '" . (int)$order_product_id . "'");
		return $results;
	}
	function get_totals($order_id){ 
		$results = $this->database->getRows("select * from order_total where order_id = '" . (int)$order_id . "' order by sort_order");
		return $results;
	}
	function get_order_history($order_id){
		$results = $this->database->getRows("select date_added, os.name as status, oh.comment from order_history oh left join order_status os on oh.order_status_id = os.order_status_id where os.language_id = '" . (int)$this->language->getId() . "' and oh.order_id = '" . (int)$order_id . "' and oh.notify = '1' order by oh.date_added");
		return $results;
	}
	function get_order($order_id){
		$result = $this->database->getRow("select * from `order` where order_id = '" . (int)$order_id . "' and customer_id = '" . (int)$this->customer->getId() . "'");
		return $result;
	}
	function get_order_ref($reference){
		$sql = "select * from `order` where reference = '?' and customer_id = '?'";
		$result = $this->database->getRow($this->database->parse($sql, $reference, $this->customer->getId()));
		return $result;
	}
}
?>
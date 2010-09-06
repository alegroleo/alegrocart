<?php //ModelAccountHistory AlegroCart
class Model_AccountHistory extends Model{
	function __construct(&$locator) {
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->session  =& $locator->get('session');
		$this->config   =& $locator->get('config');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');
		$this->url      =& $locator->get('url');
	}
	function get_orders($customer_id){
		$results = $this->database->getRows($this->database->splitQuery("select o.order_id, o.reference, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency, o.value from `order` o left join order_status os on o.order_status_id = os.order_status_id where customer_id = '" . (int)$customer_id . "' and os.language_id = '" . (int)$this->language->getId() . "' order by order_id desc", $this->session->get('account_history.page'), $this->config->get('config_max_rows')));
		return $results;
	}
	function get_product_count($order_id){
		$result = $this->database->getRow("select count(*) as products from order_product where order_id = '" . (int)$order_id . "'");
		return $result;
	}
	function get_pagination(){
		$page_data = array();
      	for ($i = 1; $i <= $this->database->getPages(); $i++) {
			$query=array('path' => $this->request->gethtml('path'));
			if ($i > 1) $query['page'] = $i;
        	$page_data[] = array(
          		'text'  => $this->language->get('text_pages', $i, $this->database->getPages()),
				'href'	=> $this->url->href('account_history', FALSE, $query),
          		'value' => $i
        	);
      	}
		return $page_data;
	}
	function get_text_results(){
		$text_results = $this->language->get('text_results', $this->database->getFrom(), $this->database->getTo(), $this->database->getTotal());
		return $text_results;
	}
	function get_pages(){
		$pages = $this->database->getpages();
		return $pages;
	}
}
?>
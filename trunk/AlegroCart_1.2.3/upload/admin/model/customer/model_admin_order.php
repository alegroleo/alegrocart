<?php //AdminModelOrder AlegroCart
class Model_Admin_Order extends Model {
	function __construct(&$locator) {	
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request 	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}
	function update_order(){
		$this->database->query("update `order` set order_status_id = '" . (int)$this->request->gethtml('order_status_id', 'post') . "', date_modified = now() where order_id = '" . (int)$this->request->gethtml('order_id') . "'");
	}
	function insert_order_history(){
		$sql = "insert into order_history set order_id = '?', order_status_id = '?', date_added = now(), notify = '?', comment = '?'";
      		$this->database->query($this->database->parse($sql, $this->request->gethtml('order_id'), $this->request->gethtml('order_status_id', 'post'), $this->request->gethtml('notify', 'post'), ($this->request->gethtml('comment', 'post'))));
	}
	function get_order_info(){
		$result = $this->database->getRow("select o.reference, o.firstname, o.lastname, o.email, o.date_added, os.name as status from `order` o left join order_status os on o.order_status_id = os.order_status_id where o.order_id = '" . (int)$this->request->gethtml('order_id') . "' and os.language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
	function get_order(){
		$result = $this->database->getRow("select * from `order` where order_id = '" . (int)$this->request->gethtml('order_id') . "'");
		return $result;
	}
	function get_products(){
		$results = $this->database->getRows("select * from order_product where order_id = '" . (int)$this->request->gethtml('order_id') . "'");
		return $results;
	}
	function get_options($order_product_id){
		$results = $this->database->getRows("select * from order_option where order_id = '" . (int)$this->request->gethtml('order_id') . "' and order_product_id = '" . (int)$order_product_id . "'");
		return $results;
	}
	function get_totals(){
		$results = $this->database->getRows("select * from order_total where order_id = '" . (int)$this->request->gethtml('order_id') . "'");
		return $results;
	}
	function get_history(){
		$results = $this->database->getRows("select date_added, os.name as status, oh.comment, oh.notify from order_history oh left join order_status os on oh.order_status_id = os.order_status_id where oh.order_id = '" . (int)$this->request->gethtml('order_id') . "' and os.language_id = '" . (int)$this->language->getId() . "' order by oh.date_added");
		return $results;
	}
	function get_downloads(){
		$results = $this->database->getRows("select * from order_download where order_id = '" . (int)$this->request->gethtml('order_id') . "' order by name");
		return $results;
	}
	function get_order_statuses(){
		$results = $this->database->cache('order_status-' . (int)$this->language->getId(), "select order_status_id, name from order_status where language_id = '" . (int)$this->language->getId() . "' order by name");
		return $results;
	}
	function delete_order(){
		$this->database->query("delete from `order` where order_id = '" . (int)$this->request->gethtml('order_id') . "'");
      	$this->database->query("delete from order_history where order_id = '" . (int)$this->request->gethtml('order_id') . "'");
      	$this->database->query("delete from order_product where order_id = '" . (int)$this->request->gethtml('order_id') . "'");
      	$this->database->query("delete from order_option where order_id = '" . (int)$this->request->gethtml('order_id') . "'");
	  	$this->database->query("delete from order_download where order_id = '" . (int)$this->request->gethtml('order_id') . "'");
      	$this->database->query("delete from order_total where order_id = '" . (int)$this->request->gethtml('order_id') . "'");
	}
	function get_page(){
		if (!$this->session->get('order.search')) {
      		$sql = "select o.order_id, o.reference, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency, o.value from `order` o left join order_status os on o.order_status_id = os.order_status_id where os.language_id = '" . (int)$this->language->getId() . "'";
    	} else {
      		$sql = "select o.order_id, o.reference, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency, o.value from `order` o left join order_status os on o.order_status_id = os.order_status_id where (o.reference like '?' or o.firstname like '?' or o.lastname like '?') and os.language_id = '" . (int)$this->language->getId() . "'";
    	}
		$sort = array('o.order_id', 'o.reference', 'o.firstname', 'os.name', 'o.date_added', 'o.total');
		if (in_array($this->session->get('order.sort'), $sort)) {
      		$sql .= " order by " . $this->session->get('order.sort') . " " . (($this->session->get('order.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by o.date_added desc";
    	}
		$search = '%' . $this->session->get('order.search') . '%';
    	$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, $search , $search, $search), $this->session->get('order.page'), $this->config->get('config_max_rows')));
		return $results;
	}
	function get_text_results(){
		$text_results = $this->language->get('text_results', $this->database->getFrom(), $this->database->getTo(), $this->database->getTotal());
		return $text_results;
	}
	function get_pagination(){
    	$page_data = array();
    	for ($i = 1; $i <= $this->get_pages(); $i++) {
      		$page_data[] = array(
        		'text'  => $this->language->get('text_pages', $i, $this->get_pages()),
        		'value' => $i
      		);
    	}
		return $page_data;
	}
	function get_pages(){
		$pages = $this->database->getpages();
		return $pages;
	}
}
?>
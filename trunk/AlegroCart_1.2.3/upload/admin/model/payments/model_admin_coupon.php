<?php //AdminModelCoupon AlegroCart
class Model_Admin_Coupon extends Model {
	function __construct(&$locator) {	
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request 	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}
	function insert_coupon(){
		$sql = "insert into coupon set code = '?', discount = '?', prefix = '?', minimum_order = '?', shipping = '?', date_start = '?', date_end = '?', uses_total = '?', uses_customer = '?', status = '?', date_added = now()";
      	$this->database->query($this->database->parse($sql, $this->request->gethtml('code', 'post'), $this->request->gethtml('discount', 'post'), $this->request->gethtml('prefix', 'post'), $this->request->gethtml('minimum_order', 'post'), $this->request->gethtml('shipping', 'post'), date('Y-m-d', strtotime($this->request->gethtml('date_start_year', 'post') . '/' . $this->request->gethtml('date_start_month', 'post') . '/' . $this->request->gethtml('date_start_day', 'post'))), date('Y-m-d H:i:s', strtotime($this->request->gethtml('date_end_year', 'post') . '/' . $this->request->gethtml('date_end_month', 'post') . '/' . $this->request->gethtml('date_end_day', 'post') . '23:59:59')), $this->request->gethtml('uses_total', 'post'), $this->request->gethtml('uses_customer', 'post'), $this->request->gethtml('status', 'post')));
	}
	function insert_description($insert_id){
		foreach ($this->request->gethtml('language', 'post') as $key => $value) {
        	$sql = "insert into coupon_description set coupon_id = '?', language_id = '?', name = '?', description = '?'";
        	$this->database->query($this->database->parse($sql, $insert_id, $key, $value['name'], $value['description']));
      	}
	}
	function insert_product($insert_id){
		foreach ($this->request->gethtml('product', 'post', array()) as $product_id) {
        	$sql = "insert into coupon_product set coupon_id = '?', product_id = '?'";
        	$this->database->query($this->database->parse($sql, $insert_id, $product_id));
      	}
	}
	function update_coupon(){
		$sql = "update coupon set code = '?', discount = '?', prefix = '?', minimum_order = '?', shipping = '?', date_start = '?', date_end = '?', uses_total = '?', uses_customer = '?', status = '?' where coupon_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('code', 'post'), $this->request->gethtml('discount', 'post'), $this->request->gethtml('prefix', 'post'), $this->request->gethtml('minimum_order', 'post'), $this->request->gethtml('shipping', 'post'), date('Y-m-d', strtotime($this->request->gethtml('date_start_year', 'post') . '/' . $this->request->gethtml('date_start_month', 'post') . '/' . $this->request->gethtml('date_start_day', 'post'))), date('Y-m-d H:i:s', strtotime($this->request->gethtml('date_end_year', 'post') . '/' . $this->request->gethtml('date_end_month', 'post') . '/' . $this->request->gethtml('date_end_day', 'post') . '23:59:59')), $this->request->gethtml('uses_total', 'post'), $this->request->gethtml('uses_customer', 'post'), $this->request->gethtml('status', 'post'), (int)$this->request->gethtml('coupon_id')));
	}
	function delete_coupon(){
		$this->database->query("delete from coupon where coupon_id = '" . (int)$this->request->gethtml('coupon_id') . "'");
	}
	function delete_description(){
		$this->database->query("delete from coupon_description where coupon_id = '" . (int)$this->request->gethtml('coupon_id') . "'");
	}
	function delete_product(){
		$this->database->query("delete from coupon_product where coupon_id = '" . (int)$this->request->gethtml('coupon_id') . "'");
	}
	function delete_redeem(){
		$this->database->query("delete from coupon_redeem where coupon_id = '" . (int)$this->request->gethtml('coupon_id') . "'");
	}
	function get_insert_id(){
		$insert_id = $this->database->getLastId();
		return $insert_id;
	}
	function get_coupon(){
		$result = $this->database->getRow("select distinct * from coupon where coupon_id = '" . (int)$this->request->gethtml('coupon_id') . "'");
		return $result;
	}
	function get_description($language_id){
		$result = $this->database->getRow("select name, description from coupon_description where coupon_id = '" . (int)$this->request->gethtml('coupon_id') . "' and language_id = '" . (int)$language_id . "'");
		return $result;
	}
	function get_coupon_product($product_id){
		$result = $this->database->getRow("select * from coupon_product where coupon_id = '" . (int)$this->request->gethtml('coupon_id') . "' and product_id = '" . (int)$product_id . "'");
		return $result;
	}
	function get_products(){
		$results = $this->database->cache('product', "select * from product p left join product_description pd on (p.product_id = pd.product_id) where pd.language_id = '" . (int)$this->language->getId() . "' order by pd.name");
		return $results;
	}
	function get_page(){
		if (!$this->session->get('coupon.search')) {
      		$sql = "select c.coupon_id, cd.name, c.code, c.discount, c.prefix, c.date_start, c.date_end, c.status from coupon c left join coupon_description cd on (c.coupon_id = cd.coupon_id) where cd.language_id = '" . (int)$this->language->getId() . "'";
    	} else {
      		$sql = "select c.coupon_id, cd.name, c.code, c.discount, c.prefix, c.date_start, c.date_end, c.status from coupon c left join coupon_description cd on (c.coupon_id = cd.coupon_id) where cd.language_id = '" . (int)$this->language->getId() . "' and (cd.name like '?' or c.code or like = '?')";
    	}
		$sort = array('cd.name', 'c.code', 'c.discount', 		'c.prefix', 'c.date_start', 'c.date_end', 'c.status');
    	if (in_array($this->session->get('coupon.sort'), $sort)) {
      		$sql .= " order by " . $this->session->get('coupon.sort') . " " . (($this->session->get('coupon.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by cd.name asc";
    	}
    	$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('coupon.search') . '%', '%' . $this->session->get('coupon.search') . '%'), $this->session->get('coupon.page'), $this->config->get('config_max_rows')));
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
	function get_languages(){
		$results = $this->database->cache('language', "select * from language order by sort_order");
		return $results;
	}
}
?>
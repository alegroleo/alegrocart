<?php //AdminModelVendor AlegroCart
class Model_Admin_Vendor extends Model {
	function __construct(&$locator) {
		$this->config	=& $locator->get('config');
		$this->database	=& $locator->get('database');
		$this->language	=& $locator->get('language');
		$this->request	=& $locator->get('request');
		$this->session	=& $locator->get('session');
	}
	function insert_vendor(){
		$sql = "insert into vendor set name = '?', image_id = '?', description= '?', discount = '?', status = '?', telephone = '?', fax = '?', email = '?', website = '?', trade = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), $this->request->gethtml('image_id', 'post'), $this->request->gethtml('description', 'post'), $this->request->gethtml('discount', 'post'), $this->request->gethtml('status', 'post'), $this->request->gethtml('telephone', 'post'), $this->request->gethtml('fax', 'post'), $this->request->gethtml('email', 'post'), $this->request->gethtml('website', 'post'), $this->request->gethtml('trade', 'post')));
	}
	function get_last_id(){
		$result = $this->database->getLastId();
		return $result;
	}
	function insert_address($vendor_id){
		$sql = "insert into vendor_address set vendor_id = '?', company = '?', firstname = '?', lastname = '?', address_1 = '?', address_2 = '?', postcode = '?', city = '?', zone_id = '?', country_id = '?'";
		$this->database->query($this->database->parse($sql, $vendor_id, $this->request->gethtml('company', 'post'), $this->request->gethtml('firstname', 'post'), $this->request->gethtml('lastname', 'post'), $this->request->gethtml('address_1', 'post'), $this->request->gethtml('address_2', 'post'), $this->request->gethtml('postcode', 'post'), $this->request->gethtml('city', 'post'), $this->request->gethtml('zone_id', 'post'), $this->request->gethtml('country_id', 'post')));
	}
	function insert_default_address($vendor_id){
		$this->database->query("update vendor set address_id = '" . (int)$this->database->getLastId() . "' where vendor_id = '" . (int)$vendor_id . "'");
	}
	function update_vendor(){
		$sql = "update vendor set name = '?', image_id = '?', description= '?', discount = '?', status = '?', telephone = '?', fax = '?', email = '?', website = '?', trade = '?' where vendor_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), $this->request->gethtml('image_id', 'post'), $this->request->gethtml('description', 'post'), $this->request->gethtml('discount', 'post'), $this->request->gethtml('status', 'post'), $this->request->gethtml('telephone', 'post'), $this->request->gethtml('fax', 'post'), $this->request->gethtml('email', 'post'), $this->request->gethtml('website', 'post'), $this->request->gethtml('trade', 'post'), (int)$this->request->gethtml('vendor_id')));
	}
	function update_address(){
		$sql = "update vendor_address set company = '?', firstname = '?', lastname = '?', address_1 = '?', address_2 = '?', postcode = '?', city = '?', zone_id = '?', country_id = '?' where address_id  = '?' and vendor_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('company', 'post'), $this->request->gethtml('firstname', 'post'), $this->request->gethtml('lastname', 'post'), $this->request->gethtml('address_1', 'post'), $this->request->gethtml('address_2', 'post'), $this->request->gethtml('postcode', 'post'), $this->request->gethtml('city', 'post'), $this->request->gethtml('zone_id', 'post'), $this->request->gethtml('country_id', 'post'), (int)$this->request->gethtml('address_id','post'), (int)$this->request->gethtml('vendor_id')));
	}
	function delete_vendor(){
		$this->database->query("delete from vendor where vendor_id = '" . (int)$this->request->gethtml('vendor_id') . "'");
		$this->database->query("delete from vendor_address where vendor_id = '" . (int)$this->request->gethtml('vendor_id') . "'"); 
	}
	function get_vendor(){
		$result = $this->database->getRow("select distinct * from vendor where vendor_id = '" . (int)$this->request->gethtml('vendor_id') . "'");
		return $result;
	}
	function get_address($address_id){
		$result = $this->database->getRow("select distinct * from vendor_address where address_id = '" . (int)$address_id . "' and vendor_id = '" . (int)$this->request->gethtml('vendor_id') . "'");
		return $result;
	}
	function get_countries(){
		$results = $this->database->cache('country', "select * from country order by name");
		return $results;
	}
	function get_zones(){ 
		$results = $this->database->cache('zone', "select * from zone order by country_id, name");
		return $results;
	}
	function return_zones($country_id){
		$results = $this->database->cache('zone-' . (int)$country_id, "select zone_id, name, zone_status from zone where country_id = '" . (int)$country_id . "' order by name");
		return $results;
	}
	function get_images(){
		$results = $this->database->cache('image', "select i.image_id, i.filename, id.title from image i left join image_description id on (i.image_id = id.image_id) where id.language_id = '" . (int)$this->language->getId() . "' order by id.title");
		return $results;
	}
	function check_products(){
		$result = $this->database->getRow("select count(*) as total from product where vendor_id = '" . (int)$this->request->gethtml('vendor_id') . "'");
		return $result;
	}
	function get_page(){
		if (!$this->session->get('vendor.search')) {
		$sql = "select v.vendor_id, v.name, v.image_id, v.status, i.filename from vendor v left join image i on (v.image_id = i.image_id)";
		} else {
		$sql = "select v.vendor_id, v.name, v.image_id, v.status, i.filename from vendor v left join image i on (v.image_id = i.image_id) where name like '?'";
		}
		$sort = array('v.name', 'v.status', 'i.filename');
		if (in_array($this->session->get('vendor.sort'), $sort)) {
		$sql .= " order by " . $this->session->get('vendor.sort') . " " . (($this->session->get('vendor.order') == 'desc') ? 'desc' : 'asc');
		} else {
		$sql .= " order by name asc";
		}
	$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('vendor.search') . '%', '%' . $this->session->get('vendor.search') . '%'), $this->session->get('vendor.page'), $this->config->get('config_max_rows')));
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
	function get_products(){
		$results = $this->database->getRows("select p.product_id, pd.name, i.filename from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where pd.language_id = '" . (int)$this->language->getId() . "' and p.status = 1 order by pd.name asc");
		return $results;
	}
	function get_vendorToProduct($product_id){
		$result = $this->database->getRow("select * from product where vendor_id = '" . (int)$this->request->gethtml('vendor_id') . "' and product_id = '" . (int)$product_id . "'");
		return $result;
	}
	function write_product($product_id, $vendor_id){
		$this->database->query("update product set vendor_id = '" . (int)$vendor_id . "' where product_id = '" . (int)$product_id . "'");
	}
	function delete_vendorToProduct(){
		$this->database->query("update product set vendor_id = '0' where vendor_id = '" . (int)$this->request->gethtml('vendor_id') . "'");
	}
	function update_product($product_id){
		$this->database->query("update product set vendor_id = '" . (int)$this->request->gethtml('vendor_id') . "' where product_id = '" . (int)$product_id . "'");
	}
	function get_vendorToProducts(){
		$result = $this->database->getRows("select p.product_id, pd.name from product p left join product_description pd on (p.product_id=pd.product_id) where vendor_id = '" . (int)$this->request->gethtml('vendor_id') . "' and pd.language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
	function change_vendor_status($status, $status_id){
		$new_status = $status ? 0 : 1;
		$sql = "update vendor set status = '?' where vendor_id = '?'";
		$this->database->query($this->database->parse($sql, (int)$new_status, (int)$status_id));
	}
}
?>

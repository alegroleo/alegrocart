<?php //AdminModelManufacturer AlegroCart
class Model_Admin_Manufacturer extends Model {

	function __construct(&$locator) {
		$this->config	=& $locator->get('config');
		$this->database	=& $locator->get('database');
		$this->language	=& $locator->get('language');
		$this->request	=& $locator->get('request');
		$this->session	=& $locator->get('session');
	}

	function insert_manufacturer(){
		$sql = "insert into manufacturer set name = '?', image_id = '?', sort_order = '?', date_added = now()";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), $this->request->gethtml('image_id', 'post'), $this->request->gethtml('sort_order', 'post')));
	}

	function get_last_id(){
		$result = $this->database->getLastId();
		return $result;
	}

	function update_manufacturer(){
		$sql = "update manufacturer set name = '?', image_id = '?', sort_order = '?' where manufacturer_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), $this->request->gethtml('image_id', 'post'), $this->request->gethtml('sort_order', 'post'), (int)$this->request->gethtml('manufacturer_id')));
	}

	function delete_manufacturer(){
		$this->database->query("delete from manufacturer where manufacturer_id = '" . (int)$this->request->gethtml('manufacturer_id') . "'");
	}

	function get_manufacturer(){
		$result = $this->database->getRow("select distinct * from manufacturer where manufacturer_id = '" . (int)$this->request->gethtml('manufacturer_id') . "'");
		return $result;
	}

	function get_deleted_log(){
		$result = $this->database->getRow("SELECT CONCAT(firstname, ' ', lastname) AS modifier FROM manufacturer_log ml INNER JOIN user u ON (u.user_id = ml.trigger_modifier_id) WHERE manufacturer_id = '" . (int)$this->request->gethtml('manufacturer_id') . "' AND trigger_action = 'DELETE'");
		return $result;
	}

	function get_modified_log($date_modified){
		$result = $this->database->getRow("SELECT id.title, ml.image_id, name, sort_order, date_modified, CONCAT(firstname, ' ', lastname) AS modifier FROM manufacturer_log ml INNER JOIN user u ON (u.user_id = ml.trigger_modifier_id) INNER JOIN image_description id ON (id.image_id = ml.image_id) WHERE manufacturer_id = '" . (int)$this->request->gethtml('manufacturer_id') . "' AND id.language_id = '" . (int)$this->language->getId() . "' AND date_modified =  '" . $date_modified . "'");
		return $result;
	}

	function get_assigned_log($date_modified){
		$results = $this->database->getRows("SELECT p.product_id, pd.name FROM product p INNER JOIN product_description pd on (p.product_id=pd.product_id) WHERE manufacturer_id = '" . (int)$this->request->gethtml('manufacturer_id') . "' AND pd.language_id = '" . (int)$this->language->getId() . "' AND p.status = 1");

		return $results;
	}

	function get_images(){
		$results = $this->database->cache('image', "select i.image_id, i.filename, id.title from image i left join image_description id on (i.image_id = id.image_id) where id.language_id = '" . (int)$this->language->getId() . "' order by id.title");
		return $results;
	}

	function check_products(){
		$result = $this->database->getRow("select count(*) as total from product where manufacturer_id = '" . (int)$this->request->gethtml('manufacturer_id') . "'");
		return $result;
	}

	function get_page(){
		if (!$this->session->get('manufacturer.search')) {
			$sql = "select m.manufacturer_id, m.name, m.image_id, m.sort_order, i.filename from manufacturer m left join image i on (m.image_id = i.image_id)";
		} else {
			$sql = "select m.manufacturer_id, m.name, m.image_id, m.sort_order, i.filename from manufacturer m left join image i on (m.image_id = i.image_id) where name like '?'";
	}
		$sort = array('m.name', 'm.sort_order', 'i.filename');
		if (in_array($this->session->get('manufacturer.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('manufacturer.sort') . " " . (($this->session->get('manufacturer.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by sort_order asc, name asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('manufacturer.search') . '%', '%' . $this->session->get('manufacturer.search') . '%'), $this->session->get('manufacturer.page'), $this->config->get('config_max_rows')));
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

	function delete_SEO($query_path){
		$this->database->query("delete from url_alias where query = '".$query_path."'");
	}

	function get_products(){
		$results = $this->database->getRows("select p.product_id, pd.name, i.filename from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where pd.language_id = '" . (int)$this->language->getId() . "' and p.status = 1 order by pd.name asc");
		return $results;
	}

	function get_manufacturerToProduct($product_id){
		$result = $this->database->getRow("select * from product where manufacturer_id = '" . (int)$this->request->gethtml('manufacturer_id') . "' and product_id = '" . (int)$product_id . "'");
		return $result;
	}

	function write_product($product_id, $manufacturer_id){
		$this->database->query("update product set manufacturer_id = '" . (int)$manufacturer_id . "' where product_id = '" . (int)$product_id . "'");
	}

	function delete_manufacturerToProduct(){
		$this->database->query("update product set manufacturer_id = '0' where manufacturer_id = '" . (int)$this->request->gethtml('manufacturer_id') . "'");
	}

	function update_product($product_id){
		$this->database->query("update product set manufacturer_id = '" . (int)$this->request->gethtml('manufacturer_id') . "' where product_id = '" . (int)$product_id . "'");
	}

	function get_manufacturerToProducts(){
		$result = $this->database->getRows("select p.product_id, pd.name from product p left join product_description pd on (p.product_id=pd.product_id) where manufacturer_id = '" . (int)$this->request->gethtml('manufacturer_id') . "' and pd.language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}

	function get_manufacturerToValidProducts(){
		$results = $this->database->getRows("select p.product_id, pd.name from product p left join product_description pd on (p.product_id=pd.product_id) where manufacturer_id = '" . (int)$this->request->gethtml('manufacturer_id') . "' and pd.language_id = '" . (int)$this->language->getId() . "' and p.status = 1");
		return $results;
	}
}
?>

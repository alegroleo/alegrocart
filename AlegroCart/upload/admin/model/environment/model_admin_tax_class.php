<?php //AdminModelTaxClass AlegroCart
class Model_Admin_Tax_Class extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function insert_tax_class(){
		$sql = "insert into tax_class set title = '?', description = '?', date_added = now()";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('title', 'post'), $this->request->gethtml('description', 'post')));
	}
	function update_tax_class(){
		$sql = "update tax_class set title = '?', description = '?', date_modified = now() where tax_class_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('title', 'post'), $this->request->gethtml('description', 'post'), $this->request->gethtml('tax_class_id')));
	}
	function delete_tax_class(){
		$this->database->query("delete from tax_class where tax_class_id = '" . (int)$this->request->gethtml('tax_class_id') . "'");
		$this->database->query("delete from tax_rate where tax_class_id = '" . (int)$this->request->gethtml('tax_class_id') . "'");
	}
	function get_tax_class(){
		$result = $this->database->getRow("select * from tax_class where tax_class_id = '" . (int)$this->request->gethtml('tax_class_id') . "'");
		return $result;
	}
	function get_page(){
		if (!$this->session->get('tax_class.search')) {
			$sql = "select tax_class_id, title from tax_class";
		} else {
			$sql = "select tax_class_id, title from tax_class where title like '?'";
		}
		if (in_array($this->session->get('tax_class.sort'), array('title'))) {
			$sql .= " order by " . $this->session->get('tax_class.sort') . " " . (($this->session->get('tax_class.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by title asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('tax_class.search') . '%'), $this->session->get('tax_class.page'), $this->config->get('config_max_rows')));
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
	function check_products(){
		$result = $this->database->getRow("select count(*) as total from product where tax_class_id = '" . (int)$this->request->gethtml('tax_class_id') . "'");
		return $result;
	}
	function check_children($path){
		$results =  $this->database->getRows("select tax_rate_id from tax_rate where tax_class_id = '" . $path . "'");
		return $results;
	}
	function get_taxclassToProducts(){
		$result = $this->database->getRows("select p.product_id, pd.name from product p left join product_description pd on (p.product_id=pd.product_id) where tax_class_id = '" . (int)$this->request->gethtml('tax_class_id') . "' and pd.language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
}
?>

<?php //AdminModelProductOption AlegroCart
class Model_Admin_Productoption extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}
	function insert_option(){
		$option = explode(':', $this->request->gethtml('option', 'post'));
		$sql ="insert into product_to_option set product_id = '?', option_id = '?', option_value_id = '?', prefix = '?', price = '?', option_weight = '?', sort_order = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('product_id'), $option[0], $option[1], $this->request->gethtml('prefix', 'post'), $this->request->gethtml('price', 'post'), $this->request->gethtml('option_weight', 'post'), $this->request->gethtml('sort_order', 'post')));
	}
	function update_option(){
		$option = explode(':', $this->request->gethtml('option', 'post'));
		$sql ="update product_to_option set product_id = '?', option_id = '?', option_value_id = '?', prefix = '?', price = '?', option_weight = '?', sort_order = '?' where product_to_option_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('product_id'), $option[0], $option[1], $this->request->gethtml('prefix', 'post'), $this->request->gethtml('price', 'post'), $this->request->gethtml('option_weight', 'post'), $this->request->gethtml('sort_order', 'post'), $this->request->gethtml('product_to_option_id')));
	}
	function delete_option(){
		$this->database->query("delete from product_to_option where product_to_option_id = '" . (int)$this->request->gethtml('product_to_option_id') . "' and product_id = '" . (int)$this->request->gethtml('product_id') . "'");
	}
	function get_options(){
		$results = $this->database->getRows("select option_id, name from `option` where language_id = '" . (int)$this->language->getId() . "'");
		return $results;
	}
	function get_option_values($option_id){
		$results = $this->database->getRows("select option_value_id, option_id, name from option_value where option_id = '" . (int)$option_id . "' and language_id = '" . (int)$this->language->getId() . "'");
		return $results;
	}
	function get_product_option(){
		$result = $this->database->getRow("select distinct * from product_to_option where product_to_option_id = '" . (int)$this->request->gethtml('product_to_option_id') . "'");
		return $result;
	}
	function get_page(){
		if (!$this->session->get('product_option.search')) {
			$sql = "select p2o.product_to_option_id, o.name as `option`, ov.name as `option_value`, p2o.price, p2o.prefix, p2o.option_weight, p2o.sort_order from product_to_option p2o left join `option` o on p2o.option_id = o.option_id left join option_value ov on p2o.option_value_id = ov.option_value_id where p2o.product_id = '" . (int)$this->request->gethtml('product_id') . "' and o.language_id = '" . (int)$this->language->getId() . "' and ov.language_id = '" . (int)$this->language->getId() . "'";
		} else {
			$sql = "select p2o.product_to_option_id, o.name as `option`, ov.name as `option_value`, p2o.price, p2o.prefix, p2o.option_weight, p2o.sort_order from product_to_option p2o left join `option` o on p2o.option_id = o.option_id left join option_value ov on p2o.option_value_id = ov.option_value_id where p2o.product_id = '" . (int)$this->request->gethtml('product_id') . "' and o.language_id = '" . (int)$this->language->getId() . "' and ov.language_id = '" . (int)$this->language->getId() . "' and o.name like '?'";
		}
		$sort = array('o.name',	'ov.name', 'p2o.price',	'p2o.prefix', 'p2o.option_weight',	'p2o.sort_order');
		if (in_array($this->session->get('product_option.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('product_option.sort') . " " . (($this->session->get('product_option.order')== 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by p2o.sort_order, o.name asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('product_option.search') . '%'), $this->session->get('product_option.' . $this->request->gethtml('product_id') . '.page'), $this->config->get('config_max_rows')));
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
	function insert_product_options($product_option){
		$sql = "insert into product_options set product_id = '?', product_option = '?', quantity = '?', barcode = '?', image_id = '?', dimension_id = '?', dimension_value = '?', model_number = '?'";
		$this->database->query($this->database->parse($sql, $product_option['product_id'], $product_option['product_option'], $product_option['quantity'], $product_option['barcode'],$product_option['image_id'], $product_option['dimension_id'], $product_option['dimension_value'], $product_option['model_number']));
	}
}
?>
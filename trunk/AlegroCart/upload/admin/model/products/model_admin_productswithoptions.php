<?php //AdminModelProductsWithOptions AlegroCart
class Model_Admin_ProductsWithOptions extends Model {
	function __construct(&$locator) {
		$this->config  	=& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request  =& $locator->get('request');
		$this->session 	=& $locator->get('session');
		$this->barcode  =& $locator->get('barcode'); 
	}
	function get_products(){
		$results = $this->database->getRows("select distinct product_id from product_to_option");
		$products = array();
		foreach($results as $result){
			$products[] = array(
				'product_id'  	=> $result['product_id'],
				'product_name'	=> $this->get_product_name($result['product_id'])
			);
		}
		return $products;
	}
	function get_product_name($product_id){
		$result = $this->database->getRow("select distinct name from product_description where product_id = '" . $product_id . "' and language_id = '" . (int)$this->language->getId() . "'");
		return $result['name'];
	}
	function update_option(){
		$image_id = $this->request->gethtml('image_id', 'post') != $this->request->gethtml('no_image_id','post') ? $this->request->gethtml('image_id', 'post') : '0';
		$dimension_value = $this->request->gethtml('dimension_value', 'post');
		$dimension_id = $dimension_value[0] > 0 ? $this->request->gethtml('dimension_id', 'post') : 0;
		$dimension_value = $dimension_value[0] > 0 ? implode(':',$dimension_value) : '0:0:0';
		$sql = "update product_options set quantity = '?', barcode = '?', image_id = '?', dimension_id = '?', dimension_value = '?', model_number = '?' where product_id = '?' and product_option = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('quantity', 'post'), $this->barcode->check($this->request->gethtml('barcode', 'post'),$this->request->gethtml('encoding', 'post')), $image_id, $dimension_id, $dimension_value, $this->request->gethtml('model_number' , 'post'), $this->request->gethtml('product_id', 'post'), $this->request->gethtml('product_option')));
	}
	function get_option_values(){
		$results = $this->database->getRows("select pto.product_to_option_id, pto.option_value_id, pto.option_id, ov.name from product_to_option pto left join option_value ov on (pto.option_value_id = ov.option_value_id) where pto.product_id = '" . (int)$this->session->get('productwo_id') . "' and ov.language_id = '" . (int)$this->language->getId() . "' order by sort_order");
		return $results;
	}
	function get_options($product_id){
		$results = $this->database->getRows("select distinct option_id from product_to_option where product_id = '" . (int)$product_id . "' order by sort_order");
		return $results;
	}
	function get_option(){
		$result = $this->database->getRow("select * from product_options where product_option = '" . $this->request->gethtml('product_option') . "'");
		return $result;
	}
	function get_option_name($option_id){
		$result = $this->database->getRow("select name from `option` where option_id = '" . $option_id . "' and language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
	function get_page(){
		if (!$this->session->get('productwoptions.search')) {
			$sql = "select po.product_option, po.quantity, po.product_id, po.model_number, i.filename, pd.name from product_options po left join product_description pd on (po.product_id = pd.product_id) left join image i on (po.image_id = i.image_id) where po.product_id = '" . (int)$this->session->get('productwo_id') . "' and pd.language_id = '" . (int)$this->language->getId() . "'";
		} else {
			$sql = "select po.product_option, po.quantity, po.product_id, po.model_number, i.filename, pd.name from product_options po left join product_description pd on (po.product_id = pd.product_id) left join image i on (po.image_id = i.image_id) where po.product_id = '" . (int)$this->session->get('productwo_id') . "' and pd.language_id = '" . (int)$this->language->getId() . "' and (po.product_option like '?' or po.model_number like '?')";
		}
		$sort = array('po.product_option', 'po.model_number');
		if (in_array($this->session->get('productwoptions.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('productwoptions.sort') . " " . (($this->session->get('productwoptions.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by po.product_option asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('productwoptions.search') . '%', '%' . $this->session->get('productwoptions.search') . '%'), $this->session->get('productwoptions.page'), $this->config->get('config_max_rows')));
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
	function get_dimension_class($dimension_id){
		$result = $this->database->getRow("select title, unit, type_id from dimension where dimension_id = '" . (int)$dimension_id . "' and language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
	function get_dimension_classes($type_id){
		$results = $this->database->getRows("select * from dimension where type_id = '" . $type_id . "' and language_id = '" . (int)$this->language->getId() . "'");
		return $results;
	}
	function get_type($type_id){
		$result = $this->database->getRow("select * from dimension_type where type_id = '" . $type_id . "'");
		return $result;
	}
	function get_types(){
		$results = $this->database->getRows("select * from dimension_type");
		return $results;
	}
	function get_images(){
		$results = $this->database->cache('image', "select i.image_id, i.filename, id.title from image i left join image_description id on (i.image_id = id.image_id) where id.language_id = '" . (int)$this->language->getId() . "' order by id.title");
		return $results;
	}
	function get_no_image(){
		$result = $this->database->getRow("select id.image_id, i.filename from image_description id inner join image i on (i.image_id=id.image_id) where id.language_id = '1' and id.title = 'no image'");
		return $result;
	}
	function check_barcode_id($value, $product_option = ''){
	      $result = $this->database->getRow("select barcode, product_id from product where barcode = '". htmlspecialchars_deep($value) ."' and product_id != '".(int)$this->request->gethtml('product_id') ."'union select barcode, product_option from product_options where barcode = '". htmlspecialchars_deep($value) ."' and product_option != '".$product_option."'");
	      return $result;
	}
}	
?>

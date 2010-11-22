<?php //ModelCategory AlegroCart
class Model_Category extends Model{
	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->config   =& $locator->get('config');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');
		$this->url      =& $locator->get('url');
	}
	function get_products($manufacturer_sql,$manufacturer_filter,$model_sql,$model_filter,$search_filter,$search_order,$page_rows,$max_rows){
		$path = explode('_', $this->request->gethtml('path'));
		$results = $this->database->getRows($this->database->splitQuery("select * from product p left join product_description pd on (p.product_id = pd.product_id) left join product_to_category p2c on (p.product_id = p2c.product_id) left join image i on (p.image_id = i.image_id) where status = '1' and language_id = '".(int)$this->language->getId()."' and p2c.category_id = '".(int)end($path)."' and p.date_available < now() and p.status = '1'" . $manufacturer_sql . $manufacturer_filter . $model_sql . $model_filter . $search_filter . $search_order, ($this->request->has('path') ? $this->session->get('category.'.$this->request->gethtml('path').'.page') : $this->session->get('category.page')), $page_rows, $max_rows));
		return $results;
	}
	function get_categories($category_path){
		$path = explode('_', $category_path);
		$results = $this->database->getRows("select * from category c left join category_description cd on (c.category_id = cd.category_id) left join image i on (c.image_id = i.image_id) where c.parent_id = '".(int)(!$category_path ? '0' : (int)end($path))."' and cd.language_id = '".(int)$this->language->getId()."' order by sort_order");
		return $results;
	}
	function checkContent_category($category_path){
		$path = explode('_', $category_path);
		if (($this->database->countRows("select * from product_to_category p2c left join product p on p2c.product_id = p.product_id where p.status = '1' and p2c.category_id = '".(int)end($path)."'")) || ($this->database->countRows("select * from category where parent_id = '".(int)end($path)."'"))) {
			$result = TRUE;
		} else {
			$result = FALSE;
		}
		return $result;
	}
	function getRow_category_info($category_path){
		$path = explode('_', $category_path);
		$result = $this->database->getRow("select distinct name, description, meta_title, meta_description, meta_keywords from category c left join category_description cd on (c.category_id = cd.category_id) where c.category_id = '".(int)((!$category_path) ? '0':(int)end($path))."' and cd.language_id = '".(int)$this->language->getId()."'");
		return $result;
	}
	function getRow_category_name($category_id){
		$result = $this->database->getRow("select cd.name, c.path from category c left join category_description cd on c.category_id = cd.category_id where c.category_id = '" . (int)$category_id . "' and cd.language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
	function get_pagination(){
		$path_string = array();
		foreach (explode('_', $this->request->gethtml('path')) as $path_id){
			$path_string[] = (int)$path_id;
		}
		$path = implode('_',$path_string);
		if($this->config->get('config_url_alias')){
			$sql = "select * from url_alias where query = '?'";
			$c_path = 'controller=category&path='.$path;
			$alias = $this->database->getRow($this->database->parse($sql, $c_path));
		} else{
			$alias = '';
		}
		$page_data = array();
      	for ($i = 1; $i <= $this->database->getPages(); $i++) {
			$query=array('path' => $this->request->gethtml('path'));
			if ($i >= 1) $query['page'] = $i;
        	$page_data[] = array(
          		'text'  => $this->language->get('text_pages', $i, $this->database->getPages()),
				'href'	=> $alias ? (HTTP_SERVER . $alias['alias'] . '/page/' . $i) : $this->url->href('category', FALSE, $query),
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
	function get_model($category,$manufacturer_sql,$manufacturer_filter){
		$results = $this->database->getRows("select distinct model from product_description pd left join product p on (p.product_id = pd.product_id) left join product_to_category p2c on (p.product_id = p2c.product_id) where p2c.category_id = '".$category."' and pd.language_id = '".(int)$this->language->getId() . "'" . $manufacturer_sql . $manufacturer_filter. " and model != '' order by model asc"); //Get models
		return $results;
	}
	function get_manufacturer($category){
		$results = $this->database->getRows("select distinct manufacturer_id from product p left join product_to_category p2c on (p.product_id = p2c.product_id) where p2c.category_id = '".$category."' and manufacturer_id > '0' order by p.manufacturer_id asc");
		return $results;
	}
}
?>
<?php //ModelSearch AlegroCart
class Model_Search extends Model{
	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->config   =& $locator->get('config');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');
		$this->url      =& $locator->get('url');
	}
	function get_products($search,$description_sql,$search_description,$manufacturer_sql,$manufacturer_filter,$model_sql,$model_filter,$search_filter,$search_order,$page_rows,$max_rows){
		$results = $this->database->getRows($this->database->splitQuery("select * from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where pd.language_id = '".(int)$this->language->getId()."' and (pd.name like '".$search."' or pd.model like '".$search."' ".$description_sql." '".$search_description."') and p.date_available < now() and p.status = '1'" . $manufacturer_sql . $manufacturer_filter . $model_sql . $model_filter . $search_filter . $search_order, $this->session->get('search.page'), $page_rows,$max_rows));
		return $results;
	}
	function get_text_results(){
		$text_results = $this->language->get('text_results', $this->database->getFrom(), $this->database->getTo(), $this->database->getTotal());
		return $text_results;
	}
	function get_pagination(){
		$page_data = array();
		if($this->config->get('config_url_alias')){
			$path = 'controller=search';
			$sql="select * from url_alias where query = '?'";
			$alias = $this->database->getRow($this->database->parse($sql, $path));
		} else{
			$alias = '';
		}
        for ($i = 1; $i <= $this->database->getPages(); $i++) {
			$query=array('path' => $this->request->gethtml('path'));
			if ($i >= 1) $query['page'] = $i;
        	$page_data[] = array(
          		'text'  => $this->language->get('text_pages', $i, $this->database->getPages()),
				'href'	=> $alias ? (HTTP_SERVER . $alias['alias'] . '/page/' . $i) :$this->url->href('search', FALSE, $query),
          		'value' => $i
        	);
        }
		return $page_data;
	}
	function get_pages(){
		$pages = $this->database->getpages();
		return $pages;
	}
	function get_total(){
		$result = $this->database->total;
		return $result;
	}
	function get_model($search,$description_sql,$search_description,$manufacturer_sql,$manufacturer_filter){
		$results = $this->database->getRows("select distinct model from product_description pd left join product p on (p.product_id = pd.product_id) where pd.language_id = '".(int)$this->language->getId()."' and (pd.name like '".$search."' or pd.model like '".$search."' ".$description_sql." '".$search_description."') and p.date_available < now() and p.status = '1'" . $manufacturer_sql . $manufacturer_filter. " and model != '' order by model asc"); //Get models
		return $results;
	}
	function get_manufacturer($search,$description_sql,$search_description){
		$results = $this->database->getRows("select distinct manufacturer_id from product p left join product_description pd on (p.product_id = pd.product_id) where pd.language_id = '".(int)$this->language->getId()."' and (pd.name like '".$search."' or pd.model like '".$search."' ".$description_sql." '".$search_description."') and p.date_available < now() and p.status = '1' and manufacturer_id > '0' order by p.manufacturer_id asc");
		return $results;
	}
}
?>
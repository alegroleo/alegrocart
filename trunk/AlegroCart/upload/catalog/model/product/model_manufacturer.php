<?php //ModelManufacturer AlegroCart
class Model_Manufacturer extends Model{
	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->session  =& $locator->get('session');
		$this->config   =& $locator->get('config');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');
		$this->url      =& $locator->get('url');
	}
	function get_products($model_sql,$model_filter,$search_filter,$search_order,$page_rows,$max_rows){
		$results = $this->database->getRows($this->database->splitQuery("select * from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where pd.language_id = '".(int)$this->language->getId()."' and p.manufacturer_id = '".(int)$this->session->get('manufacturer.manufacturer_id')."' and p.date_available < now() and p.status = '1'" . $model_sql . $model_filter . $search_filter . $search_order, $this->session->get('manufacturer.page'), $page_rows, $max_rows));
		return $results;
	}
	function get_models($manufacturer_id){
		$results = $this->database->getRows("select distinct model from product_description pd left join product p on (p.product_id = pd.product_id) where p.manufacturer_id = '".(int)$manufacturer_id ."' and pd.language_id = '".(int)$this->language->getId()."' and model != '' order by model asc"); //Get models
		return $results;
	}
	function get_pagination(){
		$page_data = array();
		if($this->config->get('config_url_alias')){
			$path = 'controller=manufacturer&manufacturer_id='.$this->session->get('manufacturer.manufacturer_id');
			$sql = "select * from url_alias where query = '?'";
			$alias = $this->database->getRow($this->database->parse($sql, $path));
		} else {
			$alias = '';
		}
        for ($i = 1; $i <= $this->database->getPages(); $i++) {
			$query=array('manufacturer_id' => $this->session->get('manufacturer.manufacturer_id'));
			if ($i >= 1) $query['page'] = $i;
        	$page_data[] = array(
          		'text'  => $this->language->get('text_pages', $i, $this->database->getPages()),
				'href'	=> $alias ? (HTTP_SERVER . $alias['alias'] . '/page/' . $i) : $this->url->href('manufacturer', FALSE, $query),
          		'value' => $i
        	);
        }
		return $page_data;
	}
	function get_pages(){
		$pages = $this->database->getpages();
		return $pages;
	}
	function get_text_results(){
		$text_results = $this->language->get('text_results', $this->database->getFrom(), $this->database->getTo(), $this->database->getTotal());
		return $text_results;
	}
}
?>
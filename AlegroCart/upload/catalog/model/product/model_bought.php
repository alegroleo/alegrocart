<?php //ModelBought AlegroCart
class Model_Bought extends Model{
	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->config   =& $locator->get('config');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');
		$this->url      =& $locator->get('url');
	}

	function get_products($already_bought_existing_ids, $already_bought_deleted_ids, $customer_id, $manufacturer_sql, $manufacturer_filter, $model_sql, $model_filter, $search_filter, $search_order, $page_rows, $max_rows){

		$existinglist = implode("','", $already_bought_existing_ids);
		$sql_existing ="SELECT *, p.price AS price FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) LEFT JOIN order_product op ON (op.product_id = p.product_id) LEFT JOIN image i ON (p.image_id = i.image_id) WHERE pd.language_id = '".(int)$this->language->getId()."' AND op.order_product_id IN ('".$existinglist."')" . $manufacturer_sql . $manufacturer_filter . $model_sql . $model_filter . $search_filter . $search_order;

		$deletedlist = implode("','", $already_bought_deleted_ids);
		$sql_deleted ="SELECT product_id, model_number, vendor_name, vendor_id, order_product_id, name FROM order_product WHERE order_product_id IN ('" . $deletedlist . "')";

		if (!$already_bought_deleted_ids && !$already_bought_existing_ids) { // neither existing, nor deleted products 
			return;
		} elseif (!$already_bought_existing_ids && (!$manufacturer_sql || !$model_sql)) { //only deleted products but filters are not used
			//one query is enough, i.e. splitQuery can be used!
			$results = $this->database->getRows($this->database->splitQuery($sql_deleted, $this->session->get('bought.page'), $page_rows, $max_rows));
			return $results;
		} elseif (!$already_bought_deleted_ids || ($already_bought_deleted_ids && ($manufacturer_sql || $model_sql))) {
			//only existing products or deleted as well but filters are used
			//one query is enough, i.e. splitQuery can be used!
			$results = $this->database->getRows($this->database->splitQuery($sql_existing, $this->session->get('bought.page'), $page_rows, $max_rows));
			return $results;
		} else { 
			//there are existing and deleted products as well but filters are not used as there is no relationship with the product or product_description table 
			// two queries are needed, new function splitQueries
			$limits = $this->database->splitQueries($sql_existing, $sql_deleted, $this->session->get('bought.page'), $page_rows, $max_rows);

			$results_existing = $this->database->getRows($sql_existing . $limits[0][0]);
			$results_deleted = $this->database->getRows($sql_deleted . $limits[0][1]);

			return array_merge($results_existing, $results_deleted);
		}
	}

	function get_all_products($customer_id){
		$results = $this->database->getRows("SELECT op.order_product_id, op.product_id, p.product_id AS notdeleted, (SELECT CONCAT(op.product_id, ':',GROUP_CONCAT(CONCAT(opt.option_id, '-', opt.option_value_id) SEPARATOR '.')) FROM order_option opt WHERE opt.order_product_id = op.order_product_id) AS orderedoptions FROM order_product op LEFT JOIN `order` o ON (o.order_id = op.order_id) LEFT JOIN product p ON (op.product_id = p.product_id) WHERE o.customer_id = '".(int)$customer_id."'");
		return $results;
	}

	function get_pagination(){
		$page_data = array();
		if($this->config->get('config_url_alias')){
			$path = 'controller=bought';
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
					'href'	=> $alias ? (HTTP_SERVER . $alias['alias'] . '/page/' . $i) :$this->url->ssl('bought', FALSE, $query),
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

	function get_model($customer_id,$manufacturer_sql,$manufacturer_filter){ 
		$results = $this->database->getRows("SELECT DISTINCT model FROM product_description pd LEFT JOIN product p ON (p.product_id = pd.product_id) INNER JOIN order_product op ON (op.product_id = p.product_id) LEFT JOIN `order` o ON (o.order_id = op.order_id) WHERE o.customer_id = '".(int)$customer_id."' AND pd.language_id = '".(int)$this->language->getId() . "'" . $manufacturer_sql . $manufacturer_filter. " AND model != '' ORDER BY model ASC"); //Get models
		return $results;
	}

	function get_manufacturer($customer_id){ 
		$results = $this->database->getRows("SELECT DISTINCT manufacturer_id FROM product p INNER JOIN order_product op ON (op.product_id = p.product_id) LEFT JOIN `order` o ON (o.order_id = op.order_id) WHERE o.customer_id = '".(int)$customer_id."' AND manufacturer_id > '0' ORDER BY p.manufacturer_id ASC");
		return $results;
	}

	function get_bought_options($order_product_id) {
		$results = $this->database->getRows("SELECT value FROM order_option WHERE order_product_id = '" .(int)$order_product_id. "'");
		$list = array();
		foreach ($results as $result){
			$list[] = $result['value'];
		}
		return implode(', ', $list);
	}
}
?>

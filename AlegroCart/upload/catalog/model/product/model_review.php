<?php //ModelReview AlegroCart
class Model_Review extends Model {
	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->currency =& $locator->get('currency');
		$this->customer =& $locator->get('customer');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request  =& $locator->get('request');
		$this->tax      =& $locator->get('tax');
		$this->url      =& $locator->get('url');
	}
	function insert_review($product_id){
		$sql = "insert into review set author = '?', customer_id = '?', product_id = '?', text = '?', rating1 = '?', rating2 = '?', rating3 = '?',rating4 = '?', date_added = now()";
      	$this->database->query($this->database->parse($sql, $this->customer->getFirstName() . ' ' . $this->customer->getLastName(), $this->customer->getId(), (int)$product_id, $this->request->sanitize('text', 'post'), $this->request->gethtml('rating1', 'post'), $this->request->gethtml('rating2', 'post'), $this->request->gethtml('rating3', 'post'), $this->request->gethtml('rating4', 'post')));
	}
	function get_product($product_id){
		$result = $this->database->getRow("select * from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.product_id = '" . (int)$product_id . "' and pd.language_id = '" . (int)$this->language->getId() . "' and p.date_available < now() and p.status = '1'");
		return $result;
	}
	function getRow_review($review_id){
		$result = $this->database->getRow("select r.review_id, r.author, r.rating1, r.rating2, r.rating3, r.rating4, r.text, p.product_id, pd.name, p.price, p.tax_class_id, p.special_price, i.filename, r.date_added from review r left join product p on(r.product_id = p.product_id) left join product_description pd on(p.product_id = pd.product_id) left join image i on(p.image_id = i.image_id) where r.review_id = '" . (int)$review_id . "' and p.status = '1' and pd.language_id = '" . (int)$this->language->getId() . "' order by r.date_added desc");
		return $result;
	}
	function get_reviews($page){
		$sql = "select r.review_id, r.author, r.rating1, r.rating2, r.rating3, r.rating4, r.text, p.product_id, pd.name, p.price, i.filename, r.date_added from review r left join product p on(r.product_id = p.product_id) left join product_description pd on(p.product_id = pd.product_id) left join image i on(p.image_id = i.image_id) where p.product_id = '" . (int)$this->request->gethtml('product_id') . "' and p.date_available < now() and p.status = '1' and r.status = '1' and pd.language_id = '" . (int)$this->language->getId() . "' order by r.date_added desc";
    	$results = $this->database->getRows($this->database->splitQuery($sql, $page, $this->config->get('config_max_rows')));
		return $results;
	}
	function get_review(){
		$result = $this->database->getRow("select r.review_id, r.rating1, r.rating2, r.rating3, r.rating4, r.text, p.product_id, pd.name, i.filename from review r left join product p on(r.product_id = p.product_id) left join product_description pd on(p.product_id = pd.product_id) left join image i on(p.image_id = i.image_id) where p.status = '1' and pd.language_id = '" . (int)$this->language->getId() . "' and p.date_available < now() and p.status = '1' and r.status = '1' order by rand() limit 1");
		return $result;
	}
	function get_text_results(){
		$text_results = $this->language->get('text_results', $this->database->getFrom(), $this->database->getTo(), $this->database->getTotal());
		return $text_results;
	}
	function get_pagination(){
		$page_data = array();
		if($this->config->get('config_url_alias')){
			$path = 'controller=review&product_id='.(int)$this->request->gethtml('product_id');
			$sql = "select * from url_alias where query = '?'";
			$alias = $this->database->getRow($this->database->parse($sql, $path));
		} else {
			$alias = '';
		}
      	for ($i = 1; $i <= $this->get_pages(); $i++) {
			$query=array('product_id' => (int)$this->request->gethtml('product_id'));
			if ($i >1) $query['page'] = $i;
        	$page_data[] = array(
          		'text'  => $this->language->get('text_pages', $i, $this->get_pages()),
				'href'	=> $alias ? (HTTP_SERVER . $alias['alias'] . '/page/' . $i) : $this->url->href('review', FALSE, $query),
          		'value' => $i
        	);
      	}
		return $page_data;
	}
	function get_pages(){
		$pages = $this->database->getpages();
		return $pages;
	}
}
?>
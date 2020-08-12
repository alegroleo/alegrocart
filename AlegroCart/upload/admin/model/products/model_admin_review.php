<?php //AdminModelReview AlegroCart
class Model_Admin_Review extends Model {

	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request 	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}

	function insert_review(){
		$sql = "insert into review set author = '?', product_id = '?', text = '?', rating1 = '?', rating2 = '?', rating3 = '?', rating4 = '?', status = '?', date_added = now()";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('author', 'post'), (int)$this->request->gethtml('product_id', 'post'), $this->request->gethtml('text', 'post'), $this->request->gethtml('rating1', 'post'), $this->request->gethtml('rating2', 'post'), $this->request->gethtml('rating3', 'post'), $this->request->gethtml('rating4', 'post'), $this->request->gethtml('status', 'post')));
	}

	function update_review(){
		$sql = "update review set author = '?', product_id = '?', text = '?', rating1 = '?', rating2 = '?', rating3 = '?', rating4 = '?', status = '?', date_added = now() where review_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('author', 'post'), (int)$this->request->gethtml('product_id', 'post'), $this->request->gethtml('text', 'post'), $this->request->gethtml('rating1', 'post'), $this->request->gethtml('rating2', 'post'), $this->request->gethtml('rating3', 'post'), $this->request->gethtml('rating4', 'post'), $this->request->gethtml('status', 'post'), (int)$this->request->gethtml('review_id')));
	}

	function change_review_status($status, $status_id){
		$new_status = $status ? 0 : 1;
		$sql = "update review set status = '?' where review_id = '?'";
		$this->database->query($this->database->parse($sql, (int)$new_status, (int)$status_id));
	}

	function get_review(){
		$result = $this->database->getRow("select distinct * from review where review_id = '" . (int)$this->request->gethtml('review_id') . "'");
		return $result;
	}

	function get_products(){
		$results = $this->database->getRows("select p.product_id, pd.name, i.filename from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where pd.language_id = '" . (int)$this->language->getId() . "' order by pd.name asc");
		return $results;
	}

	function delete_review(){
		$this->database->query("delete from review where review_id = '" . (int)$this->request->gethtml('review_id') . "'");
	}

	function get_deleted_log(){
		$result = $this->database->getRow("SELECT CONCAT(firstname, ' ', lastname) AS modifier FROM review_log rl INNER JOIN user u ON (u.user_id = rl.trigger_modifier_id) WHERE review_id = '" . (int)$this->request->gethtml('review_id') . "' AND trigger_action = 'DELETE'");
		return $result;
	}

	function get_modified_log($date_modified){
		$result = $this->database->getRow("SELECT pd.name, rl.*, CONCAT(firstname, ' ', lastname) AS modifier FROM review_log rl INNER JOIN user u ON (u.user_id = rl.trigger_modifier_id) INNER JOIN product_description pd ON (pd.product_id = rl.product_id) WHERE review_id = '" . (int)$this->request->gethtml('review_id') . "' AND pd.language_id = '" . (int)$this->language->getId() . "' AND date_modified =  '" . $date_modified . "'");
		return $result;
	}

	function get_page(){
		if (!$this->session->get('review.search')) {
		    $sql = "select r.review_id, pd.name, r.author, r.rating1, r.rating2, r.rating3, r.rating4, r.status from review r left join product_description pd on (r.product_id = pd.product_id) where pd.language_id = '" . (int)$this->language->getId() . "'";
		} else {
		    $sql = "select r.review_id, pd.name, r.author, r.rating1, r.rating2, r.rating3, r.rating4, r.status from review r left join product_description pd on (r.product_id = pd.product_id) where pd.language_id = '" . (int)$this->language->getId() . "' and (r.author like '?' or pd.name like '?')";
		}
		$sort = array('pd.name', 'r.author', 'r.rating1', 'r.rating2', 'r.rating3', 'r.rating4', 'r.status');
		if (in_array($this->session->get('review.sort'), $sort)) {
		    $sql .= " order by " . $this->session->get('review.sort') . " " . (($this->session->get('review.order') == 'desc') ? 'desc' : 'asc');
		} else {
		    $sql .= " order by pd.name asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('review.search') . '%', '%' . $this->session->get('review.search') . '%'), $this->session->get('review.page'), $this->config->get('config_max_rows')));
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

	function get_last_id(){
		$result = $this->database->getLastId();
		return $result;
	}

}
?>

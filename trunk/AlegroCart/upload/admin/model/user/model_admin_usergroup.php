<?php //AdminModelUsergroup AlegroCart
class Model_Admin_Usergroup extends Model {
	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');
	}
	function insert_usergroup($permission){
		$sql ="insert into user_group set name = '?', permission = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), serialize($permission)));
	}
	function update_usergroup($permission){
		$sql ="update user_group set name = '?', permission = '?' where user_group_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), serialize($permission), $this->request->gethtml('user_group_id')));
	}
	function delete_usergroup($user_group_id){
		$this->database->query("delete from user_group where user_group_id = '" . (int)$user_group_id . "'");
	}
	function get_page($sql){
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('user_group.search') . '%'), $this->session->get('user_group.page'), $this->config->get('config_max_rows')));
		return $results;
	}
	function get_text_results(){
		$text_results = $this->language->get('text_results', $this->database->getFrom(), $this->database->getTo(), $this->database->getTotal());
		return $text_results;
	}
	function get_usergroup($user_group_id){
		$result = $this->database->getRow("select distinct * from user_group where user_group_id = '" . (int)$user_group_id . "'");
		return $result;
	}
	function check_user($user_group_id){
		$result = $this->database->getRow("select count(*) as total from user where user_group_id = '" . (int)$user_group_id . "'");
		return $result;
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
}
?>
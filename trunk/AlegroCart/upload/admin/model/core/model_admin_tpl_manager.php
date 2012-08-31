<?php //AdminModelTemplateManager AlegroCart
class Model_Admin_Tpl_Manager extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function insert_controller(){
		$sql = "insert into tpl_manager set tpl_controller = '?', tpl_columns = '?', tpl_status = '?', tpl_color = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('tpl_controller', 'post'),$this->request->gethtml('tpl_columns', 'post'),$this->request->gethtml('tpl_status', 'post'),$this->request->gethtml('tpl_color', 'post') == 'default' ? '' : $this->request->gethtml('tpl_color', 'post')));	
	}
	function update_controller(){
		$sql = "update tpl_manager set tpl_columns = '?', tpl_status = '?', tpl_color = '?' where tpl_manager_id = '?'";
		$this->database->query($this->database->parse($sql,$this->request->gethtml('tpl_columns', 'post'),$this->request->gethtml('tpl_status', 'post'),$this->request->gethtml('tpl_color', 'post') == 'default' ? '' : $this->request->gethtml('tpl_color', 'post'), (int)$this->request->gethtml('tpl_manager_id')));
	}
	function delete_controller(){
		$this->database->query("delete from tpl_manager where tpl_manager_id = '" . (int)$this->request->gethtml('tpl_manager_id') . "'");
	}
	function delete_modules(){
		$this->database->query("delete from tpl_module where tpl_manager_id = '" . (int)$this->request->gethtml('tpl_manager_id') . "'");
	}
	function insert_module($module_data, $tpl_id){
		$sql = "insert into tpl_module set tpl_manager_id = '?', location_id = '?', module_code = '?', sort_order = '?'";
		$this->database->query($this->database->parse($sql, $tpl_id, $module_data['location_id'], $module_data['module_code'], $module_data['sort_order']));
	}
	function get_last_id(){
		$result = $this->database->getLastId();
		return $result;
	}
	function check_controller(){
		$result = $this->database->getRow("select tpl_controller from tpl_manager where tpl_controller = '" . $this->request->gethtml('tpl_controller', 'post') . "'");
		return $result;
	}
	function get_config($setting){
		$result = $this->database->getRow("select * from setting where `group` = 'config' and `key` = '" . $setting . "'");
		return $result['value'];
	}
	function getModules($tpl_manager_id, $location_id){
		$results = $this->database->getRows("select * from tpl_module mo left join tpl_location lo on (mo.location_id = lo.location_id) where mo.tpl_manager_id = '" . $tpl_manager_id . "' and mo.location_id = '" . $location_id . "' order by mo.sort_order");
		return $results;
	}
	function get_controllers(){
		$results = $this->database->getRows("select distinct tpl_controller from tpl_manager");
		return $results;
	}
	function get_locations(){
		$results = $this->database->getRows("select location_id, location from tpl_location");
		return $results;
	}
	function get_location($location_id){
		$result = $this->database->getRow("select distinct * from tpl_location where location_id = '" . (int)$location_id . "'");
		return $result['location'];
	}
	function getRow_template_info($tpl_manager_id){ 
		$result = $this->database->getRow("select distinct * from tpl_manager where tpl_manager_id = '" . (int)$tpl_manager_id . "'");
		return $result;
	}
	function get_page(){
		if (!$this->session->get('tpl.search')){
			$sql = "select tpl_manager_id, tpl_controller, tpl_columns, tpl_color, tpl_status from tpl_manager";
		} else {
			$sql = "select tpl_manager_id, tpl_controller, tpl_columns, tpl_color, tpl_status from tpl_manager where tpl_controller like '?'";
		}
		$sort = array('tpl_controller', 'tpl_columns', 'tpl_color', 'tpl_status');
		if(in_array($this->session->get('tpl.sort'), $sort)){
			$sql .= " order by " . $this->session->get('tpl.sort') . " " . (($this->session->get('tpl.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by tpl_controller asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('tpl.search') . '%'), $this->session->get('tpl.page'), $this->config->get('config_max_rows')));
		return $results;
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
	function get_text_results(){
		$text_results = $this->language->get('text_results', $this->database->getFrom(), $this->database->getTo(), $this->database->getTotal());
		return $text_results;
	}	
	function change_template_status($status, $status_id){
		$new_status = $status ? 0 : 1;
		$sql = "update tpl_manager set tpl_status = '?' where tpl_manager_id = '?'";
		$this->database->query($this->database->parse($sql, (int)$new_status, (int)$status_id));
	}
}
?>

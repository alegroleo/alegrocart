<?php //AdminModelExtension AlegroCart
class Model_Admin_Extension extends Model {
	var $module_status;
	function __construct(&$locator) {	
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request 	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}
	function get_module_statuses(){
		$extensions = $this->database->getRows("select * from extension where type = 'module'");
		foreach($extensions as $extension){
			$module = explode('_',$extension['controller']);
			$module[1] = $module[1] == 'extra' ? 'catalog' : $module[1];
			$module[1] = $module[2] == 'developer' ? 'global' : $module[1];
			if (strstr($module[2], 'options')){
				$start = substr($module[2],0,strpos($module[2],'options'));
				$module[2] = $start . '_options';
			}
			$status = $this->database->getRow("select setting.value from setting where type = '" . $module[1] . "' and `key` = '" . $module[2] . '_status' . "'");
		    $this->module_status[$extension['controller']] = $status['value'];
		}
	}
	function get_status($controller){
		return $this->module_status[$controller];
	}
	function insert_extension(){
		$sql = "insert into extension set code = '?', type = '?', directory = '?', filename = '?', controller = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('code', 'post'), $this->request->gethtml('type'), $this->request->gethtml('directory', 'post'), $this->request->gethtml('filename', 'post'), $this->request->gethtml('controller', 'post')));
	}
	function get_insert_id(){
		$insert_id = $this->database->getLastId();
		return $insert_id;
	}
	function insert_description($insert_id){
		foreach ($this->request->gethtml('extension_language', 'post') as $key => $value) {
			$sql = "insert into extension_description set extension_id = '?', language_id = '?', name = '?', description = '?'";
			$this->database->query($this->database->parse($sql, $insert_id, $key, $value['name'], $value['description']));
		}
	}
	function update_extension(){
		$sql = "update extension set code = '?', type = '?', directory = '?', filename = '?', controller = '?' where extension_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('code', 'post'), $this->request->gethtml('type'), $this->request->gethtml('directory', 'post'), $this->request->gethtml('filename', 'post'), $this->request->gethtml('controller', 'post'), (int)$this->request->gethtml('extension_id')));
	}
	function delete_extension(){
		$extension_info = $this->database->getRow("select distinct * from extension where extension_id = '" . (int)$this->request->gethtml('extension_id') . "'");
		$this->database->query($this->database->parse("delete from setting where `group` = '?'", $extension_info['code']));
		$this->database->query("delete from extension where extension_id = '" . (int)$this->request->gethtml('extension_id') . "'");
		$this->delete_description();
	}
	function delete_description(){
		$this->database->query("delete from extension_description where extension_id = '" . (int)$this->request->gethtml('extension_id') . "'");
	}
	function get_page(){
		if (!$this->session->get('extension.search')) {
			$sql = "select e.extension_id, ed.name, ed.description, e.code, e.type, e.controller from extension e left join extension_description ed on e.extension_id = ed.extension_id where e.type = '?' and ed.language_id = '" . (int)$this->language->getId() . "'";
		} else {
			$sql = "select e.extension_id, ed.name, ed.description, e.code, e.type, e.controller from extension e left join extension_description ed on e.extension_id = ed.extension_id where e.type = '?' and ed.language_id = '" . (int)$this->language->getId() . "' and ed.name like '?'";
		}
		$sort = array('ed.name', 'ed.description', 'e.code');
		if (in_array($this->session->get('extension.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('extension.sort') . " " . (($this->session->get('extension.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by ed.name asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, $this->request->gethtml('type'), '%' . $this->session->get('extension.search') . '%'), $this->session->get('extension.page'), $this->config->get('config_max_rows')));
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
	function check_setting($result){
		$array = explode('_', $result['controller']);
		if (count($array) >= 3 && in_array($array[1], array('admin','catalog'))) {
			$setting_info = $this->database->countRows($this->database->parse("select * from setting where type = '?' and `group` = '?'", $array[1], $result['code']));
		} else {
			$setting_info = $this->database->countRows($this->database->parse("select * from setting where `group` = '?'", $result['code']));
		}
		return $setting_info;
	}
	function get_extension(){
		$result = $this->database->getRow("select distinct * from extension where extension_id = '" . (int)$this->request->gethtml('extension_id') . "'");
		return $result;
	}
	function get_description($language_id){
		$result = $this->database->getRow("select name, description from extension_description where extension_id = '" . (int)$this->request->gethtml('extension_id') . "' and language_id = '" . (int)$language_id . "'");
		return $result;
	}
	function get_languages(){
		$results = $this->database->cache('language', "select * from language order by sort_order");
		return $results;
	}
	function change_extension_status($status, $status_id){
		$new_status = $status ? 0 : 1;
		$extension_controller = $this->database->getRow("select controller from extension where extension_id ='" . $status_id . "'");
		$extension_type = explode('_', $extension_controller['controller']);
			if (strstr($extension_type[0], 'module')) {
			$extension_type[1] = $extension_type[1] == 'extra' ? 'catalog' : $extension_type[1];
			$extension_type[1] = $extension_type[2] == 'developer' ? 'global' : $extension_type[1];
				if (strstr($extension_type[2], 'options')){
					$start = substr($extension_type[2],0,strpos($extension_type[2],'options'));
					$extension_type[2] = $start . '_options';
				}
			$this->database->query("delete from setting where `type` = '" . $extension_type[1] . "' and `group`= '" . $extension_type[2] . "' and `key` = '" . $extension_type[2] . '_status' . "'");
			$this->database->query($this->database->parse("insert into setting set type = '?', `group` = '?', `key` = '?' , `value` = '?'", $extension_type[1],$extension_type[2],  $extension_type[2] . '_status', (int)$new_status));
			} else {
			$this->database->query("delete from setting where `key` = '" . $extension_type[1] . '_status' . "'");
			$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = '?', `key` = '?' , `value` = '?'", $extension_type[1],  $extension_type[1] . '_status', (int)$new_status));
			} 
	}
}
?>

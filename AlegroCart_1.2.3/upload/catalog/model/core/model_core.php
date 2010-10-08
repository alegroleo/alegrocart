<?php //ModelCore AlegroCart
class Model_Core extends Model {
	var $tpl_columns;
	var $locations;
	var $tpl_manager;
	var $tpl;
	var $controller;
	var $module_location;
	
	function __construct(&$locator) {
		$this->config  		=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session  	=& $locator->get('session');
		$this->url     	 	=& $locator->get('url');
	}
	function get_columns(){
		$columns = isset($this->tpl_manager['tpl_columns']) && $this->tpl_manager['tpl_columns'] > 0 ? $this->tpl_manager['tpl_columns'] : $this->config->get('config_columns');
		$this->tpl_columns = $columns;
		return $columns;
	}
	function assign_tpl_modules(){ // Template Manager
		$template_modules = $this->get_tpl_modules(@$this->tpl_manager['tpl_manager_id']);
		if($template_modules){
			foreach ($this->locations as $location){
				$tpl_modules[$location['location']]= array();
			}
			foreach($template_modules as $template_module){
				switch($template_module['location']){
					case 'header':
						$tpl_modules['header'][] = $template_module['module_code'];
						break;
					case 'extra':
						$tpl_modules['extra'][] = $template_module['module_code'];
						break;
					case 'column':
						$tpl_modules['column'][] = $template_module['module_code'];
						break;
					case 'content':
						$tpl_modules['content'][] = $template_module['module_code'];
						break;
					case 'columnright':
						if($this->tpl_columns != 2){
							$tpl_modules['columnright'][] = $template_module['module_code'];
						}
						break;
					case 'footer':
						$tpl_modules['footer'][] = $template_module['module_code'];
						break;
					case 'pagebottom':
						$tpl_modules['pagebottom'][] = $template_module['module_code'];
						break;
				}
			}
			return $tpl_modules;
		}
	}
	function get_default_modules(){
		$modules = $this->check_default();
		if($modules){ 
			$this->default_override = TRUE;
			return $modules;
		} else {
			$this->default_override = FALSE;
		}
		foreach($this->locations as $location){
			$default_modules[$location['location']][] = array();
		}
		$default_modules['header'] = array('language', 'currency', 'header', 'search', 'navigation');
		$default_modules['column'] = array('cart','category','information');
		$default_modules['footer'] = array('footer');
		$default_modules['pagebottom'] = array('developer');
		
		return $default_modules;
	}
	function check_default(){
		$tpl_manager = $this->get_tpl_manager('default');
		$default = $this->get_tpl_modules($tpl_manager['tpl_manager_id']);
		if($default){
			foreach($this->locations as $location){
				$tpl_modules[$location['location']][] = array();
			}
			foreach($default as $template_module){
				switch($template_module['location']){
					case 'header':
						$tpl_modules['header'][] = $template_module['module_code'];
						break;
					case 'extra':
						$tpl_modules['extra'][] = $template_module['module_code'];
						break;
					case 'column':
						$tpl_modules['column'][] = $template_module['module_code'];
						break;
					case 'content':
						$tpl_modules['content'][] = $template_module['module_code'];
						break;
					case 'columnright':
						$tpl_modules['columnright'][] = $template_module['module_code'];
						break;
					case 'footer':
						$tpl_modules['footer'][] = $template_module['module_code'];
						break;
					case 'pagebottom':
						$tpl_modules['pagebottom'][] = $template_module['module_code'];
						break;
				}
			}
			return $tpl_modules;
		}
	}
	function merge_modules($modules_extra){ // Template Manager
		$this->session->set('current_page', $this->url->current_page());
		$tpl_modules = $this->assign_tpl_modules();
		$modules_default = $this->get_default_modules();
		foreach ($this->locations as $location){
			if(isset($tpl_modules) && $tpl_modules[$location['location']]){
				foreach($tpl_modules[$location['location']] as $tpl_module){
					$modules[$location['location']][] = $tpl_module;
					$this->set_tpl_modules($tpl_module, $location['location']);
					if($tpl_module){$this->module_location[$tpl_module] = $location['location'];}
				}
			} else {
				foreach ($modules_default[$location['location']] as $default){
					$modules[$location['location']][] = $default;
					if($this->default_override){
						$this->set_tpl_modules($default, $location['location']);
					}
					if($default){$this->module_location[$default] = $location['location'];}
				}
				foreach ($modules_extra[$location['location']] as $extra){
					$modules[$location['location']][] = $extra;
					if($this->default_override){
						$this->set_tpl_modules($extra, $location['location']);
					}
					if($extra){$this->module_location[$extra] = $location['location'];}
				}
				
			}
			
			if ($location['location'] == 'columnright' && $this->tpl_columns == 2){
				$modules[$location['location']] = array();
			}
		}
		return $modules;
	}
	function set_tpl_modules($module, $location){
		switch($location){
			case 'header':
				$this->tpl['tpl_headers'][] = $module;
				break;
			case 'extra':
				$this->tpl['tpl_extras'][] = $module;	
				break;
			case 'column':
				$this->tpl['tpl_left_columns'][] = $module;
				break;
			case 'content':
				$this->tpl['tpl_contents'][] = $module;	
				break;
			case 'columnright':
					if($this->tpl_columns != 2){
						$this->tpl['tpl_right_columns'][] = $module;	
					}
				break;
			case 'footer':
				$this->tpl['tpl_footers'][] = $module;
				break;
			case 'pagebottom':
				$this->tpl['tpl_bottom'][] = $module;
				break;
		}
	}
	function get_tpl_manager($controller){
		$result = $this->database->getRow("select * from tpl_manager where tpl_controller = '" . $controller . "' and tpl_status = '1'");
		if ($controller != 'default'){
			$this->tpl_manager = $result;
			$this->controller = $controller;
		}
		return $result;
	}
	function get_location($controller, $module){
		$result = $this->database->getRow("select * from tpl_manager ma left join tpl_module mo on(ma.tpl_manager_id = mo.tpl_manager_id) left join tpl_location lo on(mo.location_id = lo.location_id) where ma.tpl_controller = '" . $controller . "' and tpl_status = '1' and mo.module_code = '" . $module . "'");
		
		return $result;
	}
	function check_location_id($location){
		
		return $result;
	}
	function get_tpl_modules($tpl_manager_id){
		$results = $this->database->getRows("select * from tpl_module mo left join tpl_location lo on(mo.location_id = lo.location_id) where mo.tpl_manager_id = '" . $tpl_manager_id . "'order by lo.location_id, mo.sort_order");
		return $results;
	}
	function get_tpl_locations(){
		$results = $this->database->getRows("select location_id, location from tpl_location");
		$this->locations = $results;
		return $results;
	}
	function get_location_id(){
		$results = $this->database->getRows("select location_id, location from tpl_location");
		$location_id = array();
		foreach ($results as $result){
			$location_id[$result['location']] = $result['location_id'];
		}
		return $location_id;
	}
	function get_image_display($location){
		$location_id = $this->get_location_id();
		$results = $this->database->getRows("select * from image_display id left join image_display_description idd on(id.image_display_id = idd.image_display_id) left join image i on(idd.image_id = i.image_id) where idd.language_id = '" . (int)$this->language->getId() . "' and id.status = '1' and id.location_id = '" . $location_id[$location]  . "' order by id.sort_order");
		return $results;
	}
	function get_categories(){
		$results = $this->database->getRows("select c.category_id, c.parent_id, c.path, c.sort_order, cd.name from category c left join category_description cd on (c.category_id = cd.category_id) where cd.language_id = '" . (int)$this->language->getId() . "' order by c.path");
		return $results;
	}
	function get_currencies(){
		$currencies = $this->database->cache('currency', "select * from currency order by title");
		return $currencies;
	}
	function get_homepage(){
		$results = $this->database->getRow("select * from home_page h left join home_description hd on(h.home_id = hd.home_id) left join image i on(hd.image_id = i.image_id) where hd.language_id = '" . (int)$this->language->getId() . "' and h.status = '1'");
		return $results;
	}
	function get_information(){ 
		$results = $this->database->cache('information-' . (int)$this->language->getId(), "select * from information i left join information_description id on (i.information_id = id.information_id) where id.language_id = '" . (int)$this->language->getId() . "' order by i.sort_order");
		return $results;
	}
	function getRow_information($information_id){ 
			$result = $this->database->getRow("select * from information i left join information_description id on (i.information_id = id.information_id) where i.information_id = '" . (int)$information_id . "' and id.language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
	function get_languages(){
		$results = $this->database->cache('language', "select * from language order by sort_order");
		return $results;
	}
}
?>
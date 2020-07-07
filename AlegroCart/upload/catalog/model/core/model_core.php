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
		$this->template     =& $locator->get('template');
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
						if($this->tpl_columns == 1.2 || $this->tpl_columns == 3){
							$tpl_modules['column'][] = $template_module['module_code'];
						}
						break;
					case 'content':
						$tpl_modules['content'][] = $template_module['module_code'];
						break;
					case 'columnright':
						if($this->tpl_columns == 2.1 || $this->tpl_columns == 3){
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
		if($this->tpl_columns == 2.1 || $this->tpl_columns == 1){
			$default_modules['header'][] = 'categorymenu';
		}
		if($this->tpl_columns == 1){
			$default_modules['header'][] = 'cart';
		}
		if($this->tpl_columns == 3 || $this->tpl_columns == 1.2){
			$default_modules['column'] = array('cart','category','information');
		}
		if($this->tpl_columns == 2.1){
			$default_modules['columnright'] = array('cart','information');
		}
		$default_modules['footer'] = array('footer');
		if($this->tpl_columns == 1){
			$default_modules['footer'][] = 'information';
		}
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
				if(!$this->default_override){
					foreach ($modules_extra[$location['location']] as $extra){
						$modules[$location['location']][] = $extra;
						if($this->default_override){
							$this->set_tpl_modules($extra, $location['location']);
						}
						if($extra){$this->module_location[$extra] = $location['location'];}
					}
				}
				
			}
			
			if ($location['location'] == 'columnright' && ($this->tpl_columns == 1.2 || $this->tpl_columns == 1)){
				$modules[$location['location']] = array();
			}
			if ($location['location'] == 'column' && ($this->tpl_columns == 2.1 || $this->tpl_columns == 1)){
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
				if($this->tpl_columns == 1.2 || $this->tpl_columns == 3){
					$this->tpl['tpl_left_columns'][] = $module;
				}
				break;
			case 'content':
				$this->tpl['tpl_contents'][] = $module;	
				break;
			case 'columnright':
				if($this->tpl_columns == 2.1 || $this->tpl_columns == 3){
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
		$this->template->controller = $this->controller;
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
	function get_image_display_slides($image_display_id){
		$results = $this->database->getRows("SELECT * FROM image_display_slides ids LEFT JOIN image i on (ids.image_id = i.image_id) WHERE ids.image_id != '0' AND image_display_id = '" . (int)$image_display_id . "' AND language_id = '" . (int)$this->language->getId() . "' ORDER BY sort_order");
		return $results;
	}
	function get_menucategories(){
		$results = $this->database->getRows("SELECT c.category_id, c.parent_id, c.path, c.sort_order, i.filename, cd.name FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id) LEFT JOIN image i on (c.image_id = i.image_id) WHERE cd.language_id = '" . (int)$this->language->getId() . "' AND c.category_hide = '0' AND c.path REGEXP '^[0-9]+\_*[0-9]*$' ORDER BY c.path");
		return $results;
	}
	function get_categories(){
		$results = $this->database->getRows("select c.category_id, c.parent_id, c.path, c.sort_order, cd.name from category c left join category_description cd on (c.category_id = cd.category_id) where cd.language_id = '" . (int)$this->language->getId() . "' and c.category_hide = '0' order by c.path");
		return $results;
	}
	function get_currencies(){
		$currencies = $this->database->cache('currency', "select * from currency where status = '1' order by title");
		return $currencies;
	}
	function get_homepage(){
		$results = $this->database->getRow("select * from home_page h left join home_description hd on(h.home_id = hd.home_id) left join image i on(hd.image_id = i.image_id) where hd.language_id = '" . (int)$this->language->getId() . "' and h.status = '1'");
		return $results;
	}
	function get_homepage_slides($home_id){
		$results = $this->database->getRows("SELECT * FROM home_slides hs LEFT JOIN image i on (hs.image_id = i.image_id) WHERE hs.image_id != '0' AND home_id = '" . (int)$home_id . "' AND language_id = '" . (int)$this->language->getId() . "' ORDER BY sort_order");
		return $results;
	}
	function get_information(){ 
		$results = $this->database->cache('information-' . (int)$this->language->getId(), "select * from information i left join information_description id on (i.information_id = id.information_id) where id.language_id = '" . (int)$this->language->getId() . "' and i.information_hide = '0' order by i.sort_order");
		return $results;
	}
	function getRow_information($information_id){ 
			$result = $this->database->getRow("select * from information i left join information_description id on (i.information_id = id.information_id) where i.information_id = '" . (int)$information_id . "' and id.language_id = '" . (int)$this->language->getId() . "' and i.information_hide = '0'");
		return $result;
	}
	function get_languages(){
		$results = $this->database->cache('language', "select * from language WHERE language_status=1 order by sort_order");
		return $results;
	}
	function getPrInCat($category_id){
		$result = $this->database->countRows("select p2c.product_id from product_to_category p2c inner join category c on c.category_id = p2c.category_id and (c.path = '" . $category_id . "' or c.path like '" . $category_id . "\_%' or c.path like '%\_" . $category_id . "' or c.path like '%\_" . $category_id . "\_%') inner join product p on p.product_id = p2c.product_id where p.status = '1'");
		return $result;
	}
	function return_zones($country_id){
		$results = $this->database->cache('zone-' . (int)$country_id, "SELECT zone_id, name, zone_status FROM zone WHERE country_id = '" . (int)$country_id . "' AND zone_status = '1' ORDER BY name");
		return $results;
	}
	function get_countries(){
		$results = $this->database->cache('country', "SELECT * FROM country WHERE country_status = '1'  ORDER BY name");
		return $results;
	}
	function get_maintenance(){
		$result = $this->database->getRow("SELECT * FROM maintenance_description WHERE language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
}
?>

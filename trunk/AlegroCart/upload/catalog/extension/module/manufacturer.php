<?php  // AlegroCart Manufacturer 
class ModuleManufacturer extends Controller {
	function fetch() {
		$config   =& $this->locator->get('config');	
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');	
		$url      =& $this->locator->get('url');
		$session  =& $this->locator->get('session');		
		$request  =& $this->locator->get('request');
		$template =& $this->locator->get('template');
		$head_def =& $this->locator->get('HeaderDefinition');
		$this->modelCore 	= $this->model->get('model_core');
		
		if ($config->get('manufacturer_status')) {	
			$language->load('extension/module/manufacturer.php');
			$view = $this->locator->create('template');
    		$view->set('heading_title', $language->get('heading_title'));
			$view->set('text_empty', $language->get('text_empty'));
    		$view->set('action', $url->href('manufacturer', 'page'));
			if ($request->get('controller') == 'manufacturer'){
				if ($request->has('manufacturer_id')){
					$view->set('manufacturer_id', $request->gethtml('manufacturer_id'));
				} else if($session->get('manufacturer.manufacturer_id')){
					$view->set('manufacturer_id', $session->get('manufacturer.manufacturer_id'));
				}
			} else {
				$view->set('manufacturer_id', '0');
			}
			$results = $database->cache('manufacturer', "select * from manufacturer order by sort_order, name asc");
			$manufacturers_data = array();
			foreach ($results as $result){
				$manufacturers_data[] = array(
					'manufacturer_id'	=> $result['manufacturer_id'],
					'name'				=> $result['name'],
					'href'				=> $url->href('manufacturer', FALSE, array('manufacturer_id' => $result['manufacturer_id']))
				);
			}
			$view->set('manufacturers', $manufacturers_data);			
			$view->set('head_def',$head_def);
			$view->set('location', $this->modelCore->module_location['manufacturer']); // Template Manager 
			$template->set('head_def',$head_def);	
		
    		return $view->fetch('module/manufacturer.tpl');	
		}
	}
}
?>
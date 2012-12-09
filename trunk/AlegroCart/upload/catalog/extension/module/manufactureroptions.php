<?php  //Manufacturer Options AlegroCart
class ModuleManufacturerOptions extends Controller{
	function fetch(){
		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');
		$image    =& $this->locator->get('image');
		$request  =& $this->locator->get('request');
		$session  =& $this->locator->get('session');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');
		$head_def =& $this->locator->get('HeaderDefinition');
		$this->modelProducts = $this->model->get('model_products');
		$this->modelManufacturer = $this->model->get('model_manufacturer');
		$this->modelCore 	= $this->model->get('model_core');

		if(!$config->get('manufacturer_options_status')){ RETURN;}
    	$language->load('extension/module/manufactureroptions.php');
	   	$view = $this->locator->create('template');
		$view->set('heading_title', $language->get('heading_title'));
		$view->set('manufacturer', $session->get('manufacturer.name'));
		$view->set('default_max_rows', (int)$session->get('manufacturer.max_rows'));
		$view->set('default_page_rows', (int)$session->get('manufacturer.page_rows'));
		$view->set('default_order', $session->get('manufacturer.sort_order'));
		$view->set('default_filter', $session->get('manufacturer.sort_filter'));
		$view->set('default_columns', $session->get('manufacturer.columns'));
		$view->set('columns', $session->get('manufacturer.columns'));
		$view->set('display_lock', $config->get('manufacturer_display_lock'));
		$view->set('model', substr($session->get('manufacturer.model'),0,strpos($session->get('manufacturer.model'),"_"))); //Strip Manufaturer ID
		$view->set('options_manufacturer', $config->get('options_manufacturer'));
		$view->set('options_model', $config->get('options_model'));
		
		$results = $this->modelManufacturer->get_models($session->get('manufacturer.manufacturer_id'));
		if (count($results) > 1){
			$model_data = array();
			foreach($results as $result){
				$model_data[] = array(
					'model'			=> $result['model'],
					'model_value'	=> $result['model']."_".(int)$session->get('manufacturer.manufacturer_id')
				);
			}
		} else {
			$model_data = "";
		}
		$view->set('models_data', $model_data);
		
		$query=array('manufacturer_id' => $session->get('manufacturer.manufacturer_id'));				
		$view->set('action', $url->href('manufacturer', FALSE, $query));		
		$view->set('sort_filter', $this->sort_filter());
		$view->set('sort_order', $this->sort_order());
		$view->set('text_sort_by',$language->get('text_sort_by'));
		$view->set('text_order', $language->get('text_order'));		
		$view->set('text_max_rows', $language->get('text_max_rows'));
		$view->set('text_page_rows', $language->get('text_page_rows'));
		$view->set('text_columns', $language->get('text_columns'));
		$view->set('text_model', $language->get('text_model'));
		$view->set('text_all', $language->get('text_all'));
		$view->set('entry_submit', $language->get('entry_submit'));		
		$column_data = $this->modelCore->tpl_columns == 2 ? array(1,2,3,4,5) : array(1,2,3,4);
		$view->set('column_data', $column_data);		
		$view->set('this_controller', 'manufacturer'); 	
		$view->set('head_def',$head_def); 

		return $view->fetch('module/display_options.tpl');
	
	}
	function sort_filter(){
		$language =& $this->locator->get('language');	
    	$language->load('extension/module/manufactureroptions.php');
		$sort_filter = array();
		$sort_filter[0] = $language->get('entry_number');
		$sort_filter[1] = $language->get('entry_price');
		return $sort_filter;
	}
	
	function sort_order(){
		$language =& $this->locator->get('language');
    	$language->load('extension/module/manufactureroptions.php');
		$sort_order = array();
		$sort_order[0] = $language->get('entry_ascending');
		$sort_order[1] = $language->get('entry_descending');
		return $sort_order;
	}
}
?>
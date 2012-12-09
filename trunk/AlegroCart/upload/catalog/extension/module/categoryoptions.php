<?php  //Category Options AlegroCart
class ModuleCategoryOptions extends Controller{
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
		$this->modelCategory = $this->model->get('model_category');
		$this->modelCore 	= $this->model->get('model_core');

		if(!$config->get('category_options_status')){ RETURN;};
    	$language->load('extension/module/categoryoptions.php');
	   	$view = $this->locator->create('template');
		$view->set('heading_title', $language->get('heading_title'));
		//$view->set('manufacturer', $session->get('manufacturer.name'));
	
		$view->set('default_max_rows', (int)$session->get('category.max_rows'));
		$view->set('default_page_rows', (int)$session->get('category.page_rows'));
		$view->set('default_order', $session->get('category.sort_order'));
		$view->set('default_filter', $session->get('category.sort_filter'));
		$view->set('default_columns', $session->get('category.columns'));
		$view->set('columns', $session->get('category.columns'));
		$view->set('display_lock', $config->get('category_display_lock'));
		$view->set('options_manufacturer', $config->get('options_manufacturer'));
		$view->set('options_model', $config->get('options_model'));
		
		$manufacturer_id = (int)substr($session->get('category.manufacturer'),0,strpos($session->get('category.manufacturer'),"_"));
		$model = substr($session->get('category.model'),0,strpos($session->get('category.model'),"_"));
		$cat_id = explode('_', $request->gethtml('path'));
		$category = (int)end($cat_id);
		$session->set('category.category', $category);
		$man_results = $this->modelCategory->get_manufacturer($category);
		if (count($man_results) > 1){
			$manufacturers_data = array();
			foreach ($man_results as $man_result){
				$result = $this->modelProducts->getRow_manufacturer($man_result['manufacturer_id']);
				$manufacturers_data[] = array(
					'manufacturer'	=> $result['manufacturer_id']."_".$category,
					'name'				=> $result['name']
				);
			}
		} else {
			$manufacturers_data = "";
		}
		
		if ($manufacturer_id > 0){
			$manufacturer_sql = " and p.manufacturer_id = ";
			$manufacturer_filter = "'".$manufacturer_id."'";
		} else {
			$manufacturer_sql = "";
			$manufacturer_filter = "";
		}
		$results = $this->modelCategory->get_model($category,$manufacturer_sql,$manufacturer_filter);
		if (count($results) > 1){
			$model_data = array();
			foreach($results as $result){
				$model_data[] = array(
					'model'			=> $result['model'],
					'model_value'	=> $result['model']."_".$category
				);
			}
		} else {
			$model_data = "";
		}
		$view->set('models_data', $model_data);	
		$view->set('model', $model);
		$view->set('manufacturers_data', $manufacturers_data);
		$view->set('category', $category);
		$view->set('manufacturer_id', $session->get('category.manufacturer'));
		$view->set('action', $url->href('category', FALSE, array('path' => $request->gethtml('path'))));	
		$view->set('sort_filter', $this->sort_filter());
		$view->set('sort_order', $this->sort_order());
		$view->set('text_sort_by',$language->get('text_sort_by'));
		$view->set('text_model', $language->get('text_model'));
		$view->set('text_all', $language->get('text_all'));
		$view->set('text_manufacturer_all',$language->get('text_manufacturer_all'));
		$view->set('text_manufacturer',$language->get('text_manufacturer'));
		$view->set('text_order', $language->get('text_order'));		
		$view->set('text_max_rows', $language->get('text_max_rows'));
		$view->set('text_page_rows', $language->get('text_page_rows'));
		$view->set('text_columns', $language->get('text_columns'));
		$view->set('entry_submit', $language->get('entry_submit'));			
		$column_data = $this->modelCore->tpl_columns == 2 ? array(1,2,3,4,5) : array(1,2,3,4);
		$view->set('column_data', $column_data);		
		$view->set('this_controller', 'category');
		$view->set('head_def',$head_def); 
		return $view->fetch('module/display_options.tpl');
	
	}

	function sort_filter(){
		$language =& $this->locator->get('language');	
    	$language->load('extension/module/categoryoptions.php');
		$sort_filter = array();
		$sort_filter[0] = $language->get('entry_number');
		$sort_filter[1] = $language->get('entry_price');
		return $sort_filter;
	}
	
	function sort_order(){
		$language =& $this->locator->get('language');
    	$language->load('extension/module/categoryoptions.php');
		$sort_order = array();
		$sort_order[0] = $language->get('entry_ascending');
		$sort_order[1] = $language->get('entry_descending');
		return $sort_order;
	}
}
?>
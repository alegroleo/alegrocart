<?php  //Search Options AlegroCart
class ModuleSearchOptions extends Controller{
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
		$this->modelSearch = $this->model->get('model_search');
		$this->modelCore 	= $this->model->get('model_core');
		require_once('library/application/string_modify.php');

		if(!$config->get('search_options_status')){ RETURN;};
    	$language->load('extension/module/searchoptions.php');
		if (!$session->get('search.search')) return;
	   	$view = $this->locator->create('template');
		$view->set('heading_title', $language->get('heading_title'));

		$manufacturer_id = (int)substr($session->get('search.manufacturer'),0,strpos($session->get('search.manufacturer'),"*_*"));
		$model = substr($session->get('search.model'),0,strpos($session->get('search.model'),"*_*"));		
		$view->set('default_max_rows', (int)$session->get('search.max_rows'));
		$view->set('default_page_rows', (int)$session->get('search.page_rows'));
		$view->set('default_order', $session->get('search.sort_order'));
		$view->set('default_filter', $session->get('search.sort_filter'));
		$view->set('default_columns', $session->get('search.columns'));
		$view->set('columns', $session->get('search.columns'));
		$view->set('display_lock', $config->get('search_display_lock'));
		$view->set('options_manufacturer', $config->get('options_manufacturer'));
		$view->set('options_model', $config->get('options_model'));
		
		$search = wildcardsearch($session->get('search.search'));		
		$description = $session->get('search.description');
		$view->set('description', $description);
		if ($description == "on"){
			$description_sql = "or pd.description like ";
			$search_description = $search;
		}else {
			$description_sql = '';
			$search_description = '';
		}
		$man_results = $this->modelSearch->get_manufacturer($search,$description_sql,$search_description);		
		if (count($man_results) > 1){
			$manufacturers_data = array();
			foreach ($man_results as $man_result){
				$result = $this->modelProducts->getRow_manufacturer($man_result['manufacturer_id']);
				$manufacturers_data[] = array(
					'manufacturer'	=> $result['manufacturer_id']."*_*".$session->get('search.search'),
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
		$results = $this->modelSearch->get_model($search,$description_sql,$search_description,$manufacturer_sql,$manufacturer_filter);
		if (count($results) > 1){
			$model_data = array();
			foreach($results as $result){
				$model_data[] = array(
					'model'			=> $result['model'],
					'model_value'	=> $result['model']."*_*".$session->get('search.search')
				);
			}
		} else {
			$model_data = "";
		}		
		$view->set('models_data', $model_data);	
		$view->set('model', $model);
		$view->set('manufacturers_data', $manufacturers_data);
		$view->set('manufacturer_id', $session->get('search.manufacturer'));
		$view->set('search', $session->get('search.search'));
		$view->set('action', $url->href('search', 'search_page'));
		$view->set('sort_filter', $this->sort_filter());
		$view->set('sort_order', $this->sort_order());
		$view->set('text_sort_by',$language->get('text_sort_by'));
		$view->set('text_order', $language->get('text_order'));		
		$view->set('text_max_rows', $language->get('text_max_rows'));
		$view->set('text_page_rows', $language->get('text_page_rows'));
		$view->set('text_columns', $language->get('text_columns'));
		$view->set('text_model', $language->get('text_model'));
		$view->set('text_all', $language->get('text_all'));
		$view->set('text_manufacturer_all',$language->get('text_manufacturer_all'));
		$view->set('text_manufacturer',$language->get('text_manufacturer'));
		$view->set('entry_submit', $language->get('entry_submit'));			
		$column_data = $this->modelCore->tpl_columns == 2 ? array(1,2,3,4,5) : array(1,2,3,4);
		$view->set('column_data', $column_data);		
		$view->set('this_controller', 'search');
		$view->set('head_def',$head_def);
		
		return $view->fetch('module/display_options.tpl');
	
	}
	function sort_filter(){
		$language =& $this->locator->get('language');	
    	$language->load('extension/module/searchoptions.php');
		$sort_filter = array();
		$sort_filter[0] = $language->get('entry_number');
		$sort_filter[1] = $language->get('entry_price');
		return $sort_filter;
	}
	
	function sort_order(){
		$language =& $this->locator->get('language');
    	$language->load('extension/module/searchoptions.php');
		$sort_order = array();
		$sort_order[0] = $language->get('entry_ascending');
		$sort_order[1] = $language->get('entry_descending');
		return $sort_order;
	}
}
?>
<?php  //Bought Products Options AlegroCart
class ModuleBoughtOptions extends Controller{

	public function fetch(){

		$config			=& $this->locator->get('config');
		$customer		=& $this->locator->get('customer');
		$language		=& $this->locator->get('language');
		$request		=& $this->locator->get('request');
		$session		=& $this->locator->get('session');
		$template		=& $this->locator->get('template');
		$url			=& $this->locator->get('url');
		$head_def		=& $this->locator->get('HeaderDefinition'); 
		$this->modelProducts	= $this->model->get('model_products');
		$this->modelBought	= $this->model->get('model_bought');
		$this->modelCore	= $this->model->get('model_core');

		if(!$config->get('bought_options_status')){ return;};

		$language->load('extension/module/boughtoptions.php');

		$view = $this->locator->create('template');
		$view->set('heading_title', $language->get('heading_title'));
	
		$view->set('default_max_rows', (int)$session->get('bought.max_rows'));
		$view->set('default_page_rows', (int)$session->get('bought.page_rows'));
		$view->set('default_order', $session->get('bought.sort_order'));
		$view->set('default_filter', $session->get('bought.sort_filter'));
		$view->set('default_columns', $session->get('bought.columns'));
		$view->set('columns', $session->get('bought.columns'));
		$view->set('display_lock', $config->get('bought_display_lock'));
		$view->set('options_manufacturer', $config->get('options_manufacturer'));
		$view->set('options_model', $config->get('options_model'));

		$manufacturer_id = (int)substr($session->get('bought.manufacturer'),0,strpos($session->get('bought.manufacturer'),"_"));

		$model = substr($session->get('bought.model'),0,strpos($session->get('bought.model'),"_"));

		$customer_id = (int)$customer->getId();

		$man_results = $this->modelBought->get_manufacturer($customer_id); //check
		$manufacturers_data = array();
		if (count($man_results) > 1){
			foreach ($man_results as $man_result){
				$result = $this->modelProducts->getRow_manufacturer($man_result['manufacturer_id']);
				$manufacturers_data[] = array(
					'manufacturer'	=> $result['manufacturer_id']."_".$customer_id,
					'name'		=> $result['name']
				);
			}
		}

		if ($manufacturer_id > 0){
			$manufacturer_sql = " and p.manufacturer_id = ";
			$manufacturer_filter = "'".$manufacturer_id."'";
		} else {
			$manufacturer_sql = "";
			$manufacturer_filter = "";
		}

		$results = $this->modelBought->get_model($customer_id,$manufacturer_sql,$manufacturer_filter);
		$model_data = array();
		if (count($results) > 1){
			foreach($results as $result){
				$model_data[] = array(
					'model'		=> $result['model'],
					'model_value'	=> $result['model']."_".$customer_id
				);
			}
		}

		$view->set('models_data', $model_data);	
		$view->set('model', $model);
		$view->set('manufacturers_data', $manufacturers_data);
		$view->set('customer_id', $customer_id);
		$view->set('manufacturer_id', $session->get('bought.manufacturer'));

		$view->set('action', $url->ssl('bought'));

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
		$column_data = $this->modelCore->tpl_columns != 3 ? array(1,2,3,4,5) : array(1,2,3,4);
		$view->set('column_data', $column_data);
		$view->set('this_controller', 'bought');
		$view->set('head_def',$head_def);

		return $view->fetch('module/display_options.tpl');
	
	}

	private function sort_filter(){
		$language =& $this->locator->get('language');
		$language->load('extension/module/boughtoptions.php');
		$sort_filter = array();
		$sort_filter[0] = $language->get('entry_number');
		$sort_filter[1] = $language->get('entry_price');
		return $sort_filter;
	}

	private function sort_order(){
		$language =& $this->locator->get('language');
		$language->load('extension/module/boughtoptions.php');
		$sort_order = array();
		$sort_order[0] = $language->get('entry_ascending');
		$sort_order[1] = $language->get('entry_descending');
		return $sort_order;
	}
}
?>

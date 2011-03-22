<?php // Report Viewed AlegroCart
class ControllerReportViewed extends Controller {
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->response 	=& $locator->get('response');
		$this->template 	=& $locator->get('template');
		$this->modelReportViewed = $model->get('model_admin_report_viewed');
		
		$this->language->load('controller/report_viewed.php');
		}
	function index() {   
		$this->template->set('title', $this->language->get('heading_title'));
 
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));
 		
		$view->set('column_name', $this->language->get('column_name'));
		$view->set('column_viewed', $this->language->get('column_viewed'));
		$view->set('column_percent', $this->language->get('column_percent'));
		
		$product_data = array();
		
		$results = $this->modelReportViewed->get_viewed();
		
		$total = 0;
		
		foreach ($results as $result) {
			$total += $result['viewed'];
		}
		
		foreach ($results as $result) {
			$percent= $total ? roundDigits(($result['viewed'] / $total) * 100, 2):0;
			//$percent=$total?number_format(($result['viewed'] / $total) * 100, 2, '.', ''):0;
			$product_data[] = array(
				'name'    => $result['name'],
				'viewed'  => $result['viewed'],
				'percent' => $percent.'%',
				'graph'   => number_format($percent, 2, '.', '') . '%'
			);
		}
		
		$view->set('products', $product_data);
			
		$this->template->set('content', $view->fetch('content/report_viewed.tpl'));
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
}
?>
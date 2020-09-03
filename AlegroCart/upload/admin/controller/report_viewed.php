<?php // Report Viewed AlegroCart
class ControllerReportViewed extends Controller {

	public function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->language		=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request		=& $locator->get('request');
		$this->response		=& $locator->get('response');
		$this->session		=& $locator->get('session');
		$this->template		=& $locator->get('template');
		$this->url		=& $locator->get('url');
		$this->modelReportViewed = $model->get('model_admin_report_viewed');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('report_viewed');

		$this->language->load('controller/report_viewed.php');
		}

	protected function index() {
		$this->template->set('title', $this->language->get('heading_title'));
 
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('last', $this->url->getLast('report_viewed'));
		$view->set('action', $this->url->ssl('report_viewed'));
		$view->set('help', $this->session->get('help'));

		$view->set('button_reset', $this->language->get('button_reset'));

		$view->set('column_name', $this->language->get('column_name'));
		$view->set('column_viewed', $this->language->get('column_viewed'));
		$view->set('column_percent', $this->language->get('column_percent'));

		if ($this->request->isPost() && $this->request->has('reset', 'post')) {
			$result = $this->modelReportViewed->reset(); 
		}

		$product_data = array();
		
		$results = $this->modelReportViewed->get_viewed();
		
		$total = 0;
		$max=0;

		foreach ($results as $result) {
			$total += $result['viewed'];
		}
		$max= $total ? number_format(($results[0]['viewed'] / $total) * 100, 2, '.', ''):0;

		foreach ($results as $result) {
			$percent= $total ? roundDigits(($result['viewed'] / $total) * 100, 2):0;

			$product_data[] = array(
				'name'		=> $result['name'],
				'viewed'	=> $result['viewed'],
				'percent'	=> $percent.'%',
				'graph'		=> $max ? number_format((100/$max)*$percent, 2,'.','') . '%' : number_format(0, 2,'.','') .'%',
				'href'		=> $this->url->ssl('product', 'update', array('product_id' => $result['product_id']))
			);
		}
		
		$view->set('products', $product_data);
			
		$this->template->set('content', $view->fetch('content/report_viewed.tpl'));
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}
}
?>

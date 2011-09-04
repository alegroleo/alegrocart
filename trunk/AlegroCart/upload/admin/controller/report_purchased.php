<?php // Repor Purchased AlegroCart
class ControllerReportPurchased extends Controller {
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->config   	=& $locator->get('config');
		$this->currency 	=& $locator->get('currency');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->response 	=& $locator->get('response');
		$this->template 	=& $locator->get('template');
		$this->modelReportPurchsed = $model->get('model_admin_report_purchased');
		
		$this->language->load('controller/report_purchased.php');
		}
	function index() {  
		$this->template->set('title', $this->language->get('heading_title'));

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('column_name', $this->language->get('column_name'));
		$view->set('column_model_number', $this->language->get('column_model_number'));
		$view->set('column_quantity', $this->language->get('column_quantity'));
		$view->set('column_total', $this->language->get('column_total'));
		
		$product_data = array();
		$results = $this->modelReportPurchsed->get_purchases();
		foreach ($results as $result) {
			$product_data[] = array(
				'name'         => $result['name'],
				'model_number' => $result['model_number'],
				'quantity'     => $result['quantity'],
				'total'        => $this->currency->format($result['total'], $this->config->get('config_currency'))
			);
		}
		
		$view->set('products', $product_data);

		$this->template->set('content', $view->fetch('content/report_purchased.tpl'));
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
}
?>
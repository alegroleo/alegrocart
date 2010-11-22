<?php   
class ModuleCurrency extends Controller {
	function fetch() {
		$config   =& $this->locator->get('config');
		$currency =& $this->locator->get('currency');
		$language =& $this->locator->get('language');		
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$url      =& $this->locator->get('url');
		$this->modelCore = $this->model->get('model_core');
		
		if ($config->get('currency_status')) {	
			if (($request->isPost()) && ($request->has('currency', 'post'))) {
      			$currency->set($request->gethtml('currency', 'post'));

  				$response->redirect($url->requested($url->href('home')));
   			}
    	
			$language->load('extension/module/currency.php');
		
			$view = $this->locator->create('template');
		
   			$view->set('heading_title', $language->get('heading_title'));

   			$view->set('action', $url->requested($url->href('home')));
   			$view->set('text_currency', $language->get('text_currency'));
   			$view->set('entry_currency', $language->get('entry_currency'));

   			$view->set('default', $currency->getCode());
			$view->set('location', $this->modelCore->module_location['currency']); // Template Manager 
   			$view->set('currencies', $this->modelCore->get_currencies());

   			return $view->fetch('module/currency.tpl'); 
		}
	} 
}
?>
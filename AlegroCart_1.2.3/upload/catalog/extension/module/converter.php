<?php  // Currency Converter AlegroCart
class ModuleConverter extends Controller {
	function fetch() {
		$config   =& $this->locator->get('config');
		$currency =& $this->locator->get('currency');
		$language =& $this->locator->get('language');		
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$url      =& $this->locator->get('url');
		$head_def =& $this->locator->get('HeaderDefinition');
		$this->modelCore = $this->model->get('model_core');
		$this->modelConverter = $this->model->get('model_converter');
		if ($config->get('converter_status')) {
			$language->load('extension/module/converter.php');
			$view = $this->locator->create('template');
			$view->set('heading_title', $language->get('heading_title'));
			
			$view->set('currencies', $this->modelConverter->get_currencies());
			$view->set('text_base', $language->get('text_base'));
			$view->set('text_convert_to', $language->get('text_convert_to'));
			$view->set('text_amount', $language->get('text_amount'));
			$view->set('text_button', $language->get('text_button'));
			
			$view->set('head_def',$head_def);
			$view->set('location', $this->modelCore->module_location['converter']); // Template Manager 
			return $view->fetch('module/converter.tpl'); 
		}	
	}
}
?>
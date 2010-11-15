<?php  //Information AlegroCart
class ModuleInformation extends Controller {
	function fetch() {
		$config   =& $this->locator->get('config');
		$head_def =& $this->locator->get('HeaderDefinition');  
		$language =& $this->locator->get('language');
		$url      =& $this->locator->get('url');
		$this->modelCore = $this->model->get('model_core');
		$this->modelProducts = $this->model->get('model_products');
		
		if ($config->get('information_status')) {	
			$language->load('extension/module/information.php');
		
			$view = $this->locator->create('template');
		
    		$view->set('heading_title', $language->get('heading_title'));

    		$view->set('text_contact', $language->get('text_contact'));
    		$view->set('text_sitemap', $language->get('text_sitemap'));
			if ($this->modelProducts->currentpage($this->modelCore->controller)){
				$tax_included = $config->get('config_tax_store');
				$view->set('tax_included', $tax_included);
				$view->set('text_tax', $language->get('text_tax'));
				$view->set('text_tax_explantion', $language->get('text_tax_explantion'));
			}

    		$information_data = array();
			
			$results = $this->modelCore->get_information();

    		foreach ($results as $result) {
      			$information_data[] = array(
        			'title' => $result['title'],
	    			'href'  => $url->href('information', false, array('information_id' => $result['information_id']))
      			);
    		}

    		$view->set('information', $information_data);
    		$view->set('contact', $url->href('contact'));
    		$view->set('sitemap', $url->href('sitemap'));
			$view->set('head_def',$head_def);
			$view->set('location', $this->modelCore->module_location['information']); // Template Manager 
			
   			return $view->fetch('module/information.tpl');
		}
	}
}
?>
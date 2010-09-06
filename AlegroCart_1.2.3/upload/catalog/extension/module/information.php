<?php  //Information AlegroCart
class ModuleInformation extends Controller {
	function fetch() {
		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');
		$url      =& $this->locator->get('url');
		$this->modelCore = $this->model->get('model_core');
		
		if ($config->get('information_status')) {	
			$language->load('extension/module/information.php');
		
			$view = $this->locator->create('template');
		
    		$view->set('heading_title', $language->get('heading_title'));

    		$view->set('text_contact', $language->get('text_contact'));
    		$view->set('text_sitemap', $language->get('text_sitemap'));

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
			$view->set('location', $this->modelCore->module_location['information']); // Template Manager 
			
   			return $view->fetch('module/information.tpl');
		}
	}
}
?>
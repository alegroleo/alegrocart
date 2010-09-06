<?php    
class ControllerError extends Controller {   
	function index() { 
    	$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
		 
    	$language->load('controller/error.php');
 
    	$template->set('title', $language->get('heading_title'));
		
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $language->get('heading_title'));

    	$view->set('heading_description', $language->get('heading_description'));    
    
		$template->set('content', $view->fetch('content/error.tpl'));
    
		$template->set($module->fetch());
	
		$response->set($template->fetch('layout.tpl'));
  	}
}
?>
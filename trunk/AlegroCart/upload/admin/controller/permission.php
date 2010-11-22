<?php    
class ControllerPermission extends Controller {   	
	function index() {  
    	$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
		 
		$language->load('controller/permission.php');
		 
    	$template->set('title', $language->get('heading_title'));
		
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $language->get('heading_title'));

    	$view->set('heading_description', $language->get('heading_description')); 
		    
		$template->set('content', $view->fetch('content/error.tpl'));
    
		$template->set($module->fetch());
	
		$response->set($template->fetch('layout.tpl'));
  	}
		
	function hasPermission() {
		$request =& $this->locator->get('request');
		$user    =& $this->locator->get('user');
		
		if (!$request->has('controller')) {
			if (!$user->hasPermission('access', 'home')) {
				return $this->forward('permission', 'index');
			}
		} elseif (!$user->hasPermission('access', $request->gethtml('controller'))) {
			return $this->forward('permission', 'index');
		}	
	}	
}
?>
<?php // Maintenance AlegroCart
class ControllerMaintenance extends Controller {
	function index() {
		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');
		$session  =& $this->locator->get('session');
		$head_def =& $this->locator->get('HeaderDefinition');
		
		$language->load('controller/maintenance.php');

		$template->set('title', $language->get('heading_title'));
		
		$view = $this->locator->create('template');
		
		$view->set('heading_title', $language->get('heading_title'));
		$session->set('maintenance', 'enabled');
		$view->set('text_maintenance', $language->get('text_maintenance'));
		$view->set('head_def',$head_def);    
		$template->set('head_def',$head_def);
		
 		$template->set('content', $view->fetch('content/maintenance.tpl'));
		
		$template->set($module->load('header'));
		$template->set($module->load('footer'));
		$template->set($module->fetch());
		$response->set($template->fetch('layout_maintenance.tpl'));
	}
	
	function CheckMaintenance() {
		$user 	=& $this->locator->get('user');
		$config	=& $this->locator->get('config');

		if ($config->get('maintenance_status') && !$user->isLogged()) {
			return $this->forward('maintenance', 'index');
		}
	}
	
}
?>
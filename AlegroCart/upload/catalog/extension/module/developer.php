<?php  // Developver AlegroCart
class ModuleDeveloper extends Controller {
	function fetch() {
		$config   =& $this->locator->get('config');
		$head_def =& $this->locator->get('HeaderDefinition'); 
		$language =& $this->locator->get('language');
		
		
		if ($config->get('developer_status')) {
			$language->load('extension/module/developer.php');
			
			$view = $this->locator->create('template');
			$view->set('text_developer', $language->get('text_developer', date('Y'), $config->get('developer_developer')));
			$view->set('developer_link', $config->get('developer_link'));
			
			return $view->fetch('module/developer.tpl');
		}
	}
}
?>
<?php //Admin Footer AlegroCart
class ModuleFooter extends Controller {  
	function fetch() {
		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');
		
		if ($config->get('footer_status')) {
			$language->load('extension/module/footer.php');
			
			$view = $this->locator->create('template');
			
			$view->set('text_support', $language->get('text_support'));
			$view->set('text_powered_by', $language->get('text_powered_by', date('Y')));
			if($config->get('developer_status')) {
				$view->set('text_developer', $language->get('text_developer', $config->get('developer_developer')));
				$view->set('developer_link', $config->get('developer_link'));
			
			}

    		return $view->fetch('module/footer.tpl');
  		}
	}
}
?>
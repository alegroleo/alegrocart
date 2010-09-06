<?php  
class ModuleNavigation extends Controller {
	function fetch() {
		$config   =& $this->locator->get('config');
		$customer =& $this->locator->get('customer');
		$language =& $this->locator->get('language');
		$url      =& $this->locator->get('url');
		$this->modelCore 	= $this->model->get('model_core');
		
		if ($config->get('navigation_status')) {	
			$language->load('extension/module/navigation.php');
				
			$view = $this->locator->create('template');

    		$view->set('text_home', $language->get('text_home'));
    		$view->set('text_account', $language->get('text_account'));
    		$view->set('text_login', $language->get('text_login'));
    		$view->set('text_logout', $language->get('text_logout'));
    		$view->set('text_cart', $language->get('text_cart')); 
    		$view->set('text_checkout', $language->get('text_checkout'));
    	
			$view->set('home', $url->href('home'));

    		$view->set('account', $url->ssl('account'));

    		if (!$customer->isLogged()) {
      			$view->set('login', $url->ssl('account_login'));
    		} else {
      			$view->set('logout', $url->href('account_logout'));
    		}

    		$view->set('cart', $url->href('cart'));
	
    		$view->set('checkout', $url->ssl('checkout_shipping'));
			$view->set('location', $this->modelCore->module_location['navigation']); // Template Manager 

    		return $view->fetch('module/navigation.tpl');
		}
	}
}
?>
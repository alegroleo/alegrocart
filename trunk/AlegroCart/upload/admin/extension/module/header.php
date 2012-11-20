<?php //Admin Header AlegroCart
class ModuleHeader extends Controller {
	function fetch() {
		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
		
		if (($user->isLogged()) && $config->get('header_status')) {		
			$language->load('extension/module/header.php');

			$view = $this->locator->create('template');

			$view->set('text_heading', $language->get('text_heading'));
			$view->set('version', $config->get('version'));

			if ($user->isLogged()) {
				$view->set('user', $language->get('text_user', $user->getUserName()));
			} else {
				$view->set('user', false);
			}
			
			return $view->fetch('module/header.tpl');
		}
	}
}
?>

<?php 
class ControllerError extends Controller {

	protected function index() {
		$response	=& $this->locator->get('response');
		$session 	=& $this->locator->get('session');
		$language	=& $this->locator->get('language');
		$template	=& $this->locator->get('template');
		$module		=& $this->locator->get('module');

		$language->load('controller/error.php');

		$template->set('title', $language->get('heading_title'));

		$view = $this->locator->create('template');

		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));
		$view->set('button_help', $language->get('button_help'));
		$view->set('help', $session->get('help'));

		$template->set('content', $view->fetch('content/error.tpl'));
		$template->set($module->fetch());
		$response->set($template->fetch('layout.tpl'));
	}

	protected function help(){
		$session  =& $this->locator->get('session');
		if($session->get('help')){
			$session->delete('help');
		} else {
			$session->set('help', TRUE);
		}
	}
}
?>

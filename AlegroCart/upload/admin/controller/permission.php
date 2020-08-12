<?php 
class ControllerPermission extends Controller {

	protected function index() {
		$response	=& $this->locator->get('response');
		$language	=& $this->locator->get('language');
		$session	=& $this->locator->get('session');
		$template	=& $this->locator->get('template');
		$module		=& $this->locator->get('module');

		$language->load('controller/permission.php');

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
		if($session->get('help')){
			$session->delete('help');
		} else {
			$session->set('help', TRUE);
		}
	}

	protected function hasPermission() {
		$request	=& $this->locator->get('request');
		$user		=& $this->locator->get('user');

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

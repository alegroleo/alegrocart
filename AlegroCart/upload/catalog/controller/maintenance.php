<?php // Maintenance AlegroCart
class ControllerMaintenance extends Controller {
	function index() {
		$model			=& $this->locator->get('model');
		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');
		$session  =& $this->locator->get('session');
		$head_def =& $this->locator->get('HeaderDefinition');
		$this->modelCore	= $this->model->get('model_core');

		$language->load('controller/maintenance.php');

		$template->set('title', $language->get('heading_title'));

		$view = $this->locator->create('template');

		$view->set('heading_title', $language->get('heading_title'));
		$session->set('maintenance', 'enabled');
		$maintenance_data = array();

		$result = $this->modelCore->get_maintenance();
		$view->set('maintenance_description', $result['description']);
		$view->set('maintenance_header', $result['header']);
		$view->set('head_def',$head_def);

		$template->controller = 'maintenance';
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

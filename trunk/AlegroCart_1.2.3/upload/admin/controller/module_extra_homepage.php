<?php  //Home Page Module
class ControllerModuleExtraHomepage extends Controller {
	var $error = array();
	// All References change to module_extra_ due to new module loader
	function index() { 
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');	

		$language->load('controller/module_extra_homepage.php');

		$template->set('title', $language->get('heading_title'));
	
			if (($request->isPost()) && ($this->validate())) {
			$database->query("delete from setting where type = 'catalog' and `group` = 'homepage'");
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'homepage', `key` = 'homepage_status', `value` = '?'", $request->get('catalog_homepage_status', 'post')));	
		$session->set('message', $language->get('text_message'));
			$response->redirect($url->ssl('extension', FALSE, array('type' => 'module')));
		}
		$view = $this->locator->create('template');
		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));
		$view->set('text_enabled', $language->get('text_enabled'));
		$view->set('text_disabled', $language->get('text_disabled'));
		$view->set('text_homepage', $language->get('text_homepage'));
		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));
		$view->set('button_home', $language->get('button_home'));
		$view->set('entry_status', $language->get('entry_status'));
		$view->set('tab_module', $language->get('tab_module'));
		$view->set('tab_homepage', $language->get('tab_homepage'));
		$view->set('error', @$this->error['message']);
		$view->set('action', $url->ssl('module_extra_homepage'));
		$view->set('action_home', $url->ssl('homepage'));
		$view->set('list', $url->ssl('extension', FALSE, array('type' => 'module')));
		$view->set('cancel', $url->ssl('extension', FALSE, array('type' => 'module')));
		if (!$request->isPost()) {
			$results = $database->getRows("select * from setting where type = 'catalog' and `group` = 'homepage'");
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		if ($request->has('catalog_homepage_status', 'post')) {
			$view->set('catalog_homepage_status', $request->get('catalog_homepage_status', 'post'));
		} else {
			$view->set('catalog_homepage_status', @$setting_info['catalog']['homepage_status']);
		}
		$template->set('content', $view->fetch('content/module_extra_homepage.tpl'));
		$template->set($module->fetch());
		$response->set($template->fetch('layout.tpl'));
		}
	}
	function validate() {
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');

		if (!$user->hasPermission('modify', 'module_extra_homepage')) {
			$this->error['message'] = $language->get('error_permission');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	function install() {
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		$user     =& $this->locator->get('user');

		$language->load('controller/module_extra_homepage.php');	
		
		if ($user->hasPermission('modify', 'module_extra_homepage')) {
			$database->query("delete from setting where type = 'catalog' and `group` = 'homepage'");
			
			$database->query("insert into setting set type = 'catalog', `group` = 'homepage', `key` = 'homepage_status', value = '1'");
			
			$session->set('message', $language->get('text_message'));
		} else {
			$session->set('error', $language->get('error_permission'));
		}	
				
		$response->redirect($url->ssl('extension', FALSE, array('type' => 'module')));		
	}
	function uninstall() {
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		$user     =& $this->locator->get('user');
		
		$language->load('controller/module_extra_homepage.php');
		if ($user->hasPermission('modify', 'module_extra_homepage')) {
			$database->query("delete from setting where type = 'catalog' and `group` = 'homepage'");			
			$session->set('message', $language->get('text_message'));
		} else {
			$session->set('error', $language->get('error_permission'));
		}
		$response->redirect($url->ssl('extension', FALSE, array('type' => 'module')));
	}
}	
?>
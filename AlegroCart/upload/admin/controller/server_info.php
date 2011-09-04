<?php       
class ControllerServerInfo extends Controller {
	function index() { 
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
	
    	$language->load('controller/server_info.php');

    	$template->set('title', $language->get('heading_title'));
		
		$view = $this->locator->create('template'); 
		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));
		$view->set('button_print', $language->get('button_print'));

    	$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));

		$view->set('text_php', $language->get('text_php'));
		$view->set('text_db', $language->get('text_db'));
		$view->set('text_db_server', $language->get('text_db_server'));
		$view->set('text_db_name', $language->get('text_db_name'));

		$view->set('php', phpversion());

		$view->set('server', $_SERVER['SERVER_SOFTWARE']);

		$view->set('db', 'MySQL ' . mysql_get_server_info());

		$view->set('db_server', DB_HOST);

		$view->set('db_name', DB_NAME);
 
		ob_start();
	
		phpinfo();
	
		$phpinfo = ob_get_contents();
	
		ob_end_clean();

		$phpinfo = str_replace('border: 1px', '', $phpinfo);

		preg_match('#<body>(.*?)</body>#is', $phpinfo, $regs);

		$view->set('phpinfo', $regs[1]);

		$template->set('content', $view->fetch('content/server_info.tpl'));
	
		$template->set($module->fetch());
	
		$response->set($template->fetch('layout.tpl')); 
  	}
}
?>

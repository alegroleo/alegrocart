<?php       
class ControllerServerInfo extends Controller {
	function __construct(&$locator){
		$this->locator	=& $locator;
		$model	=& $locator->get('model');
		$this->config	=& $locator->get('config');
		$this->request  =& $locator->get('request');
		$this->response =& $locator->get('response');
		$this->session  =& $locator->get('session');
		$this->language =& $locator->get('language');
		$this->template =& $locator->get('template');
		$this->module   =& $locator->get('module');
		$this->modelServerInfo = $model->get('model_admin_server_info');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController = $this->template->set_controller('server_info');

		$this->language->load('controller/server_info.php');
	}
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));
		
		$view = $this->locator->create('template'); 
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));

		$view->set('help', $this->session->get('help'));

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_ac', $this->language->get('text_ac'));
		$view->set('text_php', $this->language->get('text_php'));
		$view->set('text_db', $this->language->get('text_db'));
		$view->set('text_db_server', $this->language->get('text_db_server'));
		$view->set('text_db_name', $this->language->get('text_db_name'));

		$view->set('version', $this->config->get('version'));
		$view->set('php', phpversion());

		$view->set('server', $_SERVER['SERVER_SOFTWARE']);

		$view->set('db', 'MySQL ' . $this->modelServerInfo->get_server_info());

		$view->set('db_server', DB_HOST);

		$view->set('db_name', DB_NAME);

		ob_start();

		phpinfo();

		$phpinfo = ob_get_contents();

		ob_end_clean();

		$phpinfo = str_replace('border: 1px', '', $phpinfo);

		preg_match('#<body>(.*?)</body>#is', $phpinfo, $regs);

		$view->set('phpinfo', $regs[1]);

		$view->set('head_def',$this->head_def); 

		$this->template->set('content', $view->fetch('content/server_info.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl')); 
	}
	function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}
}
?>

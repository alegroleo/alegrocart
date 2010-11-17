<?php // Home AlegroCart
class ControllerHome extends Controller {
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->config  		=& $locator->get('config');
		$this->config->set('config_tax', $this->config->get('config_tax_store'));
		$this->module   	=& $locator->get('module');
		$this->template 	=& $locator->get('template');
		$this->modelCore 	= $model->get('model_core');
		$this->tpl_manager = $this->modelCore->get_tpl_manager('home'); // Template Manager
		$this->locations = $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns = $this->modelCore->get_columns();// Template Manager
	}
	function index() {
		
		$customer =& $this->locator->get('customer');
		$language =& $this->locator->get('language');
		$response =& $this->locator->get('response');
		$url      =& $this->locator->get('url');
		$request  =& $this->locator->get('request');
		$session  =& $this->locator->get('session');
		$language->load('controller/home.php');

		$this->template->set('title', $language->get('title', $this->config->get('config_store')));

		$view = $this->locator->create('template');
		$view->set('action', HTTP_ADMIN);
		$view->set('heading_title', $language->get('heading_title', $this->config->get('config_store')));

		
		if ($customer->isLogged()) {
			$view->set('text_greeting', $language->get('text_logged', $customer->getFirstName()));
		} else {
			$view->set('text_greeting', $language->get('text_greeting', $url->ssl('account_login'), $url->ssl('account_create')));
		}
		
		$this->template->set('content', $view->fetch('content/home.tpl'));

		$this->load_modules();  // Template Manager
		$this->set_tpl_modules(); // Template Manager

		$this->template->set($this->module->fetch());
		
		$response->set($this->template->fetch('layout.tpl'));
	}

	function load_modules(){ // Template Manager
		$modules = $this->modelCore->merge_modules($this->get_modules_extra());
		foreach ($this->locations as $location){
			if($modules[$location['location']]){
				foreach($modules[$location['location']] as $module){
					$this->template->set($this->module->load($module));
				}
			}
		}
	}
	function get_modules_extra(){// Template Manager (Default Modules specific to current controller)
		foreach($this->locations as $location){
			$modules_extra[$location['location']] = array();
		}
		$modules_extra['column'] = array('manufacturer', 'popular', 'review');
		$modules_extra['content'] = array('homepage', 'featured', 'latest');
		$modules_extra['columnright'] = array('specials');
		return $modules_extra;
	}

	function set_tpl_modules(){ // Template Manager
		if($this->modelCore->tpl){
			if(isset($this->modelCore->tpl['tpl_headers'])){$this->template->set('tpl_headers',$this->modelCore->tpl['tpl_headers']);}
			if(isset($this->modelCore->tpl['tpl_extras'])){$this->template->set('tpl_extras',$this->modelCore->tpl['tpl_extras']);}
			if(isset($this->modelCore->tpl['tpl_left_columns'])){$this->template->set('tpl_left_columns',$this->modelCore->tpl['tpl_left_columns']);}
			if(isset($this->modelCore->tpl['tpl_contents'])){$this->template->set('tpl_contents',$this->modelCore->tpl['tpl_contents']);}
			if(isset($this->modelCore->tpl['tpl_right_columns'])){$this->template->set('tpl_right_columns',$this->modelCore->tpl['tpl_right_columns']);}
			if(isset($this->modelCore->tpl['tpl_footers'])){$this->template->set('tpl_footers',$this->modelCore->tpl['tpl_footers']);}
			if(isset($this->modelCore->tpl['tpl_bottom'])){$this->template->set('tpl_bottom',$this->modelCore->tpl['tpl_bottom']);}
		}
		if(isset($this->tpl_manager['tpl_color']) && $this->tpl_manager['tpl_color']){$this->template->set('template_color',$this->tpl_manager['tpl_color']);}
		$this->template->set('tpl_columns', $this->modelCore->tpl_columns);
	}
}
?>
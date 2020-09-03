<?php 
class ControllerCheckoutFailure extends Controller {	 
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->head_def 	=& $locator->get('HeaderDefinition');
		$this->module   	=& $locator->get('module');
		$this->template 	=& $locator->get('template');
		$this->modelCore 	= $model->get('model_core');
		$this->tpl_manager = $this->modelCore->get_tpl_manager('checkout_failure'); // Template Manager
		$this->locations = $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns = $this->modelCore->get_columns();// Template Manager
	}
	function index() {
		$customer =& $this->locator->get('customer');
		$language =& $this->locator->get('language');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		
    	if (!$customer->isLogged()) {
      		$session->set('redirect', $url->ssl('checkout_failure'));

			$response->redirect($url->ssl('account_login'));
    	}

    	$language->load('controller/checkout_failure.php');

    	$this->template->set('title', $language->get('heading_title')); 
		 
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $language->get('heading_title'));

    	$view->set('text_failure', $language->get(($session->get('checkout_failure') == 'declined' ? 'text_declined':'text_failure'), $url->ssl('contact')));
		$session->delete('checkout_failure');
    	$view->set('button_continue', $language->get('button_continue'));
    	$view->set('continue', $url->ssl('checkout_shipping'));
		$view->set('head_def',$this->head_def);
		$this->template->set('head_def',$this->head_def);
    	$this->template->set('content', $view->fetch('content/failure.tpl'));
    
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
		if($this->tpl_columns == 1.2 || $this->tpl_columns == 3){
			$modules_extra['column'] = array('manufacturer', 'popular');
			$modules_extra['columnright'] = array('specials');
		} elseif ($this->tpl_columns == 2.1) {
			$modules_extra['columnright'] = array('manufacturer', 'popular');
		}
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

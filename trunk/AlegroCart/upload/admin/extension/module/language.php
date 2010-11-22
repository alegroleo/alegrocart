<?php  //Language Module AlegroCart
class ModuleLanguage extends Controller {
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->config   	=& $locator->get('config');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->url      	=& $locator->get('url');
		$this->modelLanguage = $model->get('model_admin_languagemodule');
		
		$this->language->load('extension/module/language.php');
		}
	function fetch() {

		if ($this->config->get('language_status')) {	
    		if (($this->request->isPost()) && ($this->request->has('module_language', 'post'))) {
	  			$this->language->set($this->request->gethtml('module_language', 'post'));
				$this->response->redirect($this->url->requested($this->url->ssl('home')));
    		}
		
			$view = $this->locator->create('template');
		 
    		$view->set('heading_title', $this->language->get('heading_title'));

    		$view->set('action', $this->url->requested($this->url->ssl('home')));

   			$view->set('text_language', $this->language->get('text_language'));

    		$view->set('entry_language', $this->language->get('entry_language'));

    		$view->set('default', $this->language->getCode());

    		$view->set('languages', $this->modelLanguage->get_language_cache());

   			return $view->fetch('module/language.tpl'); 
		}
	}
}
?>
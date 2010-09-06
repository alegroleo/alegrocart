<?php //Search Module AlegroCart
class ModuleSearch extends Controller {
	function fetch() { 	
		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		$request  =& $this->locator->get('request');
		$this->modelCore 	= $this->model->get('model_core');
		require_once('library/application/string_modify.php');   //new
		
		if ($config->get('search_status')) {
			$language->load('extension/module/search.php');
		
			$view = $this->locator->create('template');

    		$view->set('heading_title', $language->get('heading_title'));

    		$view->set('text_advanced', $language->get('text_advanced'));
			$view->set('text_keywords', $language->get('text_keywords'));

    		$view->set('entry_search', $language->get('entry_search'));

    		$view->set('button_search', $language->get('button_search'));

    		$view->set('action', $url->href('search', 'search_page'));

			if ($request->get('controller') == 'search') {
				$view->set('search', $session->get('search.search'));
				$view->set('description', $session->get('search.description'));
			} else {
				$view->set('search', '');
				$view->set('description', "on");
			}
			if($session->has('search.max_rows') && $session->get('search.max_rows')>= 0){
				$view->set('max_rows', (int)$session->get('search.max_rows'));
			} else{
				$view->set('max_rows', $config->get('search_limit'));
			}
			if ($session->get('search.columns')){
				$view->set('columns', $session->get('search.columns'));
			}
			if ($session->get('search.page_rows')){
				$view->set('page_rows', (int)$session->get('search.page_rows'));
			}
			if ($session->get('search.sort_order')){
				$view->set('default_order', $session->get('search.sort_order'));
			}
			if ($session->get('search.sort_filter')){
				$view->set('default_filter', $session->get('search.sort_filter'));
			}

    		$view->set('advanced', $url->href('search'));
			$view->set('location', $this->modelCore->module_location['search']); // Template Manager 
    		return $view->fetch('module/search.tpl');
		}
  	}
}
?>
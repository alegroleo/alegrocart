<?php  // Sitemap AlegroCart
class ControllerSitemap extends Controller {
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->customer 	=& $locator->get('customer');
		$this->module   	=& $locator->get('module');
		$this->template 	=& $locator->get('template');
		$this->modelCore 	= $model->get('model_core');
		$this->modelSitemap = $model->get('model_sitemap');
		$this->tpl_manager = $this->modelCore->get_tpl_manager('sitemap'); // Template Manager
		$this->locations = $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns = $this->modelCore->get_columns();// Template Manager
	}
	function index() {
		$language =& $this->locator->get('language');
		$response =& $this->locator->get('response');
		$url      =& $this->locator->get('url');
		$head_def =& $this->locator->get('HeaderDefinition'); 
		
    	$language->load('controller/sitemap.php');
 
    	$this->template->set('title', $language->get('heading_title'));

    	$view = $this->locator->create('template');

    	$view->set('heading_title', $language->get('heading_title'));

    	$view->set('text_account', $language->get('text_account'));
    	$view->set('text_edit', $language->get('text_edit'));
    	$view->set('text_password', $language->get('text_password'));
    	$view->set('text_address', $language->get('text_address'));
    	$view->set('text_history', $language->get('text_history'));
    	$view->set('text_download', $language->get('text_download'));
    	$view->set('text_cart', $language->get('text_cart'));
    	$view->set('text_checkout', $language->get('text_checkout'));
    	$view->set('text_search', $language->get('text_search'));
    	$view->set('text_information', $language->get('text_information'));
    	$view->set('text_contact', $language->get('text_contact'));

    	$category_data = array();
		$results = $this->modelSitemap->get_categories();
    	foreach ($results as $result) {
      		$category_data[] = array(
        		'category_id' => $result['category_id'],
        		'name'        => $result['name'],
        		'href'        => $url->href('category', FALSE, array('path' => $result['path'])),
        		'level'       => count(explode('_', $result['path'])) - 1
      		);
    	}

    	$view->set('categories', $category_data);
    	
    	$view->set('account', $url->ssl('account'));
    	
		if ($this->customer->isLogged()) {
			$view->set('password', $url->ssl('account_password'));
			$view->set('edit', $url->ssl('account_edit'));
			$view->set('address', $url->ssl('account_address'));
			$view->set('history', $url->ssl('account_history'));
			$view->set('download', $url->ssl('account_download'));
		}
		
    	$view->set('cart', $url->href('cart'));
    	$view->set('checkout', $url->ssl('checkout_shipping'));
    	$view->set('search', $url->href('search'));
    	$view->set('contact', $url->href('contact'));

    	$information_data = array();
		$results = $this->modelSitemap->get_information();
    	foreach ($results as $result) {
      		$information_data[] = array(
        		'title' => $result['title'],
        		'href'  => $url->href('information', FALSE, array('information_id' => $result['information_id']))
      		);
    	}

    	$view->set('informations', $information_data);
		$view->set('head_def',$head_def); 
		$this->template->set('head_def',$head_def); 
		$this->template->set('content', $view->fetch('content/sitemap.tpl'));
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
		$modules_extra['column'] = array('manufacturer', 'popular');
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

<?php  // Header AlegroCart
class ModuleHeader extends Controller {
	function fetch() {
		$config   =& $this->locator->get('config');
		$customer =& $this->locator->get('customer');
		$head_def =& $this->locator->get('HeaderDefinition'); 
		$language =& $this->locator->get('language');
		$url      =& $this->locator->get('url');
		$image    =& $this->locator->get('image');
		$request  =& $this->locator->get('request');
		$session  =& $this->locator->get('session');
		$template =& $this->locator->get('template');
		$this->modelCore = $this->model->get('model_core');
		
		if ($config->get('header_status')) {	
			$language->load('extension/module/header.php');
			
			$view = $this->locator->create('template');
			if ($session->get('maintenance') != 'enabled') {
				if($config->get('config_rss_status')){
					$view->set('rss_image', $image->href('rss.png'));
					$view->set('rss_link', $url->get_server().'rss.php');
				}
				
				$home_data = $this->modelCore->get_homepage();
				$template->set('meta_title', $home_data['meta_title'] ? $home_data['meta_title'] : FALSE);
				$template->set('meta_description', $home_data['meta_description'] ?'<meta name="description" content="' . $home_data['meta_description'] . '">' : FALSE);
				$template->set('meta_keywords', $home_data['meta_keywords'] ? '<meta name="keywords" content="'.$home_data['meta_keywords'] . '">' : FALSE);
			} else {
			$session->set('maintenance', '');
			
			}
			$view->set('store_logo', $image->href('logo/'.$config->get('config_store_logo')));
			$view->set('logo_left', $config->get('config_logo_left'));
			$view->set('logo_top', $config->get('config_logo_top'));
			$view->set('logo_width', $config->get('config_logo_width'));
			$view->set('logo_height', $config->get('config_logo_height'));
			$view->set('store', $config->get('config_store'));
    		return $view->fetch('module/header.tpl');
		}
	}
}
?>
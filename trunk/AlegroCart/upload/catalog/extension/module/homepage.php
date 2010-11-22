<?php  // HomePage AlegroCart
class ModuleHomePage extends Controller {
	function fetch() {
		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');
		$image    =& $this->locator->get('image');
		$url      =& $this->locator->get('url');
		$request  =& $this->locator->get('request');
		$session  =& $this->locator->get('session');
		$template =& $this->locator->get('template'); 
		$head_def =& $this->locator->get('HeaderDefinition');
		$this->modelCore = $this->model->get('model_core');
		require_once('library/application/string_modify.php');
		
        if (!$config->get('homepage_status')) {return;}
		$home_data = $this->modelCore->get_homepage();
		if (!$home_data['status']){return;}
		if($home_data['run_times'] != -1){
			if($home_data['run_times'] > 0){
				If($session->has('homepage')){
					$times = $session->get('homepage');
					If ($times < $home_data['run_times']){
						$run_homepage = TRUE;
						$session->set('homepage', $times+1);
					} else {
						$run_homepage = FALSE;
					}
				} else {
					$session->set('homepage', 1);
					$run_homepage = TRUE;
				}
			} else {
				$run_homepage = TRUE;
			}
			If($run_homepage != TRUE){return;}
		}	

		$language->load('extension/module/homepage.php');
		$view = $this->locator->create('template');

		$view->set('heading_title', $home_data['title']);
		$view->set('name', $home_data['name']);
		if(strlen($home_data['description']) > 3){
			$view->set('description', $home_data['description']);
		}
		if(strlen($home_data['welcome']) > 3){
			$view->set('welcome', $home_data['welcome']);
		}
		if(strlen($home_data['flash']) > 3){
			$view->set('flash', $image->href('flash/' . $home_data['flash']));
			$view->set('flash_width', $home_data['flash_width']>0 ? $home_data['flash_width'] : 550);
			$view->set('flash_height', $home_data['flash_height'] >0? $home_data['flash_height'] : 250);
			$view->set('flash_loop', ($home_data['flash_loop'] ? 'true' : 'false'));
			
		}
		if(strlen($home_data['filename']) > 3){
			$view->set('image', $image->href($home_data['filename']));
		}
		$view->set('close_homepage', $url->href('home'));
		$view->set('skip_intro', $home_data['run_times'] === '0' ? '' : $language->get('text_skipintro'));
		$view->set('head_def',$head_def); 
		$template->set('head_def',$head_def);		
		return $view->fetch('module/homepage.tpl');
	}
}
?>
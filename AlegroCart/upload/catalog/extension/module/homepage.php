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

		if (!$config->get('homepage_status')) {return;} //if disabled in extensions/module
		$home_data = $this->modelCore->get_homepage();
		$slides_data = $this->modelCore->get_homepage_slides($home_data['home_id']);
		if (!$home_data['status']){return;} // if status disabled
		if($home_data['run_times'] != -1){ // -1 disables home page module on settings tab
			if($home_data['run_times'] > 0){ // if limited, as 0 means no limit
				if($session->has('homepage')){ // it was run at least once
					$times = $session->get('homepage'); // get number of runs
					if ($times < $home_data['run_times']){ // if runtimes is less than permitted
						$run_homepage = TRUE;
						$session->set('homepage', $times+1); // increase by one
					} else { // we reached the max
						$run_homepage = FALSE;
					}
				} else { //the first time
					$session->set('homepage', 1);
					$run_homepage = TRUE;
				}
			} else { // no limit
				$run_homepage = TRUE;
			}

			if($run_homepage != TRUE){ // i.e. false
				if ($config->get('config_page_load')) { //if condense enabled we load the css and js files to keep the condensed files unchanged
					$head_def->setcss( $template->style . "/css/homepage.css");
					$head_def->set_javascript("ajax/jquery.js");
					$head_def->set_javascript("ajax/jqueryadd2cart.js");
					if(isset($slides_data)){ // if slider was added as well
						$head_def->setcss($template->style . "/css/slick.css");
						$head_def->setcss($template->style . "/css/slick-theme.css");
						$head_def->set_javascript("slider/slick.min.js");
					}
				}
			return;
			}

		} else { // disabled, as it is set to -1
			return;
		}
		$language->load('extension/module/homepage.php');
		$view = $this->locator->create('template');

		$view->set('heading_title', $home_data['title']);
		$view->set('name', $home_data['name']);
		if ($slides_data) {
			$slides = array();
			foreach ($slides_data as $slide_data){
				$slides[] = array (
				'filename' => $image->href($slide_data['filename'])
				);
			}
			$view->set('slides', $slides);
			$sliderjs = "<script type=\"text/javascript\">
				  \$(document).ready(function(){
					  \$('#homeslider').slick({
					  slidesToScroll: 1,
					  dots: true,
					  autoplay: true,
					  autoplaySpeed: 2400,
					  infinite: true,
					  slidesToShow: 1
					  });
				  });
				  </script>";
			$view->set('sliderjs', $sliderjs);
		}
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
		if(strlen($home_data['filename']) > 3 && $home_data['image_id'] != '0'){
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

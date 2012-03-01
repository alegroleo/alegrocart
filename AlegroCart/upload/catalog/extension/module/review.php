<?php  //ReviewModule AlegroCart
class ModuleReview extends Controller {
	function fetch() {
		$config   =& $this->locator->get('config');
		$head_def =& $this->locator->get('HeaderDefinition');  
		$image    =& $this->locator->get('image');
		$language =& $this->locator->get('language');
		$request  =& $this->locator->get('request');
		$template =& $this->locator->get('template');  	
		$url      =& $this->locator->get('url');
		$this->modelReview = $this->model->get('model_review');
		$this->modelCore 	= $this->model->get('model_core');
		require_once('library/application/string_modify.php');
		
		if ($config->get('review_status')) {
			$controller = $this->modelCore->controller; // Template Manager 
			$location = $this->modelCore->module_location['review']; // Template Manager 
			$review_info = $this->modelReview->get_review();
    		
			if ($review_info) {
				$language->load('extension/module/review.php');
				$view = $this->locator->create('template');
      			$view->set('heading_title', $language->get('heading_title'));
			$average = ($review_info['rating1'] + $review_info['rating2'] + $review_info['rating3'] + $review_info['rating4'])/4;
			$avgrating = number_format($average,0);
 			$avgrating2 = number_format($average,1);
     			$view->set('text_rating', $language->get('text_rating', $avgrating));
     			$view->set('name', $review_info['name']);
      			$view->set('rating', $avgrating);
      			$view->set('avgrating', $avgrating2);
      			$view->set('desciption', strippedstring($review_info['text'], 55));
		
     			$view->set('image', $image->resize($review_info['filename'], $config->get('config_image_width'), $config->get('config_image_height')));

      			$view->set('review', $url->href('review_info', false, array('product_id' =>$review_info['product_id'], 'review_id' => $review_info['review_id'])));

   	   			$view->set('reviews', $url->href('review'));
				$template->set('head_def',$head_def);
	  			$view->set('head_def',$head_def);
				return $view->fetch('module/review.tpl');
			} else {
				return;
			}
		}
	}
}
?>
<?php // Review Information AlegroCart
class ControllerReviewInfo extends Controller { 
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->config  		=& $locator->get('config');
		$this->config->set('config_tax', $this->config->get('config_tax_store'));
		$this->module   	=& $locator->get('module');
		$this->template 	=& $locator->get('template');
		$this->modelCore 	= $model->get('model_core');
		$this->modelReview 	= $model->get('model_review');
		$this->tpl_manager = $this->modelCore->get_tpl_manager('review_info'); // Template Manager
		$this->locations = $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns = $this->modelCore->get_columns();// Template Manager
	}
	function index() {
		$currency =& $this->locator->get('currency');
		$language =& $this->locator->get('language');
		$image    =& $this->locator->get('image');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$tax      =& $this->locator->get('tax');
		$url      =& $this->locator->get('url');
		$head_def =& $this->locator->get('HeaderDefinition');

    	$language->load('controller/review_info.php');
 		
		$review_info = $this->modelReview->getRow_review($request->gethtml('review_id'));
    	
		if ($review_info) {
	  		$this->template->set('title', $language->get('heading_title', $review_info['name']));
 
      		$view = $this->locator->create('template');
      		$view->set('heading_title', $language->get('heading_title', $review_info['name'], $review_info['author']));
			
			$view->set('tax_included', $this->config->get('config_tax'));

      		$view->set('text_enlarge', $language->get('text_enlarge'));
      		$view->set('text_author', $language->get('text_author'));
      		$view->set('text_date_added', $language->get('text_date_added'));
      		$view->set('text_rating', $language->get('text_rating'));
      		$view->set('text_rating1', $language->get('text_rating1'));
      		$view->set('text_rating2', $language->get('text_rating2'));
      		$view->set('text_rating3', $language->get('text_rating3'));
      		$view->set('text_rating4', $language->get('text_rating4'));
      		
      		$view->set('text_out_of1', $language->get('text_out_of', $review_info['rating1']));
      		$view->set('text_out_of2', $language->get('text_out_of', $review_info['rating2']));
      		$view->set('text_out_of3', $language->get('text_out_of', $review_info['rating3']));
      		$view->set('text_out_of4', $language->get('text_out_of', $review_info['rating4']));
      		
      		$view->set('button_reviews', $language->get('button_reviews'));
      		$view->set('button_write', $language->get('button_write'));

      		$view->set('name', $review_info['name']);

			$view->set('href', $url->href('product', FALSE, array('product_id' => $review_info['product_id'])));

      		$view->set('price', $currency->format($tax->calculate($review_info['price'], $review_info['tax_class_id'], $this->config->get('config_tax'))));
			$view->set('special_price', $review_info['special_price']>0 ? $currency->format($tax->calculate($review_info['special_price'], $review_info['tax_class_id'], $this->config->get('config_tax'))):""); 
			
			$view->set('popup', $image->href($review_info['filename']));
      		$view->set('thumb', $image->resize($review_info['filename'], 160,160));
      		
      		$view->set('author', $review_info['author']);
      		$view->set('text', nl2br($review_info['text']));
      		$view->set('rating1', $review_info['rating1']);
      		$view->set('rating2', $review_info['rating2']);
      		$view->set('rating3', $review_info['rating3']);
      		$view->set('rating4', $review_info['rating4']);
      		
      		$view->set('date_added', $language->formatDate($language->get('date_format_long'), strtotime($review_info['date_added'])));
      
	  		$query = array(
	    		'product_id' => $review_info['product_id']
				//'review_id'  => $request->gethtml('review_id')
	  		);
	  
      		$view->set('review', $url->href('review', FALSE, $query));

      		$query = array(
        		'product_id' => $review_info['product_id']
        		//'review_id'  => $request->gethtml('review_id')
      		); 

      		$view->set('write', $url->href('review_write', FALSE, $query));
			$view->set('head_def',$head_def); 
			$this->template->set('head_def',$head_def);
	  		$this->template->set('content', $view->fetch('content/review_info.tpl'));
    	} else {  // Error no Reviews
      		$this->template->set('title', $language->get('text_error'));
      		$view = $this->locator->create('template');
      		$view->set('heading_title', $language->get('text_error'));
      		$view->set('text_error', $language->get('text_error'));
      		$view->set('button_continue', $language->get('button_continue'));
      		$view->set('continue', $url->href('home'));
			$view->set('head_def',$head_def); 
			$this->template->set('head_def',$head_def);
	  		$this->template->set('content', $view->fetch('content/error.tpl'));
    	}
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
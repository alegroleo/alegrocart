<?php  // ImageDisplay AlegroCart
class ModuleImageDisplay extends Controller {
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
		
		if(!$config->get('imagedisplay_status')){return;}
		$location = $this->modelCore->module_location['imagedisplay'];
		
		$results = $this->modelCore->get_image_display($location);
		$language->load('extension/module/imagedisplay.php');
		$view = $this->locator->create('template');
		
		$image_display_data = array();
		foreach($results as $result){
			$imagename = strlen($result['filename']) > 3 && $result['image_id'] != '0' ? $result['filename'] : '';
			if(strlen($imagename) > 6){
				if($result['image_width'] && $result['image_height']){
					$imagename = $image->resize($imagename, $result['image_width'], $result['image_height']);
				} else {
					$imagename = $image->href($imagename);
				}
			}
			$flash = strlen($result['flash']) > 3 ? ($image->href('flash/' . $result['flash'])) : '';
			
			$image_display_data[] = array(
				'flash'			=> $flash,
				'flash_width'	=> $result['flash_width'],
				'flash_height'	=> $result['flash_height'],
				'flash_loop'	=> ($result['flash_loop'] ? 'true' : 'false'),
				'image'			=> $imagename
			);
		}
		
		$view->set('image_displays', $image_display_data);
		$view->set('head_def',$head_def);
		$template->set('head_def',$head_def);
		
		return $view->fetch('module/imagedisplay.tpl');
	}
}
?>

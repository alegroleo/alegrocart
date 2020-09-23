<?php  // ImageDisplay AlegroCart
class ModuleImageDisplay extends Controller {

	public function fetch() {

		$config			=& $this->locator->get('config');
		$language		=& $this->locator->get('language');
		$image			=& $this->locator->get('image');
		$url			=& $this->locator->get('url');
		$request		=& $this->locator->get('request');
		$session		=& $this->locator->get('session');
		$template		=& $this->locator->get('template'); 
		$head_def		=& $this->locator->get('HeaderDefinition');
		$this->modelCore	= $this->model->get('model_core');
		require_once('library/application/string_modify.php');

		if(!$config->get('imagedisplay_status')) {
			return;
		}
		$location = $this->modelCore->module_location['imagedisplay'];

		$results = $this->modelCore->get_image_display($location);

		$language->load('extension/module/imagedisplay.php');
		$view = $this->locator->create('template');

		$image_display_data = array();
		foreach($results as $result){
			$imagename = strlen($result['filename']) > 3 && $result['image_id'] != '0' ? $result['filename'] : '';
			$image_width = '';
			$image_height = '';
			if(strlen($imagename) > 6){
				if($result['image_width'] && $result['image_height']){
					$imagename = $image->resize($imagename, $result['image_width'], $result['image_height']);
					$image_width = $result['image_width'];
					$image_height = $result['image_height'];
				} else {
					$imagename = $image->href($imagename);
					$image_width = $image->getWidth($result['filename']);
					$image_height = $image->getHeight($result['filename']);
				}
			}

			$flash = strlen($result['flash']) > 3 ? ($image->href('flash/' . $result['flash'])) : '';

			$slides_data = $this->modelCore->get_image_display_slides($result['image_display_id']);
			$slides = array();

			if ($slides_data) {
				foreach ($slides_data as $slide_data){
					$slides[] = array (
					'filename' => $image->href($slide_data['filename']),
					'width' => $image->getWidth($slide_data['filename']),
					'height' => $image->getHeight($slide_data['filename'])
					);
				}
				$sliderjs = "<script type=\"text/javascript\">
					  \$(document).ready(function(){
						  \$('#id_slider_" . $result['image_display_id'] . "').slick({
						  slidesToScroll: 1,
						  dots: false,
						  autoplay: true,
						  autoplaySpeed: 2400,
						  infinite: true,
						  slidesToShow: 1
						  });
					  });
					  </script>";
			}
			$image_display_data[] = array(
				'flash'		=> $flash,
				'flash_width'	=> $result['flash_width'],
				'flash_height'	=> $result['flash_height'],
				'flash_loop'	=> ($result['flash_loop'] ? 'true' : 'false'),
				'image'		=> $imagename,
				'image_width'	=> $image_width,
				'image_height'	=> $image_height,
				'slides'	=> $slides ? $slides : '',
				'sliderjs'	=> $slides ? $sliderjs : '',
				'id_id'		=> $result['image_display_id']
			);
		}

		$view->set('extra_image', $language->get('text_extra_image'));
		$view->set('image_displays', $image_display_data);
		$view->set('head_def',$head_def);
		$template->set('head_def',$head_def);

		return $view->fetch('module/imagedisplay.tpl');
	}
}
?>

<?php  // AlegroCart Category Slider
class ModuleCategorySlider extends Controller {

	public function fetch() {
		$config			=& $this->locator->get('config');
		$database		=& $this->locator->get('database');
		$image			=& $this->locator->get('image');
		$language		=& $this->locator->get('language');
		$url			=& $this->locator->get('url');
		$session		=& $this->locator->get('session');
		$request		=& $this->locator->get('request');
		$template		=& $this->locator->get('template');
		$head_def		=& $this->locator->get('HeaderDefinition');
		$this->modelCore	= $this->model->get('model_core');
		$this->modelCategory	= $this->model->get('model_category');

		if ($config->get('categoryslider_status')) {
			$view = $this->locator->create('template');

			$results = $this->modelCategory->get_allcategories();
			$categories_data = array();
			foreach ($results as $result){
				$categories_data[] = array(
					'category_id'	=> $result['category_id'],
					'name'		 => $result['name'],
					'thumb'		 => $image->resize($result['filename'], $config->get('categoryslider_image_width'), $config->get('categoryslider_image_height')),
					'href'		 => $url->ssl('category', FALSE, array('path' => $result['path'])),
					'width'		=> $config->get('categoryslider_image_width'),
					'height'	=> $config->get('categoryslider_image_height')
				);
			}
			$view->set('categories', $categories_data);
			$view->set('head_def',$head_def);
			$view->set('location', $this->modelCore->module_location['categoryslider']); // Template Manager
			$view->set('column_data', $this->modelCore->tpl_columns);
			$template->set('head_def',$head_def);
			return $view->fetch('module/categoryslider.tpl');
		}
	}
}
?>

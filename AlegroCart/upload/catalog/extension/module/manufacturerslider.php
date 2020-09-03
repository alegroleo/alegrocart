<?php  // AlegroCart Manufacturer Slider
class ModuleManufacturerSlider extends Controller {
	function fetch() {
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
		$this->modelManufacturer= $this->model->get('model_manufacturer');

		if ($config->get('manufacturerslider_status')) {
			$view = $this->locator->create('template');

			$results = $this->modelManufacturer->get_manufacturers();
			$manufacturers_data = array();
			foreach ($results as $result){
				$manufacturers_data[] = array(
					'manufacturer_id' => $result['manufacturer_id'],
					'name'		 => $result['name'],
					'thumb'		 => $image->resize($result['filename'], $config->get('manufacturerslider_image_width'), $config->get('manufacturerslider_image_height')),
					'href'		 => $url->ssl('manufacturer', FALSE, array('manufacturer_id' => $result['manufacturer_id']))
				);
			}
			$view->set('manufacturers', $manufacturers_data);
			$view->set('head_def',$head_def);
			$view->set('location', $this->modelCore->module_location['manufacturerslider']); // Template Manager
			$view->set('column_data', $this->modelCore->tpl_columns);
			$template->set('head_def',$head_def);
			return $view->fetch('module/manufacturerslider.tpl');
		}
	}
}
?>

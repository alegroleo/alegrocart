<?php //AlegroCart Watermark
class ControllerWatermark extends Controller {
	var $error = array();
	var $wm_types = array('png');
	var $image_types = array('png','jpg','jpeg');
	var $wm_method = 'manual';

 	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->cache    	=& $locator->get('cache');
		$this->image    	=& $locator->get('image');  
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session 		=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user');
		$this->watermark      	=& $locator->get('watermark');
		$this->validate 	=& $locator->get('validate');
		$this->modelWatermark   = $model->get('model_admin_watermark');
		
		$this->language->load('controller/watermark.php');
	}	
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->validate_update()) {
			$this->modelWatermark->delete_watermark($this->wm_method);
			$this->modelWatermark->update_watermark($this->wm_method);
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('watermark'));
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_none', $this->language->get('text_none'));
				
		$view->set('text_wm_with_text', $this->language->get('text_wm_with_text'));
		$view->set('text_wm_with_image', $this->language->get('text_wm_with_image'));
		$view->set('text_left', $this->language->get('text_left'));
		$view->set('text_center', $this->language->get('text_center'));
		$view->set('text_right', $this->language->get('text_right'));
		$view->set('text_top', $this->language->get('text_top'));
		$view->set('text_bottom', $this->language->get('text_bottom'));
		$view->set('text_watermark', $this->language->get('text_watermark'));
		$view->set('text_original', $this->language->get('text_original'));
		$view->set('text_watermarked', $this->language->get('text_watermarked'));

 		$view->set('entry_wm_text',$this->language->get('entry_wm_text'));
		$view->set('font_sizes', array('1', '2', '3', '4', '5'));
 		$view->set('entry_wm_fontsize',$this->language->get('entry_wm_fontsize'));
 		$view->set('entry_wm_fontcolor',$this->language->get('entry_wm_fontcolor'));
 		$view->set('entry_wm_transparency',$this->language->get('entry_wm_transparency'));
 		$view->set('entry_wm_thposition',$this->language->get('entry_wm_thposition'));
 		$view->set('entry_wm_tvposition',$this->language->get('entry_wm_tvposition'));
 		$view->set('entry_wm_thmargin',$this->language->get('entry_wm_thmargin'));
 		$view->set('entry_wm_tvmargin',$this->language->get('entry_wm_tvmargin'));
		$view->set('entry_wm_image',$this->language->get('entry_wm_image'));
 		$view->set('entry_wm_scale',$this->language->get('entry_wm_scale'));
 		$view->set('entry_wm_ihposition',$this->language->get('entry_wm_ihposition'));
 		$view->set('entry_wm_ivposition',$this->language->get('entry_wm_ivposition'));
 		$view->set('entry_wm_ihmargin',$this->language->get('entry_wm_ihmargin'));
 		$view->set('entry_wm_ivmargin',$this->language->get('entry_wm_ivmargin'));

 		$view->set('explanation_wm_text',$this->language->get('explanation_wm_text'));
 		$view->set('explanation_wm_fontsize',$this->language->get('explanation_wm_fontsize'));
 		$view->set('explanation_wm_fontcolor',$this->language->get('explanation_wm_fontcolor'));
 		$view->set('explanation_wm_transparency',$this->language->get('explanation_wm_transparency'));
 		$view->set('explanation_wm_thposition',$this->language->get('explanation_wm_thposition'));
 		$view->set('explanation_wm_tvposition',$this->language->get('explanation_wm_tvposition'));
 		$view->set('explanation_wm_thmargin',$this->language->get('explanation_wm_thmargin'));
 		$view->set('explanation_wm_tvmargin',$this->language->get('explanation_wm_tvmargin'));
		$view->set('explanation_wm_scale',$this->language->get('explanation_wm_scale'));
 		$view->set('explanation_wm_ihposition',$this->language->get('explanation_wm_ihposition'));
 		$view->set('explanation_wm_ivposition',$this->language->get('explanation_wm_ivposition'));
 		$view->set('explanation_wm_ihmargin',$this->language->get('explanation_wm_ihmargin'));
 		$view->set('explanation_wm_ivmargin',$this->language->get('explanation_wm_ivmargin'));
		$view->set('explanation_wm_image',$this->language->get('explanation_wm_image'));
 		$view->set('explanation_wm_original',$this->language->get('explanation_wm_original'));
 		$view->set('explanation_wm_watermarked',$this->language->get('explanation_wm_watermarked'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('button_preview', $this->language->get('button_preview'));
		$view->set('button_save_wmi', $this->language->get('button_save_wmi'));

		$view->set('tab_watermark', $this->language->get('tab_watermark'));

		$view->set('error', @$this->error['message']);
		$view->set('error_wm_text', @$this->error['wm_text']);
		$view->set('error_wm_fontcolor', @$this->error['wm_fontcolor']);
		$view->set('error_wm_transparency', @$this->error['wm_trancparency']);
		$view->set('error_wm_scale', @$this->error['wm_scale']);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('watermark'));
		$view->set('cancel', $this->url->ssl('watermark'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
		//Watermark
		$watermark_data = $this->modelWatermark->get_watermark_data($this->wm_method);

		if ($this->request->has('wm_text', 'post')) {
			$view->set('wm_text', $this->request->gethtml('wm_text', 'post'));
		} else {
			$view->set('wm_text', @$watermark_data['wm_text']);
		}
		if ($this->request->has('wm_font', 'post')) {
			$view->set('wm_font', $this->request->gethtml('wm_font', 'post'));
		} else {
			$view->set('wm_font', @$watermark_data['wm_font']);
		}	
		if ($this->request->has('wm_fontcolor', 'post')) {
			$view->set('wm_fontcolor', $this->request->gethtml('wm_fontcolor', 'post'));
		} else {
			$view->set('wm_fontcolor', @$watermark_data['wm_fontcolor']);
		}
		if ($this->request->has('wm_transparency', 'post')) {
			$view->set('wm_transparency', $this->request->gethtml('wm_transparency', 'post'));
		} else {
			$view->set('wm_transparency', @$watermark_data['wm_transparency']);
		}
		if ($this->request->has('wm_thposition', 'post')) {
			$view->set('wm_thposition', $this->request->gethtml('wm_thposition', 'post'));
		} else {
			$view->set('wm_thposition', @$watermark_data['wm_thposition']);
		}
		if ($this->request->has('wm_tvposition', 'post')) {
			$view->set('wm_tvposition', $this->request->gethtml('wm_tvposition', 'post'));
		} else {
			$view->set('wm_tvposition', @$watermark_data['wm_tvposition']);
		}
		if ($this->request->has('wm_thmargin', 'post')) {
			$view->set('wm_thmargin', $this->request->gethtml('wm_thmargin', 'post'));
		} else {
			$view->set('wm_thmargin', @$watermark_data['wm_thmargin']);
		}
		if ($this->request->has('wm_tvmargin', 'post')) {
			$view->set('wm_tvmargin', $this->request->gethtml('wm_tvmargin', 'post'));
		} else {
			$view->set('wm_tvmargin', @$watermark_data['wm_tvmargin']);
		}
		$view->set('images', $this->getImages());
		if ($this->request->has('wm_image', 'post')) {
			$view->set('wm_image', $this->request->gethtml('wm_image', 'post'));
		} else {
			$view->set('wm_image', @$watermark_data['wm_image']);
		}
		if ($this->request->has('wm_scale', 'post')) {
			$view->set('wm_scale', $this->request->gethtml('wm_scale', 'post'));
		} else {
			$view->set('wm_scale', @$watermark_data['wm_scale']);
		}
		if ($this->request->has('wm_ihposition', 'post')) {
			$view->set('wm_ihposition', $this->request->gethtml('wm_ihposition', 'post'));
		} else {
			$view->set('wm_ihposition', @$watermark_data['wm_ihposition']);
		}
		if ($this->request->has('wm_ivposition', 'post')) {
			$view->set('wm_ivposition', $this->request->gethtml('wm_ivposition', 'post'));
		} else {
			$view->set('wm_ivposition', @$watermark_data['wm_ivposition']);
		}
		if ($this->request->has('wm_ihmargin', 'post')) {
			$view->set('wm_ihmargin', $this->request->gethtml('wm_ihmargin', 'post'));
		} else {
			$view->set('wm_ihmargin', @$watermark_data['wm_ihmargin']);
		}
		if ($this->request->has('wm_ivmargin', 'post')) {
			$view->set('wm_ivmargin', $this->request->gethtml('wm_ivmargin', 'post'));
		} else {
			$view->set('wm_ivmargin', @$watermark_data['wm_ivmargin']);
		}

		$view->set('wmimages', $this->getWmImages());
	
		//End of Watermark
		
		$this->template->set('content', $view->fetch('content/watermark.tpl'));
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	
	function getImages(){
		$images_data = array();
		$files = glob(DIR_IMAGE.'watermark'.D_S.'*.*');
		if (!$files) { return; }
		foreach ($files as $file) {
		$pattern='#^([^tmp\.])(.*)(\.)('.implode('|',$this->wm_types).')$#';
			$filename = basename($file);
			    if (preg_match($pattern,$filename)) {
				$images_data[] = array(
					'image'	=> $filename
				);
			}
		}

		return $images_data;
	}

	function getWmImages(){
		$wm_images_data = array();
		$files = $this->modelWatermark->get_originals();

		if (!$files) { return; }
		foreach ($files as $file) {
		    foreach ($file as $filename) {
			$pattern='/\.('.implode('|',$this->image_types).')$/';
			if (preg_match($pattern,$filename)) {
				$wm_images_data[] = array(
					'image'	=> $filename
				);
			}
		}
		}
		return $wm_images_data;
	}
	
	function viewImage(){
		if($this->request->gethtml('wm_image')){
			$output = '<img src="' . HTTP_IMAGE . '/watermark/' . $this->request->gethtml('wm_image') . '"';
			$output .= 'alt="' . $this->language->get('text_watermark'). '" title="'. $this->language->get('text_watermark') .'">';
		} else {
			$output = '';
		}
		$this->response->set($output);
	}

	function previewImage(){
		$this->watermark->deleteTmp(DIR_WATERMARK);
		$this->watermark->deleteTmp(DIR_IMAGE_CACHE);
		if($this->request->gethtml('preview')){
			$output = '<img src="' . HTTP_IMAGE . $this->watermark->resize_preview($this->watermark->preview(HTTP_IMAGE . $this->request->gethtml('preview'), $this->wm_method),300,300) . '"';
			$output .= 'alt="' . $this->language->get('text_watermarked'). '" title="'. $this->language->get('text_watermarked') .'">';
		} else {
			$output = '';
		}
		$this->response->set($output);
	}

	function viewWmImage(){
		if($this->request->gethtml('wm_wmimage')){
			$image = $this->request->gethtml('wm_wmimage');
			$output = '<img src="' . HTTP_IMAGE . $this->watermark->resize($image,300,300) .  '?' . time() . '"';
			$output .= 'alt="' . $this->language->get('text_original'). '" title="'. $this->language->get('text_original') .'">';
		} else {
			$output = '';
		}
		$this->response->set($output);
	}

	function previewSave(){
	      if ($this->user->hasPermission('modify', 'watermark')) {
		$this->watermark->deleteTmp(DIR_IMAGE_CACHE);
		$this->watermark->copyTmp(DIR_IMAGE . $this->request->gethtml('save'));
		sleep(1);
		$this->watermark->deleteTmp(DIR_WATERMARK);
		$this->watermark->deleteCache($this->request->gethtml('save'));
	      }
	}

	function validate_update() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'watermark')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
       
	if ($this->request->gethtml('wm_text', 'post') !='') {
		if (!$this->validate->strlen($this->request->gethtml('wm_text', 'post'),0,64)) {
			$this->error['wm_text'] = $this->language->get('error_wm_text');
		}
	}
	if ($this->request->gethtml('wm_fontcolor', 'post') !='') {
		if (!$this->validate->strlen($this->request->gethtml('wm_fontcolor', 'post'),6,6) || !$this->validate->is_hexcolor($this->request->gethtml('wm_fontcolor', 'post'))) {
			$this->error['wm_fontcolor'] = $this->language->get('error_wm_fontcolor');
		}
	}
	if ($this->request->gethtml('wm_transparency', 'post') !='') {
		if ($this->request->gethtml('wm_transparency', 'post') < 0 || $this->request->gethtml('wm_transparency', 'post') > 100) {
			$this->error['wm_trancparency'] = $this->language->get('error_wm_transparency');
		}
	}
	if ($this->request->gethtml('wm_scale', 'post') !='') {
		if ($this->request->gethtml('wm_scale', 'post') < 0 || $this->request->gethtml('wm_scale', 'post') > 100) {
			$this->error['wm_scale'] = $this->language->get('error_wm_scale');
		}
	}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
			
}
?>
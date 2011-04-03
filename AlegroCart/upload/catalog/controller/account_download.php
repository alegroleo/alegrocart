<?php // Account Download AlegroCart
class ControllerAccountDownload extends Controller {
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->config  		=& $locator->get('config');
		$this->customer 	=& $locator->get('customer');
		$this->download 	=& $locator->get('download');
		$this->head_def 	=& $locator->get('HeaderDefinition');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->response 	=& $locator->get('response');
		$this->request  	=& $locator->get('request');
		$this->session  	=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->modelCore 	= $model->get('model_core');
		$this->modelAccountDownload = $model->get('model_accountdownload');
		$this->tpl_manager 	= $this->modelCore->get_tpl_manager('account_download'); // Template Manager
		$this->locations 	= $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns 	= $this->modelCore->get_columns();// Template Manager
	}

	function index() {
		if (!$this->customer->isLogged()) {
			$this->session->set('redirect', $this->url->ssl('account_download'));
			$this->response->redirect($this->url->ssl('account_login'));
		}

        $this->session->set('account_download.page', $this->request->has('page')?(int)$request->get('page'):1);
		$this->language->load('controller/account_download.php');
		$this->template->set('title', $this->language->get('heading_title'));
		$view = $this->locator->create('template');
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('head_def',$this->head_def); 
		$this->template->set('head_def',$this->head_def);
		
		$results = $this->modelAccountDownload->get_downloads($this->customer->getId());		
		if ($results) {
			$view->set('text_order', $this->language->get('text_order'));
			$view->set('text_date_added', $this->language->get('text_date_added'));
			$view->set('text_name', $this->language->get('text_name'));
			$view->set('text_remaining', $this->language->get('text_remaining'));
			$view->set('text_size', $this->language->get('text_size'));
			$view->set('text_download', $this->language->get('text_download'));
			$view->set('text_results', $this->modelAccountDownload->get_text_results());
			$view->set('entry_page', $this->language->get('entry_page'));
			$view->set('total_pages', $this->modelAccountDownload->get_pages());
			$view->set('first_page', $this->language->get('first_page'));
			$view->set('last_page', $this->language->get('last_page'));
			$view->set('previous' , $this->language->get('previous_page'));
			$view->set('next' , $this->language->get('next_page'));
			$view->set('button_continue', $this->language->get('button_continue'));
			
			$view->set('action', $this->url->href('account_download'));

			$download_data = array();
			foreach ($results as $result) {
				$size = filesize(DIR_DOWNLOAD . $result['filename']);
				$i = 0;
				$suffix = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}
				$download_data[] = array(
					'order_id'   => $result['order_id'],
					'date_added' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'name'       => $result['name'],
					'remaining'  => $result['remaining'],
					'size'       => round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i],
					'href'       => $this->url->ssl('account_download', 'download', array('order_download_id' => $result['order_download_id']))
				);
			}

			$view->set('downloads', $download_data);
			$view->set('page', $this->session->get('account_download.page'));
			$view->set('pages', $this->modelAccountDownload->get_pagination());
			$view->set('continue', $this->url->ssl('account'));
			$this->template->set('content', $view->fetch('content/account_download.tpl'));
		} else {
			$view->set('text_error', $this->language->get('text_error'));
			$view->set('button_continue', $this->language->get('button_continue'));
			$view->set('continue', $this->url->ssl('account'));
			$this->template->set('content', $view->fetch('content/error.tpl'));
		}
		
		$this->load_modules();  // Template Manager
		$this->set_tpl_modules(); // Template Manager
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
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
	
	function download() {
		if (!$this->customer->isLogged()) {
			$this->session->set('redirect', $this->url->ssl('account_download'));

			$this->response->redirect($this->url->ssl('account_login'));
		}
		$download_info = $this->modelAccountDownload->get_download($this->customer->getId(),$this->request->gethtml('order_download_id'));	
		if ($download_info) {
			$this->download->setSource(DIR_DOWNLOAD . $download_info['filename']);
			$this->download->setFilename($download_info['mask']);
			$this->download->output();
			if (!$this->download->getError()) {
				$this->modelAccountDownload->update_download($this->request->gethtml('order_download_id'));
			}
		} else {
			$this->response->redirect($this->url->ssl('account_download'));
		}
	}
}
?>
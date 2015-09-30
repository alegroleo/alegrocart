<?php // Featured AlegroCart
class ControllerModuleExtraFeatured extends Controller {
	var $error = array();  	// All References change to module_extra_ due to new module loader  
	function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->cache		=& $locator->get('cache');
		$this->config		=& $locator->get('config');
		$this->currency		=& $locator->get('currency');
		$this->language		=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request		=& $locator->get('request');
		$this->response		=& $locator->get('response');
		$this->session		=& $locator->get('session');
		$this->template		=& $locator->get('template');
		$this->url		=& $locator->get('url');
		$this->user		=& $locator->get('user'); 
		$this->modelFeatured	= $model->get('model_admin_featured');

		$this->language->load('controller/module_extra_featured.php');
	}

	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->isPost()) && ($this->validate())) {
			$this->modelFeatured->delete_featured();
			$this->modelFeatured->update_featured();

			$this->session->set('message', $this->language->get('text_message'));
			
			$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_module', $this->language->get('heading_module'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));
		
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_limit', $this->language->get('entry_limit'));
		$view->set('entry_height', $this->language->get('entry_height'));
		$view->set('entry_width', $this->language->get('entry_width'));
		$view->set('entry_addtocart', $this->language->get('entry_addtocart'));
		$view->set('entry_columns', $this->language->get('entry_columns'));
		$view->set('entry_image_display',$this->language->get('entry_image_display'));
		$view->set('entry_ratings',$this->language->get('entry_ratings'));
		$view->set('entry_lines_single',$this->language->get('entry_lines_single'));
		$view->set('entry_lines_multi',$this->language->get('entry_lines_multi'));
		$view->set('entry_lines_char',$this->language->get('entry_lines_char'));
		$view->set('entry_columnb', $this->language->get('entry_columnb'));
		$view->set('entry_sliderb', $this->language->get('entry_sliderb'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('explanation_entry_status', $this->language->get('explanation_entry_status'));
		$view->set('explanation_entry_limit', $this->language->get('explanation_entry_limit'));
		$view->set('explanation_entry_slimit', $this->language->get('explanation_entry_slimit'));
		$view->set('explanation_entry_height', $this->language->get('explanation_entry_height'));
		$view->set('explanation_entry_width', $this->language->get('explanation_entry_width'));
		$view->set('explanation_entry_image_display', $this->language->get('explanation_entry_image_display'));
		$view->set('explanation_entry_ratings', $this->language->get('explanation_entry_ratings'));
		$view->set('explanation_entry_addtocart', $this->language->get('explanation_entry_addtocart'));
		$view->set('explanation_entry_columns', $this->language->get('explanation_entry_columns'));
		$view->set('explanation_entry_lines_single', $this->language->get('explanation_entry_lines_single'));
		$view->set('explanation_entry_lines_multi', $this->language->get('explanation_entry_lines_multi'));
		$view->set('explanation_entry_char', $this->language->get('explanation_entry_char'));
		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);

		$view->set('action', $this->url->ssl('module_extra_featured'));
		$view->set('list', $this->url->ssl('extension', FALSE, array('type' => 'module')));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'module')));
		$view->set('column_data', array(1,2,3,4,5));
		$view->set('scolumn_data', array(2,3,4,5));
		$view->set('image_displays',array('no_image', 'image_link', 'thickbox', 'fancybox', 'lightbox'));
		
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (!$this->request->isPost()) {
			$results = $this->modelFeatured->get_featured();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}

		if ($this->request->has('catalog_featured_status', 'post')) {
			$view->set('catalog_featured_status', $this->request->gethtml('catalog_featured_status', 'post'));
		} else {
			$view->set('catalog_featured_status', @$setting_info['catalog']['featured_status']);
		}
		if ($this->request->has('catalog_featured_limit', 'post')) {
			$view->set('catalog_featured_limit', $this->request->gethtml('catalog_featured_limit', 'post'));
		} else {
			$view->set('catalog_featured_limit', @$setting_info['catalog']['featured_limit']);
		}
		if ($this->request->has('catalog_featured_image_width', 'post')) {
			$view->set('catalog_featured_image_width', $this->request->gethtml('catalog_featured_image_width', 'post'));
		} else {
			$view->set('catalog_featured_image_width', @$setting_info['catalog']['featured_image_width']);
		}
		if ($this->request->has('catalog_featured_image_height', 'post')) {
			$view->set('catalog_featured_image_height', $this->request->gethtml('catalog_featured_image_height', 'post'));
		} else {
			$view->set('catalog_featured_image_height', @$setting_info['catalog']['featured_image_height']);
		}
		if ($this->request->has('catalog_featured_addtocart', 'post')) {
			$view->set('catalog_featured_addtocart', $this->request->gethtml('catalog_featured_addtocart', 'post'));
		} else {
			$view->set('catalog_featured_addtocart', @$setting_info['catalog']['featured_addtocart']);
		}
		if ($this->request->has('catalog_featured_columns', 'post')) {
			$view->set('catalog_featured_columns', $this->request->gethtml('catalog_featured_columns', 'post'));
		} else {
			$view->set('catalog_featured_columns', @$setting_info['catalog']['featured_columns']);
		}
		if ($this->request->has('catalog_featured_image_display', 'post')) {
			$view->set('catalog_featured_image_display', $this->request->gethtml('catalog_featured_image_display', 'post'));
		} else {
			$view->set('catalog_featured_image_display', @$setting_info['catalog']['featured_image_display']);
		}
		if ($this->request->has('catalog_featured_ratings', 'post')) {
			$view->set('catalog_featured_ratings', $this->request->gethtml('catalog_featured_ratings', 'post'));
		} else {
			$view->set('catalog_featured_ratings', @$setting_info['catalog']['featured_ratings']);
		}
		if ($this->request->has('catalog_featured_lines_single', 'post')) {
			$view->set('catalog_featured_lines_single', $this->request->gethtml('catalog_featured_lines_single', 'post'));
		} else {
			$view->set('catalog_featured_lines_single', @$setting_info['catalog']['featured_lines_single']);
		}
		if ($this->request->has('catalog_featured_lines_multi', 'post')) {
			$view->set('catalog_featured_lines_multi', $this->request->gethtml('catalog_featured_lines_multi', 'post'));
		} else {
			$view->set('catalog_featured_lines_multi', @$setting_info['catalog']['featured_lines_multi']);
		}
		if ($this->request->has('catalog_featured_lines_char', 'post')) {
			$view->set('catalog_featured_lines_char', $this->request->gethtml('catalog_featured_lines_char', 'post'));
		} else {
			$view->set('catalog_featured_lines_char', @$setting_info['catalog']['featured_lines_char']);
		}
		if ($this->request->has('catalog_featured_slider', 'post')) {
			$view->set('catalog_featured_slider', $this->request->gethtml('catalog_featured_slider', 'post'));
		} else {
			$view->set('catalog_featured_slider', @$setting_info['catalog']['featured_slider']);
		}
		if ($this->request->has('catalog_featured_slimit', 'post')) {
			$view->set('catalog_featured_slimit', $this->request->gethtml('catalog_featured_slimit', 'post'));
		} else {
			$view->set('catalog_featured_slimit', @$setting_info['catalog']['featured_slimit']);
		}
		if ($this->request->has('catalog_featured_scolumns', 'post')) {
			$view->set('catalog_featured_scolumns', $this->request->gethtml('catalog_featured_scolumns', 'post'));
		} else {
			$view->set('catalog_featured_scolumns', @$setting_info['catalog']['featured_scolumns']);
		}

		$this->template->set('content', $view->fetch('content/module_extra_featured.tpl'));
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'module_extra_featured')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function install() {
		if ($this->user->hasPermission('modify', 'module_extra_featured')) {
			$this->modelFeatured->delete_featured();
			$this->modelFeatured->install_featured();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));	
	}

	function uninstall() {
		if ($this->user->hasPermission('modify', 'module_extra_featured')) {
			$this->modelFeatured->delete_featured();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
	}
}
?>

<?php // CategoryList AlegroCart
class ControllerModuleExtraCategoryList extends Controller {
	var $error = array();   // All References change to module_extra_ due to new module loader 
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
		$this->modelCategoryList	= $model->get('model_admin_categorylist');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController = $this->template->set_controller('module_extra_categorylist');

		$this->language->load('controller/module_extra_categorylist.php');
	}	
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->isPost()) && ($this->validate())) {
			$this->modelCategoryList->delete_categorylist();
			$this->modelCategoryList->update_categorylist();
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('module_extra_categorylist'));
			} else {
				$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
			}
		}

		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
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
		$view->set('entry_total', $this->language->get('entry_total'));
		$view->set('entry_columns', $this->language->get('entry_columns'));
		$view->set('entry_image_display',$this->language->get('entry_image_display'));
		$view->set('entry_ratings',$this->language->get('entry_ratings'));
		$view->set('entry_lines_multi',$this->language->get('entry_lines_multi'));
		$view->set('entry_lines_char',$this->language->get('entry_lines_char'));
		$view->set('entry_columnb', $this->language->get('entry_columnb'));
		$view->set('entry_sliderb', $this->language->get('entry_sliderb'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));

		$view->set('help', $this->session->get('help'));

		$view->set('explanation_entry_status', $this->language->get('explanation_entry_status'));
		$view->set('explanation_entry_limit', $this->language->get('explanation_entry_limit'));
		$view->set('explanation_entry_slimit', $this->language->get('explanation_entry_slimit'));
		$view->set('explanation_entry_height', $this->language->get('explanation_entry_height'));
		$view->set('explanation_entry_width', $this->language->get('explanation_entry_width'));
		$view->set('explanation_entry_total', $this->language->get('explanation_entry_total'));
		$view->set('explanation_entry_image_display', $this->language->get('explanation_entry_image_display'));
		$view->set('explanation_entry_ratings', $this->language->get('explanation_entry_ratings'));
		$view->set('explanation_entry_addtocart', $this->language->get('explanation_entry_addtocart'));
		$view->set('explanation_entry_columns', $this->language->get('explanation_entry_columns'));
		$view->set('explanation_entry_lines_multi', $this->language->get('explanation_entry_lines_multi'));
		$view->set('explanation_entry_char', $this->language->get('explanation_entry_char'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);

		$view->set('action', $this->url->ssl('module_extra_categorylist'));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'module')));

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$this->session->set('name_last_module', $this->language->get('heading_title'));
		$this->session->set('last_module', 'module_extra_categorylist');
		$this->session->set('last_extension_id', $this->modelCategoryList->get_extension_id('module_extra_categorylist'));

		$view->set('column_data', array(3,4,5));
		$view->set('scolumn_data', array(3,4,5));
		$view->set('image_displays',array('no_image', 'image_link', 'thickbox', 'fancybox', 'lightbox'));

		if (!$this->request->isPost()) {
			$results = $this->modelCategoryList->get_categorylist();
			
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}

		if ($this->request->has('catalog_categorylist_status', 'post')) {
			$view->set('catalog_categorylist_status', $this->request->gethtml('catalog_categorylist_status', 'post'));
		} else {
			$view->set('catalog_categorylist_status', @$setting_info['catalog']['categorylist_status']);
		}
		if ($this->request->has('catalog_categorylist_ratings', 'post')) {
			$view->set('catalog_categorylist_ratings', $this->request->gethtml('catalog_categorylist_ratings', 'post'));
		} else {
			$view->set('catalog_categorylist_ratings', @$setting_info['catalog']['categorylist_ratings']);
		}
		if ($this->request->has('catalog_categorylist_limit', 'post')) {
			$view->set('catalog_categorylist_limit', $this->request->gethtml('catalog_categorylist_limit', 'post'));
		} else {
			$view->set('catalog_categorylist_limit', @$setting_info['catalog']['categorylist_limit']);
		}
		if ($this->request->has('catalog_categorylist_total', 'post')) {
			$view->set('catalog_categorylist_total', $this->request->gethtml('catalog_categorylist_total', 'post'));
		} else {
			$view->set('catalog_categorylist_total', @$setting_info['catalog']['categorylist_total']);
		}
		if ($this->request->has('catalog_categorylist_image_width', 'post')) {
			$view->set('catalog_categorylist_image_width', $this->request->gethtml('catalog_categorylist_image_width', 'post'));
		} else {
			$view->set('catalog_categorylist_image_width', @$setting_info['catalog']['categorylist_image_width']);
		}
		if ($this->request->has('catalog_categorylist_image_height', 'post')) {
			$view->set('catalog_categorylist_image_height', $this->request->gethtml('catalog_categorylist_image_height', 'post'));
		} else {
			$view->set('catalog_categorylist_image_height', @$setting_info['catalog']['categorylist_image_height']);
		}
		if ($this->request->has('catalog_categorylist_addtocart', 'post')) {
			$view->set('catalog_categorylist_addtocart', $this->request->gethtml('catalog_categorylist_addtocart', 'post'));
		} else {
			$view->set('catalog_categorylist_addtocart', @$setting_info['catalog']['categorylist_addtocart']);
		}
		if ($this->request->has('catalog_categorylist_columns', 'post')) {
			$view->set('catalog_categorylist_columns', $this->request->gethtml('catalog_categorylist_columns', 'post'));
		} else {
			$view->set('catalog_categorylist_columns', @$setting_info['catalog']['categorylist_columns']);
		}
		if ($this->request->has('catalog_categorylist_image_display', 'post')) {
			$view->set('catalog_categorylist_image_display', $this->request->gethtml('catalog_categorylist_image_display', 'post'));
		} else {
			$view->set('catalog_categorylist_image_display', @$setting_info['catalog']['categorylist_image_display']);
		}
		if ($this->request->has('catalog_categorylist_lines_multi', 'post')) {
			$view->set('catalog_categorylist_lines_multi', $this->request->gethtml('catalog_categorylist_lines_multi', 'post'));
		} else {
			$view->set('catalog_categorylist_lines_multi', @$setting_info['catalog']['categorylist_lines_multi']);
		}
		if ($this->request->has('catalog_categorylist_lines_char', 'post')) {
			$view->set('catalog_categorylist_lines_char', $this->request->gethtml('catalog_categorylist_lines_char', 'post'));
		} else {
			$view->set('catalog_categorylist_lines_char', @$setting_info['catalog']['categorylist_lines_char']);
		}
		if ($this->request->has('catalog_categorylist_slider', 'post')) {
			$view->set('catalog_categorylist_slider', $this->request->gethtml('catalog_categorylist_slider', 'post'));
		} else {
			$view->set('catalog_categorylist_slider', @$setting_info['catalog']['categorylist_slider']);
		}
		if ($this->request->has('catalog_categorylist_slimit', 'post')) {
			$view->set('catalog_categorylist_slimit', $this->request->gethtml('catalog_categorylist_slimit', 'post'));
		} else {
			$view->set('catalog_categorylist_slimit', @$setting_info['catalog']['categorylist_slimit']);
		}
		if ($this->request->has('catalog_categorylist_scolumns', 'post')) {
			$view->set('catalog_categorylist_scolumns', $this->request->gethtml('catalog_categorylist_scolumns', 'post'));
		} else {
			$view->set('catalog_categorylist_scolumns', @$setting_info['catalog']['categorylist_scolumns']);
		}
		$this->template->set('content', $view->fetch('content/module_extra_categorylist.tpl'));
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'module_extra_categorylist')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}
	function install() {
		if ($this->user->hasPermission('modify', 'module_extra_categorylist')) {
			$this->modelCategoryList->delete_categorylist();
			$this->modelCategoryList->install_categorylist();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}
		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
	}
	function uninstall() {
		if ($this->user->hasPermission('modify', 'module_extra_categorylist')) {
			$this->modelCategoryList->delete_categorylist();
			if ($this->session->get('last_module') == 'module_extra_categorylist') {
				$this->session->delete('name_last_module');
				$this->session->delete('last_module');
			}
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}
		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'module')));
	}
}
?>

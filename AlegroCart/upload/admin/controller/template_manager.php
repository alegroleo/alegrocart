<?php // TemplateManager AlegroCart
class ControllerTemplateManager extends Controller {

	public $error = array();
	private $types = array('css');

	public function __construct(&$locator){
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
		$this->validate		=& $locator->get('validate');
		$this->modelTplManager	= $model->get('model_admin_tpl_manager');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('template_manager');

		$this->language->load('controller/template_manager.php');
		$this->language->load('controller/layout_locations.php');
	}

	protected function index(){
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function insert(){
		$this->template->set('title', $this->language->get('heading_title'));
		if (($this->request->isPost()) && ($this->validateForm())) {
			if($this->modelTplManager->check_controller()){
				$this->session->set('message', $this->language->get('error_already_exists'));
				$this->response->redirect($this->url->ssl('template_manager'));
			}
			$this->modelTplManager->insert_controller();
			$tpl_id = $this->modelTplManager->get_last_id();
			$locations = $this->modelTplManager->get_locations();
			foreach ($locations as $location){
				foreach($this->request->gethtml($location['location'],'post', array()) as $module_data){
					$this->modelTplManager->insert_module($module_data, $tpl_id);
				}
			}
			$name_last = $this->request->get('text_tpl_controller', 'post');
			if (strlen($name_last) > 26) {
				$name_last = substr($name_last , 0, 23) . '...';
			}
			$this->session->set('name_last_template_manager', $name_last);
			$this->session->set('last_template_manager', $this->url->ssl('template_manager', 'update', array('tpl_manager_id' => $tpl_id)));
			$this->session->set('last_template_manager_id', $tpl_id);

			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('template_manager'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());

		$this->session->delete('name_last_template_manager');
		$this->session->delete('last_template_manager');

		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function update(){
		$this->template->set('title', $this->language->get('heading_title'));
		if (($this->request->isPost()) && ($this->validateForm())) {
			$this->modelTplManager->update_controller();
			$this->modelTplManager->delete_modules();
			$locations = $this->modelTplManager->get_locations();
			foreach ($locations as $location){
				foreach($this->request->gethtml($location['location'],'post', array()) as $module_data){
					$this->modelTplManager->insert_module($module_data, (int)$this->request->gethtml('tpl_manager_id'));
				}
			}
			$this->session->set('message', $this->language->get('text_message'));
			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('template_manager', 'update', array('tpl_manager_id' => $this->request->gethtml('tpl_manager_id', 'post'))));
			} else {
				$this->response->redirect($this->url->ssl('template_manager'));
			}
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function delete(){
		$this->template->set('title', $this->language->get('heading_title'));
		if (($this->request->gethtml('tpl_manager_id')) && ($this->validateDelete())) {
			$this->modelTplManager->delete_controller();
			$this->modelTplManager->delete_modules();
			$this->session->set('message', $this->language->get('text_message'));
			$this->session->delete('name_last_template_manager');
			$this->session->delete('last_template_manager');
			$this->response->redirect($this->url->ssl('template_manager'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function changeStatus() { 
		
		if (($this->request->has('stat_id')) && ($this->request->has('stat')) && $this->validateChangeStatus()) {
			$this->modelTplManager->change_template_status($this->request->gethtml('stat'), $this->request->gethtml('stat_id'));
		}
	}

	private function getList(){
		$this->session->set('tpl_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'	=> $this->language->get('column_controller'),
			'sort'  => 'tpl_controller',
			'align' => 'left'
		);
		$cols[] = array(
			'name'	=> $this->language->get('column_tpl_columns'),
			'sort'  => 'tpl_columns',
			'align' => 'left'
		);
		$cols[] = array(
			'name'	=> $this->language->get('column_tpl_color'),
			'sort'  => 'tpl_color',
			'align' => 'left'
		);
		$cols[] = array(
			'name'	=> $this->language->get('column_tpl_status'),
			'sort'  => 'tpl_status',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_action'),
			'align' => 'action'
		);

		$results = $this->modelTplManager->get_page();

		$rows = array();
		foreach ($results as $result) {
			$cell = array();
			$last = $result['tpl_manager_id'] == $this->session->get('last_template_manager_id') ? 'last_visited': '';
			$cell[] = array(
				'value' => $this->language->get('text_controller_' . $result['tpl_controller']) . ' (' . $result['tpl_controller'] . ')',
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
			'value' => $result['tpl_columns'],
			'align' => 'left',
			'last' => $last
			);
			$cell[] = array(
				'value' => $result['tpl_color'] ? $result['tpl_color'] : 'Default',
				'align' => 'left',
				'last' => $last
			);
		if ($this->validateChangeStatus()) {
			$cell[] = array(
				'status'  => $result['tpl_status'],
				'text' => $this->language->get('button_status'),
				'align' => 'center',
				'status_id' => $result['tpl_manager_id'],
				'status_controller' => 'template_manager'
			);
		} else {
			$cell[] = array(
				'icon'  => ($result['tpl_status'] ? 'enabled.png' : 'disabled.png'),
				'align' => 'center'
			);
		}
			$action = array();
			$action[] = array(
				'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('template_manager', 'update', array('tpl_manager_id' => $result['tpl_manager_id']))
			);
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('template_manager', 'delete', array('tpl_manager_id' => $result['tpl_manager_id'],'tpl_validation' =>$this->session->get('tpl_validation')))
				);
			}
			
			$cell[] = array(
				'action' => $action,
				'align'  => 'action'
			);
			$rows[] = array('cell' => $cell);
		}
		
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));
		$view->set('text_results', $this->modelTplManager->get_text_results());
		$view->set('text_asc', $this->language->get('text_asc'));
		$view->set('text_desc', $this->language->get('text_desc'));

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_status', $this->language->get('button_status'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));
		$view->set('controller', 'template_manager');
		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');

		$view->set('action', $this->url->ssl('template_manager', 'page'));
		$view->set('action_delete', $this->url->ssl('template_manager', 'enableDelete'));
		$view->set('last', $this->url->getLast('template_manager'));

		$view->set('search', $this->session->get('tpl.search'));
		$view->set('sort', $this->session->get('tpl.sort'));
		$view->set('order', $this->session->get('tpl.order'));
		$view->set('page', $this->session->get('tpl.page'));
		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('insert', $this->url->ssl('template_manager', 'insert'));

		$view->set('pages', $this->modelTplManager->get_pagination());
		return $view->fetch('content/list.tpl');
	}

	private function getForm(){
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_form_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));
		$view->set('text_tpl_manager', $this->language->get('text_tpl_manager'));
		$view->set('text_tpl_content', $this->language->get('text_tpl_content'));
		$view->set('text_tpl_header', $this->language->get('text_tpl_header'));
		$view->set('text_tpl_extra', $this->language->get('text_tpl_extra'));
		$view->set('text_tpl_column', $this->language->get('text_tpl_column'));
		$view->set('text_tpl_columnright', $this->language->get('text_tpl_columnright'));
		$view->set('text_tpl_footer', $this->language->get('text_tpl_footer'));
		$view->set('text_tpl_pagebottom', $this->language->get('text_tpl_pagebottom'));
		$view->set('text_select_controller', $this->language->get('text_select_controller'));

		$view->set('entry_controller', $this->language->get('entry_controller'));
		$view->set('entry_columns', $this->language->get('entry_columns'));
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_color', $this->language->get('entry_color'));
		$view->set('entry_location', $this->language->get('entry_location'));
		$view->set('entry_module', $this->language->get('entry_module'));
		$view->set('entry_sortorder', $this->language->get('entry_sortorder'));

		$view->set('tab_controller', $this->language->get('tab_controller'));
		$view->set('tab_header', $this->language->get('tab_header'));
		$view->set('tab_extra', $this->language->get('tab_extra'));
		$view->set('tab_left_column', $this->language->get('tab_left_column'));
		$view->set('tab_content', $this->language->get('tab_content'));
		$view->set('tab_right_column', $this->language->get('tab_right_column'));
		$view->set('tab_footer', $this->language->get('tab_footer'));
		$view->set('tab_page_bottom', $this->language->get('tab_page_bottom'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_add', $this->language->get('button_add'));
		$view->set('button_remove', $this->language->get('button_remove'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));
		$view->set('error', @$this->error['message']);

		$view->set('action', $this->url->ssl('template_manager', $this->request->gethtml('action'), array('tpl_manager_id' => (int)$this->request->gethtml('tpl_manager_id'))));
		$view->set('insert', $this->url->ssl('template_manager', 'insert'));
		$view->set('cancel', $this->url->ssl('template_manager'));
		$view->set('last', $this->url->getLast('template_manager'));

		if ($this->request->gethtml('tpl_manager_id')) {
			$view->set('update', $this->url->ssl('template_manager', 'update', array('tpl_manager_id' => (int)$this->request->gethtml('tpl_manager_id'))));
			$view->set('delete', $this->url->ssl('template_manager', 'delete', array('tpl_manager_id' => (int)$this->request->gethtml('tpl_manager_id'),'tpl_validation' =>$this->session->get('tpl_validation'))));
		}

		$view->set('tab', $this->session->has('tpl_manager_tab') && $this->session->get('tpl_manager_id') == $this->request->gethtml('tpl_manager_id') ? $this->session->get('tpl_manager_tab') : 0);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('tpl_manager_id', $this->request->gethtml('tpl_manager_id'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$results = $this->modelTplManager->get_locations();

		$module_data = array();
		foreach ($results as $result){
			$locations_data = array();
			$view->set($result['location'] .'_id', $result['location_id']);
			if(($this->request->gethtml('tpl_manager_id')) && (!$this->request->isPost())){
				$locations_data = $this->modelTplManager->getModules($this->request->gethtml('tpl_manager_id'), $result['location_id']);
			} else if($this->request->has($result['location'],'post') && $this->request->isPost()){
				$locations_data = $this->request->gethtml($result['location'], 'post',array());
			}
			$module_data = array();
			if (@$locations_data){
				foreach($locations_data as $location_info){
					$module_data[] = array(
						'location_id'   => $location_info['location_id'],
						'module_code'	=> $location_info['module_code'],
						'sort_order'	=> $location_info['sort_order']
					);
				}
			}
			$view->set($result['location'], $module_data);
		}

		if(($this->request->gethtml('tpl_manager_id')) && (!$this->request->isPost())){
			$template_info = $this->modelTplManager->getRow_template_info($this->request->gethtml('tpl_manager_id'));
		}

		if ($this->request->has('text_tpl_controller', 'post')) {
			if ($this->request->gethtml('text_tpl_controller', 'post') != NULL) {
				$name_last = $this->request->get('text_tpl_controller', 'post');
			} else {
				$name_last = $this->session->get('name_last_template_manager');
			}
		} else {
			$controller = @$template_info['tpl_controller'];
			$name_last = $controller ? $this->language->get('text_controller_' . $controller) . ' (' . $controller . ')' : '';
		}
		if (strlen($name_last) > 26) {
			$name_last = substr($name_last , 0, 23) . '...';
		}
		$this->session->set('name_last_template_manager', $name_last);
		$this->session->set('last_template_manager', $this->url->ssl('template_manager', 'update', array('tpl_manager_id' => $this->request->gethtml('tpl_manager_id'))));
		$this->session->set('last_template_manager_id', $this->request->gethtml('tpl_manager_id'));

		if ($this->request->has('tpl_controller', 'post')){
			$controller = $this->request->get('tpl_controller', 'post');
			$view->set('tpl_controller', $controller);
			$view->set('text_tpl_controller', $controller ? $this->language->get('text_controller_' . $controller) . ' (' . $controller . ')' : '');
		} else {
			$controller = @$template_info['tpl_controller'];
			$view->set('tpl_controller', $controller);
			$view->set('text_tpl_controller', $controller ? $this->language->get('text_controller_' . $controller) . ' (' . $controller . ')' : '');
		}
		if (!$controller){
			$view->set('controllers', $this->getControllers());
		}

		if ($this->request->has('tpl_columns', 'post')){
			$view->set('tpl_columns', $this->request->get('tpl_columns', 'post'));
			$column = $this->request->get('tpl_columns', 'post') > 0 ? $this->request->get('tpl_columns', 'post') : 'default';
		} else if(@$template_info['tpl_columns']){
			$view->set('tpl_columns', @$template_info['tpl_columns']);
			$column = @$template_info['tpl_columns'];
		} else {
			$column = 'default';
			$view->set('tpl_columns', $column);
		}
		$view->set('columns', array('default','1','1.2','2.1','3'));
		$view->set('default_columns',$this->modelTplManager->get_config('config_columns'));
		if ($this->request->has('tpl_status', 'post')){
			$view->set('tpl_status', $this->request->get('tpl_status', 'post'));
		} else {
			$view->set('tpl_status', @$template_info['tpl_status']);
		}

		if ($this->request->has('tpl_color', 'post')){
			$view->set('tpl_color', $this->request->get('tpl_color', 'post'));
		} else if(@$template_info['tpl_color']){
			$view->set('tpl_color', @$template_info['tpl_color']);
		} else {
			$view->set('tpl_color', 'default');
		}
		$view->set('catalog_colors', $this->checkFiles($this->modelTplManager->get_config('config_styles'),($column != 'default' ? $column : $this->modelTplManager->get_config('config_columns'))));

		$locations = $this->modelTplManager->get_locations();
		foreach ($locations as $location){
			$view->set($location['location'].'_modules',$this->getModules($location['location'], $column, @$controller ));
		}

		return $view->fetch('content/template_manager.tpl');
	}
	

	private function getModules($location, $columns = 3, $controller = ''){
		switch($location){
			case 'header';
				$modules = array('cart', 'categorymenu', 'converter', 'currency', 'header', 'imagedisplay', 'language', 'navigation', 'search');
				break;	
			case 'extra';
				$modules = array('categorymenu', 'categoryslider', 'currency', 'homepage', 'imagedisplay', 'information', 'language', 'manufacturerslider', 'navigation', 'search');
				break;
			case 'column'; //left
				$modules = array('bestseller', 'cart', 'category', 'converter', 'currency', 'featured', 'imagedisplay', 'information', 'language', 'latest', 'manufacturer', 'navigation','popular', 'recently', 'review', 'search', 'specials', 'toprated');
				if ($columns ==1.2){
					if ($controller == 'search'){$modules[] = 'searchoptions';}
					if ($controller == 'category'){$modules[] = 'categoryoptions';}
				}
				break;
			case 'content';
				$modules = array('content', 'bestseller', 'categorylist', 'categoryslider', 'featured', 'homepage', 'imagedisplay', 'latest', 'manufacturerlist', 'manufacturerslider', 'popular', 'recently', 'specials', 'toprated');
				if ($controller == 'product'){ 
					$modules[] = 'related';
					$modules[] = 'alsobought';
				}
				break;
			case 'columnright'; //right
				$modules = array('bestseller', 'cart', 'converter', 'currency', 'featured', 'imagedisplay', 'information', 'language', 'latest', 'manufacturer', 'navigation', 'popular', 'review', 'recently', 'search', 'specials', 'toprated');
				if ($controller == 'product'){ $modules[] = 'related'; $modules[] = 'alsobought';}
				if ($controller == 'search'){$modules[] = 'searchoptions';}
				if ($controller == 'category'){$modules[] = 'categoryoptions';}
				if ($controller == 'manufacturer'){$modules[] = 'manufactureroptions';}
				break;
			case 'footer';
				$modules = array('currency', 'footer', 'imagedisplay', 'information', 'language');
				break;
			case 'pagebottom';
				$modules = array('categoryslider', 'currency', 'developer', 'homepage', 'imagedisplay', 'information', 'language', 'manufacturerslider', 'navigation');
				break;
			default:
				$modules = "";
		}
		return $modules;
	}

	private function getControllers(){
		$controllers = $this->modelTplManager->get_controllers();
		$tpl_controllers = array();
		if($controllers){
			foreach($controllers as $controller){
				$tpl_controllers[] = $controller['tpl_controller'];
			}
		} else {
			$tpl_controllers[] = 'empty';
		}

		$controller_data = array();
		$ext = 'php';
		$files = glob(DIR_CATALOG.PATH_CONTROLLER.D_S.'*.*');
		if (!$files) { return; }
		if(!in_array('default',$tpl_controllers)){
			$controller_data[] = array(
				'controller'   => 'default',
				'text_controller' => $this->language->get('text_controller_default')
			);
		}
		$exclusions = array('index', 'addtocart', 'maintenance', 'tools');
		foreach ($files as $file) {
			if (strstr($file,$ext)) {
				$filename = str_replace('.php', '', basename($file));
				if(!in_array($filename,$tpl_controllers)){
					if(!in_array($filename, $exclusions)){
						$controller_data[] = array(
							'controller'   => $filename,
							'text_controller' => $this->language->get('text_controller_' . $filename) . ' (' . $filename . ')'
						);
					}
				}
			}
		}
		return $controller_data;
	}

	private function checkFiles($style, $columns) {
		$colors_data = array();
		if (preg_match('/[1-2]\.[1-2]/',$columns)) {
			$columns = 2;
		}
		$files = glob(DIR_CATALOG_STYLES.$style.D_S.'colors'.$columns.D_S.'*.*');
		if (!$files) { return; }
		$colors_data[] = array('colorcss' => 'default');
		foreach ($files as $file) {
			$pattern='/\.('.implode('|',$this->types).')$/';
			if (preg_match($pattern,$file)) {
				$filename = basename($file);
				$colors_data[] = array(
					'colorcss'   => $filename
				);
			}
		}
		return $colors_data;
	}

	private function validateForm(){
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'template_manager')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if (!$this->validate->strlen($this->request->gethtml('tpl_controller', 'post'),1,64) && !!$this->validate->strlen((int)$this->request->gethtml('tpl_manager_id'))){
			$this->error['message'] = $this->language->get('error_controller');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	protected function enableDelete(){
		$this->template->set('title', $this->language->get('heading_title'));
		if($this->validateEnableDelete()){
			if($this->session->get('enable_delete')){
				$this->session->delete('enable_delete');
			} else {
				$this->session->set('enable_delete', TRUE);
			}
			$this->response->redirect($this->url->ssl('template_manager'));//**
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('template_manager'));//**
		}
	}

	private function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'template_manager')) {
			$this->error['message'] = $this->language->get('error_permission');  
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete(){
		if(($this->session->get('tpl_validation') != $this->request->sanitize('tpl_validation')) || (strlen($this->session->get('tpl_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('tpl_validation');
		if (!$this->user->hasPermission('modify', 'template_manager')) {
			$this->error['message'] = $this->language->get('error_permission');  
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	protected function getColors(){
		$style = $this->modelTplManager->get_config('config_styles');
		$columns = $this->request->gethtml('columns');
		if (preg_match('/[1-2]\.[1-2]/',$columns)) {
			$columns = 2;
		}
		$results = $this->checkFiles($style,$columns);
		if($results){
			$output = '<select name="tpl_color">';
			foreach($results as $result){
				$output .= '<option value="'. $result['colorcss'].'">';
				$output .= $result['colorcss']. '</option>';
			}
			$output .= '</select>';
		} else {
			$output = '<select name="tpl_color"></select>';
		}
		$this->response->set($output);
	}

	protected function module(){
		$view = $this->locator->create('template');
		
		$view->set('entry_location', $this->language->get('entry_location'));
		$view->set('entry_module', $this->language->get('entry_module'));
		$view->set('entry_sortorder', $this->language->get('entry_sortorder'));
		$view->set('button_add', $this->language->get('button_add'));
		$view->set('button_remove', $this->language->get('button_remove'));
		
		$location = $this->modelTplManager->get_location($this->request->gethtml('location_id'));
		$controller = $this->request->gethtml('tpl_controller');
		$column = $this->request->gethtml('tpl_column');
		$view->set('location_id', $this->request->gethtml('location_id'));
		$view->set('location',$location);
		$view->set($location .'_modules', $this->getModules($location, $column, $controller));
		$view->set('module_id', $this->request->gethtml('module_id'));
		$this->response->set($view->fetch('content/template_module.tpl'));
	}

	private function validateChangeStatus(){
		if (!$this->user->hasPermission('modify', 'template_manager')) {
			return FALSE;
		}  else {
			return TRUE;
		}
	}

	protected function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}

	protected function page(){
		if ($this->request->has('search', 'post')) {
			$this->session->set('tpl.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('tpl.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('tpl.order', (($this->session->get('tpl.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('tpl.order') == 'asc') ? 'desc' : 'asc'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('tpl.sort', $this->request->gethtml('sort', 'post'));
		}
		$this->response->redirect($this->url->ssl('template_manager'));	
	}

	protected function tab() {
		if ($this->request->isPost()) {
			if ($this->request->has('activeTab', 'post')) {
				$this->session->set('tpl_manager_tab', $this->request->sanitize('activeTab', 'post'));
				$this->session->set('tpl_manager_id', $this->request->sanitize('id', 'post'));
			}
		}
	}
}
?>

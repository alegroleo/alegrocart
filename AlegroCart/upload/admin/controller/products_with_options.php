<?php //Admin ProductsWithOptions
class ControllerProductsWithOptions extends Controller {

	public $error = array();
	private $option_values = array();

	function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->config		=& $locator->get('config');
		$this->image		=& $locator->get('image');   
		$this->language		=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request		=& $locator->get('request');
		$this->response		=& $locator->get('response');
		$this->session		=& $locator->get('session');
		$this->template		=& $locator->get('template');
		$this->url		=& $locator->get('url');
		$this->user		=& $locator->get('user'); 
		$this->validate		=& $locator->get('validate');
		$this->modelProductOptions = $model->get('model_admin_productswithoptions');
		$this->barcode		=& $locator->get('barcode'); 
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('products_with_options');

		$this->language->load('controller/products_with_options.php');
	}

	protected function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		if($this->session->get('productwo_id')){
			$this->template->set('content', $this->getList());
		} else {
			$this->template->set('content', $this->getProduct());
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->isPost()) && ($this->validateForm())) {
			$this->modelProductOptions->update_option();

			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('products_with_options', 'update', array('product_option' => $this->request->gethtml('product_option', 'post'))));
			} else {
				$this->response->redirect($this->url->ssl('products_with_options'));
			}
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function getProduct(){
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		if($this->request->isPost()){
			$this->session->set('productwoptions.page', (INT)'1');
			$this->session->set('productwoptions.search', '');
			$this->session->set('productwo_id', (int)$this->request->gethtml('productwo_id', 'post'));
			$this->response->redirect($this->url->ssl('products_with_options'));
		}

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));
		$view->set('entry_select_product', $this->language->get('entry_select_product'));
		$view->set('text_select', $this->language->get('text_select'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));
		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('productwo_id', $this->session->get('productwo_id'));
		$view->set('action', '');
		$view->set('action_product', $this->url->ssl('products_with_options', 'getProduct'));
		$view->set('last', $this->url->getLast('products_with_options'));
		$view->set('products' , $this->modelProductOptions->get_products());

		return $view->fetch('content/products_with_options.tpl');
	}

	private function getList(){
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_product_option'),
			'sort'  => 'po.product_option',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_name') . $this->get_option_names(),
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_model_number'),
			 'sort'	=> 'po.model_number',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_stock'),
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_image'),
			'align' => 'right'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_action'),
			'align' => 'action'
		);

		$results = $this->modelProductOptions->get_page();

		$this->get_option_value_names();
		$rows = array();
		foreach ($results as $result) {
			$last = $result['product_option'] == $this->session->get('last_product_option') ? 'last_visited': '';
			$cell = array();
			$cell[] = array(
				'value' => $result['product_option'],
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['name'] . ':' . $this->option_value_names($result['product_option']),
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['model_number'],
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['quantity'],
				'align' => 'center',
				'last' => $last
			);
			$cell[] = array(
				'image' => $result['filename']?$this->image->resize($result['filename'], '26', '26'):$this->image->resize('no_image.png', '26', '26'),
				'previewimage' => $result['filename']?$this->image->resize($result['filename'], $this->config->get('config_image_width'), $this->config->get('config_image_height')):$this->image->resize('no_image.png', $this->config->get('config_image_width'), $this->config->get('config_image_height')),
				'title' => $result['filename']?$result['filename']:$this->language->get('text_no_image'),
				'align' => 'right'
			);
			 $action = array();
			 $action[] =array(
				'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('products_with_options', 'update', array('product_option' => $result['product_option']))
			);
			
			$cell[] = array(
				'action' => $action,
				'align'  => 'action'
			);
			$rows[] = array('cell' => $cell);
		}
		
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_form_title').'<em>'. (isset($result['name']) ? $result['name']  : '') .'</em>');

		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_results', $this->modelProductOptions->get_text_results());
		$view->set('text_asc', $this->language->get('text_asc'));
		$view->set('text_desc', $this->language->get('text_desc'));

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_refresh', $this->language->get('button_refresh'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));
		$view->set('controller', 'products_with_options');
		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');

		$view->set('action', $this->url->ssl('products_with_options', 'page'));
		$view->set('action_refresh', $this->url->ssl('products_with_options', 'getProduct', array('productwo_id' => '0')));
		$view->set('last', $this->url->getLast('products_with_options'));

		$view->set('search', $this->session->get('productwoptions.search'));
		$view->set('sort', $this->session->get('productwoptions.sort'));
		$view->set('order', $this->session->get('productwoptions.order'));
		$view->set('page', $this->session->get('productwoptions.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('pages', $this->modelProductOptions->get_pagination());
		return $view->fetch('content/list.tpl');
	}

	private function getForm() {

		if(!$this->session->get('productwo_id')){
			$this->response->redirect($this->url->ssl('products_with_options'));
		}


		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_form_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_no_image', $this->language->get('text_no_image'));

		$view->set('entry_quantity', $this->language->get('entry_quantity'));
		$view->set('entry_model_number', $this->language->get('entry_model_number'));
		$view->set('entry_dimension_class', $this->language->get('entry_dimension_class'));
		$view->set('entry_image', $this->language->get('entry_image'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));
		$view->set('entry_barcode', $this->language->get('entry_barcode'));
		$view->set('entry_barcode_encoding', $this->language->get('entry_barcode_encoding'));
		$view->set('text_ean', $this->language->get('text_ean'));
		$view->set('text_upc', $this->language->get('text_upc'));

		$view->set('error', @$this->error['message']);

		$view->set('action', $this->url->ssl('products_with_options', $this->request->gethtml('action'), array('product_option' => $this->request->gethtml('product_option'))));
		$view->set('last', $this->url->getLast('products_with_options'));
		$view->set('cancel', $this->url->ssl('products_with_options'));

		if($this->request->gethtml('product_option')){
			$view->set('update', $this->url->ssl('products_with_options', 'update', array('product_option' => $this->request->gethtml('product_option'))));
		}

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$view->set('productwo_id', $this->session->get('productwo_id'));

		$this->session->set('last_product_option', $this->request->gethtml('product_option'));

		$this->get_option_value_names();

		if(($this->request->gethtml('product_option')) && (!$this->request->isPost())){
			$product_info = $this->modelProductOptions->get_option();
		}

		if($this->request->has('product_option', 'post')){
			$view->set('product_option', $this->request->gethtml('product_option', 'post'));
		} else {
			$view->set('product_option', @$product_info['product_option']);
		}

		if($this->request->has('product_id', 'post')){
			$view->set('product_id', $this->request->gethtml('product_id', 'post'));
			$product_name = $this->modelProductOptions->get_product_name($this->request->gethtml('product_id', 'post')) . ':' . $this->option_value_names($this->request->gethtml('product_option', 'post'));
			$view->set('product_name', $product_name);
			$name_last = $product_name;
		} else {
			$view->set('product_id', @$product_info['product_id']);
			$product_name = $this->modelProductOptions->get_product_name(@$product_info['product_id']) . ' : ' . $this->option_value_names(@$product_info['product_option']);
			$view->set('product_name', $product_name);
			$name_last = $product_name;
		}

		if (strlen($name_last) > 26) {
			$name_last = substr($name_last , 0, 23) . '...';
		}
		$this->session->set('name_last_products_with_options', $name_last);
		$this->session->set('last_products_with_options', $this->url->ssl('products_with_options', 'update', array('product_option' => $this->request->gethtml('product_option'))));

		if($this->request->has('quantity', 'post')){
			$view->set('quantity', $this->request->gethtml('quantity', 'post'));
		} else {
			$view->set('quantity', @$product_info['quantity']);
		}
		

		if ($this->request->has('barcode', 'post')) {
			$view->set('barcode', $this->request->gethtml('barcode', 'post'));
		} else {
			$view->set('barcode', @$product_info['barcode']);
		}

		if ($this->request->has('encoding', 'post')) {
			$view->set('encoding', $this->request->gethtml('encoding', 'post'));
		} elseif (($product_info['barcode']) != '') {
			if ($this->barcode->get_length($product_info['barcode'])==13){
				$view->set('encoding', 'ean');
			} else {
				$view->set('encoding', 'upc');
			}
		} else {
		$view->set('encoding', $this->config->get('config_barcode_encoding'));
		}

		if ($this->request->has('image_id', 'post')) {
			$view->set('image_id', $this->request->gethtml('image_id', 'post'));
		} else {
			$view->set('image_id', @$product_info['image_id']);
		}

		if ($this->request->has('model_number', 'post')) {
			$view->set('model_number', $this->request->gethtml('model_number', 'post'));
		} else {
			$view->set('model_number', @$product_info['model_number']);
		}

		if ($this->request->has('dimension_value', 'post')) {
			$dimension_value = implode(':', $this->request->gethtml('dimension_value', 'post'));
		} else {
			$dimension_value = @$product_info['dimension_value'];
		}

		if ($this->request->has('dimension_id', 'post')) {
			$dimension_id = $this->request->gethtml('dimension_id', 'post');
		} elseif (isset($product_info['dimension_id'])) {
			$dimension_id = @$product_info['dimension_id'];
		} else {
			$dimension_id =  $this->config->get('config_dimension_' . $this->config->get('config_dimension_type_id') . '_id');
		}
		$view->set('dimension_id', $dimension_id);
		$dimension_info = $this->modelProductOptions->get_dimension_class($dimension_id);
		if ($this->request->has('type_id', 'post')) {
			$view->set('type_id', $this->request->gethtml('type_id', 'post'));
		} elseif (isset($dimension_info['type_id'])) {
			$view->set('type_id', @$dimension_info['type_id']);
		} else {
			$view->set('type_id', $this->config->get('config_dimension_type_id'));
		}
		$results = $this->modelProductOptions->get_types();
		foreach ($results as $result) {
			$type_data[] = array(
				'type_id'   => $result['type_id'],
				'type_text' => $this->language->get('text_'. $result['type_name'])
			);
		}
		$view->set('types', $type_data);
		$view->set('dimensions', $this->getDimensions($dimension_info['type_id'] ? $dimension_info['type_id'] : $this->config->get('config_dimension_type_id'), $dimension_id, $dimension_value));

		$image_data = array();
		$results = $this->modelProductOptions->get_images();
		foreach ($results as $result) {
		$image_data[] = array(
			'image_id'   => $result['image_id'],
			'previewimage' => $this->image->resize($result['filename'], $this->config->get('config_image_width'), $this->config->get('config_image_height')),
			'title'      => $result['title'],
		);
		}
		$view->set('images', $image_data);
		$no_image_data=$this->modelProductOptions->get_no_image();
		$view->set('no_image_id', $no_image_data['image_id']);
		$view->set('no_image_filename', $this->image->resize($no_image_data['filename'], $this->config->get('config_image_width'), $this->config->get('config_image_height')));

		$rvalidation = '';
		$rvalidation .= '<script type="text/javascript"><!--'. "\n";
		$rvalidation .= '$(document).ready(function() { RegisterValidation(); });'. "\n";
		$rvalidation .= '//--></script>'. "\n";
		$view->set('rvalidation', $rvalidation);

		return $view->fetch('content/products_with_options.tpl');
	}

	private function option_value_names($product_option){
		$options = explode('.', substr($product_option,strpos($product_option, ':' ) + 1));
		$product_options = '';
		foreach($options as $key => $option){
			$product_options .= $this->option_values[$option]['name'];
			$product_options .= (count($options) == $key + 1) ? '' : ' - ';
		}
		return $product_options;
	}

	private function get_option_value_names(){
		$results = $this->modelProductOptions->get_option_values();
		foreach($results as $result){
			$this->option_values[$result['product_to_option_id']] = array(
				'option_value_id'	=> $result['option_value_id'],
				'name'			=> $result['name']
			);
		}
	}

	private function get_options(){
		$results = $this->modelProductOptions->get_options($this->session->get('productwo_id'));
		$options = array();
		foreach($results as $result){
			$options[] = array(
				'option_id'	=> $result['option_id']
			);
		}
		$this->option_status = $options ? TRUE : FALSE;
		return $options;
	}

	private function get_option_names(){
		$results = $this->get_options();
		$option_names = '';
		foreach($results as $key => $result){
			$name = $this->modelProductOptions->get_option_name($result['option_id']);
			$option_names .= $name['name'];
			if($key +1 != count($results)){
				$option_names .= ' - ';
			}
		}
		return $option_names;
	}

	protected function dimensions(){
		$this->response->set($this->getDimensions((int)$this->request->gethtml('type_id')));
	}

	private function getDimensions($type_id, $dimension_id = 0, $dimension_value = 0){
		$output = '';
		$dimension_data = array();
		$results = $this->modelProductOptions->get_dimension_classes($type_id);
		if ($results){
			$output .= '<tr><td style="width: 185px;" class="set">' . $this->language->get('entry_dimension') . '</td>';
			$output .= '<td><select id="dimension_id" name="dimension_id">' . "\n";
			foreach ($results as $result) {
				$output .= '<option value="' . $result['dimension_id'] . '"';
				if ($dimension_id == $result['dimension_id']){
					$output .= ' selected';
				}
				$output .= '>' . $result['title'] . ' (' . $result['unit'] . ')</option>' . "\n";
			}
			$output .= '</select></td>' . "\n";
			$output .= $this->dimension_value($type_id, $dimension_value) . "\n";
		} else {
			$type_info = $this->modelProductOptions->get_type($type_id);
			$output = '<tr><td>' . $this->language->get('text_no_dimensions', $this->language->get('text_'. $type_info['type_name'])) . "</td></tr>\n";
		}
		return $output;
	}

	private function dimension_value($type_id, $dimension_value){
		$output = '';
		$dimensions = explode(':', $dimension_value);
		if ($type_id > 1){
			$dimension_info = $this->modelProductOptions->get_dimension_classes(1);
		}
		$default_dimension = $this->config->get('config_dimension_1_id');
		switch($type_id){
			case '1':
				$output .= '<tr><td style="width: 185px;" class="set">' . $this->language->get('entry_length') . '</td>' . "\n";
				$output .= '<td><input class="validate_float" id="dimension_value0" size ="6" name="dimension_value[0]" value="' . (array_key_exists(0, $dimensions) ? @$dimensions[0] : 0) . '" onfocus="RegisterValidation()"></td></tr>' . "\n";
				$output .= '<tr><td>' . $this->language->get('entry_width') . '</td>' . "\n";
				$output .= '<td><input class="validate_float" id="dimension_value1" size ="6" name="dimension_value[1]" value="' . (array_key_exists(1, $dimensions) ? @$dimensions[1] : 0) . '" onfocus="RegisterValidation()"></td></tr>' . "\n";
				$output .= '<tr><td>' . $this->language->get('entry_height') . '</td>' . "\n";
				$output .= '<td><input class="validate_float" id="dimension_value2" size ="6" name="dimension_value[2]" value="' . (array_key_exists(2, $dimensions) ? @$dimensions[2] : 0) . '" onfocus="RegisterValidation()"></td></tr>' . "\n";
				break;
			case '2':
				$output .= '<tr><td style="width: 185px;" class="set">' . $this->language->get('entry_area') . '</td>' . "\n";
				$output .= '<td><input class="validate_float" id="dimension_value0" size ="6" name="dimension_value[0]" value="' . (array_key_exists(0, $dimensions) ? @$dimensions[0] : 0) . '" onfocus="RegisterValidation()"></td>' . "\n";
				$output .= '<td class="expl" style="font-weight:normal">' . $this->language->get('text_dimension_ship') . '</td></tr>' . "\n";
				$output .= '<td style="width: 185px;" class="set">' . $this->language->get('entry_length') . '</td>' . "\n";
				$output .= '<td><input class="validate_float" id="dimension_value1" name="dimension_value[1]" value="' . (array_key_exists(1, $dimensions) ? @$dimensions[1] : 0) . '" onfocus="RegisterValidation()"></td>' . "\n";
				$output .= '<td><select id="dimension_value2" name="dimension_value[2]">' . "\n";
				$output .= $this->dimension_select($dimension_info, (array_key_exists(2, $dimensions) ? @$dimensions[2] : $default_dimension));
				$output .= '</select></td></tr>' . "\n";
				$output .= '<td style="width: 185px;" class="set">' . $this->language->get('entry_width') . '</td>' . "\n";
				$output .= '<td><input class="validate_float" id="dimension_value3" name="dimension_value[3]" value="' . (array_key_exists(3, $dimensions) ? @$dimensions[3] : 0) . '" onfocus="RegisterValidation()"></td>' . "\n";
				$output .= '<td><select id="dimension_value4" name="dimension_value[4]">' . "\n";
				$output .= $this->dimension_select($dimension_info, (array_key_exists(4, $dimensions) ? @$dimensions[4] : $default_dimension));
				$output .= '</select></td></tr>' . "\n";
				$output .= '<td style="width: 185px;" class="set">' . $this->language->get('entry_height') . '</td>' . "\n";
				$output .= '<td><input class="validate_float" id="dimension_value5" name="dimension_value[5]" value="' . (array_key_exists(5, $dimensions) ? @$dimensions[5] : 0) . '" onfocus="RegisterValidation()"></td>' . "\n";
				$output .= '<td><select id="dimension_value6" name="dimension_value[6]">' . "\n";
				$output .= $this->dimension_select($dimension_info, (array_key_exists(6, $dimensions) ? @$dimensions[6] : $default_dimension));
				$output .= '</select></td></tr>' . "\n";
				break;
			case '3':
				$output .= '<tr><td style="width: 185px;" class="set">' . $this->language->get('entry_volume') . '</td>' . "\n";
				$output .= '<td><input class="validate_float" id="dimension_value0" size ="6" name="dimension_value[0]" value="' . (array_key_exists(0, $dimensions) ? @$dimensions[0] : 0) . '" onfocus="RegisterValidation()"></td>' . "\n";
				$output .= '<td class="expl" style="font-weight:normal">' . $this->language->get('text_dimension_ship') . '</td></tr>' . "\n";
				$output .= '<td style="width: 185px;" class="set">' . $this->language->get('entry_length') . '</td>' . "\n";
				$output .= '<td><input class="validate_float" id="dimension_value1" name="dimension_value[1]" value="' . (array_key_exists(1, $dimensions) ? @$dimensions[1] : 0) . '" onfocus="RegisterValidation()"></td>' . "\n";
				$output .= '<td><select id="dimension_value2" name="dimension_value[2]">' . "\n";
				$output .= $this->dimension_select($dimension_info, (array_key_exists(2, $dimensions) ? @$dimensions[2] : $default_dimension));
				$output .= '</select></td></tr>' . "\n";
				$output .= '<td style="width: 185px;" class="set">' . $this->language->get('entry_width') . '</td>' . "\n";
				$output .= '<td><input class="validate_float" id="dimension_value3" name="dimension_value[3]" value="' . (array_key_exists(3, $dimensions) ? @$dimensions[3] : 0) . '" onfocus="RegisterValidation()"></td>' . "\n";
				$output .= '<td><select id="dimension_value4" name="dimension_value[4]">' . "\n";
				$output .= $this->dimension_select($dimension_info, (array_key_exists(4, $dimensions) ? @$dimensions[4] : $default_dimension));
				$output .= '</select></td></tr>' . "\n";
				$output .= '<td style="width: 185px;" class="set">' . $this->language->get('entry_height') . '</td>' . "\n";
				$output .= '<td><input class="validate_float" id="dimension_value5" name="dimension_value[5]" value="' . (array_key_exists(5, $dimensions) ? @$dimensions[5] : 0) . '" onfocus="RegisterValidation()"></td>' . "\n";
				$output .= '<td><select id="dimension_value6" name="dimension_value[6]">' . "\n";
				$output .= $this->dimension_select($dimension_info, (array_key_exists(6, $dimensions) ? @$dimensions[6] : $default_dimension));
				$output .= '</select></td></tr>' . "\n";
				break;
		}
		return $output;
	}

	private function dimension_select($results, $dimension_id){
		$output = '<option value="0">' . $this->language->get('text_no_dim') . '</option>' . "\n";
		foreach ($results as $result) {
			$output .= '<option value="' . $result['dimension_id'] . '"';
			if ($dimension_id == $result['dimension_id']){
				$output .= ' selected';
			}
			$output .= '>' . $result['unit'] . '</option>' . "\n";
		}
		return $output;
	}

	protected function validate_barcode(){
		$barcode = $this->request->gethtml('barcode');
		$encoding = $this->request->gethtml('encoding');
		$product_id = $this->request->gethtml('product_id');
		$option_id = $this->request->gethtml('option_id');
		$error = FALSE;
		$success = 'Barcode Validated';
		$output = '';
		if($encoding == 'ean'){
			if(!$this->validate->strlen($barcode,12,13)){
				$error = $this->language->get('error_ean');
			}
		} else {
			if(!$this->validate->strlen($barcode,11,12)){
				$error = $this->language->get('error_upc');
			}
		}
		if(!$error){
			$barcode = $this->barcode->check($barcode, $encoding);
			if($this->modelProductOptions->check_barcode_id($barcode, $option_id)){
				$error = $this->language->get('error_barcode_already_exists');
			}
			if(!$error){
				$this->barcode->create($barcode, $encoding);
			}
		} 
		if($error){
			$error = $barcode . ': ' . $error;
			$barcode = '';
		}
		$output .= '<input id="barcode" ';
		$output .= 'type="text" size="14" maxlength="13" ';
		$output .= 'name="barcode" ';
		if(!$error){
			$output .= 'class="success" ';
		}
		$output .= 'value="' . $barcode . '" ';
		$output .= 'onchange="validate_barcode()">';
		if($error){
			$output .= '<span class="error">' . $error . '</span>';
		}
		$this->response->set($output);
	}

	private function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		
		if (!$this->user->hasPermission('modify', 'products_with_options')) {
		$this->error['message'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	protected function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}

	protected function page() {
		if ($this->request->has('search', 'post')) {
			$this->session->set('productwoptions.search', $this->request->gethtml('search', 'post'));
		}

		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('productwoptions.page', (int)$this->request->gethtml('page', 'post'));
		} 

		if ($this->request->has('sort', 'post')) {
			$this->session->set('productwoptions.order', (($this->session->get('productwoptions.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('productwoptions.order') == 'asc') ? 'desc' : 'asc'));
		}

		if ($this->request->has('sort', 'post')) {
			$this->session->set('productwoptions.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('products_with_options'));
	}
}
?>

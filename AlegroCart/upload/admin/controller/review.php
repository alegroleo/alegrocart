<?php // Review AlegroCart
class ControllerReview extends Controller {

	public $error = array();

	public function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->language		=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->config		=& $locator->get('config');
		$this->image		=& $locator->get('image');   
		$this->request		=& $locator->get('request');
		$this->response		=& $locator->get('response');
		$this->session		=& $locator->get('session');
		$this->template		=& $locator->get('template');
		$this->url		=& $locator->get('url');
		$this->user		=& $locator->get('user'); 
		$this->validate		=& $locator->get('validate');
		$this->modelReview	= $model->get('model_admin_review');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('review');

		$this->language->load('controller/review.php');
	}

	protected function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	} 

	protected function insert() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('author', 'post') && $this->validateForm()) {
			$this->modelReview->insert_review();
			$insert_id = $this->modelReview->get_last_id();

			$name_last = $this->request->get('author', 'post');
			if (strlen($name_last) > 26) {
				$name_last = substr($name_last , 0, 23) . '...';
			}
			$this->session->set('name_last_review', $name_last);
			$this->session->set('last_review', $this->url->ssl('review', 'update', array('review_id' => $insert_id)));
			$this->session->set('last_review_id', $insert_id);

			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('review'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());

		$this->session->delete('name_last_review');
		$this->session->delete('last_review');

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('author', 'post') && $this->validateForm() && $this->validateModification()) {
			$this->modelReview->update_review();
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('review', 'update', array('review_id' => $this->request->gethtml('review_id', 'post'))));
			} else {
				$this->response->redirect($this->url->ssl('review'));
			}

		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function delete() { 
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('review_id')) && ($this->validateDelete())) {
			$this->modelReview->delete_review();
			$this->session->set('message', $this->language->get('text_message'));
			$this->session->delete('name_last_review');
			$this->session->delete('last_review');
			$this->response->redirect($this->url->ssl('review'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function changeStatus() { 
		if (($this->request->has('stat_id')) && ($this->request->has('stat')) && $this->validateChangeStatus()) {
			$this->modelReview->change_review_status($this->request->gethtml('stat'), $this->request->gethtml('stat_id'));
		}
	}

	private function getList() {
		$this->session->set('review_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_product'),
			'sort'  => 'pd.name',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_author'),
			'sort'  => 'r.author',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_rating1'),
			'sort'  => 'r.rating1',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_rating2'),
			'sort'  => 'r.rating2',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_rating3'),
			'sort'  => 'r.rating3',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_rating4'),
			'sort'  => 'r.rating4',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_status'),
			'sort'  => 'r.status',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_action'),
			'align' => 'action'
		);

		$results = $this->modelReview->get_page();
		$rows = array();
		foreach ($results as $result) {
			$last = $result['review_id'] == $this->session->get('last_review_id') ? 'last_visited': '';
			$cell = array();
			$cell[] = array(
				'value' => $result['name'],
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['author'],
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['rating1'],
				'align' => 'center',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['rating2'],
				'align' => 'center',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['rating3'],
				'align' => 'center',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['rating4'],
				'align' => 'center',
				'last' => $last
			);

			if ($this->validateChangeStatus()) {
				$cell[] = array(
					'status'  => $result['status'],
					'text' => $this->language->get('button_status'),
					'align' => 'center',
					'status_id' => $result['review_id'],
					'status_controller' => 'review'
				);
			} else {
				$cell[] = array(
					'icon'  => ($result['status'] ? 'enabled.png' : 'disabled.png'),
					'align' => 'center'
				);
			}

			$action = array();
			$action[] = array(
				'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('review', 'update', array('review_id' => $result['review_id']))
			);
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('review', 'delete', array('review_id' => $result['review_id'],'review_validation' =>$this->session->get('review_validation')))
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

		$view->set('text_results', $this->modelReview->get_text_results());

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
		$view->set('controller', 'review');
		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('review', 'page'));
		$view->set('action_delete', $this->url->ssl('review', 'enableDelete'));

		$view->set('search', $this->session->get('review.search'));
		$view->set('sort', $this->session->get('review.sort'));
		$view->set('order', $this->session->get('review.order'));
		$view->set('page', $this->session->get('review.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('insert', $this->url->ssl('review', 'insert'));
		$view->set('last', $this->url->getLast('review'));

		$view->set('pages', $this->modelReview->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	private function getForm() {
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_form_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));

		$view->set('entry_product', $this->language->get('entry_product'));
		$view->set('entry_author', $this->language->get('entry_author'));
		$view->set('entry_rating1', $this->language->get('entry_rating1'));
		$view->set('entry_rating2', $this->language->get('entry_rating2'));
		$view->set('entry_rating3', $this->language->get('entry_rating3'));
		$view->set('entry_rating4', $this->language->get('entry_rating4'));
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_text', $this->language->get('entry_text'));
		$view->set('entry_good', $this->language->get('entry_good'));
		$view->set('entry_bad', $this->language->get('entry_bad'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_author', @$this->error['author']);
		$view->set('error_text', @$this->error['text']);
		$view->set('error_product', @$this->error['product']);
		$view->set('error_rating1', @$this->error['rating1']);
		$view->set('error_rating2', @$this->error['rating2']);
		$view->set('error_rating3', @$this->error['rating3']);
		$view->set('error_rating4', @$this->error['rating4']);
		$view->set('error_status', @$this->error['status']);

		$view->set('action', $this->url->ssl('review', $this->request->gethtml('action'), array('review_id' => $this->request->gethtml('review_id'))));

		$view->set('insert', $this->url->ssl('review', 'insert'));
		$view->set('cancel', $this->url->ssl('review'));
		$view->set('last', $this->url->getLast('review'));

		if ($this->request->gethtml('review_id')) {
			$view->set('update', 'update');
			$view->set('delete', $this->url->ssl('review', 'delete', array('review_id' => (int)$this->request->gethtml('review_id'),'review_validation' =>$this->session->get('review_validation'))));
		}

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('review_id', $this->request->gethtml('review_id'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (($this->request->gethtml('review_id')) && (!$this->request->isPost())) {
			$review_info = $this->modelReview->get_review();
			$this->session->set('review_date_modified', $review_info['date_modified']);
		}

		if ($this->request->has('author', 'post')) {
			if ($this->request->gethtml('author', 'post') != NULL) {
				$name_last = $this->request->get('author', 'post');
			} else {
				$name_last = $this->session->get('name_last_review');
			}
		} else {
			$name_last = @$review_info['author'];
		}
		if (strlen($name_last) > 26) {
			$name_last = substr($name_last , 0, 23) . '...';
		}
		$this->session->set('name_last_review', $name_last);
		$this->session->set('last_review', $this->url->ssl('review', 'update', array('review_id' => $this->request->gethtml('review_id'))));
		$this->session->set('last_review_id', $this->request->gethtml('review_id'));

		if ($this->request->has('product_id', 'post')) {
			$view->set('product_id', $this->request->gethtml('product_id', 'post'));
		} else {
			$view->set('product_id', @$review_info['product_id']);
		}

		$product_data = array();
		$results=$this->modelReview->get_products();
		foreach ($results as $result) {
		$product_data[] = array(
			'product_id' => $result['product_id'],
			'previewimage' => $this->image->resize($result['filename'], $this->config->get('config_image_width'), $this->config->get('config_image_height')),
			'name'        => $result['name']
			);
		}
		$view->set('products', $product_data);

		if ($this->request->has('author', 'post')) {
			$view->set('author', $this->request->gethtml('author', 'post'));
		} else {
			$view->set('author', @$review_info['author']);
		}

		if ($this->request->has('text', 'post')) {
			$view->set('text', $this->request->gethtml('text', 'post'));
		} else {
			$view->set('text', @$review_info['text']);
		}

		if ($this->request->has('rating1', 'post')) {
			$view->set('rating1', $this->request->gethtml('rating1', 'post'));
		} else {
			$view->set('rating1', @$review_info['rating1']);
		}

		if ($this->request->has('rating2', 'post')) {
			$view->set('rating2', $this->request->gethtml('rating2', 'post'));
		} else {
			$view->set('rating2', @$review_info['rating2']);
		}

		if ($this->request->has('rating3', 'post')) {
			$view->set('rating3', $this->request->gethtml('rating3', 'post'));
		} else {
			$view->set('rating3', @$review_info['rating3']);
		}

		if ($this->request->has('rating4', 'post')) {
			$view->set('rating4', $this->request->gethtml('rating4', 'post'));
		} else {
			$view->set('rating4', @$review_info['rating4']);
		}
		
		if ($this->request->has('status', 'post')) {
			$view->set('status', $this->request->gethtml('status', 'post'));
		} else {
			$view->set('status', @$review_info['status']);
		}

		return $view->fetch('content/review.tpl');
	}

	private function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'review')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->validate->strlen($this->request->gethtml('author', 'post'),1,64)) {
			$this->error['author'] = $this->language->get('error_author');
		}

		if (!$this->validate->strlen($this->request->gethtml('text', 'post'),1,1000)) {
			$this->error['text'] = $this->language->get('error_text');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateModification() {
		if ($review_data = $this->modelReview->get_review()) {
			if ($review_data['date_modified'] != $this->session->get('review_date_modified')) {
				$review_data_log = $this->modelReview->get_modified_log($review_data['date_modified']);

				if ($review_data_log['author'] != $this->request->gethtml('author', 'post')) {
					$this->error['author'] = $this->language->get('error_modified', $review_data_log['author']);
				}

				if ($review_data_log['product_id'] != $this->request->gethtml('product_id', 'post')) {
					$this->error['product'] = $this->language->get('error_modified', $review_data_log['name']);
				}

				if ($review_data_log['text'] != $this->request->gethtml('text', 'post')) {
					$this->error['text'] = $this->language->get('error_modified', $review_data_log['text']);
				}

				if ($review_data_log['rating1'] != $this->request->gethtml('rating1', 'post')) {
					$this->error['rating1'] = $this->language->get('error_modified', $review_data_log['rating1']);
				}

				if ($review_data_log['rating2'] != $this->request->gethtml('rating2', 'post')) {
					$this->error['rating2'] = $this->language->get('error_modified', $review_data_log['rating2']);
				}

				if ($review_data_log['rating3'] != $this->request->gethtml('rating3', 'post')) {
					$this->error['rating3'] = $this->language->get('error_modified', $review_data_log['rating3']);
				}

				if ($review_data_log['rating4'] != $this->request->gethtml('rating4', 'post')) {
					$this->error['rating4'] = $this->language->get('error_modified', $review_data_log['rating4']);
				}

				if ($review_data_log['status'] != $this->request->gethtml('status', 'post')) {
					$this->error['status'] = $this->language->get('error_modified', $review_data_log['status'] ? $this->language->get('text_enabled'): $this->language->get('text_disabled'));
				}

				$this->session->set('review_date_modified', $review_data_log['date_modified']);
			}
		} else {
			$review_data_log = $this->modelReview->get_deleted_log();
			$this->session->set('message', $this->language->get('error_deleted', $review_data_log['modifier']));
			$this->response->redirect($this->url->ssl('review'));
		}
		if (@$this->error){
			$this->error['warning'] = $this->language->get('error_modifier', $review_data_log['modifier']);
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
			$this->response->redirect($this->url->ssl('review'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('review'));
		}
	}

	private function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'review')) {
		$this->error['message'] = $this->language->get('error_permission');  
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if(($this->session->get('review_validation') != $this->request->sanitize('review_validation')) || (strlen($this->session->get('review_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('review_validation');
		if (!$this->user->hasPermission('modify', 'review')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateChangeStatus(){
		if (!$this->user->hasPermission('modify', 'review')) {
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

	protected function page() {
		if ($this->request->has('search', 'post')) {
			$this->session->set('review.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('review.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('review.order', (($this->session->get('review.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('review.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('review.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('review'));
	}
}
?>

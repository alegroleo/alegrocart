<?php //Order AlegroCart
class ControllerOrder extends Controller {

	public $error = array();

	public function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->address		=& $locator->get('address');
		$this->config		=& $locator->get('config');
		$this->currency		=& $locator->get('currency');
		$this->language		=& $locator->get('language');
		$this->mail		=& $locator->get('mail');
		$this->module		=& $locator->get('module');
		$this->request		=& $locator->get('request');
		$this->response		=& $locator->get('response');
		$this->session		=& $locator->get('session');
		$this->template		=& $locator->get('template');
		$this->url		=& $locator->get('url');
		$this->user		=& $locator->get('user'); 
		$this->modelOrder	= $model->get('model_admin_order');
		$this->barcode		=& $locator->get('barcode'); 
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('order');

		$this->language->load('controller/order.php');
	}

	protected function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('order_status_id', 'post') && $this->validateForm()) {

			if($this->request->gethtml('order_status_id', 'post') == "12"){
				$this->response->redirect($this->url->ssl('order_edit&order_id=' . $this->request->gethtml('order_id') . '&order_status_id=12'));
			}

			$this->modelOrder->update_order();
			$this->modelOrder->insert_order_history();
			if (($this->config->get('config_email_send')) && ($this->request->gethtml('notify', 'post'))) {
				$order_info = $this->modelOrder->get_order_info();
				$order_id = $order_info['reference'];
				$invoice_number = $order_info['invoice_number'];
				$invoice  = $this->url->create(HTTP_CATALOG, 'account_invoice', FALSE, array('order_id' => $this->request->gethtml('order_id')));
				$date     = $this->language->formatDate($this->language->get('date_format_long'),strtotime($order_info['date_added']));
				$status   = $order_info['status'];
				$comment  = $this->request->gethtml('comment', 'post');

				$this->mail->setTo($order_info['email']);
				$this->mail->setFrom($this->config->get('config_email'));
				$this->mail->setSender($this->config->get('config_store'));
				$this->mail->setSubject($this->language->get('email_subject', $order_id));
				$this->mail->setText($this->language->get('email_message', $order_id, $invoice_number, $invoice, $date, $status, $comment));
				$this->mail->send();
			}
			$this->session->set('last_order_id', $this->request->gethtml('order_id'));
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('order', 'update', array('order_id' => $this->request->gethtml('order_id', 'post'))));
			} else {
				$this->response->redirect($this->url->ssl('order'));
			}
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('order_id')) && ($this->validateDelete())) {
			$this->modelOrder->delete_order();
			$this->session->set('message', $this->language->get('text_message'));
			$this->session->delete('name_last_order');
			$this->session->delete('last_order');
			$this->response->redirect($this->url->ssl('order'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	private function getList() {
		$this->session->set('order_validation', md5(time()));

		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_order_id'),
			'sort'  => 'o.order_id',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_reference'),
			'sort'  => 'o.reference',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_invoice_number'),
			'sort'  => 'o.invoice_number',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_name'),
			'sort'  => 'o.firstname',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_status'),
			'sort'  => 'os.name',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_update_status'),
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_date_added'),
			'sort'  => 'o.date_added',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_total'),
			'sort'  => 'o.total',
			'align' => 'right'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_action'),
			'align' => 'action'
		);

		$results = $this->modelOrder->get_page();
		$rows = array();
		foreach ($results as $result) {
			$last = $result['order_id'] == $this->session->get('last_order_id') ? 'last_visited': '';
			$cell = array();
			$cell[] = array(
				'value' => $result['order_id'],
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['reference'],
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['invoice_number'],
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['firstname'] . ' ' . $result['lastname'],
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'status_name' => $result['status'],
				'status_name_id' => 'status_name_' . $result['order_id'],
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'update_status' => TRUE,
				'order_id' =>$result['order_id'],
				'align' => 'center'
			);
			$cell[] = array(
				'value' => $this->language->formatDate($this->language->get('date_format_short').' ('.$this->language->get('time_format').')', strtotime($result['date_added'])),
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'value' => $this->currency->format($result['total'], $result['currency'], $result['value']),
				'align' => 'right',
				'last' => $last
			);

			$action = array();

			$action[] = array(
				'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('order', 'update', array('order_id' => $result['order_id']))
			);

			$action[] = array(
				'icon' => 'order_edit.png',
				'text' => $this->language->get('button_edit'),
				'href' => $this->url->ssl('order_edit', 'index', array('order_id' => $result['order_id']))
			);

			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('order', 'delete', array('order_id' => $result['order_id'],'order_validation' => $this->session->get('order_validation')))
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

		$view->set('text_results', $this->modelOrder->get_text_results());

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));
		$view->set('controller', 'order');
		$view->set('order_statuses', $this->modelOrder->get_order_statuses());
		$view->set('default_status', $this->session->get('default_order_status'));
		$view->set('text_select_status', $this->language->get('text_select_status'));
		$view->set('text_status_error', $this->language->get('text_status_error'));

		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');

		$view->set('action', $this->url->ssl('order', 'page'));
		$view->set('action_delete', $this->url->ssl('order', 'enableDelete'));
		$view->set('last', $this->url->getLast('order'));

		$view->set('search', $this->session->get('order.search'));
		$view->set('sort', $this->session->get('order.sort'));
		$view->set('order', $this->session->get('order.order'));
		$view->set('page', $this->session->get('order.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('pages', $this->modelOrder->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	private function getForm() {
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def); 
		$view->set('heading_title', $this->language->get('heading_form_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_order', $this->language->get('text_order'));
		$view->set('text_invoice_number', $this->language->get('text_invoice_number'));
		$view->set('text_email', $this->language->get('text_email'));
		$view->set('text_telephone', $this->language->get('text_telephone'));
		$view->set('text_fax', $this->language->get('text_fax'));
		$view->set('text_shipping_address', $this->language->get('text_shipping_address'));
		$view->set('text_shipping_method', $this->language->get('text_shipping_method'));
		$view->set('text_payment_address', $this->language->get('text_payment_address'));
		$view->set('text_payment_method', $this->language->get('text_payment_method'));
		$view->set('text_currency', $this->language->get('text_currency'));
		$view->set('text_order_history', $this->language->get('text_order_history'));
		$view->set('text_order_download', $this->language->get('text_order_download'));
		$view->set('text_order_update', $this->language->get('text_order_update'));
		$view->set('text_product', $this->language->get('text_product'));
		$view->set('text_model_number', $this->language->get('text_model_number'));
		$view->set('text_soldby', $this->language->get('text_soldby'));
		$view->set('text_quantity', $this->language->get('text_quantity'));
		$view->set('text_price', $this->language->get('text_price'));
		$view->set('text_total', $this->language->get('text_total'));
		$view->set('text_special', $this->language->get('text_special'));
		$view->set('text_extended', $this->language->get('text_extended'));
		$view->set('text_coupon_value', $this->language->get('text_coupon_value'));
		$view->set('text_discount_value', $this->language->get('text_discount_value'));
		$view->set('text_net', $this->language->get('text_net'));
		$view->set('text_tax_rate', $this->language->get('text_tax_rate'));
		$view->set('text_tax', $this->language->get('text_tax'));
		$view->set('text_tax_amount', $this->language->get('text_tax_amount'));
		$view->set('text_product_totals', $this->language->get('text_product_totals'));
		$view->set('text_shipping_cost', $this->language->get('text_shipping_cost'));
		$view->set('text_free_shipping', $this->language->get('text_free_shipping'));
		$view->set('text_cart_totals', $this->language->get('text_cart_totals'));
		$view->set('text_shipping', $this->language->get('text_shipping'));
		$view->set('text_shippable', $this->language->get('text_shippable'));
		$view->set('text_non_shippable', $this->language->get('text_non_shippable'));
		$view->set('text_downloadable', $this->language->get('text_downloadable'));

		$view->set('column_date_added', $this->language->get('column_date_added'));
		$view->set('column_status', $this->language->get('column_status'));
		$view->set('column_download', $this->language->get('column_download'));
		$view->set('column_filename', $this->language->get('column_filename'));
		$view->set('column_remaining', $this->language->get('column_remaining'));
		$view->set('column_notify', $this->language->get('column_notify'));
		$view->set('column_comment', $this->language->get('column_comment'));

		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_comment', $this->language->get('entry_comment'));
		$view->set('entry_notify', $this->language->get('entry_notify'));
		$view->set('cancelled_status',$this->language->get('cancelled_status'));

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

		$view->set('action', $this->url->ssl('order', 'update', array('order_id' => $this->request->gethtml('order_id'))));
		$view->set('last', $this->url->getLast('order'));
		$view->set('cancel', $this->url->ssl('order'));

		if ($this->request->gethtml('order_id')) {
			$view->set('update', 'update');
			$view->set('delete', $this->url->ssl('order', 'delete', array('order_id' => $this->request->gethtml('order_id'),'order_validation' => $this->session->get('order_validation'))));
		}

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('order_id', $this->request->gethtml('order_id'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$order_info = $this->modelOrder->get_order();
		if($order_info['modified']){
			$new_order = $this->modelOrder->get_modified_order($order_info['new_reference']);
			$new_order_date = $this->language->formatDate($this->language->get('date_format_short'), strtotime($new_order['date_added']));
			$view->set('modified', $this->language->get('text_modified', $new_order['order_id'], $order_info['new_reference'], $new_order_date));
		}

		$name_last = $order_info['invoice_number'];
		if (strlen($name_last) > 26) {
			$name_last = substr($name_last , 0, 23) . '...';
		}
		$this->session->set('name_last_order', $name_last);
		$this->session->set('last_order', $this->url->ssl('order', 'update', array('order_id' => $this->request->gethtml('order_id'))));
		$this->session->set('last_order_id', $this->request->gethtml('order_id'));

		$view->set('reference', $order_info['reference']);
		$view->set('invoice_number', $order_info['invoice_number']);
		$view->set('order_id', $order_info['order_id']);
		$view->set('email', $order_info['email']);
		$view->set('telephone', $order_info['telephone']);
		$view->set('fax', $order_info['fax']);
		$view->set('currency', $order_info['currency']);
		$this->decimal_place = $this->currency->currencies[$order_info['currency']]['decimal_place'];
		$view->set('coupon_sort_order', $order_info['coupon_sort_order']);
		$view->set('discount_sort_order', $order_info['discount_sort_order']);
		//$view->set('columns', $this->tpl_columns);

		$shipping_address = array(
			'firstname' => $order_info['shipping_firstname'],
			'lastname'  => $order_info['shipping_lastname'],
			'company'   => $order_info['shipping_company'],
			'address_1' => $order_info['shipping_address_1'],
			'address_2' => $order_info['shipping_address_2'],
			'city'      => $order_info['shipping_city'],
			'postcode'  => $order_info['shipping_postcode'],
			'zone'      => $order_info['shipping_zone'],
			'country'   => $order_info['shipping_country']
		);

		if (array_filter($shipping_address)) {
			$view->set('shipping_address', $this->address->format($shipping_address, $order_info['shipping_address_format'], '<br />'));
		} else {
			$store_address = str_replace(array("\r\n", "\r", "\n"), '<br>', $this->config->get('warehouse_location') ? $this->config->get('warehouse_location') : $this->config->get('config_address'));
			$view->set('shipping_address', $this->config->get('config_store') . "<br />" . $store_address);
		}

		$view->set('shipping_method', $order_info['shipping_method']);

		$payment_address = array(
			'firstname' => $order_info['payment_firstname'],
			'lastname'  => $order_info['payment_lastname'],
			'company'   => $order_info['payment_company'],
			'address_1' => $order_info['payment_address_1'],
			'address_2' => $order_info['payment_address_2'],
			'city'      => $order_info['payment_city'],
			'postcode'  => $order_info['payment_postcode'],
			'zone'      => $order_info['payment_zone'],
			'country'   => $order_info['payment_country']
		);
		$view->set('payment_address', nl2br($this->address->format($payment_address, $order_info['payment_address_format'])));
		$view->set('payment_method', $order_info['payment_method']);

		$products = $this->modelOrder->get_products();

		$product_data = array();

		$tax_total = 0;	
		$coupon_total = 0;
		$net = 0;
		$net_total = 0;
		$producttax = 0;
		$discount_total = 0;
		$totals_total = 0;
		$shipping_tax = 0;
		$extended_total = 0;
		$freeshipping_tax = 0;
		$cart_net_total = 0;
		$cart_tax_total = 0;
		$cart_totals_total = 0;
		$shipping_net = $order_info['shipping_net'];
		$freeshipping_net = $order_info['freeshipping_net'] * -1;
		$shipping_tax = roundDigits($order_info['shipping_tax_rate'] * $order_info['shipping_net'] / 100, $this->decimal_place);
		$freeshipping_tax = roundDigits($order_info['shipping_tax_rate'] * $order_info['freeshipping_net'] / 100, $this->decimal_place);
		$shipping_total = $order_info['shipping_net'] + $shipping_tax;
		$freeshipping_total = $order_info['freeshipping_net'] + $freeshipping_tax;
		$view->set('shipping_net', $this->currency->format($order_info['shipping_net'] + ($order_info['taxed'] ? $shipping_tax : 0), $order_info['currency'], $order_info['value']));
		$view->set('shipping_tax_rate', round($order_info['shipping_tax_rate'], $this->decimal_place). '%');
		if($order_info['freeshipping_net'] <> 0){
			$view->set('freeshipping_net', $this->currency->format(($order_info['freeshipping_net'] * -1) + ($order_info['taxed'] ? ($freeshipping_tax * -1) : 0), $order_info['currency'], $order_info['value']));
		} else {
			$view->set('freeshipping_net', NULL);
		}

		foreach ($products as $product) {
			$options = $this->modelOrder->get_options($product['order_product_id']);
			$option_data = array();
			foreach ($options as $option) {
				$option_data[] = array(
					'name'  => $option['name'],
					'value' => $option['value'],
				);
			}
			$download = $this->modelOrder->check_downloads($product['order_product_id']);

			$special_pr = $product['special_price'];
			$net = $product['total'] - (($product['coupon'] != 0) ? $product['coupon'] : NULL ) - (($product['general_discount'] != 0) ? $product['general_discount'] : NULL );
			$producttax = $order_info['taxed'] ? $net - roundDigits($net / ((100 + $product['tax'])/100), $this->decimal_place) : roundDigits($net * ($product['tax'] / 100), $this->decimal_place);
			$tax_total += $producttax;
			$coupon_total += ($product['coupon'] != 0) ? $product['coupon'] : NULL;
			$discount_total += ($product['general_discount'] != 0) ? $product['general_discount'] : NULL;
			$net_total += $net;
			$total_discounted = $order_info['taxed'] ? $net : $net + $producttax;
			$totals_total += $total_discounted;
			$extended_total += $product['total'];
			$cart_net_total = $net_total + (($shipping_net != 0) ? $shipping_net : NULL) - (($freeshipping_net != 0) ? ($freeshipping_net * -1) : NULL);
			$cart_tax_total = $tax_total + (($shipping_net != 0) ? $shipping_tax : NULL) - (($freeshipping_net != 0) ? $freeshipping_tax : NULL);
			//$cart_totals_total = $order_info['taxed'] ? $cart_net_total : $cart_net_total + $cart_tax_total;
			$cart_totals_total = $order_info['taxed'] ? $cart_net_total + ($shipping_tax - $freeshipping_tax): $cart_net_total + $cart_tax_total;

			$product_data[] = array(
				'name'			=> $product['name'],
				'model_number'		=> $product['model_number'],
				'vendor_name'		=> $product['vendor_id'] !='0' ? $product['vendor_name'] : NULL,
				'option'		=> $option_data,
				'download'		=> $download,
				'quantity'		=> $product['quantity'],
				'barcode' 		=> $product['barcode'],
				'barcode_url'		=> $product['barcode'] ? $this->barcode->show($product['barcode']) : NULL,
				'special_price'	=> $special_pr != 0 ? $this->currency->format($special_pr, $order_info['currency'], $order_info['value']) : "$0.00",
				'price'			=> $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
				'discount'		=> (ceil($product['discount']) ? $this->currency->format($product['discount'], $order_info['currency'], $order_info['value']) : NULL),
				'coupon'		=> ($product['coupon'] != 0 ? $this->currency->format(($product['coupon'] * -1), $order_info['currency'], $order_info['value']) : NULL),
				'general_discount'	=> ($product['general_discount'] !=0 ? $this->currency->format(($product['general_discount'] * -1), $order_info['currency'], $order_info['value']) : NULL),
				'tax'			=> round($product['tax'], $this->decimal_place),
				'shipping'		=> $product['shipping'],
				'total'			=> $this->currency->format($product['total'],$order_info['currency'], $order_info['value']),
				'net'			=> $this->currency->format($net, $order_info['currency'], $order_info['value']),
				'product_tax'		=> $this->currency->format($producttax, $order_info['currency'], $order_info['value']),
				'total_discounted'	=> $this->currency->format($total_discounted, $order_info['currency'], $order_info['value']),
				'href'			=> $this->url->ssl('product', 'update', array('product_id' => $product['product_id'])),
				'product_id'		=> $product['product_id']
			);
		}

		$view->set('taxed', $order_info['taxed']);
		$view->set('products', $product_data);
		$view->set('totals',$this->modelOrder->get_totals($order_info['order_id']));
		$view->set('tax_total', $this->currency->format($tax_total, $order_info['currency'], $order_info['value']));
		$view->set('coupon_total', $coupon_total ? $this->currency->format(($coupon_total * -1), $order_info['currency'], $order_info['value']) : NULL);
		$view->set('discount_total', $discount_total ? $this->currency->format(($discount_total * -1), $order_info['currency'], $order_info['value']) : NULL);
		$view->set('extended_total', $this->currency->format($extended_total, $order_info['currency'], $order_info['value']));
		$view->set('net_total', $this->currency->format($net_total, $order_info['currency'], $order_info['value']));
		$view->set('cart_net_total', $this->currency->format($cart_net_total, $order_info['currency'], $order_info['value']));
		$view->set('shipping_tax', $shipping_tax  ? $this->currency->format($shipping_tax, $order_info['currency'], $order_info['value']) : NULL);
		$view->set('freeshipping_tax', $freeshipping_tax ? $this->currency->format(($freeshipping_tax * -1), $order_info['currency'], $order_info['value']) : NULL);
		$view->set('cart_tax_total', $this->currency->format($cart_tax_total, $order_info['currency'], $order_info['value']));
		$view->set('totals_total', $this->currency->format($totals_total, $order_info['currency'], $order_info['value']));
		$view->set('shipping_total', $shipping_total ? $this->currency->format($shipping_total, $order_info['currency'], $order_info['value']) : NULL);
		$view->set('freeshipping_total', $freeshipping_total ? $this->currency->format(($freeshipping_total * -1), $order_info['currency'], $order_info['value']) : NULL);
		$view->set('cart_totals_total', $this->currency->format($cart_totals_total, $order_info['currency'], $order_info['value']));

		$history_data = array();
		$results = $this->modelOrder->get_history();
		foreach ($results as $result) {
			$history_data[] = array(
				'date_added' => $this->language->formatDate($this->language->get('date_format_short').' ('.$this->language->get('time_format').')', strtotime($result['date_added'])),
				'status'     => $result['status'],
				'comment'    => $result['comment'],
				'notify'     => $result['notify']
			);
		}
		$view->set('historys', $history_data);

		$download_data = array();
		$results = $this->modelOrder->get_downloads();
		foreach ($results as $result) {
			$download_data[] = array(
				'name'      => $result['name'],
				'filename'  => $result['filename'],
				'remaining' => $result['remaining']
			);
		}
		$view->set('downloads', $download_data);
		$view->set('order_status_id', $order_info['order_status_id']);
		$view->set('order_statuses', $this->modelOrder->get_order_statuses());

		return $view->fetch('content/order.tpl');
	}

	protected function update_status(){
		$order_id = $this->request->gethtml('order_id');
		$order_status_id = $this->request->gethtml('order_status_id');
		$this->modelOrder->update_order_status($order_id, $order_status_id);
		$this->modelOrder->update_status_history($order_id, $order_status_id);
		if ($this->config->get('config_email_send')){
			$order_info = $this->modelOrder->get_order_info();
			$order_id = $order_info['reference'];
			$invoice_number = $order_info['invoice_number'];
			$invoice  = $this->url->create(HTTP_CATALOG, 'account_invoice', FALSE, array('order_id' => $this->request->gethtml('order_id')));
			$date     = $this->language->formatDate($this->language->get('date_format_long'),strtotime($order_info['date_added']));
			$status   = $order_info['status'];
			$comment  = '';
			$this->mail->setTo($order_info['email']);
			$this->mail->setFrom($this->config->get('config_email'));
			$this->mail->setSender($this->config->get('config_store'));
			$this->mail->setSubject($this->language->get('email_subject', $order_id));
			$this->mail->setText($this->language->get('email_message', $order_id, $invoice_number, $invoice, $date, $status, $comment));
			$this->mail->send();
		}
		$this->response->set(TRUE);
	}

	private function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'order')) {
			$this->error['message'] = $this->language->get('error_permission'); 
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
			$this->response->redirect($this->url->ssl('order'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('order'));
		}
	}

	private function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'order')) {
		$this->error['message'] = $this->language->get('error_permission');  
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if(($this->session->get('order_validation') != $this->request->sanitize('order_validation')) || (strlen($this->session->get('order_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('order_validation');
		if (!$this->user->hasPermission('modify', 'order')) {
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
			$this->session->set('order.search', $this->request->gethtml('search', 'post'));
		}

		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('order.page', $this->request->gethtml('page', 'post'));
		} 

		if ($this->request->has('sort', 'post')) {
			$this->session->set('order.order', (($this->session->get('order.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('order.order') == 'asc') ? 'desc' : 'asc'));
		}

		if ($this->request->has('sort', 'post')) {
			$this->session->set('order.sort', $this->request->gethtml('sort', 'post'));
		}

		if ($this->request->has('default_order_status', 'post')){
			$this->session->set('default_order_status', $this->request->get('default_order_status', 'post'));
		}

		$this->response->redirect($this->url->ssl('order'));
	}
}
?>

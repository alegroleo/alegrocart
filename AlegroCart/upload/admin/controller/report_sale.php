<?php //Report Sale AlegroCart
class ControllerReportSale extends Controller {
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->config   	=& $locator->get('config');
		$this->currency 	=& $locator->get('currency');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session  	=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->modelReportSale = $model->get('model_admin_report_sale');
		
		$this->language->load('controller/report_sale.php');
		}
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));

		$cols = array();

		$cols[] = array(
			'name'  => $this->language->get('column_date'),
			'align' => 'left',
			'sort'  => 'date_added'
		);

		$cols[] = array(
			'name'  => $this->language->get('column_orders'),
			'align' => 'right',
			'sort'  => 'order_id'
		);

		$cols[] = array(
			'name'  => $this->language->get('column_amount'),
			'align' => 'right',
			'sort'  => 'total'
		);
		
		$sql = "select min(date_added) as date_from, max(date_added) as date_to, count(order_id) as orders, sum(total) as amount from `order`";

		if (($this->session->has('report_sale.date_from')) && ($this->session->has('report_sale.date_to'))) {
			$date_from = $this->session->get('report_sale.date_from');
			
			$from = date('Y-m-d', strtotime($date_from['year'] . '/' . $date_from['month'] . '/' . $date_from['day']));
			
			$date_to = $this->session->get('report_sale.date_to');
			
			$to = date('Y-m-d H:i:s', strtotime($date_to['year'] . '/' . $date_to['month'] . '/' . $date_to['day'] . '23:59:59'));
			
			$sql .= $this->modelReportSale->sql_parse_date($from, $to);
			
		} else {
			$date = explode('/', date('d/m/Y', time()));

			$date_from = array(
				'day'   => $date[0],
				'month' => ($date[1] != '01') ? $date[1] - 1 : $date[1],
				'year'  => $date[2]
			);

			$date_to = array(
				'day'   => $date[0],
				'month' => $date[1],
				'year'  => $date[2]
			);

			$sql .= $this->modelReportSale->sql_parse_start_date($date_from);
			
		}

		if ($this->session->get('report_sale.order_status_id')) {
			$sql .= " and order_status_id = '" . (int)$this->session->get('report_sale.order_status_id') . "'";
		}

		$group = array(
			'year',
			'month',
			'week',
			'dayofweek'
		);

		if (in_array($this->session->get('report_sale.group'), $group)) {
			$sql .= " group by " . $this->session->get('report_sale.group') . "(date_added)";
		} else {
			$sql .= " group by week(date_added)";
			
			$this->session->set('report_sale.group', 'week');
		}

		$sort = array(
			'date_added',
			'order_id',
			'total'
		);

		if (in_array($this->session->get('report_sale.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('report_sale.sort') . " " . (($this->session->get('report_sale.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by date_added asc";
		}

		$results = $this->modelReportSale->get_page($sql);

		$rows = array();
		foreach ($results as $result) {
			$cell = array();
 
			$cell[] = array(
				'value' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_from'])) . ' - ' . $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_to'])),
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $result['orders'],
				'align' => 'right'
			);

			$cell[] = array(
				'value' => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'align' => 'right'
			);

			$rows[] = array('cell' => $cell);
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('text_results', $this->modelReportSale->get_text_results());

		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_date', $this->language->get('entry_date'));
		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_group', $this->language->get('entry_group'));

		$view->set('button_search', $this->language->get('button_search'));

		$view->set('sort', $this->session->get('report_sale.sort'));
		$view->set('order', $this->session->get('report_sale.order'));
		$view->set('page', $this->session->get('report_sale.page'));
		$view->set('group', $this->session->get('report_sale.group'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('action', $this->url->ssl('report_sale', 'page'));

		$group_data = array();

		$group_data[] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);

		$group_data[] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);

		$group_data[] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);

		$group_data[] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'dayofweek',
		);

		$view->set('groups', $group_data);

		$month_data = array();

		$month_data[] = array(
			'value' => '01',
			'text'  => $this->language->get('text_january')
		);
 
		$month_data[] = array(
			'value' => '02',
			'text'  => $this->language->get('text_february')
		);

		$month_data[] = array(
			'value' => '03',
			'text'  => $this->language->get('text_march')
		);

		$month_data[] = array(
			'value' => '04',
			'text'  => $this->language->get('text_april')
		);

		$month_data[] = array(
			'value' => '05',
			'text'  => $this->language->get('text_may')
		);

		$month_data[] = array(
			'value' => '06',
			'text'  => $this->language->get('text_june')
		);

		$month_data[] = array(
			'value' => '07',
			'text'  => $this->language->get('text_july')
		);

		$month_data[] = array(
			'value' => '08',
			'text'  => $this->language->get('text_august')
		);

		$month_data[] = array(
			'value' => '09',
			'text'  => $this->language->get('text_september')
		);

		$month_data[] = array(
			'value' => '10',
			'text'  => $this->language->get('text_october')
		);

		$month_data[] = array(
			'value' => '11',
			'text'  => $this->language->get('text_november')
		);

		$month_data[] = array(
			'value' => '12',
			'text'  => $this->language->get('text_december')
		);

		$view->set('months', $month_data);

		$view->set('date_from_day', $date_from['day']);
		$view->set('date_from_month', $date_from['month']);
		$view->set('date_from_year', $date_from['year']);

		$view->set('date_to_day', $date_to['day']);
		$view->set('date_to_month', $date_to['month']);
		$view->set('date_to_year', $date_to['year']);

		$view->set('order_status_id', $this->session->get('report_sale.order_status_id'));

		$order_status_data = array();

		$results = $this->modelReportSale->get_order_status();

		$order_status_data[] = array(
			'text'  => $this->language->get('text_all_status'),
			'value' => '0'
		);

		foreach ($results as $result) {
			$order_status_data[] = array(
				'text'  => $result['name'],
				'value' => $result['order_status_id'],
				'href'  => $this->url->ssl('report_sale', FALSE, array('order_status_id' => $result['order_status_id']))
			);
		}

		$view->set('order_statuses', $order_status_data);

		$view->set('pages', $this->modelReportSale->get_pagination());

		$this->template->set('content', $view->fetch('content/report_sale.tpl'));
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function page() {	
		if ($this->request->has('sort', 'post')) {
			$this->session->set('report_sale.sort', $this->request->gethtml('sort', 'post'));
		}
			
		if ($this->request->has('sort', 'post')) {
			$this->session->set('report_sale.order', (($this->session->get('report_sale.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('report_sale.order') == 'asc') ? 'desc' : 'asc'));
		}
		
		if ($this->request->has('page', 'post')) {
			$this->session->set('report_sale.page', $this->request->gethtml('page', 'post'));
		}
		
		if ($this->request->has('group', 'post')) {
			$this->session->set('report_sale.group', $this->request->gethtml('group', 'post'));
		}
		
		if ($this->request->has('order_status_id', 'post')) {
			$this->session->set('report_sale.order_status_id', $this->request->gethtml('order_status_id', 'post'));
		}
		
		if ($this->request->has('date_from', 'post')) {
			$this->session->set('report_sale.date_from', $this->request->gethtml('date_from', 'post'));
		}
		
		if ($this->request->has('date_to', 'post')) {
			$this->session->set('report_sale.date_to', $this->request->gethtml('date_to', 'post'));
		}
		
		$this->response->redirect($this->url->ssl('report_sale'));
	}
}
?>
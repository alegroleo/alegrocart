<?php // Report Online AlegroCart
class ControllerReportOnline extends Controller {
 	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->response 	=& $locator->get('response');
		$this->template 	=& $locator->get('template');
		$this->modelReportOnline = $model->get('model_admin_report_online');
		
		$this->language->load('controller/report_online.php');
		}
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));

        $results = $this->modelReportOnline->get_sessions();
		$results = $this->remove_duplicates($results, 'ip');
		$rows = array();

		foreach ($results as $result) {
			$value = array();
			$a = preg_split("/(\w+)\|/", $result['value'], - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

			for ($i = 0; $i < count($a); $i = $i + 2) {
				$value[$a[$i]] = unserialize($a[$i + 1]);
			}

			if (isset($value['user_id'])) {
				$user_info = $this->modelReportOnline->get_user($value['user_id']);
				$name = $this->language->get('text_admin', $user_info['username']);
			} elseif (isset($value['customer_id'])) {
				$customer_info = $this->modelReportOnline->get_customer($value['customer_id']);
				$name = $customer_info['name'];
			} else {
				$name = $this->language->get('text_guest');
			}
			
			if (isset($value['cart'])) {
				$items = '';
				$keys = array_keys($value['cart']);
				$values = array_values($value['cart']);
				for ($i = 0; $i < count($keys); $i++) {
					$product = $this->modelReportOnline->get_product($keys[$i]);
					if (strlen($items) == 0) {
						$items .= '<hr style="margin:0px;padding:0px"/>';
					}
					$items .= $product['name'] . " x " . $values[$i] . "<br/>";
				}
			}

			$rows[] = array(
				'name'  => $name,
				'time'  => date('dS F Y h:i:s A', strtotime($result['time'])),
				'ip'    => $result['ip'],
				'url'   => $result['url'],
				'total' => (isset($value['cart']) ? array_sum($value['cart']).$items : 0)
			);
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('column_name', $this->language->get('column_name'));
		$view->set('column_time', $this->language->get('column_time'));
		$view->set('column_ip', $this->language->get('column_ip'));
		$view->set('column_url', $this->language->get('column_url'));
		$view->set('column_total', $this->language->get('column_total'));
		$view->set('rows', $rows);

		$this->template->set('content', $view->fetch('content/report_online.tpl'));
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	function remove_duplicates($array, $row_element) {   
		$new_array[0] = $array[0];
		foreach ($array as $current) {
			$add_flag = 1;
			foreach ($new_array as $tmp) {
				if ($current[$row_element]==$tmp[$row_element]) {
					$add_flag = 0; break;
				}
			}
			if ($add_flag) $new_array[] = $current;
		}
		return $new_array;
	} 
}
?>
<?php //AdminModelPaymentBankTransfer AlegroCart
class Model_Admin_PaymentBanktr extends Model {
	function __construct(&$locator) {
		$this->config		=& $locator->get('config');
		$this->database		=& $locator->get('database');
		$this->language		=& $locator->get('language');
		$this->request		=& $locator->get('request');
		$this->session		=& $locator->get('session');
	}

	function delete_BTR(){
		$this->database->query("delete from setting where `group` = 'banktr'");
	}

	function install_BTR(){
		$this->database->query("insert into setting set type = 'global', `group` = 'banktr', `key` = 'banktr_status', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'banktr', `key` = 'banktr_geo_zone_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'banktr', `key` = 'banktr_sort_order', value = '2'");
	}

	function update_BTR($type, $key, $combo){
		$this->database->query($this->database->parse("insert into setting set type = '" . $type . "', `group` = 'banktr', `key` = '" . $key . "', `value` = '?'", htmlspecialchars($this->request->gethtml($combo, 'post'))));
	}

	function get_BTR(){
		$results = $this->database->getRows("select * from setting where `group` = 'banktr'");
		return $results;
	}

	function get_geo_zones(){
		$results = $this->database->cache('geo_zone', "select * from geo_zone");
		return $results;
	}

	function get_extension_id($controller) {
		$result = $this->database->getRow("SELECT extension_id FROM extension WHERE controller ='" . $controller . "'");
		return $result['extension_id'];
	}
}
?>

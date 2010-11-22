<?php //AdminModelPaymentAuthnetaim AlegroCart
class Model_Admin_PaymentAuthnetaim extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_AIM(){
		$this->database->query("delete from setting where `group` = 'authnetaim'");
	}
	function install_AIM(){
		// Add Order status 99 "Needs Payment Review" if not exist.
        //$this->database->query("replace into order_status(order_status_id, language_id, name) values ('99', '1', 'Paid Unconfirmed')");
        $this->database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_status', value = '0'");
        $this->database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_geo_zone_id', value = '0'");
        $this->database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_sendemail', value = 'FALSE'");
        $this->database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_test', value = '1'");
        $this->database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_prod_login', value = ''");
        $this->database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_prod_txnkey', value = ''");
        $this->database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_test_login', value = ''");
        $this->database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_test_txnkey', value = ''");
        $this->database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_authtype', value = 'auth_capture'");
        $this->database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_sort_order', value = '1'");
	}
	function update_AIM($type, $key, $combo){
		$this->database->query($this->database->parse("insert into setting set type = '" . $type . "', `group` = 'authnetaim', `key` = '" . $key . "', `value` = '?'", htmlspecialchars($this->request->gethtml($combo, 'post'))));
	}
	function get_AIM_keys(){
		$results = $this->database->getRows("select * from setting where `group` = 'authnetaim' and `key` != 'authnetaim_currency'");
		return $results;
	}
	function update_AIM_currency(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_currency', `value` = '?'", implode(',', $this->request->gethtml('global_authnetaim_currency', 'post', array()))));
	}
	function get_AIM(){
		$results = $this->database->getRows("select * from setting where `group` = 'authnetaim'");
		return $results;
	}
	function get_geo_zones(){
		$results = $this->database->cache('geo_zone', "select * from geo_zone");
		return $results;
	}
}
?>
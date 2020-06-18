<?php //AdminModelBankAccount AlegroCart
class Model_Admin_BankAccount extends Model {
	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request 	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}

	function insert_bankaccount(){
		$sql = "insert into bank_account set currency = '?', bank_name = '?', bank_address = '?', owner = '?', ban = '?', iban = '?', swift = '?', charge = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('currency', 'post'), $this->request->gethtml('bank_name', 'post'), $this->request->gethtml('bank_address', 'post'), $this->request->gethtml('owner', 'post'), $this->request->gethtml('ban', 'post'), $this->request->gethtml('iban', 'post'), $this->request->gethtml('swift', 'post'), $this->request->gethtml('charge', 'post')));
	}

	function update_bankaccount(){
		$sql = "update bank_account set currency = '?', bank_name = '?', bank_address = '?', owner = '?', ban = '?', iban = '?', swift = '?', charge = '?' where bank_account_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('currency', 'post'), $this->request->gethtml('bank_name', 'post'), $this->request->gethtml('bank_address', 'post'), $this->request->gethtml('owner', 'post'), $this->request->gethtml('ban', 'post'), $this->request->gethtml('iban', 'post'), $this->request->gethtml('swift', 'post'), $this->request->gethtml('charge', 'post'), (int)$this->request->gethtml('bank_account_id')));
	}

	function get_bankaccount(){
		$result = $this->database->getRow("select distinct * from bank_account where bank_account_id = '" . (int)$this->request->gethtml('bank_account_id') . "'");
		return $result;
	}

	function delete_bankaccount(){
		$this->database->query("delete from bank_account where bank_account_id = '" . (int)$this->request->gethtml('bank_account_id') . "'");
	}

	function get_page(){
		if (!$this->session->get('bank_account.search')) {
			$sql = "select bank_account_id, currency, ban from bank_account";
		} else {
			$sql = "select bank_account_id, currency, ban from bank_account where currency like '?'";
		}
		$sort = array('currency', 'ban');
		if (in_array($this->session->get('bank_account.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('bank_account.sort') . " " . (($this->session->get('bank_account.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by currency asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('bank_account.search') . '%', '%' . $this->session->get('bank_account.search') . '%'), $this->session->get('bank_account.page'), $this->config->get('config_max_rows')));
		return $results;
	}

	function get_text_results(){
		$text_results = $this->language->get('text_results', $this->database->getFrom(), $this->database->getTo(), $this->database->getTotal());
		return $text_results;
	}

	function get_pagination(){
		$page_data = array();
		for ($i = 1; $i <= $this->get_pages(); $i++) {
			$page_data[] = array(
				'text'  => $this->language->get('text_pages', $i, $this->get_pages()),
				'value' => $i
			);
		}
		return $page_data;
	}

	function get_pages(){
		$pages = $this->database->getpages();
		return $pages;
	}

	function get_last_id(){
		$result = $this->database->getLastId();
		return $result;
	}

	function get_currencies(){
		$results = $this->database->getRows("select * from currency where status = '1'");
		return $results;
	}

	function check_currencies(){
		$result = $this->database->getRow("SELECT count(*) AS total FROM bank_account WHERE currency = '" . $this->request->gethtml('currency', 'post') . "' OR currency = '0'");
		return $result;
	}

	function check_allCurrencies(){
		$result = $this->database->getRow("SELECT count(*) AS total FROM bank_account");
		return $result;
	}
}
?>

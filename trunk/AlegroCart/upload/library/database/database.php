<?php

define('E_DB_CONN','Error: Could not make a database connection using %s@%s');
define('E_DB_SELECT','Error: Could not select database %s');
define('E_DB_QUERY','Error: %s<br />Error No: %s<br />%s');

class Database {   
	var $connection;
	var $result;
	var $pages;
  	var $total;
  	var $from;
  	var $to;
	
	function __construct(&$locator) {	
  		$this->config =& $locator->get('config');
		$this->cache  =& $locator->get('cache');
		$this->mail     =& $locator->get('mail');
  	}

  	function connect($server, $username, $password, $database) {
		if (!$this->connection = mysql_connect($server, $username, $password)) {
			$this->SQL_handler(sprintf(E_DB_CONN,$username,$server));
      		exit;
    	}

    	if (!mysql_select_db($database, $this->connection)) {
			$this->SQL_handler(sprintf(E_DB_SELECT,$database));
      		exit;
    	}
		
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
		mysql_set_charset('utf8');
		mysql_query('set @@session.sql_mode="MYSQL40"');
  	}
    
  	function query($sql) {
		$this->result = mysql_query($sql);
		if ($this->result) { return $this->result; }
		
		//exit(sprintf(E_DB_QUERY,mysql_error(),mysql_errno(),$sql));
		$this->SQL_handler(sprintf(E_DB_QUERY,mysql_error(),mysql_errno(),$sql));
		//echo sprintf(E_DB_QUERY,mysql_error(),mysql_errno(),$sql);
  	}

	function error($sql='') {
		if (mysql_error()) {
			return 'SQL Error No: ' . mysql_errno() . '<br />MySQL Error: ' . mysql_error();
		}
	}
	
	function parse() {
    	$args = func_get_args();
		$sql = array_shift($args);
		return vsprintf(str_replace('?', '%s', $sql), array_map('mysql_real_escape_string', $args));	
	}
		
  	function getRow($sql) {
    	$this->query($sql);
    	$row = mysql_fetch_assoc($this->result);
    	mysql_free_result($this->result);
    	return $row;
  	}

  	function getRows($sql) {
		if (func_num_args()) { $this->query(implode(func_get_args(), ', ')); }
		else { $this->query($sql); }
		
    	$rows = array();

    	while (is_resource($this->result) && $row = mysql_fetch_assoc($this->result)) { $rows[] = $row; }
		if(is_resource($this->result)){
			mysql_free_result($this->result);
		}
    	return $rows;
  	}
	
	function countRows() {
    	$this->query(implode(func_get_args(), ', '));
		return mysql_num_rows($this->result);
	}
	
  	function countAffected() {
    	return mysql_affected_rows($this->connection);
  	}

  	function getLastId() {
    	return mysql_insert_id($this->connection);
  	}
		  
  	function cache($key, $sql) {
    	if ($this->config->get('config_cache_query')) {
      		if (!$result = $this->cache->get($key)) {
	    		$result = $this->getRows($sql);
				$this->cache->set($key, $result);
      		}
    	} else {
      		$result = $this->getRows($sql);
    	}
    	return ($result);
  	}

	function splitQuery($sql, $page = '1', $max_rows = '20' , $max_results = '0') {	
    	$count = $this->getRow(preg_replace(array('/select(.*)from /As', '/order by (.*)/'), array('select count(*) as total from ', ''), $sql, 1));
		
		if(($max_results != '0') && ($max_results < $count['total'])){
		  $count['total'] = $max_results;
		}
		
    	$pages = ceil($count['total'] / (int)$max_rows);

    	if (!$page) { $page = 1; }

    	$offset = ((int)$max_rows * ($page - 1));

		if(($max_results != '0') && (($page*$max_rows)>$max_results)) {
			$sql .= " limit " . (int)$offset . ", " . (int)($max_results-$offset);
		} else{
			$sql .= " limit " . (int)$offset . ", " . (int)$max_rows;
		}
		
		$this->pages = (int)(($pages > 0) ? $pages : '1');
    	$this->total = (int)$count['total']; 
    	$this->from  = (int)(($offset > 0 || $count['total'] > 0) ? $offset+1 : '0');

    	if ($count['total'] < $max_rows) {
      		$this->to = (int)$count['total'];
    	}  elseif ($this->pages == $page) {
      		$this->to = (int)($offset + $max_rows - ($offset + $max_rows - $count['total']));
    	} else {
      		$this->to = (int)($offset + $max_rows);
    	}

    	return $sql;
  	}
  
  	function getPages() {
    	return $this->pages;
  	}
  
  	function getTotal() {
    	return $this->total;
  	}
  
  	function getFrom() {
    	return $this->from;
  	}
   
  	function getTo() {
    	return $this->to;
  	}

	function import($file) {
		if ($sql=file($file)) {
			$query = '';
			foreach($sql as $line) {
				if ((substr(trim($line), 0, 2) == '--') || (substr(trim($line), 0, 1) == '#')) { 
					$line=''; 
				}
				if (!empty($line)) {
					$query .= $line;
					if (strstr($query,'ALTER TABLE') == TRUE){
						$query = trim($query).' ';
					}
					if (preg_match('/;\s*$/', $query)){
						if(preg_match('/^ALTER TABLE (.+?) ADD (.+?) /',$query,$matches)){
							if (mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM %s LIKE '%s'",$matches[1],str_replace('`','',$matches[2])))) > 0){
								$query='';
							}
						}
						if(preg_match('/^ALTER TABLE (.+?) DROP (.+?) /',$query,$matches)){
							$matches[2] = str_replace(';','',$matches[2]);
							if (mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM %s LIKE '%s'",$matches[1],str_replace('`','',$matches[2])))) == NULL){
								$query = '';
							}
						}
						if((strlen($query) > 3) && (preg_match('/;\s*$/', $line))){
							if (!mysql_query($query)) { $this->SQL_handler(sprintf(E_DB_QUERY,mysql_error(),mysql_errno(),$sql)); }
							$query = '';
						}
					}
				}	
			}
		}
	}

	function export() {
		mysql_query('set @@session.sql_mode=""');
		$output = '';
		$sql = "SHOW TABLES FROM `" . DB_NAME ."`";
		$list_tables =  mysql_query($sql);
		while ($row = mysql_fetch_row($list_tables)) {
			$output .= '#' . "\n" . '# TABLE STRUCTURE FOR: `' . $row[0] . "`\n" . '#' . "\n\n";
			$output .= 'DROP TABLE IF EXISTS `' . $row[0] . '`;' . "\n";
			$create_table = mysql_query("show create table `" . DB_NAME . "`.`" . $row[0] . "`");
			$table_sql    = mysql_fetch_row($create_table);
			$output .= trim($table_sql[1]) . ';' . "\n\n";
			$results = $this->getRows("select * from `" . $row[0] . "`");
			foreach ($results as $result) {
				$fields = '';
				foreach (array_keys($result) as $value) {
					$fields .= '`' . $value . '`, ';
				}
				$values = '';
				foreach (array_values($result) as $value) {
					$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
					$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
					$value = str_replace('\\', '\\\\',	$value);
					$value = str_replace('\'', '\\\'',	$value);
					$value = str_replace('\\\n', '\n',	$value);
					$value = str_replace('\\\r', '\r',	$value);
					$value = str_replace('\\\t', '\t',	$value);			
					$values .= '\'' . $value . '\', ';
				}
				$output .= 'INSERT INTO `' . $row[0] . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
			}
			$output .= "\n\n";
		}
		return $output;
	}
	function SQL_handler($error){
		$this->initailize_handler();
		if($this->log_file){
			$this->log_error_msg($error);
		}
		if ($this->config->get('error_email_status') ) {
			if($this->email){
				$this->send_error_msg($error);
			}
		}
		if($this->show_developer && preg_match("/^$this->ip$/i", $_SERVER['REMOTE_ADDR'])){
			$this->sql_msg_developer($error);
		}
		
	}
	function send_error_msg($error){
		$error = str_replace(array('<br />', '<br>'), "\n", $error);
		$message = "MySQL ". $error . "\n" ;
		$message .= isset($_SERVER['REQUEST_URI']) ? 'Path: '. @$_SERVER['REQUEST_URI'] . "\n" : "";
		$message .= isset($_SERVER['QUERY_STRING']) ? 'Query String: ' . @$_SERVER['QUERY_STRING'] . "\n" : "";
		$message .= isset($_SERVER['HTTP_REFERER']) ? 'HTTP Referer: ' . @$_SERVER['HTTP_REFERER'] . "\n" : "";
		$message .= 'IP:' . $_SERVER['REMOTE_ADDR'] . ' Remote Host:' . (isset($_SERVER['REMOTE_HOST']) ? @$_SERVER['REMOTE_HOST'] : $this->nslookup($_SERVER['REMOTE_ADDR'])) . "\n";
		$message .= "log: ".print_r( $this->log_message, true)."\n";
		$message .= "##################################################\n\n";
		
		$this->email_sent = false;
		
		$this->mail->setTo($this->email);
		$this->mail->setFrom($this->config->get('config_email'));
		$this->mail->setSender(HTTP_BASE);
		$this->mail->setSubject('ERROR');
		$this->mail->setText($message);
		$this->mail->send();
		
		$this->email_sent = true;
	}
	function log_error_msg($error){
		$error = str_replace(array('<br />', '<br>'), "\n", $error);
		$message =  "time: ".date("j-m-d H:i:s (T)", time())."\n";
		$message .= "MySQL ". $error . "\n" ;
		$message .= isset($_SERVER['REQUEST_URI']) ? 'Path: '. @$_SERVER['REQUEST_URI'] . "\n" : "";
		$message .= isset($_SERVER['QUERY_STRING']) ? 'Query String: ' . @$_SERVER['QUERY_STRING'] . "\n" : "";
		$message .= isset($_SERVER['HTTP_REFERER']) ? 'HTTP Referer: ' . @$_SERVER['HTTP_REFERER'] . "\n" : "";
		$message .= 'IP:' . $_SERVER['REMOTE_ADDR'] . ' Remote Host:' . (isset($_SERVER['REMOTE_HOST']) ? @$_SERVER['REMOTE_HOST'] : $this->nslookup($_SERVER['REMOTE_ADDR'])) . "\n";
		$message .= "##################################################\n\n";
		if (!$fp = fopen($this->log_file, 'a+')){ 
			$this->log_message = "Could not open/create file: $this->log_file to log error."; $log_error = true;
		}
		if (!fwrite($fp, $message)){
			$this->log_message = "Could not log error to file: $this->log_file. Write Error."; $log_error = true;
		}
		if(!$this->log_message){
			$this->log_message = "Error was logged to file: $this->log_file.";
		}
		fclose($fp); 
	}
	function sql_msg_developer($error){
		$color='red';
		$message = "<span style='color:$color;font-size: 12px'>";
		$message .= "MySQL ". $error . "<br>\n" ;
		$message .= isset($_SERVER['REQUEST_URI']) ? 'Path: '. $_SERVER['REQUEST_URI']."<br>\n" : "";
		$message .= "---------------------------------------------------<br>\n";
		$message .= "</span>";
		echo $message;
	}
	function initailize_handler(){
		$this->ip = $this->config->get('error_developer_ip') ? $this->config->get('error_developer_ip') : $_SERVER['REMOTE_ADDR'];
		$this->show_developer = $this->config->get('error_show_developer') ? TRUE : FALSE;
		$this->email = $this->config->get('config_error_email') ? $this->config->get('config_error_email') : $this->config->get('config_email');
		$log_path = DIR_BASE . 'logs' . D_S . 'error_log' . D_S;
		if (is_writable($log_path)){
			$this->log_file = $log_path . 'error-' . date("Ymd") . '.txt';
		} else {
			$this->log_file = FALSE;
		}
		$this->log_message = NULL;
		$this->email_sent = FALSE;
	}
	function nslookup($ip) {
		$host_name = gethostbyaddr($ip);
		return $host_name;
	}
}
?>

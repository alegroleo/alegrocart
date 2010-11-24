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
  	}

  	function connect($server, $username, $password, $database) {
		if (!$this->connection = mysql_connect($server, $username, $password)) {
      		exit(sprintf(E_DB_CONN,$username,$server));
    	}

    	if (!mysql_select_db($database, $this->connection)) {
      		exit(sprintf(E_DB_SELECT,$database));
    	}
		
		mysql_query('set character set utf8');
		mysql_query('set @@session.sql_mode="MYSQL40"');
  	}
    
  	function query($sql) {
		$this->result = mysql_query($sql);
		if ($this->result) { return $this->result; }
		exit(sprintf(E_DB_QUERY,mysql_error(),mysql_errno(),$sql));
		
  	}

	function error($sql='') {
		if (mysql_error()) { return sprintf(E_DB_QUERY,mysql_error(),mysql_errno(),$sql); }
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

    	while ($row = mysql_fetch_assoc($this->result)) { $rows[] = $row; }

    	mysql_free_result($this->result);
	
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
							if (!mysql_query($query)) { exit(sprintf(E_DB_QUERY,mysql_error(),mysql_errno(),$sql)); }
							$query = '';
						}
					}
				}	
			}
		}
	}

	function export() {
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
}
?>

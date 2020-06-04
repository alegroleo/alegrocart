<?php

class Database {
	var $mysqli;  //the object
	var $result;

	function connect($hostname, $username, $password, $database) {
		$this->mysqli = new mysqli($hostname, $username, $password, $database);

		if ($this->mysqli->connect_errno) {
			echo "Error MySQLi: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error;
			exit;
		}
		$this->mysqli->set_charset('utf8');
	}
	function disconnect() {
		$this->mysqli->close();
	}
	function clearSql($sql) {
		$sql = trim($sql);
		return $this->mysqli->real_escape_string($sql);
	}
	function runQuery($sql) {
		$result = $this->mysqli->query($sql);
		if ($result) {
			return $result;
		}
	}
	function import_file($file) {
		$quotes = array("'","`");
		if ($sql=file($file)) {
			$query = '';
			foreach($sql as $line) {
				if ((substr(trim($line), 0, 2) == '--') || (substr(trim($line), 0, 1) == '#')){
					$line='';
				}
				if (!empty($line)) {
					$query .= $line;
					if (strstr($query,'ALTER TABLE') == TRUE){
						$query = trim($query).' ';
					}
					if (preg_match('/;\s*$/', $query)){
						if(preg_match('/^ALTER TABLE (.+?) ADD KEY (.+?) /',$query,$matches)){
							$addkey = @$this->runQuery(sprintf("SHOW KEYS FROM `%s` WHERE Key_name='%s'",str_replace($quotes,'',$matches[1]),str_replace($quotes,"",$matches[2])));
							if ($addkey->num_rows > 0){
								$query='';
							}
						}
						if(preg_match('/^ALTER TABLE (.+?) ADD (.+?) /',$query,$matches)){
							$add = @$this->runQuery(sprintf("SHOW COLUMNS FROM `%s` LIKE '%s'",str_replace($quotes,'',$matches[1]),str_replace($quotes,'',$matches[2])));
							if ($add->num_rows > 0){
								$query='';
							}
						}
						if(preg_match('/^ALTER TABLE (.+?) DROP (.+?) /',$query,$matches)){
							$matches[2] = str_replace(';','',$matches[2]);
							$drop = @$this->runQuery(sprintf("SHOW COLUMNS FROM `%s` LIKE '%s'",str_replace($quotes,'',$matches[1]),str_replace($quotes,'',$matches[2])));
							if ($drop->num_rows == NULL){
								$query = '';
							}
						}
						if(preg_match('/^ALTER TABLE (.+?) CHANGE (.+?) /',$query,$matches)){
							$matches[2] = str_replace(';','',$matches[2]);
							$change = @$this->runQuery(sprintf("SHOW COLUMNS FROM `%s` LIKE '%s'",str_replace($quotes,'',$matches[1]),str_replace($quotes,'',$matches[2])));
							if ($change->num_rows == NULL){
								$query = '';
							}
						}
						if((strlen($query) > 3) && (preg_match('/;\s*$/', $line))){
							$this->runQuery($query);
							$query = '';
						}
					}
				}
				echo '<div class="error">'.$this->mysqli->error.'</div>';
			}
		}
	}
}
?>

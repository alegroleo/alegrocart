<?php
Class GenerateSEO {
	function __construct(&$locator){
		$this->locator  =& $locator;
		$this->database =& $locator->get('database');
	}
	
	function _generate_url_alias($select_sql, $query, $query_id_array, $alias_id_array, $path = FALSE) {
		$results = $this->database->getRows($select_sql);
		foreach ($results as $result) {
			$query_path = $query; //build query path
			foreach ($query_id_array as $key => $value) {
				$query_path = str_replace('{'.$key.'}', $result[$value], $query_path);
			}
			$alias = ""; //build alias
			$alias .= $path ? $path : "";
			foreach ($alias_id_array as $key => $value) {
				$alias .= $result[$value] . " ";
			}
			$alias = $this->clean_alias($alias);
			$this->_insert_url_alias($query_path, $alias.'.html');//insert alias
		}
	}
	
	function clean_alias($alias){
		$alias = trim($alias);
		$alias = str_replace('.', '', $alias);
		$alias = str_replace(',', '', $alias);
		$alias = str_replace(';', '', $alias);
		$alias = str_replace(':', '', $alias);
		$alias = str_replace(';', '', $alias);
		$alias = str_replace('\'', '', $alias);
		$alias = str_replace('"', '', $alias);
		$alias = str_replace('&quot','',$alias);
		$alias = str_replace('%', '', $alias);
		$alias = str_replace('!', '', $alias);
		$alias = str_replace('$', '', $alias);
		$alias = str_replace('&amp', 'and', $alias);
		$alias = str_replace('&', 'and', $alias);
		$alias = str_replace('/', '-', $alias);
		$alias = str_replace('(', '', $alias);
		$alias = str_replace(')', '', $alias);
		$alias = str_replace('=', '', $alias);
		$alias = str_replace('?', '', $alias);
		$alias = str_replace('^', '', $alias);
		$alias = str_replace('@', '', $alias);
		$alias = str_replace('#', '', $alias);
		$alias = str_replace('*', '', $alias);
		$alias = str_replace(' ', '-', $alias);
		$alias = $this->_removeaccents($alias);
		$alias = strtolower($alias);
        return $alias;
    }
	
	function _insert_url_alias($query, $alias) {
		$insert_sql = "replace into url_alias set query = '?', alias = '?'";
		$this->database->query($this->database->parse($insert_sql, $query, $alias));
	}
	
	function _check_column_exists($table, $col) {

		if (mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM %s LIKE '%s'",$table,$col))) > 0) { return true; }
	}

	function _removeaccents($string){
		$string = utf8_decode($string);
		$txt = strtr($string,
			"\xe1\xc1\xe0\xc0\xe2\xc2\xe4\xc4\xe3\xc3\xe5\xc5".
			"\xaa\xe7\xc7\xe9\xc9\xe8\xc8\xea\xca\xeb\xcb\xed".
			"\xcd\xec\xcc\xee\xce\xef\xcf\xf1\xd1\xf3\xd3\xf2".
			"\xd2\xf4\xd4\xf6\xd6\xf5\xd5\x8\xd8\xba\xf0\xfa".
			"\xda\xf9\xd9\xfb\xdb\xfc\xdc\xfd\xdd\xff\xe6\xc6\xdf",
			"aAaAaAaAaAaAacCeEeEeEeEiIiIiIiInNoOoOoOoOoOoOoouUuUuUuUyYyaAs");
		return $txt;
	}
}
?>
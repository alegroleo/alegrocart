<?php

define('CACHE_SEP','.');
define('CACHE_PREFIX','cache'.CACHE_SEP);

class Cache {

    var $expire = 3600;

	function __construct() {
        $files = glob(DIR_CACHE.CACHE_PREFIX.'*');
        if (!$files) return;
		foreach ($files as $file) {
			$array = explode(CACHE_SEP, basename($file));
			if ($array[2] < time()) { @unlink($file); }
		}
    }

    function set($key, $value) {
		$key = preg_replace('/[^A-Z0-9\._-]/i', '', $key);
        $this->delete($key);
        $fh = fopen(DIR_CACHE.CACHE_PREFIX.$key.CACHE_SEP.(time() + $this->expire), 'a');
        fwrite($fh, serialize($value));
        fclose($fh);
    }

    function get($key) {
		$key = preg_replace('/[^A-Z0-9\._-]/i', '', $key);
        $files = glob(DIR_CACHE.CACHE_PREFIX.$key.CACHE_SEP.'*');
        if (!$files) return;
		foreach ($files as $file) {
			if(filesize($file)>0) {
				$fh = fopen($file, 'r');
				$cache = fread($fh, filesize($file));
				fclose($fh);
				return  unserialize($cache);
			}
		}
    }

    function delete($key) {
		$key = preg_replace('/[^A-Z0-9\._-]/i', '', $key);
        $files = glob(DIR_CACHE.CACHE_PREFIX.$key.'*'.CACHE_SEP.'*');
        if (!$files) return;
		foreach ($files as $file) { @unlink($file); }
    }
}
?>
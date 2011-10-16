<?php //Date Time AlegroCart
class Dates{
	var $codes = array();
	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->language =& $locator->get('language');
		$this->codes = $this->getCodes();
	}
	
	function getDate($date_format){
		$lang_keys = array('a','A','D','F','l','M','S');
		$todays_date = array();
		$formats  = array(); 
		$i = 0;
		while ($i < strlen($date_format)){
			$formats[] = substr($date_format,$i,1);
			$i ++;
		}
		foreach($formats as $key => $format){
			if(in_array($format, $lang_keys)){
				$code = $this->codes[$format];
				if($format == 'a' || $format == 'A'){
					$text = 'text_' . ($format == 'a' ? 'l' : 'u') .strtolower($code);
				} else {
					$text = 'text_' . strtolower($code);
				}
				if($this->language->check($text)){
					$todays_date[$key] = $this->language->get($text);
				} else {
					$todays_date[$key] = $code;
				}
			} else if(isset($this->codes[$format])){
				$todays_date[$key] = $this->codes[$format];
			} else {
				$todays_date[$key] = $format;
			}
		}
		
		return implode($todays_date);
	}
	
	function getCodes(){
		$formats = array('a','A','B','c','d','D','e','F','g','G','h','H','i','I','j','l','L','m','M','n','o','O','r','s','S','t','T','U','w','W','y','Y','z','Z');
		$codes = array();
		foreach($formats as $format){
			$codes[$format] = date($format);
		}
		return $codes;
	}
}
?>
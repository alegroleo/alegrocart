<?php //Library Currency AlegroCart
class Currency {
  	var $code;
  	var $currencies = array();
    public $fromCurr = 'CAD';
    public $toCurr = 'USD';  
  	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');
		$this->language =& $locator->get('language');
		$this->cache    =& $locator->get('cache');
			
   		$results = $this->database->cache('currency', "select * from currency where status = '1' order by title");

    	foreach ($results as $result) {
      		$this->currencies[$result['code']] = array(
        		'currency_id'   => $result['currency_id'],
        		'title'         => $result['title'],
        		'symbol_left'   => $result['symbol_left'],
        		'symbol_right'  => $result['symbol_right'],
        		'decimal_place' => $result['decimal_place'],
        		'value'         => $result['value'],
				'status'        => $result['status'],
				'lock_rate'     => $result['lock_rate'],
				'date_modified' => $result['date_modified']
      		); 
    	}
  
    	if (array_key_exists($this->session->get('currency'), $this->currencies)) {
      		$this->set($this->session->get('currency'));
    	} elseif (array_key_exists($this->request->get('currency', 'cookie'), $this->currencies)) {
      		$this->set($this->request->get('currency', 'cookie'));
    	} else {
      		$this->set($this->config->get('config_currency'));
    	}
  	}
	
  	function set($currency) {
    	$this->code = $currency;

    	if ((!$this->session->has('currency')) || ($this->session->get('currency') != $currency)) {
      		$this->session->set('currency', $currency);
    	}

    	if ((!$this->request->get('currency', 'cookie')) || ($this->request->get('currency', 'cookie') != $currency)) {
	  		setcookie('currency', $currency, time() + 60 * 60 * 24 * 30, '/', $_SERVER['HTTP_HOST']);
    	}
		if ($this->config->get('config_currency') != $currency){
			$this->update_currency($currency);
		}
  	}

	function update_currency($currency){
		If ($this->currencies[$currency]['date_modified'] < date('Y-m-d H:i:s', time() - 86400) && !$this->currencies[$currency]['lock_rate']){
			$new_rate = $this->currency_converter(($this->currencies[$this->config->get('config_currency')]['value'] + $this->config->get('config_currency_surcharge')),$this->config->get('config_currency'),$currency);  // Format 1, From, To
			If ($new_rate > 0){
				$this->currencies[$currency]['value'] = $new_rate;
				$this->cache->delete('currency');
				$this->database->query("update `currency` set value ='".$new_rate."', date_modified = now() where code = '".$currency."'");
			}			
		}
	}
	
	function currency_converter($amount, $from, $to){
		$this->amount = (float)($amount > 0) ? (float)($amount) : 1;
		if(trim($to) != ''){$this->toCurr = trim($to);}
		if(trim($from) != ''){$this->fromCurr = trim($from);}	
		return $this->getUpdate();
	}
	
	function getUpdate() {
        $returnHtml = array();
        $page = 'http://www.google.com/finance/converter?a='.$this->amount.'&from='.$this->fromCurr.'&to='. $this->toCurr;
 		$returnRawHtml = @file_get_contents( $page);
        preg_match_all('/\<span class=bld\>(.*)\<\/span\>/Uis',$returnRawHtml,$returnHtml,PREG_PATTERN_ORDER); // Mine
        if (isset($returnHtml[0][0])) 
        {
          $ConversionRate = strip_tags($returnHtml[1][0]);
          return (float)$ConversionRate;
        }
        else {
            return false;
        }
    }

  	function format($number, $currency = NULL, $value = NULL, $format = TRUE) {
        if (!$currency) { $currency = $this->code; }

        $symbol_left   = $this->currencies[$currency]['symbol_left'];
        $symbol_right  = $this->currencies[$currency]['symbol_right'];
        $decimal_place = $this->currencies[$currency]['decimal_place'];

        if (!$value) { $value = $this->currencies[$currency]['value']; }

        if ($value) { $value = $number * $value; }
        else { $value = $number; }

    	$string = '';

    	if (($symbol_left) && ($format)) {
      		$string .= $symbol_left;
    	}
		
    	$string .= number_format(round($value, $decimal_place), $decimal_place, (($format) ? $this->language->get('decimal_point') : '.'), (($format) ? $this->language->get('thousand_point') : NULL));

    	if (($symbol_right) && ($format)) {
      		$string .= $symbol_right;
    	}

    	return $string;
  	}
	
  	function getCode() {
    	return $this->code;
  	}
 
  	function getValue($currency) {
    	return (isset($this->currencies[$currency]) ? $this->currencies[$currency]['value'] : NULL);
  	}
    
  	function has($currency) {
    	return isset($this->currencies[$currency]);
  	}

}
?>

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
    	} elseif ($detected_currency = $this->detect()) {
		$this->set($detected_currency);
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
	  		setcookie('currency', $currency, time() + 60 * 60 * 24 * 30, '/', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']);
    	}
		if ($this->config->get('config_currency') != $currency){
			$this->update_currency($currency);
		}
  	}

	private function update_currency($currency){
        $currency = preg_replace('#[^a-z]#i', '',$currency);
		If (isset($this->currencies[$currency]) && $this->currencies[$currency]['date_modified'] < date('Y-m-d H:i:s', time() - 86400) && !$this->currencies[$currency]['lock_rate']){
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
		
    	$string .= number_format(round($value, $decimal_place), $decimal_place, (($format) ? $this->language->get('decimal_point') : '.'), (($format) ? $this->language->get('thousand_point') : ''));

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

	function detect() {
	$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$top_level_domain = substr($host, strrpos($host,'.') + 1);

	$domains=array(
			'ad'=>'EUR',
			'ae'=>'AED',
			'af'=>'AFA',
			'ag'=>'XCD',
			'ai'=>'XCD',
			'al'=>'ALL',
			'am'=>'AMD',
			'an'=>'ANG',
			'ao'=>'AOK',
			'aq'=>'',
			'ar'=>'ARP',
			'as'=>'EUR',
			'at'=>'EUR',
			'au'=>'AUD',
			'aw'=>'ANG',
			'az'=>'AZM',
			'ba'=>'BAK',
			'bb'=>'BBD',
			'bd'=>'BDT',
			'be'=>'EUR',
			'bf'=>'XOF',
			'bg'=>'BGL',
			'bh'=>'BHD',
			'bi'=>'BIF',
			'bj'=>'XOF',
			'bm'=>'BMD',
			'bn'=>'BND',
			'bo'=>'BOB',
			'br'=>'BRR',
			'bs'=>'BSD',
			'bt'=>'INR',
			'bv'=>'NOK',
			'bw'=>'BWP',
			'by'=>'BYR',
			'bz'=>'BZD',
			'ca'=>'CAD',
			'cc'=>'AUD',
			'cf'=>'XAF',
			'cg'=>'XAF',
			'ch'=>'CHF',
			'ci'=>'XOF',
			'ck'=>'ZND',
			'cl'=>'CLP',
			'cm'=>'XAF',
			'cn'=>'CNY',
			'co'=>'COP',
			'cr'=>'CRC',
			'cs'=>'CSK',
			'cu'=>'CUP',
			'cv'=>'CVE',
			'cx'=>'AUD',
			'cy'=>'CYP',
			'cz'=>'CSK',
			'de'=>'EUR',
			'dj'=>'DJF',
			'dk'=>'DKK',
			'dm'=>'XCD',
			'do'=>'DOP',
			'dz'=>'DZD',
			'ec'=>'ECS',
			'ee'=>'EUR',
			'eg'=>'EGP',
			'eh'=>'MAD',
			'er'=>'ETB',
			'es'=>'EUR',
			'et'=>'ETB',
			'fi'=>'EUR',
			'fj'=>'FJD',
			'fk'=>'FKP',
			'fm'=>'USD',
			'fo'=>'DKK',
			'fr'=>'EUR',
			'fx'=>'EUR',
			'ga'=>'XAF',
			'gb'=>'GBP',
			'gd'=>'XCD',
			'ge'=>'GEL',
			'gf'=>'EUR',
			'gh'=>'GHC',
			'gi'=>'GIP',
			'gl'=>'DKK',
			'gm'=>'GMD',
			'gn'=>'GNF',
			'gp'=>'EUR',
			'gq'=>'XAF',
			'gr'=>'EUR',
			'gs'=>'GBP',
			'gt'=>'GTQ',
			'gu'=>'USD',
			'gw'=>'XOF',
			'gy'=>'GYD',
			'hk'=>'HKD',
			'hm'=>'AUD',
			'hn'=>'HNL',
			'hr'=>'HRK',
			'ht'=>'HTG',
			'hu'=>'HUF',
			'id'=>'IDR',
			'ie'=>'EUR',
			'il'=>'ILS',
			'in'=>'INR',
			'int'=>'',
			'io'=>'USD',
			'iq'=>'IQD',
			'ir'=>'IRR',
			'is'=>'ISK',
			'it'=>'EUR',
			'jm'=>'JMD',
			'jo'=>'JOD',
			'jp'=>'JPY',
			'ke'=>'KES',
			'kg'=>'KGS',
			'kh'=>'KHR',
			'ki'=>'AUD',
			'km'=>'KMF',
			'kn'=>'XCD',
			'kp'=>'KPW',
			'kr'=>'KRW',
			'kw'=>'KWD',
			'ky'=>'KYD',
			'kz'=>'KZT',
			'la'=>'LAK',
			'lb'=>'LBP',
			'lc'=>'XCD',
			'li'=>'CHF',
			'lk'=>'LKR',
			'lr'=>'LRD',
			'ls'=>'LSL',
			'lt'=>'LTL',
			'lu'=>'EUR',
			'lv'=>'LVL',
			'ly'=>'LYD',
			'ma'=>'MAD',
			'mc'=>'EUR',
			'md'=>'MDL',
			'mg'=>'MGF',
			'mh'=>'USD',
			'mil'=>'USD',
			'mk'=>'MKD',
			'ml'=>'XOF',
			'mm'=>'MMK',
			'mn'=>'MNT',
			'mo'=>'MOP',
			'mp'=>'USD',
			'mq'=>'EUR',
			'mr'=>'MRO',
			'ms'=>'XCD',
			'mt'=>'MTL',
			'mu'=>'MUR',
			'mv'=>'MVR',
			'mw'=>'MWK',
			'mx'=>'MXP',
			'my'=>'MYR',
			'mz'=>'MZM',
			'na'=>'NAD',
			'nc'=>'XPF',
			'ne'=>'XOF',
			'net'=>'',
			'nf'=>'AUD',
			'ng'=>'NGN',
			'ni'=>'NIO',
			'nl'=>'EUR',
			'no'=>'NOK',
			'np'=>'NPR',
			'nr'=>'AUD',
			'nt'=>'',
			'nu'=>'NZD',
			'nz'=>'NZD',
			'om'=>'OMR',
			'pa'=>'PAB',
			'pe'=>'PEN',
			'pf'=>'XPF',
			'pg'=>'PGK',
			'ph'=>'PHP',
			'pk'=>'PKR',
			'pl'=>'PLZ',
			'pm'=>'EUR',
			'pn'=>'NZD',
			'pr'=>'USD',
			'pt'=>'EUR',
			'pw'=>'USD',
			'py'=>'PYG',
			'qa'=>'QAR',
			're'=>'EUR',
			'ro'=>'ROL',
			'ru'=>'RUR',
			'rw'=>'RWF',
			'sa'=>'SAR',
			'sb'=>'SBD',
			'sc'=>'SCR',
			'sd'=>'SDD',
			'se'=>'SEK',
			'sg'=>'SGD',
			'sh'=>'SHP',
			'si'=>'EUR',
			'sj'=>'NOK',
			'sk'=>'EUR',
			'sl'=>'SLL',
			'sm'=>'EUR',
			'sn'=>'XOF',
			'so'=>'SOS',
			'sr'=>'SRG',
			'st'=>'STD',
			'su'=>'RUR',
			'sv'=>'SVC',
			'sy'=>'SYP',
			'sz'=>'SZL',
			'tc'=>'USD',
			'td'=>'XAF',
			'tf'=>'EUR',
			'tg'=>'XOF',
			'th'=>'THB',
			'tj'=>'TJR',
			'tk'=>'NZD',
			'tm'=>'TMM',
			'tn'=>'TND',
			'to'=>'TOP',
			'tp'=>'IDR',
			'tr'=>'TRL',
			'tt'=>'TTD',
			'tv'=>'AUD',
			'tw'=>'TWD',
			'tz'=>'TZS',
			'ua'=>'UAH',
			'ug'=>'UGX',
			'uk'=>'GBP',
			'um'=>'USD',
			'us'=>'USA',
			'uy'=>'UYU',
			'uz'=>'UZS',
			'va'=>'EUR',
			'vc'=>'XCD',
			've'=>'VEB',
			'vg'=>'USD',
			'vi'=>'USD',
			'vn'=>'VND',
			'vu'=>'VUV',
			'wf'=>'XPF',
			'ws'=>'WST',
			'ye'=>'YER',
			'yt'=>'EUR',
			'yu'=>'YUN',
			'za'=>'ZAR',
			'zm'=>'ZMK',
			'zr'=>'ZRZ',
			'zw'=>'ZWD');

	if (isset($domains[$top_level_domain]))	{
		$currency= $domains[$top_level_domain];
		    if (isset($this->currencies[$currency])) {
	
			  return $currency;
		    }
	}
	return FALSE;
	}
}
?>
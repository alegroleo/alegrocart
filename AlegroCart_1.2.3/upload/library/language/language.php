<?php // AlegroCart

define('D_LANG','english');
define('E_LANG','Error: Could not load language data from %s!');
define('R_LANG','/^(%s)(;q=[0-9]\\.[0-9])?$/i');

class Language {
  	var $code;
  	var $languages = array();
  	var $data      = array();
	var $lang;
	var $expire = 2592000; // 60 * 60 * 24 * 30 (30 days)

  	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');

		$this->lang = strtolower(APP).'_language'; 

		$results = $this->database->cache('language', 'select * from language');

		if (!$results) { echo(sprintf(E_LANG,'database')); }

    	foreach ($results as $result) {
      		$this->languages[$result['code']] = array(
        		'language_id' => $result['language_id'],
        		'name'        => $result['name'],
        		'code'        => $result['code'],
				'directory'   => $result['directory'],
				'filename'    => $result['filename']
      		);
    	}
 		
    	if (array_key_exists($this->session->get($this->lang), $this->languages)) {
      		$this->set($this->session->get($this->lang));
    	} elseif (array_key_exists($this->request->get($this->lang, 'cookie'), $this->languages)) {
      		$this->set($this->request->get($this->lang, 'cookie'));
    	} elseif ($browser = $this->detect()) {
	    	$this->set($browser);
	  	} else {
        	$this->set($this->config->get('config_language'));
		}
		$this->load($this->languages[$this->code]['filename']);
  	}
	
	function set($language) {
    	$this->code = $language;
		
    	if ((!$this->session->has($this->lang)) || ($this->session->get($this->lang) != $language)) {
      		$this->session->set($this->lang, $language);
    	}

    	if ((!$this->request->get($this->lang, 'cookie')) || ($this->request->get($this->lang, 'cookie') != $language)) {	  
	  		setcookie($this->lang, $language, time() + $this->expire, '/', $_SERVER['HTTP_HOST']);
    	}
  	}
    
	function load($filename, $directory = DIR_LANGUAGE) {
		$_ = array();

		// Get the corrent default filename (eg: english.php or cart.php)
		$dfn = ($filename == $this->languages[$this->code]['directory'].'.php')?D_LANG.'.php':$filename;

		// Include the default language
		$dfile = $directory.D_LANG.DIRECTORY_SEPARATOR.$dfn;
		if (file_exists($dfile)) { include($dfile); }

		// Include the specified language
		$file = $directory.$this->languages[$this->code]['directory'].DIRECTORY_SEPARATOR.$filename;
		// Check it's not the same as the default, and it exists, then include
		if (($dfile != $file) && file_exists($file)) { include($file); }

		// We have no languages, exit
		if (empty($_)) { echo sprintf(E_LANG,$filename); }

        $this->data = array_merge($this->data, $_);

		$this->setCharset($this->get('charset'));
		$this->setLocale($this->get('locale'));
    }
  
  	function get($key) {
    	$args = func_get_args();
 
    	if (count($args) > 1) {
      		return vsprintf($this->get(array_shift($args)), $args);
    	} else {
      		return (isset($this->data[$key]) ? $this->data[$key] : $key);
    	}
  	}

  	function detect() {
    	if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) { return; }
		$browser_languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
  
		$languages = array(
			'af' => 'af|afrikaans',
			'ar-dz' => 'ar[-_]dz',
			'ar-bh' => 'ar[-_]bh',
			'ar-eg' => 'ar[-_]eg',
			'ar-iq' => 'ar[-_]iq',
			'ar-jo' => 'ar[-_]jo',
			'ar-kw' => 'ar[-_]kw',
			'ar-lb' => 'ar[-_]lb',
			'ar-ly' => 'ar[-_]ly',
			'ar-ma' => 'ar[-_]ma',
			'ar-om' => 'ar[-_]om',
			'ar-qa' => 'ar[-_]qa',
			'ar-sa' => 'ar[-_]sa',
			'ar-sy' => 'ar[-_]sy',
			'ar-tn' => 'ar[-_]tn',
			'ar-ae' => 'ar[-_]ae',
			'ar-ye' => 'ar[-_]ye',
			'ar' => 'ar([-_][[:alpha:]]{2})?|arabic',
			'as' => 'as|assamese',
			'az' => 'az|azeri',
			'be' => 'be|belarusian',
			'bg' => 'bg|bulgarian',
			'bn' => 'bn|bengali',
			'br' => 'pt[-_]br|brazilian portuguese',
			'ca' => 'ca|catalan',
			'cs' => 'cs|czech',
			'da' => 'da|danish',
			'de-at' => 'de[-_]at',
			'de-ch' => 'de[-_]ch',
			'de-li' => 'de[-_]li',
			'de-lu' => 'de[-_]lu',
			'de' => 'de([-_][[:alpha:]]{2})?|german',
			'div' => 'div|divehi',
			'el' => 'el|greek',
			'en-au' => 'en[-_]au',
			'en-bz' => 'en[-_]bz',
			'en-ca' => 'en[-_]ca',
			'en-gb' => 'en[-_]gb',
			'en-ie' => 'en[-_]ie',
			'en-jm' => 'en[-_]jm',
			'en-nz' => 'en[-_]nz',
			'en-ph' => 'en[-_]ph',
			'en-za' => 'en[-_]za',
			'en-tt' => 'en[-_]tt',
			'en-us' => 'en[-_]us',
			'en-zw' => 'en[-_]zw',
			'en' => 'en([-_][[:alpha:]]{2})?|english',
			'es-ar' => 'es[-_]ar',
			'es-bo' => 'es[-_]bo',
			'es-cl' => 'es[-_]cl',
			'es-co' => 'es[-_]co',
			'es-cr' => 'es[-_]cr',
			'es-do' => 'es[-_]do',
			'es-ec' => 'es[-_]ec',
			'es-sv' => 'es[-_]sv',
			'es-gt' => 'es[-_]gt',
			'es-hn' => 'es[-_]hn',
			'es-mx' => 'es[-_]mx',
			'es-ni' => 'es[-_]ni',
			'es-pa' => 'es[-_]pa',
			'es-py' => 'es[-_]py',
			'es-pe' => 'es[-_]pe',
			'es-pr' => 'es[-_]pr',
			'es-us' => 'es[-_]us',
			'es-uy' => 'es[-_]uy',
			'es-ve' => 'es[-_]ve',
			'es' => 'es([-_][[:alpha:]]{2})?|spanish',
			'et' => 'et|estonian',
			'eu' => 'eu|basque',
			'fa' => 'fa|farsi',
			'fi' => 'fi|finnish',
			'fo' => 'fo|faeroese',
			'fr-be' => 'fr[-_]be',
			'fr-ca' => 'fr[-_]ca',
			'fr-ch' => 'fr[-_]ch',
			'fr-lu' => 'fr[-_]lu',
			'fr-mc' => 'fr[-_]mc',
			'fr' => 'fr([-_][[:alpha:]]{2})?|french',
			'gd' => 'gd|gaelic',
			'gl' => 'gl|galician',
			'gu' => 'gu|gujarati',
			'he' => 'he|hebrew',
			'hi' => 'hi|hindi',
			'hr' => 'hr|croatian',
			'hu' => 'hu|hungarian',
			'hy' => 'hy|armenian',
			'id' => 'id|indonesian',
			'is' => 'is|icelandic',
			'it-ch' => 'it[-_]ch',
			'it' => 'it|italian',
			'ja' => 'ja|japanese',
			'ka' => 'ka|georgian',
			'kk' => 'kk|kazakh',
			'kn' => 'kn|kannada',
			'ko' => 'ko|korean',
			'kok' => 'kok|konkani',
			'kz' => 'kz|kyrgyz',
			'ls' => 'ls|slovenian',

			'lt' => 'lt|lithuanian',
			'lv' => 'lv|latvian',
			'mk' => 'mk|macedonian',
			'ml' => 'ml|malayalam',
			'mn' => 'mn|mongolian',
			'mr' => 'mr|marathi',
			'ms' => 'ms|malay',
			'mt' => 'mt|maltese',
			'ne' => 'ne|nepali',
			'nl-be' => 'nl[-_]be',
			'nl' => 'nl([-_][[:alpha:]]{2})?|dutch',
			'nb-no' => 'nb[-_]no',
			'nn-no' => 'nn[-_]no',
			'no' => 'no|norwegian',
			'or' => 'or|oriya',
			'pa' => 'pa|punjabi',
			'pl' => 'pl|polish',
			'pt-br' => 'pt[-_]br',
			'pt' => 'pt([-_][[:alpha:]]{2})?|portuguese',
			'rm' => 'rm|rhaeto',
			'ro-md' => 'ro[-_]md',
			'ro' => 'ro|romanian',
			'ru-md' => 'ru[-_]md',
			'ru' => 'ru|russian',
			'sa' => 'sa|sanskrit',
			'sb' => 'sb|sorbian',
			'sk' => 'sk|slovak',
			'sq' => 'sq|albanian',
			'sr' => 'sr|serbian',
			'sv-fi' => 'sv[-_]fi',
			'sv' => 'sv|swedish',
			'sw' => 'sw|swahili',
			'sx' => 'sx|sutu',
			'syr' => 'syr|syriac',
			'ta' => 'ta|tamil',
			'te' => 'te|telugu',
			'th' => 'th|thai',
			'tr' => 'tr|turkish',
			'tt' => 'tt|tatar',
			'uk' => 'uk|ukrainian',
			'ur' => 'us|urdu',
			'uz' => 'uz|uzbek',
			'vi' => 'vi|vietnamese',
			'tw' => 'zh[-_]tw|chinese traditional',
			'us' => 'us|united states',
			'xh' => 'xh|xhosa',
			'yi' => 'yi|yiddish',
			'zh-cn' => 'zh[-_]cn',
			'zh-hk' => 'zh[-_]hk',
			'zh-mo' => 'zh[-_]mo',
			'zh-sg' => 'zh[-_]sg',
			'zh-tw' => 'zh[-_]tw',
			'zh' => 'zh|chinese simplified',
			'zu' => 'zu|zulu'
		);

		foreach ($browser_languages as $browser_language) {
			foreach ($languages as $key => $value) {
				if (preg_match(sprintf(R_LANG,$value), $browser_language)) {
					if (isset($this->languages[$key])) {
					  return $key;
					}
				}
			}
		}

    	return FALSE;
	}

  	function getId($code=false) {
    	return $this->languages[$code?$code:$this->code]['language_id'];
  	}

  	function getCode() {
    	return $this->code;
  	}

	function setLocale($locale=0) {
		if ($locale && !is_array($locale) && strstr($locale,',')) $locale=explode(',',$locale);
		return setlocale(LC_ALL,$locale);
	}

	function setCharset($charset='UTF-8') {
		$charset=strtoupper($charset);
		if (function_exists('mb_language')) { //see http://www.php.net/mb_language
			if ($charset == 'ISO-2022-JP') { mb_language('ja'); }
			elseif ($charset == 'ISO-8859-1') { mb_language('en'); }
			else { mb_language('uni'); }
		}
		if (function_exists('mb_internal_encoding')) { mb_internal_encoding($charset); }
	}

	function formatDate($format,$time=false) {
		if (strstr($format,'%')) return ($time)?strftime($format,$time):strftime($format);
		return ($time)?date($format,$time):date($format);
	}

}
?>

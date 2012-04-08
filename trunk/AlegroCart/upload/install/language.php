<?php
define('DIR_LANG','install/language');
define('D_LANG','en');
define('E_LANG','Error: Could not load language data from %s!');
define('R_LANG','/^(%s)(;q=[0-9]\\.[0-9])?$/i');

class language {

var $lang      = array();
var $data      = array();
var $error;

function get_languages(){

$dir_handle = opendir(DIR_BASE.D_S.DIR_LANG);
  if ($dir_handle) {
	  while (false !== ($fname = readdir($dir_handle))) {
		  if (($fname != '.') && ($fname != '..')) {
			$this->langs[]  = substr($fname,0,-4);
			}
	  }
closedir($dir_handle);
  }
}

function check_default(){
  if (!in_array(D_LANG, $this->langs)) { $this->error= DIR_BASE.DIR_LANG.D_S.D_LANG. ".php was not found! (ensure you have uploaded it)";}
}

function load($filename='en') {
$directory = DIR_BASE.DIR_LANG.D_S;		
$_ = array();

		$dfile = $directory.D_LANG.'.php';
		include($dfile); 
		$file = $directory.$filename.'.php';
		if (($dfile != $file) && file_exists($file)) { include($file); }
		if (empty($_)) { echo sprintf(E_LANG,$filename); }
		$this->data = array_merge($this->data, $_);
}

function get($key) {
    	$args = func_get_args();
 
    	if (count($args) > 1) {
      		return vsprintf($this->get(array_shift($args)), $args);
    	} else {
		return $this->data[$key];
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
					if (in_array($key, $this->langs)) {
					  return $key;
					}
				}
			}
		}
	return FALSE;
	}
}
?>
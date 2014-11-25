<?php

function wildcardsearch ($searchstring,$language){
	$searchstring = trim($searchstring);
	if (strpos($searchstring,'%') !== 0) {
		if ((strlen($searchstring) > 1) && ($searchstring != $language->get('text_keywords'))){
			$wildstring = implode('%%',array_slice(preg_split('#\s#',$searchstring),0));
			$wildstring = "%$wildstring%";}
		else{$wildstring = $language->get('text_keywords');}
	} else {
		$wildstring = $searchstring;}
	return $wildstring;
}

function cleansearch ($searchstring){
	$searchstring = trim($searchstring);
	//$searchstring = html_entity_decode($searchstring);
	if (strpos($searchstring,'%') === 0){
		$cleanstring = implode(' ',array_slice(preg_split('#%%#',$searchstring),0));
		$cleanstring = substr($cleanstring,1,strlen($cleanstring)-2);
	}else{ $cleanstring = $searchstring;
	}
	return $cleanstring;
}

function formatedstring ($sourcestring, $lines){
	$paragraph = array("<p>","</p>");
	$sourcestring = str_replace($paragraph,"",$sourcestring);
	$desckey = 0;
	if ((strpos($sourcestring,'<hidden>')+8)< strlen($sourcestring)) {
		$desckey = true;
	} 
	if (strpos($sourcestring,'<hidden>')) {
		$short = substr($sourcestring,0,strpos($sourcestring,'<hidden>'));
	} else {
		$short = $sourcestring;
	}
	$strip = $short;
	//$short.= '<br>'; This can be included if you want line feed ending when displaying complete description
	$short = implode('<br>',array_slice(preg_split('#<br\s?\/?>#',$short),0,$lines));
	if ($lines > 1){
		if ((strlen(strip_tags($short)) < (strlen(strip_tags($sourcestring)))) && ($desckey == false)) {
			$short.= ' ...';
		}
		else if (strlen(strip_tags($short)) < strlen(strip_tags($strip))){
			$short.= ' ...';
		}
	}
	return $short;
}

function strippedstring ($sourcestring, $length){
	if ($length > 0) {
		$paragraph = array("<p>","</p>");
		$sourcestring = str_replace($paragraph,"",$sourcestring);
		if (strpos($sourcestring,'<hidden>')) {
			$sourcestring = substr($sourcestring,0,strpos($sourcestring,'<hidden>'));
		}
		$sourcestring = implode(' ',array_slice(preg_split('#<br\s?\/?>#',$sourcestring),0));
		$sourcestring = strip_tags($sourcestring);
		if (strlen($sourcestring) >= ($length-4)){
			$sourcestring = (substr(htmlspecialchars_decode($sourcestring),0,$length-4)) . ' ...';
		} else {
			$sourcestring = (substr(htmlspecialchars_decode($sourcestring),0,$length));
		}
	} else {
		$sourcestring = NULL;
	}
	return $sourcestring;
}

function plainstring ($sourcestring,$length){
	$paragraph = array("<p>","</p>");
	$sourcestring = str_replace($paragraph,"",$sourcestring);
	if (strpos($sourcestring,'<hidden>')) {$sourcestring = substr($sourcestring,0,strpos($sourcestring,'<hidden>'));}
	$desc = (substr(htmlspecialchars_decode($sourcestring),0,$length));
	if (strlen($desc) < (strlen($sourcestring)-8)) {$desc.= ' ....';}
	return $desc;
}
?>

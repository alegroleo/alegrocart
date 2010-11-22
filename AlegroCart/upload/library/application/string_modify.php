<?php

function wildcardsearch ($searchstring){
	$searchstring = trim($searchstring);
	If (strpos($searchstring,'%') !== 0) {
		If ((strlen($searchstring) > 1) && ($searchstring != "Keywords")){
			$wildstring = implode('%%',array_slice(preg_split('#\s#',$searchstring),0));
			$wildstring = "%$wildstring%";}
		else{$wildstring = "Re-enter Keywords";}
	} else {
		$wildstring = $searchstring;}
	return $wildstring;
}

function cleansearch ($searchstring){
	$searchstring = trim($searchstring);
	//$searchstring = html_entity_decode($searchstring);
	If (strpos($searchstring,'%') === 0){
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
	if ((strpos($sourcestring,'<hidden>')+8)< strlen($sourcestring)) {$desckey = true;} 
	if (strpos($sourcestring,'<hidden>')) {$short = substr($sourcestring,0,strpos($sourcestring,'<hidden>'));}
	else {$short = $sourcestring;}
	$strip = $short;
	//$short.= '<br>'; This can be included if you want line feed ending when displaying complete description
	$short = implode('<br>',array_slice(preg_split('#<br\s?\/?>#',$short),0,$lines));
	if ($lines > 1){
	if ((strlen(strip_tags($short)) < (strlen(strip_tags($sourcestring)))) && ($desckey == false)) {$short.= ' ...';}
	else if (strlen(strip_tags($short)) < strlen(strip_tags($strip))){$short.= ' ...';}
	}
return $short;
}

function strippedstring ($sourcestring, $length){
	$paragraph = array("<p>","</p>");
	$sourcestring = str_replace($paragraph,"",$sourcestring);
	if (strpos($sourcestring,'<hidden>')) {$sourcestring = substr($sourcestring,0,strpos($sourcestring,'<hidden>'));}
	$short = $sourcestring;
	$short = implode(' ',array_slice(preg_split('#<br\s?\/?>#',$short),0));
	$short = strip_tags($short);
	if (strlen($short) >= ($length-4)){
		//$short = substr($short,0,$length-4) . ' ...';
		$short = (substr(htmlspecialchars_decode($short),0,$length-4)) . ' ...';
	} else {   
		$short = (substr(htmlspecialchars_decode($short),0,$length));
	}
	return $short;
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
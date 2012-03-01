<?php
define ('PATH_BARCODE', DIR_IMAGE . 'barcode' . D_S);

class Barcode {

      var $bar_color=Array(0,0,0);
      var $bg_color=Array(255,255,255);
      var $text_color=Array(0,0,0);
      var $scale = 1;
      var $mode = "png";
      var $type;
      var $checksum;
      var $img_name='';

function check($code, $encoding){

		$even = true; 
		$esum = 0; 
		$osum = 0; 

		if ($this->get_length($code) < 11) return;

// get the barcode digits without the checksum as we will recalculate it

		if ($encoding == 'upc') {
		    
		    $code = substr($code,0,11);

		} elseif ($encoding == 'ean') {

		    $code = substr($code,0,12);

		} else {

		    return false;
		}

		if (preg_match("/[^0-9]/i",$code)) return;

// recalculate the checksum				
		for ($i = strlen($code)-1;$i>=0;$i--) {

			  if ($even) {$esum+=$code[$i];

			  } else { 

			  $osum+=$code[$i];

			  }
			  
			  $even=!$even;
		}

		$this->checksum = (10-((3*$esum+$osum)%10))%10;

// get the correct, full barcode
		$code .= $this->checksum;

		return $code;
}

function get_length ($code){
// we need it do get the encoding as it is not saved into the database.
// if length is 12-->UPC, if 13--> EAN
		return strlen(trim($code));
}


function href($filename) {
		if (@$_SERVER['HTTPS'] != 'on') {
	  		return HTTP_IMAGE . $filename;
		} else {
	  		return HTTPS_IMAGE . $filename;
		}	
}

function show($code) {
// get the full file name
		$this->img_name = $code . '.' . $this->mode;
// and the full path		
		$http_path = $this->href('barcode/' . $this->img_name);
		return $http_path;
}

// get the bars and the text position
function barcode_encode($code, $encoding){

$digits=array(3211,2221,2122,1411,1132,1231,1114,1312,1213,3112);
$mirror=array("000000","001011","001101","001110","010011","011001","011100","010101","010110","011010");
$guards=array("9a1a","1a1a1","a1a");

if ($this->get_length($code) < 11) return;

// we need the corrected barcode
$code=trim($this->check($code, $encoding));

// if encoding is upc we have to amend it
if ($encoding == 'upc') {
	      $code = '0'.$code;
}

// get the bars
$line=$guards[0];

	    for ($i=1;$i<13;$i++) {
	
		  $str=$digits[$code[$i]];
    
		  if ($encoding == 'ean') {
	
		      if ($i<7 && $mirror[$code[0]][$i-1]==1) $line.=strrev($str); else $line.=$str;
		
		  } else {
	
		      if ($i<7 && $i>1 && $mirror[$code[0]][$i-1]==1) $line.=strrev($str); else $line.=$str;
		  }

		  if ($i==6) $line.=$guards[1];
	    }

$line.=$guards[2];

// get text position depending on encoding
$pos=0;
$text="";

	  if ($encoding == 'ean') {
    
		for ($a=0;$a<13;$a++){
		if ($a>0) $text.=" ";
		$text.="$pos:12:{$code[$a]}";
		if ($a==0) $pos+=12;
		else if ($a==6) $pos+=12;
		else $pos+=7;
		}

	  } else {
 
		for ($a=1;$a<13;$a++){
		if ($a>1) $text.=" ";
		$text.="$pos:12:{$code[$a]}";
		if ($a==1) $pos+=20;
		else if ($a==6) $pos+=12;
		else if ($a==11) $pos+=18;
		else $pos+=7;
	  }
}

//we are ready, so cut the 0
if ($encoding == 'upc') {
	      $code = substr($code,1,12);
}

return array(
		"encoding" => $encoding,
		"bars" => $line,
		"text" => $text,
		"code" => $code
		);   
}


// create the barcode image using libgd
function barcode_outimage($text, $bars, $code, $encoding, $total_y = 0, $space = '') {
   
    /* set defaults */
    if ($this->scale<1) $this->scale=2;
    $total_y=(int)($total_y);
    if ($total_y<1) $total_y=(int)$this->scale * 60;
    if (!$space)
      $space=array('top'=>2*$this->scale,'bottom'=>2*$this->scale,'left'=>2*$this->scale,'right'=>2*$this->scale);
    
    /* count total width */
    $xpos=0;
    $width=true;
    for ($i=0;$i<strlen($bars);$i++){
	$val=strtolower($bars[$i]);
	if ($width){
	    $xpos+=$val*$this->scale;
	    $width=false;
	    continue;
	}
	if (preg_match("/[a-z]/", $val)){
	    /* tall bar */
	    $val=ord($val)-ord('a')+1;
	} 
	$xpos+=$val*$this->scale;
	$width=true;
    }

    /* allocate the image */
    $total_x=( $xpos )+($encoding == 'ean' ? 2 : 6 )*$space['right'];
    $xpos=$space['left'];
    
    $im=imagecreate($total_x, $total_y);
    /* create two images */
    $col_bg=ImageColorAllocate($im,$this->bg_color[0],$this->bg_color[1],$this->bg_color[2]);
    $col_bar=ImageColorAllocate($im,$this->bar_color[0],$this->bar_color[1],$this->bar_color[2]);
    $col_text=ImageColorAllocate($im,$this->text_color[0],$this->text_color[1],$this->text_color[2]);
    $height=round($total_y-($this->scale*10));
    $height2=round($total_y-$space['bottom']);


    /* paint the bars */
    $width=true;
    for ($i=0;$i<strlen($bars);$i++){
	$val=strtolower($bars[$i]);
	if ($width){
	    $xpos+=$val*$this->scale;
	    $width=false;
	    continue;
	}
	if (preg_match("/[a-z]/", $val)){
	    /* tall bar */
	    $val=ord($val)-ord('a')+1;
	    $h=$height2;
	} else $h=$height;
	imagefilledrectangle($im, $xpos, $space['top'], $xpos+($val*$this->scale)-1, $h, $col_bar);
	$xpos+=$val*$this->scale;
	$width=true;
    }

    /* write out the text */
    $chars=explode(" ", $text);
    reset($chars);
    while (list($n, $v)=each($chars)){
	if (trim($v)){
	    $inf=explode(":", $v);
	    $fontsize=$this->scale*($inf[1]/1.8);
	    $fontheight=$total_y-($fontsize/2.7)+2;
	    @imagettftext($im, $fontsize, 0, $space['left']+($this->scale*$inf[0])+2,
	    $fontheight, $col_text, DIR_FONTS.'FreeSansBold.ttf', $inf[2]);
	}
    }

    /* output the image */
    $this->type=strtolower($this->mode);
    if ($this->type=='jpg' || $this->type=='jpeg'){
	imagejpeg($im, PATH_BARCODE . $code . '.jpg');
    } else if ($this->type=='gif'){
	imagegif($im, PATH_BARCODE . $code . '.gif');
    } else {
	imagepng($im, PATH_BARCODE . $code . '.png');
    }

imagedestroy($im);

}

function create($code, $encoding) {
  
    $bars=$this->barcode_encode($code,$encoding);
    if (!$bars) return;
    $this->barcode_outimage($bars['text'],$bars['bars'], $bars['code'], $bars['encoding']);
}
}
?>
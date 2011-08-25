<?php
define ('PATH_MASK', DIR_IMAGE . 'mask' .  D_S);

class Mask{
	var $types=array('jpg','gif','jpeg','png');
	var $size_x = 200;
	var $size_y = 65;
	var $letters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	var $space_per_char = 0;
	var $mask = '';
	var $img_name = '';
		
	function __construct(&$locator){
		$this->config  =& $locator->get('config');
		$this->rand =& $locator->get('randomnumber');
	}

	function create_text($digit_total = 8){
		$i = 0;
		while ($i < $digit_total){
			$this->rand->uRand(0,25);
			$i ++;
		}
		$mask = array();
		foreach ($this->rand->RandomNumbers as $mykey){
			$mask[] .= $this->letters[$mykey];
		}
		$this->rand->clearrand();
		return $mask;
	}
	
	function captcha($char_length, $char_value = ''){
		$this->clean_files();
		if(!$char_value){
			$text_value = $this->create_text($char_length);
		} else {
			$text_value = str_split($char_value);
		}
		$this->mask = implode($text_value);
		$this->size_x = 38 * ($char_length ? $char_length : 8);
		$this->space_per_char = $this->size_x / ($char_length);
		$img = $this->create_image($text_value);
		$this->img_name = implode($this->create_text(12)) . '.jpg';
		imagejpeg($img, PATH_MASK . $this->img_name, 90);
		
		$http_path = $this->href('mask/' . $this->img_name);
		imagedestroy($img);
		return $http_path;
	}
	function create_image($char_value){
		//create canvas
		$img = imagecreatetruecolor($this->size_x + 10, $this->size_y);
		//set colors
		$background = imagecolorallocate($img, 255, 255, 255);
		$border = imagecolorallocate($img, 128, 128, 128);
		$colors[] = imagecolorallocate($img, 128, 64, 192);
		$colors[] = imagecolorallocate($img, 192, 64, 128);
		$colors[] = imagecolorallocate($img, 108, 192, 64);
		// fill background
		imagefilledrectangle($img, 1, 1, ($this->size_x +10) - 2, $this->size_y - 2,$background);
		imagerectangle($img, 0, 0, ($this->size_x +10) - 1, $this->size_y - 1, $border);
		//draw text
		for ($i = 0; $i < count($char_value); $i++){
			$color = $colors[$i % count($colors)];
			imagettftext(
				$img,
				28 + rand(0,8),
				-20 + rand(0,40),
				($i + 0.3) * $this->space_per_char,
				45+ rand(0,10),
				$color,
				DIR_FONTS.'luggerbu.ttf',
				$char_value[$i]
			);
		}
		for($i = 0; $i < 1000; $i++){
			$x1 = rand(5, ($this->size_x +10) - 5);
			$y1 = rand(5, $this->size_y - 5);
			$x2 = $x1 - 4 + rand(0,8);
			$y2 = $y1 - 4 + rand(0,8);
			imageline($img, $x1, $y1, $x2, $y2, $colors[rand(0, count($colors) -1)]);
		}
		return $img;
	}
	function href($filename) {
		if (@$_SERVER['HTTPS'] != 'on') {
	  		return HTTP_IMAGE . $filename;
		} else {
	  		return HTTPS_IMAGE . $filename;
		}	
	}
	function delete_image($image){
		@unlink(PATH_MASK . $image);
	
	}
	function clean_files(){
		$files=glob(PATH_MASK.'*.*');
		if (!$files) { return; }
		foreach ($files as $file) {
			$pattern='/\.('.implode('|',$this->types).')$/';
			if (preg_match($pattern,$file)) {
				if(filemtime($file) < time() - 86400){
					@unlink($file);
				}
			}
		}
	}
}
?>
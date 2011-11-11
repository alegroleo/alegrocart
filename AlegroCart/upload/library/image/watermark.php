<?php
define('DIR_WATERMARK', DIR_IMAGE . 'watermark' . D_S);

class Watermark { 

    var $original;
    var $wm_original;
    var $new_wm_width;
    var $new_wm_height;
    var $scale;
    var $destination;
    var $image_types = array('png','jpg','jpeg');

	function __construct(&$locator) {
		$this->config         =& $locator->get('config');
		$model 		      =& $locator->get('model');
		$this->database       =& $locator->get('database');
		$this->modelWatermark = $model->get('model_admin_watermark');
  	}

	function check_status($method) {

	$result = $this->modelWatermark->get_watermark_data($method);

	if ($result['wm_text'] != '' || $result['wm_image'] != '0') {

	return true;

	} else {

	return false;
	}
	}

	function get_wm_data($method) {

	$result = $this->modelWatermark->get_watermark_data($method);

		if ($result) {
			$this->wm_text = $result['wm_text'];
			$this->wm_font = $result['wm_font'];
			$this->wm_fontcolor = $result['wm_fontcolor'];
			$this->wm_transparency = $result['wm_transparency'];
			$this->wm_thposition = $result['wm_thposition'];
			$this->wm_tvposition = $result['wm_tvposition'];
			$this->wm_thmargin = $result['wm_thmargin'];
			$this->wm_tvmargin = $result['wm_tvmargin'];
			$this->wm_image = $result['wm_image'];
			$this->wm_ihposition = $result['wm_ihposition'];
			$this->wm_ivposition = $result['wm_ivposition'];
			$this->wm_ihmargin = $result['wm_ihmargin'];
			$this->wm_ivmargin = $result['wm_ivmargin'];
			$this->wm_scale = $result['wm_scale'];
		}
	}

	function get_size($image) {

		$size =  @getimagesize($image);
		
		switch( $size[2] ){
	    		case '2':
	    			$this->original["format"]="JPEG";
	    			$this->original["src"] = ImageCreateFromJPEG ($image);
				break;
	    		case '3':
	    			$this->original["format"]="PNG";
	    			$this->original["src"] = ImageCreateFromPNG ($image);
				break;
	    		default:
	                	return false;
		}
		
		$this->original["width"] = $size[0];  
		$this->original["height"] = $size[1];
		
		return true;

	}
	
	function get_wm_img_size() {

	$info = @getimagesize(DIR_WATERMARK . $this->wm_image);

	if ($info[2] != 3) return;

	$this->wm_original["src"] = ImageCreateFromPNG (DIR_WATERMARK . $this->wm_image);
	
	$this->wm_original["width"] = $info[0];  
	$this->wm_original["height"] = $info[1];

	}

	function resize_wm_image() {

	$this->scale = $this->wm_scale; 
	$this->get_wm_img_size();

	if ($this->scale == 0) {
		if ($this->wm_original["height"] < $this->original["height"] && $this->wm_original["width"] < $this->original["width"]) {
		
		    $this->new_wm_height  = $this->wm_original["height"];	
		    $this->new_wm_width   = $this->wm_original["width"];
		    $this->destination = $this->wm_original["src"];
		    return $this->destination;
		} else {
		$this->scale = 100;
		}
	} 

	$new_height = (int)($this->original["height"] * $this->scale / 100);
	$new_width = (int)($new_height * $this->wm_original["width"] / $this->wm_original["height"]);

	if ($new_width < $this->original["width"]) {
		
		  $this->new_wm_height  = $new_height;	
		  $this->new_wm_width   = $new_width;
	
	} else {

		  $this->new_wm_width = (int)($this->original["width"] * $this->scale / 100);
		  $this->new_wm_height  = (int)($this->new_wm_width * $this->wm_original["height"] / $this->wm_original["width"]);
	}

    	$this->destination = imagecreatetruecolor($this->new_wm_width, $this->new_wm_height);
	imagealphablending($this->destination, false);  	
	imagesavealpha($this->destination, true);
	$background  = imagecolorallocatealpha($this->destination, 255, 255, 255, 127);
	
    	imagefilledrectangle($this->destination, 0, 0, $this->new_wm_width,  $this->new_wm_height, $background);

	if (function_exists('imagecopyresampled')) {
        
		imagecopyresampled($this->destination, $this->wm_original["src"], 0, 0, 0, 0, $this->new_wm_width,  $this->new_wm_height, $this->wm_original["width"], $this->wm_original["height"]);

	} else {
		imagecopyresized($this->destination, $this->wm_original["src"], 0, 0, 0, 0, $this->new_wm_width, $this->new_wm_height, $this->wm_original["width"], $this->wm_original["height"]);
	}

	imagedestroy($this->wm_original["src"]);

    	return $this->destination;

	}

	function get_wm_hposition() {

        switch ($this->wm_ihposition) {
             case 'LEFT':
                 $x = (($this->original["width"]- $this->new_wm_width) > $this->wm_ihmargin ? $this->wm_ihmargin : $this->original["width"]- $this->new_wm_width );
                 break;
	     case 'CENTER':
                 $x = ($this->original["width"]- $this->new_wm_width)/2;
                 break;
             case 'RIGHT':
                 $x = (($this->original["width"]- $this->new_wm_width) > $this->wm_ihmargin ? $this->original["width"]-$this->new_wm_width-$this->wm_ihmargin : 0);
                 break;
             
         }

	return (int)$x;

	}

	function get_text_hposition() {

        $text_width  =  imagefontwidth($this->wm_font)*strlen($this->wm_text);
      
        switch ($this->wm_thposition) {
             case 'LEFT':
                 $x = $this->wm_thmargin;
                 break;
	     case 'CENTER':
                 $x = ($this->original["width"]-$text_width)/2;
                 break;
             case 'RIGHT':
                 $x = $this->original["width"]-$text_width-($this->wm_thmargin);
                 break;
             
         }

	return (int)$x;

	}

	function get_wm_vposition() {

        switch ($this->wm_ivposition) {
             case 'TOP':
                 $y = (($this->original["height"]- $this->new_wm_height) > $this->wm_ivmargin ? $this->wm_ivmargin : $this->original["height"]- $this->new_wm_height );
                 break;
	     case 'CENTER':
                 $y = ($this->original["height"]- $this->new_wm_height)/2;
                 break;
             case 'BOTTOM':
                 $y = (($this->original["height"]- $this->new_wm_height) > $this->wm_ivmargin ? $this->original["height"]-$this->new_wm_height-$this->wm_ivmargin : $this->original["height"]);
                 break;
             
         }

	return (int)$y;

	}

	function get_text_vposition() {

        $text_height =  imagefontheight($this->wm_font);
	
        switch ($this->wm_tvposition) {
             case 'TOP':
                $y = $this->wm_tvmargin;
                 break;
	     case 'CENTER':
                 $y = ($this->original["height"]-$text_height)/2;
                 break;
             case 'BOTTOM':
                 $y = $this->original["height"]-$text_height-($this->wm_tvmargin);
                 break;
            
         }

         return (int)$y;

	}

	function add_text () {

		    $wm_layer = imagecreatetruecolor($this->original["width"], $this->original["height"]);
		    imagesavealpha($wm_layer, true);

		    $white = imagecolorallocate($wm_layer, 255, 255, 255);

		    $color = imagecolorallocate($wm_layer, '0x' . substr($this->wm_fontcolor,0,2), '0x' . substr($this->wm_fontcolor,2,2), '0x' . substr($this->wm_fontcolor,4,2));
		    
		    $transparent_text = imagecolortransparent($wm_layer, $white);

		    imagefill($wm_layer, 0, 0, $transparent_text);
		    imagestring($wm_layer, $this->wm_font, 0, 0, $this->wm_text, $color);
	  
		    imagecopymerge($this->original["src"], $wm_layer, $this->get_text_hposition(),  $this->get_text_vposition(), 0, 0, $this->original["width"], $this->original["height"], $this->wm_transparency);

	}

	function add_image() {
		    
		    if (function_exists('imagecopyresampled')) {
	      
			imagecopyresampled($this->original["src"], $this->resize_wm_image(), $this->get_wm_hposition(), $this->get_wm_vposition(), 0, 0, $this->new_wm_width, $this->new_wm_height, $this->new_wm_width, $this->new_wm_height);

		    } else {
      
			imagecopy ($this->original["src"], $this->resize_wm_image(), $this->get_wm_hposition(), $this->get_wm_vposition(), 0, 0,  $this->new_wm_width, $this->new_wm_height);
	      
		    }
	}

	function deleteTmp($path){
		
		$files = glob($path.'*.*');
		if (!$files) { return; }
		foreach ($files as $file) {
		$pattern='#^([tmp\.])(.*)(\.)('.implode('|',$this->image_types).')$#';
			$filename = basename($file);
			    if (preg_match($pattern,$filename)) {
				@unlink($file);
			}
		}
	}

	function deleteCache($image) {
		$wildcard=preg_replace('/\.([a-z]{3,4})$/i', '-*x*.jpg', $image);
		$files=glob(DIR_IMAGE_CACHE.$wildcard);
		foreach ($files as $file) {
			@unlink($file);
		}
	}

	function copyTmp($file){

		if  (file_exists(DIR_WATERMARK . 'tmp.'. basename($file))) {
		@unlink($file);	
		}
		
		copy(DIR_WATERMARK . 'tmp.'. basename($file), $file);

	}

	function merge($image, $method) {
	  
	      if ($this->get_size($image)){
	      
	      $this->get_wm_data($method);

	      if ($this->wm_text != '') {
			  
			$this->add_text();
		}

		if ($this->wm_image != '0') {

			$this->add_image();
		}

		if ($this->original["format"] =='JPEG'){
		    imagejpeg($this->original["src"], $image );
		} elseif ($this->original["format"] =='PNG') {
		    imagepng($this->original["src"], $image);
		}
		
		imagedestroy($this->original["src"]);
	      
	      } else {
		  return;
	      }
	}

	function preview($image, $method) {
	  
	      if ($this->get_size($image)){
	      
	      $this->get_wm_data($method);

	      if ($this->wm_text != '') {

			$this->add_text();
		}

		if ($this->wm_image != '0') {

			$this->add_image();
		}

		if ($this->original["format"] =='JPEG'){
		    imagejpeg($this->original["src"], DIR_WATERMARK . 'tmp.'. basename($image));
		} elseif ($this->original["format"] =='PNG') {
		    imagepng($this->original["src"], DIR_WATERMARK . 'tmp.'. basename($image));
		}
		
		imagedestroy($this->original["src"]);
	     
		return 'tmp.'. basename($image);

	      } else {
		  return;
	      }
	}

	function resize($filename, $width, $height) {

		if ((!$filename) || (!$width) || (!$height)) {
			return $filename;
		} 
		if  (!file_exists(DIR_IMAGE . $filename)) {
			return $filename;
		}

		$new_filename = preg_replace('/\.([a-z]{3,4})$/i', '-' . $width . 'x' . $height . '.jpg', $filename);

		if ((file_exists(DIR_IMAGE_CACHE . $new_filename)) && (filemtime(DIR_IMAGE . $filename) < filemtime(DIR_IMAGE_CACHE . $new_filename))) {
				return (PATH_IMAGE_CACHE . $new_filename);
		}

		$image_info = getimagesize(DIR_IMAGE . $filename);

		switch( $image_info[2] ){
	    	
		case '2':
		
		      $this->image = imagecreatefromjpeg(DIR_IMAGE . $filename);
		
		      break;

		case '3':

		      $this->image = imagecreatefrompng(DIR_IMAGE . $filename);
		      
		      break;
		}

		$ratio = $width / imagesx($this->image);

		if ($ratio > 1) {
		      $new_filename = preg_replace('/\.([a-z]{3,4})$/i', '-' . imagesx($this->image) . 'x' . imagesy($this->image) . '.jpg', $filename);
		      imagejpeg($this->image,  DIR_IMAGE_CACHE . $new_filename,90);
		      imagedestroy($this->image);

		      $file =  PATH_IMAGE_CACHE . $new_filename;

		      return $file;


		} else {  
		
		      $height =  imagesy($this->image) * $ratio;
		
		      $new_image = imagecreatetruecolor($width, $height);
		      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, imagesx($this->image), imagesy($this->image));
		      $this->image = $new_image;

		      imagejpeg($this->image,  DIR_IMAGE_CACHE . $new_filename,90);
		      
		      imagedestroy($this->image);

		      $file =  PATH_IMAGE_CACHE . $new_filename;

		      return $file;

		}
	}

	function resize_preview($filename, $width, $height) {

		if ((!$filename) || (!$width) || (!$height)) {
			return $filename;
		} 
		if  (!file_exists(DIR_WATERMARK . $filename)) {
			return $filename;
		}

		$new_filename = preg_replace('/\.([a-z]{3,4})$/i', '-' . $width . 'x' . $height . '.jpg', $filename);

		$image_info = getimagesize(DIR_WATERMARK . $filename);

		switch( $image_info[2] ){
	    	
		case '2':
		
		      $this->image = imagecreatefromjpeg(DIR_WATERMARK . $filename);
		
		      break;

		case '3':

		      $this->image = imagecreatefrompng(DIR_WATERMARK . $filename);
		      
		      break;
		}

		$ratio = $width / imagesx($this->image);

		if ($ratio > 1) {
		      $new_filename = preg_replace('/\.([a-z]{3,4})$/i', '-' . imagesx($this->image) . 'x' . imagesy($this->image) . '.jpg', $filename);
		      imagejpeg($this->image,  DIR_IMAGE_CACHE . $new_filename,90);
		      imagedestroy($this->image);

		      $file =  PATH_IMAGE_CACHE . $new_filename;

		      return $file;
		    

		} else {
		
		      $height =  imagesy($this->image) * $ratio;
		
		      $new_image = imagecreatetruecolor($width, $height);
		      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, imagesx($this->image), imagesy($this->image));
		      $this->image = $new_image;

		      imagejpeg($this->image,  DIR_IMAGE_CACHE . $new_filename,90);
		      
		      imagedestroy($this->image);

		      $file =  PATH_IMAGE_CACHE . $new_filename;

		      return $file;

		}
	}
}
?>
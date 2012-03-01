<?php

define('PATH_IMAGE_CACHE','cache/');
define('DIR_IMAGE_CACHE',DIR_IMAGE.PATH_IMAGE_CACHE);

class Image { 
	function __construct(&$locator) {
		$this->request =& $locator->get('request');
  		$this->config  =& $locator->get('config');
  	}
	
	function href($filename) {
		if (@$_SERVER['HTTPS'] != 'on') {
	  		return HTTP_IMAGE . $filename;
		} else {
	  		return HTTPS_IMAGE . $filename;
		}	
	}

	function delete($image) {
		$wildcard=preg_replace('/\.([a-z]{3,4})$/i', '-*x*.jpg', $image);
		$files=glob(DIR_IMAGE_CACHE.$wildcard);
		array_push($files,DIR_IMAGE.$image);
		foreach ($files as $file) {
			@unlink($file);
		}
	}
	        
  	function resize($image, $width, $height) {
    	if (!$this->config->get('config_image_resize')) {
      		return $this->href($image);
    	}

 		if ((!$image) || (!$width) || (!$height)) {
			return $image;
		} 
		
		if  (!file_exists(DIR_IMAGE . $image)) {
			return $image;
		}
 
    	$new_image = preg_replace('/\.([a-z]{3,4})$/i', '-' . $width . 'x' . $height . '.jpg', $image);

    	if ((file_exists(DIR_IMAGE_CACHE . $new_image)) && (filemtime(DIR_IMAGE . $image) < filemtime(DIR_IMAGE_CACHE . $new_image))) {
      		return $this->href(PATH_IMAGE_CACHE . $new_image);
    	}

    	$size = @getimagesize(DIR_IMAGE . $image);

    	if ((!$size[0]) || (!$size[1])) {
      		return $this->href($image);
    	}

    	$scale = min($width / $size[0], $height / $size[1]);

    	if ($scale == 1) {
      		return $this->href($image);
    	}

    	$new_width   = (int)($size[0] * $scale);
    	$new_height  = (int)($size[1] * $scale);
    	$x_pos       = (int)(($width - $new_width) / 2);
    	$y_pos       = (int)(($height - $new_height) / 2);

    	$destination = imagecreatetruecolor($width, $height);
    	$background  = imagecolorallocate($destination, 255, 255, 255);
	
    	imagefilledrectangle($destination, 0, 0, $width, $height, $background);

    	switch ($size[2]) {
      		case '1':
        		$source = imagecreatefromgif(DIR_IMAGE . $image);

        		if (function_exists('imagecopyresampled')) {
          			imagecopyresampled($destination, $source, $x_pos, $y_pos, 0, 0, $new_width, $new_height, $size[0], $size[1]);
        		} else {
          			imagecopyresized($destination, $source, $x_pos, $y_pos, 0, 0, $new_width, $new_height, $size[0], $size[1]);
        		}

        		imagegif($destination, DIR_IMAGE_CACHE . $new_image, 90);

        		$image = $new_image;
        		break;	
      		case '2':
        		$source = imagecreatefromjpeg(DIR_IMAGE . $image);

        		if (function_exists('imagecopyresampled')) {
          			imagecopyresampled($destination, $source, $x_pos, $y_pos, 0, 0, $new_width, $new_height, $size[0], $size[1]);
        		} else {
          			imagecopyresized($destination, $source, $x_pos, $y_pos, 0, 0, $new_width, $new_height, $size[0], $size[1]);
        		}

        		imagejpeg($destination, DIR_IMAGE_CACHE . $new_image, 90);

        		$image = $new_image;
        		break;
      		case '3':
        		$source = imagecreatefrompng(DIR_IMAGE . $image);

        		if (function_exists('imagecopyresampled')) {
          			imagecopyresampled($destination, $source, $x_pos, $y_pos, 0, 0, $new_width, $new_height, $size[0], $size[1]);
        		} else {
          			imagecopyresized($destination, $source, $x_pos, $y_pos, 0, 0, $new_width, $new_height, $size[0], $size[1]);
        		}

        		imagejpeg($destination, DIR_IMAGE_CACHE . $new_image, 90);

        		$image = $new_image;
        		break;			
    	}

    	imagedestroy($source);
    	imagedestroy($destination);

    	return $this->href(PATH_IMAGE_CACHE . $image);
  	}
}
?>
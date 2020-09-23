<?php

define('PATH_IMAGE_CACHE','cache/');
define('DIR_IMAGE_CACHE',DIR_IMAGE.PATH_IMAGE_CACHE);

final class Image { 

	public function __construct(&$locator) {
		$this->request =& $locator->get('request');
		$this->config  =& $locator->get('config');
	}

	public function href($filename) {
		return $this->getImageBase() . $filename;
	}

	public function getImageBase() { //for images stored in the /image directory 
		if(SSL && $this->config->get('config_ssl')) {
			return ((HTTPS_STATIC_IMAGE && $this->config->get('config_static')) ? HTTPS_STATIC_IMAGE : HTTPS_IMAGE);
		} else {
			return ((HTTP_STATIC_IMAGE && $this->config->get('config_static')) ? HTTP_STATIC_IMAGE : HTTP_IMAGE);
		}

	}

	public function delete($image) {
		$wildcard=preg_replace('/\.([a-z]{3,4})$/i', '-*x*.jpg', $image);
		$files=glob(DIR_IMAGE_CACHE.$wildcard);
		array_push($files,DIR_IMAGE.$image);
		foreach ($files as $file) {
			@unlink($file);
		}
	}

	public function getWidth($image){
		$size = getimagesize(DIR_IMAGE . $image);
		return $size[0];
	}

	public function getHeight($image){
		$size = getimagesize(DIR_IMAGE . $image);
		return $size[1];
	}

	public function resize($image, $width, $height) {
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

				imagegif($destination, DIR_IMAGE_CACHE . $new_image, $this->config->get('config_image_quality'));

				$image = $new_image;
				break;
			case '2':
				$source = imagecreatefromjpeg(DIR_IMAGE . $image);

				if (function_exists('imagecopyresampled')) {
					imagecopyresampled($destination, $source, $x_pos, $y_pos, 0, 0, $new_width, $new_height, $size[0], $size[1]);
				} else {
					imagecopyresized($destination, $source, $x_pos, $y_pos, 0, 0, $new_width, $new_height, $size[0], $size[1]);
				}

				imagejpeg($destination, DIR_IMAGE_CACHE . $new_image, $this->config->get('config_image_quality'));

				$image = $new_image;
				break;
			case '3':
				$source = imagecreatefrompng(DIR_IMAGE . $image);

				if (function_exists('imagecopyresampled')) {
					imagecopyresampled($destination, $source, $x_pos, $y_pos, 0, 0, $new_width, $new_height, $size[0], $size[1]);
				} else {
					imagecopyresized($destination, $source, $x_pos, $y_pos, 0, 0, $new_width, $new_height, $size[0], $size[1]);
				}

				imagejpeg($destination, DIR_IMAGE_CACHE . $new_image, $this->config->get('config_image_quality'));

				$image = $new_image;
				break;
		}

		imagedestroy($source);
		imagedestroy($destination);

		return $this->href(PATH_IMAGE_CACHE . $image);
	}

	public function base64encode($image){
		$content = file_get_contents($image);
		$encoded = base64_encode($content);
		$type = pathinfo($image, PATHINFO_EXTENSION);
		$base64 = 'data:image/' . $type . ';base64,' . $encoded;
		return $base64;
	}

}
?>

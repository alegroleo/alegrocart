<?php
class Template {
	var $data = array();
	var $directory;
	var $controller;
	var $cssColumns;
	var $style;
	var $color;
	var $page_columns;
	var $social = FALSE;
	function __construct(&$locator, $directory, $styles, $color, $columns) {
		$this->config =& $locator->get('config');
		$this->directory = $directory;
		$this->set('template',$directory);
		$this->style = $styles;
		$this->color = $color;
		$this->page_columns = $columns;
		$this->cssColums = 3;
		$this->head_def  =& $locator->get('HeaderDefinition');
		$this->social = $this->config->get('config_social');
		$this->condense = $this->config->get('config_page_load');
	}

	function set($key, $value = NULL) {
		if (!is_array($key)) {
			$this->data[$key] = $value;
		} else {
			$this->data = array_merge($this->data, $key);
		}
	}
	function condense_css($add_default = true, $add_color = true){
		$css = "";
		$created = 0;
		$size = 0;
		$media = array(" ", '"', '=' , 'media', 'screen');
		if($this->head_def->css_path){
			foreach($this->head_def->css_path as $pagecss){
				$pagecss = str_replace($media, '', $pagecss);
				$pagecss = str_replace($this->style . '/css/',DIR_BASE . 'catalog' . D_S . 'styles' . D_S . $this->style . D_S. 'css' . $this->cssColumns . D_S, $pagecss);
				$created += filemtime($pagecss);
				$size += filesize($pagecss);
			}
			if ($add_default) {
				$pagecss = 'catalog' . D_S . 'styles' . D_S . $this->style . D_S . 'css' . $this->cssColumns . D_S . 'default.css';
				$created += filemtime($pagecss);
				$size += filesize($pagecss);
			}
			if ($add_color) {
				$pagecss = 'catalog' . D_S . 'styles' . D_S . $this->style . D_S . 'colors' . $this->cssColumns . D_S . $this->color;
				$created += filemtime($pagecss);
				$size += filesize($pagecss);
			}
		}
		$fp_path = DIR_BASE . 'catalog' . D_S . 'styles' . D_S . $this->style . D_S . 'render' . D_S. $this->controller .'_'.$created.'_'.$size.'.css';
		if(!file_exists($fp_path)){
			array_map('unlink', glob(DIR_BASE . 'catalog' . D_S . 'styles' . D_S . $this->style . D_S . 'render' . D_S. $this->controller .'_'.'*'));
			if($this->head_def->css_path){
				foreach($this->head_def->css_path as $pagecss){
					$pagecss = str_replace($media, '', $pagecss);
					$pagecss = str_replace($this->style . '/css/',$this->style . D_S. 'css' . $this->cssColumns . D_S, $pagecss);
					$css .= file_get_contents('catalog' . D_S . 'styles' . D_S . $pagecss);
				}
			}
			if ($add_default) {
				$css .= file_get_contents('catalog' . D_S . 'styles' . D_S . $this->style . D_S . 'css' . $this->cssColumns . D_S . 'default.css');
			}
			if ($add_color) {
				$css .= file_get_contents('catalog' . D_S . 'styles' . D_S . $this->style . D_S . 'colors' . $this->cssColumns . D_S . $this->color);
			}
			$fp = fopen($fp_path, 'w+');
			fwrite($fp, $this->minify_css($css));
			fclose($fp);
		}
		$css_path = "<link rel=\"stylesheet\" type=\"text/css\" href=\"catalog/styles/" . $this->style . "/render/" . $this->controller . '_' . $created . '_' . $size . '.css' . "\">";
		return $css_path;
	}
	function condense_js(){
		$js = "";
		$created = 0;
		$size = 0;
		if($this->head_def->java_path){
			foreach($this->head_def->java_path as $pagejs){
				$pagejs = str_replace('/', D_S, $pagejs);
				$created += filemtime($pagejs);
				$size += filesize($pagejs);
			}
		}
		$fp_path = DIR_BASE . 'catalog' . D_S . 'javascript' . D_S . 'render' . D_S . $this->controller.'_'.$created.'_'.$size . '.js';
		if(!file_exists($fp_path)){
			array_map('unlink', glob(DIR_BASE . 'catalog' . D_S . 'javascript' . D_S . 'render' . D_S. $this->controller .'_'.'*'));
			if($this->head_def->java_path){
				foreach($this->head_def->java_path as $pagejs){
					$pagejs = str_replace('/', D_S, $pagejs);
					$js .= file_get_contents($pagejs);
				}
				$fp = fopen($fp_path, 'w+');
				fwrite($fp, $this->minify_js($js));
				fclose($fp); 
			}
		}
		$js_path = "<script type=\"text/javascript\" src=\"catalog/javascript/render/" . $this->controller . '_' . $created . '_' . $size . ".js\"></script>";;
		return $js_path;
	}
	function minify_css($string){
		$string = preg_replace('/(?<!\S)\/\/\s*[^\r\n]*/', '', $string); // remove // like comments
		$string = preg_replace('/\/\*[\s\S]*?\*\//','', $string); // remove /**/ like comments
		$string = preg_replace('/^\s*\n/m','', $string); // delete blank lines
		$string = str_replace("\r\n", "\n", $string);
		$string = preg_replace('/\s*([;{])\s*/', '$1', $string); // delete whitespace around semicolons and {
		$string = preg_replace('/;?\s*}\s*/', '}', $string); // delete whitespace around } and delete the last semicolon as well
		return $string;
	}
	function minify_js($string){
		$string = preg_replace('/(?<!\S)\/\/\s*[^\r\n]*/', '', $string); // remove // like comments
		$string = preg_replace('/\/\*[\s\S]*?\*\//','', $string); // remove /**/ like comments
		$string = preg_replace('/^\s*\n/m','', $string); // delete blank lines
		$string = preg_replace('/\t+/','',$string); // delete tabs
		$string = preg_replace('/\s*([=,;\)\}\(\{\[\]])\s*/','$1',$string); //delete unneeded whitespace
		$string = preg_replace('/ *\n/',' ',$string); // replace linebreaks
		return $string;
	}
	function fetch($filename, $directory = DIR_TEMPLATE) {
	$file = $directory . $this->directory . '/' . $filename;

		if (file_exists($file)) {
			extract($this->data);

			ob_start();

			include($file);

			$content = ob_get_contents();

			ob_end_clean();

			return $content;
		} else {
			exit('Error: Template ' . $file . ' not found!');
		}
	}
}
?>

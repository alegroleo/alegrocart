<?php

final class Template {

	public $data = array();
	public $directory;
	public $controller;
	public $action;
	public $admin_controller;
	public $cssColumns;
	public $style;
	public $color;
	public $page_columns;
	public $social = FALSE;
	private $getlist = FALSE;
	private $index = array( // pages without getList and getForm
				'backup',
				'home',
				'login',
				'mail',
				'maintenance',
				'minov',
				'order_edit',
				'report_logs',
				'report_online',
				'report_purchased',
				'report_sale',
				'report_viewed',
				'server_info',
				'setting',
				'watermark'
			);
	private $extensions = array('calculate', 'module', 'shipping', 'payment');

	public function __construct(&$locator, $directory, $styles, $color, $columns) {
		$this->config		=& $locator->get('config');
		$this->locator		=& $locator;
		$this->directory	= $directory;
		$this->set('template',$directory);
		$this->style		= $styles;
		$this->color		= $color;
		$this->page_columns	= $columns;
		$this->cssColums	= 3;
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->url		=& $locator->get('url');
		$this->social		= $this->config->get('config_social');
		$this->condense		= $this->config->get('config_page_load');
		$this->admin_condense	= $this->config->get('config_admin_page_load');
	}

	public function set($key, $value = NULL) {
		if (!is_array($key)) {
			$this->data[$key] = $value;
		} else {
			$this->data = array_merge($this->data, $key);
		}
	}

	public function condense_css($add_default = true, $add_color = true){
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

	public function condense_admin_css($add_default = true){
		$css = "";
		$created = 0;
		$size = 0;
		$pattern='/^('.implode('|',$this->extensions).')/';
		$media = array(" ", '"', '=' , 'media', 'screen');
		if($this->head_def->css_admin_path){
			foreach($this->head_def->css_admin_path as $pagecss){
				$pagecss = str_replace($media, '', $pagecss);
				$pagecss = str_replace('/',D_S, $pagecss);
				$pagecss = DIR_BASE . PATH_ADMIN . D_S . $pagecss;
				$created += filemtime($pagecss);
				$size += filesize($pagecss);
			}
			if ($add_default) {
				$pagecss = DIR_BASE . PATH_ADMIN . D_S . 'template' . D_S . $this->directory. D_S . 'css' . D_S . 'default.css';
				$created += filemtime($pagecss);
				$size += filesize($pagecss);
			}
		}
		if ($this->action == 'index' && !preg_match($pattern, $this->admin_controller)) { // i.e. not insert nor update and not extension
			if (in_array($this->admin_controller, $this->index)){ // pages without getList and getForm
				$fp_path = DIR_BASE . PATH_ADMIN . D_S . 'template' . D_S . $this->directory. D_S . 'render' . D_S. $this->admin_controller .'_'.$created.'_'.$size.'.css';
			} else { // common css file for every getList
				$fp_path = DIR_BASE . PATH_ADMIN . D_S . 'template' . D_S . $this->directory. D_S . 'render' . D_S. 'list_'.$created.'_'.$size.'.css';
				$this->getlist = TRUE;
			}
		} else { // css for getForm
			$fp_path = DIR_BASE . PATH_ADMIN . D_S . 'template' . D_S . $this->directory. D_S . 'render' . D_S. $this->admin_controller .'_'.$created.'_'.$size.'.css';
		}

		if(!file_exists($fp_path)){
			if ($this->getlist){
				array_map('unlink', glob(DIR_BASE . PATH_ADMIN .D_S . 'template' . D_S . $this->directory . D_S . 'render' . D_S. 'list_'.'*'));
			} else {
				array_map('unlink', glob(DIR_BASE . PATH_ADMIN .D_S . 'template' . D_S . $this->directory . D_S . 'render' . D_S. $this->admin_controller .'_'.'*'));
			}
			if($this->head_def->css_admin_path){
				foreach($this->head_def->css_admin_path as $pagecss){
					$pagecss = str_replace($media, '', $pagecss);
					$pagecss = str_replace('/', D_S, $pagecss);
					$css .= file_get_contents(DIR_BASE . PATH_ADMIN . D_S . $pagecss);
				}
			}
			if ($add_default) {
				$css .= file_get_contents(DIR_BASE . PATH_ADMIN . D_S . 'template' . D_S . $this->directory . D_S . 'css' . D_S . 'default.css');
			}
			$fp = fopen($fp_path, 'w+');
			fwrite($fp, $this->minify_css($css));
			fclose($fp);
		}
		if ($this->getlist){
			$css_path = "<link rel=\"stylesheet\" type=\"text/css\" href=\"template/".$this->directory."/render/" . 'list_' . $created . '_' . $size . '.css' . "\">";
		} else {
			$css_path = "<link rel=\"stylesheet\" type=\"text/css\" href=\"template/".$this->directory."/render/" . $this->admin_controller . '_' . $created . '_' . $size . '.css' . "\">";
		}
		return $css_path;
	}

	public function condense_js(){
		$js = "";
		$created = 0;
		$size = 0;
		if($this->head_def->java_path){
			foreach($this->head_def->java_path as $pagejs){
				$pagejs = str_replace('/', D_S, $pagejs);
				$created += filemtime($pagejs);
				$size += filesize($pagejs);
			}
		} else {
			return;
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
		$js_path = "<script type=\"text/javascript\" src=\"catalog/javascript/render/" . $this->controller . '_' . $created . '_' . $size . ".js\"></script>";
		return $js_path;
	}

	public function condense_admin_js(){
		$js = "var CKEDITOR_BASEPATH = \"" . (HTTPS_ADMIN ? HTTPS_ADMIN : HTTP_ADMIN) . "javascript/ckeditor/\";";
		$created = 0;
		$size = 0;
		$pattern='/^('.implode('|',$this->extensions).')/';
		if($this->head_def->java_admin_path){
			foreach($this->head_def->java_admin_path as $pagejs){
				$pagejs = str_replace('/', D_S, $pagejs);
				$pagejs = DIR_BASE . PATH_ADMIN . D_S . $pagejs;
				$created += filemtime($pagejs);
				$size += filesize($pagejs);
			}
		} else {
			return;
		}
		if ($this->action == 'index' && !preg_match($pattern, $this->admin_controller)) { // i.e. not insert nor update and not extension
			if (in_array($this->admin_controller, $this->index)){ // pages without getList and getForm
				$fp_path = DIR_BASE . PATH_ADMIN . D_S. 'javascript' . D_S . 'render' . D_S . $this->admin_controller.'_'.$created.'_'.$size . '.js';
			} else { // common js file for every getList
				$fp_path = DIR_BASE . PATH_ADMIN . D_S. 'javascript' . D_S . 'render' . D_S . 'list_'.$created.'_'.$size . '.js';
				$this->getlist = TRUE;
			}
		} else { // js for getForm
			$fp_path = DIR_BASE . PATH_ADMIN . D_S. 'javascript' . D_S . 'render' . D_S . $this->admin_controller.'_'.$created.'_'.$size . '.js';
		}

		if(!file_exists($fp_path)){
			if ($this->getlist){
				array_map('unlink', glob(DIR_BASE . PATH_ADMIN . D_S . 'javascript' . D_S . 'render' . 'list_'.'*'));
			} else {
				array_map('unlink', glob(DIR_BASE . PATH_ADMIN . D_S . 'javascript' . D_S . 'render' . D_S. $this->admin_controller .'_'.'*'));
			}
			if($this->head_def->java_admin_path){
				foreach($this->head_def->java_admin_path as $pagejs){
					$pagejs = str_replace('/', D_S, $pagejs);
					$pagejs = DIR_BASE . PATH_ADMIN . D_S . $pagejs;
					$js .= file_get_contents($pagejs);
				}
				$fp = fopen($fp_path, 'w+');
				fwrite($fp, $this->minify_js($js));
				fclose($fp); 
			}
		}
		if ($this->getlist){
			$js_path = "<script type=\"text/javascript\" src=\"javascript/render/" . 'list_' . $created . '_' . $size . ".js\"></script>";
		} else {
			$js_path = "<script type=\"text/javascript\" src=\"javascript/render/" . $this->admin_controller . '_' . $created . '_' . $size . ".js\"></script>";
		}
		return $js_path;
	}

	private function minify_css($string){
		$string = preg_replace('/\/\*[\s\S]*?\*\//','', $string); // remove /**/ like comments
		$string = preg_replace('/^\s*\n/m','', $string); // delete blank lines
		$string = str_replace("\r\n", "\n", $string);
		$string = preg_replace('/\s*([,;{])\s*/', '$1', $string); // delete whitespace around commas, semicolons and {
		$string = preg_replace('/;?\s*}\s*/', '}', $string); // delete whitespace around } and delete the last semicolon as well
		return $string;
	}

	private function minify_js($string){
		$string = preg_replace("/(\*\/\s*)\/\/(?!(\*\/|[^\r\n]*?[\\\\n\\\\r]+\s*\"\s*\+|[^\r\n]*?[\\\\n\\\\r]+\s*\'\s*\+))[^\n\r\;]*?[^\;]\s*[\n\r]/", "$1\n", $string);
		do {
			$string = preg_replace("/(http(s)?\:)([^\r\n]*?)(\/\/)/", "$1$3qDdXX", $string, 1, $count);
		} while ($count);
		do {
			$string = preg_replace("/(^^\s*\/)(\/).*/", "\n", $string, 1, $count);
		} while ($count);
		$string = preg_replace("/([\r\n]+?\s*|\,\s*|\;\s*|\|\s*|\)\s*|\+\s*|\&\s*|\{\s*|\}\s*|\]\s*|\[\s*|\+\s*|\'\s*|\"\s*|\:\s*|-\s*)((\/)(\/)+)([^\r\n\'\"]*?[nte]'[a-z])*?(?!([^\r\n]*?)([\'\"]|[\\\\]|\*\/|[=]+\s*\";|[=]+\s*\';)).*/", "$1\n", $string);
		$string = preg_replace("/(^^\s*\/\*)(?!([\'\"]))[\s\S]*?(\*\/)/", "\n \n", $string);    
		$string = preg_replace("/(\|\|\s*|=\s*|[\n\r]|\;\s*|\,\s*|\{\s*|\}\s*|\+\s*|\?\s*)((?!([\'\"]))\/\*)(?!(\*\/))[\s\S]*?(\*\/)/", "$1\n", $string);
		$string = preg_replace("/(\;\s*|\,\s*|\{\s*|\}\s*|\+\s*|\?\s*|[\n\r]\s*)((?!([\'\"]))\/\*)(?!(\*\/))[\s\S]*?(\*\/)/", "$1\n", $string);
		//Remove: ) /* non-empty*//*xxx*/)
		do {
			$string = preg_replace('/([^\/\"\'\*a-zA-Z0-9\>])\/\*(?!(\*\/))[^\n\r@]*?\*\/(?=([\/\"\'\\\\\*a-zA-Z0-9\>\s=\)\(\,:;\.\}\{\|\]\[]))/', "$1", $string, 1, $count);
		} while ($count); 
		$string = preg_replace("/([\;\n\r]\s*)\/\/.*/", "$1\n", $string);   
		$string = preg_replace("/(\/\s\/)([g][\W])/", "ZUQQ$2", $string);
		$string = preg_replace("/\\\\n/", "AQerT", $string);
		$string = preg_replace("/\\\\r/", "BQerT", $string);    
		// Remove all extra new lines after [ and \
		$string = preg_replace("/([^\*])(\*|[\r\n]|\'|\"|\,|\+|\{|;|\(|\)|\[|\]|\{|\}|\?|[^p|s]:|\&|\%|[^\\\\][a-m-o-u-s-zA-Z]|\||-|=|[0-9])(\s*)(?!([^=\\\\\&\/\"\'\^\*:]))(\/)(\/)+(?!([\r\n\*\+\"]*?([^\r\n]*?\*\/|[^\r\n]*?\"\s*\+|([^\r\n]*?=\";))))([^\n\r]*)([^;\"\'\{\(\}\,]\s*[\\\\\[])(?=([\r\n]+))/", "$1$2$3", $string);
		// /* followed by (not new line but) ... */ ... /* ... till */
		$string = preg_replace("/((([\r\n]\s*)(\/\*[^\r\n]*?\*\/(?!([^\n\r]*?\"\s*\+)))([^\n\r]*?\/\*[^\n\r]*?\*\/(?!([^\n\r]*?\"\s*\+))[^\n\r]*?\/\*[^\n\r]*?\*\/(?!([^\n\r]*?\"\s*\+)))+)+(?!([\*]))(?=([^\n\r\/]*?\/\/\/)))/", "$3", $string);
		// (slash slash) remove everything behinde it not if its followed by */ and /n/r or " + and /n/r
		$string = preg_replace("/([\r\n]+?\s*)((\/)(\/)+)(?!([^\r\n]*?)([\\\\]|\*\/|[=]+\s*\";|[=]+\s*\';)).*/", "$1\n", $string);
		// slash slash star between collons protect like: ' //* ' by TDdXX
		$string = preg_replace("/(\'\s*)(\/\/\*)([^\r\n\*]*?(?!(\*\/))(\'))/", "$1TDdXX$3", $string); 
		// slash slash star between collons protect like: " //* " by TDdXX
		$string = preg_replace("/(\"\s*)(\/\/\*)([^\r\n\*]*?(?!(\*\/))(\"))/", "$1TDdXX$3", $string); 
		// slash slash star between collons protect like: ' //* ' by TDdXX
		$string = preg_replace("/(\'\s*)(\/\*)([^\r\n\*]*?(?!(\*\/))(\'))/", "$1pDdYX$3", $string); 
		// slash slash star between collons protect like: " //* " by TDdXX
		$string = preg_replace("/(\"\s*)(\/\*)([^\r\n\*]*?(?!(\*\/))(\"))/", "$1pDdYX$3", $string);
		// in regex star slash protect by: ODdPK
		$string = preg_replace("/(\,\s*)(\*\/\*)(\s*[\}\"\'\;\)])/", "$1RDdPK$3", $string); // , */* '
		$string = preg_replace('/(\n|\r|\+|\&|\=|\|\||\(|[^\)]\:[^\=\,\/\$\\\\\<]|\(|return(?!(\/[a-zA-Z]+))|\!|\,)(?!(\s*\/\/|\n))(\s*\/)([^\]\)\}\*\;\,gi\.]\s*)([^\/\n]*?)(\*\/)/', '$1$4$5$6ODdPK', $string); 
		//// (slash r) (slash n) protect if followed by " + and new line
		$string = preg_replace("/[\/][\/]+(AQerTBQerT)(\s*[\"]\s*[\+])/", "WQerT", $string);
		$string = preg_replace("/[\/][\/]+(\*\/AQerTBQerT)(\s*[\"]\s*[\+])/", "YQerT", $string);
		// Html Text protection!
		$string = preg_replace("/([\r\n]\s*\/\/)[^\r\n]*?\/\*(?=(\/))[^\r\n]*?([\r\n])/", "$1 */$3", $string);
		$string = preg_replace("/([\)]|[^\/|\\\\|\"])(\/\*)(?=([^\r\n]*?[\\\\][rn]([\\\\][nr])?\s*\"\s*\+\s*(\n|\r)?\s*\"))/", "$1pDdYX", $string);
		$string = preg_replace('/([\"]\s*[\,\+][\r\n]\s*[\"])(\s*\/\/)((\/\/)|(\/))*/', '$1qDdXX', $string);
		$string = preg_replace('/([\"]\s*[\,\+][\r\n]\s*[\"](qDdXX))[\\\\]*(\s*\/\/)*((\/\/)|(\/))*/', '$1', $string);
		// started by new line slash slash remove all not followed by */ and new line!
		$string = preg_replace("/([\r\n]\s*)(?=([^\r\n\*\,\:\;a-zA-Z\"]*?))(\/)+(\/)[^\r\n\/][^\r\n\*\,]*?[\*]+(?!([^\r\n]*?(([^\r\n]*?\/|\"\s*\)\s*\;|\"\s*\;|\"\s*\,|\'\s*\)\s*\;|\'\s*\;|\'\s*\,))))[^\r\n]*(?!([\/\r\n]))[^\r\n]*/", "$1", $string);
		// removes all *.../ achter // leaves the ( // /* staan en */ ) 1 off 2
		$string = preg_replace("/([\r\n](\/)*[^:\;\,\.\+])(\/\/[^\r\n]*?)(\*)?([^\r\n]+?)(\*)+([^\r\n\*\/])+?(\/[^\*])(?!([^\r\n]*?((\"\s*\)\s*\;|\"\s*\;|\"\s*\,|\'\s*\)\s*\;|\'\s*\;|\'\s*\,))))/", "$1$3$7$8", $string);
		// removes all /* after // leaves the ( // */ staan ) 2 off 2
		do {
			$string = preg_replace("/([\r\n])((\/)*[^:\;\,\.\+])(\/\/[^\r\n]*?)(\*)?([^\r\n]+?)(\/|\*)([^\r\n]*?)(\*)[\r\n]/", "$1", $string, 1, $count);
		} while ($count); 
		// removes all (/* and */) combinations after // and everything behinde it! but leaves  ///* */ or example. ///*//*/ one times.
		$string = preg_replace("/(((([\r\n](?=([^:;,\.\+])))(\/)+(\/))(\*))([^\r\n]*?)(\/\*)*([^\r\n])*?(\*\/)(?!([^\r\n]*?((\"\s*\)\s*\;|\"\s*\;|\"\s*\,|\'\s*\)\s*\;|\'\s*\;|\'\s*\,))))(((?=([^:\;\,\.\+])))(\/)*([^\r\n]*?)(\*|\/)?([^\r\n]*?)(\/\*)([^\r\n])*?(\*\/)(?!([^\r\n]*?((\"\s*\)\s*\;|\"\s*\;|\"\s*\,|\'\s*\)\s*\;|\'\s*\;|\'\s*\,)))))*)+[^\r\n]*/", "$2$7$9$10$11$12", $string);
		// removes /* ... followed by */ repeat even pairs till new line!
		$string = preg_replace("/(\/\*[\r\n]\s*)(?!([^\/<>;:%~`#@&-_=,\.\$\^\{\[\(\|\)\*\+\?\'\"\a-zA-Z0-9]))(((\/\*)[^\r\n]*?(\*\/)?[^\r\n]*?(\/\*)[^\r\n]*?(\*\/))*((\/\*)[^\r\n]*?(\*\/)))+(?!([^\r\n]*?(\*\/|\/\*)))[^\r\n]*?[\r\n]/", "\n", $string);
		// (Mark) Regex Find all "  Mark with = AwTc  and  CwRc // special cahacers are:  . \ + * ? ^ $ [ ] ( ) { } < > = ! | : " '
		$string = preg_replace("/(?!([\r\n]))([^a-zA-Z0-9]\+|\?|&|\=|\|\||\!|\(|,|return(?!(\/[a-zA-Z]+))|[^\)]\:)(?!(\s*\/\/|\n|\/\*[^\r\n\*]*?\*\/))(\s*\/([\*\^]?))(?!([\r\n\*\/]|[\*]))(?!(\<\!\-\-))(([^\^\]\)\}\*;,g&\.\"\']?\s*)(?=([\]\)\}\*;,g&\.\/\"\']))?)(([^\r\n]*?)(([\w\W])([\*]?\/\s*)(\})|([^\\\\])([\*]?\/\s*)(\))|([\w\W])([\*]?\/\s*)([i][g]?[\W])|([\w\W])([\*]?\/\s*)([g][i]?[\W])|([\w\W])([\*]?\/\s*)(?=(\,))|([^\\\\]|[\/])([\*]?\/\s*)(;)|([\w\W])([\*]?\/\:\s)(?!([@\]\[\)\(\}\{\.,#%\+-\=`~\*&\^;\:\'\"]))|([^\\\\])([\*]?\/\s*)(\.[^\/])|([^\\\\])([\*]?\/\s*)([\r\n]\s*[;\.,\)\}\]]\s*[^\/]|[\r\n]\s*([i][g]?[\W])|[\r\n]\s*([g][i]?[\W])))|([^\\\\])([\*]?\/\s*)([;\.,\)\}\]]\s*[^\/]|([i][g]?[\W])|([g][i]?[\W])))/", "$2$3$5AwTc$7$8$10$13$15$18$21$24$27$30$33$36$39$44CwRc$16$17$19$20$22$23$25$26$28$31$32$34$35$37$38$40$41$45$46", $string);
		// Remove all extra new lines after [ and \
		$string = preg_replace("/([^;\"\'\{\(\}\,\/]\s*[^\/][\\\\\[]\s?)\s*([\r\n]+)/", "$1", $string); 
		$string = preg_replace("/([\|\[])\s*([\|\]])/", "$1$2", $string);
		// (star slash) or (slash star) 1 sentence! Protect! With pDdYX and ODdPK
		do {
			$string = preg_replace('/(AwTc)([^\r\nC]*?)(\/\*)(?=([^\r\n]*?CwRc))/', '$1$2pDdYX', $string, 1, $count);
		} while ($count);
		do {
			$string = preg_replace('/(AwTc)([^\r\nC]*?)(\*\/)(?=([^\r\n]*?CwRc))/', '$1$2ODdPK', $string, 1, $count);
		} while ($count);
		// (slash slash) 1 sentence! Protect with: qDdXX
		do {
			$string = preg_replace('/(AwTc)([^\r\nC]*?)(\/\/)(?=([^\r\n]*?CwRc))/', '$1$2qDdXX', $string, 1, $count);
		} while ($count); 
		// DEZE WERKT !! multiple parentheticals counting for even ones!
		$string = preg_replace("/([^\(\/\"\']\s*)(?!\(\s*function)((\()(?=([^\n\r\)]*?[\'\"]))(?!([^\r\n]*?\"\s*\<[^\r\n]*?\>\s*\"|[^\r\n]*?\"\s*\\\\\s*\"|[^\r\n]*?\"\s*\[[^\r\n]*?\]\s*\"))((?>[^()]+)|(?2))*?\))(?!(\s*\"\s*\;|\s*\'\s*\;|\s*\/|\s*\)|\s*\"|[^\n\r]*?\"\s*\+\s*(\n|\r)?\s*\"))/", "$1 /*Yu*/ $2 /*Zu*/ ", $string);
		// this one is  SINGLE parentheticals pair.
		//     $string = preg_replace("/([^\(\/\"\']\s*)((\()(?=([^\n\r\)]*?[\'\"]))(?!(function|\)|[^\r\n]*?\"\s*\+[^\r\n]*?\+\s*\"|[^\r\n]*?\"\s*\<[^\r\n]*?\>\s*\"|[^\r\n]*?\"\s*\\\\\s*\"|[^\r\n]*?\"\s*\[[^\r\n]*?\]\s*\"))([^()]*?\)))(?!(\s*\"\s*\;|\s*\'\s*\;|\s*\/|\s*\)|\s*\"|[^\n\r]*?\"\s*\+\s*(\n|\r)?\s*\"))/", "$1 /*Yu*/ $2 /*Zu*/ ", $string); 
		// (slash slash) 1 sentence! Protect with: qDdXX
		do {
			$string = preg_replace('/(\/\*Yu\*\/)([^\r\n]*?)(\/)(\/)(?=([^\r\n]*?\/\*Zu\*\/))/', '$1$2qDdXX', $string, 1, $count);
		} while ($count); 
		do {
			$string = preg_replace("/(\/\*Yu\*\/)([^\n\r\'\"]*?[\"\'])([^\n\r\)]*?)(\/\*)([^\n\r\'\"\)]*?[\"\'])([^\n\r]*?\/\*Zu\*\/)/", "$1$2$3pDdYX$5$6", $string, 1, $count);
		} while ($count);
		do {
			$string = preg_replace("/(\/\*Yu\*\/)([^\n\r\'\"]*?[\"\'])([^\n\r\)]*?)(\*\/)([^\n\r\'\"\)]*?[\"\'])([^\n\r]*?\/\*Zu\*\/)/", "$1$2$3ODdPK$5$6", $string, 1, $count);
		} while ($count);
		// (slash slash) 2 sentences! Protect ' and "
		do {
			$string = preg_replace("/(=|\+|\(|[a-z]|\,)(\s*)(\")([^\r\n\;\/\'\)\,\]\}\*]*?)(\/)(\/)([^\r\n\;\"\*]*?)(\")/", "$1$2$3$4qDdXX$7$8", $string, 1, $count);
		} while ($count);
		do {
			$string = preg_replace("/(=|\+|\(|[a-z]|\,)(\s*)(\')([^\r\n\;\/\'\)\,\]\}\*]*?)(\/)(\/)([^\r\n\*\;\']*?)(\')/", "$1$2$3$4qDdXX$7$8", $string, 1, $count);
		} while ($count); 
		// (slash slash) 2 sentences! Protect slash slash between ' and "
		do {
			$string = preg_replace("/(\"[^\r\n\;]*?)(\/)(\/)([^\r\n\"\;]*?([\"]\s*(\;|\)|\,)))/", "$1qDdXX$4", $string, 1, $count);
		} while ($count); 
		do {
			$string = preg_replace("/(\'[^\r\n\;]*?)(\/)(\/)([^\r\n\'\;]*?([\']\s*(\;|\)|\,)))/", "$1qDdXX$4", $string, 1, $count);
		} while ($count); 
		// Remove all slar slash achter \n
		$string = preg_replace("/([\n\r])([^\n\r\*\,\"\']*?)(?=([^\*\,\:\;a-zA-Z\"]*?))(\/)(\/)+(?=([^\n\r]*?\*\/))([^\n\r]*?(\*\/)).*/", "$1$4$5 $8", $string); 
		do {
			$string = preg_replace("/([\r\n]\s*)((\/\*(?!(\*\/)))([^\r\n]+?)(\*\/))(?!([^\n\r\/]*?(\/)(\/)+\*))/", "$1$3$6", $string, 1, $count);
		} while ($count);
		$string = preg_replace("/([\n\r]\/)(\/)+([^\n\r]*?)(\*\/)([^\n\r]*?(\*\/))(?!([^\n\r]*?(\*\/)|[^\n\r]*?(\/\*))).*/", "$1/ $4", $string);  
		do {
			$string = preg_replace("/([\n\r]\s*\/\*\*\/)([^\n\r=]*?\/\*[^\n\r]*?\*\/)(?=([\n\r]|\/\/))/", "$1", $string, 1, $count);
		} while ($count); 
		$string = preg_replace("/([\n\r]\s*\/\*\*\/)([^\n\r=]*?)(\/\/.*)/", "$1$2", $string); 
		// Remove all slash slash achter = '...'; //......
		do {
			$string = preg_replace("/(\=\s*)(?=([^\r\n\'\"]*?\'[^\n\r\']*?\'))([^\n\r;]*?[;]\s*)(\/\/[^\r\n][^\r\n]*)[\n\r]/", "$1$3", $string, 1, $count);
		} while ($count);
		// protect slash slash '...abc//...abc'!
		do {
			$string = preg_replace("/(\=)(\s*\')([^\r\n\'\"]*?)(\/)(\/)([^\r\n]*?[\'])/", "$1$2$3qDdXX$6", $string, 1, $count);
		} while ($count);
		//(slash star) or (star slash) : no dubble senteces here! Protect with: pDdYX and ODdPK
		do {
			$string = preg_replace("/(\"[^\r\n\;\,\"]*?)(\/)(\*)(?!([YZ]u\*\/))([^\r\n;\,\"]*?)(\")/", "$1pDdYX$5$6", $string, 1, $count);
		} while ($count);   // open
		do {
			$string = preg_replace("/([^\"]\"[^\r\n\;\/\,\"]*?)(\s*)(\*)(\/)([^\r\n;\,\"=]*?)(\")/", "$1$2ODdPK$5$6", $string, 1, $count);
		} while ($count); // close
		do {
			$string = preg_replace("/(\'[^\r\n\;\,\']*?)(\/)(\*)(?!([YZ]u\*\/))([^\r\n;\,\']*?)(\')/", "$1pDdYX$5$6", $string, 1, $count);
		} while ($count);   // open
		do {
			$string = preg_replace("/(\'[^\r\n\;\/\,\']*?)(\s*)(\*)(\/)([^\r\n;\,\']*?)(\')/", "$1$2ODdPK$5$6", $string, 1, $count);
		} while ($count); // close
		// protect star slash '...abc*/...abc'!
		do {
			$string = preg_replace("/(\'[^\r\n\;\,\']*?)(\*)(\/)([^\r\n;\,\']*?)(\')(?!([^\n\r\+]*?[\']))/", "$1ODdPK$4$5", $string, 1, $count);
		} while ($count); 
		// protect star slash '...abc*/...abc'!
		do {
			$string = preg_replace("/(\"[^\r\n\;\,\"]*?)(\*)(\/)([^\r\n;\,\"]*?)(\")(?!([^\n\r\+]*?[\"]))/", "$1ODdPK$4$5", $string, 1, $count);
		} while ($count);
		//// \n protect
		do {
			$string = preg_replace("/(=\s*\"[^\n\r\"]*?)(\/\/)(?=([^\n\r]*?\"\s*;))/", "$1qDdXX", $string, 1, $count);
		} while ($count); 
		do {
			$string = preg_replace("/(=\s*\"[^\n\r\"]*?)(\/\*)(?!([YZ]u\*\/))(?=([^\n\r]*?\"\s*;))/", "$1pDdYX", $string, 1, $count);
		
		} while ($count); 
		do {
			$string = preg_replace("/(=\s*\"[^\n\r\"]*?)(\*\/)(?=([^\n\r]*?\"\s*;))/", "$1ODdPK", $string, 1, $count);
		} while ($count); 
		do {
			$string = preg_replace("/(=\s*\'[^\n\r\']*?)(\/\/)(?=([^\n\r]*?\'\s*;))/", "$1qDdXX", $string, 1, $count);
		} while ($count); 
		do {
			$string = preg_replace("/(=\s*\'[^\n\r\']*?)(\/\*)(?!([YZ]u\*\/))(?=([^\n\r]*?\'\s*;))/", "$1pDdYX", $string, 1, $count);
		} while ($count); 
		do {
			$string = preg_replace("/(=\s*\'[^\n\r\']*?)(\*\/)(?=([^\n\r]*?\'\s*;))/", "$1ODdPK", $string, 1, $count);
		} while ($count); 
		// (Slash Slash) alle = " // " and = ' // ' replace by! qDdXX
		do {
			$string = preg_replace("/(\=|\()(\s*\")([^\r\n\'\"]*?[\'][^\r\n\'\"]*?)(\/)(\/)([^\r\n\'\"]*?[\'])(\s*\'[^\r\n\'\"]*?)(\/\/|qDdXX)?([^\r\n\'\"]*?[\'][^\r\n\'\"]*?[\"])(?!(\'\)|\s*[\)]?\s*\+|\'))/", "$1$2$3qDdXX$6$7qDdXX$9$10", $string, 1, $count);} while ($count); 
		do {
			$string = preg_replace("/(\=|\()(\s*\')([^\r\n\'\"]*?[\"][^\r\n\'\"]*?)(\/)(\/)([^\r\n\'\"]*?[\"])(\s*\"[^\r\n\'\"]*?)(\/\/|qDdXX)?([^\r\n\'\"]*?[\"][^\r\n\'\"]*?[\'])(?!(\'\)|\s*[\)]?\s*\+|\'))/", "$1$2$3qDdXX$6$7qDdXX$9$10", $string, 1, $count);} while ($count); 
		// (slash slash) Remove all also , or + not followed by */ and newline
		$string = preg_replace("/([^\*])(\*|[\r\n]|[^\\\\]\'|[^\\\\]\"|\,|\+|\{|;|\(|\)|\[|\]|\{|\}|\?|[^p|s]:|\&|\%|[^\\\\][a-m-o-u-s-zA-Z]|\||-|=|[0-9])(\s*)(?!([^=\\\\\&\/\"\'\^\*:]))(\/)(\/)+(?!([\r\n\*\+\"]*?([^\r\n]*?\*\/|[^\r\n]*?\"\s*\+|([^\r\n]*?=\";)))).*/", "$1$2$3", $string);
		// (slash slash star slash) Remove everhing behinde it not followed by */ or new line
		$string = preg_replace("/(\/\/\*\/)(?!([\r\n\*\+\"]*?([^\r\n]*?\*\/|[^\r\n]*?\"\s*\+|([^\r\n]*?=\";)))).*/", "", $string);
		// Remove almost all star comments except colon/**/
		$string = preg_replace("/(?!([^\n\r]*?[\'\"]))(\s*<!--.*-->)(?!(<\/div>))[^\n\r]*?.*/","$2$4", $string);
		$string = preg_replace("/([\n\r][^\n\r\*\,\"\']*?)(?=([^\*\,\:\;a-zA-Z\"]*?))(\/)(\/)+(?!([\r\n\*\+\"]*?([^\r\n]*?\*\/|[^\r\n]*?\"\s*\+|([^\r\n]*?=\";)))).*/", "$1", $string);
		$string = preg_replace("/(?!([^\n\r]*?[\'\"]))(\s*<!--.*-->)(?!(<\/div>))[^\n\r]*?(\*\/)?.*/","", $string);
		$string = preg_replace("/(<!--.*?-->)(?=(\s*<\/div>))/","", $string);
		// Restore all
		$string = preg_replace("/qDdXX/", "//", $string);  // Restore //
		$string = preg_replace("/pDdYX/", "/*", $string);   // Restore 
		$string = preg_replace("/ODdPK/", "*/", $string);   // Restore 
		$string = preg_replace("/RDdPK/", "*/*", $string);   // Restore 
		$string = preg_replace("/TDdXX/", "//*", $string);   // Restore */
		$string = preg_replace('/WQerT/', '\\\\r\\\\n" +', $string);   // Restore \r\n" + 
		$string = preg_replace('/YQerT/', '//*/\\\\r\\\\n" +', $string);   // Restore \r\n" + 
		$string = preg_replace('/AQerT/', '\\\\n', $string);   // Restore \n" 
		$string = preg_replace('/BQerT/', '\\\\r', $string);   // Restore \r"
		$string = preg_replace("/ZUQQ/", "/ /", $string);
		$string = preg_replace('/\s\/\*Zu\*\/\s/', '', $string);   // Restore \n"
		$string = preg_replace('/\s\/\*Yu\*\/\s/', '', $string);   // Restore \n"
		//// Remove all markings!
		$string = preg_replace('/(AwTc)/', '', $string);  // Start most Regex!
		$string = preg_replace('/(CwRc)/', '', $string);  // End Most regex!
		// all \s and [\n\r] repair like they where!
		$string = preg_replace("/([a-zA-Z0-9]\s?)\s*[\n\r]+(\s*[\)\,&]\s?)(\s*[\r\n]+\s*[\{])/", "$1$2$3", $string); 
		$string = preg_replace("/([a-zA-Z0-9\(]\s?)\s*[\n\r]+(\s*[;\)\,&\+\-a-zA-Z0-9]\s?)(\s*[\{;a-zA-Z0-9\,&\n\r])/", "$1$2$3", $string); 
		$string = preg_replace("/(\(\s?)\s*[\n\r]+(\s*function)/", "$1$2", $string);
		$string = preg_replace("/(=\s*\[[a-zA-Z0-9]\s?)\s*([\r\n]+)/", "$1", $string); 
		$string = preg_replace("/([^\*\/\'\"]\s*)(\/\/\s*\*\/)/", "$1", $string);
		//// Remove all /**/// .... Remove expept /**/ and followed by */ till newline!
		$string = preg_replace("/(\/\*\*\/)(\/\/(?!([^\n\r]*?\*\/)).*)/", "$1", $string);
		$string = preg_replace("/(\;\/\*\*\/)(?!([^\n\r]*?\*\/)).*/", "", $string);
		$string = preg_replace("/(\/\/\\\\\*[^\n\r\"\'\/]*?[\n\r])/", "\r\n", $string);
		$string = preg_replace("/([\r\n]\s*)(\/\*[^\r\n]*?\*\/(?!([^\r\n]*?\"\s*\+)))/", "$1", $string);
		//Remove colon /**/
		$string = preg_replace("/\/\*\*\/\s/", " ", $string);
		$string = preg_replace("/(\=\s*)(?=([^\r\n\'\"]*?\'[^\n\r\'\"]*?\'))([^\n\r\/]*?)(\/\/[^\r\n\"\'][^\r\n]*[\'\"])(\/\*\*\/)[\n\r]/", "$1$3$4\n", $string);
		$string = preg_replace("/(\=\s*)(?=([^\r\n\'\"]*?\"[^\n\r\'\"]*?\"))([^\n\r\/]*?)(\/\/[^\r\n\"\'][^\r\n]*[\'\"])(\/\*\*\/)[\n\r]/", "$1$3$4\n", $string);
		//Remove colon //
		$string = preg_replace("/([^\'\"ps\s]\s*)(\:[^\r\n\'\"\[\]]*?\'[^\n\r\'\"]*?\')([^\n\r\/a-zA-Z0-9]*?)(\/\/)[^\r\n\/\'][^\r\n]*/", "$1$2", $string);
		$string = preg_replace("/([^\'\"ps\s]\s*)(\:[^\r\n\'\"\[\]]*?\"[^\n\r\'\"]*?\")([^\n\r\/a-zA-Z0-9]*?)(\/\/)[^\r\n\/\"][^\r\n]*/", "$1$2", $string);
		$string = preg_replace("/(\"[^\n\r\'\"\+]*?\")([^\n\r\/a-zA-Z0-9]*?)(\/\/)(?!(\*|[^\r\n]*?[\\\\n\\\\r]+\s*\"\s*\+|[^\r\n]*?[\\\\n\\\\r]+\s*\'\s*\+))[^\r\n\/\"][^\r\n]*/", "$1$2", $string);
		//Remove all after ; slah slah+
		$string = preg_replace("/(;\s*)\/\/(?!([^\n\r]*?\"\s*;)).*/", "$1 \n", $string);
		//Remove: ) /* non-empty*//*xxx*/)
		$string = preg_replace('/([\n\r][^\n\r\"]*?)([^\/\"\'\*\>])\/\*(?!(\*\/))[^\n\r\"]*?[^@]\*\//', "$1$2", $string);
		//Remove // vooraf gegaan door: || | ? | , //
		$string = preg_replace("/(\|\||[\?]|\,)(\s*)\/\/(?!([^\n\r]*?\*\/|\"|\')).*/", "$1$2", $string);
		//Remove: [\n\r] after: [ \|\[\;\,\:\=\-\{\}\]\[\?\)\( ]
		$string = preg_replace("/([\|\[\;\,\:\=\-\{\}\]\[\?\)\(])\s*[\n\r]\s*[\n\r](\s*[\n\r])+/", "$1\n", $string);
		//END Remove comments.    //START Remove all whitespaces    
		$string = preg_replace('/(--\s+\>)/', 'HwRc', $string);  // protect space between: -- >
		$string = preg_replace('/\s+/', ' ', $string);
		// protect select :selected with sDdXX
		$string = preg_replace("/select :selected/", "sDdXX", $string);
		// protect ] option with oDdXX
		$string = preg_replace("/\] option/", "oDdXX", $string);
		// plus sign space plus sign should be replaced (+ +) with pDdXX
		$string = preg_replace('/\+ \+/', "pDdXX", $string); 
		$string = preg_replace('/\s*(?:(?=[=\-\+\|%&\*\)\[\]\{\};:\,\.\<\>\!\@\#\^`~]))/', '', $string);
		$string = preg_replace("/sDdXX/", "select :selected", $string);  // Restore //
		$string = preg_replace('/(?:(?<=[=\-\+\|%&\*\)\[\]\{\};:\,\.\<\>\?\!\@\#\^`~]))\s*/', '', $string);
		$string = preg_replace("/pDdXX/", "+ +", $string);  // Restore //
		$string = preg_replace("/oDdXX/", "\] option", $string);  // Restore //
		$string = preg_replace('/([^a-zA-Z0-9\s\-=+\|!@#$%^&*()`~\[\]{};:\'",<.>\/?])\s+([^a-zA-Z0-9\s\-=+\|!@#$%^&*()`~\[\]{};:\'",<.>\/?])/', '$1$2', $string);
		$string = preg_replace('/(HwRc)/', '-- >', $string);  // Repair space between: -- >
		//END Remove all whitespaces
		return $string;
	}

	public function fetch($filename, $directory = DIR_TEMPLATE) {
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

	public function set_controller($controller){
		$this->admin_controller = $controller;
	}

}
?>

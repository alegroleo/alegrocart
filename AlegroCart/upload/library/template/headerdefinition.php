<?php

final Class HeaderDefinition {

	public $CssDef = array(); //we collect every <link rel="" type="text/css"> for the catalog layout.tpl
	public $CssAdminDef = array(); //same as above, only for the admin
	public $java_script = array();
	public $java_admin_script = array();
	public $java_path = array();
	public $java_admin_path = array();
	public $css_path = array(); //we store all the css files in this array (coming from the catalog templates) for condense_css()
	public $css_admin_path = array(); //same as above, only for the admin
	public $meta_title;
	public $meta_description;
	public $meta_keywords = array();

	public function setcss($CssStyle){
		$Temp_CSS = $CssStyle;
		if (array_search($Temp_CSS, $this->css_path)===false){ //if not added yet
			$this->css_path[]=$Temp_CSS; //add it
		}
		$CssStyle = "<link rel=\"stylesheet\" type=\"text/css\" href=\"catalog/styles/" . $CssStyle . "\">"; //this goes into the <head>
		if (array_search($CssStyle,$this->CssDef)===false){ //if not added yet
			$this->CssDef[]=$CssStyle; //add it
		}
	}

	public function set_admin_css($CssStyle){
		$Temp_CSS = $CssStyle;
		if (array_search($Temp_CSS, $this->css_admin_path)===false){
			$this->css_admin_path[]=$Temp_CSS;
		}
		$CssStyle = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $CssStyle . "\">";
		if (array_search($CssStyle,$this->CssAdminDef)===false){
			$this->CssAdminDef[]=$CssStyle;
		}
	}

	public function set_javascript($JavaDef){
		$Temp_JS = "catalog/javascript/" . $JavaDef;
		if (array_search($Temp_JS, $this->java_path)===false){
			$this->java_path[]=$Temp_JS;
		}
		$JavaDef = "<script type=\"text/javascript\" src=\"catalog/javascript/" . $JavaDef . "\"></script>";
		if (array_search($JavaDef,$this->java_script)===false){
			$this->java_script[]=$JavaDef;
		}
	}

	public function set_admin_javascript($JavaDef){
		$Temp_JS = $JavaDef;
		if (array_search($Temp_JS, $this->java_admin_path)===false){
			$this->java_admin_path[]=$Temp_JS;
		}
		$JavaDef = "<script type=\"text/javascript\" src=\"" . $JavaDef . "\"></script>";
		if (array_search($JavaDef,$this->java_admin_script)===false){
			$this->java_admin_script[]=$JavaDef;
		}
	}

	public function set_MetaTitle($CssTitle){
		if (strpos($this->meta_title,$CssTitle)===false){
			$this->meta_title.= $CssTitle;
		}
	}

	public function get_MetaTitle(){
		$title = substr($this->meta_title,0,60);
		return $title;
	}

	public function set_MetaDescription($CssDescription) {
		if(strpos($this->meta_description,$CssDescription)===false){
			$this->meta_description.= htmlspecialchars($CssDescription) .',';
		}
	}

	public function get_MetaDescription() {
		$description = "<meta name=\"description\" content=\"". substr($this->meta_description,0,200)."\">";
		return $description; 
	}

	public function set_MetaKeywords($CssKeywords) {
		$keywords = array_slice(preg_split('#\,\s?#',$CssKeywords),0);
		$union = array_unique(array_merge($this->meta_keywords,$keywords));
		$this->meta_keywords = $union;
	}

	public function get_MetaKeywords(){
		$keywords = htmlspecialchars(substr(implode(', ',$this->meta_keywords),0,500));
		$keywords = "<meta name=\"keywords\" content=\"" . $keywords . "\">";
		return $keywords;
	}

	public function set_Fb(){
		$fb = '<div id="fb-root"></div><script>window.fbAsyncInit = function(){FB.init({xfbml: true,version: \'v2.3\'});}; (function(d, s, id){public js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) {return;} js = d.createElement(s); js.id = id; js.src="https://connect.facebook.net/en_US/sdk.js"; fjs.parentNode.insertBefore(js, fjs);}(document, \'script\', \'facebook-jssdk\'));</script>';
		return $fb;
	}

}
?>

<?php
Class HeaderDefinition {
  var $CssDef = array();
  var $java_script = array();
  var $meta_title;
  var $meta_description;
  var $meta_keywords = array();
  
  function setcss($CssStyle){
    $CssStyle = "<link rel=\"stylesheet\" type=\"text/css\" href=\"catalog/styles/" . $CssStyle . "\">";
    if (array_search($CssStyle,$this->CssDef)===false){
      $this->CssDef[]=$CssStyle;
    }
  }
  function set_javascript($JavaDef){
    $JavaDef = "<script type=\"text/javascript\" src=\"catalog/javascript/" . $JavaDef . "\"></script>";
    if (array_search($JavaDef,$this->java_script)===false){
      $this->java_script[]=$JavaDef;
    }
  }  
  function set_MetaTitle($CssTitle){
    if (strpos($this->meta_title,$CssTitle)===false){
      $this->meta_title.= $CssTitle;
    }
  }
  function get_MetaTitle(){
    $title = substr($this->meta_title,0,60);
    return $title;
  }
  function set_MetaDescription($CssDescription) {
    if(strpos($this->meta_description,$CssDescription)===false){
      $this->meta_description.= htmlspecialchars($CssDescription) .',';
    }
  }
  function get_MetaDescription() {
    $description = "<meta name=\"description\" content=\"". substr($this->meta_description,0,200)."\">";
    return $description; 
  }  
  function set_MetaKeywords($CssKeywords) {
    $keywords = array_slice(preg_split('#\,\s?#',$CssKeywords),0);
    $union = array_unique(array_merge($this->meta_keywords,$keywords));
    $this->meta_keywords = $union;
  }
  function get_MetaKeywords(){
    $keywords = htmlspecialchars(substr(implode(', ',$this->meta_keywords),0,500));
    $keywords = "<meta name=\"keywords\" content=\"" . $keywords . "\">";
    return $keywords;  
  }
}
?>
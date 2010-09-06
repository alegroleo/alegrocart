<?php

//IIS REQUEST_URI compatibility
// See: http://neosmart.net/blog/2006/100-apache-compliant-request_uri-for-iis-and-windows/
if (!isset($_SERVER['REQUEST_URI'])) {
  //ISAPI_Rewrite 3.x
  if (isset($_SERVER['HTTP_X_REWRITE_URL'])){
    $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
  }
  //ISAPI_Rewrite 2.x w/ HTTPD.INI configuration
  elseif (isset($_SERVER['HTTP_REQUEST_URI'])){
    $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_REQUEST_URI'];
  }
  //ISAPI_Rewrite isn't installed or not configured
  else {
    $_SERVER['HTTP_REQUEST_URI']=isset($_SERVER['SCRIPT_NAME'])?$_SERVER['SCRIPT_NAME']:$_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) $_SERVER['HTTP_REQUEST_URI'] .= '?'.$_SERVER['QUERY_STRING'];
    $_SERVER['REQUEST_URI']=$_SERVER['HTTP_REQUEST_URI'];
  }
}

?>
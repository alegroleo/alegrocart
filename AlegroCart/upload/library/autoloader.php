<?php
/**
 *
 * @param string $className Class or Interface name automatically
 *              passed to this function by the PHP Interpreter
 */
function autoLoader($className){
    //Directories added here must be
//relative to the script going to use this file.
//New entries can be added to this list
    $directories = array(
      '../',
      'library/',
      'library/application/',
      'library/cache/',
      'library/cart/',
      'library/common/',
      'library/database/',
      'library/environment/',
      'library/filesystem/',
      'library/image/',
      'library/language/',
      'library/mail/',
	  'library/payments/',
      'library/session/',
      'library/template/',
      'library/user/',
      'library/validate/',
    );
    $directories = glob(DIR_LIBRARY.'*', GLOB_ONLYDIR);

    //Add your file naming formats here
    $fileNameFormats = array(
      '%s.php'
    );

    // this is to take care of the PEAR style of naming classes
    $path = str_ireplace('_', '/', $className);
   
    foreach($directories as $directory){
        foreach($fileNameFormats as $fileNameFormat){
            $path = $directory.DIRECTORY_SEPARATOR.sprintf($fileNameFormat, $className);
            if(file_exists($path)){
                require_once $path;
                return;
            }
        }
    }
}

spl_autoload_register('autoLoader');
?>

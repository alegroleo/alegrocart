<?php
// Heading
$_['heading_title']    = 'AlegroCart Installation';
$_['heading_step1']    = 'Step 1/3 - Database Details';
$_['heading_step2']    = 'Step 2/3 - Administration';
$_['heading_step3']    = 'Finished!';

// Error
$_['error_not_found']  = '%s was not found! (ensure you have uploaded it)';
$_['error_not_777']    = '%s is not writable! (chmod a+w or chmod 777)';
$_['error_not_666']    = '%s is not writable! (chmod a+w or chmod 666)';
$_['error_php']        = 'PHP 5.0 or greater is required for AlegroCart';
$_['error_session']    = 'You must disable session.auto_start in php.ini to use AlegroCart';
$_['error_mysql']      = 'MySql extension is required to run AlegroCart';
$_['error_gd']         = 'GD extension is required to run AlegroCart';
$_['error_upload']     = 'File uploads is required to be enable to run AlegroCart';
$_['error_zlib']       = 'ZLIB must be loaded in php.ini for AlegroCart to work';
$_['error']            = 'The following errors occured:';
$_['error_fix']        = 'Please fix the above error(s), install halted!';
$_['error_dbhost']     = 'Database Host is required';
$_['error_dbuser']     = 'Database Username is required';
$_['error_dbname']     = 'Database Name is required';
$_['error_dbconnect']  = 'Could not connect to the database server using the username and password provided.';
$_['error_dbperm']     = 'The database could not be accessed. Check that you have permissions, and that it exists on the server.';
$_['error_sql']        = 'Install SQL file %s could not be found.';
$_['error_new_admin_name']= 'New name for the admin directory is required!';
$_['error_admin_uname']= 'Admin username is required';
$_['error_admin_passw']= 'Admin password is required';
$_['error_write']      = 'Could not write to %s file.';
$_['error_open']       = 'Could not open %s file for writing.';
$_['error_rename']     = 'The admin directory cannot be renamed. Rename it manually e.g. via FTP connection then reload this page.';
$_['error_alphanumeric']= 'Use only the lower case letters of the English alphabet, the underscore, the hyphen and the numbers, without space,';
$_['error_length']     = 'The new name must be greater than 5 and less than 15 characters!';
$_['error_restricted'] = 'The \'%s\' is not permitted name.';
$_['error_dir']        = 'Error in the directory structure.';
$_['error_post']       = 'The given directory name doesn\'t match the renamed one.';

// Text
$_['ac']               = 'AlegroCart Home';
$_['acforum']          = 'AlegroCart Support';
$_['fresh']            = 'THIS IS FOR FRESH INSTALLS ONLY! YOUR DATABASE WILL BE REMOVED!';
$_['database_details'] = 'Please enter your database connection details.';
$_['dbhost']           = 'Database Host:';
$_['dbuser']           = 'Database Username:';
$_['dbpassw']          = 'Database Password:';
$_['dbname']           = 'Database Name:';
$_['continue']         = 'Continue';
$_['rename']           = 'To hide your administration directory from malicious activity, please enter new name for the main admin directory. This directory name must differ from \'admin\' or \'administration\' because those are not permitted under the new security structure. The uploaded admin directory will be changed to this new name and this directory will contain all your admin files.';
$_['new_admin']        = 'New name for the admin directory:';
$_['admin_details']    = 'Please enter a username and password for the administration.';
$_['uname']            = 'Username:';
$_['passw']            = 'Password:';
$_['success']          = '%s was updated successfully.';
$_['config']           = 'Make \'config.php\' unwritable (chmod go-w or chmod 644).';
$_['htaccess']         = 'Make \'.htaccess\' unwritable (chmod go-w 644).';
$_['congrat']          = 'Congratulations! You have successfully installed AlegroCart.';
$_['congrat_upg']      = 'Congratulations! You have successfully upgraded AlegroCart.';
$_['shop']             = 'Online Shop';
$_['admin']            = 'Administration';
$_['method']           = 'Install method:';
$_['default_install']  = 'Default Install';
$_['clean_install']    = 'Clean Install';
$_['default_expl']     = 'This will install sample products, categories, manufacturers, images, reviews etc. as well. This is for testing AlegroCart';
$_['clean_expl']       = 'This will remove every product and product related data. This is for creating a clean installation with no products.';
$_['rename_expl']      = 'Use only the lower case letters of the English alphabet, the underscore, the hyphen and the numbers, without space. The new name must be greater than 5 and less than 15 characters!';
?>

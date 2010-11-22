<?php

// compatibility check
$errors=array();
if (phpversion() < '5.0') {
	$errors[] = '<a href="http://www.php.net/downloads.php">PHP 5.0</a> or above is required.';
}
if (!ini_get('file_uploads')) {
	$errors[] = '<a href="http://www.php.net/features.file-upload">file_uploads</a> must be enabled.';
}
if (ini_get('session.auto_start')) {
	$errors[] = '<a href="http://www.php.net/manual/en/session.configuration.php#ini.session.auto-start">session.auto_start</a> must be enabled.';
}
if (!extension_loaded('mysql')) {
	$errors[] = '<a href="http://www.php.net/manual/en/mysql.installation.php">MySQL extension</a> must be loaded.';
}
if (!extension_loaded('gd')) {
	$errors[] = '<a href="http://www.php.net/manual/en/image.installation.php">GD extension</a> must be loaded.';
}
if (!empty($errors)) {
	foreach ($errors as $error) { echo '<div><strong>Error:</strong> '.$error.'</div>'."<br>\n"; }
	exit;
}

?>
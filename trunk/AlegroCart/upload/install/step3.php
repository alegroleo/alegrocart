<?php

if (!$step) { header('Location: .'); die(); }

if (filesize('../config.php') == 0) { //install is already done...

if (empty($_POST['username'])) { $errors[] = $language->get('error_admin_uname'); }
if (empty($_POST['password'])) { $errors[] = $language->get('error_admin_passw'); }

if (!$errors) {
		//replace existing config with new one
		$newfile='default.config.php';
		$file='../config.php';
		$str=file_get_contents($newfile);
		if ($handle = fopen($file, 'w')) {
			$reps=array(
				'DIR_BASE' => addslashes(DIR_BASE),
				'HTTP_BASE' => HTTP_BASE,
				'DB_HOST' => isset($_POST['db_host'])?$_POST['db_host']:'',
				'DB_USER' => isset($_POST['db_user'])?$_POST['db_user']:'',
				'DB_PASSWORD' => isset($_POST['db_pass'])?$_POST['db_pass']:'',
				'DB_NAME' => isset($_POST['db_name'])?$_POST['db_name']:''
			);
			foreach ($reps as $key => $val) {
				$str=preg_replace("/($key', ')(.*?)(')/", '${1}'.addslashes($val).'$3', $str);
			}

			if (fwrite($handle, $str)) {
				echo "<p class=\"a\">".$language->get('success',$file)."</p>\n";
				fclose($handle);
			}
			else { $errors[]=$language->get('error_write',$file); }
		} 
		else { $errors[]="<b>$language->get('error_open',$file)"; }
		unset($str);

		//change .htaccess if necessary
		$pieces = array_filter(explode('/', HTTP_BASE));
		$pieces = array_slice($pieces,2);
		if (array_filter($pieces)) {
		$rwb = implode('/', $pieces);
		$rwb = "/".$rwb."/";
		$file2='../.htaccess';
		if ($handle2 = fopen($file2, 'w')) {
			$content  = '# Uncomment this to ensure that register_globals is Off'."\n";
			$content .= '# php_flag register_globals Off'."\n";
			$content .= "\n";
			$content .= '# URL Alias - see install.txt'."\n";
			$content .= '# Prevent access to .tpl'."\n";
			$content .= '<Files ~ "\.tpl$">'."\n";
			$content .= 'Order allow,deny'."\n";
			$content .= 'Deny from all'."\n";
			$content .= '</Files>'."\n";
			$content .= "\n";
			$content .= 'Options +FollowSymlinks'."\n";
			$content .= "\n";
			$content .= '<IfModule mod_rewrite.c>'."\n";
			$content .= 'RewriteEngine On'."\n";
			$content .= "\n";
			$content .= 'RewriteBase '.$rwb."\n";
			$content .= "\n";
			$content .= '# AlegroCart REWRITES START'."\n";
			$content .= 'RewriteCond %{REQUEST_FILENAME} !-f'."\n";
			$content .= 'RewriteCond %{REQUEST_FILENAME} !-d'."\n";
			$content .= 'RewriteRule ^(.*) index.php?$1 [L,QSA]'."\n";
			$content .= '# AlegroCart REWRITES END'."\n";
			$content .= "\n";
			$content .= '</IfModule>'."\n";
			$content .= '# Try if you have problems with url alias'."\n";
			$content .= '# RewriteRule ^(.*) index.php [L,QSA]'."\n";
			$content .= "\n";
			$content .= '# Focus on one domain - Uncomment to use'."\n";
			$content .= '# RewriteCond %{HTTP_HOST} !^www\.example\.com$ [NC]'."\n";
			$content .= '# RewriteRule ^(.*)$ http://www.example.com/$1 [R=301,L]'."\n";

		if (fwrite($handle2, $content)) {
			echo "<p class=\"a\">".$language->get('success',$file2)."</p>\n";
			fclose($handle2);
		} else { 
			$errors[]=$language->get('error_write',$file2); 
		}
		} else { 
			$errors[]="<b>$language->get('error_open',$file2)"; 
		}
		unset($content);
}
}

if (!$errors) {
	if (!$link = @mysql_connect($_POST['db_host'], $_POST['db_user'], $_POST['db_pass'])) {
		$errors[] = $language->get('error_dbconnect');
	}
	else {
		if (!@mysql_select_db($_POST['db_name'], $link)) {
			$errors[] = $language->get('error_dbperm');
		}			
	}
}
						
if (!$errors) {
	mysql_query('set character set utf8', $link);
	mysql_query('set @@session.sql_mode="MYSQL40"', $link);
	mysql_query("delete from user where user_id = '1'", $link);
	mysql_query("insert into `user` set user_id = '1', user_group_id = '1', username = '" . mysql_real_escape_string($_POST['username']) . "', password = '" . mysql_real_escape_string(md5($_POST['password'])) . "', date_added = now()", $link);
	mysql_close($link);
}

} //end install check

if ($errors && $step == 3) {
	require('step2.php');
} else {

	if ($_POST['method']=='clean') {

	      $unneeded_files=array('BRE_PE4013HWPCAT.jpg',
				    'Featured1.jpg',
				    'Featured2.jpg',
				    'Featured3.jpg',
				    'Featured4.jpg',
				    'Featured5.jpg',
				    'Featured6.jpg',
				    'Featured7.jpg',
				    'Featured8.jpg',
				    'Featured9.jpg',
				    'HomepageDemo.gif',
				    'HOP_40955.jpg',
				    'HoppyLogo.png',
				    'image_1.jpg',
				    'image_2.jpg',
				    'image_3.jpg',
				    'image_4.jpg',
				    'Latest1.jpg',
				    'Latest2.jpg',
				    'Latest3.jpg',
				    'PEF_0369-10.jpg',
				    'PerformanceFriction.jpg',
				    'Related1.jpg',
				    'Related2.jpg',
				    'Related3.jpg',
				    'Related4.jpg',
				    'Related5.jpg',
				    'Related6.jpg',
				    'Related7.jpg',
				    'Related8.jpg',
				    'Related9.jpg',
				    'Related10.jpg',
				    'Related11.jpg',
				    'Shopping.gif',
				    'Specials1.jpg',
				    'Specials2.jpg',
				    'Specials3.jpg',
				    'Specials4.jpg',
				    'Specials5.jpg',
				    'Specials6.jpg'
				    ); 

		foreach ($unneeded_files as $unneeded_file) {
			if (file_exists(DIR_IMAGE . $unneeded_file)) {
				unlink(DIR_IMAGE . $unneeded_file);
			} 
		}
	}

$file = DIR_BASE.'config.php';
$file2 = DIR_BASE.'.htaccess';
@chmod($file, 0644);
@chmod($file2, 0644);
?>

<div id="content">
  <div class="warning"><?php echo $language->get('del_inst')?></div>
  <?php if(is_writable($file)) { ?>
	<div class="warning"><?php echo $language->get('config')?></div>
        <div class="warning"><?php echo $language->get('htaccess')?></div>
 <?php }?>
  <p class="a"><?php echo $language->get('congrat')?></p>
</div>
<div id="buttons">
<form>
	<input type="button" value="<?php echo $language->get('shop')?>" class="button" onclick="location='<?php echo HTTP_CATALOG; ?>';">
	<input type="button" value="<?php echo $language->get('admin')?>" class="button" onclick="location='<?php echo HTTP_ADMIN; ?>';">
</form>
</div>
<?php } ?>

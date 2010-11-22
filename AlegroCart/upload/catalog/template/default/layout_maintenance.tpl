<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html dir="<?php echo $direction; ?>" lang="<?php echo $code; ?>">
<head> 
<?php $pageColumns = $this->page_columns;?>
<title><?php if ($head_def->meta_title){echo $head_def->get_MetaTitle(); } else if  (@$meta_title){echo $meta_title;} else {echo $title;} ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>">
<meta name="robots" content="NOINDEX, NOFOLLOW">
<base href="<?php echo $base; ?>">

<?php                     // New CSS Generator
	$css_dir = $this->style . '/css' . $pageColumns;
	if ($head_def->CssDef){
		foreach ($head_def->CssDef as $pagecss){
			echo str_replace($this->style . '/css', $css_dir, $pagecss)."\n";
		}
    }
	if($head_def->java_script){
		foreach ($head_def->java_script as $pagejs){
			echo $pagejs."\n";
		}
	}
?>
<?php $page_color = $this->color;?>
<link rel="stylesheet" type="text/css" href="catalog/styles/<?php echo $this->style; ?>/colors<?php echo $pageColumns;?>/<?php echo $page_color;?>">
<link rel="alternate" type="application/rss+xml" title="<?php echo $title; ?>" href="rss.php">
<link rel="shortcut icon" type="image/x-icon" href="image/favicon.ico">
<!--[if lte IE 6]>
  <link rel="stylesheet" type="text/css" href="catalog/styles/<?php echo $this->style; ?>/css<?php echo $pageColumns;?>/ie6fix.css">
<![endif]-->
</head>
<body>
<div id="container">
  <div id="header">
  <?php if (isset($header)) { echo $header; } ?>
  </div>
  <div id="column">
  </div>
  <div id="content">
  <?php if (isset($content)) echo $content; ?>
  </div>
  <div id="columnright">
  </div>	
  <?php if (isset($footer)) { ?>
  <div id="footer"><?php echo $footer; ?></div>
  <?php } ?>
</div>
</body>
</html>
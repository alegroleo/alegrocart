<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html dir="<?php echo $direction; ?>" lang="<?php echo $code; ?>">
<head> 
<title><?php if ($head_def->meta_title){echo $head_def->get_MetaTitle(); } else if  (@$meta_title){echo $meta_title;} else {echo $title;} ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>">
<meta name="robots" content="NOINDEX, NOFOLLOW">
<base href="<?php echo $base; ?>">
<?php $pageColumns = isset($tpl_columns) ? $tpl_columns : $this->page_columns;
  $this->page_columns = $pageColumns;
  $pageColumns == 1.2 || $pageColumns == 2.1 ? $cssColumns = 2 : $cssColumns = $pageColumns;
  $this->cssColumns = $cssColumns;
  $this->color = isset($template_color) ? $template_color : $this->color;
?>
<?php if ($this->condense){ 
	echo $this->condense_css('css_path')."\n";
	echo $this->condense_js('js_path')."\n";
} else { ?>
	<?php $css_dir = $this->style . '/css' . $this->cssColumns; ?>
	<?php if ($head_def->CssDef){ 
		foreach ($head_def->CssDef as $pagecss){
			echo str_replace($this->style . '/css', $css_dir, $pagecss)."\n";
		}
	}
	?>
	<link rel="stylesheet" type="text/css" href="catalog/styles/<?php echo $this->style; ?>/colors<?php echo $this->cssColumns;?>/<?php echo $this->color;?>">
	<?php if($head_def->java_script){
		foreach ($head_def->java_script as $pagejs){
			echo $pagejs."\n";
		}
	}
}
?>

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

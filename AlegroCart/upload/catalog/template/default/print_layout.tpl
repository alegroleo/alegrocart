<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html dir="<?php echo $direction; ?>" lang="<?php echo $code; ?>">
<head>
<?php $request = $locator->get('request');?>
<title><?php if ($head_def->meta_title){echo $head_def->get_MetaTitle(); } else if  (@$meta_title){echo $meta_title;} else {echo $title;} ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>">
<?php if ($head_def->meta_description){ echo $head_def->get_MetaDescription()."\n"; } else if  (@$meta_description){echo $meta_description."\n";}else { ?>
<meta name="description" content="* Meta Description Goes here *">
<?php } ?>
<?php if ($head_def->meta_keywords){ echo $head_def->get_MetaKeywords() ."\n"; } else if (@$meta_keywords){echo $meta_keywords. "\n";} else { ?>
<meta name="keywords" content=" * Meta Keywords go here *">
<?php } ?>
<meta name="robots" content="INDEX, FOLLOW">
<base href="<?php echo $base; ?>">
<?php $pageColumns = isset($tpl_columns) ? $tpl_columns : $this->page_columns;
  $this->page_columns = $pageColumns;
  $pageColumns == 1.2 || $pageColumns == 2.1 ? $cssColumns = 2 : $cssColumns = $pageColumns;
  $this->cssColumns = $cssColumns;
  $this->color = isset($template_color) ? $template_color : $this->color;
?>
<?php 
  $head_def->setcss($this->style . "/css/print.css");
?>
<?php if ($this->condense){ 
	echo $this->condense_css(false, false)."\n";
	echo $this->condense_js()."\n";
} else { ?>
	<?php $css_dir = $this->style . '/css' . $this->cssColumns; ?>
	<?php if ($head_def->CssDef){ 
		foreach ($head_def->CssDef as $pagecss){
			echo str_replace($this->style . '/css', $css_dir, $pagecss)."\n";
		}
	}
	?>
	<?php if($head_def->java_script){
		foreach ($head_def->java_script as $pagejs){
			echo $pagejs."\n";
		}
	}
}
?>

  <script type="text/javascript">
    $(document).ready(function() {
	  $('#printMe').on("click", function() {
		window.print();
		return false;
	  });
	});
  </script>

<link rel="alternate" type="application/rss+xml" title="<?php echo $title; ?>" href="rss.php">
<link rel="shortcut icon" type="image/x-icon" href="image/favicon.ico">
</head>
<body>
<noscript><div class="noscript"><?php echo $noscript; ?></div></noscript>
<div id="container">
  <div id="header">
	  <div class="c"><a href="<?php echo $continue;?>"><img class="back_button" src="catalog/styles/<?php echo $this->style;?>/image/button_back.png" width=32 height=32 alt="<?php echo $back;?>" title="<?php echo $back;?>"></a>
	  <img class="print_button" id="printMe" src="catalog/styles/<?php echo $this->style;?>/image/print32.png" width=32 height=32 alt="<?php echo $print;?>" title="<?php echo $print;?>">
	  </div>
	  <div class="b"><?php echo $config_owner . '<br>' . $config_address;?></div>
	  <?php if(isset($store_logo)){?>
	  <div class="a"><img src="<?php echo $store_logo;?>" width="<?php echo $logo_width;?>" height="<?php echo $logo_height;?>" alt="<?php echo $store;?>" ></div>
	  <?php }?>
	  <div class="clearfix"></div>
  </div>

  <div id="content">
    <?php 
		if (isset($tpl_contents)){
			foreach($tpl_contents as $tpl_content){
				if (@isset($$tpl_content)) {echo @$$tpl_content;}
			}
		} else {
			if (isset($content)) {echo $content;}
		}
    ?>  
  </div>
  <div class="clearfix"></div>
</div>
</body>
</html>

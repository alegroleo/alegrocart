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
<?php if ($this->condense){ 
	echo $this->condense_css()."\n";
	echo $this->condense_js()."\n";
} else { ?>
	<link rel="stylesheet" type="text/css" href="catalog/styles/<?php echo $this->style; ?>/css<?php echo $this->cssColumns;?>/default.css">
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
  <link rel="stylesheet" type="text/css" href="catalog/styles/<?php echo $this->style; ?>/css<?php echo $cssColumns;?>/ie6fix.css">
<![endif]-->
</head>
<body>
<noscript><div class="noscript"><?php echo $noscript; ?></div></noscript>
<div id="container">
<?php if($category_menu){?>
  <div id="header" style="height:140px;">
<?php } else {?>  
  <div id="header">
<?php }?>
	<?php if(isset($tpl_headers)){
		foreach ($tpl_headers as $key => $tpl_header){
			if(@isset($$tpl_header)){echo $$tpl_header;}
			
		}
	} else {
		if (isset($language)) { echo $language; }
		if (isset($currency)) { echo $currency; }
		if (isset($header)) { echo $header; } 
		if (isset($search)) { echo $search; }
		if (isset($navigation)) { echo $navigation;}
		if ($pageColumns == 2.1 || $pageColumns == 1){
			if (isset($categorymenu)) {echo $categorymenu;}
		}
		if ($pageColumns == 1){
			if (isset($cart)) {echo $cart;}
		}
	}?>
  </div>
  <?php if(isset($tpl_extras)){?>
  <div id="extra">
	<?php foreach($tpl_extras as $extra){
			if(@isset($$extra)){echo @$$extra;}
		}?>
  </div>
  <?php }?>
  <?php if($pageColumns == 1.2 || $pageColumns == 3){?>
  <div id="column">
    <?php if(isset($tpl_left_columns)){
		foreach ($tpl_left_columns as $left_column){
			if(@isset($$left_column)) {echo $$left_column;}
		}
	} else {
		if ($pageColumns == 1.2){
			if (isset($searchoptions)){ echo $searchoptions;}
			if (isset($categoryoptions)){ echo $categoryoptions;}
			if (isset($boughtoptions)){ echo $boughtoptions;}
		}
		if (isset($cart)) {echo $cart;}
		if (isset($category)) {echo $category;}
		if (isset($manufacturer)) { echo $manufacturer;}
		if (isset($popular)) {echo $popular;}
		if (isset($review)) {echo $review;}
		if (isset($information)) { echo $information;}
	}?>
  </div>
  <?php }?>
  <div id="content">
    <?php 
		if (isset($tpl_contents)){
			foreach($tpl_contents as $tpl_content){
				if (@isset($$tpl_content)) {echo @$$tpl_content;}
			}
		} else {
			if (isset($content)) {echo $content;}
			if (isset($homepage)) {echo $homepage;}
			if (isset($featured)) {echo $featured;}
			if (isset($latest)) {echo $latest;}
			if(($pageColumns == 1.2 || $pageColumns == 2.1) && $request->get('controller') == 'product'){
				if (isset($specials)) {echo $specials;}
				if (isset($related)) {echo $related;}
			}
		}
    ?>
  </div>
  <?php if($pageColumns == 3 || $pageColumns == 2.1){?>
  <div id="columnright">
    <?php
		if (isset($tpl_right_columns)){
			foreach($tpl_right_columns as $right_column){
				if (@isset($$right_column)){echo @$$right_column;}
			}
		} else {
			if (isset($searchoptions)){ echo $searchoptions;}
			if (isset($categoryoptions)){ echo $categoryoptions;}
			if (isset($boughtoptions)){ echo $boughtoptions;}
			if ($pageColumns == 2.1){
				if (isset($cart)) {echo $cart;}
				if (isset($manufacturer)) { echo $manufacturer;}
				if (isset($popular)) {echo $popular;}
				if (isset($review)) {echo $review;}
				if (isset($information)) { echo $information;}
			} else {
				if (isset($manufactureroptions)){ echo $manufactureroptions;}
				if (isset($specials)) {echo $specials;}
				if (isset($related)) {echo $related;}
			}
		}
    ?>
  </div>
  <?php }?>
  <?php if (isset($footer) || isset($tpl_footers)) {
		echo '<div id="footer">' . "\n";
		if (isset($tpl_footers)){
			foreach($tpl_footers as $tpl_footer){
				if (@isset($$tpl_footer)){echo @$$tpl_footer;}
			}
		} else {
			echo $footer;
			if ($pageColumns == 1){
				if (isset($information)){ echo $information;}
			}
		}
		echo '</div>' . "\n";
	}
  ?>

  <div id="pagebottom">
	<?php if(isset($tpl_bottom)){?>
		<?php foreach($tpl_bottom as $pageBottom){
			if(@isset($$pageBottom)){echo @$$pageBottom;}
		}?>
	<?php } else { 
		if (isset($developer)){ echo $developer;}
	}?>
  </div>
  
</div>
<?php if (isset($time)) { ?>
<div id="time"><?php echo $time; ?></div>
<?php } ?>
<?php if ($this->social && $request->get('controller') == 'product') { ?>
	<?php echo $head_def->set_Fb(); ?>
<?php } ?>
<script>
function init() {
	var imgDefer = document.getElementsByTagName('img');
	for (var i=0, len =imgDefer.length; i<len; i++) {
		if(imgDefer[i].getAttribute('data-src')) {
			imgDefer[i].setAttribute('src',imgDefer[i].getAttribute('data-src'));
		}
	}
}
window.onload = init;
</script> 
</body>
</html>

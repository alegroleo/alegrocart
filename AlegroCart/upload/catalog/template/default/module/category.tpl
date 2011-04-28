<?php 
  $head_def->setcss($this->style . "/css/category.css");
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("ajax/jqueryadd2cart.js");
?>
<div>
  <div class="headingcolumn"><h1><?php echo $heading_title; ?></h1></div>
  <div id="category_menu" class="category">
  <div class="menu_breadcrumb" id="menu_breadcrumb"></div>
  <?php
	$output = '<ul>'."\n";
	$level = 0;
	foreach ($categories as $key => $category) {
	  if ($key< count($categories)-1){
		if($categories[$key+1]['level'] > $level){
		  $output .= "\t".'<li class="'. $category['status'].'">';
		  $output .= '<a class="'. $category['class'].$category['state'] . '" href="' . $category['href'] . '">';
		  $output .= $category['name'] . '<img src="catalog/styles/' . $this->style . '/image/arrow.png"></a>' . "\n";
		  $ul = ($categories[$key+1]['status'] == 'enabled') ? '<ul style="display:'.$categories[$key+1]['type'].'">': '<ul class="menu" style="display:'.$categories[$key+1]['type'].'">';
		  $output .= $ul . "\n";
		  $level++;
		} else if($categories[$key+1]['level'] < $level){
		  $output .= "\t".'<li class="'. $category['status'] . '">';
		  $output .= '<a class="'. $category['class'].$category['state'] . '" href="' . $category['href'] . '">';
		  $output .= $category['name'] . '</a></li>' . "\n";
		  while ($categories[$key+1]['level'] < $level){
		    $output .= '</ul></li>'. "\n";
			$level--;
		  }
		} else{
		  $output .= "\t".'<li class="'. $category['status'] . '">';
		  $output .= '<a class="'. $category['class'].$category['state'] . '" href="' . $category['href'] . '">';
		  $output .= $category['name'] . '</a></li>' . "\n";
		}
	  } else {
		$output .= "\t".'<li class="'. $category['status'] . '">';
		$output .= '<a class="'. $category['class'].$category['state'] . '" href="' . $category['href'] . '">';
		$output .= $category['name'] . '</a></li>' . "\n";
	  }
	}
	while ($level >0){
	  $output .= '</ul></li>'. "\n";
	  $level--;
	}
	$output .= '</ul>' . "\n";
	echo $output;
  ?>
  </div>
<div class="columnBottom"></div>
</div>
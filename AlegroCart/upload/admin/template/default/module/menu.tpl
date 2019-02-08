<?php 
  $this->head_def->set_admin_css("template/".$this->directory."/css/acmenu.css");
  $this->head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $this->head_def->set_admin_javascript("javascript/ACMenu/ACMenu.js");
?>
  <div id="myMenuID" class="myMenuID">
  <div class="menu_breadcrumb" id="menu_breadcrumb"></div>
  <?php
	$image_path = "template/$this->directory/image/acmenu/";
	$output = '<ul id="menu_lvl_0">'."\n";
	$level = 0;
	foreach ($menus as $key => $menu) {
	  if ($key< count($menus)-1){
		if($menus[$key+1]['level'] > $level){
		  $output .= "\t".'<li class="'. $menu['status'].'" id="' . $menu['id'] . '">';
		  $output .= '<a class="'. $menu['class'].$menu['state'] . '"';
		  $output .= ($menu['href'] ? ' href="' . $menu['href'] . '"' : '');
		  $output .= ($menu['new_tab'] ? ' target="_blank"' : '') . '>';
		  $output .= $menu['image'] ? '<img class="icon" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="' . $image_path . $menu['image'] . '">' : '';

		  $output .= (!$menu['href'] ? '<img class="arrow" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="' .$image_path .'/arrow.png">' : '');
		  $output .= '<span>'.$menu['name'] . '</span></a>' . "\n";
		  $ul = ($menus[$key+1]['status'] == 'enabled') ? '<ul style="display:'.$menus[$key+1]['type'].'" id="' . $menus[$key+1]['class'] . '" >': '<ul class="menu" style="display:'.$menus[$key+1]['type'].'" id="' . $menus[$key+1]['class'] . '" >';
		  $output .= $ul . "\n";
		  $level++;
		} else if($menus[$key+1]['level'] < $level){
		  $output .= "\t".'<li class="'. $menu['status'].'" id="' . $menu['id'] . '">';
		  $output .= '<a class="'. $menu['class'].$menu['state'] . '"';
		  $output .= ($menu['href'] ? ' href="' . $menu['href'] . '"' : '');
		  $output .= ($menu['new_tab'] ? ' target="_blank"' : '') . '>';
		  $output .= $menu['image'] ? '<img class="icon" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="' . $image_path . $menu['image'] . '">' : '';
		  $output .= '<span>'.$menu['name'] . '</span>';
		  $output .= (!$menu['href'] ? '<img class="arrow" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="' .$image_path .'/arrow.png">' : '') . '</a></li>' . "\n";
		  while ($menus[$key+1]['level'] < $level){
		    $output .= '</ul></li>'. "\n";
			$level--;
		  }
		} else{
		  $output .= "\t".'<li class="'. $menu['status'].'" id="' . $menu['id'] . '">';
		  $output .= '<a class="'. $menu['class'].$menu['state'] . '"';
		  $output .= ($menu['href'] ? ' href="' . $menu['href'] . '"' : '');
		  $output .= ($menu['new_tab'] ? ' target="_blank"' : '') . '>';
		  $output .= $menu['image'] ? '<img class="icon" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="' . $image_path . $menu['image'] . '">' : '';
		  $output .= '<span>'.$menu['name'] . '</span>';
		  $output .= (!$menu['href'] ? '<img class="arrow" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="' .$image_path .'/arrow.png">' : '') . '</a></li>' . "\n";
		}
	  } else {
		$output .= "\t".'<li class="'. $menu['status'].'" id="' . $menu['id'] . '">';
		$output .= '<a class="'. $menu['class'].$menu['state'] . '"';
		$output .= ($menu['href'] ? ' href="' . $menu['href'] . '"' : '');
		  $output .= ($menu['new_tab'] ? ' target="_blank"' : '') . '>';
		$output .= $menu['image'] ? '<img class="icon" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="' . $image_path . $menu['image'] . '">' : '';
		$output .= '<span>'.$menu['name'] . '</span>';
		  $output .= (!$menu['href'] ? '<img class="arrow" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="' .$image_path .'/arrow.png">' : '') . '</a></li>' . "\n";
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

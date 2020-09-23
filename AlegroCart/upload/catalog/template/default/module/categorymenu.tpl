<?php 
  $head_def->setcss($this->style . "/css/categorymenu.css");
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("ajax/jqueryadd2cart.js");
?>
  <div id="myMenuID" class="myMenuID">
  <div class="hmenu_breadcrumb" id="hmenu_breadcrumb"></div>
  <?php
	$output = '<ul id="ul_0">'."\n";
	$level = 0;
	foreach ($menus as $key => $menu) {
	  if ($key< count($menus)-1){ //the main menu
		if($menus[$key+1]['level'] > $level){
		  $output .= "\t".'<li class="'. $menu['status'].' '.$menu['state'] . ' '. $menu['liclass'] .'" >';
		  $output .= '<a class="'. $menu['class'].'"';
		  $output .= ($menu['href'] ? ' href="' . $menu['href'] . '"' : '');
		  $output .= '>';
		  $output .= $menu['image'] ? '<img class="icon" src="' . $menu['image'] . '" alt="icon" width=16 height=16 >' : '';
		  $output .= '<span>'.$menu['name'] . ($menu['products_in_category'] != 0 ? ' (' . $menu['products_in_category'].')':'') . '</span></a>' . "\n";
		  $ul = ($menus[$key+1]['status'] == 'enabled') ? '<ul style="display:'.$menus[$key+1]['type'].'" class="' . $menus[$key+1]['ulclass'] . '" >': '<ul style="display:'.$menus[$key+1]['type'].'" class="menu ' . $menus[$key+1]['ulclass'] . '" >';
		  $output .= $ul . "\n";
		  $level++;
		} else if($menus[$key+1]['level'] < $level){ //subcategory closing entry except in the last subcategory 
		  $output .= "\t".'<li class="'. $menu['status'].' '.$menu['state'] . ' '. $menu['liclass'] .'" >';
		  $output .= '<a class="'. $menu['class'].'"';
		  $output .= ($menu['href'] ? ' href="' . $menu['href'] . '"' : '');
		  $output .= '>';
		  $output .= $menu['image'] ? '<img class="icon" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" alt="icon" width=16 height=16 data-src="' . $menu['image'] . '">' : '';
		  $output .= '<span>'.$menu['name'] . ($menu['products_in_category'] != 0 ? ' (' . $menu['products_in_category'].')':'') . '</span>';
		  $output .= '</a></li>' . "\n";
		  while ($menus[$key+1]['level'] < $level){
		    $output .= '</ul></li>'. "\n";
		    $level--;
		  }
		} else{ //subcategory entries, except the closing one
		  $output .= "\t".'<li class="'. $menu['status'].' '.$menu['state'] . ' '. $menu['liclass'] .'" >';
		  $output .= '<a class="'. $menu['class'].'"';
		  $output .= ($menu['href'] ? ' href="' . $menu['href'] . '"' : '');
		  $output .= '>';
		  if ($menu['class'] == 'menu_lvl_0'){
		  $output .= $menu['image'] ? '<img class="icon" src="' . $menu['image'] . '" alt="icon" width=16 height=16 >' : '';
		  } else {
		  $output .= $menu['image'] ? '<img class="icon" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" alt="icon" width=16 height=16 data-src="' . $menu['image'] . '">' : '';
		  }
		  $output .= '<span>'.$menu['name'] . ($menu['products_in_category'] != 0 ? ' (' . $menu['products_in_category'].')':'') . '</span>';
		  $output .= '</a></li>' . "\n";
		}
	  } else { //last closing entry of the main menu or if the main menu has subcategory, then its closing entry
		$output .= "\t".'<li class="'. $menu['status'].' '.$menu['state'] . ' '. $menu['liclass'] .'" >';
		$output .= '<a class="'. $menu['class'].'"';
		$output .= ($menu['href'] ? ' href="' . $menu['href'] . '"' : '');
		$output .= '>';
		$output .= $menu['image'] ? '<img class="icon" src="' . $menu['image'] . '" alt="icon" width=16 height=16 >' : '';
		$output .= '<span>'.$menu['name'] . ($menu['products_in_category'] != 0 ? ' (' . $menu['products_in_category'].')':'') . '</span>';
		$output .= '</a></li>' . "\n";
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
	<script type="text/javascript">
	$(document).ready(function(){
		max_width = $('#myMenuID').outerWidth();
		var awidth=0;
		var liwidth = [];
		$("li.menu_level_0").each(function() {
			awidth+= $(this).outerWidth();
			if (awidth + 5 > max_width) {
				$(this).hide();
			}
		});
		$("#ul_0 > li").each(function(i){
			liwidth[i]=$(this).width();
		});
		$("#ul_0 li").hover(function(){
			var submenuwidth = $(this).children('ul').outerWidth();
			if (submenuwidth != null){
				var diff = $(this).position().left + submenuwidth - max_width;
				var margin_left = liwidth[$(this).index()] - submenuwidth;
				if (diff > 0) {
					$(this).children('ul').css('margin-left', margin_left+'px');
				}
			}
		});
		$("#ul_0 li:visible:last").css("border-right","0px");
	});
    </script>

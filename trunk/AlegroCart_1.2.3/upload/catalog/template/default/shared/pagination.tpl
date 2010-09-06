<?php
 echo '<div class="results"><div class="pagination">'."\n"; // 1 & 2
 echo '<div class="left">'.$text_results.'</div>'."\n";
 if ($total_pages > 10){echo '<br>';}
  echo '<b>'.$entry_page.'</b>'."\n";
  if ($page < 1) {$page = 1;}
   if ($page > 10) {
	echo '<a class="prev_next" href="'.$pages[0]['href'].'">';
	echo $first_page.'</a>'."\n";
	$first_link = (floor($page/10)*10)+1;
   } else {
	$first_link = 1;
   }
   $last_link = $first_link + 9;
   if ($page > 1) {
	echo '<a class="prev_next" href="'.$pages[$page-2]['href'].'">';
	echo " &laquo;".$previous.'</a>'.'&nbsp;'."\n";
   }
   foreach ($pages as $p) {
	if ($p['value']>=$first_link && $p['value']<=$last_link){
	 if ($p['value'] == $page) {
	  echo '<a class="num_active" id="current" href="'.$p['href'].'">';
	  echo $p['value'].'</a>'."\n";
	 } else {
	  echo '<a class="num" href="'.$p['href'].'">';
	  echo $p['value'].'</a>'."\n";
	 }
	}
   }
   if ($page < $total_pages) {
	echo '&nbsp;';
	echo '<a class="prev_next" href="'.$pages[$page]['href'].'">';
	echo $next." &raquo;".'</a>'."\n";
	if ($total_pages > 2){
		echo '<a class="prev_next" href="'.$pages[$total_pages-1]['href'].'">';
		echo $last_page.'</a>'."\n";
	}
   }
  echo '</div></div>'; // close 1 & 2
?>
<?php 
  echo '<h4>';
  echo $text_support;
  echo $text_powered_by;
  if(isset($text_developer)){
	echo '<a href="' . $developer_link . '">' . $text_developer . '</a>';
  }
  echo '</h4>';
?>
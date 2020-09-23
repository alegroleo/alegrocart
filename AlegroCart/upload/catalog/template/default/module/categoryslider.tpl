<?php 
	$head_def->setcss($this->style . "/css/slick.css");
	$head_def->setcss($this->style . "/css/slick-theme.css");
	$head_def->set_javascript("slider/slick.min.js");
?>
<div class="categoryslider_module">
	<?php foreach ($categories as $category) { ?>
		<div>
			<a href="<?php echo $category['href']; ?>">
			<img src="<?php echo $category['thumb']; ?>" width="<?php echo $category['width']; ?>" height="<?php echo $category['height']; ?>" alt="<?php echo $category['name']; ?>">
			</a><br>
			<a class="cn" href="<?php echo $category['href']; ?>"><b><?php echo $category['name'] ?></b></a>
		</div>
	<?php } ?>
</div>
  <script type="text/javascript">
	$(document).ready(function(){
	  $('.categoryslider_module').slick({
		dots: true,
		autoplay: true,
		autoplaySpeed: 2000,
		infinite: true,
		<?php if ($location == 'content'){
		  if($column_data == 3){
			echo ('slidesToScroll: 3, slidesToShow: 3');
		  } else if ($column_data == 1.2 || $column_data == 2.1){
			echo ('slidesToScroll: 4, slidesToShow: 4');
		  } else {
			echo ('slidesToScroll: 5, slidesToShow: 5');
		  }
		} else {
			echo ('slidesToScroll: 5, slidesToShow: 5');
		} ?>
	  });
	});
  </script>

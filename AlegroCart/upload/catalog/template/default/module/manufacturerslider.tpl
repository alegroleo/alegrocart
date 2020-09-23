<?php 
	$head_def->setcss($this->style . "/css/slick.css");
	$head_def->setcss($this->style . "/css/slick-theme.css");
	$head_def->set_javascript("slider/slick.min.js");
?>
<div class="manufacturerslider_module">
	<?php foreach ($manufacturers as $manufacturer) { ?>
		<div>
			<a href="<?php echo $manufacturer['href']; ?>">
			<img src="<?php echo $manufacturer['thumb']; ?>" width="<?php echo $manufacturer['width']; ?>" height="<?php echo $manufacturer['height']; ?>" alt="<?php echo $manufacturer['name']; ?>">
			</a>
		</div>
	<?php } ?>
</div>
  <script type="text/javascript">
	$(document).ready(function(){
	  $('.manufacturerslider_module').slick({
		slidesToScroll: 1,
		dots: true,
		autoplay: true,
		autoplaySpeed: 2000,
		infinite: true,
		<?php if ($location == 'content'){
		  if($column_data == 3){
			echo ('slidesToShow: 3');
		  } else if ($column_data == 1.2 || $column_data == 2.1){
			echo ('slidesToShow: 4');
		  } else {
			echo ('slidesToShow: 5');
		  }
		} else {
			echo ('slidesToShow: 5');
		} ?>
	  });
	});
  </script>

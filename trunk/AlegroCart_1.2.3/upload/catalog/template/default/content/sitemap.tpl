<?php 
  $head_def->setcss($this->style . "/css/sitemap.css");
  $head_def->set_MetaDescription("Site Map of all categories and subcategories");
  $head_def->set_MetaKeywords("site map, categories, information, layout");	  
?>

<div class="headingbody"><?php echo $heading_title; ?></div>
<div class="contentBody">
<div id="sitemap">
  <div class="b">
    <ul>
      <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a>
        <ul>
<?php if(isset($edit)){echo '<li><a href="'.$edit.'">'.$text_edit.'</a></li>';}?>
<?php if(isset($password)){echo '<li><a href="'.$password.'">'.$text_password.'</a></li>';}?>
<?php if(isset($address)){echo '<li><a href="'.$address.'">'.$text_address.'</a></li>';}?>
<?php if(isset($history)){echo '<li><a href="'.$history.'">'.$text_history.'</a></li>';}?>
<?php if(isset($download)){echo '<li><a href="'.$download.'">'.$text_download.'</a></li>';}?>
        </ul>
      </li>
<?php if(isset($cart)){echo '<li><a href="'.$cart.'">'.$text_cart.'</a></li>';}?>
<?php if(isset($checkout)){echo '<li><a href="'.$checkout.'">'.$text_checkout.'</a></li>';}?>
<?php if(isset($search)){echo '<li><a href="'.$search.'">'.$text_search.'</a></li>';}?>
      <li><?php echo $text_information; ?>
        <ul>
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
        </ul>
      </li>
    </ul>
  </div>
  <div class="a">
    <ul>
      <?php	   
	  foreach ($categories as $category) {	
		if ($category['level'] == '0') {
		  $bullet = 'disc';
		  $margin = 0;
		} elseif ($category['level'] == '1') {
		  $bullet = 'circle';
		  $margin = 40;
		} elseif ($category['level'] > '1') {
		  $bullet = 'square';
		  $margin = ($category['level'] * 4) * 10;
		}
		$style="list-style-type: $bullet; margin-left: {$margin}px;";
		?>
		<li style="<?php echo $style?>"><a href="<?php echo $category['href']?>"><?php echo $category['name']?></a></li>
	<?php } ?>
    </ul>
  </div>
</div></div>
<div class="contentBodyBottom"></div>
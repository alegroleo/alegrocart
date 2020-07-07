<?php if ($error_install_dir) { ?>
<div class="warning"><?php echo $error_install_dir; ?></div>
<?php } ?>
<?php if ($error_config) { ?>
<div class="warning"><?php echo $error_config; ?></div>
<?php } ?>
<?php if ($error_htaccess) { ?>
<div class="warning"><?php echo $error_htaccess; ?></div>
<?php } ?>
<?php if ($error_robots) { ?>
<div class="warning"><?php echo $error_robots; ?></div>
<?php } ?>
<?php if ($error_page_load) { ?>
<div class="warning"><?php echo $error_page_load; ?></div>
<?php } ?>
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>
<div id="home">
  <div class="a">
    <table>
      <tr>
        <td onclick="location='<?php echo $online; ?>'"><img src="template/<?php echo $this->directory?>/image/online.png" alt="<?php echo $text_online; ?>" class="png"><br>
          <?php echo $text_online; ?><br>
          <?php echo $users; ?></td>
        <td onclick="location='<?php echo $customer; ?>'"><img src="template/<?php echo $this->directory?>/image/customer.png" alt="<?php echo $text_customer; ?>" title="<?php echo $explanation_customer; ?>"class="png"><br>
          <?php echo $text_customer; ?><br>
          <?php echo $new_customers; ?> / <?php echo $customers; ?></td>
        <td onclick="location='<?php echo $order; ?>'"><img src="template/<?php echo $this->directory?>/image/order.png" alt="<?php echo $text_order; ?>" title="<?php echo $explanation_order; ?>"class="png"><br>
          <?php echo $text_order; ?><br>
          <?php echo $new_orders; ?> / <?php echo $orders; ?></td>
        <td onclick="location='<?php echo $product; ?>'"><img src="template/<?php echo $this->directory?>/image/product.png" alt="<?php echo $text_product; ?>" title="<?php echo $explanation_product; ?>"class="png"><br>
          <?php echo $text_product; ?><br>
          <?php echo $active_products; ?> / <?php echo $products; ?></td>
        <td onclick="location='<?php echo $review; ?>'"><img src="template/<?php echo $this->directory?>/image/review.png" alt="<?php echo $text_review; ?>" title="<?php echo $explanation_review; ?>"class="png"><br>
          <?php echo $text_review; ?><br>
          <?php echo $active_reviews; ?> / <?php echo $reviews; ?></td>
        <td onclick="location='<?php echo $language; ?>'"><img src="template/<?php echo $this->directory?>/image/language.png" alt="<?php echo $text_language; ?>" title="<?php echo $explanation_language; ?>"class="png"><br>
          <?php echo $text_language; ?><br>
          <?php echo $active_languages; ?> / <?php echo $languages; ?></td>
        <td onclick="location='<?php echo $currency; ?>'"><img src="template/<?php echo $this->directory?>/image/currency.png" alt="<?php echo $text_currency; ?>" title="<?php echo $explanation_currency; ?>"class="png"><br>
          <?php echo $text_currency; ?><br>
          <?php echo $active_currencies; ?> / <?php echo $currencies; ?></td>
        <td onclick="location='<?php echo $country; ?>'"><img src="template/<?php echo $this->directory?>/image/country.png" alt="<?php echo $text_country; ?>" title="<?php echo $explanation_country; ?>"class="png"><br>
          <?php echo $text_country; ?><br>
          <?php echo $active_countries; ?> / <?php echo $countries; ?></td>
        <td onclick="location='<?php echo $image; ?>'"><img src="template/<?php echo $this->directory?>/image/image.png" alt="<?php echo $text_online; ?>" class="png"><br>
          <?php echo $text_image; ?><br>
          <?php echo $images; ?></td>
      </tr>
    </table>
  </div>
  <div class="b">
    <fieldset>
    <legend><?php echo $text_latest_orders; ?></legend>
    <table class="list">
      <tr>
        <th class="left"><?php echo $column_order_id; ?></th>
        <th class="left"><?php echo $column_customer; ?></th>
        <th class="left"><?php echo $column_status; ?></th>
        <th class="left"><?php echo $column_date_added; ?></th>
        <th class="right"><?php echo $column_total; ?></th>
      </tr>
      <?php $i = 1; ?>
      <?php foreach ($latest_orders as $order) { ?>
      <?php  
    if ($i != 1) {
    	$i = 1;
    } else {
    	$i = 0;
    }
    
    if ($i == 0) {
    	$class = 'row1';
    } elseif ($i == 1) {
     	$class = 'row2';
    }
    ?>
      <tr class="<?php echo $class; ?>" onmouseover="this.className='highlight'" onmouseout="this.className='<?php echo $class; ?>'" onclick="location='<?php echo $order['href']; ?>'">
        <td class="left"><?php echo $order['order_id']; ?></td>
        <td class="left"><?php echo $order['customer']; ?></td>
        <td class="left"><?php echo $order['status']; ?></td>
        <td class="left"><?php echo $order['date_added']; ?></td>
        <td class="right"><?php echo $order['total']; ?></td>
      </tr>
      <?php } ?>
    </table>
    </fieldset>
    <fieldset>
    <legend><?php echo $text_latest_reviews; ?></legend>
    <table class="list">
      <tr>
        <th class="left"><?php echo $column_product; ?></th>
        <th class="left"><?php echo $column_author; ?></th>
        <th class="center"><?php echo $column_avgrating; ?></th>
        <th class="center"><?php echo $column_status; ?></th>
      </tr>
      <?php $j = 1; ?>
      <?php foreach ($latest_reviews as $review) { ?>
      <?php  
    if ($j != 1) {
    	$j = 1;
    } else {
    	$j = 0;
    }
    
    if ($j == 0) {
    	$class = 'row1';
    } elseif ($j == 1) {
     	$class = 'row2';
    }
    ?>
      <tr class="<?php echo $class; ?>" onmouseover="this.className='highlight'" onmouseout="this.className='<?php echo $class; ?>'" onclick="location='<?php echo $review['href']; ?>'">
        <td class="left"><?php echo $review['product']; ?></td>
        <td class="left"><?php echo $review['author']; ?></td>
        <td class="center"><?php echo $review['avgrating']; ?></td>
        <td class="center"><img src="template/<?php echo $this->directory?>/image/<?php echo (($review['status']) ? 'enabled' : 'disabled'); ?>.png" alt="" class="png"></td>
      </tr>
      <?php } ?>
    </table>
    </fieldset>
  </div>
  <script type="text/javascript"><!--
  $(document).ready(function() {
	<?php if (!$help) { ?>
		$('.description').hide(0);
	<?php } ?>
 });
  function ShowDesc(){
	$.ajax({
		type:    'POST',
		url:     'index.php?controller=home&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
</div>

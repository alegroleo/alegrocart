<?php 
  $head_def->setcss($this->style . "/css/checkout_shipping.css");
  $head_def->set_javascript("ajax/jquery.js");
?>
<div class="headingbody"><h1><?php echo $heading_title; ?></h1></div>
<div class="contentBody">
<?php if (isset($message)) { ?> 
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<?php if ($error) { ?> 
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
<div id="shipping">
  <?php if ($hasnoshipping) { ?> 
    <div class="a"><?php echo $text_nonshippable; ?></div>
    <div class="j">
    <table class="k">
      <tr>
        <th class="center"><?php echo $text_image; ?></th>
	<th class="left"><?php echo $text_name; ?></th>
        <th class="center"><?php echo $text_quantity; ?></th>
       	<th class="center"><?php echo $text_ship; ?></th>
      </tr>
      <?php foreach ($products as $product) { ?>
      <tr>
	  <td class="l"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"></a></td>
	  <td class="left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php foreach ($product['option'] as $option) { ?>
          <br>
          &nbsp;<small> - <?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
          <?php } ?>
          </td>
          <td class="center"><?php echo $product['quantity']; ?></td>
	  <td class="center">
	  <?php if ($product['download']) {?>
	  <img src="catalog/styles/<?php echo $this->style?>/image/downloadable.png" alt="<?php echo $text_downloadable; ?>" title="<?php echo $text_downloadable; ?>" >
	  <?php } else {?>
	  <img src="catalog/styles/<?php echo $this->style?>/image/non_shippable.png" alt="<?php echo $text_non_shippable; ?>" title="<?php echo $text_non_shippable; ?>">
	  <?php }?>
	  </td>
      </tr>
      <?php } ?>
    </table>
    </div>
    <div class="a"><?php echo $text_choose; ?></div><br>
<?php } ?>
    <div class="a"><?php echo $text_shipping; ?></div>
    <div class="b">
      <table>
        <tr>
          <td width="50%" valign="top"><?php echo $text_shipping_to; ?></td>
          <td valign="top" rowspan="2"><b><?php echo $text_shipping_address; ?></b><br>
            <?php echo $address; ?></td>
        </tr>
        <tr>
          <td align="center" valign="top"><input type="button" value="<?php echo $button_change_address; ?>" id="change_address"></td>
        </tr>
      </table>
    </div>
    <?php if ($methods) { ?>
    <div class="c"><?php echo $text_shipping_method; ?></div>
    <div class="d"><?php echo $text_shipping_methods; ?>
        <?php foreach ($methods as $method) { ?>
		<?php if($method['quote'][key($method['quote'])]['id'] == $default){?>
		<table class="default_method">
		<?php } else {?>
		<table class="method">
		<?php }?>
        <tr>
          <td class="g" colspan="2"><b><?php echo $method['title']; ?></b></td>
        </tr>
      <?php if (!$method['error']) { ?>
        <?php foreach ($method['quote'] as $quote) { ?>
		  <?php if(isset($quote['error']) && @$quote['error']){?>
		    <tr>
              <td colspan="2" class="g"><div class="warning"><?php echo $quote['error']; ?></div></td>
            </tr>
		  <?php } else {?>
			<tr>
			  <td class="g"><label for="<?php echo $quote['id']; ?>">
              <?php if ($quote['id'] == $default) { ?>
                <input type="radio" name="shipping" value="<?php echo $quote['id']; ?>" id="<?php echo $quote['id']; ?>" CHECKED>
              <?php } else { ?>
                <input type="radio" name="shipping" value="<?php echo $quote['id']; ?>" id="<?php echo $quote['id']; ?>">
              <?php } ?>
              <?php echo $quote['title']; ?></label></td>
              <td class="i"><label for="<?php echo $quote['id']; ?>"><?php echo $quote['text']; ?></label></td>
		      <input type="hidden" name="<?php echo $quote['id']; ?>_quote" value="<?php echo $quote['text']; ?>">
          </tr>
		   <?php if(isset($quote['shipping_form']) ){ echo $quote['shipping_form'];}?>
		  <?php }?>
        <?php } ?>
        <?php } else { ?>
		  <tr>
            <td colspan="2" class="g"><div class="warning"><?php echo $method['error']; ?></div></td>
          </tr>
        <?php } ?>
		</table>
        <?php } ?>
    </div>
    <?php } ?>
    <div class="e"><?php echo $text_comments; ?></div>
    <div class="f">
      <textarea  id="comment" name="comment" cols="89" rows="8"><?php echo $comment; ?></textarea>
    </div>
	<input type="hidden" name="account_validation" value="<?php echo $account_validation;?>">
  </div>
  <div class="buttons">
    <table>
      <tr>
        <td align="left"><input type="button" value="<?php echo $button_back; ?>" id="back"></td>
        <td align="right"><input type="submit" value="<?php echo $button_continue; ?>"></td>
      </tr>
    </table>
  </div>
</form>
</div>
  <script type="text/javascript"><!--
$("#back, #change_address").on("click", function(){
	var Comment = $('#comment').val();
	var shippingMethod = $('input[name=shipping]:checked').val();
	var Button = this.id;
	var data_json = {'Comment':Comment, 'shippingMethod':shippingMethod, 'Button':Button};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=checkout_shipping&action=comment',
		data: data_json,
		dataType:'json',
		success: function (data) {
				if (data.status=='BACK_SUCCESS') {
					window.location='<?php echo $back; ?>'; 
				}
				else if (data.status=='S_ADDRESS_SUCCESS') {
					window.location='<?php echo $change_address; ?>'.replace("&amp;", "&"); 
				}
			}
	});
});
//--></script>

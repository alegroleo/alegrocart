<?php 
  $head_def->setcss($this->style . "/css/checkout_payment.css");
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
  <div id="payment">
  
    <div class="a"><?php echo $text_payment; ?></div>
    <div class="b">
      <table>
        <tr>
          <td width="50%" valign="top"><?php echo $text_payment_to; ?></td>
          <td valign="top" rowspan="2"><b><?php echo $text_payment_address; ?></b><br>
            <?php echo $address; ?></td>
        </tr>
        <tr>
          <td align="center" valign="top"><input type="button" value="<?php echo $button_change_address; ?>" id="change_address"></td>
        </tr>
      </table>
    </div>
    <?php if ($methods) { ?>
    <div class="c"><?php echo $text_payment_method; ?></div>
    <div class="d"><?php echo $text_payment_methods; ?>
      <?php foreach ($methods as $method) { ?>
	  <table class="method">
	  <tbody>
            <tr>
              <td class="i" colspan="2"><label for="<?php echo $method['id']; ?>">
                <?php if ($method['id'] == $default) { ?>
                  <input type="radio" name="payment" value="<?php echo $method['id']; ?>" id="<?php echo $method['id']; ?>" checked="checked">
                <?php } else { ?>
			      <input type="radio" name="payment" value="<?php echo $method['id']; ?>" id="<?php echo $method['id']; ?>">
                <?php } ?>
              <b><?php echo $method['title'];?></b></label></td>
            </tr>
		    <?php if($method['message']) {?>
		      <tr>
		        <td class="i" colspan="2">
		          <?php echo $method['message'];?>
		        </td>
		      </tr>
		    <?php } ?>
	  </tbody>
	  </table>
      <?php } ?>
    </div>
    <?php } ?>
    <div class="g"><?php echo $text_comments; ?></div>
    <div class="h">
      <textarea id="comment" name="comment" cols="89" rows="8"><?php echo $comment; ?></textarea>
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
<div class="contentBodyBottom"></div>
  <script type="text/javascript"><!--
$("#back, #change_address").on("click", function(){
	var Comment = $('#comment').val();
	var paymentMethod = $('input[name=payment]:checked').val();
	var Button = this.id;
	var data_json = {'Comment':Comment, 'paymentMethod':paymentMethod, 'Button':Button};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=checkout_payment&action=comment',
		data: data_json,
		dataType:'json',
		success: function (data) {
				if (data.status=='BACK_SUCCESS') {
					window.location='<?php echo $back; ?>'; 
				}
				else if (data.status=='P_ADDRESS_SUCCESS') {
					window.location='<?php echo $change_address; ?>'.replace("&amp;", "&"); 
				}
			}
	});
});
//--></script>
  <script type="text/javascript">
	$('input[name="payment"][checked="checked"]').closest('table').attr('class', 'default_method');
//--></script>
  <script type="text/javascript">
	$('input[name="payment"]').on("click", function(){
		$('input[name="payment"]').closest('table').removeClass('default_method').addClass('method');
		$(this).closest('table').attr('class', 'default_method');
});
//--></script>

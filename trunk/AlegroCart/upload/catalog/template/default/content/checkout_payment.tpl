<?php 
  $head_def->setcss($this->style . "/css/checkout_payment.css");
?>
<div class="headingbody"><?php echo $heading_title; ?></div>
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
          <td align="center" valign="top"><input type="button" value="<?php echo $button_change_address; ?>" onclick="location='<?php echo $change_address; ?>'"></td>
        </tr>
      </table>
    </div>
    <?php if ($methods) { ?>
    <div class="c"><?php echo $text_payment_method; ?></div>
    <div class="d"><?php echo $text_payment_methods; ?>
      
      <?php foreach ($methods as $method) { ?>
	    <?php if ($method['id'] == $default) { ?>
		  <table style="border: 3px solid #0099FF; border-radius: 10px ;">
		<?php } else {?>
		  <table style="border: 3px solid #DDDDDD; border-radius: 10px ;">
		<?php }?>
            <tr>
              <td class="i" colspan="2"><label for="<?php echo $method['id']; ?>">
                <?php if ($method['id'] == $default) { ?>
                  <input type="radio" name="payment" value="<?php echo $method['id']; ?>" id="<?php echo $method['id']; ?>" CHECKED>
                <?php } else { ?>
			      <input type="radio" name="payment" value="<?php echo $method['id']; ?>" id="<?php echo $method['id']; ?>">
                <?php } ?>
              <?php echo $method['title'];?></label></td>
            </tr>
		    <?php if($method['message']){?>
		      <tr>
		        <td class="i" colspan="2">
		          <?php echo $method['message'];?>
		        </td>
		      </tr>
		    <?php } ?>
		  </table>
      <?php } ?>
    </div>
    <?php } ?>
    <div class="g"><?php echo $text_comments; ?></div>
    <div class="h">
      <textarea name="comment" cols="89" rows="8"><?php echo $comment; ?></textarea>
    </div>
	<input type="hidden" name="account_validation" value="<?php echo $account_validation;?>">
  </div>
  <div class="buttons">
    <table>
      <tr>
        <td align="left"><input type="button" value="<?php echo $button_back; ?>" onclick="location='<?php echo $back; ?>'"></td>
        <td align="right"><input type="submit" value="<?php echo $button_continue; ?>"></td>
      </tr>
    </table>
  </div>
</form>
</div>
<div class="contentBodyBottom"></div>
<?php 
  $head_def->setcss($this->style . "/css/contact.css");
  $head_def->set_MetaDescription("Contact Form, Send requests or comments with this form.");
  $head_def->set_MetaKeywords("contact, requests, information, comments");
?>
<div class="headingbody"><?php echo $heading_title; ?></div>
<div class="contentBody">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div id="contact">
    <div class="a"> 
      <div class="b">
        <table>
          <tr>
            <td align="right" valign="top"><b><?php echo $text_address; ?></b></td>
            <td><?php echo $store; ?><br>
              <?php echo formatedstring($address,20); ?></td>
          </tr>
        </table>
      </div>
      <div class="c">
        <table>
          <tr>
            <td align="right"><b><?php echo $text_telephone; ?></b></td>
            <td><?php echo $telephone; ?></td>
          </tr>
          <?php if ($fax) { ?>
          <tr>
            <td align="right"><b><?php echo $text_fax; ?></b></td>
            <td><?php echo $fax; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
    </div>
    <div class="d">
      <table>
        <tr>
          <td><?php echo $entry_name; ?></td>
        </tr>
        <tr>
          <td><input type="text" name="name" value="<?php echo $name; ?>">
            <?php if ($error_name) { ?>
            <span class="error"><?php echo $error_name; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_email; ?></td>
        </tr>
        <tr>
          <td><input type="text" name="email" value="<?php echo $email; ?>">
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_enquiry; ?></td>
        </tr>
        <tr>
          <td><textarea name="enquiry" cols="40" rows="15"><?php echo $enquiry; ?></textarea>
            <?php if ($error_enquiry) { ?>
            <span class="error"><?php echo $error_enquiry; ?></span>
            <?php } ?></td>
        </tr>
      </table>
 </div>
 <?php if (@$captcha) { ?>
	<div style="margin: 10px;">
		  <img src="<?php echo $captcha;?>" title="<?php echo $text_captcha;?>" alt="<?php echo $text_captcha;?>"> 
	  <table>
		<tr>
		  <td>
		    <?php echo $exp_captcha;?>
		  </td>
		</tr>
		<tr>
		  <td>
		    <input size="20" type="text" name="captcha_value" value="">
		    <?php if($error_captcha) {?>
		    <span class="error"><?php echo $error_captcha; ?></span>
		    <?php }?>
		  </td>
		</tr>
	  </table>
        </div>
 <?php } ?>
  </div>
 <div class="buttons">
    <table>
      <tr>
        <td align="right"><input type="submit" value="<?php echo $button_continue; ?>"></td>
      </tr>
    </table>
  </div>
</form>
</div>
<div class="contentBodyBottom"></div>
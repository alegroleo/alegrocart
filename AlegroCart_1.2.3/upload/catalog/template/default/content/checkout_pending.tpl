<?php 
  $head_def->setcss($this->style . "/css/checkout_pending.css");
?>
<div class="headingbody"><?php echo $heading_title; ?></div>
<div class="contentBody">
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>

<div id="pending">
  <?php echo $text_pending; ?>
  <form name="payform" id="payform" action="<?php echo $payment_url; ?>" method="post" enctype="<?php echo (isset($payment_form_enctype) && $payment_form_enctype)?$payment_form_enctype:'multipart/form-data'?>">
    <?php if ($fields) { ?>
    <div class="a"><?php echo $fields; ?></div>
    <?php } ?>
    <div class="buttons">
      <table>
        <tr>
          <td align="left" width="5"><input type="submit" value="<?php echo $button_click_to_complete; ?>"></td>
        </tr>
      </table>
    </div>
  </form>
  <img style="display:none;" width="100%" id="loadingbar" src="catalog/styles/<?php echo $this->style;?>/image/loading_bar.gif" ><br>
</div></div>
<div class="contentBodyBottom"></div>

<script type="text/javascript"><!--
document.getElementById('pending').appendChild(document.createTextNode("<?php echo $text_redirect; ?>"));
document.getElementById('loadingbar').style.display = 'block';
--></script> 
<script type="text/javascript"><!--
window.onload=document.forms['payform'].submit();
--></script> 
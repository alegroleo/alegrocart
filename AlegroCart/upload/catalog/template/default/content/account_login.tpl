<?php 
 $head_def->setcss($this->style . "/css/account_login.css");
?>

<div class="headingbody"><?php echo $heading_title; ?></div>
<div class="contentBody">
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div id="login">
  <table><tr><td>	
  <div class="a">
    <div class="b"><?php echo $text_new_customer; ?></div>
    <div class="c" style="height: <?php echo (isset($text_guest_account) ? '300px' : '140px') ?>">
      <div class="d"><?php echo $text_i_am_new_customer; ?></div>
	  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
		<div class="e">
	    <input type="radio" name="account_type" value='new_account' id="ca" CHECKED><label for="ca"><b><?php echo $text_create_account;?></b></label><br>
			<div class="x">
				<?php echo $text_create_account_exp; ?>
			</div>
		  <?php if(isset($text_guest_account)){?>
		    <input type="radio" name="account_type" value='guest_account' id="ga">
		    <label for="ga"><b><?php echo $text_guest_account;?></b></label><br>
				<div class="x">
					<?php echo $text_guest_account_exp;?>
				</div>
		  <?php }?>
	  </div>
	    
        <div class="f">
          <input type="submit" value="<?php echo $button_continue; ?>">
        </div>
		<input type="hidden" name="account_validation" value="<?php echo $account_validation;?>">
	  </form>
    </div>
  </div>
  <div class="g">
    <div class="h"><?php echo $text_returning_customer; ?></div>
    <div class="i" style="height: <?php echo (isset($text_guest_account) ? '300px' : '140px') ?>">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="j"><?php echo $text_i_am_returning_customer; ?></div>
        <div class="k">
          <table> 
            <tr>
              <td><b><?php echo $entry_email; ?></b></td>
              <td><input type="text" name="email" value="<?php echo isset($email)?$email:''; ?>"></td>
            </tr>
            <tr>
              <td><b><?php echo $entry_password; ?></b></td>
              <td><input type="password" name="password"></td>
            </tr>
          </table>
        </div>
        <div class="l">
          <input type="submit" value="<?php echo $button_login; ?>">
        </div>
        <?php if ($redirect) { ?>
        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>">
        <?php } ?>
		  <input type="hidden" name="account_validation" value="<?php echo $account_validation;?>">
      </form>
      <div class="m"><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten_password; ?></a></div>
    </div>
  </div></td></tr></table>
</div>
</div>
<div class="contentBodyBottom"></div>
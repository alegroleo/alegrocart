<?php 
  $head_def->setcss($this->style . "/css/account_create.css");
  $head_def->set_javascript("ajax/jquery.js");
?>
<div class="headingbody"><?php echo $heading_title; ?></div>
<div class="contentBody">
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if (isset($message)) { ?> 
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="create-account">
  <div id="create">
    <div class="b">
	  <?php echo $text_account_already; ?>
      <?php if (isset($agree)) { ?>
	    <table>
          <tr>
            <td align="right" width="290px"><?php echo $agree; ?></td>
		    <?php if ($agreed == 1) { ?>
		      <td align="left" width="50px"><input type="checkbox" id="agreed" name="agreed" value="1" onclick="document.getElementById('submit').disabled = (this.checked == true) ? false : true; enable_input(this.checked)" CHECKED></td>
		    <?php } else { ?>
		      <td align="left" width="50px"><input type="checkbox" id="agreed" name="agreed" value="1" onclick="document.getElementById('submit').disabled = (this.checked == true) ? false : true; enable_input(this.checked)"></td>
		    <?php } ?>
			<td align="left" width="250px"><?php echo $text_required;?></td>
          </tr>
	    </table>
		<input type="hidden" id="information" value="<?php echo $information;?>">
	  <?php } ?>
    </div>
    <div class="a"><?php echo $text_your_details; ?></div>
    <div class="b">
      <table>
        <tr>
          <td style="width:150px"><span class="required">*</span> <?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname" id="firstname"<?php if(isset($agree) && (@!$agreed)) echo ' disabled="disabled"';?> value="<?php echo $firstname; ?>">
            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname" id="lastname"<?php if(isset($agree) && (@!$agreed)) echo ' disabled="disabled"';?> value="<?php echo $lastname; ?>">
            <?php if ($error_lastname) { ?>
            <span class="error"><?php echo $error_lastname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><input type="text" name="email" id="email"<?php if(isset($agree) && (@!$agreed)) echo ' disabled="disabled"';?> value="<?php echo $email; ?>">
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
          <td><input type="text" name="telephone" id="telephone"<?php if(isset($agree) && (@!$agreed)) echo ' disabled="disabled"';?> value="<?php echo $telephone; ?>">
            <?php if ($error_telephone) { ?>
            <span class="error"><?php echo $error_telephone; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_fax; ?></td>
          <td><input type="text" name="fax" id="fax"<?php if(isset($agree) && (@!$agreed)) echo ' disabled="disabled"';?> value="<?php echo $fax; ?>"></td>
        </tr>
      </table>
    </div>
    <div class="c"><?php echo $text_your_address; ?></div>
    <div class="d">
      <table>
        <tr>
          <td style="width:150px"><?php echo $entry_company; ?></td>
          <td><input type="text" name="company" id="company"<?php if(isset($agree) && (@!$agreed)) echo ' disabled="disabled"';?> value="<?php echo $company; ?>"></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
          <td><input type="text" name="address_1" id="address_1"<?php if(isset($agree) && (@!$agreed)) echo ' disabled="disabled"';?> value="<?php echo $address_1; ?>">
            <?php if ($error_address_1) { ?>
            <span class="error"><?php echo $error_address_1; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_address_2; ?></td>
          <td><input type="text" name="address_2" id="address_2"<?php if(isset($agree) && (@!$agreed)) echo ' disabled="disabled"';?> value="<?php echo $address_2; ?>"></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_postcode; ?></td>
          <td><input type="text" name="postcode" id="postcode"<?php if(isset($agree) && (@!$agreed)) echo ' disabled="disabled"';?> value="<?php echo $postcode; ?>"><?php echo $text_no_postal;?>
		  <?php if ($error_postcode) { ?>
		  <span class="error"><?php echo $error_postcode; ?></span>
		  <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_city; ?></td>
          <td><input type="text" name="city" id="city"<?php if(isset($agree) && (@!$agreed)) echo ' disabled="disabled"';?> value="<?php echo $city; ?>">
            <?php if ($error_city) { ?>
            <span class="error"><?php echo $error_city; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_country; ?></td>
          <td><select name="country_id" onchange="$('#zone').load('index.php?controller=account_create&amp;action=zone&amp;country_id='+this.value+'&amp;zone_id=<?php echo $zone_id; ?>');">
              <?php foreach ($countries as $country) { ?>
              <?php if ($country['country_id'] == $country_id) { ?>
              <option value="<?php echo $country['country_id']; ?>" SELECTED><?php echo $country['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_zone; ?></td>
          <td id="zone">
		    <select name="zone_id"><option disabled></option>
            </select></td>
        </tr>
      </table>
    </div>
	<?php if(!$guest){?>
    <div class="e"><?php echo $text_your_password; ?></div>
    <div class="f">
      <table>
        <tr>
          <td style="width:150px"><span class="required">*</span> <?php echo $entry_password; ?></td>
          <td><input type="password" name="password" id="password"<?php if(isset($agree) && (@!$agreed)) echo ' disabled="disabled"';?> value="<?php echo $password; ?>">
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_confirm; ?></td>
          <td><input type="password" name="confirm" id="confirm"<?php if(isset($agree) && (@!$agreed)) echo ' disabled="disabled"';?> value="<?php echo $confirm; ?>">
            <?php if ($error_confirm) { ?>
            <span class="error"><?php echo $error_confirm; ?></span>
            <?php } ?></td>
        </tr>
      </table>
    </div>
    <div class="e"><?php echo $text_newsletter; ?></div>
    <div class="f">
      <table>
        <tr>
          <td style="width:150px"><?php echo $entry_newsletter; ?></td>
          <td><?php if ($newsletter == 1) { ?>
            <input type="radio" name="newsletter" value="1" CHECKED>
            <?php echo $text_yes; ?>
            <input type="radio" name="newsletter" value="0">
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="newsletter" value="1">
            <?php echo $text_yes; ?>
            <input type="radio" name="newsletter" value="0" CHECKED>
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
      </table>
    </div>
	<?php }?>
  </div>
  <div class="buttons">
    <table>
      <tr>
        <td align="right"><input type="submit" value="<?php echo $button_continue; ?>" id="submit"<?php if(isset($agree) && (@!$agreed)) echo ' DISABLED';?>></td>
      </tr>
    </table>
  </div>

  <input type="hidden" name="account_validation" value="<?php echo $account_validation;?>">
</form></div>
<div class="contentBodyBottom"></div>
<script type="text/javascript"><!--
$('#zone').load('index.php?controller=account_create&action=zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');

function enable_input(agreed){
	var Agreed = agreed;
	var Infolink = $('#information'). val();
	if(Agreed == true){
		$('#firstname').attr("disabled", false);
		$('#lastname').attr("disabled", false);
		$('#email').attr("disabled", false);
		$('#telephone').attr("disabled", false);
		$('#fax').attr("disabled", false);
		$('#company').attr("disabled", false);
		$('#address_1').attr("disabled", false);
		$('#address_2').attr("disabled", false);
		$('#postcode').attr("disabled", false);
		$('#city').attr("disabled", false);
		$('#password').attr("disabled", false);
		$('#confirm').attr("disabled", false);
		$('#infolink'). removeAttr("href");
	} else {
		$('#firstname').attr("disabled", true);
		$('#lastname').attr("disabled", true);
		$('#email').attr("disabled", true);
		$('#telephone').attr("disabled", true);
		$('#fax').attr("disabled", true);
		$('#company').attr("disabled", true);
		$('#address_1').attr("disabled", true);
		$('#address_2').attr("disabled", true);
		$('#postcode').attr("disabled", true);
		$('#city').attr("disabled", true);
		$('#password').attr("disabled", true);
		$('#confirm').attr("disabled", true);
		$('#infolink'). attr("href", Infolink);
	}
}

$(document).ready(function(){
	var Agreed = $('#agreed').attr("checked");
	if(Agreed){
		$('#infolink'). removeAttr("href");
	}
});
//--></script>
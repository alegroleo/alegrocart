<?php 
  $head_def->setcss($this->style . "/css/checkout_address.css");
  $head_def->set_javascript("ajax/jquery.js");
?>

<div class="headingbody"><?php echo $heading_title; ?></div>
<div class="contentBody">
<?php if (isset($message)) { ?> 
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<div id="checkout_address">
  <?php if ($addresses) { ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <div class="a"><?php echo $text_entries; ?></div>
    <div class="b">
      <table>
        <?php foreach ($addresses as $address) { ?>
        <?php if ($address['address_id'] == $default) { ?>
        <tr>
          <td class="e"><label for="address_id[<?php echo $address['address_id']; ?>]">
            <input type="radio" name="address_id" value="<?php echo $address['address_id']; ?>" id="address_id[<?php echo $address['address_id']; ?>]" CHECKED>
            <?php echo $address['address']; ?></label></td>
        </tr>
        <?php } else { ?>
        <tr>
          <td class="e"><label for="address_id[<?php echo $address['address_id']; ?>]">
            <input type="radio" name="address_id" value="<?php echo $address['address_id']; ?>" id="address_id[<?php echo $address['address_id']; ?>]">
            <?php echo $address['address']; ?></label></td>
        </tr>
        <?php } ?>
        <?php } ?>
      </table>
    </div>
    <div class="buttons">
      <table>
        <tr>
          <td align="right"><input type="submit" value="<?php echo $button_continue; ?>"></td>
        </tr>
      </table>
    </div>
  </form>
  <?php } ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <div class="c"><?php echo $text_new_address; ?></div>
    <div class="d">
      <table>
        <tr>
          <td width="150"><span class="required">*</span> <?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname" value="<?php echo $firstname; ?>">
            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname" value="<?php echo $lastname; ?>">
            <?php if ($error_lastname) { ?>
            <span class="error"><?php echo $error_lastname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_company; ?></td>
          <td><input type="text" name="company" value="<?php echo $company; ?>"></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
          <td><input type="text" name="address_1" value="<?php echo $address_1; ?>">
            <?php if ($error_address_1) { ?>
            <span class="error"><?php echo $error_address_1; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_address_2; ?></td>
          <td><input type="text" name="address_2" value="<?php echo $address_2; ?>"></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_postcode; ?></td>
          <td><input type="text" name="postcode" value="<?php echo $postcode; ?>"><?php echo $text_no_postal;?>
		  <?php if ($error_postcode) { ?>
		  <span class="error"><?php echo $error_postcode; ?></span>
		  <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_city; ?></td>
          <td><input type="text" name="city" value="<?php echo $city; ?>">
            <?php if ($error_city) { ?>
            <span class="error"><?php echo $error_city; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_country; ?></td>
          <td><select name="country_id" onchange="$('#zone').load('index.php?controller=checkout_address&action=zone&country_id='+this.value+'&zone_id=<?php echo $zone_id; ?>');">
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
          <td id="zone"><select name="zone_id">
            </select></td>
        </tr>
      </table>
	  <input type="hidden" name="account_validation" value="<?php echo $account_validation;?>">
    </div>
    <div class="buttons">
      <table>
        <tr>
          <td align="right"><input type="submit" value="<?php echo $button_continue; ?>"></td>
        </tr>
      </table>
    </div>
  </form>
</div></div>
<div class="contentBodyBottom"></div>
<script type="text/javascript"><!--
$('#zone').load('index.php?controller=checkout_address&action=zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
//--></script>

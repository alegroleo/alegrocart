<?php 
  $head_def->setcss($this->style . "/css/account_address.css");
  $head_def->set_javascript("ajax/jquery.js");  
?>
<div class="headingbody"><?php echo $heading_title; ?></div>
<div class="contentBody">
<div id="address">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <div class="e"><?php echo $text_new_address; ?></div>
    <div class="f">
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
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_address_2; ?></td>
          <td><input type="text" name="address_2" value="<?php echo $address_2; ?>"></td>
        </tr>
        <tr>
          <td><span class="required">*</span><?php echo $entry_postcode; ?></td>
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
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_country; ?></td>
          <td><select name="country_id" onchange="$('#zone').load('index.php?controller=account_address&action=zone&country_id='+this.value+'&zone_id=<?php echo $zone_id; ?>');">
              <?php foreach ($countries as $country) { ?>
              <?php if ($country['country_id'] == $country_id) { ?>
              <option value="<?php echo $country['country_id']; ?>" SELECTED><?php echo $country['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_zone; ?></td>
          <td id="zone"><select name="zone_id">
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_default; ?></td>
          <td><?php if ($default) { ?>
            <input type="radio" name="default" value="1" CHECKED>
            <?php echo $text_yes; ?>
            <input type="radio" name="default" value="0">
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="default" value="1">
            <?php echo $text_yes; ?>
            <input type="radio" name="default" value="0" CHECKED>
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
      </table>
	<input type="hidden" name="account_validation" value="<?php echo $account_validation;?>">
    </div>
    <div class="buttons">
      <table>
        <tr>
          <td align="left"><input type="button" value="<?php echo $button_back; ?>" onclick="location='<?php echo $back; ?>'"></td>
          <td align="right"><input type="submit" value="<?php echo $button_continue; ?>" class="right"></td>
        </tr>
      </table>
    </div>
  </form>
</div></div>
<div class="contentBodyBottom"></div>
<script type="text/javascript"><!--
$('#zone').load('index.php?controller=account_address&action=zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
//--></script>
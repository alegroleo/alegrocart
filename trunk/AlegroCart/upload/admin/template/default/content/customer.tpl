<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/<?php echo $this->directory?>/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $insert; ?>'"><img src="template/<?php echo $this->directory?>/image/insert_enabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
  <?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css">
<script type="text/javascript" src="javascript/ajax/jquery.js"></script>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_customer; ?></div></a><a><div class="tab_text"><?php echo $tab_address; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><span class="required">*</span> <?php echo $entry_firstname; ?></td>
              <td><input type="text" name="firstname" value="<?php echo $firstname; ?>">
                <?php if ($error_firstname) { ?>
                <span class="error"><?php echo $error_firstname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_lastname; ?></td>
              <td><input type="text" name="lastname" value="<?php echo $lastname; ?>">
                <?php if ($error_lastname) { ?>
                <span class="error"><?php echo $error_lastname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_email; ?></td>
              <td><input type="text" name="email" value="<?php echo $email; ?>">
                <?php if ($error_email) { ?>
                <span class="error"><?php echo $error_email; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_telephone; ?></td>
              <td><input type="text" name="telephone" value="<?php echo $telephone; ?>">
                <?php if ($error_telephone) { ?>
                <span class="error"><?php echo $error_telephone; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_fax; ?></td>
              <td><input type="text" name="fax" value="<?php echo $fax; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_password; ?></td>
              <td>
			    <?php if (@$update) { ?>
				  <input type="password" readonly="readonly" name="password" value="<?php echo $password; ?>" >
				<?php } else { ?>
			      <input type="password" name="password" value="<?php echo $password; ?>" >
                  <?php if ($error_password) { ?>
                    <span class="error"><?php echo $error_password; ?></span>
                  <?php  } ?>
				<?php  } ?>
			  </td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_confirm; ?></td>
              <td>
			    <?php if (@$update) { ?>
				  <input type="password" readonly="readonly" name="confirm" value="<?php echo $confirm; ?>">
				<?php } else { ?>
				  <input type="password" name="confirm" value="<?php echo $confirm; ?>">
                  <?php if ($error_confirm) { ?>
                    <span class="error"><?php echo $error_confirm; ?></span>
                  <?php  } ?>
				<?php  } ?>
			  </td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_newsletter; ?></td>
              <td><select name="newsletter">
                  <?php if ($newsletter) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_status; ?></td>
              <td><select name="status">
                  <?php if ($status) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
      </div>
	  <div class="page">
        <div class="pad">
          <table>
		    <tr>
            <td class="set"><?php echo $entry_company; ?></td>
            <td><input type="text" name="company" value="<?php echo $company; ?>"></td>
            </tr>
			<tr>
			  <td class="set"><span class="required">*</span> <?php echo $entry_address_1; ?></td>
              <td><input type="text" name="address_1" value="<?php echo $address_1; ?>">
              <?php if ($error_address_1) { ?>
                <span class="error"><?php echo $error_address_1; ?></span>
              <?php } ?>
              </td>
			</tr>
			<tr>
			  <td class="set""><?php echo $entry_address_2; ?></td>
			  <td><input type="text" name="address_2" value="<?php echo $address_2; ?>"></td>
			</tr>
			<tr>
			  <td class="set""><span class="required">*</span><?php echo $entry_postcode; ?></td>
			  <td><input type="text" name="postcode" value="<?php echo $postcode; ?>">
			  <?php if ($error_postcode) { ?>
				<span class="error"><?php echo $error_postcode; ?></span>
			  <?php } ?></td>
			  <td class="expl"><?php echo $text_no_postal;?></td>
			</tr>
			<tr>
			  <td class="set""><span class="required">*</span> <?php echo $entry_city; ?></td>
			  <td><input type="text" name="city" value="<?php echo $city; ?>">
              <?php if ($error_city) { ?>
				<span class="error"><?php echo $error_city; ?></span>
              <?php } ?>
			  </td>
			</tr>
			<tr>
			  <td class="set""><?php echo $entry_country; ?></td>
			  <td><select name="country_id" onchange="$('#zone').load('index.php?controller=customer&action=zone&country_id='+this.value+'&zone_id=<?php echo $zone_id; ?>');">
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
			  <td class="set""><?php echo $entry_zone; ?></td>
			  <td id="zone"><select name="zone_id">
			  </select></td>
			</tr>
		  </table>
		</div>
	  </div>
    </div>
  </div>
  <input type="hidden" name="address_id" value="<?php echo $address_id;?>">
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  
</form>
<script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
<script type="text/javascript"><!--
$('#zone').load('index.php?controller=customer&action=zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
//--></script>
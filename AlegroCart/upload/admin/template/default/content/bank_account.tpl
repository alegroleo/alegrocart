<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled store" onmouseover="className='hover store'" onmouseout="className='enabled store'" onclick="getValues();document.getElementById('update_form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="currency" value="">
  <input type="hidden" name="bank_name" value="">
  <input type="hidden" name="bank_address" value="">
  <input type="hidden" name="ban" value="">
  <input type="hidden" name="iban" value="">
  <input type="hidden" name="swift" value="">
  <input type="hidden" name="charge" value="">
  <input type="hidden" name="owner" value="">
  <input type="hidden" name="bank_account_id" value="<?php echo $bank_account_id; ?>">
  </form>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
  <?php if (@$delete) { ?>
  <div class="enabled delete" onmouseover="className='hover delete'" onmouseout="className='enabled delete'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled store" onmouseover="className='hover store'" onmouseout="className='enabled store'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" alt="<?php echo $button_print; ?>" class="png"><?php echo $button_print; ?></div>
  <div class="enabled cancel" onmouseover="className='hover cancel'" onmouseout="className='enabled cancel'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
<?php if (@$last) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $last; ?>'"><img src="template/<?php echo $this->directory?>/image/last_enabled.png" alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/last_disabled.png" alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } ?>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?>
 <em></em>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td class="set"><?php echo $entry_currency; ?></td>
              <td><select name="currency" id="currency">
                  <option value="0"><?php echo $text_all_currencies; ?></option>
                  <?php foreach ($enabled_currencies as $enabled_currency) { ?>
                  <?php if ($enabled_currency['code'] == $currency) { ?>
                  <option value="<?php echo $enabled_currency['code']; ?>" selected><?php echo $enabled_currency['title']; ?> (<?php echo $enabled_currency['code']; ?>)</option>
                  <?php } else { ?>
                  <option value="<?php echo $enabled_currency['code']; ?>"><?php echo $enabled_currency['title']; ?> (<?php echo $enabled_currency['code']; ?>)</option>
                  <?php } ?>
                  <?php } ?>
                </select>
		</td>
		<td class="expl"><?php echo $explanation_currency;?></td>
            </tr>
            <tr>
              <td class="set" width="185"><span class="required">*</span> <?php echo $entry_bank_name; ?></td>
              <td><input class="validate_alpha_num" id="bank_name" name="bank_name" type="text" size="30" value="<?php echo $bank_name; ?>">
                <?php if ($error_bank_name) { ?>
                <span class="error"><?php echo $error_bank_name; ?></span>
                <?php } ?></td>
              <td class="expl"><?php echo $explanation_bank_name; ?></td>
            </tr>
            <tr>
              <td class="set" valign="top"><?php echo $entry_bank_address; ?></td>
              <td><textarea rows="5" cols="30" name="bank_address"><?php echo $bank_address; ?></textarea>
              <td class="expl" valign="top"><?php echo $explanation_bank_address; ?></td>
            </tr>
	    <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_owner; ?></td>
              <td><input class="validate_alpha_num" id="owner" type="text" size="30" name="owner" value="<?php echo $owner; ?>">
                <?php if ($error_owner) { ?>
                <span class="error"><?php echo $error_owner; ?></span>
                <?php } ?></td>
              <td class="expl"><?php echo $explanation_owner; ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_ban; ?></td>
              <td><input class="validate_alpha_num" id="ban" type="text" size="30" name="ban" value="<?php echo $ban; ?>">
                <?php if ($error_ban) { ?>
                <span class="error"><?php echo $error_ban; ?></span>
                <?php } ?></td>
              <td class="expl"><?php echo $explanation_ban; ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_iban; ?></td>
              <td><input class="validate_alpha_num" id="iban" type="text" size="30" name="iban" value="<?php echo $iban; ?>"></td>
              <td class="expl"><?php echo $explanation_iban; ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_swift; ?></td>
              <td><input class="validate_alpha_num" id="swift" type="text" size="20" name="swift" value="<?php echo $swift; ?>"></td>
              <td class="expl"><?php echo $explanation_swift; ?></td>
            </tr>
	    <tr>
	      <td class="set"><?php echo $entry_charges; ?></td>
	      <td><select name="charge" id="charge">
                  <?php foreach ($charges as $value) { ?>
                  <?php if ($value == $charge) { ?>
                  <option value="<?php echo $value; ?>" selected><?php echo $value; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
	      <td class="expl"><?php echo $explanation_charges;?></td>
	    </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
</form>
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
  <script type="text/javascript"><!--
    $('#currency').change(function () {
      var value = $("#currency option:selected").text();
      $(".heading em").text(value);
    }).change();
  //--></script>
  <script type="text/javascript"><!--
	function getValues() {
		document.forms['update_form'].currency.value=document.forms['form'].currency.value;
		document.forms['update_form'].bank_name.value=document.forms['form'].bank_name.value;
		document.forms['update_form'].bank_address.value=document.forms['form'].bank_address.value;
		document.forms['update_form'].owner.value=document.forms['form'].owner.value;
		document.forms['update_form'].ban.value=document.forms['form'].ban.value;
		document.forms['update_form'].iban.value=document.forms['form'].iban.value;
		document.forms['update_form'].swift.value=document.forms['form'].swift.value;
		document.forms['update_form'].charge.value=document.forms['form'].charge.value;
	}
  //--></script>
  <script type="text/javascript"><!--
  $(document).ready(function() {
	$('.task').each(function(){
	    $('.task .disabled').hide(0);
	});
	<?php if (!$help) { ?>
		$('.description').hide(0);
	<?php } ?>
 });
  function ShowDesc(){
	$.ajax({
		type:    'POST',
		url:     'index.php?controller=bank_account&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
  <script type="text/javascript"><!--
    $(document).ready(function() {
	  RegisterValidation();
    });
  //--></script>

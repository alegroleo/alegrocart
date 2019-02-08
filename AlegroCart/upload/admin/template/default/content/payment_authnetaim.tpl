<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
?>
<div class="task">
  <div class="disabled"><img src="template/default/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="getValues();document.getElementById('update_form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="global_authnetaim_status" value="">
  <input type="hidden" name="global_authnetaim_test" value="">
  <input type="hidden" name="global_authnetaim_test_login" value="">
  <input type="hidden" name="global_authnetaim_test_txnkey" value="">
  <input type="hidden" name="global_authnetaim_prod_login" value="">
  <input type="hidden" name="global_authnetaim_prod_txnkey" value="">
  <input type="hidden" name="global_authnetaim_authtype" value="">
  <input type="hidden" name="global_authnetaim_sendemail" value="">
  <input type="hidden" name="global_authnetaim_geo_zone_id" value="">
  <input type="hidden" name="global_authnetaim_sort_order" value="">
</form>
  <div class="disabled"><img src="template/default/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/default/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/default/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_payment; ?><em><?php echo $heading_title; ?></em>
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
              <td width="185" class="set"><?php echo $entry_authnetaim_status; ?></td>
              <td><select name="global_authnetaim_status">
                  <?php if ($global_authnetaim_status) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
                <td class="expl"><?php echo $extra_authnetaim_status; ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_authnetaim_test; ?></td>
              <td><?php if ($global_authnetaim_test) { ?>
                <input type="radio" name="global_authnetaim_test" value="1" checked id="yes">
                <label for="yes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_authnetaim_test" value="0" id="no">
                <label for="no"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_authnetaim_test" value="1" id="yes">
                <label for="yes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_authnetaim_test" value="0" checked id="no">
                <label for="no"><?php echo $text_no; ?></label>
                <?php } ?></td>
                <td class="expl"><?php echo $extra_authnetaim_test; ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_authnetaim_test_login; ?></td>
              <td><input type="text" name="global_authnetaim_test_login" value="<?php echo $global_authnetaim_test_login; ?>">
              <td class="expl"><?php echo $extra_authnetaim_test_login; ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_authnetaim_test_txnkey; ?></td>
              <td><input type="text" name="global_authnetaim_test_txnkey" value="<?php echo $global_authnetaim_test_txnkey; ?>">
              <td class="expl"><?php echo $extra_authnetaim_test_txnkey; ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_authnetaim_prod_login; ?></td>
              <td><input type="text" name="global_authnetaim_prod_login" value="<?php echo $global_authnetaim_prod_login; ?>">
              <td class="expl"><?php echo $extra_authnetaim_prod_login; ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_authnetaim_prod_txnkey; ?></td>
              <td><input type="text" name="global_authnetaim_prod_txnkey" value="<?php echo $global_authnetaim_prod_txnkey; ?>">
              <td class="expl"><?php echo $extra_authnetaim_prod_txnkey; ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_authnetaim_authtype; ?></td>
              <td>
                <input type="radio" name="global_authnetaim_authtype" value="auth_only" id="auth_only" <?php echo ($global_authnetaim_authtype == 'auth_only' ? 'checked' : '') ?>>
                <label for="auth_only"><?php echo $text_authonly; ?><br /></label>
                <input type="radio" name="global_authnetaim_authtype" value="auth_capture" id="auth_capture" <?php echo ($global_authnetaim_authtype == 'auth_capture' ? 'checked' : '') ?>>
                <label for="auth_capture"><?php echo $text_authcapture; ?></label>
              </td>
              <td class="expl"><?php echo $extra_authnetaim_authtype; ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_authnetaim_sendemail; ?></td>
              <td>
                <input type="radio" name="global_authnetaim_sendemail" value="TRUE" id="yess" <?php echo ($global_authnetaim_sendemail == 'TRUE' ? 'checked' : '') ?>>
                <label for="yess"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_authnetaim_sendemail" value="FALSE" id="noo" <?php echo ($global_authnetaim_sendemail == 'FALSE' ? 'checked' : '') ?>>
                <label for="noo"><?php echo $text_no; ?></label>
              </td>
              <td class="expl"><?php echo $extra_authnetaim_sendemail; ?></td>
            </tr>
            <tr>
              <td width="185" class="set"><?php echo $entry_authnetaim_geo_zone_id; ?></td>
              <td><select name="global_authnetaim_geo_zone_id">
                  <option value="0"><?php echo $text_all_zones; ?></option>
                  <?php foreach ($geo_zones as $geo_zone) { ?>
                  <?php if ($geo_zone['geo_zone_id'] == $global_authnetaim_geo_zone_id) { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected><?php echo $geo_zone['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
                <td class="expl"><?php echo $extra_authnetaim_geo_zone_id; ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_authnetaim_sort_order; ?></td>
              <td><input class="validate_int" id="authnetaim_sort_order" type="text" name="global_authnetaim_sort_order" value="<?php echo $global_authnetaim_sort_order; ?>" size="1"></td>
              <td class="expl"><?php echo $extra_authnetaim_sort_order; ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
  <script type="text/javascript"><!--
    $(document).ready(function() {
	  RegisterValidation();
    });
  //--></script>
  <script type="text/javascript"><!--
	function getValues() {
		document.forms['update_form'].global_authnetaim_status.value=document.forms['form'].global_authnetaim_status.value;
		document.forms['update_form'].global_authnetaim_test.value=document.forms['form'].global_authnetaim_test.value;
		document.forms['update_form'].global_authnetaim_test_login.value=document.forms['form'].global_authnetaim_test_login.value;
		document.forms['update_form'].global_authnetaim_test_txnkey.value=document.forms['form'].global_authnetaim_test_txnkey.value;
		document.forms['update_form'].global_authnetaim_prod_login.value=document.forms['form'].global_authnetaim_prod_login.value;
		document.forms['update_form'].global_authnetaim_prod_txnkey.value=document.forms['form'].global_authnetaim_prod_txnkey.value;
		document.forms['update_form'].global_authnetaim_authtype.value=document.forms['form'].global_authnetaim_authtype.value;
		document.forms['update_form'].global_authnetaim_sendemail.value=document.forms['form'].global_authnetaim_sendemail.value;
		document.forms['update_form'].global_authnetaim_geo_zone_id.value=document.forms['form'].global_authnetaim_geo_zone_id.value;
		document.forms['update_form'].global_authnetaim_sort_order.value=document.forms['form'].global_authnetaim_sort_order.value;
	}
  //--></script>
  <script type="text/javascript"><!--
  $(document).ready(function() {
$('.task').each(function(){
    $('.task .disabled').hide();
});
	<?php if (!$help) { ?>
		$('.description').hide(0);
	<?php } ?>
   });
  function ShowDesc(){
	$.ajax({
		type:    'POST',
		url:     'index.php?controller=payment_authnetaim&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
</form>

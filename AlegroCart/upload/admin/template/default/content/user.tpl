<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
   <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="getValues();document.getElementById('update_form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
   <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
   <input type="hidden" name="update_form" value="1">
   <input type="hidden" name="username" value="">
   <input type="hidden" name="firstname" value="">
   <input type="hidden" name="lastname" value="">
   <input type="hidden" name="position" value="">
   <input type="hidden" name="email" value="">
   <input type="hidden" name="mobile" value="">
   <input type="hidden" name="telephone" value="">
   <input type="hidden" name="fax" value="">
   <input type="hidden" name="signature" value="">
   <input type="hidden" name="monogram" value="">
   <input type="hidden" name="user_group_id" value="">
   <input type="hidden" name="password" value="">
   <input type="hidden" name="confirm" value="">
   <input type="hidden" name="old" value="">
  <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
  </form>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
  <?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
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
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="name">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><span class="required">*</span> <?php echo $entry_username; ?></td>
              <td><input class="validate_alpha_num" id="username" type="text" name="username" value="<?php echo $username; ?>">
                <?php if ($error_username) { ?>
                <span class="error"><?php echo $error_username; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_user_group; ?></td>
              <td><select name="user_group_id">
                  <?php foreach ($user_groups as $user_group) { ?>
                  <?php if ($user_group['user_group_id'] == $user_group_id) { ?>
                  <option value="<?php echo $user_group['user_group_id']; ?>" selected><?php echo $user_group['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $user_group['user_group_id']; ?>"><?php echo $user_group['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_password; ?></td>
              <td><input type="password" name="password" value="<?php echo $password; ?>" readonly onfocus="this.removeAttribute('readonly');">
                <?php if ($error_password) { ?>
                <span class="error"><?php echo $error_password; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_confirm; ?></td>
              <td><input type="password" name="confirm" value="<?php echo $confirm; ?>">
                <?php if ($error_confirm) { ?>
                <span class="error"><?php echo $error_confirm; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_old; ?></td>
              <td><input type="password" name="old" value="<?php echo $old; ?>">
                <?php if ($error_old) { ?>
                <span class="error"><?php echo $error_old; ?></span>
                <?php  } ?></td>
            </tr>
	    <tr>
	      <td colspan="2"><hr></td>
	    </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_firstname; ?></td>
              <td><input class="validate_alpha" id="firstname" type="text" name="firstname" value="<?php echo $firstname; ?>">
                <?php if ($error_firstname) { ?>
                <span class="error"><?php echo $error_firstname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_lastname; ?></td>
              <td><input class="validate_alpha" id="lastname" type="text" name="lastname" value="<?php echo $lastname; ?>">
                <?php if ($error_lastname) { ?>
                <span class="error"><?php echo $error_lastname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_monogram; ?></td>
              <td><input class="validate_alpha" size="4" id="monogram" type="text" name="monogram" value="<?php echo $monogram; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_position; ?></td>
              <td><input class="validate_alpha" id="position" type="text" name="position" value="<?php echo $position; ?>">
	    </td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_email; ?></td>
              <td><input class="validate_mail" id="email" type="text" name="email" value="<?php echo $email; ?>">
                <?php if ($error_email) { ?>
                <span class="error"><?php echo $error_email; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_telephone; ?></td>
              <td><input class="validate_phone" id="telephone" type="text" name="telephone" value="<?php echo $telephone; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_mobile; ?></td>
              <td><input class="validate_phone" id="mobile" type="text" name="mobile" value="<?php echo $mobile; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_fax; ?></td>
              <td><input class="validate_phone" id="fax" type="text" name="fax" value="<?php echo $fax; ?>"></td>
            </tr>
	    <tr>
		<td class="set" rowspan="2"><?php echo $entry_signature;?></td>
	      <td rowspan="2"><select id="signature_id" name="signature" onchange="$('#signature_image').load('index.php?controller=user&action=viewSignature&signature='+this.value);">
		    <option value="0"><?php echo $text_none; ?></option>
		    <?php foreach ($user_signatures as $user_signature){?>
			  <option value="<?php echo $user_signature['signature'];?>"<?php if($user_signature['signature'] == $signature){echo ' selected';}?>><?php echo $user_signature['signature'];?></option>
		    <?php }?>
		  </select></td>
		<td class="signature_image" id="signature_image"></td>
		</tr>
		    <tr>
		      <td class="expl">
				    <?php echo $explanation_signature . $signatures_location; ?>
		      </td>
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
    $('input[name="username"]').change(function () {
      var value = $(this).val();
      $(".heading em").text(value);
    }).change();
  //--></script>
  <script type="text/javascript"><!--
	function getValues() {
		document.forms['update_form'].username.value=document.forms['form'].username.value;
		document.forms['update_form'].firstname.value=document.forms['form'].firstname.value;
		document.forms['update_form'].lastname.value=document.forms['form'].lastname.value;
		document.forms['update_form'].position.value=document.forms['form'].position.value;
		document.forms['update_form'].email.value=document.forms['form'].email.value;
		document.forms['update_form'].monogram.value=document.forms['form'].monogram.value;
		document.forms['update_form'].telephone.value=document.forms['form'].telephone.value;
		document.forms['update_form'].mobile.value=document.forms['form'].mobile.value;
		document.forms['update_form'].fax.value=document.forms['form'].fax.value;
		document.forms['update_form'].signature.value=document.forms['form'].signature.value;
		document.forms['update_form'].user_group_id.value=document.forms['form'].user_group_id.value;
		document.forms['update_form'].password.value=document.forms['form'].password.value;
		document.forms['update_form'].confirm.value=document.forms['form'].confirm.value;
		document.forms['update_form'].old.value=document.forms['form'].old.value;
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
		url:     'index.php?controller=user&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
  <script type="text/javascript"><!--
    $('#signature_image').load('index.php?controller=user&action=viewSignature&signature='+document.getElementById('signature_id').value);
  //--></script>
  <script type="text/javascript"><!--
    $(document).ready(function() {
	  RegisterValidation();
    });
  //--></script>
</form>

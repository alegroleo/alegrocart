<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/default/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
  <div class="disabled"><img src="template/default/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <div class="disabled"><img src="template/default/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <div class="disabled"><img src="template/default/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/default/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/default/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><?php echo $tab_general; ?></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185"><?php echo $entry_authnetaim_status; ?></td>
              <td><select name="global_authnetaim_status">
                  <?php if ($global_authnetaim_status) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
                <td><?php echo $extra_authnetaim_status; ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_authnetaim_test; ?></td>
              <td><?php if ($global_authnetaim_test) { ?>
                <input type="radio" name="global_authnetaim_test" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="global_authnetaim_test" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="global_authnetaim_test" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="global_authnetaim_test" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
                <td><?php echo $extra_authnetaim_test; ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_authnetaim_test_login; ?></td>
              <td><input type="text" name="global_authnetaim_test_login" value="<?php echo $global_authnetaim_test_login; ?>">
              <td><?php echo $extra_authnetaim_test_login; ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_authnetaim_test_txnkey; ?></td>
              <td><input type="text" name="global_authnetaim_test_txnkey" value="<?php echo $global_authnetaim_test_txnkey; ?>">
              <td><?php echo $extra_authnetaim_test_txnkey; ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_authnetaim_prod_login; ?></td>
              <td><input type="text" name="global_authnetaim_prod_login" value="<?php echo $global_authnetaim_prod_login; ?>">
              <td><?php echo $extra_authnetaim_prod_login; ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_authnetaim_prod_txnkey; ?></td>
              <td><input type="text" name="global_authnetaim_prod_txnkey" value="<?php echo $global_authnetaim_prod_txnkey; ?>">
              <td><?php echo $extra_authnetaim_prod_txnkey; ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_authnetaim_authtype; ?></td>
              <td>
                <input type="radio" name="global_authnetaim_authtype" value="auth_only" <?php echo ($global_authnetaim_authtype == 'auth_only' ? 'checked' : '') ?>>
                <?php echo $text_authonly; ?><br />
                <input type="radio" name="global_authnetaim_authtype" value="auth_capture" <?php echo ($global_authnetaim_authtype == 'auth_capture' ? 'checked' : '') ?>>
                <?php echo $text_authcapture; ?>
              </td>
              <td><?php echo $extra_authnetaim_authtype; ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_authnetaim_sendemail; ?></td>
              <td>
                <input type="radio" name="global_authnetaim_sendemail" value="TRUE" <?php echo ($global_authnetaim_sendemail == 'TRUE' ? 'checked' : '') ?>>
                <?php echo $text_yes; ?>
                <input type="radio" name="global_authnetaim_sendemail" value="FALSE" <?php echo ($global_authnetaim_sendemail == 'FALSE' ? 'checked' : '') ?>>
                <?php echo $text_no; ?>
              </td>
              <td><?php echo $extra_authnetaim_sendemail; ?></td>
            </tr>
            <tr>
              <td width="185"><?php echo $entry_authnetaim_geo_zone_id; ?></td>
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
                <td><?php echo $extra_authnetaim_geo_zone_id; ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_authnetaim_sort_order; ?></td>
              <td><input type="text" name="global_authnetaim_sort_order" value="<?php echo $global_authnetaim_sort_order; ?>" size="1"></td>
              <td><?php echo $extra_authnetaim_sort_order; ?></td>
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
</form>

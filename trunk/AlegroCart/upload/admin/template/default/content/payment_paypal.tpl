<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/<?php echo $this->directory?>/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
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
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><?php echo $tab_general; ?></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><?php echo $entry_status; ?></td>
              <td><select name="global_paypal_status">
                  <?php if ($global_paypal_status) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td width="185" class="set"><?php echo $entry_geo_zone; ?></td>
              <td><select name="global_paypal_geo_zone_id">
                  <option value="0"><?php echo $text_all_zones; ?></option>
                  <?php foreach ($geo_zones as $geo_zone) { ?>
                  <?php if ($geo_zone['geo_zone_id'] == $geo_zone_id) { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected><?php echo $geo_zone['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_email; ?></td>
              <td><input type="text" name="global_paypal_email" value="<?php echo $global_paypal_email; ?>">
                <?php if ($error_email) { ?>
                <span class="error"><?php echo $error_email; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_pdt_token; ?></td>
              <td width="200"><input type="text" name="global_paypal_pdt_token" value="<?php echo $global_paypal_pdt_token; ?>">
                <?php if ($error_pdt_token) { ?>
                <span class="error"><?php echo $error_pdt_token; ?></span>
                <?php } ?></td>
                <td class="expl"><?php echo $extra_pdt_token; ?></td>
            </tr>
            <tr>
              <td width="185" class="set"><?php echo $entry_itemized; ?></td>
              <td width="200"><select name="global_paypal_itemized">
                  <option value="0" <?php echo ($global_paypal_itemized == '0' ? 'selected' : '') ?>><?php echo $text_no; ?></option>
                  <option value="1" <?php echo ($global_paypal_itemized == '1' ? 'selected' : '') ?>><?php echo $text_yes; ?></option>
                </select></td>
                <td class="expl"><?php echo $extra_itemized; ?></td>
            </tr>
            <tr>
              <td width="185" class="set"><?php echo $entry_ipn_debug; ?></td>
              <td width="200"><select name="global_paypal_ipn_debug">
                  <option value="0" <?php echo ($global_paypal_ipn_debug == '0' ? 'selected' : '') ?>><?php echo $text_no; ?></option>
                  <option value="1" <?php echo ($global_paypal_ipn_debug == '1' ? 'selected' : '') ?>><?php echo $text_yes; ?></option>
                </select></td>
                <td class="expl"><?php echo $extra_ipn_debug; ?></td>
            </tr>
            <tr>
              <td width="185" class="set"><?php echo $entry_auth_type; ?></td>
              <td width="200"><select name="global_paypal_auth_type">
                  <option value="sale" <?php echo ($global_paypal_auth_type == 'sale' ? 'selected' : '') ?>><?php echo $text_sale; ?></option>
                  <option value="authorization" <?php echo ($global_paypal_auth_type == 'authorization' ? 'selected' : '') ?>><?php echo $text_authorization; ?></option>
                  <!--<option value="order" <?php echo ($global_paypal_auth_type == 'order' ? 'selected' : '') ?>><?php echo $text_order; ?></option>-->
                </select></td>
                <td class="expl"><?php echo $extra_auth_type; ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_test; ?></td>
              <td><?php if ($global_paypal_test) { ?>
                <input type="radio" name="global_paypal_test" value="1" checked id="yes">
                <label for="yes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_paypal_test" value="0" id="no">
                <label for="no"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="global_paypal_test" value="1" id="yes">
                <label for="yes"><?php echo $text_yes; ?></label>
                <input type="radio" name="global_paypal_test" value="0" checked id="no">
                <label for="no"><?php echo $text_no; ?></label>
                <?php } ?></td>
            </tr>
            <tr>
              <td valign="top" class="set"><?php echo $entry_currency; ?></td>
              <td><select name="global_paypal_currency[]" multiple="multiple">
                  <?php foreach ($currencies as $currency) { ?>
                  <?php if ($currency['selected']) { ?>
                  <option value="<?php echo $currency['value']; ?>" selected><?php echo $currency['text']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $currency['value']; ?>"><?php echo $currency['text']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="global_paypal_sort_order" value="<?php echo $global_paypal_sort_order; ?>" size="1"></td>
            </tr>
          </table>
          <table width="100%">
          <tr>
            <td class="expl"><?php echo $text_support; ?></td>
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

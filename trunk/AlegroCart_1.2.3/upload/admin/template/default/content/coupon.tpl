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
    <div class="tabs"><a><?php echo $tab_general; ?></a><a><?php echo $tab_data; ?></a></div>
    <div class="pages">
      <div class="page">
        <div id="tabmini">
          <div class="tabs">
            <?php foreach ($coupons as $coupon) { ?>
            <a><?php echo $coupon['language']; ?></a>
            <?php } ?>
          </div>
          <div class="pages">
            <?php foreach ($coupons as $coupon) { ?>
            <div class="page">
              <div class="minipad">
                <table>
                  <tr>
                    <td width="185"><span class="required">*</span> <?php echo $entry_name; ?></td>
                    <td><input name="language[<?php echo $coupon['language_id']; ?>][name]" value="<?php echo $coupon['name']; ?>">
                      <?php if ($error_name) { ?>
                      <span class="error"><?php echo $error_name; ?></span>
                      <?php } ?></td>
                  </tr>
                  <tr>
                    <td valign="top"><span class="required">*</span> <?php echo $entry_description; ?></td>
                    <td><textarea name="language[<?php echo $coupon['language_id']; ?>][description]" cols="40" rows="5" id="description<?php echo $coupon['language_id']; ?>"><?php echo $coupon['description']; ?></textarea>
                      <?php if ($error_description) { ?>
                      <span class="error"><?php echo $error_description; ?></span>
                      <?php } ?></td>
                  </tr>
                </table>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185"><span class="required">*</span> <?php echo $entry_code; ?></td>
              <td><input type="text" name="code" value="<?php echo $code; ?>">
                <?php if ($error_code) { ?>
                <span class="error"><?php echo $error_code; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_discount; ?></td>
              <td><input type="text" name="discount" id="discount" value="<?php echo $discount; ?>" onchange="check_discount()"></td>
			  <td><span id="error_discount"></span></td>
            </tr>
            <tr>
              <td><?php echo $entry_prefix; ?></td>
              <td><select name="prefix" id="prefix" onchange="check_discount()">
                  <?php if ($prefix != '-') { ?>
                  <option value="%" selected><?php echo $text_percent; ?></option>
                  <option value="-"><?php echo $text_minus; ?></option>
                  <?php } else { ?>
                  <option value="%"><?php echo $text_percent; ?></option>
                  <option value="-" selected><?php echo $text_minus; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
			<tr>
			  <td><?php echo $entry_minimum_order; ?></td>
			  <td><input type="text" name="minimum_order" value="<?php echo $minimum_order; ?>"></td>
			</tr>
            <tr>
              <td><?php echo $entry_shipping; ?></td>
              <td><?php if ($shipping) { ?>
                <input type="radio" name="shipping" value="1" checked>
                <?php echo $text_yes; ?>
                <input type="radio" name="shipping" value="0">
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="shipping" value="1">
                <?php echo $text_yes; ?>
                <input type="radio" name="shipping" value="0" checked>
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_date_start; ?></td>
              <td><input name="date_start_day" value="<?php echo $date_start_day; ?>" size="2" maxlength="2">
                <select name="date_start_month">
                  <?php foreach ($months as $month) { ?>
                  <?php if ($month['value'] == $date_start_month) { ?>
                  <option value="<?php echo $month['value']; ?>" selected><?php echo $month['text']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <input name="date_start_year" value="<?php echo $date_start_year; ?>" size="4" maxlength="4">
                <?php if ($error_date_start) { ?>
                <span class="error"><?php echo $error_date_start; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_date_end; ?></td>
              <td><input name="date_end_day" value="<?php echo $date_end_day; ?>" size="2" maxlength="2">
                <select name="date_end_month">
                  <?php foreach ($months as $month) { ?>
                  <?php if ($month['value'] == $date_end_month) { ?>
                  <option value="<?php echo $month['value']; ?>" selected><?php echo $month['text']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <input name="date_end_year" value="<?php echo $date_end_year; ?>" size="4" maxlength="4">
                <?php if ($error_date_end) { ?>
                <span class="error"><?php echo $error_date_end; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_uses_total; ?></td>
              <td><input type="text" name="uses_total" value="<?php echo $uses_total; ?>"></td>
            </tr>
            <tr>
              <td><?php echo $entry_uses_customer; ?></td>
              <td><input type="text" name="uses_customer" value="<?php echo $uses_customer; ?>"></td>
            </tr>
            <tr>
              <td valign="top"><?php echo $entry_product; ?></td>
              <td><select name="product[]" multiple="multiple">
                  <?php foreach ($products as $product) { ?>
                  <?php if ($product['coupon_id']) { ?>
                  <option value="<?php echo $product['product_id']; ?>" selected><?php echo $product['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $product['product_id']; ?>"><?php echo $product['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
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
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
  <script type="text/javascript"><!--
  tabview_initialize('tabmini');
  //--></script>
  <script type="text/javascript"><!-- 
  function check_discount(){
	var Discount = $('#discount').val();
	var Prefix = $('#prefix').val();
	if (Prefix == "%" && Discount > 100){
	  $('#discount').val(100);
	  $('#error_discount').text('<?php echo $error_discount;?>');
	} else
	  $('#error_discount').text('');
  }
  //--></script>
</form>

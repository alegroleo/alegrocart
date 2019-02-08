<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/datepicker.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
  $head_def->set_admin_javascript("javascript/datepicker/datepicker.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/save_disabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="window.print();"><img src="template/<?php echo $this->directory?>/image/print_enabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/cancel_disabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
  
</div>
<div class="heading"><?php echo $heading_title; ?>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>
<div id="list">
  <table class="a">
    <tr>
      <td class="left"><?php echo $text_results; ?></td>
    </tr>
  </table>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <table class="a">
      <tr>
        <td class="left" id="search"><input type="submit" class="submit" value="<?php echo $button_search; ?>"></td>
        <td class="left"><?php echo $entry_group; ?>
          <select name="group">
            <?php foreach ($groups as $groups) { ?>
            <?php if ($groups['value'] == $group) { ?>
            <option value="<?php echo $groups['value']; ?>" selected><?php echo $groups['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $groups['value']; ?>"><?php echo $groups['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
        <td class="left"><?php echo $entry_status; ?>
          <select name="order_status_id">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['value'] == $order_status_id) { ?>
            <option value="<?php echo $order_status['value']; ?>" selected><?php echo $order_status['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['value']; ?>"><?php echo $order_status['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
        <td class="right"><?php echo $entry_page; ?>
          <select name="page" onchange="location=this.value">
		<?php foreach ($pages as $p) { ?>
		<option value="<?php echo $p['value']; ?>"<?php if ($p['value'] == $page) { ?>SELECTED<?php } ?>><?php echo $p['text']; ?></option>
		<?php } ?>
          </select></td>
        <td class="right"><?php echo $entry_date; ?>
          <input class="validate_int" id="date_from_day" name="date_from[day]" value="<?php echo $date_from_day; ?>" size="2" maxlength="2">
          <select name="date_from[month]" id="date_from[month]">
            <?php foreach (@$months as $month) { ?>
            <?php if ($month['value'] == $date_from_month) { ?>
            <option value="<?php echo $month['value']; ?>" selected><?php echo $month['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
          <input class="validate_int" id="date_from_year" name="date_from[year]" value="<?php echo $date_from_year; ?>" size="4" maxlength="4">
          -
          <input class="validate_int" id="date_to_day" name="date_to[day]" value="<?php echo $date_to_day; ?>" size="2" maxlength="2">
          <select name="date_to[month]" id="date_to[month]">
            <?php foreach (@$months as $month) { ?>
            <?php if ($month['value'] == $date_to_month) { ?>
            <option value="<?php echo $month['value']; ?>" selected><?php echo $month['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
          <input class="validate_int" id="date_to_year" name="date_to[year]" value="<?php echo $date_to_year; ?>" size="4" maxlength="4">
        </td>
      </tr>
    </table>
  </form>
  <table class="list">
    <tr>
      <?php foreach ($cols as $col) { ?>
      <th class="<?php echo $col['align']; ?>"><form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" onclick="this.submit();">
          <?php echo $col['name']; ?></a>
          <?php if (@$sort == $col['sort']) { ?>
          <?php if ($order == 'asc') { ?>
          &nbsp;<img src="template/<?php echo $this->directory?>/image/asc.png" class="png">
          <?php } else { ?>
          &nbsp;<img src="template/<?php echo $this->directory?>/image/desc.png" class="png">
          <?php } ?>
          <?php } ?>
          <input type="hidden" name="sort" value="<?php echo $col['sort']; ?>">
        </form></th>
      <?php } ?>
    </tr>
    <?php $j = 1; ?>
    <?php foreach ($rows as $row) { ?>
    <?php  
    if ($j != 1) {
    	$j = 1;
    } else {
    	$j = 0;
    }
    
    if ($j == 0) {
    	$class = 'row1';
    } elseif ($j == 1) {
     	$class = 'row2';
    }
    ?>
    <tr class="<?php echo $class; ?>" onmouseover="this.className='highlight'" onmouseout="this.className='<?php echo $class; ?>'">
      <?php foreach ($row['cell'] as $cell) { ?>
      <td class="<?php echo $cell['align']; ?>"><?php echo $cell['value']; ?></td>
      <?php } ?>
    </tr>
    <?php } ?>
  </table>
</div>
<script type="text/javascript"><!--
    $(document).ready(function() {
	  RegisterValidation();
    });
  //--></script>
  <script type="text/javascript"><!--
    $(document).ready(function() {
    datePickerController.createDatePicker({
    formElements:{"date_from_year":"%Y","date_from[month]":"%n", "date_from_day":"%d"},
    showWeeks:true,
    statusFormat:"%l, %d %F %Y"
    });
    });
  //--></script>
  <script type="text/javascript"><!--
    $(document).ready(function() {
    datePickerController.createDatePicker({
    formElements:{"date_to_year":"%Y","date_to[month]":"%n", "date_to_day":"%d"},
    showWeeks:true,
    statusFormat:"%l, %d %F %Y"
    });
    });
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
		url:     'index.php?controller=report_sale&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>

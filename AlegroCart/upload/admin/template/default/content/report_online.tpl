<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/save_disabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" alt="<?php echo $button_print; ?>" class="png"><?php echo $button_print; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/cancel_disabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
</div>
<div class="heading"><?php echo $heading_title; ?>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>
<div id="list">
  <table cellspacing="0" cellpadding="4" class="list"> 
    <tr>
      <th class="left"><?php echo $column_name; ?></th>
      <th class="left"><?php echo $column_time; ?></th>
      <th class="left"><?php echo $column_ip; ?></th>
      <th class="left"><?php echo $column_url; ?></th>
      <th class="right"><?php echo $column_total; ?></th>
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
      <td class="left"><?php echo $row['name']; ?></td>
      <td class="left"><?php echo $row['time']; ?></td>
      <td class="left"><?php echo $row['ip']; ?></td>
      <td class="left"><?php echo $row['url']; ?></td>
      <td class="right"><?php echo $row['total']; ?></td>
    </tr>
    <?php } ?>
  </table>
</div>
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
		url:     'index.php?controller=report_online&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
//--></script>

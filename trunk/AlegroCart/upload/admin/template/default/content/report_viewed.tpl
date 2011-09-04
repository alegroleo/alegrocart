<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/list_disabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/save_disabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="window.print();"><img src="template/<?php echo $this->directory?>/image/print_enabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/cancel_disabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
</div>
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<div id="list">
  <table class="list">
    <tr>
      <th class="left"><?php echo $column_name; ?></th>
      <th width="50%"></th>
      <th class="right"><?php echo $column_viewed; ?></th>
      <th class="right"><?php echo $column_percent; ?></th>
    </tr>
    <?php $j = 1; ?>
    <?php foreach ($products as $product) { ?>
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
      <td class="left"><?php echo $product['name']; ?></td>
      <td class="left"><div style="background: #FF0000; width: <?php echo $product['graph']; ?>; height: 50%;"></div></td>
      <td class="right"><?php echo $product['viewed']; ?></td>
      <td class="right"><?php echo $product['percent']; ?></td>
    </tr>
    <?php } ?>
  </table>
</div>

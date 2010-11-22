<div class="heading"><?php echo $heading_title; ?></div>
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

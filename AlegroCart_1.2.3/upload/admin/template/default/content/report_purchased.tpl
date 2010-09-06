<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<div id="list">
  <table class="list">
    <tr>
      <th class="left"><?php echo $column_name; ?></th>
      <th class="left"><?php echo $column_model_number; ?></th>
      <th class="right"><?php echo $column_quantity; ?></th>
      <th class="right"><?php echo $column_total; ?></th>
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
      <td class="left"><?php echo $product['model_number']; ?></td>
      <td class="right"><?php echo $product['quantity']; ?></td>
      <td class="right"><?php echo $product['total']; ?></td>
    </tr>
    <?php } ?>
  </table>
</div>

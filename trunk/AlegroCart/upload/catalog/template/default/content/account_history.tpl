<?php 
  $head_def->setcss($this->style . "/css/account_history.css");
  $head_def->setcss($this->style . "/css/paging.css");
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';  
?>
<div class="headingbody"><?php echo $heading_title; ?></div>
<div class="contentBody">
<div id="history">
  <?php foreach ($orders as $order) { ?>
  <div class="a">
    <table>
      <tr>
	<td><b><?php echo $text_order; ?></b> <?php echo $order['reference']; ?></b></td>
      	<td rowspan="2" align="right"><b><?php echo $text_status; ?></b> <?php echo $order['status']; ?></td> 
      </tr>
	<tr><td><b><?php echo $text_invoice_number; ?></b> <?php echo $order['invoice_number']; ?></td></tr>
    </table>
  </div>
  <div class="d">
    <table>
      <tr>
        <td><?php echo $text_date_added; ?> <?php echo $order['date_added']; ?></td>
        <td><?php echo $text_customer; ?> <?php echo $order['name']; ?></td>
        <td rowspan="2"><input type="button" value="<?php echo $button_view; ?>" onclick="location='<?php echo $order['href']; ?>'"></td>
		<td rowspan="2"><a href="<?php echo $order['print'];?>"><img src="catalog/styles/<?php echo $this->style;?>/image/print.png" alt="<?php echo $text_print; ?>" title="<?php echo $text_print; ?>" ></a></td>
      </tr>
      <tr>
        <td><?php echo $text_products; ?> <?php echo $order['products']; ?></td>
        <td><?php echo $text_total; ?> <?php echo $order['total']; ?></td>
      </tr>
    </table>
  </div>
  <?php } ?>
</div>
</div>
<div class="contentBodyBottom"></div>
<?php include $shared_path . 'pagination.tpl'; ?>
<div class="buttons">
  <table>
    <tr>
      <td align="right"><input type="button" value="<?php echo $button_continue; ?>" onclick="location='<?php echo $continue; ?>'"></td>
    </tr>
  </table>
</div>

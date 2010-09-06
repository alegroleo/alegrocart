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
    <div class="b"><b><?php echo $text_order; ?></b> <?php echo $order['reference']; ?></div>
    <div class="c"><b><?php echo $text_status; ?></b> <?php echo $order['status']; ?></div>
  </div>
  <div class="d">
    <table>
      <tr>
        <td><?php echo $text_date_added; ?> <?php echo $order['date_added']; ?></td>
        <td><?php echo $text_customer; ?> <?php echo $order['name']; ?></td>
        <td rowspan="2"><input type="button" value="<?php echo $button_view; ?>" onclick="location='<?php echo $order['href']; ?>'"></td>
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

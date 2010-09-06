<?php 
  $head_def->setcss($this->style . "/css/account_download.css");
  $head_def->setcss($this->style . "/css/paging.css");
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';  
?>
<div class="headingbody"><?php echo $heading_title; ?></div>
<div class="contentBody">
<div id="download">
  <?php foreach ($downloads as $download) { ?>
  <div class="a">
    <div class="b"><b><?php echo $text_order; ?></b> <?php echo $download['order_id']; ?></div>
    <div class="c"><b><?php echo $text_size; ?></b> <?php echo $download['size']; ?></div>
  </div>
  <div class="d">
    <table>
      <tr>
        <td width="40%"><?php echo $text_name; ?> <?php echo $download['name']; ?></td>
        <td width="50%"><?php echo $text_remaining; ?> <?php echo $download['remaining']; ?></td>
        <td rowspan="2"><a href="<?php echo $download['href']; ?>"><img src="catalog/styles/<?php echo $this->style?>/image/download.png" class="png"></a></td>
      </tr>
      <tr>
        <td><?php echo $text_date_added; ?> <?php echo $download['date_added']; ?></td>
        <td></td>
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

<?php 
  $head_def->setcss($this->style . "/css/information.css");
?>
<div class="headingbody"><?php echo $heading_title; ?></div>
<div class="contentBody">
<div class="box">
<?php echo $description;?>
</div></div>
<div class="contentBodyBottom"></div>
<div class="buttons">
  <table>
    <tr>
      <td align="right"><input type="button" value="<?php echo $button_continue; ?>" onclick="<?php echo $continue; ?>"></td>
    </tr>
  </table>
</div>

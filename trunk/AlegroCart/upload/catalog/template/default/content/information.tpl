<?php 
  $head_def->setcss($this->style . "/css/information.css");
?>
<div class="headingbody"><h1><?php echo $heading_title; ?></h1></div>
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

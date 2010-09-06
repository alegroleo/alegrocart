<?php 
  $head_def->setcss($this->style . "/css/account_newsletter.css");
?>
<div class="headingbody"><?php echo $heading_title; ?></div>
<div class="contentBody">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div id="newsletter">
    <div class="a"><?php echo $text_newsletter; ?></div>
    <div class="b">
      <table> 
        <tr>
          <td width="150"><?php echo $entry_newsletter; ?></td>
          <td><?php if ($newsletter == 1) { ?>
            <input type="radio" name="newsletter" value="1" CHECKED>
            <?php echo $text_yes; ?>
            <input type="radio" name="newsletter" value="0">
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="newsletter" value="1">
            <?php echo $text_yes; ?>
            <input type="radio" name="newsletter" value="0" CHECKED>
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
      </table>
    </div>
  </div></div>
  <div class="contentBodyBottom"></div>
  <div class="buttons">
    <table>
      <tr>
        <td align="left"><input type="button" value="<?php echo $button_back; ?>" onclick="location='<?php echo $back; ?>'"></td>
        <td align="right"><input type="submit" value="<?php echo $button_continue; ?>"></td>
      </tr>
    </table>
  </div>
</form>
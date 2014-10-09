<div class="headingbody"><h1><?php echo $heading_title; ?></h1></div>
<div class="contentBody">
<div id="error"><?php echo $text_error; ?></div>
</div>
<div class="contentBodyBottom"></div>
<?php if (isset($breadcrumbs)) { ?> 
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div> 
<?php } ?>
<div class="buttons">
  <table>
    <tr>
      <td align="right"><input type="button" value="<?php echo $button_continue; ?>" onclick="location='<?php echo $continue; ?>'"></td>
    </tr>
  </table>
</div>

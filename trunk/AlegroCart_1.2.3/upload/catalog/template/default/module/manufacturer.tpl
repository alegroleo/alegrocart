<?php 
  $head_def->setcss($this->style . "/css/manufacturer.css");
?>
 <div class="headingcolumn"><h1><?php echo $heading_title; ?></h1></div>
<div class="manufacturer_module">
 <select id="manufacturer_list" class="manufacturer_list" style="width: 180px;" onchange="location=this.value">
  <?php if ($manufacturer_id == '0'){ ?>
   <option value="0" selected><?php echo $text_empty; ?></option>
  <?php }?>
  <?php foreach ($manufacturers as $manufacturer) { ?>
   <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id){ ?>
    <option value="<?php echo $manufacturer['href']; ?>" selected><?php echo $manufacturer['name']; ?></option>
   <?php } else { ?>
    <option value="<?php echo $manufacturer['href']; ?>"><?php echo $manufacturer['name']; ?></option>
   <?php } ?>
  <?php } ?>
 </select>
</div>
<div class="columnBottom"></div>
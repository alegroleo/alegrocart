<?php if($location == 'header'){?>
<div id="mini_cart" class="mini_cart" style="position: absolute;">
<?php } else {?>
<div id="mini_cart" class="mini_cart">
<?php }?>
  <div class="headingcolumn" style="cursor: pointer" onclick="ShowCart()"><h1><?php echo $heading_title; ?></h1></div>

  <div  class="cart">
    <div id="cart_content" class="cart_content">
    <?php if ($products) { ?>
    <div id="cart_products">
	<table>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td><?php echo $product['quantity']; ?>&nbsp;x&nbsp;</td>
        <td style="width: 100px;"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></td>
		<td><?php echo ' '.$product['total']; ?></td>
      </tr>
      <?php } ?>
    </table>
    <div class="aa"><?php echo $text_subtotal; ?><?php echo $subtotal; ?></div>
	</div>
	<div class="cc"><?php echo $text_products.$product_total;?><div class="dd"><?php echo $text_items.$item_total;?></div></div>
	
    <div class="bb"><a href="<?php echo $view_cart; ?>"><?php echo $text_view_cart; ?></a></div>
    <?php } else { ?>
    <div class="bb"><?php echo $text_empty; ?></div>
    <?php } ?>
  </div></div>
  <div class="bottom"></div>
</div>

  <script type="text/javascript"><!--
$(document).ready(function(){
	$('#cart_products').hide(0);
});
	
function ShowCart(){	
	$('#cart_products').toggle('slow');
}
  //--></script>
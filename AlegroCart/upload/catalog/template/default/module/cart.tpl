<?php if($location == 'header'){?>
<div id="mini_cart" class="mini_cart" style="position: absolute; right: <?php echo $cart_offset;?>px;">
<?php } else {?>
<div id="mini_cart" class="mini_cart">
<?php }?>
  <div class="headingcolumn pointer" onclick="ShowCart()"><h3><?php echo $heading_title; ?></h3></div>
  <div  class="cart">
    <div id="cart_content" class="cart_content">
    <?php if ($products) { ?>
    <div id="cart_products">
	<table>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td><?php echo $product['quantity']; ?>&nbsp;x&nbsp;</td>
        <td class="ff"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></td>
	<td class="ee"><?php echo ' '.$product['total']; ?></td>
      </tr>
      <?php } ?>
    </table>
	</div>
	<div class="aa"><?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>
	<div class="cc"><?php echo $product_total.' '.$text_products .' - ';?><div class="dd"><?php echo $item_total.' '.$text_items;?></div></div>
	<div class="bb" id="cart_button"><a href="<?php echo $view_cart; ?>"><?php echo $text_view_cart; ?></a></div>
    <?php } else { ?>
    <div class="bb"><?php echo $text_empty; ?></div>
    <?php } ?>
  </div></div>
  <div class="bottom"></div>
</div>
  <script type="text/javascript"><!--
$(document).ready(function(){
	$('#cart_products, #cart_button').hide(0);
});
	
function ShowCart(){	
	$('#cart_products, #cart_button').toggle('slow');
}
  //--></script>

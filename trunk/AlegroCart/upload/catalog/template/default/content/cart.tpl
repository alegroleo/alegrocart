<?php 
  $head_def->setcss($this->style . "/css/cart.css");
  if(isset($tax_included)){
    $head_def->set_javascript("ajax/jquery.js");
    $head_def->set_javascript("ajax/tooltip.js");
  }
?>
<div class="headingbody"><?php echo $heading_title; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div class="contentBody">
  <?php if ($message) { ?>
	<div class="message"><?php if(isset($message)){ echo $message;} ?></div>
  <?php } ?>
  <?php if ($error) { ?>
  <div class="warning"><?php echo $error; ?></div>
  <?php } ?>  
  <div id="cart">
    <table class="a">
      <tr>
        <th class="b"><?php echo $column_remove; ?></th>
        <th class="c"><?php echo $column_image; ?></th>
        <th class="d"><?php echo $column_name; ?></th>
        <th class="f"><?php echo $column_quantity; ?></th>
        <th class="f"><?php echo $column_min_qty; ?></th>
        <th class="g"><?php echo $column_price; ?></th>
        <th class="e"><?php echo $column_special; ?></th>
		<?php if($columns == 2){?>
		  <th class="e"><?php echo $column_extended; ?></th>
		  <?php if($coupon_sort_order < $discount_sort_order){ ?>
			<th class="e"><?php echo $column_coupon_value; ?></th>
			<th class="e"><?php echo $column_discount_value; ?></th>
		  <?php } else { ?>
			<th class="e"><?php echo $column_discount_value; ?></th>
			<th class="e"><?php echo $column_coupon_value; ?></th>
		  <?php }?>
		<?php }?>
		<th class="s"><?php echo $text_shipping; ?></th>
        <th class="g"><?php echo $column_total; ?></th>
      </tr>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="h"><input type="checkbox" name="remove[<?php echo $product['key']; ?>]"></td>
        <td class="i"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"></a></td>
        <td class="j"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php if (!$product['stock'] && $stock_check) { ?>
          <span><?php echo $text_stock_ind ?></span>
          <?php } ?>
          <?php if ($product['min_qty_error'] == '1') { ?>
          <span><?php echo $text_min_qty_ind ?></span>
          <?php } ?>
          <div>
            <?php foreach ($product['option'] as $option) { ?>
            - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br>
            <?php } ?>
          </div></td>
        <td class="l">
          <input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="3">
          <input type="hidden" name="min_qty[<?php echo $product['key']; ?>]" value="<?php echo $product['min_qty']; ?>">
        </td>
        <td class="l"><?php echo $product['min_qty']; ?></td>
        <td class="m"><?php if (!$product['discount']) { ?>
          <?php echo ($tax_included ? '<span class="tax">*</span>' : '') . $product['price']; ?>
          <?php } else { ?>
          <span class="cartprice_old"><?php echo ($tax_included ? '<span class="tax">*</span>' : '') . $product['price']; ?></span><br>
          <span class="cartprice_new"><?php echo ($tax_included ? '<span class="tax">*</span>' : '') . $product['discount']; ?></span>
          <?php } ?></td>
        <td class="k"><?php if ($product['special_price'] > "$0.00"){echo ($tax_included ? '<span class="tax">*</span>' : '') . $product['special_price'];} ?></td>
		<?php if($columns == 2){?>
		  <td class="m"><?php echo ($tax_included ? '<span class="tax">*</span>' : '') . $product['total']; ?></td>
		  <?php if($coupon_sort_order < $discount_sort_order){ ?>
			<td class="m"><?php echo ($tax_included && $product['coupon'] ? '<span class="tax">*</span>' : '') . $product['coupon']; ?></td>
			<td class="m"><?php echo ($tax_included && $product['general_discount'] ? '<span class="tax">*</span>' : '') . $product['general_discount']; ?></td>
		  <?php } else { ?>
			<td class="m"><?php echo ($tax_included && $product['general_discount'] ? '<span class="tax">*</span>' : '') . $product['general_discount']; ?></td>
			<td class="m"><?php echo ($tax_included && $product['coupon'] ? '<span class="tax">*</span>' : '') . $product['coupon']; ?></td>
		  <?php }?>
		<?php } ?>
		<td class="l">
		<?php if ($product['download']) {?><img src="catalog/styles/<?php echo $this->style?>/image/downloadable.png" alt="<?php echo $text_downloadable; ?>" title="<?php echo $text_downloadable; ?>" >
		<?php }else if ($product['shipping']) { ?><img src="catalog/styles/<?php echo $this->style?>/image/shippable.png" alt="<?php echo $text_shippable; ?>" title="<?php echo $text_shippable; ?>" ><?php } else { ?><img src="catalog/styles/<?php echo $this->style?>/image/non_shippable.png" alt="<?php echo $text_non_shippable; ?>" title="<?php echo $text_non_shippable; ?>"><?php  } ?>
		</td>
        <td class="m"><?php echo ($tax_included ? '<span class="tax">*</span>' : '') . $product['total_discounted']; ?></td>
      </tr>
      <?php } ?>
	  <?php if($columns == 2){?>
	    <tr><td colspan="12"><hr></td></tr>
	  <?php } else {?>
	    <tr><td colspan="9"><hr></td></tr>
	  <?php }?>
	  <?php if($columns == 2){?>
	  <tr>
	    <th class="t" colspan="7"><?php echo $text_product_totals;?></th>
	    <td class="m"><?php echo ($tax_included ? '<span class="tax">* </span>' : '') . $extended_total;?></td>
		
		<?php if($coupon_sort_order < $discount_sort_order){ ?>
	      <td class="m"><?php echo ($tax_included && $coupon_total ? '<span class="tax">* </span>' : '') . $coupon_total;?></td>
		  <td class="m"><?php echo ($tax_included && $discount_total ? '<span class="tax">* </span>' : '') . $discount_total;?></td>
		<?php } else {?>
		  <td class="m"><?php echo ($tax_included && $discount_total ? '<span class="tax">* </span>' : '') . $discount_total;?></td>
		  <td class="m"><?php echo ($tax_included && $coupon_total ? '<span class="tax">* </span>' : '') . $coupon_total;?></td>
		<?php }?>
		  <td></td>
		  <td class="m"><?php echo ($tax_included && $coupon_total ? '<span class="tax">* </span>' : '') . $subtotal;?></td>
	  </tr>
	  <?php }?>
	  <?php if($columns == 2){?>
	    <tr><td colspan="7"></td><td colspan="5"><hr></td></tr>
	  <?php }?>
    </table>
    <div class="n">
      <table>
        <tr>
	  <td></td> 
          <td><?php echo ($tax_included ? '<span class="tax">*</span>' : '') . $text_subtotal; ?></td>
          <td><?php echo $subtotal; ?></td>
        </tr>
	<?php if($tax_included){?>
	  <tr>
	  <td></td>  
	  <td><?php echo $text_net_total;?></td>
	  <td><?php echo $net_total; ?></td>
	  <?php } ?>
         <?php if ($minov_status) { ?>
	    <tr>
	      <td></td>
	      <td><?php echo $text_min_order_value; ?></td>
	      <td><?php echo $minov_value; ?></td>
	    </tr>
	 <?php } ?>
	<tr>
	  <td></td>
          <td><?php echo $text_cart_weight; ?></td>
          <td><?php echo $weight; ?></td>
        </tr>
      </table>
    </div>
	<table width="100%"><tr>
           <td><span class="tax"><?php echo isset($text_shortfall) ? $text_shortfall : ''; ?></span></td>
        </tr></table>
	<?php if ($discount_status) {?>
	  <table width="100%">
	    <tr>
		  <?php if(isset($text_discount_lprice)){?>
		    <td><?php echo $text_discount_lprice;?></td>
		  <?php }?>
	    </tr>
	    <tr>
		  <?php if(isset($text_discount_gprice)){?>
		    <td><?php echo $text_discount_gprice;?></td>
		  <?php }?>
		</tr>
	  </table> 
	<?php }?>
	
	  <table width="100%">
        <tr>
		<?php if($tax_included){?>
		  <script type="text/javascript">
			$(document).ready(function(){
	          $('.taxE[title]').tooltip({
              offset: [160,-70], tipClass: 'tooltip_white'});
	        });
          </script>
		  <?php echo '<td class="left"><div title="' . $text_tax_explantion  . '" class="taxE" ><span class="tax">* </span>' . $text_tax . '</div></td>';?>
		  <?php } else {?>
		  <td></td>
		  <?php }?>
          <td class="right"><?php echo $entry_coupon; ?></td>
          <td class="right" width="1"><input type="text" name="coupon" value="<?php echo $coupon; ?>"></td>
        </tr>
      </table>
	
  </div>
  <div class="buttons">
    <table>
      <tr>
        <td align="left"><input type="submit" value="<?php echo $button_update; ?>"></td>
        <td align="center"><input type="button" value="<?php echo $button_shopping; ?>" onclick="<?php echo $continue; ?>"></td>
        <td align="right"><input type="button" value="<?php echo $button_checkout; ?>" onclick="location='<?php echo $checkout; ?>'"></td>
      </tr>
    </table>
  </div>
  </div>
  <div class="contentBodyBottom"></div>
  <input type="hidden" name="task" value="update">
</form>

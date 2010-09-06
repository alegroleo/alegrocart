<?php 
  $head_def->setcss($this->style . "/css/cart.css");
?>
<div class="headingbody"><?php echo $heading_title; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <?php if ($error) { ?>
  <div class="warning"><?php echo $error; ?></div>
  <?php } ?>
    <div class="contentBody">
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
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="cartprice_old"><?php echo $product['price']; ?></span><br>
          <span class="cartprice_new"><?php echo $product['discount']; ?></span>
          <?php } ?></td>
        <td class="k"><?php if ($product['special_price'] > "$0.00"){echo $product['special_price'];} ?></td>
        <td class="m"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>
    </table>
    <div class="n">
      <table>
        <tr>
          <td><?php echo $text_subtotal; ?></td>
          <td><?php echo $subtotal; ?></td>
        </tr>
        <tr>
          <td><?php echo $text_cart_weight; ?></td>
          <td><?php echo $weight; ?></td>
        </tr>
      </table>
    </div>
  </div></div>
  <div class="contentBodyBottom"></div>
  <div class="buttons">
    <table>
      <tr>
        <td align="left"><input type="submit" value="<?php echo $button_update; ?>"></td>
        <td align="center"><input type="button" value="<?php echo $button_shopping; ?>" onclick="<?php echo $continue; ?>"></td>
        <td align="right"><input type="button" value="<?php echo $button_checkout; ?>" onclick="location='<?php echo $checkout; ?>'"></td>
      </tr>
    </table>
  </div>
  <input type="hidden" name="task" value="update">
</form>

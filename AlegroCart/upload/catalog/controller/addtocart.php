<?php   // Add to Cart AlegroCart
class ControllerAddToCart extends Controller {
	function add() {
		$config		=& $this->locator->get('config');
		$config->set('config_tax', $config->get('config_tax_store'));
		$cart		=& $this->locator->get('cart');
		$currency	=& $this->locator->get('currency');
		$language	=& $this->locator->get('language');
		$url		=& $this->locator->get('url');
		$request	=& $this->locator->get('request');
		$response	=& $this->locator->get('response');
		$tax		=& $this->locator->get('tax');
		$session	=& $this->locator->get('session');

		if ($request->isPost() && $request->has('item', 'post')){
			$cart->add($request->gethtml('item', 'post'), $request->gethtml('quantity', 'post'), $request->gethtml('option', 'post'));
		} else {
			$cart->add($request->gethtml('item'), $request->gethtml('quantity'), $request->gethtml('option'));
		}
		$item = $request->get('item');
		if ($item){
			$cart->subtotal = '0';
			$cart->total = '0';
			$cart->data_refresh();
			if ($config->get('cart_status')) {
				$language->load('extension/module/cart.php');
				$products = array();
				foreach ($cart->getProducts() as $result) {
					$products[] = array(
						'href'		=> $url->ssl('product', false, array('product_id' => $result['product_id'])),
						'name'		=> $result['name'],
						'quantity'	=> $result['quantity'],
						'total'		=> $currency->format($tax->calculate($result['total'], $result['tax_class_id'], $config->get('config_tax')))
					);
				}
				//Create Output to Mini Cart
				$output = '<div class="headingcolumn pointer" onclick="ShowCart()"><h3>' . $language->get('heading_title') . '</h3></div>' . "\n";
				$output .= '<div  class="cart">' . "\n";
				$output .= '<div id="cart_content" class="cart_content">' . "\n";
				if($products) {
					$output .= '<div id="cart_products">' . "\n";
					$output .= '<table>' . "\n";
					foreach ($products as $product) {
						$output .= '<tr>' . "\n";
						$output .= '<td>' . $product['quantity'] . '&nbsp;x&nbsp;</td>' . "\n";
						$output .= '<td class="ff"><a href="' . $product['href'] . '">' . $product['name'] . '</a></td>' . "\n";
						$output .= '<td class="ee"> ' . $product['total'] . '</td>' . "\n";
						$output .= '</tr>' . "\n";
					}
					$output .= '</table>' . "\n";
					$output .= '</div>' . "\n";
					$output .= '<div class="aa">' . $language->get('text_subtotal') . $currency->format($cart->getsubTotal()) . '</div>' . "\n";
					
					$output .= '<div class="cc">' . count($products) .' '. $language->get('text_products') .' - ';
					$output .= '<div class="dd">' . $cart->countProducts() .' '. $language->get('text_items') . '</div></div>' . "\n";
					$output .= '<div class="bb" id="cart_button"><a href="' . $url->ssl('cart') . '">' . $language->get('text_view_cart') . '</a></div>'  . "\n";
				} else {
					$output .= '<div class="bb">' . $language->get('text_empty') . '</div>' . "\n"; 
				}
				$output .= '</div></div>' . "\n";
				$output .= '<div class="bottom"></div>' . "\n";
				$output .= '<script type="text/javascript"><!--' . "\n";
				$output .= '$(\'#cart_products, #cart_button\').show(\'slow\');' . "\n";
				$output .= '$(\'#cart_products, #cart_button\').hide(3500);' . "\n";
				$output .= '//--></script>' . "\n";
				
				$response->set($output);
			}
		}
	}
}
?>

<?php // Cart AlegroCart
class ControllerCart extends Controller {

	var $error = array();
	var $error_min = array();
	var $error_max = array();

	public function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->address		=& $locator->get('address');
		$this->config		=& $locator->get('config');
		$this->check_ssl();
		$this->config->set('config_tax', $this->config->get('config_tax_store'));
		$this->calculate	=& $locator->get('calculate');
		$this->cart		=& $locator->get('cart');
		$this->coupon		=& $locator->get('coupon');
		$this->currency		=& $locator->get('currency');
		$this->customer		=& $locator->get('customer');
		$this->head_def		=& $locator->get('HeaderDefinition');  
		$this->language		=& $locator->get('language');
		$this->image		=& $locator->get('image');
		$this->module		=& $locator->get('module');
		$this->response		=& $locator->get('response');
		$this->request		=& $locator->get('request');
		$this->shipping		=& $locator->get('shipping');
		$this->session		=& $locator->get('session');
		$this->tax		=& $locator->get('tax');
		$this->template		=& $locator->get('template');
		$this->url		=& $locator->get('url');
		$this->modelCore	= $model->get('model_core');
		require_once('library/application/string_modify.php');   
		$this->tpl_manager = $this->modelCore->get_tpl_manager('cart'); // Template Manager
		$this->locations = $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns = $this->modelCore->get_columns();// Template Manager
		$this->language->load('controller/cart.php');
	}

	function index() {

	if ($this->request->isPost() && !$this->request->has('currency', 'post') && !$this->request->has('module_language', 'post')) { 
		if($this->session->has('shipping_method') && !$this->customer->isLogged()) {
			$this->session->delete('shipping_method');
			$this->session->set('had_shipping_method', TRUE);
		}
		if ($this->request->gethtml('quantity', 'post') != null && $this->request->gethtml('quantity', 'post')) {
			foreach ($this->request->gethtml('quantity', 'post') as $key => $value) {
				$this->session->set('min_qty_error['.$key.']', '0');
				$this->session->set('line_min_error['.$key.']', '0');
				$this->session->set('max_qty_error['.$key.']', '0');
				$this->session->set('line_max_error['.$key.']', '0');
				$this->session->set('multiple qty_error['.$key.']', '0');
				$this->session->set('line_multiple_error['.$key.']', '0');

					if ($this->request->gethtml('min_qty', 'post') != null) {
						foreach ($this->request->gethtml('min_qty', 'post') as $k => $v) {
							if ($k == $key) {
								if ($value != 0) {
									if ($value < $v) {
										$value = $v;
										$this->session->set('min_qty_error['.$key.']', '1');
										$this->session->set('line_min_error['.$key.']', '1');
									}
								}
							$this->cart->update($key, (int)$value);
							}
						}
					}

					if ($this->request->gethtml('max_qty', 'post') != null) {
						foreach ($this->request->gethtml('max_qty', 'post') as $k => $v) {
							if ($k == $key) {
								if ($value != 0 && $v != 0) {
									if ($value > $v) {
										$value = $v;
										$this->session->set('max_qty_error['.$key.']', '1');
										$this->session->set('line_max_error['.$key.']', '1');
									}
								}
							$this->cart->update($key, (int)$value);
							}
						}
					}

					if ($this->request->gethtml('multiple', 'post') != null) {
						foreach ($this->request->gethtml('multiple', 'post') as $k => $v) {
							if ($k == $key) {
								if ($value != 0 && $v != 0) {
									if ($value < $v) {
										$value = $v;
										$this->session->set('multiple_qty_error['.$key.']', '1');
										$this->session->set('line_multiple_error['.$key.']', '1');
									}else {
										if ($value % $v == 0) {
											$value = $value;
										} else {
											$rest = $value % $v;
											$value += $v - $rest;
											$this->session->set('multiple_qty_error['.$key.']', '1');
											$this->session->set('line_multiple_error['.$key.']', '1');
											if ($this->request->gethtml('max_qty', 'post') != null) {
												foreach ($this->request->gethtml('max_qty', 'post') as $ke => $va) {
													if ($ke == $k) {
														if ($value > $va) {
															$value -= $v;
															$this->session->set('multiple_qty_error['.$key.']', '1');
															$this->session->set('line_multiple_error['.$key.']', '1');
														}
													}
												}
											}
										}
									}
								}
							$this->cart->update($key, (int)$value);
							}
						}
					}
					$this->cart->update($key, (int)$value);
			}
		}

		if ($this->request->gethtml('remove', 'post')) {
			foreach (array_keys($this->request->gethtml('remove', 'post')) as $key) {
				$this->cart->remove($key);
				}
		}

		$this->validate();

		$this->response->redirect($this->url->ssl('cart'));
	}

	$this->template->set('title', $this->language->get('heading_title'));
	$view = $this->locator->create('template');
	$view->set('head_def',$this->head_def);    // New Header
	$view->set('heading_title', $this->language->get('heading_title'));
	$this->template->set('head_def',$this->head_def);    // New Header
	$view->set('tax_included', $this->config->get('config_tax'));

	if ($this->cart->hasProducts()) {
		$totals = $this->calculate->getTotals(); //************************
		$view->set('totals', $totals);

		if ($this->customer->isLogged() && !$this->address->has($this->session->get('shipping_address_id'))) {
			$this->session->set('shipping_address_id', $this->customer->getAddressId());
		}

		$view->set('text_stock_ind', $this->language->get('text_stock_ind'));
		$view->set('text_min_order_value', $this->language->get('text_min_order_value'));
		$view->set('text_min_qty_ind', $this->language->get('text_min_qty_ind'));
		$view->set('text_max_qty_ind', $this->language->get('text_max_qty_ind'));
		$view->set('text_multiple_qty_ind', $this->language->get('text_multiple_qty_ind'));
		$view->set('text_shipping', $this->language->get('text_shipping'));
		$view->set('text_shippable', $this->language->get('text_shippable'));
		$view->set('text_non_shippable', $this->language->get('text_non_shippable'));
		$view->set('text_tax', $this->language->get('text_tax'));
		$view->set('text_tax_explantion', $this->language->get('text_tax_explantion'));
		$view->set('text_product_totals', $this->language->get('text_product_totals'));
		$view->set('text_downloadable', $this->language->get('text_downloadable'));
		$view->set('text_shipping_method', $this->language->get('text_shipping_method'));
		$view->set('text_shipping_methods', $this->language->get('text_shipping_methods'));
		$view->set('text_estimate', $this->language->get('text_estimate'));
		$view->set('text_merged', $this->language->get('text_merged'));
		$view->set('text_modified', $this->language->get('text_modified'));

		$view->set('text_soldby', $this->language->get('text_soldby'));
		$view->set('column_remove', $this->language->get('column_remove'));
		$view->set('column_image', $this->language->get('column_image'));
		$view->set('column_name', $this->language->get('column_name'));
		$view->set('column_quantity', $this->language->get('column_quantity'));
		$view->set('column_price', $this->language->get('column_price'));
		$view->set('column_special', $this->language->get('column_special'));
		$view->set('column_discount_value', $this->language->get('column_discount_value'));
		$view->set('column_coupon_value', $this->language->get('column_coupon_value'));
		$view->set('column_extended', $this->language->get('column_extended'));
		$view->set('column_total', $this->language->get('column_total'));
		$view->set('column_min_qty', $this->language->get('column_min_qty'));
		$view->set('column_max_qty', $this->language->get('column_max_qty'));
		$view->set('entry_coupon', $this->language->get('entry_coupon'));
		$view->set('entry_country', $this->language->get('entry_country'));
		$view->set('entry_zone', $this->language->get('entry_zone'));
		$view->set('entry_postcode', $this->language->get('entry_postcode'));

		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_shopping', $this->language->get('button_shopping'));
		$view->set('button_checkout', $this->language->get('button_checkout'));
		$view->set('button_calculate', $this->language->get('button_calculate'));

		$view->set('max_qty_column', $this->cart->hasMaxQty());

		$view->set('error', @$this->error['message']);
		$view->set('error_max', @$this->error_max['message']);
		$view->set('error_min', @$this->error_min['message']);
		$view->set('error_multiple', @$this->error_multiple['message']);
		$error_stock_check = ((!$this->cart->hasStock()) && ($this->config->get('config_stock_check')) ? $this->language->get('error_stock') : NULL);
		$error_checkout = ((!$this->cart->hasStock()) && ($this->config->get('config_stock_checkout')) ? $this->language->get('error_checkout') : NULL);
		$view->set('error', $error_stock_check . $error_checkout);
		$view->set('stock_check', $this->config->get('config_stock_check'));
		$view->set('error_calc_request', $this->language->get('error_calc_request'));
		$view->set('error_calc_response', $this->language->get('error_calc_response'));

		if ($this->session->has('error')) {
			$view->set('error', $this->session->get('error'));
			$this->session->delete('error');
			$view->set('message', '');
		} elseif ($this->session->has('merged_message')) {
			$view->set('message', $this->language->get('text_merged'));
		} elseif ($this->session->has('modified_message') && !$this->customer->isLogged() && $this->session->has('had_shipping_method')) {
			$view->set('message', $this->language->get('text_modified'));
		} else {
			$view->set('message', $this->coupon->getCode() ? $this->session->get('coupon_message') : '');
		}
		$this->session->delete('coupon_message');
		$this->session->delete('merged_message');
		$this->session->delete('modified_message');
		$this->session->delete('had_shipping_method');

		if ($this->request->has('country_id', 'post')) {
			$view->set('country_id', $this->request->gethtml('country_id', 'post'));
		} elseif ($this->session->has('country_id')) {
			$view->set('country_id', $this->session->get('country_id'));
		} else {
			$view->set('country_id', $this->config->get('config_country_id'));
		}

		if ($this->request->has('zone_id', 'post')) {
			$view->set('zone_id', $this->request->gethtml('zone_id', 'post'));
		} elseif ($this->session->has('zone_id')) {
			$view->set('zone_id', $this->session->get('zone_id'));
		} else {
			$view->set('zone_id', $this->config->get('config_zone_id'));
		}

		if ($this->request->has('postcode', 'post')) {
			$view->set('postcode', $this->request->sanitize('postcode', 'post'));
		} else {
			$view->set('postcode', $this->session->get('postcode'));
		}
		$view->set('countries',$this->modelCore->get_countries());
		$view->set('has_shipping',$this->cart->hasShipping());
		$view->set('estimated_shipping_status', $this->config->get('config_estimate'));
		$view->set('islogged', $this->customer->isLogged());

		$view->set('couponproducts', $this->coupon->hasProducts());
		$view->set('coupon', $this->coupon->getCode());
		$view->set('action', $this->url->ssl('cart'));

		$product_data = array();
		$subtotal = 0;
		$coupon_total = NULL;
		$discount_total = NULL;
		$extended_total = 0;
		$net_total = 0;
		foreach ($this->cart->getProducts() as $result) {
			$option_data = array();

			foreach ($result['option'] as $option) {
				$option_data[] = array(
				'name'  => $option['name'],
				'value' => $option['value'],
				);
			}

			// Minimum Order Verification
			$min_qty_error = '0';
			$line_min_error = '0';
			if ($result['quantity'] != 0) {
				if ($result['quantity'] < $result['min_qty']) {
					$result['quantity'] = $result['min_qty'];
					$min_qty_error = '1';
					$line_min_error = '1';
				}
			}
			// Maximum Order Verification
			$max_qty_error = '0';
			$line_max_error = '0';
			if ($result['quantity'] != 0 && $result['max_qty'] != 0) {
				if ($result['quantity'] > $result['max_qty']) {
					$result['quantity'] = $result['max_qty'];
					$max_qty_error = '1';
					$line_max_error = '1';
				}
			}
			// Multiple Verification
			$multiple_qty_error = 0;
			$line_multiple_error = 0;
			if ($result['quantity'] != 0 && $result['multiple'] != 0) {
				if ($result['quantity'] < $result['multiple']) {
					$result['quantity'] = $result['multiple'];
					$multiple_qty_error = '1';
					$line_multiple_error = '1';
				} else {
					if ($result['quantity'] % $result['multiple'] == 0) {
						$result['quantity'] = $result['quantity'];
					} else {
						$rest = $result['quantity'] % $result['multiple'];
						$result['quantity'] += $result['multiple'] - $rest;
						if ($result['max_qty'] != 0 && $result['quantity'] > $result['max_qty']) {
							$result['quantity'] -= $result['multiple'];
						}
						$multiple_qty_error = '1';
						$line_multiple_error = '1';
					}
				}
			}

			$special_price = $result['special_price'] ?$result['special_price'] - $result['discount'] : 0;
			if($result['special_price']){
				$discount_percent = (100 - $result['discount_percent'])/100;
				$discount = ($result['discount'] ? $this->currency->format($this->tax->calculate(($result['price'] * $discount_percent), $result['tax_class_id'], $this->config->get('config_tax'))) : NULL);
				} else {
				$discount = ($result['discount'] ? $this->currency->format($this->tax->calculate($result['price'] - $result['discount'], $result['tax_class_id'], $this->config->get('config_tax'))) : NULL);
			}
			$extended_total += $this->tax->calculate($result['total'], $result['tax_class_id'], $this->config->get('config_tax'));
			$coupon_total += $result['coupon'] ? $result['coupon'] : NULL;
			$discount_total += $result['general_discount'] ? $result['general_discount'] : NULL;
			$net_total += $result['total_discounted'];
			$subtotal += $result['total_discounted'] + ($this->config->get('config_tax') ? $result['product_tax'] : 0);

		$product_data[] = array(
			'key'           => $result['key'],
			'name'          => $result['name'],
			'model_number'		=> $result['model_number'],
			'shipping'		=> $result['shipping'],
			'download'		=> $result['download'],
			'thumb'			=> $this->image->resize($result['image'], 40, 40),
			'width'			=> '40',
			'height'		=> '40',
			'option'        => $option_data,
			'vendor_name'   => $result['vendor_name'],
			'quantity'      => $result['quantity'],
			'min_qty'       => $result['min_qty'],
			'min_qty_error' => ($line_min_error || $this->session->get('line_min_error['.$result['key'].']') ? '1' : '0'),
			'max_qty'       => $result['max_qty'],
			'max_qty_error'		=> ($line_max_error || $this->session->get('line_max_error['.$result['key'].']') ? '1' : '0'),
			'multiple'      => $result['multiple'],
			'multiple_qty_error'=> ($line_multiple_error || $this->session->get('line_multiple_error['.$result['key'].']') ? '1' : '0'),
			'stock'         => $result['stock'],
			'stock'         => $result['stock'],
			'price'         => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
			'special_price'	=> $special_price ? $this->currency->format($this->tax->calculate($special_price, $result['tax_class_id'], $this->config->get('config_tax'))) : NULL,
			'discount'      => $discount,
			'coupon'		=>  ($result['coupon'] ? '-' . $this->currency->format($result['coupon']) : NULL),
			'general_discount'	=> ($result['general_discount'] ? '-' . $this->currency->format($result['general_discount']) : NULL),
			'total_discounted'	=> $this->currency->format($result['total_discounted'] + ($this->config->get('config_tax') ? $result['product_tax'] : 0)),
			'total'			=> $this->currency->format($this->tax->calculate($result['total'], $result['tax_class_id'], $this->config->get('config_tax'))),
			'href'			=> $this->url->ssl('product', FALSE, array('product_id' => $result['product_id']))
		);

			if ($min_qty_error == '1' || $this->session->get('min_qty_error['.$result['key'].']')) {
				$view->set('error_min', $this->language->get('error_min_qty'));
				$this->session->set('min_qty_error['.$result['key'].']', '0');
				$this->session->set('line_min_error['.$result['key'].']', '0');
			}

			if ($max_qty_error == '1' || $this->session->get('max_qty_error['.$result['key'].']')) {
				$view->set('error_max', $this->language->get('error_max_qty'));
				$this->session->set('max_qty_error['.$result['key'].']', '0');
				$this->session->set('line_max_error['.$result['key'].']', '0');
			}

			if ($multiple_qty_error == '1' || $this->session->get('multiple_qty_error['.$result['key'].']')) {
				$view->set('error_multiple', $this->language->get('error_multiple_qty'));
				$this->session->set('multiple_qty_error['.$result['key'].']', '0');
				$this->session->set('line_multiple_error['.$result['key'].']', '0');
			}
		}

		$view->set('discount_status', $this->config->get('discount_status'));

		$discount_lprice = $this->config->get('discount_lprice');
		$discount_gprice = $this->config->get('discount_gprice');
		if ($discount_lprice != 0 && $discount_lprice > $net_total){
			$view->set('text_discount_lprice', $this->language->get('text_discount_lprice', $this->config->get('discount_lprice_percent'), $this->currency->format($discount_lprice)));
		}
		if ($discount_gprice != 0 && $discount_gprice > $net_total){
			$view->set('text_discount_gprice', $this->language->get('text_discount_gprice', $this->config->get('discount_gprice_percent'), $this->currency->format($discount_gprice)));
		}

		if (!$this->cart->moreThanMinov($this->cart->getNetTotal())) {
			$shortfall = 0;
			$shortfall = $this->config->get('minov_value') - $this->cart->getNetTotal();
			$view->set('text_shortfall', $this->language->get('text_shortfall', $this->currency->format($shortfall)));
		}

		$view->set('columns', $this->tpl_columns);
		$view->set('coupon_sort_order', $this->config->get('coupon_sort_order'));
		$view->set('discount_sort_order', $this->config->get('discount_sort_order'));
		$view->set('products', $product_data);
		$view->set('subtotal', $this->currency->format($subtotal));

		$view->set('text_net_total', $this->language->get('text_net_total'));
		$view->set('net_total', $this->currency->format($net_total));

		$view->set('extended_total', $this->currency->format($extended_total));
		$view->set('coupon_total', $coupon_total ? '-' . $this->currency->format($coupon_total) : NULL);
		$view->set('discount_total', $discount_total ? '-' . $this->currency->format($discount_total) : NULL);
		$view->set('weight', $this->cart->formatWeight($this->cart->getWeight()));
		$view->set('minov_value', $this->currency->format($this->config->get('minov_value')));
		$view->set('minov_status', $this->config->get('minov_status'));
		$view->set('text_cart_weight', $this->language->get('text_cart_weight'));
		$referer_page = $this->url->get_controller($this->session->get('current_page'),array('category','product', 'manufacturer' , 'search'));
		$view->set('continue', "location='" . ($referer_page && $this->session->get('current_page') ? $this->session->get('current_page') : $this->url->ssl('home')) . "'");

		$view->set('checkout', $this->url->ssl('checkout_shipping'));

		$this->template->set('content', $view->fetch('content/cart.tpl'));
	} else {
		$view->set('text_error', $this->language->get('text_error'));
		$view->set('this_controller', 'cart');
		$view->set('tax_included', $this->config->get('config_tax'));
		$view->set('button_continue', $this->language->get('button_continue'));
		$view->set('continue', $this->url->ssl('home'));
		$this->template->set('content', $view->fetch('content/error.tpl'));
	}

		$this->load_modules();  // Template Manager
		$this->set_tpl_modules(); // Template Manager
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function zone() {
		$output = '<select name="zone_id">';
		$results = $this->modelCore->return_zones($this->request->gethtml('country_id'));

		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if ($this->request->gethtml('zone_id') == $result['zone_id']) {
				$output .= ' SELECTED';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}

		$output .= '</select>';
		$this->response->set($output);
	}

	function estimate(){
		if ($this->request->isPost()) {
			$this->session->set('country_id', $this->request->sanitize('Country_id', 'post'));
			$this->session->set('zone_id', $this->request->sanitize('Zone_id', 'post'));
			$this->session->set('postcode', $this->request->sanitize('PostCode', 'post'));

			$estimated_quotes = $this->shipping->getQuotes();
			if ($estimated_quotes) {
				$html = '<div class="v">'.$this->language->get('text_shipping_method').'</div>';
				$html .= '<div class="w">'.$this->language->get('text_shipping_methods');
				foreach ($estimated_quotes as $estimated_quote){
					$html .= '<table class="method"><tbody><tr><td class="x" colspan="2"><b>'.$estimated_quote['title'].'</b></td></tr>';
						if (!$estimated_quote['error']){
							foreach ($estimated_quote['quote'] as $quote){
								if (isset($quote['error']) && !empty($quote['error'])){
									$html .= '<tr><td colspan="2" class="x"><div class="warning">'.$quote['error'].'</div></td></tr>';
								} else {
									$html .= '<tr><td class="x">';
									$html .= '<label for="'.$quote['id'].'">';
									$html .= '<input type="radio" name="shipping" value="'.$quote['id'].'" id="'.$quote['id'].'">';
									$html .= $quote['title'].'</label></td>';
									$html .= '<td class="y"><label for="'.$quote['id'].'">'. ($this->config->get('config_tax') ? '<span class="tax">*</span>' : '') . $quote['text'].'</label></td>';
									$html .= '<input type="hidden" name="'.$quote['id'].'_quote" value="'.$quote['text'].'"></tr>';

									if(isset($quote['shipping_form'])){
										$html.= $quote['shipping_form'];
									}
								}
							}
						} else {
							$html .= '<tr><td colspan="2" class="x"><div class="warning">'.$estimated_quote['error'].'</div></td></tr>';
						}
					$html .= '</tbody></table>';
				}
				$html .= '</div>';

				$html .= '<div class="buttons"><table><tr>';
				$html .= '<td align="left" class="buttons"><input type="button" name="apply" id="apply" value="'.$this->language->get('button_apply').'" ></td>';
				$html .= '</tr></table></div>';

				$html .= '<script type="text/javascript">';
				$html .= '$(document).ready(function(){';
				$html .= '$("#apply").hide();';
				$html .= '$(\'input[name="shipping"]\').on("click", function(){';
				$html .= 'if (!$(\'input[name="shipping"]\').is(\'checked\')) {';
				$html .= '$("#apply").show();';
				$html .= '}';
				$html .= '});';
				$html .= '});';
				$html .= '</script>';

				$html .= '<script type="text/javascript">';
				$html .= '$(\'input[name="shipping"]\').on("click", function(){';
				$html .= '$(\'input[name="shipping"]\').closest(\'table\').removeClass(\'default_method\').addClass(\'method\');';
				$html .= '$(this).closest(\'table\').attr(\'class\', \'default_method\');';
				$html .= '});';
				$html .= '</script>';

				$html .= '<script type="text/javascript">';
				$html .= '$("#apply").on("click", function(){';
				$html .= 'var shippingMethod = $(\'input[name=shipping]:checked\').val();';
				$html .= 'var data_json = {\'shippingMethod\':shippingMethod};';
				$html .= '$.ajax({';
				$html .= 'type: \'POST\',';
				$html .= 'url: \'index.php?controller=cart&action=apply\',';
				$html .= 'data: data_json,';
				$html .= 'dataType:\'json\',';
				$html .= 'beforeSend: function (data) {';
				$html .= '$(".apply_error").remove();';
				$html .= '$("#apply").prop(\'disabled\',true);';
				$html .= '},';
				$html .= 'success: function (data) {';
				$html .= 'if (data.status === true) {';
				$html .= '$("#estimated_results").remove();';
				$html .= '$(\'html, body\').scrollTop(0);';
				$html .= 'location.reload();';
				$html .= '} else {';
				$html .= '$(\'<div class="warning apply_error">'.$this->language->get('error_apply_response').'</div>\').insertBefore("#cart");';
				$html .= '$(\'html, body\').scrollTop(0);';
				$html .= '$("#apply").prop(\'disabled\',false);';
				$html .= '}';
				$html .= '$("#apply").prop(\'disabled\',false);';
				$html .= '},';
				$html .= 'error: function (data) {';
				$html .= '$(\'<div class="warning apply_error">'.$this->language->get('error_apply_request').'</div>\').insertBefore("#cart");';
				$html .= '$("#apply").prop(\'disabled\',false);';
				$html .= '}';
				$html .= '});';
				$html .= '});';
				$html .= '</script>';

			$output = array('status' => true, 'html' => $html);
			} else {
				$output = array('status' => false);
			}
			echo json_encode($output);
		}
	}

	function apply() {
		if ($this->request->isPost()) {
			if ($this->request->has('shippingMethod', 'post')) {
				$this->session->set('shipping_method', $this->request->sanitize('shippingMethod', 'post'));
				$output = array('status' => true);
			} else {
				$output = array('status' => false);
			}
			echo json_encode($output);
		}
	}


	function load_modules(){ // Template Manager
		$modules = $this->modelCore->merge_modules($this->get_modules_extra());
		foreach ($this->locations as $location){
			if($modules[$location['location']]){
				foreach($modules[$location['location']] as $module){
					$this->template->set($this->module->load($module));
				}
			}
		}
	}

	function get_modules_extra(){// Template Manager (Default Modules specific to current controller)
		foreach($this->locations as $location){
			$modules_extra[$location['location']] = array();
		}
		if($this->tpl_columns == 1.2 || $this->tpl_columns == 3){
			$modules_extra['column'] = array('popular');
			$modules_extra['columnright'] = array('specials');
		} elseif ($this->tpl_columns == 2.1) {
			$modules_extra['columnright'] = array('popular');
		}
		return $modules_extra;
	}

	function set_tpl_modules(){ // Template Manager
		if($this->modelCore->tpl){
			if(isset($this->modelCore->tpl['tpl_headers'])){$this->template->set('tpl_headers',$this->modelCore->tpl['tpl_headers']);}
			if(isset($this->modelCore->tpl['tpl_extras'])){$this->template->set('tpl_extras',$this->modelCore->tpl['tpl_extras']);}
			if(isset($this->modelCore->tpl['tpl_left_columns'])){$this->template->set('tpl_left_columns',$this->modelCore->tpl['tpl_left_columns']);}
			if(isset($this->modelCore->tpl['tpl_contents'])){$this->template->set('tpl_contents',$this->modelCore->tpl['tpl_contents']);}
			if(isset($this->modelCore->tpl['tpl_right_columns'])){$this->template->set('tpl_right_columns',$this->modelCore->tpl['tpl_right_columns']);}
			if(isset($this->modelCore->tpl['tpl_footers'])){$this->template->set('tpl_footers',$this->modelCore->tpl['tpl_footers']);}
			if(isset($this->modelCore->tpl['tpl_bottom'])){$this->template->set('tpl_bottom',$this->modelCore->tpl['tpl_bottom']);}
		}
		if(isset($this->tpl_manager['tpl_color']) && $this->tpl_manager['tpl_color']){$this->template->set('template_color',$this->tpl_manager['tpl_color']);}
		$this->template->set('tpl_columns', $this->modelCore->tpl_columns);
	}

	function validate() {
		if(!$this->request->gethtml('coupon', 'post')){
			$this->session->delete('coupon_message');
		} else {
			if ($this->coupon->getCode() != $this->request->gethtml('coupon', 'post')){
				$this->session->set('coupon_message', $this->language->get('text_coupon'));
			}
			if (!$this->coupon->set($this->request->gethtml('coupon', 'post'))) {
				$this->session->set('error', $this->language->get('error_coupon'));
				$this->session->delete('coupon_message');
				if (!$this->coupon->hasProduct()) {
					$this->session->set('error', $this->language->get('error_product')); 
				}
			}
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function check_ssl(){
		if(!((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) && $this->config->get('config_ssl')){
			header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		}
	}

}
?>

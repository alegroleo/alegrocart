<link rel="stylesheet" href="javascript/JSCookMenu/default/theme.css" type="text/css">
<script type="text/javascript" src="javascript/JSCookMenu/JSCookMenu.js"></script>
<script type="text/javascript" src="javascript/JSCookMenu/default/theme.js"></script>
<div id="myMenuID"></div>
<script language="JavaScript"><!--  
  var myMenu = [ 
     [null, '<?php echo $text_system; ?>', null, null, null,
	    ['<img src="javascript/JSCookMenu/default/home.png">', '<?php echo $text_home; ?>', '<?php echo $home; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/shop.png">', '<?php echo $text_shop; ?>', '<?php echo $shop; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/maintenance.png">', '<?php echo $text_maintenance; ?>', '<?php echo $maintenance; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/backup.png">', '<?php echo $text_backup; ?>', '<?php echo $backup; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/server_info.png">', '<?php echo $text_server_info; ?>', '<?php echo $server_info; ?>', null, null], 
	    ['<img src="javascript/JSCookMenu/default/logout.png">', '<?php echo $text_logout; ?>', '<?php echo $logout; ?>', null, null]
	  ],
	_cmSplit,	
	  [null, '<?php echo $text_configuration; ?>', null, null, null, 	  
	    ['<img src="javascript/JSCookMenu/default/setting.png">', '<?php echo $text_setting; ?>', '<?php echo $setting; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/users.png">', '<?php echo $text_users; ?>', null, null, null,
		      ['<img src="javascript/JSCookMenu/default/user.png">', '<?php echo $text_user; ?>', '<?php echo $user; ?>', null, null],
		      ['<img src="javascript/JSCookMenu/default/user_group.png">', '<?php echo $text_user_group; ?>', '<?php echo $usergroup; ?>', null, null]
	    ],
	    ['<img src="javascript/JSCookMenu/default/localisation.png">', '<?php echo $text_localisation; ?>', null, null, null,
		      ['<img src="javascript/JSCookMenu/default/language.png">', '<?php echo $text_language; ?>', '<?php echo $language; ?>', null, null],
		      ['<img src="javascript/JSCookMenu/default/currency.png">', '<?php echo $text_currency; ?>', '<?php echo $currency; ?>', null, null],
		      ['<img src="javascript/JSCookMenu/default/order_status.png">', '<?php echo $text_order_status; ?>', '<?php echo $order_status; ?>', null, null],
		      ['<img src="javascript/JSCookMenu/default/country.png">', '<?php echo $text_country; ?>', '<?php echo $country; ?>', null, null],
		      ['<img src="javascript/JSCookMenu/default/zone.png">', '<?php echo $text_zone; ?>', '<?php echo $zone; ?>', null, null],
		      ['<img src="javascript/JSCookMenu/default/geo_zone.png">', '<?php echo $text_geo_zone; ?>', '<?php echo $geo_zone; ?>', null, null],
		      ['<img src="javascript/JSCookMenu/default/tax_class.png">', '<?php echo $text_tax_class; ?>', '<?php echo $tax_class; ?>', null, null],
		      ['<img src="javascript/JSCookMenu/default/weight_class.png">', '<?php echo $text_weight_class; ?>', '<?php echo $weight_class; ?>', null, null],
			  ['<img src="javascript/JSCookMenu/default/dimension_class.png">', '<?php echo $text_dimension_class; ?>', '<?php echo $dimension_class; ?>', null, null]
	    ],
	    ['<img src="javascript/JSCookMenu/default/url_alias.png">', '<?php echo $text_url_alias; ?>', '<?php echo $url_alias; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/home_page.png">', '<?php echo $text_homepage; ?>', '<?php echo $homepage; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/tpl_manager.png">', '<?php echo $text_tpl_manager; ?>', '<?php echo $template_manager; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/image_display.png">', '<?php echo $text_image_display; ?>', '<?php echo $image_display; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/minov.png" />', '<?php echo $text_minov; ?>', '<?php echo $minov; ?>', null, null]
    ],
	_cmSplit,	
	  [null, '<?php echo $text_catalog; ?>', null, null, null, 	  
	    ['<img src="javascript/JSCookMenu/default/category.png">', '<?php echo $text_category; ?>', '<?php echo $category; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/product.png">', '<?php echo $text_product; ?>', '<?php echo $product; ?>', null, null],
		['<img src="javascript/JSCookMenu/default/products_with_options.png">', '<?php echo $text_products_with_options; ?>', '<?php echo $products_with_options; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/option.png">', '<?php echo $text_option; ?>', '<?php echo $option; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/manufacturer.png">', '<?php echo $text_manufacturer; ?>', '<?php echo $manufacturer; ?>', null, null],	
	    ['<img src="javascript/JSCookMenu/default/image.png">', '<?php echo $text_image; ?>', '<?php echo $image; ?>', null, null],	
		['<img src="javascript/JSCookMenu/default/download.png">', '<?php echo $text_download; ?>', '<?php echo $download; ?>', null, null],	
	    ['<img src="javascript/JSCookMenu/default/review.png">', '<?php echo $text_review; ?>', '<?php echo $review; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/information.png">', '<?php echo $text_information; ?>', '<?php echo $information; ?>', null, null]
    ],
	_cmSplit,  
	  [null, '<?php echo $text_extension; ?>', null, null, null, 	  
	    ['<img src="javascript/JSCookMenu/default/module.png">', '<?php echo $text_module; ?>', '<?php echo $module; ?>', null, null],
		['<img src="javascript/JSCookMenu/default/shipping.png">', '<?php echo $text_shipping; ?>', '<?php echo $shipping; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/payment.png">', '<?php echo $text_payment; ?>', '<?php echo $payment; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/calculate.png">', '<?php echo $text_calculate; ?>', '<?php echo $calculate; ?>', null, null]
    ],
	_cmSplit,	
	  [null, '<?php echo $text_customers; ?>', null, null, null, 	  
	    ['<img src="javascript/JSCookMenu/default/customer.png">', '<?php echo $text_customer; ?>', '<?php echo $customer; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/order.png">', '<?php echo $text_order; ?>', '<?php echo $order; ?>', null, null],
		['<img src="javascript/JSCookMenu/default/coupon.png">', '<?php echo $text_coupon; ?>', '<?php echo $coupon; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/mail.png">', '<?php echo $text_mail; ?>', '<?php echo $mail; ?>', null, null],
		['<img src="javascript/JSCookMenu/default/newsletter.png">', '<?php echo $text_newsletter; ?>', '<?php echo $newsletter; ?>', null, null],
    ],	
	_cmSplit,	
	  [null, '<?php echo $text_reports; ?>', null, null, null, 	  
	    ['<img src="javascript/JSCookMenu/default/report.png">', '<?php echo $text_online; ?>', '<?php echo $online; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/report.png">', '<?php echo $text_sale; ?>', '<?php echo $sale; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/report.png">', '<?php echo $text_viewed; ?>', '<?php echo $viewed; ?>', null, null],
	    ['<img src="javascript/JSCookMenu/default/report.png">', '<?php echo $text_purchased; ?>', '<?php echo $purchased; ?>', null, null],
    ]			
  ]
//--></script>
<script language="JavaScript"><!--
  cmDraw('myMenuID', myMenu, 'hbr', cmThemeDefault, 'ThemeDefault');
//--></script>
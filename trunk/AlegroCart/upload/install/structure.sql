#
# TABLE STRUCTURE FOR: `address`
#

CREATE TABLE IF NOT EXISTS `address` (
  `address_id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL default '0',
  `company` varchar(32) collate utf8_unicode_ci default NULL,
  `firstname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `lastname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `address_1` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `address_2` varchar(64) collate utf8_unicode_ci default NULL,
  `postcode` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `city` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `country_id` int(11) NOT NULL default '0',
  `zone_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`address_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `category`
#

CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(11) NOT NULL auto_increment,
  `image_id` int(11) NOT NULL default '0',
  `parent_id` int(11) NOT NULL default '0',
  `path` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `sort_order` int(3) default '0',
  `date_added` datetime default NULL,
  `date_modified` datetime default NULL,
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `category_description`
#

CREATE TABLE IF NOT EXISTS `category_description` (
  `category_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '1',
  `name` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `description` text collate utf8_unicode_ci default NULL,
  `meta_keywords` varchar(255) collate utf8_unicode_ci default NULL,
  `meta_description` varchar(255) collate utf8_unicode_ci default NULL,
  `meta_title` varchar(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`category_id`,`language_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `country`
#

CREATE TABLE IF NOT EXISTS `country` (
  `country_id` int(11) NOT NULL auto_increment,
  `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `country_status` int(1) collate utf8_unicode_ci NOT NULL default '0',
  `iso_code_2` varchar(2) collate utf8_unicode_ci NOT NULL default '',
  `iso_code_3` varchar(3) collate utf8_unicode_ci NOT NULL default '',
  `address_format` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`country_id`)
) ENGINE=MyISAM AUTO_INCREMENT=250 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `coupon`
#

CREATE TABLE IF NOT EXISTS `coupon` (
  `coupon_id` int(11) NOT NULL auto_increment,
  `code` varchar(10) collate utf8_unicode_ci NOT NULL,
  `discount` decimal(15,4) NOT NULL,
  `prefix` varchar(1) collate utf8_unicode_ci NOT NULL,
  `shipping` int(1) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `uses_total` int(11) NOT NULL,
  `uses_customer` varchar(11) collate utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY  (`coupon_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `coupon_description`
#

CREATE TABLE IF NOT EXISTS `coupon_description` (
  `coupon_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(128) collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`coupon_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `coupon_product`
#

CREATE TABLE IF NOT EXISTS `coupon_product` (
  `coupon_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY  (`coupon_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `coupon_redeem`
#

CREATE TABLE IF NOT EXISTS `coupon_redeem` (
  `coupon_redeem_id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `coupon_id` int(11) NOT NULL,
  PRIMARY KEY  (`coupon_redeem_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `currency`
#

CREATE TABLE IF NOT EXISTS `currency` (
  `currency_id` int(11) NOT NULL auto_increment,
  `title` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `code` varchar(3) collate utf8_unicode_ci NOT NULL default '',
  `symbol_left` varchar(12) collate utf8_unicode_ci default NULL,
  `symbol_right` varchar(12) collate utf8_unicode_ci default NULL,
  `decimal_place` char(1) collate utf8_unicode_ci default NULL,
  `value` double(13,8) default NULL,
  `date_modified` datetime default NULL,
  PRIMARY KEY  (`currency_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `customer`
#

CREATE TABLE IF NOT EXISTS `customer` (
  `customer_id` int(11) NOT NULL auto_increment,
  `firstname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `lastname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `email` varchar(96) collate utf8_unicode_ci NOT NULL default '',
  `telephone` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `fax` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `password` varchar(40) collate utf8_unicode_ci NOT NULL default '',
  `newsletter` int(1) NOT NULL default '0',
  `address_id` int(11) NOT NULL default '0',
  `cart` text collate utf8_unicode_ci default NULL,
  `status` int(1) default '0',
  `ip` varchar(39) collate utf8_unicode_ci default NULL,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  PRIMARY KEY  (`customer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `download`
#

CREATE TABLE IF NOT EXISTS `download` (
  `download_id` int(11) NOT NULL auto_increment,
  `filename` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `mask` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `remaining` int(11) NOT NULL default '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  PRIMARY KEY  (`download_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `download_description`
#

CREATE TABLE IF NOT EXISTS `download_description` (
  `download_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '0',
  `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`download_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `extension`
#

CREATE TABLE IF NOT EXISTS `extension` (
  `extension_id` int(11) NOT NULL auto_increment,
  `code` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `type` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `directory` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `filename` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `controller` varchar(128) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`extension_id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `extension_description`
#

CREATE TABLE IF NOT EXISTS `extension_description` (
  `extension_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '0',
  `name` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `description` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`extension_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `geo_zone`
#

CREATE TABLE IF NOT EXISTS `geo_zone` (
  `geo_zone_id` int(11) NOT NULL auto_increment,
  `name` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `description` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `date_modified` datetime default NULL,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  PRIMARY KEY  (`geo_zone_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `home_description`
#

CREATE TABLE IF NOT EXISTS `home_description` (
  `home_id` int(11) NOT NULL auto_increment,
  `language_id` int(11) NOT NULL default '1',
  `title` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `description` text collate utf8_unicode_ci default NULL,
  `welcome` varchar(255) collate utf8_unicode_ci default NULL,
  `flash` varchar(128) collate utf8_unicode_ci default NULL,
  `image_id` int(11) default NULL,
  PRIMARY KEY  (`home_id`,`language_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `home_page`
#

CREATE TABLE IF NOT EXISTS `home_page` (
  `home_id` int(11) NOT NULL auto_increment,
  `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `status` int(1) NOT NULL default '0',
  PRIMARY KEY  (`home_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `image`
#

CREATE TABLE IF NOT EXISTS `image` (
  `image_id` int(11) NOT NULL auto_increment,
  `filename` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  PRIMARY KEY  (`image_id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `image_description`
#

CREATE TABLE IF NOT EXISTS `image_description` (
  `image_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '0',
  `title` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`image_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `information`
#

CREATE TABLE IF NOT EXISTS `information` (
  `information_id` int(11) NOT NULL auto_increment,
  `sort_order` int(3) NOT NULL default '0',
  PRIMARY KEY  (`information_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `information_description`
#

CREATE TABLE IF NOT EXISTS `information_description` (
  `information_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '0',
  `title` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `description` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`information_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `language`
#

CREATE TABLE IF NOT EXISTS `language` (
  `language_id` int(11) NOT NULL auto_increment,
  `name` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `code` varchar(5) collate utf8_unicode_ci default NULL,
  `image` varchar(64) collate utf8_unicode_ci default NULL,
  `directory` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `filename` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `sort_order` int(3) default NULL,
  PRIMARY KEY  (`language_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `manufacturer`
#

CREATE TABLE IF NOT EXISTS `manufacturer` (
  `manufacturer_id` int(11) NOT NULL auto_increment,
  `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `image_id` int(11) NOT NULL default '0',
  `sort_order` int(3) NOT NULL default '0',
  PRIMARY KEY  (`manufacturer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `newsletter`
#

CREATE TABLE IF NOT EXISTS `newsletter` (
  `newsletter_id` int(11) NOT NULL auto_increment,
  `subject` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `content` text collate utf8_unicode_ci NOT NULL,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_sent` datetime NOT NULL default '1000-01-01 00:00:00',
  PRIMARY KEY  (`newsletter_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `option`
#

CREATE TABLE IF NOT EXISTS `option` (
  `option_id` int(11) NOT NULL auto_increment,
  `language_id` int(11) NOT NULL default '0',
  `name` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`option_id`,`language_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `option_value`
#

CREATE TABLE IF NOT EXISTS `option_value` (
  `option_value_id` int(11) NOT NULL auto_increment,
  `language_id` int(11) NOT NULL default '0',
  `option_id` int(11) NOT NULL default '0',
  `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`option_value_id`,`language_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `order`
#

CREATE TABLE IF NOT EXISTS `order` (
  `order_id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL default '0',
  `reference` varchar(32) collate utf8_unicode_ci NOT NULL,
  `firstname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `lastname` varchar(32) collate utf8_unicode_ci default NULL,
  `telephone` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `fax` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `email` varchar(96) collate utf8_unicode_ci NOT NULL default '',
  `shipping_firstname` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `shipping_lastname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `shipping_company` varchar(32) collate utf8_unicode_ci default NULL,
  `shipping_address_1` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `shipping_address_2` varchar(32) collate utf8_unicode_ci default NULL,
  `shipping_city` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `shipping_postcode` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `shipping_zone` varchar(32) collate utf8_unicode_ci default NULL,
  `shipping_country` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `shipping_address_format` text collate utf8_unicode_ci NOT NULL,
  `shipping_method` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `payment_firstname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `payment_lastname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `payment_company` varchar(32) collate utf8_unicode_ci default NULL,
  `payment_address_1` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `payment_address_2` varchar(32) collate utf8_unicode_ci default NULL,
  `payment_city` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `payment_postcode` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `payment_zone` varchar(32) collate utf8_unicode_ci default NULL,
  `payment_country` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `payment_address_format` text collate utf8_unicode_ci NOT NULL,
  `payment_method` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `total` decimal(15,4) NOT NULL default '0.0000',
  `date_modified` datetime default NULL,
  `date_added` datetime default NULL,
  `order_status_id` int(5) NOT NULL default '0',
  `currency` varchar(3) collate utf8_unicode_ci default NULL,
  `value` decimal(14,6) default NULL,
  `ip` varchar(39) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `order_data`
#

CREATE TABLE IF NOT EXISTS `order_data` (
  `order_id` int(11) NOT NULL auto_increment,
  `reference` varchar(32) collate utf8_unicode_ci NOT NULL,
  `data` text collate utf8_unicode_ci NOT NULL,
  `expire` int(10) NOT NULL default '0',
  PRIMARY KEY  (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `order_download`
#

CREATE TABLE IF NOT EXISTS `order_download` (
  `order_download_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL default '0',
  `order_product_id` int(11) NOT NULL default '0',
  `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `filename` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `mask` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `remaining` int(3) NOT NULL default '0',
  PRIMARY KEY  (`order_download_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `order_history`
#

CREATE TABLE IF NOT EXISTS `order_history` (
  `order_history_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL default '0',
  `order_status_id` int(5) NOT NULL default '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `notify` int(1) default '0',
  `comment` text,
  PRIMARY KEY  (`order_history_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `order_option`
#

CREATE TABLE IF NOT EXISTS `order_option` (
  `order_option_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL default '0',
  `order_product_id` int(11) NOT NULL default '0',
  `name` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `value` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `price` decimal(15,4) NOT NULL default '0.0000',
  `prefix` char(1) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`order_option_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `order_product`
#

CREATE TABLE IF NOT EXISTS `order_product` (
  `order_product_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL default '0',
  `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `model` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `price` decimal(15,4) NOT NULL default '0.0000',
  `discount` decimal(15,4) NOT NULL,
  `total` decimal(15,4) NOT NULL default '0.0000',
  `tax` decimal(15,4) NOT NULL default '0.0000',
  `quantity` int(4) NOT NULL default '0',
  PRIMARY KEY  (`order_product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `order_status`
#

CREATE TABLE IF NOT EXISTS `order_status` (
  `order_status_id` int(11) NOT NULL auto_increment,
  `language_id` int(11) NOT NULL default '1',
  `name` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`order_status_id`,`language_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `order_total`
#

CREATE TABLE IF NOT EXISTS `order_total` (
  `order_total_id` int(10) unsigned NOT NULL auto_increment,
  `order_id` int(11) NOT NULL default '0',
  `title` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `text` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `value` decimal(15,4) NOT NULL default '0.0000',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`order_total_id`),
  KEY `idx_orders_total_orders_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `product`
#

CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int(11) NOT NULL auto_increment,
  `quantity` int(4) NOT NULL default '0',
  `min_qty` int(4) NOT NULL default '1',
  `manufacturer_id` int(11) NOT NULL default '0',
  `image_id` int(11) NOT NULL default '0',
  `shipping` int(1) NOT NULL default '1',
  `price` decimal(15,4) NOT NULL default '0.0000',
  `sort_order` int(3) NOT NULL default '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` datetime default NULL,
  `date_available` datetime default NULL,
  `weight` decimal(5,2) NOT NULL default '0.00',
  `weight_class_id` int(11) NOT NULL default '0',
  `status` int(1) NOT NULL default '0',
  `featured` int(1) NOT NULL default '0',
  `special_offer` int(1) NOT NULL default '0',
  `related` int(1) NOT NULL default '0',
  `sale_end_date` datetime NOT NULL default '1000-01-01 00:00:00',
  `sale_start_date` datetime NOT NULL default '1000-01-01 00:00:00',
  `special_price` decimal(15,4) NOT NULL default '0.0000',
  `tax_class_id` int(11) NOT NULL default '0',
  `viewed` int(5) NOT NULL default '0',
  PRIMARY KEY  (`product_id`),
  KEY `date_added` (`date_added`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `product_description`
#

CREATE TABLE IF NOT EXISTS `product_description` (
  `product_id` int(11) NOT NULL auto_increment,
  `language_id` int(11) NOT NULL default '1',
  `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `description` text collate utf8_unicode_ci,
  `technical` text collate utf8_unicode_ci,
  `model` varchar(32) collate utf8_unicode_ci default NULL,
  `meta_keywords` varchar(255) collate utf8_unicode_ci default NULL,
  `meta_description` varchar(255) collate utf8_unicode_ci default NULL,
  `meta_title` varchar(255) collate utf8_unicode_ci default NULL,
  `alt_description` varchar(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`product_id`,`language_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `product_discount`
#

CREATE TABLE IF NOT EXISTS `product_discount` (
  `product_discount_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `quantity` int(4) NOT NULL,
  `discount` decimal(15,4) NOT NULL,
  PRIMARY KEY  (`product_discount_id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

#
# TABLE STRUCTURE FOR: `product_to_category`
#

CREATE TABLE IF NOT EXISTS `product_to_category` (
  `product_id` int(11) NOT NULL default '0',
  `category_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`product_id`,`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `product_to_download`
#

CREATE TABLE IF NOT EXISTS `product_to_download` (
  `product_id` int(11) NOT NULL default '0',
  `download_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`product_id`,`download_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `product_to_image`
#

CREATE TABLE IF NOT EXISTS `product_to_image` (
  `product_id` int(11) NOT NULL default '0',
  `image_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`product_id`,`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `product_to_option`
#

CREATE TABLE IF NOT EXISTS `product_to_option` (
  `product_to_option_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL default '0',
  `option_id` int(11) NOT NULL default '0',
  `option_value_id` int(11) NOT NULL default '0',
  `price` decimal(15,4) NOT NULL default '0.0000',
  `prefix` char(1) collate utf8_unicode_ci NOT NULL default '+',
  `sort_order` int(3) NOT NULL default '0',
  PRIMARY KEY  (`product_to_option_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `related_products`
#

CREATE TABLE IF NOT EXISTS `related_products` (
  `product_id` int(11) NOT NULL,
  `related_product_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`related_product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `review`
#

CREATE TABLE IF NOT EXISTS `review` (
  `review_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL default '0',
  `customer_id` int(11) NOT NULL default '0',
  `author` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `text` text collate utf8_unicode_ci NOT NULL,
  `rating1` int(1) default NULL,
  `rating2` int(1) default NULL,
  `rating3` int(1) default NULL,
  `rating4` int(1) default NULL,
  `status` int(1) NOT NULL default '0',
  `date_added` datetime default NULL,
  `date_modified` datetime default NULL,
  PRIMARY KEY  (`review_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `session`
#

CREATE TABLE IF NOT EXISTS `session` (
  `session_id` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `expire` int(10) NOT NULL default '0',
  `value` text collate utf8_unicode_ci NOT NULL,
  `ip` varchar(39) collate utf8_unicode_ci NOT NULL,
  `time` datetime NOT NULL default '1000-01-01 00:00:00',
  `url` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `setting`
#

CREATE TABLE IF NOT EXISTS `setting` (
  `setting_id` int(11) NOT NULL auto_increment,
  `type` varchar(12) collate utf8_unicode_ci NOT NULL default '',
  `group` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `key` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `value` varchar(256) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`setting_id`),
  KEY `group` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `tax_class`
#

CREATE TABLE IF NOT EXISTS `tax_class` (
  `tax_class_id` int(11) NOT NULL auto_increment,
  `title` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `description` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` datetime NOT NULL default '1000-01-01 00:00:00',
  PRIMARY KEY  (`tax_class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `tax_rate`
#

CREATE TABLE IF NOT EXISTS `tax_rate` (
  `tax_rate_id` int(11) NOT NULL auto_increment,
  `geo_zone_id` int(11) NOT NULL default '0',
  `tax_class_id` int(11) NOT NULL default '0',
  `priority` int(5) default '1',
  `rate` decimal(7,4) NOT NULL default '0.0000',
  `description` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `date_modified` datetime default NULL,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  PRIMARY KEY  (`tax_rate_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `url_alias`
#

CREATE TABLE IF NOT EXISTS `url_alias` (
  `url_alias_id` int(11) NOT NULL auto_increment,
  `query` varchar(128) collate utf8_unicode_ci NOT NULL default '0',
  `alias` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`url_alias_id`),
  KEY `query` (`query`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `user`
#

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_group_id` int(11) NOT NULL default '0',
  `username` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `password` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `firstname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `lastname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `email` varchar(96) collate utf8_unicode_ci NOT NULL default '',
  `ip` varchar(39) collate utf8_unicode_ci NOT NULL,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `user_group`
#

CREATE TABLE IF NOT EXISTS `user_group` (
  `user_group_id` int(11) NOT NULL auto_increment,
  `name` varchar(64) collate utf8_unicode_ci default NULL,
  `permission` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`user_group_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `weight_class`
#

CREATE TABLE IF NOT EXISTS `weight_class` (
  `weight_class_id` int(11) NOT NULL auto_increment,
  `unit` varchar(4) collate utf8_unicode_ci NOT NULL default '',
  `language_id` int(11) NOT NULL default '0',
  `title` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`weight_class_id`,`language_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `weight_rule`
#

CREATE TABLE IF NOT EXISTS `weight_rule` (
  `from_id` int(11) NOT NULL default '0',
  `to_id` int(11) NOT NULL default '0',
  `rule` decimal(15,4) NOT NULL default '0.0000'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `zone`
#

CREATE TABLE IF NOT EXISTS `zone` (
  `zone_id` int(11) NOT NULL auto_increment,
  `country_id` int(11) NOT NULL default '0',
  `code` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `name` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `zone_status` int(1) NOT NULL default '1',
  PRIMARY KEY  (`zone_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3848 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: `zone_to_geo_zone`
#

CREATE TABLE IF NOT EXISTS `zone_to_geo_zone` (
  `zone_to_geo_zone_id` int(11) NOT NULL auto_increment,
  `country_id` int(11) NOT NULL default '0',
  `zone_id` int(11) default NULL,
  `geo_zone_id` int(11) default NULL,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` datetime NOT NULL default '1000-01-01 00:00:00',
  PRIMARY KEY  (`zone_to_geo_zone_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
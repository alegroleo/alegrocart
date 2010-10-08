#Version Setting
SET @ver='1.2.3';
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `type` = 'global' and `group` = 'version' and `key` = 'version';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'version', 'version', @ver) ON DUPLICATE KEY UPDATE value=@ver;

# Add the extension details to the database

SET @lid=1;
SELECT @lid:=language_id FROM language WHERE `code` = 'en';

# Extension Authorize Net
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_authnetaim';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'authnetaim', 'payment', 'payment', 'authnetaim.php', 'payment_authnetaim') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_authnetaim';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Authorize.Net (AIM)', 'Authorize.Net (AIM)') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#Settings AuthorizeNet
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_status';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_status', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_geo_zone_id';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_geo_zone_id', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_sendemail';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_sendemail', 'FALSE') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_test';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_test', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_prod_login';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_prod_login', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_prod_txnkey';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_prod_txnkey', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_test_login';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_test_login', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_test_txnkey';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_test_txnkey', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_authtype';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_authtype', 'auth_capture') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_sort_order';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_sort_order', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Add the extension details to the database

# Extension Google Checkout
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_google';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'google', 'payment', 'payment', 'google.php', 'payment_google') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_google';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Google Checkout', 'Google Checkout Payment Gateway') ON DUPLICATE KEY UPDATE extension_id=extension_id;

CREATE TABLE IF NOT EXISTS `order_google` (
  `order_reference` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `order_number` varchar(30) collate utf8_unicode_ci NOT NULL default '',
  `total` decimal(14,6) NOT NULL,
  PRIMARY KEY  (`order_reference`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

# Settings Google Checkout

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'google' and `key` = 'google_status';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'google', 'google_status', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'google' and `key` = 'google_geo_zone_id';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'google', 'google_geo_zone_id', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'google' and `key` = 'google_merchantid';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'google', 'google_merchantid', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'google' and `key` = 'google_merchantkey';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'google', 'google_merchantkey', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'google' and `key` = 'google_test';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'google', 'google_test', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'google' and `key` = 'google_currency';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'google', 'google_currency', 'USD') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'google' and `key` = 'google_sort_order';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'google', 'google_sort_order', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Add Flash Size to Homepage Description
ALTER TABLE `home_description`
ADD `flash_width` int(11) After `flash`,
ADD `flash_height` int(11) After `flash`;
ALTER TABLE `home_description` CHANGE `welcome` `welcome` VARCHAR( 510 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

#Add Model_Number Column
ALTER TABLE `order_product` DROP `model`;
ALTER TABLE `order_product`
ADD `model_number` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER `name`;
ALTER TABLE `product_description`
ADD `model_number` varchar( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER `model`;

# Settings for Catalog Options Display type select or radio
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'product_options_select';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'product_options_select', 'select') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'category_options_select';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'category_options_select', 'select') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'search_options_select';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'search_options_select', 'select') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'manufacturer' and `key` = 'manufacturer_options_select';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'manufacturer', 'manufacturer_options_select', 'select') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Start of version 1.2
# Setting for STYLES
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_styles';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'config_styles', 'default') ON DUPLICATE KEY UPDATE setting_id=setting_id;
# Setting for AutoUpdate SEO
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_seo';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_seo', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;
# Change URL Alias to Global
SET @ver='global';
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_url_alias';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_url_alias', '0') ON DUPLICATE KEY UPDATE type=@ver;
# Change vreview module to extra
set @ver='module_extra_review';
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `code` = 'review' and `type` ='module';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'review', 'module', 'module', 'review.php', 'module_extra_review') ON DUPLICATE KEY UPDATE controller=@ver;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'module_extra_review';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Catalog Review', 'Catalog Review') ON DUPLICATE KEY UPDATE extension_id=extension_id;

# Start of version 1.2.1
# Setting for COLORS
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_colors';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'config_colors', 'neutral.css') ON DUPLICATE KEY UPDATE setting_id=setting_id;
# Add Run Times and Meta tags to Home Page
ALTER TABLE `home_description`
ADD `run_times` int(11) default '1' After `image_id`,
ADD `meta_keywords` varchar(255) CHARACTER SET utf8 collate utf8_unicode_ci DEFAULT NULL AFTER `welcome`,
ADD `meta_description` varchar(512) CHARACTER SET utf8 collate utf8_unicode_ci DEFAULT NULL AFTER `welcome`,
ADD `meta_title` varchar(255) CHARACTER SET utf8 collate utf8_unicode_ci DEFAULT NULL AFTER `welcome`;

# Start of version 1.2.2
# Add option Weight  
ALTER TABLE `product_to_option`
ADD `option_weight` int(11) NOT NULL default '0' AFTER `prefix`;

# Add image display to featured
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'featured' and `key` = 'featured_image_display';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'featured', 'featured_image_display', 'image_link') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'featured' and `key` = 'featured_lines_single';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'featured', 'featured_lines_single', '6') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'featured' and `key` = 'featured_lines_multi';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'featured', 'featured_lines_multi', '4') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'featured' and `key` = 'featured_lines_char';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'featured', 'featured_lines_char', '108') on DUPLICATE KEY UPDATE setting_id=setting_id;

# Add Image display to latest
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'latest' and `key` = 'latest_image_display';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'latest', 'latest_image_display', 'image_link') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'latest' and `key` = 'latest_lines_single';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'latest', 'latest_lines_single', '6') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'latest' and `key` = 'latest_lines_multi';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'latest', 'latest_lines_multi', '4') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'latest' and `key` = 'latest_lines_char';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'latest', 'latest_lines_char', '108') on DUPLICATE KEY UPDATE setting_id=setting_id;

# Add Image display to specials
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'specials' and `key` = 'specials_columns';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'specials', 'specials_columns', '3') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'specials' and `key` = 'specials_image_display';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'specials', 'specials_image_display', 'image_link') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'specials' and `key` = 'specials_lines_single';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'specials', 'specials_lines_single', '6') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'specials' and `key` = 'specials_lines_multi';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'specials', 'specials_lines_multi', '3') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'specials' and `key` = 'specials_lines_char';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'specials', 'specials_lines_char', '108') on DUPLICATE KEY UPDATE setting_id=setting_id;

# Add Image display to related
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'related' and `key` = 'related_columns';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'related', 'related_columns', '3') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'related' and `key` = 'related_image_display';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'related', 'related_image_display', 'image_link') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'related' and `key` = 'related_lines_single';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'related', 'related_lines_single', '6') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'related' and `key` = 'related_lines_multi';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'related', 'related_lines_multi', '3') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'related' and `key` = 'related_lines_char';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'related', 'related_lines_char', '108') on DUPLICATE KEY UPDATE setting_id=setting_id;

# Add Image display to popular
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'popular' and `key` = 'popular_columns';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'popular', 'popular_columns', '1') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'popular' and `key` = 'popular_image_display';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'popular', 'popular_image_display', 'image_link') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'popular' and `key` = 'popular_lines_single';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'popular', 'popular_lines_single', '6') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'popular' and `key` = 'popular_lines_multi';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'popular', 'popular_lines_multi', '3') on DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'popular' and `key` = 'popular_lines_char';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'popular', 'popular_lines_char', '108') on DUPLICATE KEY UPDATE setting_id=setting_id;

# Add Image display to settings
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'product_image_display';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'product_image_display', 'thickbox') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'content_image_display';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'content_image_display', 'thickbox') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'content_lines_single';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'content_lines_single', '6') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'content_lines_multi';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'content_lines_multi', '4') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'content_lines_char';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'content_lines_char', '108') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Change order_status_id 
ALTER TABLE `order` CHANGE `order_status_id` `order_status_id` int(11) NOT NULL default '0';
ALTER TABLE `order_history` CHANGE `order_status_id` `order_status_id` int(11) NOT NULL default '0';

# Start of version 1.2.3
# Setting for Columns
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_columns';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'config_columns', '3') ON DUPLICATE KEY UPDATE setting_id=setting_id;

#
# TABLE STRUCTURE FOR: `tpl_manager`
#

CREATE TABLE IF NOT EXISTS `tpl_manager` (
  `tpl_manager_id` int(11) NOT NULL auto_increment,
  `tpl_controller` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `tpl_columns` int(3) default '0',
  `tpl_status` int(3) default '1',
  `tpl_color` varchar(64) collate utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY  (`tpl_manager_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `tpl_module` (
  `tpl_module_id` int(11) NOT NULL auto_increment,
  `tpl_manager_id` int(11) NOT NULL default '0',
  `location_id` int(11) NOT NULL default '0',
  `module_code` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `sort_order` int(3) default '0',
  PRIMARY KEY (`tpl_module_id`),
  KEY `location_id` (`location_id`),
  KEY `tpl_manager_id` (`tpl_manager_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `tpl_location` (
  `location_id` int(11) NOT NULL auto_increment,
  `location` varchar(32) collate utf8_unicode_ci NOT NULL default '', 
  PRIMARY KEY (`location_id`),
  KEY `location` (`location`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

SET @id=NULL;
SELECT @id:=location_id FROM tpl_location WHERE `location` = 'header' ;
INSERT INTO `tpl_location` (`location_id`, `location`) VALUES (@id, 'header') ON DUPLICATE KEY UPDATE location_id=location_id;
SET @id=NULL;
SELECT @id:=location_id FROM tpl_location WHERE `location` = 'extra' ;
INSERT INTO `tpl_location` (`location_id`, `location`) VALUES (@id, 'extra') ON DUPLICATE KEY UPDATE location_id=location_id;
SET @id=NULL;
SELECT @id:=location_id FROM tpl_location WHERE `location` = 'column' ;
INSERT INTO `tpl_location` (`location_id`, `location`) VALUES (@id, 'column') ON DUPLICATE KEY UPDATE location_id=location_id;
SET @id=NULL;
SELECT @id:=location_id FROM tpl_location WHERE `location` = 'content' ;
INSERT INTO `tpl_location` (`location_id`, `location`) VALUES (@id, 'content') ON DUPLICATE KEY UPDATE location_id=location_id;
SET @id=NULL;
SELECT @id:=location_id FROM tpl_location WHERE `location` = 'columnright' ;
INSERT INTO `tpl_location` (`location_id`, `location`) VALUES (@id, 'columnright') ON DUPLICATE KEY UPDATE location_id=location_id;
SET @id=NULL;
SELECT @id:=location_id FROM tpl_location WHERE `location` = 'footer' ;
INSERT INTO `tpl_location` (`location_id`, `location`) VALUES (@id, 'footer') ON DUPLICATE KEY UPDATE location_id=location_id;
SET @id=NULL;
SELECT @id:=location_id FROM tpl_location WHERE `location` = 'pagebottom' ;
INSERT INTO `tpl_location` (`location_id`, `location`) VALUES (@id, 'pagebottom') ON DUPLICATE KEY UPDATE location_id=location_id;

# Add Minimum_value to Coupon
ALTER TABLE `coupon`
ADD `minimum_order` decimal(15,4) NOT NULL default '0.0000' After `prefix`;

# extra Emails
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_email_orders';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_email_orders', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_email_accounts';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_email_accounts', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_email_newsletter';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_email_newsletter', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_email_mail';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_email_mail', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_email_contact';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_email_contact', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;

#Add to Cart Quantity settings
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'addtocart_quantity_box';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'addtocart_quantity_box', 'selectbox') on DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'addtocart_quantity_max';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'addtocart_quantity_max', '20') on DUPLICATE KEY UPDATE setting_id=setting_id;

# Developer Name and link
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'module_extra_developer';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'developer', 'module', 'module', 'developer.php', 'module_extra_developer') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'module_extra_developer';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Developer', 'Developer Information') ON DUPLICATE KEY UPDATE extension_id=extension_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'developer' and `key` = 'developer_developer';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'developer', 'developer_developer', 'Your Developer Team') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'developer' and `key` = 'developer_link';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'developer', 'developer_link', 'http://www.alegrocart.com') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'developer' and `key` = 'developer_status';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'developer', 'developer_status', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Start of version 1.2.4 **************
# Extension Cheque
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_cheque';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'cheque', 'payment', 'payment', 'cheque.php', 'payment_cheque') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_cheque';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Cheque Payment', 'Offline Payment by Cheque') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#Best Seller Module 
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'module_extra_bestseller';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES
(NULL, 'bestseller', 'module', 'module', 'bestseller.php', 'module_extra_bestseller') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'module_extra_bestseller';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES
(@id, @lid, 'Catalog bestseller', 'Display Best Seller Products') ON DUPLICATE KEY UPDATE extension_id=extension_id;

# Zone shipping Status
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'zone' and `key` = 'zone_status';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'zone', 'zone_status', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Extension MoneyOrder
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_moneyorder';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'moneyorder', 'payment', 'payment', 'moneyorder.php', 'payment_moneyorder') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_moneyorder';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Money Order Payment', 'Offline Payment by Money Order') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#extension Paymate
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_paymate';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'paymate', 'payment', 'payment', 'paymate.php', 'payment_paymate') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_paymate';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Paymate', 'Paymate Gateway') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#Remove Orderstatus 99
DELETE FROM `order_status` WHERE `order_status_id` = '99' AND `language_id` = '1';

# Calculate General Discount
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'calculate_discount';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'discount', 'calculate', 'calculate', 'discount.php', 'calculate_discount') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'calculate_discount';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Calculate Discount', 'Calculate Discount') ON DUPLICATE KEY UPDATE extension_id=extension_id;

# Change Settings
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `type` = 'global' and `group` = 'coupon' and `key` = 'coupon_sort_order';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'coupon', 'coupon_sort_order', '4') ON DUPLICATE KEY UPDATE value='4';
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `type` = 'global' and `group` = 'tax' and `key` = 'tax_sort_order';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'tax', 'tax_sort_order', '5') ON DUPLICATE KEY UPDATE value='5';
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `type` = 'global' and `group` = 'total' and `key` = 'total_sort_order';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'total', 'total_sort_order', '6') ON DUPLICATE KEY UPDATE value='6';


#Extension Image Display
SET @id=NULL;
SELECT @id:=extension_id FROM `extension` WHERE `code` = 'imagedisplay' and `controller` = 'module_extra_imagedisplay';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'imagedisplay', 'module', 'module', 'imagedisplay.php', 'module_extra_imagedisplay') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SELECT @id:=extension_id FROM `extension` WHERE `code` = 'imagedisplay' and `controller` = 'module_extra_imagedisplay';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Image Display', 'Image Display Module') ON DUPLICATE KEY UPDATE extension_id=extension_id;

# Setting ImageDisplay
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'imagedisplay' and `key` = 'imagedisplay_status';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'imagedisplay', 'imagedisplay_status', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

#
# TABLE STRUCTURE FOR: `image_display`
#

CREATE TABLE IF NOT EXISTS `image_display` (
 `image_display_id` int(11) NOT NULL auto_increment,
 `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
 `location_id` int(11) NOT NULL default '0',
 `status` int(1) NOT NULL default '0',
 `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`image_display_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `image_display_description` (
 `image_display_id` int(11) NOT NULL auto_increment,
 `language_id` int(11) NOT NULL default '1',
 `flash` varchar(128) collate utf8_unicode_ci default NULL,
 `flash_width` int(11) NOT NULL default '0',
 `flash_height` int(11) NOT NULL default '0',
 `flash_loop` int(11) NOT NULL default '0',
 `image_id` int(11) default NULL,
 `image_width` int(11) NOT NULL default '0',
 `image_height` int(11) NOT NULL default '0',
 PRIMARY KEY  (`image_display_id`,`language_id`)
 ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 
# Add Loop to Home Description
ALTER TABLE `home_description`
ADD `flash_loop` int(11) After `flash_height`;

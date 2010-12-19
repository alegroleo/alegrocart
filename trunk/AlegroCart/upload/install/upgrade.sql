#Version Setting
SET @ver='1.2.4';
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

# Extension Currency Converter
SET @id=NULL;
SELECT @id:=extension_id FROM `extension` WHERE `code` = 'converter' and `controller` = 'module_catalog_converter';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'converter', 'module', 'module', 'converter.php', 'module_catalog_converter') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SELECT @id:=extension_id FROM `extension` WHERE `code` = 'converter' and `controller` = 'module_catalog_converter';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Currency Converter', 'Currency Converter Module') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#Add Status and Rate Lock to Currency
ALTER  TABLE `currency`
ADD `lock_rate` int(1) NOT NULL default '0' After `code`,
ADD `status` int(1) NOT NULL default '1' After `code`;

# Setting Currency Surcharge
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_currency_surcharge';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_currency_surcharge', '0.000') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Currency Title change to 64 char
ALTER TABLE `currency` CHANGE `title` `title` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL default '';

#Add Currencies
SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'AED';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'United Arab Emirates Dirham', 'AED', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'ANG';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Netherlands Antillean Guilder', 'ANG', '0', '0', 'ƒ', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'ARS';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Argentine Peso', 'ARS', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'BDT';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Bangladeshi Taka', 'BDT', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'BGN';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Bulgarian Lev', 'BGN', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'BHD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Bahraini Dinar', 'BHD', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'BND';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Brunei Dollar', 'BND', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'BOB';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Bolivian Boliviano', 'BOB', '0', '0', '$b', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'BRL';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Brazilian Real', 'BRL', '0', '0', 'R$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'BWP';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Botswanan Pula', 'BWP', '0', '0', 'P', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'CHF';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Swiss Franc', 'CHF', '0', '0', 'CHF', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'CLP';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Chilean Peso', 'CLP', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'CNY';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Chinese Yuan Renminbi', 'CNY', '0', '0', '¥', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'COP';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Colombian Peso', 'COP', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'CRC';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Costa Rican Colón', 'CRC', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'CZK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Czech Republic Koruna', 'CZK', '0', '0', 'Kc', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'DKK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Danish Krone', 'DKK', '0', '0', 'kr', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'DOP';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Dominican Peso', 'DOP', '0', '0', 'RD$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'DZD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Algerian Dinar', 'DZD', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'EEK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Estonian Kroon', 'EEK', '0', '0', 'kr', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'EGP';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Egyptian Pound', 'EGP', '0', '0', '£', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'FJD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Fijian Dollar', 'FJD', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'HKD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Hong Kong Dollar', 'HKD', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'HNL';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Honduran Lempira', 'HNL', '0', '0', 'L', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'HRK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Croatian Kuna', 'HRK', '0', '0', 'kn', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'HUF';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Hungarian Forint', 'HUF', '0', '0', '', 'Ft', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'IDR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Indonesian Rupiah', 'IDR', '0', '0', 'Rp', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'ILS';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Israeli New Sheqel', 'ILS', '0', '0', '?', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'INR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Indian Rupee', 'INR', '0', '0', 'Rp', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'ISK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Icelandic Króna', 'ISK', '0', '0', 'kr', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'JMD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Jamaican Dollar', 'JMD', '0', '0', 'J$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'JOD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Jordanian Dinar', 'JOD', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'JPY';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Japanese Yen', 'JPY', '0', '0', '¥', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'KES';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Kenyan Shilling', 'KES', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'KRW';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'South Korean Won', 'KRW', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'KWD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Kuwaiti Dinar', 'KWD', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'KYD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Cayman Islands Dollar', 'KYD', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'KZT';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Kazakhstan Tenge', 'KZT', '0', '0', '??', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'LBP';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Lebanese Pound', 'LBP', '0', '0', '£', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'LKR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Sri Lanka Rupee', 'LKR', '0', '0', 'Rp', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'LTL';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Lithuanian Litas', 'LTL', '0', '0', 'Lt', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'LVL';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Latvian Lats', 'LVL', '0', '0', 'Ls', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'MAD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Moroccan Dirham', 'MAD', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'MDL';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Moldovan Leu', 'MDL', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'MKD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Macedonian Denar', 'MKD', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'MUR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Mauritian Rupee', 'MUR', '0', '0', 'Rp', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'MVR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Maldivian Rufiyaa', 'MVR', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'MXN';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Mexican Peso', 'MXN', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'MYR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Malaysian Ringgit', 'MYR', '0', '0', 'RM', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'NAD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Namibian Dollar', 'NAD', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'NGN';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Nigerian Naira', 'NGN', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'NIO';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Nicaraguan Cordoba Oro', 'NIO', '0', '0', 'C$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'NOK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Norwegian Krone', 'NOK', '0', '0', 'kr', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'NPR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Nepalese Rupee', 'NPR', '0', '0', 'Rp', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'NZD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'New Zealand Dollar', 'NZD', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'OMR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Omani Rial', 'OMR', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'PEN';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Peruvian Nuevo Sol', 'PEN', '0', '0', 'S/.', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'PGK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Papua New Guinean Kina', 'PGK', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'PHP';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Philippine Peso', 'PHP', '0', '0', 'Php', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'PKR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Pakistani Rupee', 'PKR', '0', '0', 'Rp', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'PLN';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Polish Zloty', 'PLN', '0', '0', 'zl', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'PYG';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Paraguayan Guarani', 'PYG', '0', '0', 'Gs', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'QAR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Qatari Rial', 'QAR', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'RON';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Romanian Leu', 'RON', '0', '0', 'lei', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'RSD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Serbian Dinar', 'RSD', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'RUB';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Russian Ruble', 'RUB', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'SAR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Saudi Riyal', 'SAR', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'SCR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Seychellois Rupee', 'SCR', '0', '0', 'Rp', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'SEK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Swedish Krona', 'SEK', '0', '0', 'kr', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'SGD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Singapore Dollar', 'SGD', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'SKK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Slovak Koruna', 'SKK', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'SLL';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Sierra Leonean Leone', 'SLL', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'SVC';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Salvadoran Colón', 'SVC', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'THB';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Thai Baht', 'THB', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'TND';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Tunisian Dinar', 'TND', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'TRY';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Turkish Lira', 'TRY', '0', '0', '£', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'TTD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Trinidad and Tobago Dollar', 'TTD', '0', '0', 'TT$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'TWD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'New Taiwan Dollar', 'TWD', '0', '0', 'NT$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'TZS';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Tanzanian Shilling', 'TZS', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'UAH';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Ukrainian Hryvnia', 'UAH', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'UGX';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Ugandan Shilling', 'UGX', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'UYU';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Uruguayan Peso', 'UYU', '0', '0', '$U', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'UZS';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Uzbekistan Som', 'UZS', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'VEF';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Venezuelan Bolr Fuerte', 'VEF', '0', '0', 'Bs', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'VND';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Vietnamese Dong', 'VND', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'XOF';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'CFA Franc BCEAO', 'XOF', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'YER';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Yemeni Rial', 'YER', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'ZAR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'South African Rand', 'ZAR', '0', '0', 'R', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id=currency_id FROM currency WHERE `code` = 'ZMK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Zambian Kwacha', 'ZMK', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

#New invoice and tax settings
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'invoice_number';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'invoice_number', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_tax_store';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_tax_store', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'search_rows';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'search_rows', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'category_rows';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'category_rows', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# New Invoice Order Columns
ALTER TABLE `order` ADD `coupon_sort_order` INT( 3 ) NOT NULL DEFAULT '0';
ALTER TABLE `order` ADD `discount_sort_order` INT( 3 ) NOT NULL DEFAULT '0';
ALTER TABLE `order_product` ADD `special_price` DECIMAL( 15, 4 ) NOT NULL DEFAULT '0.000' AFTER `discount`;
ALTER TABLE `order_product` ADD `coupon` DECIMAL( 15, 4 ) NOT NULL DEFAULT '0.000' AFTER `special_price`; 
ALTER TABLE `order_product` ADD `general_discount` DECIMAL( 15, 4 ) NOT NULL DEFAULT '0.000' AFTER `coupon`; 
ALTER TABLE `order_product` ADD `shipping` BOOLEAN NOT NULL DEFAULT '0'; 
ALTER TABLE `order` ADD `shipping_tax_rate` DECIMAL( 15, 4 ) NOT NULL DEFAULT '0';
ALTER TABLE `order` ADD `freeshipping_net` DECIMAL( 15, 4 ) NOT NULL DEFAULT '0.000';
ALTER TABLE `order` ADD `shipping_net` DECIMAL( 15, 4 ) NOT NULL DEFAULT '0.000';
ALTER TABLE `order` ADD `taxed` BOOLEAN NOT NULL DEFAULT '0';
ALTER TABLE `order` ADD `invoice_number` varchar(32) collate utf8_unicode_ci NOT NULL default '' AFTER `reference`;

#Start of Version 1.2.5 ***********

#ô Dimensions length,area and volume 

CREATE TABLE IF NOT EXISTS `dimension`(
  `dimension_id` int(11) NOT NULL auto_increment,
  `unit` varchar(24) collate utf8_unicode_ci NOT NULL default '',
  `type_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '0',
  `title` varchar(32) collate utf8_unicode_ci NOT NULL default '',
    PRIMARY KEY  (`dimension_id`,`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `dimension_type`(
  `type_id` int(11) NOT NULL auto_increment,
  `type_name` varchar(32) collate utf8_unicode_ci NOT NULL default '',
   PRIMARY KEY (`type_id`),
   KEY `type_name` (`type_name`)
)  ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `dimension_rule` (
  `dimension_rule_id` int(11) NOT NULL auto_increment,
  `type_id` int(11) NOT NULL default '0',
  `from_id` int(11) NOT NULL default '0',
  `to_id` int(11) NOT NULL default '0',
  `rule` decimal(17,6) NOT NULL default '0.0000',
   PRIMARY KEY (`dimension_rule_id`,`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

# data for table `dimension_type`
INSERT INTO `dimension_type` (`type_id`, `type_name`) VALUES
(1, 'linear'),
(2, 'area'),
(3, 'volume') ON DUPLICATE KEY UPDATE type_id=type_id;

# data for table `dimension`
INSERT INTO `dimension` (`dimension_id`, `unit`, `type_id`, `language_id`, `title`) VALUES
(1, 'in', 1, 1, 'Inch'),
(2, 'ft', 1, 1, 'Foot'),
(3, 'm', 1, 1, 'Meter'),
(4, 'L', 3, 1, 'Litre'),
(5, 'US qt', 3, 1, 'Quart US'),
(6, 'in²', 2, 1, 'Inch Squared'),
(7, 'cm²', 2, 1, 'Centimetre Squared'),
(8, 'cm', 1, 1, 'Centimetre'),
(9, 'mm', 1, 1, 'Millimetre'),
(10, 'yd', 1, 1, 'Yard'),
(11, 'ft²', 2, 1, 'Foot Squared'),
(12, 'm²', 2, 1, 'Metre Squared'),
(13, 'mm²', 2, 1, 'Millimtre Squared'),
(14, 'Imp qt', 3, 1, 'Quart Imp'),
(15, 'ml', 3, 1, 'Milliliter') ON DUPLICATE KEY UPDATE dimension_id=dimension_id;

# data for table `dimension_rule`
INSERT INTO `dimension_rule` (`dimension_rule_id`, `type_id`, `from_id`, `to_id`, `rule`) VALUES
(1, 1, 1, 8, '2.540000'),
(2, 1, 2, 8, '30.480000'),
(3, 1, 3, 8, '100.000000'),
(4, 1, 3, 2, '3.280800'),
(5, 3, 5, 14, '0.832674'),
(6, 3, 4, 5, '1.056688'),
(7, 1, 2, 3, '0.304800'),
(8, 1, 1, 3, '0.025400'),
(9, 2, 7, 6, '0.155000'),
(10, 2, 6, 7, '6.451600'),
(11, 1, 8, 3, '0.010000'),
(12, 1, 8, 2, '0.032808'),
(13, 1, 8, 1, '0.393700'),
(14, 1, 2, 1, '12.000000'),
(15, 1, 1, 2, '0.083333'),
(16, 1, 3, 1, '393701.000000'),
(17, 1, 9, 8, '0.100000'),
(18, 1, 9, 3, '0.001000'),
(19, 1, 9, 2, '0.003281'),
(20, 1, 9, 1, '0.039370'),
(21, 1, 10, 9, '914.400000'),
(22, 1, 10, 8, '91.440000'),
(23, 1, 10, 3, '0.914400'),
(24, 1, 10, 2, '3.000000'),
(25, 1, 10, 1, '36.000000'),
(26, 1, 1, 9, '25.400000'),
(27, 1, 1, 10, '0.027778'),
(28, 1, 2, 9, '308.800000'),
(29, 1, 2, 10, '0.333333'),
(30, 1, 3, 9, '1000.000000'),
(31, 1, 3, 10, '1.093613'),
(32, 1, 8, 9, '10.000000'),
(33, 1, 8, 10, '0.010936'),
(34, 1, 9, 10, '0.001094'),
(35, 2, 11, 7, '929.030400'),
(36, 2, 11, 6, '144.000000'),
(37, 2, 12, 11, '10.763900'),
(38, 2, 12, 7, '10000.000000'),
(39, 2, 12, 6, '1550.003000'),
(40, 2, 13, 6, '0.001550'),
(41, 2, 13, 7, '0.010000'),
(42, 2, 13, 11, '0.000011'),
(43, 2, 13, 12, '0.000001'),
(44, 2, 12, 13, '1000000.000000'),
(45, 2, 7, 11, '0.001076'),
(46, 2, 7, 12, '0.000100'),
(47, 2, 7, 13, '100.000000'),
(48, 2, 11, 12, '0.092903'),
(49, 2, 11, 13, '92903.040000'),
(50, 2, 6, 11, '0.006900'),
(51, 2, 6, 12, '0.000645'),
(52, 2, 6, 13, '645.160000'),
(53, 3, 14, 5, '1.200950'),
(54, 3, 14, 4, '1.136524'),
(55, 3, 5, 4, '0.946353'),
(56, 3, 15, 4, '0.001000'),
(57, 3, 15, 5, '0.001057'),
(58, 3, 15, 14, '0.000879'),
(59, 3, 4, 14, '0.879876'),
(60, 3, 4, 15, '1000.000000'),
(61, 3, 14, 15, '1136.522970'),
(62, 3, 5, 15, '946.352946') ON DUPLICATE KEY UPDATE dimension_rule_id=dimension_rule_id;


# add Dimension to table `product`
ALTER TABLE `product` ADD `dimension_id` int(11) NOT NULL DEFAULT '0' AFTER `weight_class_id`;
ALTER TABLE `product` ADD `dimension_value` varchar(64) collate utf8_unicode_ci NOT NULL DEFAULT '0:0:0' AFTER `dimension_id`;

# Settings Dimension Values
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_dimension_type_id';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_dimension_type_id', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_dimension_1_id';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_dimension_1_id', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_dimension_2_id';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_dimension_2_id', '6') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_dimension_3_id';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_dimension_3_id', '4') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_dimension_decimal';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_dimension_decimal', '2') ON DUPLICATE KEY UPDATE setting_id=setting_id;

#Add RSS feeds limit
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_rss_limit';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_rss_limit', '40') ON DUPLICATE KEY UPDATE setting_id=setting_id;

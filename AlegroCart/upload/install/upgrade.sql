#Version Setting
SET @ver='1.2.9';
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
(@id, 'bestseller', 'module', 'module', 'bestseller.php', 'module_extra_bestseller') ON DUPLICATE KEY UPDATE extension_id=extension_id;
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
ALTER TABLE `currency`
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
SELECT @id:=currency_id FROM currency WHERE `code` = 'AED';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'United Arab Emirates Dirham', 'AED', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'ANG';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Netherlands Antillean Guilder', 'ANG', '0', '0', 'ƒ', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'ARS';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Argentine Peso', 'ARS', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'BDT';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Bangladeshi Taka', 'BDT', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'BGN';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Bulgarian Lev', 'BGN', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'BHD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Bahraini Dinar', 'BHD', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'BND';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Brunei Dollar', 'BND', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'BOB';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Bolivian Boliviano', 'BOB', '0', '0', '$b', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'BRL';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Brazilian Real', 'BRL', '0', '0', 'R$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'BWP';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Botswanan Pula', 'BWP', '0', '0', 'P', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'CHF';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Swiss Franc', 'CHF', '0', '0', 'CHF', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'CLP';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Chilean Peso', 'CLP', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'CNY';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Chinese Yuan Renminbi', 'CNY', '0', '0', '¥', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'COP';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Colombian Peso', 'COP', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'CRC';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Costa Rican Colón', 'CRC', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'CZK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Czech Republic Koruna', 'CZK', '0', '0', 'Kc', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'DKK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Danish Krone', 'DKK', '0', '0', 'kr', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'DOP';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Dominican Peso', 'DOP', '0', '0', 'RD$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'DZD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Algerian Dinar', 'DZD', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'EEK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Estonian Kroon', 'EEK', '0', '0', 'kr', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'EGP';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Egyptian Pound', 'EGP', '0', '0', '£', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'FJD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Fijian Dollar', 'FJD', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'HKD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Hong Kong Dollar', 'HKD', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'HNL';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Honduran Lempira', 'HNL', '0', '0', 'L', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'HRK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Croatian Kuna', 'HRK', '0', '0', 'kn', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'HUF';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Hungarian Forint', 'HUF', '0', '0', '', 'Ft', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'IDR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Indonesian Rupiah', 'IDR', '0', '0', 'Rp', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'ILS';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Israeli New Sheqel', 'ILS', '0', '0', '?', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'INR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Indian Rupee', 'INR', '0', '0', 'Rp', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'ISK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Icelandic Króna', 'ISK', '0', '0', 'kr', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'JMD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Jamaican Dollar', 'JMD', '0', '0', 'J$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'JOD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Jordanian Dinar', 'JOD', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'JPY';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Japanese Yen', 'JPY', '0', '0', '¥', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'KES';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Kenyan Shilling', 'KES', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'KRW';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'South Korean Won', 'KRW', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'KWD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Kuwaiti Dinar', 'KWD', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'KYD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Cayman Islands Dollar', 'KYD', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'KZT';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Kazakhstan Tenge', 'KZT', '0', '0', '??', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'LBP';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Lebanese Pound', 'LBP', '0', '0', '£', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'LKR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Sri Lanka Rupee', 'LKR', '0', '0', 'Rp', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'LTL';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Lithuanian Litas', 'LTL', '0', '0', 'Lt', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'LVL';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Latvian Lats', 'LVL', '0', '0', 'Ls', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'MAD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Moroccan Dirham', 'MAD', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'MDL';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Moldovan Leu', 'MDL', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'MKD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Macedonian Denar', 'MKD', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'MUR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Mauritian Rupee', 'MUR', '0', '0', 'Rp', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'MVR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Maldivian Rufiyaa', 'MVR', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'MXN';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Mexican Peso', 'MXN', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'MYR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Malaysian Ringgit', 'MYR', '0', '0', 'RM', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'NAD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Namibian Dollar', 'NAD', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'NGN';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Nigerian Naira', 'NGN', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'NIO';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Nicaraguan Cordoba Oro', 'NIO', '0', '0', 'C$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'NOK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Norwegian Krone', 'NOK', '0', '0', 'kr', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'NPR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Nepalese Rupee', 'NPR', '0', '0', 'Rp', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'NZD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'New Zealand Dollar', 'NZD', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'OMR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Omani Rial', 'OMR', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'PEN';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Peruvian Nuevo Sol', 'PEN', '0', '0', 'S/.', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'PGK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Papua New Guinean Kina', 'PGK', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'PHP';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Philippine Peso', 'PHP', '0', '0', 'Php', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'PKR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Pakistani Rupee', 'PKR', '0', '0', 'Rp', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'PLN';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Polish Zloty', 'PLN', '0', '0', 'zl', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'PYG';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Paraguayan Guarani', 'PYG', '0', '0', 'Gs', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'QAR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Qatari Rial', 'QAR', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'RON';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Romanian Leu', 'RON', '0', '0', 'lei', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'RSD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Serbian Dinar', 'RSD', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'RUB';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Russian Ruble', 'RUB', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'SAR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Saudi Riyal', 'SAR', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'SCR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Seychellois Rupee', 'SCR', '0', '0', 'Rp', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'SEK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Swedish Krona', 'SEK', '0', '0', 'kr', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'SGD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Singapore Dollar', 'SGD', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'SKK';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Slovak Koruna', 'SKK', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'SLL';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Sierra Leonean Leone', 'SLL', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'SVC';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Salvadoran Colón', 'SVC', '0', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'THB';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Thai Baht', 'THB', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'TND';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Tunisian Dinar', 'TND', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'TRY';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Turkish Lira', 'TRY', '0', '0', '£', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'TTD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Trinidad and Tobago Dollar', 'TTD', '0', '0', 'TT$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'TWD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'New Taiwan Dollar', 'TWD', '0', '0', 'NT$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'TZS';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Tanzanian Shilling', 'TZS', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'UAH';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Ukrainian Hryvnia', 'UAH', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'UGX';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Ugandan Shilling', 'UGX', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'UYU';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Uruguayan Peso', 'UYU', '0', '0', '$U', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'UZS';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Uzbekistan Som', 'UZS', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'VEF';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Venezuelan Bolr Fuerte', 'VEF', '0', '0', 'Bs', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'VND';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Vietnamese Dong', 'VND', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'XOF';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'CFA Franc BCEAO', 'XOF', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'YER';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Yemeni Rial', 'YER', '0', '0', '', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'ZAR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'South African Rand', 'ZAR', '0', '0', 'R', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'ZMK';
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

#Add Product With Options table
CREATE TABLE IF NOT EXISTS `product_options` (
  `product_id` int(11) NOT NULL auto_increment,
  `product_option`  varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `quantity` int(4) NOT NULL default '0',
  `image_id` int(11) NOT NULL default '0',
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension_value` varchar(64) collate utf8_unicode_ci NOT NULL DEFAULT '0:0:0',
  `model_number` varchar( 32 ) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`product_id`, `product_option`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

# Add selectable Logo
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_store_logo';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'config_store_logo', 'aclogo.png') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_logo_left';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'config_logo_left', '350') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_logo_top';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'config_logo_top', '5') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_logo_width';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'config_logo_width', '300') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_logo_height';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'config_logo_height', '67') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Add Magnifier
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'magnifier';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'magnifier', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'magnifier_width';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'magnifier_width', '125') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'magnifier_height';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'magnifier_height', '125') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# error Handler
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'error_developer_ip';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'error_developer_ip', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'error_show_user';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'error_show_user', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'error_show_developer';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'error_show_developer', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_error_email';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_error_email', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'error_handler_status';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'error_handler_status', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_rss_status';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_rss_status', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Warehouse pickup
SET @lid=1;
SELECT @lid:=language_id FROM language WHERE `code` = 'en';

# Insert without duplicate
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'shipping_warehouse';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'warehouse', 'shipping', 'shipping', 'warehouse.php', 'shipping_warehouse') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'shipping_warehouse';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Warehouse Pickup', 'Warehouse Pickup') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#Start of Version 1.2.6 ***********

# Add 2 currencies if not exist
SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'AUD';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Australian Dollar', 'AUD', '1', '0', '$', '', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

SET @id=NULL;
SELECT @id:=currency_id FROM currency WHERE `code` = 'EUR';
INSERT INTO `currency` (`currency_id`,  `title`, `code`, `status`, `lock_rate`,`symbol_left`, `symbol_right`, `decimal_place`, `value`, `date_modified`) VALUES (@id, 'Euro', 'EUR', '1', '0', '', '€', '2', '1.00000000', '2008-12-17 20:46:47') ON DUPLICATE KEY UPDATE currency_id=currency_id;

# RSS Source
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_rss_source';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_rss_source', 'rss_latest') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Footer Logo
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_footer_logo';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'config_footer_logo', 'paypal-visa-mastercard.png') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'footer_logo_left';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'footer_logo_left', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'footer_logo_top';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'footer_logo_top', '15') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'footer_logo_width';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'footer_logo_width', '170') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'footer_logo_height';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'footer_logo_height', '30') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Add Guest Checkout
ALTER TABLE `customer`
ADD `guest` int(1) NOT NULL default '0' After `status`;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_guest_checkout';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'config_guest_checkout', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Add Email status to error handler
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'error_email_status';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'error_email_status', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Add Date Time Zone setting
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_time_zone';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_time_zone', 'UTC') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Extension ccAvenue
SET @lid=1;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_ccavenue';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'ccavenue', 'payment', 'payment', 'ccavenue.php', 'payment_ccavenue') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_ccavenue';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'ccAvenue', 'ccAvenue Gateway') ON DUPLICATE KEY UPDATE extension_id=extension_id;

# Increase Weight field length
ALTER TABLE `product` CHANGE `weight` `weight` decimal(15,4) NOT NULL default '0.00';

#Extend review rating
ALTER TABLE `review` CHANGE `rating` `rating1` INT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE `review` ADD `rating2` INT( 1 ) NOT NULL DEFAULT '1' AFTER `rating1` ,
ADD `rating3` INT( 1 ) NOT NULL DEFAULT '1' AFTER `rating2` ,
ADD `rating4` INT( 1 ) NOT NULL DEFAULT '1' AFTER `rating3` ;

# Extension Australian Post
SET @lid=1;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'shipping_australiapost';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'australiapost', 'shipping', 'shipping', 'australiapost.php', 'shipping_australiapost') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'shipping_australiapost';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Australia Post', 'Australia Post') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#Zone Plus Shipping
SET @lid=1;
SELECT @lid:=language_id FROM language WHERE `code` = 'en';

SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'shipping_zoneplus';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'zoneplus', 'shipping', 'shipping', 'zoneplus.php', 'shipping_zoneplus') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'shipping_zoneplus';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Zone Plus', 'Zone Plus Shipping') ON DUPLICATE KEY UPDATE extension_id=extension_id;

# Add Captcha
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'captcha_contactus';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'captcha_contactus', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'captcha_reg';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'captcha_reg', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'captcha_length';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'captcha_length', '5') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'show_stock';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'show_stock', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Extension Canada Post
SET @lid=1;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'shipping_canadapost';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'canadapost', 'shipping', 'shipping', 'canadapost.php', 'shipping_canadapost') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'shipping_canadapost';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Canada Post', 'Canada Post Shipping') ON DUPLICATE KEY UPDATE extension_id=extension_id;

# Increase Value field length in Setting
ALTER TABLE `setting` CHANGE `value` `value` VARCHAR(768) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL default '';

# Add Barcode
ALTER TABLE `product` ADD `barcode` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' AFTER `quantity` ;
ALTER TABLE `product_options` ADD `barcode` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' AFTER `quantity` ;
ALTER TABLE `order_product` ADD `barcode` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' AFTER `quantity` ;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'barcode_encoding';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'barcode_encoding', 'upc') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Add Option Weight Class

ALTER TABLE `product_to_option` CHANGE `option_weight` `option_weight` decimal(15,4) NOT NULL default '0.00';
ALTER TABLE `product_to_option`
ADD `option_weightclass_id` int(11) NOT NULL default '0' AFTER `option_weight`;

SET @wid=1;
SELECT @wid:=`value` FROM setting WHERE `group` = 'config' and `key` = 'config_weight_class_id';
UPDATE `product_to_option` SET `option_weightclass_id` = @wid WHERE `option_weightclass_id` = '0';

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_weight_decimal';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_weight_decimal', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

#Add Technical Tab Name

ALTER TABLE `product_description`
ADD `technical_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL default '' AFTER `technical`;

#Create Watermark Table

CREATE TABLE IF NOT EXISTS `watermark`(
  `wm_id` int(11) NOT NULL auto_increment,
  `wm_method` varchar(6) collate utf8_unicode_ci NOT NULL default '',
  `wm_text` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `wm_font` int(11) NOT NULL default '0',
  `wm_fontcolor` varchar(6) collate utf8_unicode_ci NOT NULL default '',
  `wm_transparency` int(11) NOT NULL default '0',
  `wm_thposition` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `wm_tvposition` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `wm_thmargin` int(11) NOT NULL default '0',
  `wm_tvmargin` int(11) NOT NULL default '0',
  `wm_image` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `wm_ihposition` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `wm_ivposition` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `wm_ihmargin` int(11) NOT NULL default '0',
  `wm_ivmargin` int(11) NOT NULL default '0',
  `wm_scale` int(11) NOT NULL default '0',
  PRIMARY KEY  (`wm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

# data for table `watermark`
INSERT INTO `watermark` (`wm_id`, `wm_method`, `wm_text`, `wm_font`, `wm_fontcolor`, `wm_transparency`, `wm_thposition`, `wm_tvposition`, `wm_thmargin`, `wm_tvmargin`, `wm_image`, `wm_ihposition`, `wm_ivposition`, `wm_ihmargin`, `wm_ivmargin`, `wm_scale`) VALUES
(NULL, 'auto', '', 5, 'CCCCCC', 80, 'CENTER', 'TOP', 10, 15, '0', 'RIGHT', 'BOTTOM', 12, 21, 50),
(NULL, 'manual', 'Alegrocart TEST manual watermark', 4, '000000', 70, 'CENTER', 'TOP', 15, 25, 'aclogo.png', 'RIGHT', 'BOTTOM', 15, 25, 60)
 ON DUPLICATE KEY UPDATE wm_id=wm_id;

#Add Show Remaining Days
ALTER TABLE `product` ADD `remaining` int(1) NOT NULL default '1' After `sale_start_date`;

#Start of Version 1.2.7 ***********

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_discount_options';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'config_discount_options', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_show_stock_icon';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'config_show_stock_icon', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_low_stock_warning';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'config_low_stock_warning', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_session_expire';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_session_expire', '3600') ON DUPLICATE KEY UPDATE setting_id=setting_id;

#Add free download
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_freedownload';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'config_freedownload', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;

ALTER TABLE `product_to_download` ADD `free` INT( 1 ) NOT NULL DEFAULT '0';

# ADD Modified to orders
ALTER TABLE `order` ADD `modified` INT(1) NOT NULL DEFAULT '0' AFTER `reference`;
ALTER TABLE `order` ADD `new_reference` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' AFTER `modified`;
ALTER TABLE `order_product` ADD `product_id` int(11) NOT NULL DEFAULT '0' AFTER `name`;

UPDATE `order_product` INNER JOIN `product_description` ON order_product.name = product_description.name and order_product.product_id = '0' set order_product.product_id = product_description.product_id;

# Extension Bank Transfer
# Get language id for english
SET @lid=1;
SELECT @lid:=language_id FROM language WHERE `code` = 'en';

SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_banktr';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'banktr', 'payment', 'payment', 'banktr.php', 'payment_banktr') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_banktr';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Bank Transfer', 'Offline Bank Transfer') ON DUPLICATE KEY UPDATE extension_id=extension_id;

# Add Sitemap
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'sitemap_status';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'sitemap_status', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;

#Manufacturer Model filter settings
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'options_manufacturer';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'options_manufacturer', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'options_model';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'options_model', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Version 1.2.8

# Add hide Category
ALTER TABLE `category` ADD `category_hide` int(1) NOT NULL default '0' After `sort_order`;

# Add hide Information
ALTER TABLE `information` ADD `information_hide` int(1) NOT NULL default '0' After `sort_order`;

#
# TABLE STRUCTURE FOR: `vendor`
#

CREATE TABLE IF NOT EXISTS `vendor` (
  `vendor_id` int(11) NOT NULL auto_increment,
  `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `image_id` int(11) NOT NULL default '0',
  `description` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `discount` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `status` int(1) NOT NULL,
  `email` varchar(96) collate utf8_unicode_ci NOT NULL default '',
  `telephone` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `fax` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `website` varchar(96) collate utf8_unicode_ci NOT NULL default '',
  `trade` varchar(96) collate utf8_unicode_ci NOT NULL default '',
  `address_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`vendor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
#Modify `product` table
#
ALTER TABLE `product`
ADD `vendor_id` int(11) NOT NULL default '0' AFTER `manufacturer_id`;

#
#Modify `order_product` table
#
ALTER TABLE `order_product`
ADD `vendor_id` int(11) NOT NULL default '0' AFTER `model_number`;
ALTER TABLE `order_product`
ADD `vendor_name` varchar(64) collate utf8_unicode_ci NOT NULL default '' AFTER `model_number`;

#
#Modify `address` table
#
ALTER TABLE `address`
ADD `vendor_id` int(11) NOT NULL default '0' AFTER `customer_id`;
ALTER TABLE `address`
ADD KEY `vendor_id` (`vendor_id`);

#
#Add SMTP support
#

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_email_port';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_email_port', '25') ON DUPLICATE KEY UPDATE setting_id=setting_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_email_tout';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_email_tout', '10') ON DUPLICATE KEY UPDATE setting_id=setting_id;

#
# Add Image display to review
#
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'review_image_display';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'review', 'review_image_display', 'thickbox') ON DUPLICATE KEY UPDATE setting_id=setting_id;

#
#Add Maximum Order Quantity and Multiple
#
ALTER TABLE `product` ADD `max_qty` int(4) NOT NULL default '0' After `min_qty`;
ALTER TABLE `product` ADD `multiple` int(4) NOT NULL default '0' After `max_qty`;

#
#Modify `language` table
#
ALTER TABLE `language`
ADD `language_status` int(1) NOT NULL default '1' AFTER `language_id`;

#
#Add Shipping Time
#
ALTER TABLE `product` ADD `shipping_time_to` int(2) NOT NULL default '4' After `shipping`;
ALTER TABLE `product` ADD `shipping_time_from` int(2) NOT NULL default '2' After `shipping`;

#
#Add Recently Viewed Module
#
SET @lid=1;
SET @id=NULL;
SELECT @id:=extension_id FROM `extension` WHERE `controller` = 'module_extra_recently';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES
(@id, 'recently', 'module', 'module', 'recently.php', 'module_extra_recently') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM `extension` WHERE `controller` = 'module_extra_recently';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES
(@id, @lid, 'Catalog Recently Viewed', 'Display Recently Viewed Products') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#
#Add Top Rated Module
#
SET @lid=1;
SET @id=NULL;
SELECT @id:=extension_id FROM `extension` WHERE `controller` = 'module_extra_toprated';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES
(@id, 'toprated', 'module', 'module', 'toprated.php', 'module_extra_toprated') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM `extension` WHERE `controller` = 'module_extra_toprated';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES
(@id, @lid, 'Catalog Top Rated', 'Display Top Rated Products') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#
#Add Also Bought Module
#
SET @lid=1;
SET @id=NULL;
SELECT @id:=extension_id FROM `extension` WHERE `controller` = 'module_extra_alsobought';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES
(@id, 'alsobought', 'module', 'module', 'alsobought.php', 'module_extra_alsobought') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM `extension` WHERE `controller` = 'module_extra_alsobought';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES
(@id, @lid, 'Catalog Also Bought', 'Display Also Bought Products') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#
# Modif Australia Post
#
DELETE FROM `setting` WHERE `group` = 'australiapost' and `key` = 'australiapost_default_method';

#
# Add Extension Category Menu
#
SET @lid=1;
SET @id=NULL;
SELECT @id:=extension_id FROM `extension` WHERE `controller` = 'module_catalog_categorymenu';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'categorymenu', 'module', 'module', 'categorymenu.php', 'module_catalog_categorymenu') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM `extension` WHERE `controller` = 'module_catalog_categorymenu';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Catalog Category Menu', 'Category Menu Module') ON DUPLICATE KEY UPDATE extension_id=extension_id;

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'cart' and `key` = 'cart_offset';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'cart', 'cart_offset', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;

#
# Modify tpl_manager
#
ALTER TABLE `tpl_manager` CHANGE `tpl_columns` `tpl_columns` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0';

# Add Image Quality
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_image_quality';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_image_quality', '75') ON DUPLICATE KEY UPDATE setting_id=setting_id;

#
# Manufacturer List Module
#
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'module_extra_manufacturerlist';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES
(@id, 'manufacturerlist', 'module', 'module', 'manufacturerlist.php', 'module_extra_manufacturerlist') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SET @lid=1;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'module_extra_manufacturerlist';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES
(@id, @lid, 'Catalog Manufacturer List', 'Display List of Manufacturers') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#
# Category List Module
#
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'module_extra_categorylist';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES
(@id, 'categorylist', 'module', 'module', 'categorylist.php', 'module_extra_categorylist') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SET @lid=1;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'module_extra_categorylist';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES
(@id, @lid, 'Catalog Category List', 'Display List of Categories') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#
# Manufacturer Slider Module
#
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'module_extra_manufacturerslider';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES
(@id, 'manufacturerslider', 'module', 'module', 'manufacturerslider.php', 'module_extra_manufacturerslider') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SET @lid=1;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'module_extra_manufacturerslider';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES
(@id, @lid, 'Catalog Manufacturer Slider', 'Manufacturer Image Slider') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#
# Category Slider Module
#
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'module_extra_categoryslider';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES
(@id, 'categoryslider', 'module', 'module', 'categoryslider.php', 'module_extra_categoryslider') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SET @lid=1;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'module_extra_categoryslider';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES
(@id, @lid, 'Catalog Category Slider', 'Category Image Slider') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#
# Group Newsletter
#
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_newsletter';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_newsletter', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;

#
# Create table home_slides
#
CREATE TABLE IF NOT EXISTS `home_slides` (
  `home_slide_id` int(11) NOT NULL auto_increment,
  `home_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '1',
  `image_id` int(11) default NULL,
  `sort_order` int(3) default '0',
  PRIMARY KEY (`home_slide_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Create table image_display_slides
#
CREATE TABLE IF NOT EXISTS `image_display_slides` (
  `image_display_slide_id` int(11) NOT NULL auto_increment,
  `image_display_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '1',
  `image_id` int(11) default NULL,
  `sort_order` int(3) default '0',
  PRIMARY KEY (`image_display_slide_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#Start of Version 1.3 ***********

#
# Modify data in order_data table (from text to mediumtext)
#
ALTER TABLE `order_data` CHANGE `data` `data`  MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

#
#Add Condense Admin
#

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_admin_page_load';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'admin', 'config', 'config_admin_page_load', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

#
#Modify `user` table
#
ALTER TABLE `user`
ADD `telephone` varchar(32) collate utf8_unicode_ci NOT NULL default '',
ADD `mobile` varchar(32) collate utf8_unicode_ci NOT NULL default '',
ADD `fax` varchar(32) collate utf8_unicode_ci NOT NULL default '',
ADD `monogram` varchar(4) collate utf8_unicode_ci NOT NULL default '',
ADD `position` varchar(32) collate utf8_unicode_ci NOT NULL default '',
ADD `signature` varchar(64) collate utf8_unicode_ci NOT NULL default '';

#
# Stamp
#
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_stamp';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_stamp', 'example_stamp.png') ON DUPLICATE KEY UPDATE setting_id=setting_id;


#
# Delete from extension Bank Transfer
#
DELETE from `setting` WHERE `key` = 'banktr_swift';
DELETE from `setting` WHERE `key` = 'banktr_ban';
DELETE from `setting` WHERE `key` = 'banktr_iban';
DELETE from `setting` WHERE `key` = 'banktr_owner';
DELETE from `setting` WHERE `key` = 'banktr_bank_name';

#
# Add Bank Account
#
CREATE TABLE IF NOT EXISTS `bank_account` (
 `bank_account_id` int(11) NOT NULL auto_increment,
 `bank_name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
 `bank_address` varchar(64) collate utf8_unicode_ci NOT NULL default '',
 `owner` varchar(32) collate utf8_unicode_ci NOT NULL default '',
 `ban` varchar(64) collate utf8_unicode_ci NOT NULL default '',
 `iban` varchar(64) collate utf8_unicode_ci NOT NULL default '',
 `swift` varchar(16) collate utf8_unicode_ci NOT NULL default '',
 `charge` varchar(3) collate utf8_unicode_ci NOT NULL default '',
 `currency` varchar(3) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`bank_account_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Add Description to Maintenance
#
CREATE TABLE IF NOT EXISTS `maintenance_description` (
  `maintenance_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '1',
  `header` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `description` text collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`maintenance_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `maintenance_description` (`maintenance_id`, `language_id`, `header`, `description`) VALUES ('1', '1', 'Maintenance', '<p>This site is unavailable due to scheduled maintenance.</p><p>Check back soon!</p>');

#
# Add Bought Products module
#
SET @lid=1;
SET @id=NULL;
SELECT @id:=extension_id FROM `extension` WHERE `controller` = 'module_extra_bought';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES
(@id, 'bought', 'module', 'module', 'bought.php', 'module_extra_bought') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM `extension` WHERE `controller` = 'module_extra_bought';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES
(@id, @lid, 'Catalog Bought Products', 'Display Bought Products') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#
# Add Bought Products Options module
#
SET @lid=1;
SET @id=NULL;
SELECT @id:=extension_id FROM `extension` WHERE `controller` = 'module_extra_boughtoptions';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES
(@id, 'boughtoptions', 'module', 'module', 'boughtoptions.php', 'module_extra_boughtoptions') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM `extension` WHERE `controller` = 'module_extra_boughtoptions';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES
(@id, @lid, 'Catalog Bought Products Options', 'Display Bought Products Options') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#
# Add new columns to order_option table
#
ALTER TABLE `order_option`
ADD `option_id` int(11) NOT NULL DEFAULT '0' After `order_product_id`,
ADD `option_value_id` int(11) NOT NULL DEFAULT '0' After `name`;

#
# Update order_option table, i.e. import option_ids and option_value_ids from option and option_value tables
#
UPDATE `order_option` JOIN `option` ON `order_option`.`name` = `option`.`name` SET `order_option`.`option_id` = `option`.`option_id`;
UPDATE `order_option` JOIN `option_value` ON `order_option`.`value` = `option_value`.`name` SET `order_option`.`option_value_id` = `option_value`.`option_value_id`;

#
# Log every change in order_data table
#
CREATE TABLE IF NOT EXISTS `order_data_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `reference` varchar(32) collate utf8_unicode_ci,
  `data` text collate utf8_unicode_ci,
  `expire` int(10) DEFAULT '0',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `order_data_insertTrigger`;
CREATE TRIGGER `order_data_insertTrigger` AFTER INSERT ON `order_data` FOR EACH ROW INSERT delayed INTO order_data_log (trigger_action, trigger_modifier_id, trigger_modifier_title, reference, data, expire) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.reference, NEW.data, NEW.expire);

DROP TRIGGER IF EXISTS `order_data_updateTrigger`;
CREATE TRIGGER `order_data_updateTrigger` AFTER UPDATE ON `order_data` FOR EACH ROW INSERT delayed INTO order_data_log (trigger_action, trigger_modifier_id, trigger_modifier_title, reference, data, expire) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.reference, NEW.data, NEW.expire);

DROP TRIGGER IF EXISTS `order_data_deleteTrigger`;
CREATE TRIGGER `order_data_deleteTrigger` BEFORE DELETE ON `order_data` FOR EACH ROW INSERT delayed INTO order_data_log (trigger_action, trigger_modifier_id, trigger_modifier_title, reference, data, expire) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.reference, OLD.data, OLD.expire);

#
# Add date_modified to customer table
#
ALTER TABLE `customer` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in customer table
#
CREATE TABLE IF NOT EXISTS `customer_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `customer_id` int(11) DEFAULT '0',
  `firstname` varchar(32) collate utf8_unicode_ci default '',
  `lastname` varchar(32) collate utf8_unicode_ci default '',
  `email` varchar(96) collate utf8_unicode_ci default '',
  `telephone` varchar(32) collate utf8_unicode_ci default '',
  `fax` varchar(32) collate utf8_unicode_ci default '',
  `password` varchar(40) collate utf8_unicode_ci default '',
  `newsletter` int(1) default '0',
  `address_id` int(11) default '0',
  `cart` text collate utf8_unicode_ci default NULL,
  `status` int(1) default '0',
  `guest` int(1) default '0',
  `ip` varchar(39) collate utf8_unicode_ci default NULL,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `customer_insertTrigger`;
CREATE TRIGGER `customer_insertTrigger` AFTER INSERT ON `customer` FOR EACH ROW INSERT delayed INTO customer_log (trigger_action, trigger_modifier_id, trigger_modifier_title, customer_id, firstname, lastname, email, telephone, fax, password, newsletter, address_id, cart, status, guest, ip, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.customer_id, NEW.firstname, NEW.lastname, NEW.email, NEW.telephone, NEW.fax, NEW.password, NEW.newsletter, NEW.address_id, NEW.cart, NEW.status, NEW.guest, NEW.ip, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `customer_updateTrigger`;
CREATE TRIGGER `customer_updateTrigger` AFTER UPDATE ON `customer` FOR EACH ROW INSERT delayed INTO customer_log (trigger_action, trigger_modifier_id, trigger_modifier_title, customer_id, firstname, lastname, email, telephone, fax, password, newsletter, address_id, cart, status, guest, ip, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.customer_id, NEW.firstname, NEW.lastname, NEW.email, NEW.telephone, NEW.fax, NEW.password, NEW.newsletter, NEW.address_id, NEW.cart, NEW.status, NEW.guest, NEW.ip, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `customer_deleteTrigger`;
CREATE TRIGGER `customer_deleteTrigger` BEFORE DELETE ON `customer` FOR EACH ROW INSERT delayed INTO customer_log (trigger_action, trigger_modifier_id, trigger_modifier_title, customer_id, firstname, lastname, email, telephone, fax, password, newsletter, address_id, cart, status, guest, ip, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.customer_id, OLD.firstname, OLD.lastname, OLD.email, OLD.telephone, OLD.fax, OLD.password, OLD.newsletter, OLD.address_id, OLD.cart, OLD.status, OLD.guest, OLD.ip, OLD.date_added, OLD.date_modified);

#
# Move vendor related addresses into a separate table 
#
CREATE TABLE `vendor_address` LIKE `address`; 
INSERT `vendor_address` SELECT * FROM `address`;
DELETE FROM `vendor_address` WHERE `vendor_id` = "0";
ALTER TABLE `vendor_address` DROP COLUMN `customer_id`;
DELETE FROM `address` WHERE `address_id` = "0";
ALTER TABLE `address` DROP COLUMN `vendor_id`;

#
# Add date_added and date_modified to address table
#
ALTER TABLE `address` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `address` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in address table
#
CREATE TABLE IF NOT EXISTS `address_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `address_id` int(11) DEFAULT '0',
  `customer_id` int(11) default '0',
  `company` varchar(32) collate utf8_unicode_ci default NULL,
  `firstname` varchar(32) collate utf8_unicode_ci default '',
  `lastname` varchar(32) collate utf8_unicode_ci default '',
  `address_1` varchar(64) collate utf8_unicode_ci default '',
  `address_2` varchar(64) collate utf8_unicode_ci default NULL,
  `postcode` varchar(10) collate utf8_unicode_ci default '',
  `city` varchar(32) collate utf8_unicode_ci default '',
  `country_id` int(11) default '0',
  `zone_id` int(11) default '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `address_insertTrigger`;
CREATE TRIGGER `address_insertTrigger` AFTER INSERT ON `address` FOR EACH ROW INSERT delayed INTO address_log (trigger_action, trigger_modifier_id, trigger_modifier_title, address_id, customer_id, company, firstname, lastname, address_1, address_2, postcode, city, country_id, zone_id, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.address_id, NEW.customer_id, NEW.company, NEW.firstname, NEW.lastname, NEW.address_1, NEW.address_2, NEW.postcode, NEW.city, NEW.country_id, NEW.zone_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `address_updateTrigger`;
CREATE TRIGGER `address_updateTrigger` AFTER UPDATE ON `address` FOR EACH ROW INSERT delayed INTO address_log (trigger_action, trigger_modifier_id, trigger_modifier_title, address_id, customer_id, company, firstname, lastname, address_1, address_2, postcode, city, country_id, zone_id, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.address_id, NEW.customer_id, NEW.company, NEW.firstname, NEW.lastname, NEW.address_1, NEW.address_2, NEW.postcode, NEW.city, NEW.country_id, NEW.zone_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `address_deleteTrigger`;
CREATE TRIGGER `address_deleteTrigger` BEFORE DELETE ON `address` FOR EACH ROW INSERT delayed INTO address_log (trigger_action, trigger_modifier_id, trigger_modifier_title, address_id, customer_id, company, firstname, lastname, address_1, address_2, postcode, city, country_id, zone_id, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.address_id, OLD.customer_id, OLD.company, OLD.firstname, OLD.lastname, OLD.address_1, OLD.address_2, OLD.postcode, OLD.city, OLD.country_id, OLD.zone_id, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to bank_account table
#
ALTER TABLE `bank_account` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `bank_account` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in bank_account table
#
CREATE TABLE IF NOT EXISTS `bank_account_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `bank_account_id` int(11) DEFAULT '0',
  `bank_name` varchar(64) collate utf8_unicode_ci default '',
  `bank_address` varchar(64) collate utf8_unicode_ci default '',
  `owner` varchar(32) collate utf8_unicode_ci default '',
  `ban` varchar(64) collate utf8_unicode_ci default '',
  `iban` varchar(64) collate utf8_unicode_ci default '',
  `swift` varchar(16) collate utf8_unicode_ci default '',
  `charge` varchar(3) collate utf8_unicode_ci default '',
  `currency` varchar(3) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `bank_account_insertTrigger`;
CREATE TRIGGER `bank_account_insertTrigger` AFTER INSERT ON `bank_account` FOR EACH ROW INSERT delayed INTO bank_account_log (trigger_action, trigger_modifier_id, trigger_modifier_title, bank_account_id, bank_name, bank_address, owner, ban, iban, swift, charge, currency, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.bank_account_id, NEW.bank_name, NEW.bank_address, NEW.owner, NEW.ban, NEW.iban, NEW.swift, NEW.charge, NEW.currency, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `bank_account_updateTrigger`;
CREATE TRIGGER `bank_account_updateTrigger` AFTER UPDATE ON `bank_account` FOR EACH ROW INSERT delayed INTO bank_account_log (trigger_action, trigger_modifier_id, trigger_modifier_title, bank_account_id, bank_name, bank_address, owner, ban, iban, swift, charge, currency, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.bank_account_id, NEW.bank_name, NEW.bank_address, NEW.owner, NEW.ban, NEW.iban, NEW.swift, NEW.charge, NEW.currency, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `bank_account_deleteTrigger`;
CREATE TRIGGER `bank_account_deleteTrigger` BEFORE DELETE ON `bank_account` FOR EACH ROW INSERT delayed INTO bank_account_log (trigger_action, trigger_modifier_id, trigger_modifier_title, bank_account_id, bank_name, bank_address, owner, ban, iban, swift, charge, currency, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.bank_account_id, OLD.bank_name, OLD.bank_address, OLD.owner, OLD.ban, OLD.iban, OLD.swift, OLD.charge, OLD.currency, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to option value table
#
ALTER TABLE `option_value` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `option_value` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in option value table
#
CREATE TABLE IF NOT EXISTS `option_value_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `option_value_id` int(11) DEFAULT '0',
  `language_id` int(11) DEFAULT '0',
  `option_id` int(11) DEFAULT '0',
  `name` varchar(64) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `option_value_insertTrigger`;
CREATE TRIGGER `option_value_insertTrigger` AFTER INSERT ON `option_value` FOR EACH ROW INSERT delayed INTO option_value_log (trigger_action, trigger_modifier_id, trigger_modifier_title, option_value_id, language_id, option_id, name, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.option_value_id, NEW.language_id, NEW.option_id, NEW.name, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `option_value_updateTrigger`;
CREATE TRIGGER `option_value_updateTrigger` AFTER UPDATE ON `option_value` FOR EACH ROW INSERT delayed INTO option_value_log (trigger_action, trigger_modifier_id, trigger_modifier_title, option_value_id, language_id, option_id, name, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.option_value_id, NEW.language_id, NEW.option_id, NEW.name, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `option_value_deleteTrigger`;
CREATE TRIGGER `option_value_deleteTrigger` BEFORE DELETE ON `option_value` FOR EACH ROW INSERT delayed INTO option_value_log (trigger_action, trigger_modifier_id, trigger_modifier_title, option_value_id, language_id, option_id, name, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.option_value_id, OLD.language_id, OLD.option_id, OLD.name, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to information table
#
ALTER TABLE `information` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `information` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in information table
#
CREATE TABLE IF NOT EXISTS `information_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `information_id` int(11) DEFAULT '0',
  `sort_order` int(3) DEFAULT '0',
  `information_hide` int(1) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `information_insertTrigger`;
CREATE TRIGGER `information_insertTrigger` AFTER INSERT ON `information` FOR EACH ROW INSERT delayed INTO information_log (trigger_action, trigger_modifier_id, trigger_modifier_title, information_id, sort_order, information_hide, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.information_id, NEW.sort_order, NEW.information_hide, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `information_updateTrigger`;
CREATE TRIGGER `information_updateTrigger` AFTER UPDATE ON `information` FOR EACH ROW INSERT delayed INTO information_log (trigger_action, trigger_modifier_id, trigger_modifier_title, information_id, sort_order, information_hide, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.information_id, NEW.sort_order, NEW.information_hide, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `information_deleteTrigger`;
CREATE TRIGGER `information_deleteTrigger` BEFORE DELETE ON `information` FOR EACH ROW INSERT delayed INTO information_log (trigger_action, trigger_modifier_id, trigger_modifier_title, information_id, sort_order, information_hide, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.information_id, OLD.sort_order, OLD.information_hide, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to information description table
#
ALTER TABLE `information_description` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `information_description` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in information description table
#
CREATE TABLE IF NOT EXISTS `information_description_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `information_id` int(11) DEFAULT '0',
  `language_id` int(11) DEFAULT '0',
  `title` varchar(64) collate utf8_unicode_ci default '',
  `description` text collate utf8_unicode_ci,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `information_description_insertTrigger`;
CREATE TRIGGER `information_description_insertTrigger` AFTER INSERT ON `information_description` FOR EACH ROW INSERT delayed INTO information_description_log (trigger_action, trigger_modifier_id, trigger_modifier_title, information_id, language_id, title, description, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.information_id, NEW.language_id, NEW.title, NEW.description, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `information_description_updateTrigger`;
CREATE TRIGGER `information_description_updateTrigger` AFTER UPDATE ON `information_description` FOR EACH ROW INSERT delayed INTO information_description_log (trigger_action, trigger_modifier_id, trigger_modifier_title, information_id, language_id, title, description, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.information_id, NEW.language_id, NEW.title, NEW.description, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `information_description_deleteTrigger`;
CREATE TRIGGER `information_description_deleteTrigger` BEFORE DELETE ON `information_description` FOR EACH ROW INSERT delayed INTO information_description_log (trigger_action, trigger_modifier_id, trigger_modifier_title, information_id, language_id, title, description, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.information_id, OLD.language_id, OLD.title, OLD.description, OLD.date_added, OLD.date_modified);

#
# Alter category table
#
ALTER TABLE `category` CHANGE `date_added` `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `category` CHANGE `date_modified` `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

#
# Log every change in category table
#
CREATE TABLE IF NOT EXISTS `category_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `category_id` int(11) DEFAULT '0',
  `image_id` int(11) DEFAULT '0',
  `parent_id` int(11) DEFAULT '0',
  `path` varchar(64) collate utf8_unicode_ci default '',
  `sort_order` int(3) DEFAULT '0',
  `category_hide` int(1) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `category_insertTrigger`;
CREATE TRIGGER `category_insertTrigger` AFTER INSERT ON `category` FOR EACH ROW INSERT delayed INTO category_log (trigger_action, trigger_modifier_id, trigger_modifier_title, category_id, image_id, parent_id, path, sort_order, category_hide, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.category_id, NEW.image_id, NEW.parent_id, NEW.path, NEW.sort_order, NEW.category_hide, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `category_updateTrigger`;
CREATE TRIGGER `category_updateTrigger` AFTER UPDATE ON `category` FOR EACH ROW INSERT delayed INTO category_log (trigger_action, trigger_modifier_id, trigger_modifier_title, category_id, image_id, parent_id, path, sort_order, category_hide, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.category_id, NEW.image_id, NEW.parent_id, NEW.path, NEW.sort_order, NEW.category_hide, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `category_deleteTrigger`;
CREATE TRIGGER `category_deleteTrigger` BEFORE DELETE ON `category` FOR EACH ROW INSERT delayed INTO category_log (trigger_action, trigger_modifier_id, trigger_modifier_title, category_id, image_id, parent_id, path, sort_order, category_hide, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.category_id, OLD.image_id, OLD.parent_id, OLD.path, OLD.sort_order, OLD.category_hide, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to category description table
#
ALTER TABLE `category_description` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `category_description` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in category description table
#
CREATE TABLE IF NOT EXISTS `category_description_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `category_id` int(11) DEFAULT '0',
  `language_id` int(11) DEFAULT '0',
  `name` varchar(32) collate utf8_unicode_ci default '',
  `description` text collate utf8_unicode_ci,
  `meta_keywords` varchar(255) collate utf8_unicode_ci default NULL,
  `meta_description` varchar(255) collate utf8_unicode_ci default NULL,
  `meta_title` varchar(255) collate utf8_unicode_ci default NULL,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `category_description_insertTrigger`;
CREATE TRIGGER `category_description_insertTrigger` AFTER INSERT ON `category_description` FOR EACH ROW INSERT delayed INTO `category_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, category_id, language_id, name, description, meta_keywords, meta_description, meta_title, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.category_id, NEW.language_id, NEW.name, NEW.description, NEW.meta_keywords, NEW.meta_description, NEW.meta_title, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `category_description_updateTrigger`;
CREATE TRIGGER `category_description_updateTrigger` AFTER UPDATE ON `category_description` FOR EACH ROW INSERT delayed INTO `category_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, category_id, language_id, name, description, meta_keywords, meta_description, meta_title, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.category_id, NEW.language_id, NEW.name, NEW.description, NEW.meta_keywords, NEW.meta_description, NEW.meta_title, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `category_description_deleteTrigger`;
CREATE TRIGGER `category_description_deleteTrigger` BEFORE DELETE ON `category_description` FOR EACH ROW INSERT delayed INTO `category_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, category_id, language_id, name, description, meta_keywords, meta_description, meta_title, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.category_id, OLD.language_id, OLD.name, OLD.description, OLD.meta_keywords, OLD.meta_description, OLD.meta_title, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to product_to_category table
#
ALTER TABLE `product_to_category` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `product_to_category` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in product_to_category table
#
CREATE TABLE IF NOT EXISTS `product_to_category_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `product_id` int(11) DEFAULT '0',
  `category_id` int(11) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `product_to_category_insertTrigger`;
CREATE TRIGGER `product_to_category_insertTrigger` AFTER INSERT ON `product_to_category` FOR EACH ROW INSERT delayed INTO product_to_category_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, category_id, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.product_id, NEW.category_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_to_category_updateTrigger`;
CREATE TRIGGER `product_to_category_updateTrigger` AFTER UPDATE ON `product_to_category` FOR EACH ROW INSERT delayed INTO product_to_category_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, category_id, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.product_id, NEW.category_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_to_category_deleteTrigger`;
CREATE TRIGGER `product_to_category_deleteTrigger` BEFORE DELETE ON `product_to_category` FOR EACH ROW INSERT delayed INTO product_to_category_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, category_id, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.product_id, OLD.category_id, OLD.date_added, OLD.date_modified);

#
# Alter review table
#
ALTER TABLE `review` CHANGE `date_added` `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `review` CHANGE `date_modified` `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

#
# Log every change in review table
#
CREATE TABLE IF NOT EXISTS `review_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `review_id` int(11) DEFAULT '0',
  `product_id` int(11) DEFAULT '0',
  `customer_id` int(11) DEFAULT '0',
  `author` varchar(64) collate utf8_unicode_ci default '',
  `text` text collate utf8_unicode_ci,
  `rating1` int(1) DEFAULT '0',
  `rating2` int(1) DEFAULT '0',
  `rating3` int(1) DEFAULT '0',
  `rating4` int(1) DEFAULT '0',
  `status` int(1) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `review_insertTrigger`;
CREATE TRIGGER `review_insertTrigger` AFTER INSERT ON `review` FOR EACH ROW INSERT delayed INTO review_log (trigger_action, trigger_modifier_id, trigger_modifier_title, review_id, product_id, customer_id, author, text, rating1, rating2, rating3, rating4, status, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.review_id, NEW.product_id, NEW.customer_id, NEW.author, NEW.text, NEW.rating1, NEW.rating2, NEW.rating3, NEW.rating4, NEW.status, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `review_updateTrigger`;
CREATE TRIGGER `review_updateTrigger` AFTER UPDATE ON `review` FOR EACH ROW INSERT delayed INTO review_log (trigger_action, trigger_modifier_id, trigger_modifier_title, review_id, product_id, customer_id, author, text, rating1, rating2, rating3, rating4, status, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.review_id, NEW.product_id, NEW.customer_id, NEW.author, NEW.text, NEW.rating1, NEW.rating2, NEW.rating3, NEW.rating4, NEW.status, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `review_deleteTrigger`;
CREATE TRIGGER `review_deleteTrigger` BEFORE DELETE ON `review` FOR EACH ROW INSERT delayed INTO review_log (trigger_action, trigger_modifier_id, trigger_modifier_title, review_id, product_id, customer_id, author, text, rating1, rating2, rating3, rating4, status, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.review_id, OLD.product_id, OLD.customer_id, OLD.author, OLD.text, OLD.rating1, OLD.rating2, OLD.rating3, OLD.rating4, OLD.status, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to country table
#
ALTER TABLE `country` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `country` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in country table
#
CREATE TABLE IF NOT EXISTS `country_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `country_id` int(11) DEFAULT '0',
  `name` varchar(64) collate utf8_unicode_ci default '',
  `country_status` int(1) DEFAULT '0',
  `iso_code_2` varchar(2) collate utf8_unicode_ci default '',
  `iso_code_3` varchar(3) collate utf8_unicode_ci default '',
  `address_format` text collate utf8_unicode_ci,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `country_insertTrigger`;
CREATE TRIGGER `country_insertTrigger` AFTER INSERT ON `country` FOR EACH ROW INSERT delayed INTO country_log (trigger_action, trigger_modifier_id, trigger_modifier_title, country_id, name, country_status, iso_code_2, iso_code_3, address_format, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.country_id, NEW.name, NEW.country_status, NEW.iso_code_2, NEW.iso_code_3, NEW.address_format, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `country_updateTrigger`;
CREATE TRIGGER `country_updateTrigger` AFTER UPDATE ON `country` FOR EACH ROW INSERT delayed INTO country_log (trigger_action, trigger_modifier_id, trigger_modifier_title, country_id, name, country_status, iso_code_2, iso_code_3, address_format, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.country_id, NEW.name, NEW.country_status, NEW.iso_code_2, NEW.iso_code_3, NEW.address_format, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `country_deleteTrigger`;
CREATE TRIGGER `country_deleteTrigger` BEFORE DELETE ON `country` FOR EACH ROW INSERT delayed INTO country_log (trigger_action, trigger_modifier_id, trigger_modifier_title, country_id, name, country_status, iso_code_2, iso_code_3, address_format, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.country_id, OLD.name, OLD.country_status, OLD.iso_code_2, OLD.iso_code_3, OLD.address_format, OLD.date_added, OLD.date_modified);

#
# Alter geo_zone table
#
ALTER TABLE `geo_zone` CHANGE `date_modified` `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in geo_zone table
#
CREATE TABLE IF NOT EXISTS `geo_zone_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `geo_zone_id` int(11) DEFAULT '0',
  `name` varchar(32) collate utf8_unicode_ci default '',
  `description` varchar(255) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `geo_zone_insertTrigger`;
CREATE TRIGGER `geo_zone_insertTrigger` AFTER INSERT ON `geo_zone` FOR EACH ROW INSERT delayed INTO geo_zone_log (trigger_action, trigger_modifier_id, trigger_modifier_title, geo_zone_id, name, description, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.geo_zone_id, NEW.name, NEW.description, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `geo_zone_updateTrigger`;
CREATE TRIGGER `geo_zone_updateTrigger` AFTER UPDATE ON `geo_zone` FOR EACH ROW INSERT delayed INTO geo_zone_log (trigger_action, trigger_modifier_id, trigger_modifier_title, geo_zone_id, name, description, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.geo_zone_id, NEW.name, NEW.description, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `geo_zone_deleteTrigger`;
CREATE TRIGGER `geo_zone_deleteTrigger` BEFORE DELETE ON `geo_zone` FOR EACH ROW INSERT delayed INTO geo_zone_log (trigger_action, trigger_modifier_id, trigger_modifier_title, geo_zone_id, name, description, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.geo_zone_id, OLD.name, OLD.description, OLD.date_added, OLD.date_modified);

#
# Alter tax_class table
#
ALTER TABLE `tax_class` CHANGE `date_modified` `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

#
# Log every change in tax_class table
#
CREATE TABLE IF NOT EXISTS `tax_class_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `tax_class_id` int(11) DEFAULT '0',
  `title` varchar(32) collate utf8_unicode_ci default '',
  `description` varchar(255) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `tax_class_insertTrigger`;
CREATE TRIGGER `tax_class_insertTrigger` AFTER INSERT ON `tax_class` FOR EACH ROW INSERT delayed INTO tax_class_log (trigger_action, trigger_modifier_id, trigger_modifier_title, tax_class_id, title, description, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.tax_class_id, NEW.title, NEW.description, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `tax_class_updateTrigger`;
CREATE TRIGGER `tax_class_updateTrigger` AFTER UPDATE ON `tax_class` FOR EACH ROW INSERT delayed INTO tax_class_log (trigger_action, trigger_modifier_id, trigger_modifier_title, tax_class_id, title, description, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.tax_class_id, NEW.title, NEW.description, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `tax_class_deleteTrigger`;
CREATE TRIGGER `tax_class_deleteTrigger` BEFORE DELETE ON `tax_class` FOR EACH ROW INSERT delayed INTO tax_class_log (trigger_action, trigger_modifier_id, trigger_modifier_title, tax_class_id, title, description, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.tax_class_id, OLD.title, OLD.description, OLD.date_added, OLD.date_modified);

#
# Alter tax_rate table
#
ALTER TABLE `tax_rate` CHANGE `date_modified` `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in tax_rate table
#
CREATE TABLE IF NOT EXISTS `tax_rate_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `tax_rate_id` int(11) DEFAULT '0',
  `geo_zone_id` int(11) DEFAULT '0',
  `tax_class_id` int(11) DEFAULT '0',
  `priority` int(5) DEFAULT '1',
  `rate` decimal(7,4) DEFAULT '0.0000',
  `description` varchar(255) collate utf8_unicode_ci DEFAULT '',
  `date_added` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `tax_rate_insertTrigger`;
CREATE TRIGGER `tax_rate_insertTrigger` AFTER INSERT ON `tax_rate` FOR EACH ROW INSERT delayed INTO tax_rate_log (trigger_action, trigger_modifier_id, trigger_modifier_title, tax_rate_id, geo_zone_id, tax_class_id, priority, rate, description, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.tax_rate_id, NEW.geo_zone_id, NEW.tax_class_id, NEW.priority, NEW.rate, NEW.description, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `tax_rate_updateTrigger`;
CREATE TRIGGER `tax_rate_updateTrigger` AFTER UPDATE ON `tax_rate` FOR EACH ROW INSERT delayed INTO tax_rate_log (trigger_action, trigger_modifier_id, trigger_modifier_title, tax_rate_id, geo_zone_id, tax_class_id, priority, rate, description, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.tax_rate_id, NEW.geo_zone_id, NEW.tax_class_id, NEW.priority, NEW.rate, NEW.description, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `tax_rate_deleteTrigger`;
CREATE TRIGGER `tax_rate_deleteTrigger` BEFORE DELETE ON `tax_rate` FOR EACH ROW INSERT delayed INTO tax_rate_log (trigger_action, trigger_modifier_id, trigger_modifier_title, tax_rate_id, geo_zone_id, tax_class_id, priority, rate, description, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.tax_rate_id, OLD.geo_zone_id, OLD.tax_class_id, OLD.priority, OLD.rate, OLD.description, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to zone table
#
ALTER TABLE `zone` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `zone` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in zone table
#
CREATE TABLE IF NOT EXISTS `zone_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `zone_id` int(11) DEFAULT '0',
  `country_id` int(11) DEFAULT '0',
  `code` varchar(32) collate utf8_unicode_ci default '',
  `name` varchar(32) collate utf8_unicode_ci default '',
  `zone_status` int(1) DEFAULT '1',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `zone_insertTrigger`;
CREATE TRIGGER `zone_insertTrigger` AFTER INSERT ON `zone` FOR EACH ROW INSERT delayed INTO zone_log (trigger_action, trigger_modifier_id, trigger_modifier_title, zone_id, country_id, code, name, zone_status, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.zone_id, NEW.country_id, NEW.code, NEW.name, NEW.zone_status, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `zone_updateTrigger`;
CREATE TRIGGER `zone_updateTrigger` AFTER UPDATE ON `zone` FOR EACH ROW INSERT delayed INTO zone_log (trigger_action, trigger_modifier_id, trigger_modifier_title, zone_id, country_id, code, name, zone_status, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.zone_id, NEW.country_id, NEW.code, NEW.name, NEW.zone_status, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `zone_deleteTrigger`;
CREATE TRIGGER `zone_deleteTrigger` BEFORE DELETE ON `zone` FOR EACH ROW INSERT delayed INTO zone_log (trigger_action, trigger_modifier_id, trigger_modifier_title, zone_id, country_id, code, name, zone_status, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.zone_id, OLD.country_id, OLD.code, OLD.name, OLD.zone_status, OLD.date_added, OLD.date_modified);

#
# Alter zone_to_geo_zone table
#
ALTER TABLE `zone_to_geo_zone` CHANGE `date_modified` `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in zone_to_geo_zone table
#
CREATE TABLE IF NOT EXISTS `zone_to_geo_zone_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `zone_to_geo_zone_id` int(11) DEFAULT '0',
  `country_id` int(11) DEFAULT '0',
  `zone_id` int(11) DEFAULT '0',
  `geo_zone_id` int(11) DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `zone_to_geo_zone_insertTrigger`;
CREATE TRIGGER `zone_to_geo_zone_insertTrigger` AFTER INSERT ON `zone_to_geo_zone` FOR EACH ROW INSERT delayed INTO zone_to_geo_zone_log (trigger_action, trigger_modifier_id, trigger_modifier_title, zone_to_geo_zone_id, country_id, zone_id, geo_zone_id, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.zone_to_geo_zone_id, NEW.country_id, NEW.zone_id, NEW.geo_zone_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `zone_to_geo_zone_updateTrigger`;
CREATE TRIGGER `zone_to_geo_zone_updateTrigger` AFTER UPDATE ON `zone_to_geo_zone` FOR EACH ROW INSERT delayed INTO zone_to_geo_zone_log (trigger_action, trigger_modifier_id, trigger_modifier_title, zone_to_geo_zone_id, country_id, zone_id, geo_zone_id, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.zone_to_geo_zone_id, NEW.country_id, NEW.zone_id, NEW.geo_zone_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `zone_to_geo_zone_deleteTrigger`;
CREATE TRIGGER `zone_to_geo_zone_deleteTrigger` BEFORE DELETE ON `zone_to_geo_zone` FOR EACH ROW INSERT delayed INTO zone_to_geo_zone_log (trigger_action, trigger_modifier_id, trigger_modifier_title, zone_to_geo_zone_id, country_id, zone_id, geo_zone_id, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.zone_to_geo_zone_id, OLD.country_id, OLD.zone_id, OLD.geo_zone_id, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to url_alias table
#
ALTER TABLE `url_alias` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `url_alias` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in url_alias table
#
CREATE TABLE IF NOT EXISTS `url_alias_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `url_alias_id` int(11) DEFAULT '0',
  `query` varchar(128) collate utf8_unicode_ci default '',
  `alias` varchar(128) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `url_alias_insertTrigger`;
CREATE TRIGGER `url_alias_insertTrigger` AFTER INSERT ON `url_alias` FOR EACH ROW INSERT delayed INTO url_alias_log (trigger_action, trigger_modifier_id, trigger_modifier_title, url_alias_id, query, alias, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.url_alias_id, NEW.query, NEW.alias, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `url_alias_updateTrigger`;
CREATE TRIGGER `url_alias_updateTrigger` AFTER UPDATE ON `url_alias` FOR EACH ROW INSERT delayed INTO url_alias_log (trigger_action, trigger_modifier_id, trigger_modifier_title, url_alias_id, query, alias, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.url_alias_id, NEW.query, NEW.alias, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `url_alias_deleteTrigger`;
CREATE TRIGGER `url_alias_deleteTrigger` BEFORE DELETE ON `url_alias` FOR EACH ROW INSERT delayed INTO url_alias_log (trigger_action, trigger_modifier_id, trigger_modifier_title, url_alias_id, query, alias, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.url_alias_id, OLD.query, OLD.alias, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to order_status table
#
ALTER TABLE `order_status` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `order_status` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in order_status table
#
CREATE TABLE IF NOT EXISTS `order_status_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `order_status_id` int(11) DEFAULT '0',
  `language_id` int(11) DEFAULT '0',
  `name` varchar(32) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `order_status_insertTrigger`;
CREATE TRIGGER `order_status_insertTrigger` AFTER INSERT ON `order_status` FOR EACH ROW INSERT delayed INTO order_status_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_status_id, language_id, name, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.order_status_id, NEW.language_id, NEW.name, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `order_status_updateTrigger`;
CREATE TRIGGER `order_status_updateTrigger` AFTER UPDATE ON `order_status` FOR EACH ROW INSERT delayed INTO order_status_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_status_id, language_id, name, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.order_status_id, NEW.language_id, NEW.name, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `order_status_deleteTrigger`;
CREATE TRIGGER `order_status_deleteTrigger` BEFORE DELETE ON `order_status` FOR EACH ROW INSERT delayed INTO order_status_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_status_id, language_id, name, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.order_status_id, OLD.language_id, OLD.name, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to language table
#
ALTER TABLE `language` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `language` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in language table
#
CREATE TABLE IF NOT EXISTS `language_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `language_id` int(11) DEFAULT '0',
  `language_status` int(1) DEFAULT '1',
  `name` varchar(32) collate utf8_unicode_ci default '',
  `code` varchar(5) collate utf8_unicode_ci default NULL,
  `image` varchar(64) collate utf8_unicode_ci default NULL,
  `directory` varchar(32) collate utf8_unicode_ci default '',
  `filename` varchar(64) collate utf8_unicode_ci default '',
  `sort_order` varchar(3) collate utf8_unicode_ci default NULL,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `language_insertTrigger`;
CREATE TRIGGER `language_insertTrigger` AFTER INSERT ON `language` FOR EACH ROW INSERT delayed INTO language_log (trigger_action, trigger_modifier_id, trigger_modifier_title, language_id, language_status, name, code, image, directory, filename, sort_order, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.language_id, NEW.language_status, NEW.name, NEW.code, NEW.image, NEW.directory, NEW.filename, NEW.sort_order, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `language_updateTrigger`;
CREATE TRIGGER `language_updateTrigger` AFTER UPDATE ON `language` FOR EACH ROW INSERT delayed INTO language_log (trigger_action, trigger_modifier_id, trigger_modifier_title, language_id, language_status, name, code, image, directory, filename, sort_order, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.language_id, NEW.language_status, NEW.name, NEW.code, NEW.image, NEW.directory, NEW.filename, NEW.sort_order, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `language_deleteTrigger`;
CREATE TRIGGER `language_deleteTrigger` BEFORE DELETE ON `language` FOR EACH ROW INSERT delayed INTO language_log (trigger_action, trigger_modifier_id, trigger_modifier_title, language_id, language_status, name, code, image, directory, filename, sort_order, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.language_id, OLD.language_status, OLD.name, OLD.code, OLD.image, OLD.directory, OLD.filename, OLD.sort_order, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to manufacturer table
#
ALTER TABLE `manufacturer` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `manufacturer` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in manufacturer table
#
CREATE TABLE IF NOT EXISTS `manufacturer_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `manufacturer_id` int(11) DEFAULT '0',
  `name` varchar(64) collate utf8_unicode_ci default '',
  `image_id` int(11) DEFAULT '0',
  `sort_order` varchar(3) collate utf8_unicode_ci default '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `manufacturer_insertTrigger`;
CREATE TRIGGER `manufacturer_insertTrigger` AFTER INSERT ON `manufacturer` FOR EACH ROW INSERT delayed INTO manufacturer_log (trigger_action, trigger_modifier_id, trigger_modifier_title, manufacturer_id, name, image_id, sort_order, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.manufacturer_id, NEW.name, NEW.image_id, NEW.sort_order, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `manufacturer_updateTrigger`;
CREATE TRIGGER `manufacturer_updateTrigger` AFTER UPDATE ON `manufacturer` FOR EACH ROW INSERT delayed INTO manufacturer_log (trigger_action, trigger_modifier_id, trigger_modifier_title, manufacturer_id, name, image_id, sort_order, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.manufacturer_id, NEW.name, NEW.image_id, NEW.sort_order, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `manufacturer_deleteTrigger`;
CREATE TRIGGER `manufacturer_deleteTrigger` BEFORE DELETE ON `manufacturer` FOR EACH ROW INSERT delayed INTO manufacturer_log (trigger_action, trigger_modifier_id, trigger_modifier_title, manufacturer_id, name, image_id, sort_order, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.manufacturer_id, OLD.name, OLD.image_id, OLD.sort_order, OLD.date_added, OLD.date_modified);

#
# TABLE STRUCTURE FOR: `login`
#
CREATE TABLE IF NOT EXISTS `login` (
  `login_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `ip` varchar(39) collate utf8_unicode_ci NOT NULL,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY  (`login_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Log every change in login table
#
CREATE TABLE IF NOT EXISTS `login_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `login_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `ip` varchar(39) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `login_insertTrigger`;
CREATE TRIGGER `login_insertTrigger` AFTER INSERT ON `login` FOR EACH ROW INSERT delayed INTO login_log (trigger_action, login_id, user_id, ip, date_added, date_modified) VALUES ('INSERT', NEW.login_id, NEW.user_id, NEW.ip, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `login_updateTrigger`;
CREATE TRIGGER `login_updateTrigger` AFTER UPDATE ON `login` FOR EACH ROW INSERT delayed INTO login_log (trigger_action, login_id, user_id, ip, date_added, date_modified) VALUES ('UPDATE', NEW.login_id, NEW.user_id, NEW.ip, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `login_deleteTrigger`;
CREATE TRIGGER `login_deleteTrigger` BEFORE DELETE ON `login` FOR EACH ROW INSERT delayed INTO login_log (trigger_action, login_id, user_id, ip, date_added, date_modified) VALUES ('DELETE', OLD.login_id, OLD.user_id, OLD.ip, OLD.date_added, OLD.date_modified);

#
# Add index to order_product table and product table
#
ALTER TABLE `order_product` ADD INDEX(`product_id`);
ALTER TABLE `product` ADD INDEX(`image_id`);

#
# Add date_updated and date_added to currency table
#
ALTER TABLE `currency` ADD `date_value_updated` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `currency` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';

#
# Alter currency table
#
ALTER TABLE `currency` CHANGE `date_modified` `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in currency table
#
CREATE TABLE IF NOT EXISTS `currency_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `currency_id` int(11) DEFAULT '0',
  `title` varchar(64) collate utf8_unicode_ci default '',
  `code` varchar(3) collate utf8_unicode_ci default '',
  `status` int(1) DEFAULT '1',
  `lock_rate` int(1) DEFAULT '0',
  `symbol_left` varchar(12) collate utf8_unicode_ci default NULL,
  `symbol_right` varchar(12) collate utf8_unicode_ci default NULL,
  `decimal_place` char(1) collate utf8_unicode_ci default NULL,
  `value` double(13,8) default NULL,
  `date_value_updated` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `currency_insertTrigger`;
CREATE TRIGGER `currency_insertTrigger` AFTER INSERT ON `currency` FOR EACH ROW INSERT delayed INTO currency_log (trigger_action, trigger_modifier_id, trigger_modifier_title, currency_id, title, code, status, lock_rate, symbol_left, symbol_right, decimal_place, value, date_value_updated, date_added, date_modified) VALUES ('INSERT',  @modifier_id, @modifier_title, NEW.currency_id, NEW.title, NEW.code, NEW.status, NEW.lock_rate, NEW.symbol_left, NEW.symbol_right, NEW.decimal_place, NEW.value, NEW.date_value_updated, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `currency_updateTrigger`;
CREATE TRIGGER `currency_updateTrigger` AFTER UPDATE ON `currency` FOR EACH ROW INSERT delayed INTO currency_log (trigger_action, trigger_modifier_id, trigger_modifier_title, currency_id, title, code, status, lock_rate, symbol_left, symbol_right, decimal_place, value, date_value_updated, date_added, date_modified) VALUES ('UPDATE',  @modifier_id, @modifier_title, NEW.currency_id, NEW.title, NEW.code, NEW.status, NEW.lock_rate, NEW.symbol_left, NEW.symbol_right, NEW.decimal_place, NEW.value, NEW.date_value_updated, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `currency_deleteTrigger`;
CREATE TRIGGER `currency_deleteTrigger` BEFORE DELETE ON `currency` FOR EACH ROW INSERT delayed INTO currency_log (trigger_action, trigger_modifier_id, trigger_modifier_title, currency_id, title, code, status, lock_rate, symbol_left, symbol_right, decimal_place, value, date_value_updated, date_added, date_modified) VALUES ('DELETE',  @modifier_id, @modifier_title, OLD.currency_id, OLD.title, OLD.code, OLD.status, OLD.lock_rate, OLD.symbol_left, OLD.symbol_right, OLD.decimal_place, OLD.value, OLD.date_value_updated, OLD.date_added, OLD.date_modified);

#
# Alter newsletter table
#
ALTER TABLE `newsletter` CHANGE `date_sent` `date_sent` datetime NOT NULL default '1000-01-01 00:00:00' AFTER `content`;
ALTER TABLE `newsletter` CHANGE `date_added` `date_added` datetime NOT NULL default '1000-01-01 00:00:00';

#
# Add date_modified to newsletter table
#
ALTER TABLE `newsletter` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in newsletter table
#
CREATE TABLE IF NOT EXISTS `newsletter_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `newsletter_id` int(11) DEFAULT '0',
  `subject` varchar(255) collate utf8_unicode_ci default '',
  `content` text collate utf8_unicode_ci,
  `date_sent` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `newsletter_insertTrigger`;
CREATE TRIGGER `newsletter_insertTrigger` AFTER INSERT ON `newsletter` FOR EACH ROW INSERT delayed INTO newsletter_log (trigger_action, trigger_modifier_id, trigger_modifier_title, newsletter_id, subject, content, date_sent, date_added, date_modified) VALUES ('INSERT',  @modifier_id, @modifier_title, NEW.newsletter_id, NEW.subject, NEW.content, NEW.date_sent, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `newsletter_updateTrigger`;
CREATE TRIGGER `newsletter_updateTrigger` AFTER UPDATE ON `newsletter` FOR EACH ROW INSERT delayed INTO newsletter_log (trigger_action, trigger_modifier_id, trigger_modifier_title, newsletter_id, subject, content, date_sent, date_added, date_modified) VALUES ('UPDATE',  @modifier_id, @modifier_title, NEW.newsletter_id, NEW.subject, NEW.content, NEW.date_sent, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `newsletter_deleteTrigger`;
CREATE TRIGGER `newsletter_deleteTrigger` BEFORE DELETE ON `newsletter` FOR EACH ROW INSERT delayed INTO newsletter_log (trigger_action, trigger_modifier_id, trigger_modifier_title, newsletter_id, subject, content, date_sent, date_added, date_modified) VALUES ('DELETE',  @modifier_id, @modifier_title, OLD.newsletter_id, OLD.subject, OLD.content, OLD.date_sent, OLD.date_added, OLD.date_modified);

#
# Alter user table
#
ALTER TABLE `user` CHANGE `date_added` `date_added` datetime NOT NULL default '1000-01-01 00:00:00' AFTER `position`;

#
# Add date_modified to user table
#
ALTER TABLE `user` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in user table
#
CREATE TABLE IF NOT EXISTS `user_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `user_id` int(11) DEFAULT '0',
  `user_group_id` int(11) DEFAULT '0',
  `username` varchar(20) collate utf8_unicode_ci DEFAULT '',
  `password` varchar(32) collate utf8_unicode_ci DEFAULT '',
  `firstname` varchar(32) collate utf8_unicode_ci DEFAULT '',
  `lastname` varchar(32) collate utf8_unicode_ci DEFAULT '',
  `email` varchar(96) collate utf8_unicode_ci DEFAULT '',
  `ip` varchar(39) collate utf8_unicode_ci DEFAULT '',
  `telephone` varchar(32) collate utf8_unicode_ci DEFAULT '',
  `mobile` varchar(32) collate utf8_unicode_ci DEFAULT '',
  `fax` varchar(32) collate utf8_unicode_ci DEFAULT '',
  `monogram` varchar(4) collate utf8_unicode_ci DEFAULT '',
  `signature` varchar(64) collate utf8_unicode_ci DEFAULT '',
  `position` varchar(32) collate utf8_unicode_ci DEFAULT '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `user_insertTrigger`;
CREATE TRIGGER `user_insertTrigger` AFTER INSERT ON `user` FOR EACH ROW INSERT delayed INTO user_log (trigger_action, trigger_modifier_id, trigger_modifier_title, user_id, user_group_id, username, password, firstname, lastname, email, ip, telephone, mobile, fax, monogram, signature, position, date_added, date_modified) VALUES ('INSERT',  @modifier_id, @modifier_title, NEW.user_id, NEW.user_group_id, NEW.username, NEW.password, NEW.firstname, NEW.lastname, NEW.email, NEW.ip, NEW.telephone, NEW.mobile, NEW.fax, NEW.monogram, NEW.signature, NEW.position, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `user_updateTrigger`;
CREATE TRIGGER `user_updateTrigger` AFTER UPDATE ON `user` FOR EACH ROW INSERT delayed INTO user_log (trigger_action, trigger_modifier_id, trigger_modifier_title, user_id, user_group_id, username, password, firstname, lastname, email, ip, telephone, mobile, fax, monogram, signature, position, date_added, date_modified) VALUES ('UPDATE',  @modifier_id, @modifier_title, NEW.user_id, NEW.user_group_id, NEW.username, NEW.password, NEW.firstname, NEW.lastname, NEW.email, NEW.ip, NEW.telephone, NEW.mobile, NEW.fax, NEW.monogram, NEW.signature, NEW.position, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `user_deleteTrigger`;
CREATE TRIGGER `user_deleteTrigger` BEFORE DELETE ON `user` FOR EACH ROW INSERT delayed INTO user_log (trigger_action, trigger_modifier_id, trigger_modifier_title, user_id, user_group_id, username, password, firstname, lastname, email, ip, telephone, mobile, fax, monogram, signature, position, date_added, date_modified) VALUES ('DELETE',  @modifier_id, @modifier_title, OLD.user_id, OLD.user_group_id, OLD.username, OLD.password, OLD.firstname, OLD.lastname, OLD.email, OLD.ip, OLD.telephone, OLD.mobile, OLD.fax, OLD.monogram, OLD.signature, OLD.position, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to user_group table
#
ALTER TABLE `user_group` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `user_group` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

#
# Log every change in user_group table
#
CREATE TABLE IF NOT EXISTS `user_group_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `user_group_id` int(11) DEFAULT '0',
  `name` varchar(64) collate utf8_unicode_ci DEFAULT NULL,
  `permission` text collate utf8_unicode_ci,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `user_group_insertTrigger`;
CREATE TRIGGER `user_group_insertTrigger` AFTER INSERT ON `user_group` FOR EACH ROW INSERT delayed INTO user_group_log (trigger_action, trigger_modifier_id, trigger_modifier_title, user_group_id, name, permission,  date_added, date_modified) VALUES ('INSERT',  @modifier_id, @modifier_title, NEW.user_group_id, NEW.name, NEW.permission, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `user_group_updateTrigger`;
CREATE TRIGGER `user_group_updateTrigger` AFTER UPDATE ON `user_group` FOR EACH ROW INSERT delayed INTO user_group_log (trigger_action, trigger_modifier_id, trigger_modifier_title, user_group_id, name, permission,  date_added, date_modified) VALUES ('UPDATE',  @modifier_id, @modifier_title, NEW.user_group_id, NEW.name, NEW.permission, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `user_group_deleteTrigger`;
CREATE TRIGGER `user_group_deleteTrigger` BEFORE DELETE ON `user_group` FOR EACH ROW INSERT delayed INTO user_group_log (trigger_action, trigger_modifier_id, trigger_modifier_title, user_group_id, name, permission,  date_added, date_modified) VALUES ('DELETE',  @modifier_id, @modifier_title, OLD.user_group_id, OLD.name, OLD.permission, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to watermark table
#
ALTER TABLE `watermark` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `watermark` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

#
# Log every change in watermark table
#
CREATE TABLE IF NOT EXISTS `watermark_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `wm_id` int(11) DEFAULT '0',
  `wm_method` varchar(6) collate utf8_unicode_ci DEFAULT NULL,
  `wm_text` varchar(64) collate utf8_unicode_ci DEFAULT NULL,
  `wm_font` int(11) DEFAULT '0',
  `wm_fontcolor` varchar(6) collate utf8_unicode_ci DEFAULT NULL,
  `wm_transparency` int(11) DEFAULT '0',
  `wm_thposition` varchar(64) collate utf8_unicode_ci DEFAULT NULL,
  `wm_tvposition` varchar(64) collate utf8_unicode_ci DEFAULT NULL,
  `wm_thmargin` int(11) DEFAULT '0',
  `wm_tvmargin` int(11) DEFAULT '0',
  `wm_image` varchar(64) collate utf8_unicode_ci DEFAULT NULL,
  `wm_ihposition` varchar(64) collate utf8_unicode_ci DEFAULT NULL,
  `wm_ivposition` varchar(64) collate utf8_unicode_ci DEFAULT NULL,
  `wm_ihmargin` int(11) DEFAULT '0',
  `wm_ivmargin` int(11) DEFAULT '0',
  `wm_scale` int(11) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `watermark_insertTrigger`;
CREATE TRIGGER `watermark_insertTrigger` AFTER INSERT ON `watermark` FOR EACH ROW INSERT delayed INTO watermark_log (trigger_action, trigger_modifier_id, trigger_modifier_title, wm_id, wm_method, wm_text, wm_font, wm_fontcolor, wm_transparency, wm_thposition, wm_tvposition, wm_thmargin, wm_tvmargin, wm_image, wm_ihposition, wm_ivposition, wm_ihmargin, wm_ivmargin, wm_scale, date_added, date_modified) VALUES ('INSERT',  @modifier_id, @modifier_title, NEW.wm_id, NEW.wm_method, NEW.wm_text, NEW.wm_font, NEW.wm_fontcolor, NEW.wm_transparency, NEW.wm_thposition, NEW.wm_tvposition, NEW.wm_thmargin, NEW.wm_tvmargin, NEW.wm_image, NEW.wm_ihposition, NEW.wm_ivposition, NEW.wm_ihmargin, NEW.wm_ivmargin, NEW.wm_scale, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `watermark_updateTrigger`;
CREATE TRIGGER `watermark_updateTrigger` AFTER UPDATE ON `watermark` FOR EACH ROW INSERT delayed INTO watermark_log (trigger_action, trigger_modifier_id, trigger_modifier_title, wm_id, wm_method, wm_text, wm_font, wm_fontcolor, wm_transparency, wm_thposition, wm_tvposition, wm_thmargin, wm_tvmargin, wm_image, wm_ihposition, wm_ivposition, wm_ihmargin, wm_ivmargin, wm_scale, date_added, date_modified) VALUES ('UPDATE',  @modifier_id, @modifier_title, NEW.wm_id, NEW.wm_method, NEW.wm_text, NEW.wm_font, NEW.wm_fontcolor, NEW.wm_transparency, NEW.wm_thposition, NEW.wm_tvposition, NEW.wm_thmargin, NEW.wm_tvmargin, NEW.wm_image, NEW.wm_ihposition, NEW.wm_ivposition, NEW.wm_ihmargin, NEW.wm_ivmargin, NEW.wm_scale, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `watermark_deleteTrigger`;
CREATE TRIGGER `watermark_deleteTrigger` BEFORE DELETE ON `watermark` FOR EACH ROW INSERT delayed INTO watermark_log (trigger_action, trigger_modifier_id, trigger_modifier_title, wm_id, wm_method, wm_text, wm_font, wm_fontcolor, wm_transparency, wm_thposition, wm_tvposition, wm_thmargin, wm_tvmargin, wm_image, wm_ihposition, wm_ivposition, wm_ihmargin, wm_ivmargin, wm_scale, date_added, date_modified) VALUES ('DELETE',  @modifier_id, @modifier_title, OLD.wm_id, OLD.wm_method, OLD.wm_text, OLD.wm_font, OLD.wm_fontcolor, OLD.wm_transparency, OLD.wm_thposition, OLD.wm_tvposition, OLD.wm_thmargin, OLD.wm_tvmargin, OLD.wm_image, OLD.wm_ihposition, OLD.wm_ivposition, OLD.wm_ihmargin, OLD.wm_ivmargin, OLD.wm_scale, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to tpl_location table
#
ALTER TABLE `tpl_location` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `tpl_location` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

#
# Log every change in tpl_location table
#
CREATE TABLE IF NOT EXISTS `tpl_location_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `location_id` int(11) DEFAULT '0',
  `location` varchar(64) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `tpl_location_insertTrigger`;
CREATE TRIGGER `tpl_location_insertTrigger` AFTER INSERT ON `tpl_location` FOR EACH ROW INSERT delayed INTO tpl_location_log (trigger_action, location_id, location, date_added, date_modified) VALUES ('INSERT', NEW.location_id, NEW.location, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `tpl_location_updateTrigger`;
CREATE TRIGGER `tpl_location_updateTrigger` AFTER UPDATE ON `tpl_location` FOR EACH ROW INSERT delayed INTO tpl_location_log (trigger_action, location_id, location, date_added, date_modified) VALUES ('UPDATE', NEW.location_id, NEW.location, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `tpl_location_deleteTrigger`;
CREATE TRIGGER `tpl_location_deleteTrigger` BEFORE DELETE ON `tpl_location` FOR EACH ROW INSERT delayed INTO tpl_location_log (trigger_action, location_id, location, date_added, date_modified) VALUES ('DELETE', OLD.location_id, OLD.location, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to tpl_manager table
#
ALTER TABLE `tpl_manager` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `tpl_manager` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

#
# Log every change in tpl_manager table
#
CREATE TABLE IF NOT EXISTS `tpl_manager_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `tpl_manager_id` int(11) DEFAULT '0',
  `tpl_controller` varchar(64) collate utf8_unicode_ci default '',
  `tpl_columns` varchar(32) collate utf8_unicode_ci default '0',
  `tpl_status` int(3) DEFAULT '1',
  `tpl_color` varchar(64) collate utf8_unicode_ci DEFAULT NULL,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `tpl_manager_insertTrigger`;
CREATE TRIGGER `tpl_manager_insertTrigger` AFTER INSERT ON `tpl_manager` FOR EACH ROW INSERT delayed INTO tpl_manager_log (trigger_action, trigger_modifier_id, trigger_modifier_title, tpl_manager_id, tpl_controller, tpl_columns, tpl_status, tpl_color, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.tpl_manager_id, NEW.tpl_controller, NEW.tpl_columns, NEW.tpl_status, NEW.tpl_color, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `tpl_manager_updateTrigger`;
CREATE TRIGGER `tpl_manager_updateTrigger` AFTER UPDATE ON `tpl_manager` FOR EACH ROW INSERT delayed INTO tpl_manager_log (trigger_action, trigger_modifier_id, trigger_modifier_title, tpl_manager_id, tpl_controller, tpl_columns, tpl_status, tpl_color, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.tpl_manager_id, NEW.tpl_controller, NEW.tpl_columns, NEW.tpl_status, NEW.tpl_color, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `tpl_manager_deleteTrigger`;
CREATE TRIGGER `tpl_manager_deleteTrigger` BEFORE DELETE ON `tpl_manager` FOR EACH ROW INSERT delayed INTO tpl_manager_log (trigger_action, trigger_modifier_id, trigger_modifier_title, tpl_manager_id, tpl_controller, tpl_columns, tpl_status, tpl_color, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.tpl_manager_id, OLD.tpl_controller, OLD.tpl_columns, OLD.tpl_status, OLD.tpl_color, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to tpl_module table
#
ALTER TABLE `tpl_module` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `tpl_module` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

#
# Log every change in tpl_module table
#
CREATE TABLE IF NOT EXISTS `tpl_module_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `tpl_module_id` int(11) DEFAULT '0',
  `tpl_manager_id` int(11) DEFAULT '0',
  `location_id` int(11) DEFAULT '0',
  `module_code` varchar(32) collate utf8_unicode_ci default '',
  `sort_order` int(3) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `tpl_module_insertTrigger`;
CREATE TRIGGER `tpl_module_insertTrigger` AFTER INSERT ON `tpl_module` FOR EACH ROW INSERT delayed INTO tpl_module_log (trigger_action, trigger_modifier_id, trigger_modifier_title, tpl_module_id, tpl_manager_id, location_id, module_code, sort_order, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.tpl_module_id, NEW.tpl_manager_id, NEW.location_id, NEW.module_code, NEW.sort_order, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `tpl_module_updateTrigger`;
CREATE TRIGGER `tpl_module_updateTrigger` AFTER UPDATE ON `tpl_module` FOR EACH ROW INSERT delayed INTO tpl_module_log (trigger_action, trigger_modifier_id, trigger_modifier_title, tpl_module_id, tpl_manager_id, location_id, module_code, sort_order, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.tpl_module_id, NEW.tpl_manager_id, NEW.location_id, NEW.module_code, NEW.sort_order, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `tpl_module_deleteTrigger`;
CREATE TRIGGER `tpl_module_deleteTrigger` BEFORE DELETE ON `tpl_module` FOR EACH ROW INSERT delayed INTO tpl_module_log (trigger_action, trigger_modifier_id, trigger_modifier_title, tpl_module_id, tpl_manager_id, location_id, module_code, sort_order, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.tpl_module_id, OLD.tpl_manager_id, OLD.location_id, OLD.module_code, OLD.sort_order, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to option table
#
ALTER TABLE `option` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `option` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in option table
#
CREATE TABLE IF NOT EXISTS `option_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `option_id` int(11) DEFAULT '0',
  `language_id` int(11) DEFAULT '0',
  `name` varchar(32) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `option_insertTrigger`;
CREATE TRIGGER `option_insertTrigger` AFTER INSERT ON `option` FOR EACH ROW INSERT delayed INTO option_log (trigger_action, trigger_modifier_id, trigger_modifier_title, option_id, language_id, name, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.option_id, NEW.language_id, NEW.name, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `option_updateTrigger`;
CREATE TRIGGER `option_updateTrigger` AFTER UPDATE ON `option` FOR EACH ROW INSERT delayed INTO option_log (trigger_action, trigger_modifier_id, trigger_modifier_title, option_id, language_id, name, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.option_id, NEW.language_id, NEW.name, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `option_deleteTrigger`;
CREATE TRIGGER `option_deleteTrigger` BEFORE DELETE ON `option` FOR EACH ROW INSERT delayed INTO option_log (trigger_action, trigger_modifier_id, trigger_modifier_title, option_id, language_id, name, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.option_id, OLD.language_id, OLD.name, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to vendor table
#
ALTER TABLE `vendor` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `vendor` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in vendor table
#
CREATE TABLE IF NOT EXISTS `vendor_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `vendor_id` int(11) DEFAULT '0',
  `name` varchar(64) collate utf8_unicode_ci default '',
  `image_id` int(11) DEFAULT '0',
  `description` varchar(255) collate utf8_unicode_ci default '',
  `discount` varchar(255) collate utf8_unicode_ci default '',
  `status` int(1) default '0',
  `email` varchar(96) collate utf8_unicode_ci default '',
  `telephone` varchar(32) collate utf8_unicode_ci default '',
  `fax` varchar(32) collate utf8_unicode_ci default '',
  `website` varchar(96) collate utf8_unicode_ci default '',
  `trade` varchar(96) collate utf8_unicode_ci default '',
  `address_id` int(11) default '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `vendor_insertTrigger`;
CREATE TRIGGER `vendor_insertTrigger` AFTER INSERT ON `vendor` FOR EACH ROW INSERT delayed INTO vendor_log (trigger_action, trigger_modifier_id, trigger_modifier_title, vendor_id, name, image_id, description, discount, status, email, telephone, fax, website, trade, address_id, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.vendor_id, NEW.name, NEW.image_id, NEW.description, NEW.discount, NEW.status, NEW.email, NEW.telephone, NEW.fax, NEW.website, NEW.trade, NEW.address_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `vendor_updateTrigger`;
CREATE TRIGGER `vendor_updateTrigger` AFTER UPDATE ON `vendor` FOR EACH ROW INSERT delayed INTO vendor_log (trigger_action, trigger_modifier_id, trigger_modifier_title, vendor_id, name, image_id, description, discount, status, email, telephone, fax, website, trade, address_id, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.vendor_id, NEW.name, NEW.image_id, NEW.description, NEW.discount, NEW.status, NEW.email, NEW.telephone, NEW.fax, NEW.website, NEW.trade, NEW.address_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `vendor_deleteTrigger`;
CREATE TRIGGER `vendor_deleteTrigger` BEFORE DELETE ON `vendor` FOR EACH ROW INSERT delayed INTO vendor_log (trigger_action, trigger_modifier_id, trigger_modifier_title, vendor_id, name, image_id, description, discount, status, email, telephone, fax, website, trade, address_id, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.vendor_id, OLD.name, OLD.image_id, OLD.description, OLD.discount, OLD.status, OLD.email, OLD.telephone, OLD.fax, OLD.website, OLD.trade, OLD.address_id, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to vendor_address table
#
ALTER TABLE `vendor_address` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `vendor_address` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in vendor_address table
#
CREATE TABLE IF NOT EXISTS `vendor_address_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `address_id` int(11) DEFAULT '0',
  `vendor_id` int(11) default '0',
  `company` varchar(32) collate utf8_unicode_ci default NULL,
  `firstname` varchar(32) collate utf8_unicode_ci default '',
  `lastname` varchar(32) collate utf8_unicode_ci default '',
  `address_1` varchar(64) collate utf8_unicode_ci default '',
  `address_2` varchar(64) collate utf8_unicode_ci default NULL,
  `postcode` varchar(10) collate utf8_unicode_ci default '',
  `city` varchar(32) collate utf8_unicode_ci default '',
  `country_id` int(11) default '0',
  `zone_id` int(11) default '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `vendor_address_insertTrigger`;
CREATE TRIGGER `vendor_address_insertTrigger` AFTER INSERT ON `vendor_address` FOR EACH ROW INSERT delayed INTO vendor_address_log (trigger_action, trigger_modifier_id, trigger_modifier_title, address_id, vendor_id, company, firstname, lastname, address_1, address_2, postcode, city, country_id, zone_id, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.address_id, NEW.vendor_id, NEW.company, NEW.firstname, NEW.lastname, NEW.address_1, NEW.address_2, NEW.postcode, NEW.city, NEW.country_id, NEW.zone_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `vendor_address_updateTrigger`;
CREATE TRIGGER `vendor_address_updateTrigger` AFTER UPDATE ON `vendor_address` FOR EACH ROW INSERT delayed INTO vendor_address_log (trigger_action, trigger_modifier_id, trigger_modifier_title, address_id, vendor_id, company, firstname, lastname, address_1, address_2, postcode, city, country_id, zone_id, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.address_id, NEW.vendor_id, NEW.company, NEW.firstname, NEW.lastname, NEW.address_1, NEW.address_2, NEW.postcode, NEW.city, NEW.country_id, NEW.zone_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `vendor_address_deleteTrigger`;
CREATE TRIGGER `vendor_address_deleteTrigger` BEFORE DELETE ON `vendor_address` FOR EACH ROW INSERT delayed INTO vendor_address_log (trigger_action, trigger_modifier_id, trigger_modifier_title, address_id, vendor_id, company, firstname, lastname, address_1, address_2, postcode, city, country_id, zone_id, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.address_id, OLD.vendor_id, OLD.company, OLD.firstname, OLD.lastname, OLD.address_1, OLD.address_2, OLD.postcode, OLD.city, OLD.country_id, OLD.zone_id, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to order_product table
#
ALTER TABLE `order_product` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `order_product` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in order_product table
#
CREATE TABLE IF NOT EXISTS `order_product_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `order_product_id` int(11) DEFAULT '0',
  `order_id` int(11) default '0',
  `name` varchar(64) collate utf8_unicode_ci default '',
  `product_id` int(11) default '0',
  `model_number` varchar(32) collate utf8_unicode_ci default NULL,
  `vendor_name` varchar(64) collate utf8_unicode_ci default '',
  `vendor_id` int(11) default '0',
  `price` decimal(15,4) DEFAULT '0.0000',
  `discount` decimal(15,4) DEFAULT '0.0000',
  `special_price` decimal(15,4) DEFAULT '0.0000',
  `coupon` decimal(15,4) DEFAULT '0.0000',
  `general_discount` decimal(15,4) DEFAULT '0.0000',
  `total` decimal(15,4) DEFAULT '0.0000',
  `tax` decimal(15,4) DEFAULT '0.0000',
  `quantity` int(4) default '0',
  `barcode` varchar(20) collate utf8_unicode_ci default '',
  `shipping` tinyint(1) default '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `order_product_insertTrigger`;
CREATE TRIGGER `order_product_insertTrigger` AFTER INSERT ON `order_product` FOR EACH ROW INSERT delayed INTO order_product_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_product_id, order_id, name, product_id, model_number, vendor_name, vendor_id, price, discount, special_price, coupon, general_discount, total, tax, quantity, barcode, shipping, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.order_product_id, NEW.order_id, NEW.name, NEW.product_id, NEW.model_number, NEW.vendor_name, NEW.vendor_id, NEW.price, NEW.discount, NEW.special_price, NEW.coupon, NEW.general_discount, NEW.total, NEW.tax, NEW.quantity, NEW.barcode, NEW.shipping, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `order_product_updateTrigger`;
CREATE TRIGGER `order_product_updateTrigger` AFTER UPDATE ON `order_product` FOR EACH ROW INSERT delayed INTO order_product_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_product_id, order_id, name, product_id, model_number, vendor_name, vendor_id, price, discount, special_price, coupon, general_discount, total, tax, quantity, barcode, shipping, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.order_product_id, NEW.order_id, NEW.name, NEW.product_id, NEW.model_number, NEW.vendor_name, NEW.vendor_id, NEW.price, NEW.discount, NEW.special_price, NEW.coupon, NEW.general_discount, NEW.total, NEW.tax, NEW.quantity, NEW.barcode, NEW.shipping, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `order_product_deleteTrigger`;
CREATE TRIGGER `order_product_deleteTrigger` BEFORE DELETE ON `order_product` FOR EACH ROW INSERT delayed INTO order_product_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_product_id, order_id, name, product_id, model_number, vendor_name, vendor_id, price, discount, special_price, coupon, general_discount, total, tax, quantity, barcode, shipping, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.order_product_id, OLD.order_id, OLD.name, OLD.product_id, OLD.model_number, OLD.vendor_name, OLD.vendor_id, OLD.price, OLD.discount, OLD.special_price, OLD.coupon, OLD.general_discount, OLD.total, OLD.tax, OLD.quantity, OLD.barcode, OLD.shipping, OLD.date_added, OLD.date_modified);

#
# Alter order table
#
ALTER TABLE `order` CHANGE `date_added` `date_added` datetime NOT NULL default '1000-01-01 00:00:00' AFTER `taxed`;
ALTER TABLE `order` CHANGE `date_modified` `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in order table
#
CREATE TABLE IF NOT EXISTS `order_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `order_id` int(11) DEFAULT '0',
  `customer_id` int(11) default '0',
  `reference` varchar(32) collate utf8_unicode_ci default '',
  `modified` int(1) default '0',
  `new_reference` varchar(32) collate utf8_unicode_ci default '',
  `invoice_number` varchar(32) collate utf8_unicode_ci default '',
  `firstname` varchar(32) collate utf8_unicode_ci default '',
  `lastname` varchar(32) collate utf8_unicode_ci default NULL,
  `fax` varchar(32) collate utf8_unicode_ci default '',
  `email` varchar(96) collate utf8_unicode_ci default '',
  `shipping_firstname` varchar(64) collate utf8_unicode_ci default '',
  `shipping_lastname` varchar(32) collate utf8_unicode_ci default '',
  `shipping_company` varchar(32) collate utf8_unicode_ci default NULL,
  `shipping_address_1` varchar(64) collate utf8_unicode_ci default '',
  `shipping_address_2` varchar(32) collate utf8_unicode_ci default NULL,
  `shipping_city` varchar(32) collate utf8_unicode_ci default '',
  `shipping_postcode` varchar(10) collate utf8_unicode_ci default '',
  `shipping_zone` varchar(32) collate utf8_unicode_ci default NULL,
  `shipping_country` varchar(64) collate utf8_unicode_ci default '',
  `shipping_address_format` text collate utf8_unicode_ci,
  `shipping_method` varchar(128) collate utf8_unicode_ci default '',
  `payment_firstname` varchar(32) collate utf8_unicode_ci default '',
  `payment_lastname` varchar(32) collate utf8_unicode_ci default '',
  `payment_company` varchar(32) collate utf8_unicode_ci default NULL,
  `payment_address_1` varchar(64) collate utf8_unicode_ci default '',
  `payment_address_2` varchar(32) collate utf8_unicode_ci default NULL,
  `payment_city` varchar(32) collate utf8_unicode_ci default '',
  `payment_postcode` varchar(10) collate utf8_unicode_ci default '',
  `payment_zone` varchar(32) collate utf8_unicode_ci default NULL,
  `payment_country` varchar(64) collate utf8_unicode_ci default '',
  `payment_address_format` text collate utf8_unicode_ci,
  `payment_method` varchar(128) collate utf8_unicode_ci default '',
  `total` decimal(15,4) DEFAULT '0.0000',
  `order_status_id` int(11) DEFAULT '0',
  `currency` varchar(3) collate utf8_unicode_ci default NULL,
  `value` decimal(14,6) DEFAULT NULL,
  `ip` varchar(39) collate utf8_unicode_ci default '',
  `coupon_sort_order` int(3) DEFAULT '0',
  `discount_sort_order` int(3) DEFAULT '0',
  `shipping_tax_rate` decimal(15,4) DEFAULT '0.0000',
  `freeshipping_net` decimal(15,4) DEFAULT '0.0000',
  `shipping_net` decimal(15,4) DEFAULT '0.0000',
  `taxed` tinyint(1) default '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `order_insertTrigger`;
CREATE TRIGGER `order_insertTrigger` AFTER INSERT ON `order` FOR EACH ROW INSERT delayed INTO order_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_id, customer_id, reference, modified, new_reference, invoice_number, firstname, lastname, fax, email, shipping_firstname, shipping_lastname, shipping_company, shipping_address_1, shipping_address_2, shipping_city, shipping_postcode, shipping_zone, shipping_country, shipping_address_format, shipping_method, payment_firstname, payment_lastname, payment_company, payment_address_1, payment_address_2, payment_city, payment_postcode, payment_zone, payment_country, payment_address_format, payment_method, total, order_status_id, currency, value, ip, coupon_sort_order, discount_sort_order, shipping_tax_rate, freeshipping_net, shipping_net, taxed, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.order_id, NEW.customer_id, NEW.reference, NEW.modified, NEW.new_reference, NEW.invoice_number, NEW.firstname, NEW.lastname, NEW.fax, NEW.email, NEW.shipping_firstname, NEW.shipping_lastname, NEW.shipping_company, NEW.shipping_address_1, NEW.shipping_address_2, NEW.shipping_city, NEW.shipping_postcode, NEW.shipping_zone, NEW.shipping_country, NEW.shipping_address_format, NEW.shipping_method, NEW.payment_firstname, NEW.payment_lastname, NEW.payment_company, NEW.payment_address_1, NEW.payment_address_2, NEW.payment_city, NEW.payment_postcode, NEW.payment_zone, NEW.payment_country, NEW.payment_address_format, NEW.payment_method, NEW.total, NEW.order_status_id, NEW.currency, NEW.value, NEW.ip, NEW.coupon_sort_order, NEW.discount_sort_order, NEW.shipping_tax_rate, NEW.freeshipping_net, NEW.shipping_net, NEW.taxed, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `order_updateTrigger`;
CREATE TRIGGER `order_updateTrigger` AFTER UPDATE ON `order` FOR EACH ROW INSERT delayed INTO order_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_id, customer_id, reference, modified, new_reference, invoice_number, firstname, lastname, fax, email, shipping_firstname, shipping_lastname, shipping_company, shipping_address_1, shipping_address_2, shipping_city, shipping_postcode, shipping_zone, shipping_country, shipping_address_format, shipping_method, payment_firstname, payment_lastname, payment_company, payment_address_1, payment_address_2, payment_city, payment_postcode, payment_zone, payment_country, payment_address_format, payment_method, total, order_status_id, currency, value, ip, coupon_sort_order, discount_sort_order, shipping_tax_rate, freeshipping_net, shipping_net, taxed, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.order_id, NEW.customer_id, NEW.reference, NEW.modified, NEW.new_reference, NEW.invoice_number, NEW.firstname, NEW.lastname, NEW.fax, NEW.email, NEW.shipping_firstname, NEW.shipping_lastname, NEW.shipping_company, NEW.shipping_address_1, NEW.shipping_address_2, NEW.shipping_city, NEW.shipping_postcode, NEW.shipping_zone, NEW.shipping_country, NEW.shipping_address_format, NEW.shipping_method, NEW.payment_firstname, NEW.payment_lastname, NEW.payment_company, NEW.payment_address_1, NEW.payment_address_2, NEW.payment_city, NEW.payment_postcode, NEW.payment_zone, NEW.payment_country, NEW.payment_address_format, NEW.payment_method, NEW.total, NEW.order_status_id, NEW.currency, NEW.value, NEW.ip, NEW.coupon_sort_order, NEW.discount_sort_order, NEW.shipping_tax_rate, NEW.freeshipping_net, NEW.shipping_net, NEW.taxed, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `order_deleteTrigger`;
CREATE TRIGGER `order_deleteTrigger` BEFORE DELETE ON `order` FOR EACH ROW INSERT delayed INTO order_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_id, customer_id, reference, modified, new_reference, invoice_number, firstname, lastname, fax, email, shipping_firstname, shipping_lastname, shipping_company, shipping_address_1, shipping_address_2, shipping_city, shipping_postcode, shipping_zone, shipping_country, shipping_address_format, shipping_method, payment_firstname, payment_lastname, payment_company, payment_address_1, payment_address_2, payment_city, payment_postcode, payment_zone, payment_country, payment_address_format, payment_method, total, order_status_id, currency, value, ip, coupon_sort_order, discount_sort_order, shipping_tax_rate, freeshipping_net, shipping_net, taxed, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.order_id, OLD.customer_id, OLD.reference, OLD.modified, OLD.new_reference, OLD.invoice_number, OLD.firstname, OLD.lastname, OLD.fax, OLD.email, OLD.shipping_firstname, OLD.shipping_lastname, OLD.shipping_company, OLD.shipping_address_1, OLD.shipping_address_2, OLD.shipping_city, OLD.shipping_postcode, OLD.shipping_zone, OLD.shipping_country, OLD.shipping_address_format, OLD.shipping_method, OLD.payment_firstname, OLD.payment_lastname, OLD.payment_company, OLD.payment_address_1, OLD.payment_address_2, OLD.payment_city, OLD.payment_postcode, OLD.payment_zone, OLD.payment_country, OLD.payment_address_format, OLD.payment_method, OLD.total, OLD.order_status_id, OLD.currency, OLD.value, OLD.ip, OLD.coupon_sort_order, OLD.discount_sort_order, OLD.shipping_tax_rate, OLD.freeshipping_net, OLD.shipping_net, OLD.taxed, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to order_total table
#
ALTER TABLE `order_total` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `order_total` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in order_total table
#
CREATE TABLE IF NOT EXISTS `order_total_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `order_total_id` int(10) DEFAULT '0',
  `order_id` int(11) default '0',
  `title` varchar(255) collate utf8_unicode_ci default '',
  `text` varchar(255) collate utf8_unicode_ci default '',
  `value` decimal(15,4) DEFAULT '0.0000',
  `sort_order` int(11) default '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `order_total_insertTrigger`;
CREATE TRIGGER `order_total_insertTrigger` AFTER INSERT ON `order_total` FOR EACH ROW INSERT delayed INTO order_total_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_total_id, order_id, title, text, value, sort_order, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.order_total_id, NEW.order_id, NEW.title, NEW.text, NEW.value, NEW.sort_order, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `order_total_updateTrigger`;
CREATE TRIGGER `order_total_updateTrigger` AFTER UPDATE ON `order_total` FOR EACH ROW INSERT delayed INTO order_total_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_total_id, order_id, title, text, value, sort_order, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.order_total_id, NEW.order_id, NEW.title, NEW.text, NEW.value, NEW.sort_order, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `order_total_deleteTrigger`;
CREATE TRIGGER `order_total_deleteTrigger` BEFORE DELETE ON `order_total` FOR EACH ROW INSERT delayed INTO order_total_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_total_id, order_id, title, text, value, sort_order, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.order_total_id, OLD.order_id, OLD.title, OLD.text, OLD.value, OLD.sort_order, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to order_option table
#
ALTER TABLE `order_option` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `order_option` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in order_option table
#
CREATE TABLE IF NOT EXISTS `order_option_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `order_option_id` int(11) DEFAULT '0',
  `order_id` int(11) default '0',
  `order_product_id` int(11) default '0',
  `option_id` int(11) default '0',
  `name` varchar(32) collate utf8_unicode_ci default '',
  `option_value_id` int(11) default '0',
  `value` varchar(32) collate utf8_unicode_ci default '',
  `price` decimal(15,4) DEFAULT '0.0000',
  `prefix` char(1) default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `order_option_insertTrigger`;
CREATE TRIGGER `order_option_insertTrigger` AFTER INSERT ON `order_option` FOR EACH ROW INSERT delayed INTO order_option_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_option_id, order_id, order_product_id, option_id, name, option_value_id, value, price, prefix, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.order_option_id, NEW.order_id, NEW.order_product_id, NEW.option_id, NEW.name, NEW.option_value_id, NEW.value, NEW.price, NEW.prefix, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `order_option_updateTrigger`;
CREATE TRIGGER `order_option_updateTrigger` AFTER UPDATE ON `order_option` FOR EACH ROW INSERT delayed INTO order_option_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_option_id, order_id, order_product_id, option_id, name, option_value_id, value, price, prefix, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.order_option_id, NEW.order_id, NEW.order_product_id, NEW.option_id, NEW.name, NEW.option_value_id, NEW.value, NEW.price, NEW.prefix, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `order_option_deleteTrigger`;
CREATE TRIGGER `order_option_deleteTrigger` BEFORE DELETE ON `order_option` FOR EACH ROW INSERT delayed INTO order_option_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_option_id, order_id, order_product_id, option_id, name, option_value_id, value, price, prefix, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.order_option_id, OLD.order_id, OLD.order_product_id, OLD.option_id, OLD.name, OLD.option_value_id, OLD.value, OLD.price, OLD.prefix, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to order_download table
#
ALTER TABLE `order_download` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `order_download` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in order_download table
#
CREATE TABLE IF NOT EXISTS `order_download_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `order_download_id` int(11) DEFAULT '0',
  `order_id` int(11) default '0',
  `order_product_id` int(11) default '0',
  `name` varchar(64) collate utf8_unicode_ci default '',
  `filename` varchar(128) collate utf8_unicode_ci default '',
  `mask` varchar(128) collate utf8_unicode_ci default '',
  `remaining` int(3) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `order_download_insertTrigger`;
CREATE TRIGGER `order_download_insertTrigger` AFTER INSERT ON `order_download` FOR EACH ROW INSERT delayed INTO order_download_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_download_id, order_id, order_product_id, name, filename, mask, remaining, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.order_download_id, NEW.order_id, NEW.order_product_id, NEW.name, NEW.filename, NEW.mask, NEW.remaining, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `order_download_updateTrigger`;
CREATE TRIGGER `order_download_updateTrigger` AFTER UPDATE ON `order_download` FOR EACH ROW INSERT delayed INTO order_download_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_download_id, order_id, order_product_id, name, filename, mask, remaining, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.order_download_id, NEW.order_id, NEW.order_product_id, NEW.name, NEW.filename, NEW.mask, NEW.remaining, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `order_download_deleteTrigger`;
CREATE TRIGGER `order_download_deleteTrigger` BEFORE DELETE ON `order_download` FOR EACH ROW INSERT delayed INTO order_download_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_download_id, order_id, order_product_id, name, filename, mask, remaining, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.order_download_id, OLD.order_id, OLD.order_product_id, OLD.name, OLD.filename, OLD.mask, OLD.remaining, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to order_google table
#
ALTER TABLE `order_google` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `order_google` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in order_google table
#
CREATE TABLE IF NOT EXISTS `order_google_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `order_reference` varchar(32) collate utf8_unicode_ci DEFAULT '',
  `order_number` varchar(30) collate utf8_unicode_ci DEFAULT '',
  `total` decimal(14,6),
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `order_google_insertTrigger`;
CREATE TRIGGER `order_google_insertTrigger` AFTER INSERT ON `order_google` FOR EACH ROW INSERT delayed INTO order_google_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_reference, order_number, total, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.order_reference, NEW.order_number, NEW.total, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `order_google_updateTrigger`;
CREATE TRIGGER `order_google_updateTrigger` AFTER UPDATE ON `order_google` FOR EACH ROW INSERT delayed INTO order_google_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_reference, order_number, total, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.order_reference, NEW.order_number, NEW.total, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `order_google_deleteTrigger`;
CREATE TRIGGER `order_google_deleteTrigger` BEFORE DELETE ON `order_google` FOR EACH ROW INSERT delayed INTO order_google_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_reference, order_number, total, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.order_reference, OLD.order_number, OLD.total, OLD.date_added, OLD.date_modified);

#
# Alter order_history table
#
ALTER TABLE `order_history` CHANGE `date_added` `date_added` datetime NOT NULL default '1000-01-01 00:00:00' AFTER `comment`;

#
# Add date_modified to order_history table
#
ALTER TABLE `order_history` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in order_history table
#
CREATE TABLE IF NOT EXISTS `order_history_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `order_history_id` int(11) DEFAULT '0',
  `order_id` int(11) default '0',
  `order_status_id` int(11) default '0',
  `notify` int(1) DEFAULT '0',
  `comment` text collate utf8_unicode_ci,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `order_history_insertTrigger`;
CREATE TRIGGER `order_history_insertTrigger` AFTER INSERT ON `order_history` FOR EACH ROW INSERT delayed INTO order_history_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_history_id, order_id, order_status_id, notify, comment, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.order_history_id, NEW.order_id, NEW.order_status_id, NEW.notify, NEW.comment, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `order_history_updateTrigger`;
CREATE TRIGGER `order_history_updateTrigger` AFTER UPDATE ON `order_history` FOR EACH ROW INSERT delayed INTO order_history_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_history_id, order_id, order_status_id, notify, comment, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.order_history_id, NEW.order_id, NEW.order_status_id, NEW.notify, NEW.comment, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `order_history_deleteTrigger`;
CREATE TRIGGER `order_history_deleteTrigger` BEFORE DELETE ON `order_history` FOR EACH ROW INSERT delayed INTO order_history_log (trigger_action, trigger_modifier_id, trigger_modifier_title, order_history_id, order_id, order_status_id, notify, comment, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.order_history_id, OLD.order_id, OLD.order_status_id, OLD.notify, OLD.comment, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to maintenance_description table
#
ALTER TABLE `maintenance_description` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `maintenance_description` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in maintenance_description table
#
CREATE TABLE IF NOT EXISTS `maintenance_description_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `maintenance_id` int(11) DEFAULT '0',
  `language_id` int(11) DEFAULT '0',
  `header` varchar(64) collate utf8_unicode_ci default '',
  `description` text collate utf8_unicode_ci,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `maintenance_description_insertTrigger`;
CREATE TRIGGER `maintenance_description_insertTrigger` AFTER INSERT ON `maintenance_description` FOR EACH ROW INSERT delayed INTO maintenance_description_log (trigger_action, trigger_modifier_id, trigger_modifier_title, maintenance_id, language_id, header, description, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.maintenance_id, NEW.language_id, NEW.header, NEW.description, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `maintenance_description_updateTrigger`;
CREATE TRIGGER `maintenance_description_updateTrigger` AFTER UPDATE ON `maintenance_description` FOR EACH ROW INSERT delayed INTO maintenance_description_log (trigger_action, trigger_modifier_id, trigger_modifier_title, maintenance_id, language_id, header, description, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.maintenance_id, NEW.language_id, NEW.header, NEW.description, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `maintenance_description_deleteTrigger`;
CREATE TRIGGER `maintenance_description_deleteTrigger` BEFORE DELETE ON `maintenance_description` FOR EACH ROW INSERT delayed INTO maintenance_description_log (trigger_action, trigger_modifier_id, trigger_modifier_title, maintenance_id, language_id, header, description, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.maintenance_id, OLD.language_id, OLD.header, OLD.description, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to setting table
#
ALTER TABLE `setting` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `setting` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in setting table
#
CREATE TABLE IF NOT EXISTS `setting_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `setting_id` int(11) DEFAULT '0',
  `type` varchar(12) collate utf8_unicode_ci default '',
  `group` varchar(32) collate utf8_unicode_ci default '',
  `key` varchar(64) collate utf8_unicode_ci default '',
  `value` varchar(768) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `setting_insertTrigger`;
CREATE TRIGGER `setting_insertTrigger` AFTER INSERT ON `setting` FOR EACH ROW INSERT delayed INTO setting_log (trigger_action, trigger_modifier_id, trigger_modifier_title, setting_id, type, `group`, `key`, `value`, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.setting_id, NEW.type, NEW.group, NEW.key, NEW.value, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `setting_updateTrigger`;
CREATE TRIGGER `setting_updateTrigger` AFTER UPDATE ON `setting` FOR EACH ROW INSERT delayed INTO setting_log (trigger_action, trigger_modifier_id, trigger_modifier_title, setting_id, type, `group`, `key`, `value`, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.setting_id, NEW.type, NEW.group, NEW.key, NEW.value, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `setting_deleteTrigger`;
CREATE TRIGGER `setting_deleteTrigger` BEFORE DELETE ON `setting` FOR EACH ROW INSERT delayed INTO setting_log (trigger_action, trigger_modifier_id, trigger_modifier_title, setting_id, type, `group`, `key`, `value`, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.setting_id, OLD.type, OLD.group, OLD.key, OLD.value, OLD.date_added, OLD.date_modified);

#
# Alter coupon table
#
ALTER TABLE `coupon` CHANGE `date_added` `date_added` datetime NOT NULL default '1000-01-01 00:00:00';

#
# Add date_modified to coupon table
#
ALTER TABLE `coupon` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in coupon table
#
CREATE TABLE IF NOT EXISTS `coupon_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `coupon_id` int(11) DEFAULT '0',
  `code` varchar(10) collate utf8_unicode_ci DEFAULT '',
  `discount` decimal(15,4),
  `prefix` varchar(1) collate utf8_unicode_ci DEFAULT '',
  `minimum_order` decimal(15,4),
  `shipping` int(1) DEFAULT '0',
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `uses_total` int(11) DEFAULT '0',
  `uses_customer` varchar(11) collate utf8_unicode_ci DEFAULT '',
  `status` int(1) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `coupon_insertTrigger`;
CREATE TRIGGER `coupon_insertTrigger` AFTER INSERT ON `coupon` FOR EACH ROW INSERT delayed INTO setting_log (trigger_action, trigger_modifier_id, trigger_modifier_title, coupon_id, code, discount, prefix, minimum_order, shipping, date_start, date_end, uses_total, uses_customer, status, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.coupon_id, NEW.code, NEW.discount, NEW.prefix, NEW.minimum_order, NEW.shipping, NEW.date_start, NEW.date_end, NEW.uses_total, NEW.uses_customer, NEW.status, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `coupon_updateTrigger`;
CREATE TRIGGER `coupon_updateTrigger` AFTER UPDATE ON `coupon` FOR EACH ROW INSERT delayed INTO setting_log (trigger_action, trigger_modifier_id, trigger_modifier_title, coupon_id, code, discount, prefix, minimum_order, shipping, date_start, date_end, uses_total, uses_customer, status, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.coupon_id, NEW.code, NEW.discount, NEW.prefix, NEW.minimum_order, NEW.shipping, NEW.date_start, NEW.date_end, NEW.uses_total, NEW.uses_customer, NEW.status, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `coupon_deleteTrigger`;
CREATE TRIGGER `coupon_deleteTrigger` BEFORE DELETE ON `coupon` FOR EACH ROW INSERT delayed INTO setting_log (trigger_action, trigger_modifier_id, trigger_modifier_title, coupon_id, code, discount, prefix, minimum_order, shipping, date_start, date_end, uses_total, uses_customer, status, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.coupon_id, OLD.code, OLD.discount, OLD.prefix, OLD.minimum_order, OLD.shipping, OLD.date_start, OLD.date_end, OLD.uses_total, OLD.uses_customer, OLD.status, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to coupon_description table
#
ALTER TABLE `coupon_description` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `coupon_description` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in coupon_description table
#
CREATE TABLE IF NOT EXISTS `coupon_description_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `coupon_id` int(11) DEFAULT '0',
  `language_id` int(11) DEFAULT '0',
  `name` varchar(128) collate utf8_unicode_ci default '',
  `description` text collate utf8_unicode_ci,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `coupon_description_insertTrigger`;
CREATE TRIGGER `coupon_description_insertTrigger` AFTER INSERT ON `coupon_description` FOR EACH ROW INSERT delayed INTO coupon_description_log (trigger_action, trigger_modifier_id, trigger_modifier_title, coupon_id, language_id, name, description, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.coupon_id, NEW.language_id, NEW.name, NEW.description, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `coupon_description_updateTrigger`;
CREATE TRIGGER `coupon_description_updateTrigger` AFTER UPDATE ON `coupon_description` FOR EACH ROW INSERT delayed INTO coupon_description_log (trigger_action, trigger_modifier_id, trigger_modifier_title, coupon_id, language_id, name, description, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.coupon_id, NEW.language_id, NEW.name, NEW.description, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `coupon_description_deleteTrigger`;
CREATE TRIGGER `coupon_description_deleteTrigger` BEFORE DELETE ON `coupon_description` FOR EACH ROW INSERT delayed INTO coupon_description_log (trigger_action, trigger_modifier_id, trigger_modifier_title, coupon_id, language_id, name, description, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.coupon_id, OLD.language_id, OLD.name, OLD.description, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to coupon_product table
#
ALTER TABLE `coupon_product` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `coupon_product` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in coupon_product table
#
CREATE TABLE IF NOT EXISTS `coupon_product_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `coupon_id` int(11) DEFAULT '0',
  `product_id` int(11) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `coupon_product_insertTrigger`;
CREATE TRIGGER `coupon_product_insertTrigger` AFTER INSERT ON `coupon_product` FOR EACH ROW INSERT delayed INTO coupon_product_log (trigger_action, trigger_modifier_id, trigger_modifier_title, coupon_id, product_id, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.coupon_id, NEW.product_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `coupon_product_updateTrigger`;
CREATE TRIGGER `coupon_product_updateTrigger` AFTER UPDATE ON `coupon_product` FOR EACH ROW INSERT delayed INTO coupon_product_log (trigger_action, trigger_modifier_id, trigger_modifier_title, coupon_id, product_id, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.coupon_id, NEW.product_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `coupon_product_deleteTrigger`;
CREATE TRIGGER `coupon_product_deleteTrigger` BEFORE DELETE ON `coupon_product` FOR EACH ROW INSERT delayed INTO coupon_product_log (trigger_action, trigger_modifier_id, trigger_modifier_title, coupon_id, product_id, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.coupon_id, OLD.product_id, OLD.date_added, OLD.date_modified);

#
# Alter coupon_redeem table
#
ALTER TABLE `coupon_redeem` CHANGE `date_added` `date_added` datetime NOT NULL default '1000-01-01 00:00:00' AFTER `coupon_id`;

#
# Add date_modified to coupon_redeem table
#
ALTER TABLE `coupon_redeem` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in coupon_redeem table
#
CREATE TABLE IF NOT EXISTS `coupon_redeem_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `coupon_redeem_id` int(11) DEFAULT '0',
  `customer_id` int(11) DEFAULT '0',
  `order_id` int(11) DEFAULT '0',
  `coupon_id` int(11) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `coupon_redeem_insertTrigger`;
CREATE TRIGGER `coupon_redeem_insertTrigger` AFTER INSERT ON `coupon_redeem` FOR EACH ROW INSERT delayed INTO coupon_redeem_log (trigger_action, trigger_modifier_id, trigger_modifier_title, coupon_redeem_id, customer_id, order_id, coupon_id, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.coupon_redeem_id, NEW.customer_id, NEW.order_id, NEW.coupon_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `coupon_redeem_updateTrigger`;
CREATE TRIGGER `coupon_redeem_updateTrigger` AFTER UPDATE ON `coupon_redeem` FOR EACH ROW INSERT delayed INTO coupon_redeem_log (trigger_action, trigger_modifier_id, trigger_modifier_title, coupon_redeem_id, customer_id, order_id, coupon_id, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.coupon_redeem_id, NEW.customer_id, NEW.order_id, NEW.coupon_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `coupon_redeem_deleteTrigger`;
CREATE TRIGGER `coupon_redeem_deleteTrigger` BEFORE DELETE ON `coupon_redeem` FOR EACH ROW INSERT delayed INTO coupon_redeem_log (trigger_action, trigger_modifier_id, trigger_modifier_title, coupon_redeem_id, customer_id, order_id, coupon_id, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.coupon_redeem_id, OLD.customer_id, OLD.order_id, OLD.coupon_id, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to related_products table
#
ALTER TABLE `related_products` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `related_products` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in related_products table
#
CREATE TABLE IF NOT EXISTS `related_products_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `product_id` int(11) DEFAULT '0',
  `related_product_id` int(11) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `related_products_insertTrigger`;
CREATE TRIGGER `related_products_insertTrigger` AFTER INSERT ON `related_products` FOR EACH ROW INSERT delayed INTO related_products_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, related_product_id, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.product_id, NEW.related_product_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `related_products_updateTrigger`;
CREATE TRIGGER `related_products_updateTrigger` AFTER UPDATE ON `related_products` FOR EACH ROW INSERT delayed INTO related_products_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, related_product_id, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.product_id, NEW.related_product_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `related_products_deleteTrigger`;
CREATE TRIGGER `related_products_deleteTrigger` BEFORE DELETE ON `related_products` FOR EACH ROW INSERT delayed INTO related_products_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, related_product_id, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.product_id, OLD.related_product_id, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to product_to_image table
#
ALTER TABLE `product_to_image` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `product_to_image` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in product_to_image table
#
CREATE TABLE IF NOT EXISTS `product_to_image_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `product_id` int(11) DEFAULT '0',
  `image_id` int(11) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `product_to_image_insertTrigger`;
CREATE TRIGGER `product_to_image_insertTrigger` AFTER INSERT ON `product_to_image` FOR EACH ROW INSERT delayed INTO product_to_image_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, image_id, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.product_id, NEW.image_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_to_image_updateTrigger`;
CREATE TRIGGER `product_to_image_updateTrigger` AFTER UPDATE ON `product_to_image` FOR EACH ROW INSERT delayed INTO product_to_image_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, image_id, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.product_id, NEW.image_id, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_to_image_deleteTrigger`;
CREATE TRIGGER `product_to_image_deleteTrigger` BEFORE DELETE ON `product_to_image` FOR EACH ROW INSERT delayed INTO product_to_image_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, image_id, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.product_id, OLD.image_id, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to product_to_download table
#
ALTER TABLE `product_to_download` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `product_to_download` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in product_to_download table
#
CREATE TABLE IF NOT EXISTS `product_to_download_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `product_id` int(11) DEFAULT '0',
  `download_id` int(11) DEFAULT '0',
  `free` int(1) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `product_to_download_insertTrigger`;
CREATE TRIGGER `product_to_download_insertTrigger` AFTER INSERT ON `product_to_download` FOR EACH ROW INSERT delayed INTO product_to_download_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, download_id, free, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.product_id, NEW.download_id, NEW.free, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_to_download_updateTrigger`;
CREATE TRIGGER `product_to_download_updateTrigger` AFTER UPDATE ON `product_to_download` FOR EACH ROW INSERT delayed INTO product_to_download_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, download_id, free, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.product_id, NEW.download_id, NEW.free, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_to_download_deleteTrigger`;
CREATE TRIGGER `product_to_download_deleteTrigger` BEFORE DELETE ON `product_to_download` FOR EACH ROW INSERT delayed INTO product_to_download_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, download_id, free, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.product_id, OLD.download_id, OLD.free, OLD.date_added, OLD.date_modified);

#
# Add date_modified to image table
#
ALTER TABLE `image` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in image table
#
CREATE TABLE IF NOT EXISTS `image_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `image_id` int(11) DEFAULT '0',
  `filename` varchar(128) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `image_insertTrigger`;
CREATE TRIGGER `image_insertTrigger` AFTER INSERT ON `image` FOR EACH ROW INSERT delayed INTO image_log (trigger_action, trigger_modifier_id, trigger_modifier_title, image_id, filename, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.image_id, NEW.filename, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `image_updateTrigger`;
CREATE TRIGGER `image_updateTrigger` AFTER UPDATE ON `image` FOR EACH ROW INSERT delayed INTO image_log (trigger_action, trigger_modifier_id, trigger_modifier_title, image_id, filename, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.image_id, NEW.filename, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `image_deleteTrigger`;
CREATE TRIGGER `image_deleteTrigger` BEFORE DELETE ON `image` FOR EACH ROW INSERT delayed INTO image_log (trigger_action, trigger_modifier_id, trigger_modifier_title, image_id, filename, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.image_id, OLD.filename, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to image_description table
#
ALTER TABLE `image_description` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `image_description` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in image_description table
#
CREATE TABLE IF NOT EXISTS `image_description_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `image_id` int(11) DEFAULT '0',
  `language_id` int(11) DEFAULT '0',
  `title` varchar(64) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `image_description_insertTrigger`;
CREATE TRIGGER `image_description_insertTrigger` AFTER INSERT ON `image_description` FOR EACH ROW INSERT delayed INTO `image_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, image_id, language_id, title, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.image_id, NEW.language_id, NEW.title, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `image_description_updateTrigger`;
CREATE TRIGGER `image_description_updateTrigger` AFTER UPDATE ON `image_description` FOR EACH ROW INSERT delayed INTO `image_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, image_id, language_id, title, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.image_id, NEW.language_id, NEW.title, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `image_description_deleteTrigger`;
CREATE TRIGGER `image_description_deleteTrigger` BEFORE DELETE ON `image_description` FOR EACH ROW INSERT delayed INTO `image_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, image_id, language_id, title, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.image_id, OLD.language_id, OLD.title, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to weight_class table
#
ALTER TABLE `weight_class` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `weight_class` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in weight_class table
#
CREATE TABLE IF NOT EXISTS `weight_class_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `weight_class_id` int(11) DEFAULT '0',
  `unit` varchar(4) collate utf8_unicode_ci default '',
  `language_id` int(11) DEFAULT '0',
  `title` varchar(32) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `weight_class_insertTrigger`;
CREATE TRIGGER `weight_class_insertTrigger` AFTER INSERT ON `weight_class` FOR EACH ROW INSERT delayed INTO `weight_class_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, weight_class_id, unit, language_id, title, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.weight_class_id, NEW.unit, NEW.language_id, NEW.title, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `weight_class_updateTrigger`;
CREATE TRIGGER `weight_class_updateTrigger` AFTER UPDATE ON `weight_class` FOR EACH ROW INSERT delayed INTO `weight_class_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, weight_class_id, unit, language_id, title, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.weight_class_id, NEW.unit, NEW.language_id, NEW.title, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `weight_class_deleteTrigger`;
CREATE TRIGGER `weight_class_deleteTrigger` BEFORE DELETE ON `weight_class` FOR EACH ROW INSERT delayed INTO `weight_class_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, weight_class_id, unit, language_id, title, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.weight_class_id, OLD.unit, OLD.language_id, OLD.title, OLD.date_added, OLD.date_modified);

#
# Add primary key to weight_rule table
#
ALTER TABLE `weight_rule` ADD `weight_rule_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

#
# Add date_added and date_modified to weight_rule table
#
ALTER TABLE `weight_rule` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `weight_rule` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in weight_rule table
#
CREATE TABLE IF NOT EXISTS `weight_rule_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `weight_rule_id` int(11) DEFAULT '0',
  `from_id` int(11) DEFAULT '0',
  `to_id` int(11) DEFAULT '0',
  `rule` decimal(15,4),
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `weight_rule_insertTrigger`;
CREATE TRIGGER `weight_rule_insertTrigger` AFTER INSERT ON `weight_rule` FOR EACH ROW INSERT delayed INTO `weight_rule_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, weight_rule_id, from_id, to_id, rule, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.weight_rule_id, NEW.from_id, NEW.to_id, NEW.rule, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `weight_rule_updateTrigger`;
CREATE TRIGGER `weight_rule_updateTrigger` AFTER UPDATE ON `weight_rule` FOR EACH ROW INSERT delayed INTO `weight_rule_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, weight_rule_id, from_id, to_id, rule, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.weight_rule_id, NEW.from_id, NEW.to_id, NEW.rule, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `weight_rule_deleteTrigger`;
CREATE TRIGGER `weight_rule_deleteTrigger` BEFORE DELETE ON `weight_rule` FOR EACH ROW INSERT delayed INTO `weight_rule_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, weight_rule_id, from_id, to_id, rule, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.weight_rule_id, OLD.from_id, OLD.to_id, OLD.rule, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to dimension table
#
ALTER TABLE `dimension` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `dimension` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in dimension table
#
CREATE TABLE IF NOT EXISTS `dimension_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `dimension_id` int(11) DEFAULT '0',
  `unit` varchar(24) collate utf8_unicode_ci default '',
  `type_id` int(11) DEFAULT '0',
  `language_id` int(11) DEFAULT '0',
  `title` varchar(32) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `dimension_insertTrigger`;
CREATE TRIGGER `dimension_insertTrigger` AFTER INSERT ON `dimension` FOR EACH ROW INSERT delayed INTO `dimension_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, dimension_id, unit, type_id, language_id, title, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.dimension_id, NEW.unit, NEW.type_id, NEW.language_id, NEW.title, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `dimension_updateTrigger`;
CREATE TRIGGER `dimension_updateTrigger` AFTER UPDATE ON `dimension` FOR EACH ROW INSERT delayed INTO `dimension_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, dimension_id, unit, type_id, language_id, title, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.dimension_id, NEW.unit, NEW.type_id, NEW.language_id, NEW.title, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `dimension_deleteTrigger`;
CREATE TRIGGER `dimension_deleteTrigger` BEFORE DELETE ON `dimension` FOR EACH ROW INSERT delayed INTO `dimension_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, dimension_id, unit, type_id, language_id, title, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.dimension_id, OLD.unit, OLD.type_id, OLD.language_id, OLD.title, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to dimension_rule table
#
ALTER TABLE `dimension_rule` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `dimension_rule` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in dimension_rule table
#
CREATE TABLE IF NOT EXISTS `dimension_rule_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `dimension_rule_id` int(11) DEFAULT '0',
  `type_id` int(11) DEFAULT '0',
  `from_id` int(11) DEFAULT '0',
  `to_id` int(11) DEFAULT '0',
  `rule` decimal(17,6),
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `dimension_rule_insertTrigger`;
CREATE TRIGGER `dimension_rule_insertTrigger` AFTER INSERT ON `dimension_rule` FOR EACH ROW INSERT delayed INTO `dimension_rule_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, dimension_rule_id, type_id, from_id, to_id, rule, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.dimension_rule_id,  NEW.type_id, NEW.from_id, NEW.to_id, NEW.rule, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `dimension_rule_updateTrigger`;
CREATE TRIGGER `dimension_rule_updateTrigger` AFTER UPDATE ON `dimension_rule` FOR EACH ROW INSERT delayed INTO `dimension_rule_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, dimension_rule_id, type_id, from_id, to_id, rule, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.dimension_rule_id,  NEW.type_id, NEW.from_id, NEW.to_id, NEW.rule, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `dimension_rule_deleteTrigger`;
CREATE TRIGGER `dimension_rule_deleteTrigger` BEFORE DELETE ON `dimension_rule` FOR EACH ROW INSERT delayed INTO `dimension_rule_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, dimension_rule_id, type_id, from_id, to_id, rule, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.dimension_rule_id,  OLD.type_id, OLD.from_id, OLD.to_id, OL.rule, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to dimension_type table
#
ALTER TABLE `dimension_type` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `dimension_type` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in dimension_type table
#
CREATE TABLE IF NOT EXISTS `dimension_type_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `type_id` int(11) DEFAULT '0',
  `type_name` varchar(32) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `dimension_type_insertTrigger`;
CREATE TRIGGER `dimension_type_insertTrigger` AFTER INSERT ON `dimension_type` FOR EACH ROW INSERT delayed INTO `dimension_type_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, type_id, type_name, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.type_id, NEW.type_name, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `dimension_type_updateTrigger`;
CREATE TRIGGER `dimension_type_updateTrigger` AFTER UPDATE ON `dimension_type` FOR EACH ROW INSERT delayed INTO `dimension_type_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, type_id, type_name, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.type_id, NEW.type_name, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `dimension_type_deleteTrigger`;
CREATE TRIGGER `dimension_type_deleteTrigger` BEFORE DELETE ON `dimension_type` FOR EACH ROW INSERT delayed INTO `dimension_type_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, type_id, type_name, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.type_id, OLD.type_name, OLD.date_added, OLD.date_modified);

#
# Add date_modified to download table
#
ALTER TABLE `download` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in download table
#
CREATE TABLE IF NOT EXISTS `download_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `download_id` int(11) DEFAULT '0',
  `filename` varchar(128) collate utf8_unicode_ci default '',
  `mask` varchar(128) collate utf8_unicode_ci default '',
  `remaining` int(11) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `download_insertTrigger`;
CREATE TRIGGER `download_insertTrigger` AFTER INSERT ON `download` FOR EACH ROW INSERT delayed INTO `download_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, download_id, filename, mask, remaining, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.download_id, NEW.filename, NEW.mask, NEW.remaining, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `download_updateTrigger`;
CREATE TRIGGER `download_updateTrigger` AFTER UPDATE ON `download` FOR EACH ROW INSERT delayed INTO `download_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, download_id, filename, mask, remaining, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.download_id, NEW.filename, NEW.mask, NEW.remaining, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `download_deleteTrigger`;
CREATE TRIGGER `download_deleteTrigger` BEFORE DELETE ON `download` FOR EACH ROW INSERT delayed INTO `download_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, download_id, filename, mask, remaining, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.download_id, OLD.filename, OLD.mask, OLD.remaining, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to download_description table
#
ALTER TABLE `download_description` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `download_description` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in download_description table
#
CREATE TABLE IF NOT EXISTS `download_description_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `download_id` int(11) DEFAULT '0',
  `language_id` int(11) DEFAULT '0',
  `name` varchar(64) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `download_description_insertTrigger`;
CREATE TRIGGER `download_description_insertTrigger` AFTER INSERT ON `download_description` FOR EACH ROW INSERT delayed INTO `download_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, download_id, language_id, name, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.download_id, NEW.language_id, NEW.name, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `download_description_updateTrigger`;
CREATE TRIGGER `download_description_updateTrigger` AFTER UPDATE ON `download_description` FOR EACH ROW INSERT delayed INTO `download_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, download_id, language_id, name, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.download_id, NEW.language_id, NEW.name, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `download_description_deleteTrigger`;
CREATE TRIGGER `download_description_deleteTrigger` BEFORE DELETE ON `download_description` FOR EACH ROW INSERT delayed INTO `download_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, download_id, language_id, name, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.download_id, OLD.language_id, OLD.name, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to extension table
#
ALTER TABLE `extension` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `extension` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in extension table
#
CREATE TABLE IF NOT EXISTS `extension_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `extension_id` int(11) DEFAULT '0',
  `code` varchar(32) collate utf8_unicode_ci default '',
  `type` varchar(32) collate utf8_unicode_ci default '',
  `directory` varchar(32) collate utf8_unicode_ci default '',
  `filename` varchar(128) collate utf8_unicode_ci default '',
  `controller` varchar(128) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `extension_insertTrigger`;
CREATE TRIGGER `extension_insertTrigger` AFTER INSERT ON `extension` FOR EACH ROW INSERT delayed INTO `extension_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, extension_id, code, type, directory, filename, controller, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.extension_id, NEW.code, NEW.type, NEW.directory, NEW.filename, NEW.controller, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `extension_updateTrigger`;
CREATE TRIGGER `extension_updateTrigger` AFTER UPDATE ON `extension` FOR EACH ROW INSERT delayed INTO `extension_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, extension_id, code, type, directory, filename, controller, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.extension_id, NEW.code, NEW.type, NEW.directory, NEW.filename, NEW.controller, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `extension_deleteTrigger`;
CREATE TRIGGER `extension_deleteTrigger` BEFORE DELETE ON `extension` FOR EACH ROW INSERT delayed INTO `extension_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, extension_id, code, type, directory, filename, controller, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.extension_id, OLD.code, OLD.type, OLD.directory, OLD.filename, OLD.controller, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to extension_description table
#
ALTER TABLE `extension_description` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `extension_description` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in extension_description table
#
CREATE TABLE IF NOT EXISTS `extension_description_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `extension_id` int(11) DEFAULT '0',
  `language_id` int(11) DEFAULT '0',
  `name` varchar(128) collate utf8_unicode_ci default '',
  `description` varchar(255) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `extension_description_insertTrigger`;
CREATE TRIGGER `extension_description_insertTrigger` AFTER INSERT ON `extension_description` FOR EACH ROW INSERT delayed INTO `extension_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, extension_id, language_id, name, description, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.extension_id, NEW.language_id, NEW.name, NEW.description, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `extension_description_updateTrigger`;
CREATE TRIGGER `extension_description_updateTrigger` AFTER UPDATE ON `extension_description` FOR EACH ROW INSERT delayed INTO `extension_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, extension_id, language_id, name, description, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.extension_id, NEW.language_id, NEW.name, NEW.description, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `extension_description_deleteTrigger`;
CREATE TRIGGER `extension_description_deleteTrigger` BEFORE DELETE ON `extension_description` FOR EACH ROW INSERT delayed INTO `extension_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, extension_id, language_id, name, description, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.extension_id, OLD.language_id, OLD.name, OLD.description, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to product_discount table
#
ALTER TABLE `product_discount` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `product_discount` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in product_discount table
#
CREATE TABLE IF NOT EXISTS `product_discount_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `product_discount_id` int(11) DEFAULT '0',
  `product_id` int(11) DEFAULT '0',
  `quantity` int(4) DEFAULT '0',
  `discount` decimal(15,4),
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `product_discount_insertTrigger`;
CREATE TRIGGER `product_discount_insertTrigger` AFTER INSERT ON `product_discount` FOR EACH ROW INSERT delayed INTO `product_discount_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, product_discount_id, product_id, quantity, discount, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.product_discount_id,  NEW.product_id, NEW.quantity, NEW.discount, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_discount_updateTrigger`;
CREATE TRIGGER `product_discount_updateTrigger` AFTER UPDATE ON `product_discount` FOR EACH ROW INSERT delayed INTO `product_discount_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, product_discount_id, product_id, quantity, discount, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.product_discount_id,  NEW.product_id, NEW.quantity, NEW.discount, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_discount_deleteTrigger`;
CREATE TRIGGER `product_discount_deleteTrigger` BEFORE DELETE ON `product_discount` FOR EACH ROW INSERT delayed INTO `product_discount_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, product_discount_id, product_id, quantity, discount, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.product_discount_id,  OLD.product_id, OLD.quantity, OLD.discount, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to home_page table
#
ALTER TABLE `home_page` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `home_page` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in home_page table
#
CREATE TABLE IF NOT EXISTS `home_page_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `home_id` int(11) DEFAULT '0',
  `name` varchar(64) collate utf8_unicode_ci default '',
  `status` int(1) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `home_page_insertTrigger`;
CREATE TRIGGER `home_page_insertTrigger` AFTER INSERT ON `home_page` FOR EACH ROW INSERT delayed INTO `home_page_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, home_id, name, status, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.home_id, NEW.name, NEW.status, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `home_page_updateTrigger`;
CREATE TRIGGER `home_page_updateTrigger` AFTER UPDATE ON `home_page` FOR EACH ROW INSERT delayed INTO `home_page_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, home_id, name, status, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.home_id, NEW.name, NEW.status, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `home_page_deleteTrigger`;
CREATE TRIGGER `home_page_deleteTrigger` BEFORE DELETE ON `home_page` FOR EACH ROW INSERT delayed INTO `home_page_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, home_id, name, status, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.home_id, OLD.name, OLD.status, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to home_slides table
#
ALTER TABLE `home_slides` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `home_slides` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in home_slides table
#
CREATE TABLE IF NOT EXISTS `home_slides_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `home_slide_id` int(11) DEFAULT '0',
  `home_id` int(11) DEFAULT '0',
  `language_id` int(11) DEFAULT '0',
  `image_id` int(11) DEFAULT '0',
  `sort_order` int(3) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `home_slides_insertTrigger`;
CREATE TRIGGER `home_slides_insertTrigger` AFTER INSERT ON `home_slides` FOR EACH ROW INSERT delayed INTO `home_slides_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, home_slide_id, home_id, language_id, image_id, sort_order, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.home_slide_id, NEW.home_id, NEW.language_id, NEW.image_id, NEW.sort_order, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `home_slides_updateTrigger`;
CREATE TRIGGER `home_slides_updateTrigger` AFTER UPDATE ON `home_slides` FOR EACH ROW INSERT delayed INTO `home_slides_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, home_slide_id, home_id, language_id, image_id, sort_order, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.home_slide_id, NEW.home_id, NEW.language_id, NEW.image_id, NEW.sort_order, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `home_slides_deleteTrigger`;
CREATE TRIGGER `home_slides_deleteTrigger` BEFORE DELETE ON `home_slides` FOR EACH ROW INSERT delayed INTO `home_slides_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, home_slide_id, home_id, language_id, image_id, sort_order, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.home_slide_id, OLD.home_id, OLD.language_id, OLD.image_id, OLD.sort_order, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to home_description table
#
ALTER TABLE `home_description` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `home_description` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in home_description table
#
CREATE TABLE IF NOT EXISTS `home_description_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `home_id` int(11) DEFAULT '0',
  `language_id` int(11) DEFAULT '1',
  `title` varchar(64) collate utf8_unicode_ci default '',
  `description` text collate utf8_unicode_ci,
  `welcome` varchar(510) collate utf8_unicode_ci default NULL,
  `meta_title` varchar(255) collate utf8_unicode_ci default NULL,
  `meta_description` varchar(512) collate utf8_unicode_ci default NULL,
  `meta_keywords` varchar(255) collate utf8_unicode_ci default NULL,
  `flash` varchar(128) collate utf8_unicode_ci default NULL,
  `flash_height` int(11) DEFAULT NULL,
  `flash_loop` int(11) DEFAULT NULL,
  `flash_width` int(11) DEFAULT NULL,
  `image_id` int(11) DEFAULT NULL,
  `run_times` int(11) DEFAULT '1',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `home_description_insertTrigger`;
CREATE TRIGGER `home_description_insertTrigger` AFTER INSERT ON `home_description` FOR EACH ROW INSERT delayed INTO `home_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, home_id, language_id, title, description, welcome, meta_title, meta_description, meta_keywords, flash, flash_height, flash_loop, flash_width, image_id, run_times, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.home_id, NEW.language_id, NEW.title, NEW.description, NEW.welcome, NEW.meta_title, NEW.meta_description, NEW.meta_keywords, NEW.flash, NEW.flash_height, NEW.flash_loop, NEW.flash_width, NEW.image_id, NEW.run_times, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `home_description_updateTrigger`;
CREATE TRIGGER `home_description_updateTrigger` AFTER UPDATE ON `home_description` FOR EACH ROW INSERT delayed INTO `home_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, home_id, language_id, title, description, welcome, meta_title, meta_description, meta_keywords, flash, flash_height, flash_loop, flash_width, image_id, run_times, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.home_id, NEW.language_id, NEW.title, NEW.description, NEW.welcome, NEW.meta_title, NEW.meta_description, NEW.meta_keywords, NEW.flash, NEW.flash_height, NEW.flash_loop, NEW.flash_width, NEW.image_id, NEW.run_times, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `home_description_deleteTrigger`;
CREATE TRIGGER `home_description_deleteTrigger` BEFORE DELETE ON `home_description` FOR EACH ROW INSERT delayed INTO `home_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, home_id, language_id, title, description, welcome, meta_title, meta_description, meta_keywords, flash, flash_height, flash_loop, flash_width, image_id, run_times, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.home_id, OLD.language_id, OLD.title, OLD.description, OLD.welcome, OLD.meta_title, OLD.meta_description, OLD.meta_keywords, OLD.flash, OLD.flash_height, OLD.flash_loop, OLD.flash_width, OLD.image_id, OLD.run_times, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to image_display table
#
ALTER TABLE `image_display` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `image_display` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in image_display table
#
CREATE TABLE IF NOT EXISTS `image_display_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `image_display_id` int(11) DEFAULT '0',
  `name` varchar(64) collate utf8_unicode_ci default '',
  `location_id` int(11) DEFAULT '0',
  `status` int(1) DEFAULT '0',
  `sort_order` int(11) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `image_display_insertTrigger`;
CREATE TRIGGER `image_display_insertTrigger` AFTER INSERT ON `image_display` FOR EACH ROW INSERT delayed INTO `image_display_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, image_display_id, name, location_id, status, sort_order, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.image_display_id, NEW.name, NEW.location_id, NEW.status, NEW.sort_order, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `image_display_updateTrigger`;
CREATE TRIGGER `image_display_updateTrigger` AFTER UPDATE ON `image_display` FOR EACH ROW INSERT delayed INTO `image_display_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, image_display_id, name, location_id, status, sort_order, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.image_display_id, NEW.name, NEW.location_id, NEW.status, NEW.sort_order, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `image_display_deleteTrigger`;
CREATE TRIGGER `image_display_deleteTrigger` BEFORE DELETE ON `image_display` FOR EACH ROW INSERT delayed INTO `image_display_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, image_display_id, name, location_id, status, sort_order, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.image_display_id, OLD.name, OLD.location_id, OLD.status, OLD.sort_order, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to image_display_slides table
#
ALTER TABLE `image_display_slides` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `image_display_slides` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in image_display_slides table
#
CREATE TABLE IF NOT EXISTS `image_display_slides_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `image_display_slide_id` int(11) DEFAULT '0',
  `image_display_id` int(11) DEFAULT '0',
  `language_id` int(11) DEFAULT '1',
  `image_id` int(11) DEFAULT NULL,
  `sort_order` int(3) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `image_display_slides_insertTrigger`;
CREATE TRIGGER `image_display_slides_insertTrigger` AFTER INSERT ON `image_display_slides` FOR EACH ROW INSERT delayed INTO `image_display_slides_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, image_display_slide_id, image_display_id, language_id, image_id, sort_order, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.image_display_slide_id, NEW.image_display_id, NEW.language_id, NEW.image_id, NEW.sort_order, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `image_display_slides_updateTrigger`;
CREATE TRIGGER `image_display_slides_updateTrigger` AFTER UPDATE ON `image_display_slides` FOR EACH ROW INSERT delayed INTO `image_display_slides_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, image_display_slide_id, image_display_id, language_id, image_id, sort_order, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.image_display_slide_id, NEW.image_display_id, NEW.language_id, NEW.image_id, NEW.sort_order, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `image_display_slides_deleteTrigger`;
CREATE TRIGGER `image_display_slides_deleteTrigger` BEFORE DELETE ON `image_display_slides` FOR EACH ROW INSERT delayed INTO `image_display_slides_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, image_display_slide_id, image_display_id, language_id, image_id, sort_order, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.image_display_slide_id, OLD.image_display_id, OLD.language_id, OLD.image_id, OLD.sort_order, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to image_display_description table
#
ALTER TABLE `image_display_description` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `image_display_description` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in image_display_description table
#
CREATE TABLE IF NOT EXISTS `image_display_description_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `image_display_id` int(11) DEFAULT '0',
  `language_id` int(11) DEFAULT '1',
  `flash` varchar(128) collate utf8_unicode_ci default NULL,
  `flash_width` int(11) DEFAULT '0',
  `flash_height` int(11) DEFAULT '0',
  `flash_loop` int(11) DEFAULT '0',
  `image_id` int(11) DEFAULT NULL,
  `image_width` int(11) DEFAULT '0',
  `image_height` int(11) DEFAULT '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `image_display_description_insertTrigger`;
CREATE TRIGGER `image_display_description_insertTrigger` AFTER INSERT ON `image_display_description` FOR EACH ROW INSERT delayed INTO `image_display_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, image_display_id, language_id, flash, flash_width, flash_height, flash_loop, image_id, image_width, image_height, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.image_display_id, NEW.language_id, NEW.flash, NEW.flash_width, NEW.flash_height, NEW.flash_loop, NEW.image_id, NEW.image_width, NEW.image_height, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `image_display_description_updateTrigger`;
CREATE TRIGGER `image_display_description_updateTrigger` AFTER UPDATE ON `image_display_description` FOR EACH ROW INSERT delayed INTO `image_display_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, image_display_id, language_id, flash, flash_width, flash_height, flash_loop, image_id, image_width, image_height, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.image_display_id, NEW.language_id, NEW.flash, NEW.flash_width, NEW.flash_height, NEW.flash_loop, NEW.image_id, NEW.image_width, NEW.image_height, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `image_display_description_deleteTrigger`;
CREATE TRIGGER `image_display_description_deleteTrigger` BEFORE DELETE ON `image_display_description` FOR EACH ROW INSERT delayed INTO `image_display_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, image_display_id, language_id, flash, flash_width, flash_height, flash_loop, image_id, image_width, image_height, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.image_display_id, OLD.language_id, OLD.flash, OLD.flash_width, OLD.flash_height, OLD.flash_loop, OLD.image_id, OLD.image_width, OLD.image_height, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to product_to_option table
#
ALTER TABLE `product_to_option` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `product_to_option` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in product_to_option table
#
CREATE TABLE IF NOT EXISTS `product_to_option_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `product_to_option_id` int(11) DEFAULT '0',
  `product_id` int(11) default '0',
  `option_id` int(11) default '0',
  `option_value_id` int(11) default '0',
  `price` decimal(15,4) DEFAULT '0.0000',
  `prefix` char(1) default '+',
  `option_weight` decimal(15,4) DEFAULT '0.0000',
  `option_weightclass_id` int(11) default '0',
  `sort_order` int(3) default '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `product_to_option_insertTrigger`;
CREATE TRIGGER `product_to_option_insertTrigger` AFTER INSERT ON `product_to_option` FOR EACH ROW INSERT delayed INTO `product_to_option_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, product_to_option_id, product_id, option_id, option_value_id, price, prefix, option_weight, option_weightclass_id, sort_order, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.product_to_option_id, NEW.product_id, NEW.option_id, NEW.option_value_id, NEW.price, NEW.prefix, NEW.option_weight, NEW.option_weightclass_id, NEW.sort_order, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_to_option_updateTrigger`;
CREATE TRIGGER `product_to_option_updateTrigger` AFTER UPDATE ON `product_to_option` FOR EACH ROW INSERT delayed INTO `product_to_option_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, product_to_option_id, product_id, option_id, option_value_id, price, prefix, option_weight, option_weightclass_id, sort_order, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.product_to_option_id, NEW.product_id, NEW.option_id, NEW.option_value_id, NEW.price, NEW.prefix, NEW.option_weight, NEW.option_weightclass_id, NEW.sort_order, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_to_option_deleteTrigger`;
CREATE TRIGGER `product_to_option_deleteTrigger` BEFORE DELETE ON `product_to_option` FOR EACH ROW INSERT delayed INTO `product_to_option_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, product_to_option_id, product_id, option_id, option_value_id, price, prefix, option_weight, option_weightclass_id, sort_order, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.product_to_option_id, OLD.product_id, OLD.option_id, OLD.option_value_id, OLD.price, OLD.prefix, OLD.option_weight, OLD.option_weightclass_id, OLD.sort_order, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to product_options table
#
ALTER TABLE `product_options` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `product_options` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in product_options table
#
CREATE TABLE IF NOT EXISTS `product_options_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `product_id` int(11) default '0',
  `product_option` varchar(64) collate utf8_unicode_ci default '',
  `quantity` int(4) default '0',
  `barcode` varchar(20) collate utf8_unicode_ci default '',
  `image_id` int(11) default '0',
  `dimension_id` int(11) default '0',
  `dimension_value` varchar(64) collate utf8_unicode_ci default '0:0:0',
  `model_number` varchar(32) collate utf8_unicode_ci default '',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `product_options_insertTrigger`;
CREATE TRIGGER `product_options_insertTrigger` AFTER INSERT ON `product_options` FOR EACH ROW INSERT delayed INTO `product_options_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, product_option, quantity, barcode, image_id, dimension_id, dimension_value, model_number, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.product_id, NEW.product_option, NEW.quantity, NEW.barcode, NEW.image_id, NEW.dimension_id, NEW.dimension_value, NEW.model_number, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_options_updateTrigger`;
CREATE TRIGGER `product_options_updateTrigger` AFTER UPDATE ON `product_options` FOR EACH ROW INSERT delayed INTO `product_options_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, product_option, quantity, barcode, image_id, dimension_id, dimension_value, model_number, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.product_id, NEW.product_option, NEW.quantity, NEW.barcode, NEW.image_id, NEW.dimension_id, NEW.dimension_value, NEW.model_number, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_options_deleteTrigger`;
CREATE TRIGGER `product_options_deleteTrigger` BEFORE DELETE ON `product_options` FOR EACH ROW INSERT delayed INTO `product_options_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, product_option, quantity, barcode, image_id, dimension_id, dimension_value, model_number, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.product_id, OLD.product_option, OLD.quantity, OLD.barcode, OLD.image_id, OLD.dimension_id, OLD.dimension_value, OLD.model_number, OLD.date_added, OLD.date_modified);

#
# Add date_added and date_modified to product_description table
#
ALTER TABLE `product_description` ADD `date_added` datetime NOT NULL default '1000-01-01 00:00:00';
ALTER TABLE `product_description` ADD `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in product_description table
#
CREATE TABLE IF NOT EXISTS `product_description_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `product_id` int(11) DEFAULT '0',
  `language_id` int(11) DEFAULT '1',
  `name` varchar(64) collate utf8_unicode_ci default '',
  `description` text collate utf8_unicode_ci,
  `technical` text collate utf8_unicode_ci,
  `technical_name` varchar(64) collate utf8_unicode_ci default '',
  `model` varchar(32) collate utf8_unicode_ci default NULL,
  `model_number` varchar(32) collate utf8_unicode_ci default NULL,
  `meta_keywords` varchar(255) collate utf8_unicode_ci default NULL,
  `meta_description` varchar(255) collate utf8_unicode_ci default NULL,
  `meta_title` varchar(255) collate utf8_unicode_ci default NULL,
  `alt_description` varchar(255) collate utf8_unicode_ci default NULL,
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `product_description_insertTrigger`;
CREATE TRIGGER `product_description_insertTrigger` AFTER INSERT ON `product_description` FOR EACH ROW INSERT delayed INTO `product_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, language_id, name, description, technical, technical_name, model, model_number, meta_keywords, meta_description, meta_title, alt_description, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.product_id, NEW.language_id, NEW.name, NEW.description, NEW.technical, NEW.technical_name, NEW.model, NEW.model_number, NEW.meta_keywords, NEW.meta_description, NEW.meta_title, NEW.alt_description, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_description_updteTrigger`;
CREATE TRIGGER `product_description_updateTrigger` AFTER UPDATE ON `product_description` FOR EACH ROW INSERT delayed INTO `product_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, language_id, name, description, technical, technical_name, model, model_number, meta_keywords, meta_description, meta_title, alt_description, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.product_id, NEW.language_id, NEW.name, NEW.description, NEW.technical, NEW.technical_name, NEW.model, NEW.model_number, NEW.meta_keywords, NEW.meta_description, NEW.meta_title, NEW.alt_description, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_description_deleteTrigger`;
CREATE TRIGGER `product_description_deleteTrigger` BEFORE DELETE ON `product_description` FOR EACH ROW INSERT delayed INTO `product_description_log` (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, language_id, name, description, technical, technical_name, model, model_number, meta_keywords, meta_description, meta_title, alt_description, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.product_id, OLD.language_id, OLD.name, OLD.description, OLD.technical, OLD.technical_name, OLD.model, OLD.model_number, OLD.meta_keywords, OLD.meta_description, OLD.meta_title, OLD.alt_description, OLD.date_added, OLD.date_modified);

#
# Alter product table
#
ALTER TABLE `product` CHANGE `date_added` `date_added` datetime NOT NULL default '1000-01-01 00:00:00' AFTER `viewed`;
ALTER TABLE `product` CHANGE `date_modified` `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `date_added`;

#
# Log every change in product table
#
CREATE TABLE IF NOT EXISTS `product_log` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trigger_action` varchar(6) COLLATE utf8_unicode_ci DEFAULT '',
  `trigger_modifier_id` int(11) DEFAULT '0',
  `trigger_modifier_title` varchar(8) COLLATE utf8_unicode_ci DEFAULT '',
  `product_id` int(11) default '0',
  `quantity` int(4) default '0',
  `barcode` varchar(20) collate utf8_unicode_ci default '',
  `min_qty` int(4) default '1',
  `max_qty` int(4) default '0',
  `multiple` int(4) default '0',
  `manufacturer_id` int(11) default '0',
  `vendor_id` int(11) default '0',
  `image_id` int(11) default '0',
  `shipping` int(1) default '1',
  `shipping_time_from` int(2) default '2',
  `shipping_time_to` int(2) default '4',
  `price` decimal(15,4) DEFAULT '0.0000',
  `sort_order` int(3) default '0',
  `date_available` datetime default NULL,
  `weight` decimal(15,4) DEFAULT '0.0000',
  `weight_class_id` int(11) default '0',
  `dimension_id` int(11) default '0',
  `dimension_value` varchar(64) collate utf8_unicode_ci default '0:0:0',
  `status` int(1) default '0',
  `featured` int(1) default '0',
  `special_offer` int(1) default '0',
  `related` int(1) default '0',
  `sale_end_date` datetime NOT NULL default '1000-01-01 00:00:00',
  `sale_start_date` datetime NOT NULL default '1000-01-01 00:00:00',
  `remaining` int(1) default '1',
  `special_price` decimal(15,4) DEFAULT '0.0000',
  `tax_class_id` int(11) default '0',
  `viewed` int(5) default '0',
  `date_added` datetime NOT NULL default '1000-01-01 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`trigger_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TRIGGER IF EXISTS `product_insertTrigger`;
CREATE TRIGGER `product_insertTrigger` AFTER INSERT ON `product` FOR EACH ROW INSERT delayed INTO product_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, quantity, min_qty, max_qty, multiple, manufacturer_id, vendor_id, image_id, shipping, shipping_time_from, shipping_time_to, price, sort_order, date_available, weight, weight_class_id, dimension_id, dimension_value, status, featured, special_offer, related, sale_end_date, sale_start_date, remaining, special_price, tax_class_id, viewed, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.product_id, NEW.quantity, NEW.barcode, NEW.min_qty, NEW.max_qty, NEW.multiple, NEW.manufacturer_id, NEW.vendor_id, NEW.image_id, NEW.shipping, NEW.shipping_time_from, NEW.shipping_time_to, NEW.price, NEW.sort_order, NEW.date_available, NEW.weight, NEW.weight_class_id, NEW.dimension_id, NEW.dimension_value, NEW.status, NEW.featured, NEW.special_offer, NEW.related, NEW.sale_end_date, NEW.sale_start_date, NEW.remaining, NEW.special_price, NEW.tax_class_id, NEW.viewed, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_updateTrigger`;
CREATE TRIGGER `product_updateTrigger` AFTER UPDATE ON `product` FOR EACH ROW INSERT delayed INTO product_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, quantity, min_qty, max_qty, multiple, manufacturer_id, vendor_id, image_id, shipping, shipping_time_from, shipping_time_to, price, sort_order, date_available, weight, weight_class_id, dimension_id, dimension_value, status, featured, special_offer, related, sale_end_date, sale_start_date, remaining, special_price, tax_class_id, viewed, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.product_id, NEW.quantity, NEW.barcode, NEW.min_qty, NEW.max_qty, NEW.multiple, NEW.manufacturer_id, NEW.vendor_id, NEW.image_id, NEW.shipping, NEW.shipping_time_from, NEW.shipping_time_to, NEW.price, NEW.sort_order, NEW.date_available, NEW.weight, NEW.weight_class_id, NEW.dimension_id, NEW.dimension_value, NEW.status, NEW.featured, NEW.special_offer, NEW.related, NEW.sale_end_date, NEW.sale_start_date, NEW.remaining, NEW.special_price, NEW.tax_class_id, NEW.viewed, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_deleteTrigger`;
CREATE TRIGGER `product_deleteTrigger` BEFORE DELETE ON `product` FOR EACH ROW INSERT delayed INTO product_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, quantity, min_qty, max_qty, multiple, manufacturer_id, vendor_id, image_id, shipping, shipping_time_from, shipping_time_to, price, sort_order, date_available, weight, weight_class_id, dimension_id, dimension_value, status, featured, special_offer, related, sale_end_date, sale_start_date, remaining, special_price, tax_class_id, viewed, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.product_id, OLD.quantity, OLD.barcode, OLD.min_qty, OLD.max_qty, OLD.multiple, OLD.manufacturer_id, OLD.vendor_id, OLD.image_id, OLD.shipping, OLD.shipping_time_from, OLD.shipping_time_to, OLD.price, OLD.sort_order, OLD.date_available, OLD.weight, OLD.weight_class_id, OLD.dimension_id, OLD.dimension_value, OLD.status, OLD.featured, OLD.special_offer, OLD.related, OLD.sale_end_date, OLD.sale_start_date, OLD.remaining, OLD.special_price, OLD.tax_class_id, OLD.viewed, OLD.date_added, OLD.date_modified);

#
# Fix on product triggers
#
DROP TRIGGER IF EXISTS `product_insertTrigger`;
CREATE TRIGGER `product_insertTrigger` AFTER INSERT ON `product` FOR EACH ROW INSERT delayed INTO product_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, quantity, barcode, min_qty, max_qty, multiple, manufacturer_id, vendor_id, image_id, shipping, shipping_time_from, shipping_time_to, price, sort_order, date_available, weight, weight_class_id, dimension_id, dimension_value, status, featured, special_offer, related, sale_end_date, sale_start_date, remaining, special_price, tax_class_id, viewed, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.product_id, NEW.quantity, NEW.barcode, NEW.min_qty, NEW.max_qty, NEW.multiple, NEW.manufacturer_id, NEW.vendor_id, NEW.image_id, NEW.shipping, NEW.shipping_time_from, NEW.shipping_time_to, NEW.price, NEW.sort_order, NEW.date_available, NEW.weight, NEW.weight_class_id, NEW.dimension_id, NEW.dimension_value, NEW.status, NEW.featured, NEW.special_offer, NEW.related, NEW.sale_end_date, NEW.sale_start_date, NEW.remaining, NEW.special_price, NEW.tax_class_id, NEW.viewed, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_updateTrigger`;
CREATE TRIGGER `product_updateTrigger` AFTER UPDATE ON `product` FOR EACH ROW INSERT delayed INTO product_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, quantity, barcode, min_qty, max_qty, multiple, manufacturer_id, vendor_id, image_id, shipping, shipping_time_from, shipping_time_to, price, sort_order, date_available, weight, weight_class_id, dimension_id, dimension_value, status, featured, special_offer, related, sale_end_date, sale_start_date, remaining, special_price, tax_class_id, viewed, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.product_id, NEW.quantity, NEW.barcode, NEW.min_qty, NEW.max_qty, NEW.multiple, NEW.manufacturer_id, NEW.vendor_id, NEW.image_id, NEW.shipping, NEW.shipping_time_from, NEW.shipping_time_to, NEW.price, NEW.sort_order, NEW.date_available, NEW.weight, NEW.weight_class_id, NEW.dimension_id, NEW.dimension_value, NEW.status, NEW.featured, NEW.special_offer, NEW.related, NEW.sale_end_date, NEW.sale_start_date, NEW.remaining, NEW.special_price, NEW.tax_class_id, NEW.viewed, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `product_deleteTrigger`;
CREATE TRIGGER `product_deleteTrigger` BEFORE DELETE ON `product` FOR EACH ROW INSERT delayed INTO product_log (trigger_action, trigger_modifier_id, trigger_modifier_title, product_id, quantity, barcode, min_qty, max_qty, multiple, manufacturer_id, vendor_id, image_id, shipping, shipping_time_from, shipping_time_to, price, sort_order, date_available, weight, weight_class_id, dimension_id, dimension_value, status, featured, special_offer, related, sale_end_date, sale_start_date, remaining, special_price, tax_class_id, viewed, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.product_id, OLD.quantity, OLD.barcode, OLD.min_qty, OLD.max_qty, OLD.multiple, OLD.manufacturer_id, OLD.vendor_id, OLD.image_id, OLD.shipping, OLD.shipping_time_from, OLD.shipping_time_to, OLD.price, OLD.sort_order, OLD.date_available, OLD.weight, OLD.weight_class_id, OLD.dimension_id, OLD.dimension_value, OLD.status, OLD.featured, OLD.special_offer, OLD.related, OLD.sale_end_date, OLD.sale_start_date, OLD.remaining, OLD.special_price, OLD.tax_class_id, OLD.viewed, OLD.date_added, OLD.date_modified);

#
# Fix on coupon triggers
#
DROP TRIGGER IF EXISTS `coupon_insertTrigger`;
CREATE TRIGGER `coupon_insertTrigger` AFTER INSERT ON `coupon` FOR EACH ROW INSERT delayed INTO coupon_log (trigger_action, trigger_modifier_id, trigger_modifier_title, coupon_id, code, discount, prefix, minimum_order, shipping, date_start, date_end, uses_total, uses_customer, status, date_added, date_modified) VALUES ('INSERT', @modifier_id, @modifier_title, NEW.coupon_id, NEW.code, NEW.discount, NEW.prefix, NEW.minimum_order, NEW.shipping, NEW.date_start, NEW.date_end, NEW.uses_total, NEW.uses_customer, NEW.status, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `coupon_updateTrigger`;
CREATE TRIGGER `coupon_updateTrigger` AFTER UPDATE ON `coupon` FOR EACH ROW INSERT delayed INTO coupon_log (trigger_action, trigger_modifier_id, trigger_modifier_title, coupon_id, code, discount, prefix, minimum_order, shipping, date_start, date_end, uses_total, uses_customer, status, date_added, date_modified) VALUES ('UPDATE', @modifier_id, @modifier_title, NEW.coupon_id, NEW.code, NEW.discount, NEW.prefix, NEW.minimum_order, NEW.shipping, NEW.date_start, NEW.date_end, NEW.uses_total, NEW.uses_customer, NEW.status, NEW.date_added, NEW.date_modified);

DROP TRIGGER IF EXISTS `coupon_deleteTrigger`;
CREATE TRIGGER `coupon_deleteTrigger` BEFORE DELETE ON `coupon` FOR EACH ROW INSERT delayed INTO coupon_log (trigger_action, trigger_modifier_id, trigger_modifier_title, coupon_id, code, discount, prefix, minimum_order, shipping, date_start, date_end, uses_total, uses_customer, status, date_added, date_modified) VALUES ('DELETE', @modifier_id, @modifier_title, OLD.coupon_id, OLD.code, OLD.discount, OLD.prefix, OLD.minimum_order, OLD.shipping, OLD.date_start, OLD.date_end, OLD.uses_total, OLD.uses_customer, OLD.status, OLD.date_added, OLD.date_modified);

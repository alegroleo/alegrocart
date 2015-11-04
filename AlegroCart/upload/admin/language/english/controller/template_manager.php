<?php
// Heading
$_['heading_title']		= 'Templates';
$_['heading_form_title']	= 'Template:';
$_['heading_description']	= 'Template comes with a set of built in default layouts. This can be overridden by creating a new default by selecting default as the controller.<br>You can replace all tabs, or only the ones you wish to change. You can change all or any tabs for specific controllers which overrides the default settings for this controller only.<br>If you wish to move module like cart to a new position, you must modify the tab cart is current located in and set the tab where you wish cart to be located in.<br>Other defaults such as template, style, color, and number of columns should be prviously setup in settings. For more detail, go to http://forum.alegrocart.com/viewtopic.php?f=22&t=154';

// Text
$_['text_message']		= 'Success: You have updated templates!';
$_['text_tpl_manager']		= 'Controller section must be filled and enabled to work.<br>All other tabs are optional, but if any modules are entered, only the modules entered will be displayed in that area.<br>The information entered in the template manager over rides defaults.<br>Make sure you check for duplication of modules between defaults in one area to your insertions.';

$_['text_tpl_content']		= 'If content location is enabled , CONTENT must be included.<br>If layout is 1 column, CATEGORYMENU and CART must be included in Top Header or Sub Header (if you over ride defaults).<br>Default Modules: content, homepage, featured, latest.<br>If Controller is Product and columns is set to 1.2 or 2.1, specials or related are included by default.';

$_['text_tpl_header']		= 'If header location is enabled, HEADER must be included.<br>Default Modules: header, language, currency, search, navigation. In case of 1 column layout, CART and CATEGORYMENU are loaded by default. CATEGORYMENU is included in 2.1 column layout as well.';
$_['text_tpl_extra']		= 'The Sub Header (extra) is located between the Top Header and the Content.<br>It is for optional content like a flash image or logo image.';

$_['text_tpl_column']		= 'The left column is included with either 1.2 or 3 column layout.<br>Default Modules: cart, category, manufacturer, popular, review, information.<br>If layout is 1.2 or 2.1 column, search options or category options will be included.';

$_['text_tpl_columnright']	= 'The right column is available in either 2.1 or 3 column layout.<br>Default Modules: specials, related in product page, search options in search page, category options in category page, and manufacturer options in manufacturer page.<br>If layout is 2.1 column, CATEGORYMENU must be included in Top Header or Sub Header (if you over ride defaults).';

$_['text_tpl_footer']		= 'If the footer is enabled, FOOTER must be included.<br>Default Modules: footer. If layout is 1 column, INFORMATION is included by default.';
$_['text_tpl_pagebottom']	= 'The Page Bottom location is for optional content.<br>This could be used for things like information or whatever content you like.<br>Default Modules: developer.';
//Tabs
$_['tab_controller']		= 'Controller';
$_['tab_header']		= 'Top Header';
$_['tab_extra']			= 'Sub Header<br>(extra)';
$_['tab_left_column']		= 'Left<br>Column';
$_['tab_content']		= 'Center Content';
$_['tab_right_column']		= 'Right<br>Column';
$_['tab_footer']		= 'Footer';
$_['tab_page_bottom']		= 'Page<br>Bottom';

// Column
$_['column_controller']		= 'Controller';
$_['column_tpl_columns']	= 'Page Columns';
$_['column_tpl_color']		= 'Page Color';
$_['column_tpl_status']		= 'Status';
$_['column_action']		= 'Action';

// Button
$_['button_add']		= 'Add';
$_['button_remove']		= 'Remove';

//Entry
$_['entry_controller']		= 'Main Controller: ';
$_['entry_columns']		= 'Number of Page columns: ';
$_['entry_status']		= 'Status: ';
$_['entry_color']		= 'Page Color: ';
$_['entry_location']		= 'Module Page Location: ';
$_['entry_module']		= 'Module: ';
$_['entry_sortorder']		= 'Sort Order: ';

// Error
$_['error_permission']		= 'Warning: You do not have permission to modify templates';
$_['error_already_exists']	= 'Error: an entry for this controller already exists!';
$_['error_controller']		= 'Controller must be specified!';
?>

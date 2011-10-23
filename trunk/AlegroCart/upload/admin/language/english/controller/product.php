<?php
// Heading
$_['heading_title']        = 'Products';
$_['heading_description']  = 'You can edit your products here.';

// Text
$_['text_message']         = 'Success: You have updated products!';
$_['text_plus']            = '+';
$_['text_minus']           = '-';
$_['text_model']	   = 'Enter a new Model name';
$_['text_model_select']	   = 'or select from existing model database';
$_['select_model']         = 'Select Model';
$_['text_unique']          = 'The Item or Product Name must be unique';
$_['text_linear']          = 'Linear: Length, Width, Height';
$_['text_area']            = 'Area: Length X Width';
$_['text_volume']          = 'Volume: Litre, Quart';
$_['text_no_dimensions']   = 'There are no dimensions available for <b> %s </b>';
$_['text_no_dim']          = 'None';
$_['text_dimension_ship']  = 'Length, width, height are optional, used for shipping calculations where cubing is applied';
$_['text_options']         = 'Options: ';
$_['text_quantity_options']= 'Add Quantities in Product Options tab';
$_['text_barcode_options'] = 'Add Barcodes in Product Options tab';
$_['text_model_options']   = 'Add Model Number in Product Options tab';
$_['text_option_info']     = '<b>Important Information !</b>';
$_['text_option_explantion'] = 'Before entering Quantities and other product:option information, you should create all your product to options first. <br>A product with one set of options will have entries like<br> 2:10 - 2:11 - 2:12<br>With 2 options, the entries will be like<br> 2:10.13 - 2:11.13 - 2:12.13 - 2:10.14 - 2:11.14 - 2:12.14<br>As you can see, the first set of product to option entries will no longer exist<br>If you create one set of options and later decide to create a second option, all your product:option specific data will be lost.<br>';
$_['text_folder_help']    = 'The <b>List Icons</b> below are the gateways to adding or updating product options.<br>You can add, delete, or change product options for each product.<br>Icons with 3 red stars indicate the product has options.<br>Click the icon to edit options for the product chosen.';
$_['text_featured_help']  = 'F - Featured Products<br>S - Special Priced Products<br>R - Related Products<br>D - Downloadable Products';
$_['text_barcode_explanation'] = 'If you enter the barcode without the last check digit it will be calculated automatically. The check digit is a single checksum digit calculated from the first 11 (UPC) or 12 (EAN-13/ISBN) barcode digits.';
$_['text_barcode_enc_explanation'] = 'The Universal Product Code (UPC) consists of 12 numerical digits (11 data and 1 check digit).<br>An EAN-13/ISBN barcode is a 13 digit (12 data and 1 check digit) barcoding standard.';
$_['text_upc']            = 'UPC';
$_['text_ean']            = 'EAN-13/ISBN';

// Column
$_['column_options']       = 'Options!';
$_['column_name']          = 'Product Name';
$_['column_price']         = 'Price'; 
$_['column_dated_special'] = 'Dated<br>Special'; 
$_['column_stock']         = 'Stock'; 
$_['column_weight']        = 'Weight';
$_['column_image']         = 'Image';
$_['column_model']         = 'Model';
$_['column_featured']      = 'Specialties';
$_['column_sort_order']    = 'Sort<br>Order';
$_['column_status']        = 'Status';
$_['column_action']        = 'Action';

// Entry
$_['entry_name']           = 'Product Name (Item Number):';
$_['entry_description']    = 'Description:<br><br><br>Use <b>SHIFT->ENTER</b> for<br>line feed or empty lines<br>for spacing.<br>ENTER only produces<br>Paragraph Tags which are<br>Stripped.';
$_['entry_model']          = 'Model:';
$_['entry_model_number']   = 'Model Number:';
$_['entry_model_numbers']   = 'Model Number';
$_['entry_manufacturer']   = 'Manufacturer:';
$_['entry_shipping']       = 'Shippable Product:';
$_['entry_date_available'] = 'Date Available:';
$_['entry_quantity']       = 'In Stock Quantity:';
$_['entry_barcode']        = 'Barcode Digits:';
$_['entry_barcode_encoding'] = 'Barcode Encoding:';
$_['entry_discount']       = 'Discount Amount %s';
$_['entry_status']         = 'Product Status:';
$_['entry_sort_order']     = 'Sort Order:';
$_['entry_tax_class']      = 'Tax Class:';
$_['entry_price']          = 'Base Price:';
$_['entry_weight_class']   = 'Weight Class:';
$_['entry_weight']         = 'Product Weight:';
$_['entry_dimension_class']= 'Dimension Type:';
$_['entry_dimension']      = 'Default Dimension:';
$_['entry_length']         = 'Length:';
$_['entry_width']          = 'Width:';
$_['entry_height']         = 'Height:';
$_['entry_area']           = 'Area:';
$_['entry_volume']         = 'Volume:';
$_['entry_prefix']         = 'Prefix:';
$_['entry_image']          = 'Image:';
$_['entry_images']         = 'Additional Images:';
$_['entry_download']       = 'Downloads:';
$_['entry_category']       = 'Categories:';
$_['entry_min_qty']        = 'Minimum Order Quantity:';
$_['entry_dated_special']  = 'Dated Special Price  %s '; // New
$_['entry_start_date']     = 'Start Date:'; // New
$_['entry_end_date']       = 'End Date:'; // New
$_['entry_alt_description']= 'Alternate Short Description:<br><br><br>Use <b>SHIFT->ENTER</b> for<br>line feed or empty lines<br>for spacing.<br>ENTER only produces<br>Paragraph Tags which are<br>Stripped.'; // New
$_['entry_technical']      = 'Technical Specification Description:'; // New
$_['entry_meta_title']     = 'Meta Title:'; // New
$_['entry_meta_description'] = 'Meta Description:'; // New
$_['entry_meta_keywords']  = 'Meta Keywords:'; // New
$_['entry_regular_price']  = 'Regular Price %s '; //New
$_['entry_percent_discount'] = 'Percentage Discount % '; // New
$_['entry_quantity_discount'] = '<br>Percentage Discount is the base for quantity discount calculations.<br>Dollar amounts are for reference only, based on the regular selling price.<br>A new percentage discount can be created by entering a dollar amount.<br>If Dated Special pricing is in affect, the discount will be calculated off the dated special price.';
$_['entry_product_option']    = 'Product Option';
$_['entry_po_quantity']       = 'Quantity in Stock';
$_['entry_po_barcode']        = 'Barcode Digits';
$_['entry_po_barcode_encoding'] = 'Barcode Encoding';
// Button
$_['button_add']           = 'Add';
$_['button_remove']        = 'Remove';

// Tab
$_['tab_general']       = 'Product Name<br>Description';
$_['tab_product_options']  = 'Product<br>Options';
$_['tab_dated_special']    = 'Dated<br>Specials';
$_['tab_alt_description']  = 'Alternate<br>Description';
$_['tab_download']      = 'Product<br>Download';
$_['tab_discount']      = 'Quantity<br>Discounts';
$_['tab_category']      = 'Product<br>Category';
$_['tab_image']         = 'Product Image<br>Additional Images';
$_['tab_data']          = 'Product Detail<br>Information';

// Error
$_['error_permission']     = 'Warning: You do not have permission to modify products';
$_['error_name']           = '* Product Name must be between 1 and 64 characters!';
$_['error_date_available'] = '* Date Available is not valid!';
$_['error_start_date']     = '* Start Date is not valid!'; // New
$_['error_end_date']       = '* End Date is not valid!'; // New
$_['error_already_exists'] = '* ERROR - This product name already EXISTS *';
$_['error_duplicate_name'] = '*ERROR - You already have another product with this name*';
$_['error_ean']            = 'EAN-13/ISBN barcode must be 12 or 13 digit!'; // New
$_['error_upc']            = 'UPC barcode must be 11 or 12 digit!'; // New
$_['error_barcode_already_exists'] = '* ERROR - This barcode already EXISTS *';
$_['error_warning']        = '* You have errors. Please check all tabs for error messages!';
$_['error_duplicate_barcode'] = '* ERROR - DUPLICATE BARCODE *';
?>
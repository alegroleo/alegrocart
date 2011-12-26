/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.docType = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
	
	config.removePlugins = 
		'about,' +
		'a11yhelp,' +
		'button,' +
		'clipboard,' +
		'elementspath,' +
		'filebrowser,' +
		'find,' +
		'flash,' +
		'forms,' +
		'iframe,' +
		'image,' +
		'link,' +
		'maximize,' +
		'newpage,' +
		'pagebreak,' +
		'pastefromword,' +
		'pastetext,' +
		'popup,' +
		'preview,' +
		'print,' +
		'resize,' +
		'save,' +
		'scayt,' +
		'smiley,' +
		'templates,' +
		'undo';
	
	config.height = 300;
	config.width = 650;
	config.extraPlugins = 'hiddentext';	
	config.toolbar_Full = [
		{ name: 'document',		items : [ 'Source', ] },
		{ name: 'styles',		items : [ 'Styles','Format','Font','FontSize' ] },
		{ name: 'tools',		items : [ 'ShowBlocks'] },
		{ name: 'colors',		items : [ 'TextColor','BGColor', '-', 'hiddentext' ] },
		'/',
		{ name: 'basicstyles',	items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
		{ name: 'paragraph',	items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
		{ name: 'links',		items : ['Anchor' ] },
		{ name: 'insert',		items : [ 'Table','HorizontalRule','SpecialChar',] },
	];
};
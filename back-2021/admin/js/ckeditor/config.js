/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
   config.language = 'pt-br';
   //config.uiColor = '#C4B6AB';
   config.height = '500px';
   // The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];
     
   config.filebrowserBrowseUrl = 'js/ckeditor/kcfinder/browse.php?opener=ckeditor&type=files';
   config.filebrowserImageBrowseUrl = 'js/ckeditor/kcfinder/browse.php?opener=ckeditor&type=images';
   config.filebrowserFlashBrowseUrl = 'js/ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash';
   config.filebrowserUploadUrl = 'js/ckeditor/kcfinder/upload.php?opener=ckeditor&type=files';
   config.filebrowserImageUploadUrl = 'js/ckeditor/kcfinder/upload.php?opener=ckeditor&type=images';
   config.filebrowserFlashUploadUrl = 'js/ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash';
   
   // Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';
};

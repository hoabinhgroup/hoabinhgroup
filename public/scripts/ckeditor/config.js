/**
* @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
* For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';
    
    	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
	
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	//config.removeDialogTabs = 'image:advanced;link:advanced';
	
	config.entities = false;
	config.basicEntities = false;

	//config.extraPlugins = 'btgrid';

    //config.filebrowserBrowseUrl = '/public/scripts/ckeditor/ckfinder/ckfinder.html';

   // config.filebrowserImageBrowseUrl = '/public/scripts/ckeditor/ckfinder/ckfinder.html?type=Images';

   // config.filebrowserFlashBrowseUrl = '/public/scripts/ckeditor/ckfinder/ckfinder.html?type=Flash';

   // config.filebrowserUploadUrl = '/public/scripts/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';

   // config.filebrowserImageUploadUrl = '/public/scripts/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';

   // config.filebrowserFlashUploadUrl = '/public/scripts/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';


     //  config.filebrowserBrowseUrl = '/public/scripts/ckeditor/kcfinder/browse.php?type=files';
      // config.filebrowserImageBrowseUrl = '/public/scripts/ckeditor/kcfinder/browse.php?type=images';
      // config.filebrowserFlashBrowseUrl = '/public/scripts/ckeditor/kcfinder/browse.php?type=flash';
      // config.filebrowserUploadUrl = '/public/scripts/ckeditor/kcfinder/upload.php?type=files';
      // config.filebrowserImageUploadUrl = '/public/scripts/ckeditor/kcfinder/upload.php?type=images';
      // config.filebrowserFlashUploadUrl = '/public/scripts/ckeditor/kcfinder/upload.php?type=flash';

//config.filebrowserBrowseUrl = '/public/scripts/ckeditor/Filemanager-master/index.html';
   // config.filebrowserImageBrowseUrl = '/public/scripts/ckeditor/Filemanager-master/index.html?Type=Images';
   // config.filebrowserFlashBrowseUrl = '/public/scripts/ckeditor/Filemanager-master/index.html?Type=Flash';
   // config.filebrowserUploadUrl = '/public/scripts/ckeditor/Filemanager-master/connectors/ashx/filemanager.ashx?command=QuickUpload&type=Files';
   // config.filebrowserImageUploadUrl = '/public/scripts/ckeditor/Filemanager-master/connectors/ashx/filemanager.ashx?command=QuickUpload&type=Images';
   //config.filebrowserFlashUploadUrl = '/public/scripts/ckeditor/Filemanager-master/connectors/ashx/filemanager.ashx?command=QuickUpload&type=Flash';

};


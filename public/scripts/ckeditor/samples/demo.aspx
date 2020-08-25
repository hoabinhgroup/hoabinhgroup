<%@ Page Language="C#" AutoEventWireup="true" CodeFile="demo.aspx.cs" Inherits="samples_demo" %>

<!--
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.md or http://ckeditor.com/license
-->
<html>
<head>
    <title>Inline Editing by Code &mdash; CKEditor Sample</title>
    <script type="text/javascript" src="../ckeditor.js"></script>
    <script type="text/javascript" src="../ckfinder/ckfinder.js"></script>
    <link href="sample.css" rel="stylesheet" />
    <style type="text/css">
        #divImage, #editor1, #checkeditable
        {
            padding: 10px;
            float: left;
        }
    </style>
</head>
<body>
    <div id="editor1" contenteditable="true">
        <p>
            <b>Test First Apollo 11</b> was the spaceflight that landed the first humans, Americans
            <a href="http://en.wikipedia.org/wiki/Neil_Armstrong" title="Neil Armstrong">Neil Armstrong</a>
            and <a href="http://en.wikipedia.org/wiki/Buzz_Aldrin" title="Buzz Aldrin">Buzz Aldrin</a>,
            on the Moon on July 20, 1969, at 20:18 UTC. Armstrong became the first to step onto
            the lunar surface 6 hours later on July 21 at 02:56 UTC.</p>
        <p>
            Armstrong spent about <s>three and a half</s> two and a half hours outside the spacecraft,
            Aldrin slightly less; and together they collected 47.5 pounds (21.5&nbsp;kg) of
            lunar material for return to Earth. A third member of the mission, <a href="http://en.wikipedia.org/wiki/Michael_Collins_(astronaut)"
                title="Michael Collins (astronaut)">Michael Collins</a>, piloted the <a href="http://en.wikipedia.org/wiki/Apollo_Command/Service_Module"
                    title="Apollo Command/Service Module">command</a> spacecraft alone in lunar
            orbit until Armstrong and Aldrin returned to it for the trip back to Earth.</p>
        <p>
            Broadcast on live TV to a world-wide audience, Armstrong stepped onto the lunar
            surface and described the event as:</p>
    </div>
    <div id="checkeditable" contenteditable="true">
        <img alt="" src="../TestImage.jpg" width="150px" height="150px" />
        Armstrong spent about <s>three and a half</s> two and a half hours outside the spacecraft,
        Aldrin slightly less; and together they collected 47.5 pounds (21.5&nbsp;kg) of
        lunar material for return to Earth. A third member of the mission, <a href="http://en.wikipedia.org/wiki/Michael_Collins_(astronaut)"
            title="Michael Collins (astronaut)">Michael Collins</a>, piloted the <a href="http://en.wikipedia.org/wiki/Apollo_Command/Service_Module"
                title="Apollo Command/Service Module">command</a> spacecraft alone in lunar
        orbit until Armstrong and Aldrin returned to it for the trip back to Earth.
        <p>
            Broadcast on live TV to a world-wide audience, Armstrong stepped onto the lunar
            surface and described the event as:</p>
    </div>
    <div id="divImage" contenteditable="true">
        <img alt="" src="../Camera.jpg" width="150px" height="150px" />
    </div>    
    <script type="text/javascript">
        // We need to turn off the automatic editor creation first.
        //CKEDITOR.disableAutoInline = true;      
        
        //var neweditable = CKEDITOR.inline('neweditable');
//        var editor = CKEDITOR.inline('editor1');
//        var chkeditor = CKEDITOR.inline('checkeditable');        
//        var divImage = CKEDITOR.inline('divImage');

        CKEDITOR.replace('editor1', 
        {
            toolbar: [
		                { name: 'document', groups: ['mode', 'document', 'doctools'], items: ['-', 'Save',  'Preview', 'Print', '-'] },
		                { name: 'clipboard', groups: ['clipboard', 'undo'], items: ['Cut', 'Copy', 'Paste', '-', 'Undo', 'Redo'] },
                        { name: 'editing', groups: ['find', 'selection', 'spellchecker'], items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'] },                        
		                '/',
		                { name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline', 'Subscript', 'Superscript', '-'] },
                        { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language'] },
                        { name: 'links', items: ['Link', 'Unlink'] },
                        { name: 'insert', items: ['HorizontalRule', 'Smiley', 'SpecialChar'] },
	                    '/',
                        { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
	                    { name: 'colors', items: ['TextColor', 'BGColor'] },
	                    { name: 'tools', items: ['Maximize'] },
	                    { name: 'others', items: ['-'] }	                    
	                ]
            });

        //        CKEDITOR.replace('editor1',
        //        {
        //            filebrowserBrowseUrl: '../ckfinder/ckfinder.html',
        //            filebrowserImageBrowseUrl: '../ckfinder/ckfinder.html?Type=Images',
        //            filebrowserFlashBrowseUrl: '../ckfinder/ckfinder.html?Type=Flash',
        //            filebrowserUploadUrl: '../ckfinder/core/connector/aspx/connector.aspx?command=QuickUpload&type=Files',
        //            filebrowserImageUploadUrl: '../ckfinder/core/connector/aspx/connector.aspx?command=QuickUpload&type=Images',
        //            filebrowserFlashUploadUrl: '../ckfinder/core/connector/aspx/connector.aspx?command=QuickUpload&type=Flash'
        //        });

        CKEDITOR.replace('checkeditable',
        {
            toolbar: [
		                { name: 'document', groups: ['mode', 'document', 'doctools'], items: ['-', 'Save', 'Preview', 'Print', '-'] },
		                { name: 'clipboard', groups: ['clipboard', 'undo'], items: ['Cut', 'Copy', 'Paste', '-', 'Undo', 'Redo'] },
                        { name: 'editing', groups: ['find', 'selection', 'spellchecker'], items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'] },
		                '/',
		                { name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline', 'Subscript', 'Superscript', '-'] },
                        { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language'] },
                        { name: 'links', items: ['Link', 'Unlink'] },
                       	{ name: 'insert', items: ['Image'] },
	                    '/',
                        { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
	                    { name: 'colors', items: ['TextColor', 'BGColor'] },
	                    { name: 'tools', items: ['Maximize'] },
	                    { name: 'others', items: ['-'] }
	                ]
        });

        CKEDITOR.replace('divImage',
        {
            toolbar:
                    [
                        { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
	                   	{ name: 'insert', items: ['Image'] },
	                    { name: 'tools', items: ['Maximize'] }
	                ]
        });

//        CKEDITOR.replace('divImage',
//        {
//            filebrowserBrowseUrl: '../ckfinder/ckfinder.html',
//            filebrowserImageBrowseUrl: '../ckfinder/ckfinder.html?Type=Images',
//            filebrowserFlashBrowseUrl: '../ckfinder/ckfinder.html?Type=Flash',
//            filebrowserUploadUrl: '../ckfinder/core/connector/aspx/connector.aspx?command=QuickUpload&type=Files',
//            filebrowserImageUploadUrl: '../ckfinder/core/connector/aspx/connector.aspx?command=QuickUpload&type=Images',
//            filebrowserFlashUploadUrl: '../ckfinder/core/connector/aspx/connector.aspx?command=QuickUpload&type=Flash'
//        });

//        CKEDITOR.replace('checkeditable',
//                {
//                    filebrowserBrowseUrl: '../ckfinder/ckfinder.html',
//                    filebrowserImageBrowseUrl: '../ckfinder/ckfinder.html?Type=Images',
//                    filebrowserFlashBrowseUrl: '../ckfinder/ckfinder.html?Type=Flash',
//                    filebrowserUploadUrl: '../ckfinder/core/connector/aspx/connector.aspx?command=QuickUpload&type=Files',
//                    filebrowserImageUploadUrl: '../ckfinder/core/connector/aspx/connector.aspx?command=QuickUpload&type=Images',
//                    filebrowserFlashUploadUrl: '../ckfinder/core/connector/aspx/connector.aspx?command=QuickUpload&type=Flash'
//                });       
        //CKEDITOR.inlineAll();        

    </script>    
</body>
</html>

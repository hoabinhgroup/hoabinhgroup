<%@ Page Language="C#" AutoEventWireup="true" CodeFile="newsletterdemo.aspx.cs" Inherits="samples_newsletterdemo" %>

<!--
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.md or http://ckeditor.com/license
-->
<html>
<head>
    <title>Inline Editing by Code &mdash; CKEditor Sample</title>
    <script type="text/javascript" src="../ckeditor.js"></script>
    <script type="text/javascript" src="../ckfinder/ckfinder.js"></script>
    <script type="text/javascript" src="../jquery-1.7.2.min.js"></script>
    <link href="sample.css" rel="stylesheet" />
    <script src="mColorPicker.js" type="text/javascript"></script>
    <script type="text/javascript">
        var temp1;
        $(document).ready(function () {
            $(function () {
                $('div').click(function () {
                    //                    alert($(this).attr('id'));
                    if ($(this).attr('id') == 'divHeader') {
                        temp1 = 'divHeader';
                    }
                    if ($(this).attr('id') == 'divContent1') {
                        temp1 = 'divContent1';
                    }
                    if ($(this).attr('id') == 'divContent2') {
                        temp1 = 'divContent2';
                    }
                    if ($(this).attr('id') == 'divFooter') {
                        temp1 = 'divFooter';
                    }
                });
            });
        });
        function SetTemplateBackGroundColor() {
            //alert('temp1 in set: ' + temp1);
            if (temp1 == 'divHeader') {
                var x1 = document.getElementById("tbMain").getElementsByTagName("td");
                x1[0].style.backgroundColor = txttabcolor.value;
            }
            var x = document.getElementById(temp1);
            x.style.backgroundColor = txttabcolor.value;
        }

        function SaveSubmittedData() {
            var divMainData = $('#divMain').html();
            alert(divMainData);
        }
    </script>
    <style type="text/css">
        #divHeader, #divContent1, #divContent2, #divFooter
        {
            padding: 10px;
            float: left;
            background-color: transparent;
        }
    </style>
</head>
<body>
    <div>
        <label>
            Select Background Color</label>
        <input id="txttabcolor" type="color" data-hex="true" style="z-index: -100;" name="tabcolor"
            value="" name="tabcolor" class="tooltip" title="Enter Tab Color" />
        <input type="button" onclick="SetTemplateBackGroundColor();" name="btnTemplateBackGround"
            id="btnTemplateBackGround" value="Set BackGround Color" />
    </div>
    <br />
    <br />    
    <div id="divMain" name="divMain" style="width: 100%;" align="center">
        <table id="tbMain" width="600" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td id="tdBackGround" align="left" valign="top" style="background-color: #ff6c00;"
                    bgcolor="#ff6c00;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="13" align="left" valign="top">
                                &nbsp;
                            </td>
                            <td width="465" align="left" valign="top">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td align="left" valign="top">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="10">
                                                <tr>
                                                    <td style="font-family: Georgia, Times New Roman, Times, serif; color: #000000;">
                                                        <div id="divHeader" name="divHeader" contenteditable="true">
                                                            <div style="font-size: 48px;">
                                                                <i>Newsletter Title</i></div>
                                                            <div style="font-size: 24px;">
                                                                <i>Newsletter Subtitle</i></div>
                                                            Month Day, Year
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td align="left" valign="top">
                                                        <img src="images/tc.gif" alt="" width="465" height="9" style="display: block;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left" valign="top" bgcolor="#7c4513">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td width="10">
                                                                    &nbsp;
                                                                </td>
                                                                <td align="left" valign="top" style="color: #FFFFFF; font-size: 12px; font-family: Arial, Helvetica, sans-serif;">
                                                                    <div id="divContent1" contenteditable="true">
                                                                        <div style="font-size: 24px;">
                                                                            <b>Article Headline</b></div>
                                                                        <br>
                                                                        <img src="images/pic1.jpg" width="142" height="110" align="left" style="margin-right: 10px;">Lorem
                                                                        ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum magna enim, volutpat
                                                                        nec imperdiet id, tempor venenatis eros. Aliquam sed velit vitae nibh pulvinar iaculis.
                                                                        Aenean hendrerit, lorem eu luctus cursus, sapien justo auctor ligula, id bibendum
                                                                        lorem leo quis leo. Suspendisse sit amet aliquam orci. Aliquam erat volutpat.
                                                                        <br>
                                                                        <br>
                                                                        Aliquam erat volutpat. Nunc ac justo enim. Morbi eleifend feugiat turpis non placerat.
                                                                        Etiam sed tellus ac lectus lacinia molestie nec eu nisl. Pellentesque mattis luctus
                                                                        ultrices. Suspendisse pretium feugiat ipsum nec dapibus. Aenean bibendum vestibulum
                                                                        scelerisque. Curabitur tempus pharetra mollis. Pellentesque rhoncus euismod pellentesque.
                                                                        Nam vulputate purus et neque rutrum dignissim. Duis aliquam erat massa, vel gravida
                                                                        orci. Aenean consectetur, libero non sodales consequat, lorem leo ultrices eros,
                                                                        in porta erat arcu at ante.<br>
                                                                        <br>
                                                                        <br>
                                                                        <br>
                                                                    </div>
                                                                    <div id="divContent2" contenteditable="true">
                                                                        <div style="font-size: 24px;">
                                                                            <b>Article Headline</b></div>
                                                                        <br>
                                                                        <img src="images/pic2.jpg" alt="" width="142" height="110" align="right" style="margin-left: 10px;">Lorem
                                                                        ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum magna enim, volutpat
                                                                        nec imperdiet id, tempor venenatis eros. Aliquam sed velit vitae nibh pulvinar iaculis.
                                                                        Aenean hendrerit, lorem eu luctus cursus, sapien justo auctor ligula, id bibendum
                                                                        lorem leo quis leo. Suspendisse sit amet aliquam orci. Aliquam erat volutpat. Aliquam
                                                                        erat volutpat. Nunc ac justo enim. Morbi eleifend feugiat turpis non placerat. Etiam
                                                                        sed tellus ac lectus lacinia molestie nec eu nisl. Pellentesque mattis luctus ultrices.
                                                                        Suspendisse pretium feugiat ipsum nec dapibus.
                                                                        <br>
                                                                        <br>
                                                                        Aenean bibendum vestibulum scelerisque. Curabitur tempus pharetra mollis. Pellentesque
                                                                        rhoncus euismod pellentesque. Nam vulputate purus et neque rutrum dignissim. Duis
                                                                        aliquam erat massa, vel gravida orci. Aenean consectetur, libero non sodales consequat,
                                                                        lorem leo ultrices eros, in porta erat arcu at ante.
                                                                    </div>
                                                                </td>
                                                                <td width="10">
                                                                    &nbsp;
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left" valign="top">
                                                        <img src="images/bc.gif" alt="" width="465" height="9" style="display: block;">
                                                    </td>
                                                </tr>
                                            </table>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td align="left" valign="top">
                                                        &nbsp;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left" valign="top">
                                                        <div id="divFooter" contenteditable="true" style="font-size: 11px; color: #000000;
                                                            font-family: Arial, Helvetica, sans-serif;">
                                                            <b>Company Address </b>
                                                            <br>
                                                            123 James Street, Suite100,
                                                            <br>
                                                            Long Beach CA, 90000<br>
                                                            (000) 123 4567
                                                            <br>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td width="13%">
                                                                        <b>
                                                                            <img src="images/tweet.gif" alt="" width="24" height="23">
                                                                            <img src="images/facebook.gif" alt="" width="24" height="23"></b>
                                                                    </td>
                                                                    <td width="87%" style="font-size: 11px; color: #000000; font-family: Arial, Helvetica, sans-serif;">
                                                                        <b>Hours: Mon-Fri 9:30-5:30, Sat. 9:30-3:00, Sun. Closed
                                                                            <br>
                                                                            Customer Support: support@companyname.com</b>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left" valign="top">
                                                        &nbsp;
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td align="left" valign="top">
                                &nbsp;
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <input type="button" id="btnSave" name="btnSave" value="Submit" onclick="SaveSubmittedData();" />
    </div>
    <script type="text/javascript">
        // We need to turn off the automatic editor creation first.
        //CKEDITOR.disableAutoInline = true;

        //var neweditable = CKEDITOR.inline('divHeader');
        //        var editor = CKEDITOR.inline('Content1');
        //        var chkeditor = CKEDITOR.inline('Content2');
        //        var divImage = CKEDITOR.inline('divFooter');

        //                CKEDITOR.replace('divHeader',
        //                {
        //                    toolbar: [
        //        		                { name: 'document', groups: ['mode', 'document', 'doctools'], items: ['-', 'Save', 'Preview', 'Print', '-'] },
        //        		                { name: 'clipboard', groups: ['clipboard', 'undo'], items: ['Cut', 'Copy', 'Paste', '-', 'Undo', 'Redo'] },
        //                                { name: 'editing', groups: ['find', 'selection', 'spellchecker'], items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'] },
        //        		                '/',
        //        		                { name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline', 'Subscript', 'Superscript', '-'] },
        //                                { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language'] },
        //                                { name: 'links', items: ['Link', 'Unlink'] },
        //                                { name: 'insert', items: ['HorizontalRule', 'Smiley', 'SpecialChar'] },
        //        	                    '/',
        //                                { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
        //        	                    { name: 'colors', items: ['TextColor', 'BGColor'] },
        //        	                    { name: 'tools', items: ['Maximize'] },
        //        	                    { name: 'others', items: ['-'] }
        //        	                ]
        //                });

        //                CKEDITOR.replace('editor1',
        //                {
        //                    filebrowserBrowseUrl: '../ckfinder/ckfinder.html',
        //                    filebrowserImageBrowseUrl: '../ckfinder/ckfinder.html?Type=Images',
        //                    filebrowserFlashBrowseUrl: '../ckfinder/ckfinder.html?Type=Flash',
        //                    filebrowserUploadUrl: '../ckfinder/core/connector/aspx/connector.aspx?command=QuickUpload&type=Files',
        //                    filebrowserImageUploadUrl: '../ckfinder/core/connector/aspx/connector.aspx?command=QuickUpload&type=Images',
        //                    filebrowserFlashUploadUrl: '../ckfinder/core/connector/aspx/connector.aspx?command=QuickUpload&type=Flash'
        //                });

        //                CKEDITOR.replace('Content1',
        //                {
        //                    toolbar:
        //                            [
        //                                { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
        //        	                   	{ name: 'insert', items: ['Image'] },
        //        	                    { name: 'tools', items: ['Maximize'] }
        //        	                ]
        //                });

        //                CKEDITOR.replace('Content2',
        //                {
        //                    toolbar:
        //                            [
        //                                { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
        //        	                   	{ name: 'insert', items: ['Image'] },
        //        	                    { name: 'tools', items: ['Maximize'] }
        //        	                ]
        //                });

        //        CKEDITOR.replace('divFooter',
        //        {
        //            toolbar:
        //                    [
        //                        { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
        //	                   	{ name: 'insert', items: ['Image'] },
        //	                    { name: 'tools', items: ['Maximize'] }
        //	                ]
        //        });

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
        CKEDITOR.inlineAll();        

    </script>
</body>
</html>

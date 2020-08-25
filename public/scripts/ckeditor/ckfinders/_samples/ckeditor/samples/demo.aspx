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
        #editable, #checkeditable, #neweditable
        {
            padding: 10px;
            float: left;
        }
    </style>
</head>
<body>
    <div id="editable" contenteditable="true">
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
        <p>
            <b>Test Second Apollo 11</b> was the spaceflight that landed the first humans, Americans
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
    <div id="neweditable" contenteditable="true">
        <p>
            <b>Test Third Apollo 11</b> was the spaceflight that landed the first humans, Americans
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
    <script type="text/javascript">
        // We need to turn off the automatic editor creation first.
        CKEDITOR.disableAutoInline = true;

        var editor = CKEDITOR.inline('editable');
        var chkeditor = CKEDITOR.inline('checkeditable');
        var neweditable = CKEDITOR.inline('neweditable');

        var editor = CKEDITOR.replace('editor1');
        CKFinder.setupCKEditor(editor, '/ckfinder/');        
    </script>
    <input type="button" id="btnSave" value="Save" />
</body>
</html>

<?php
class Louis_View_Helper_Ckeditor extends Zend_View_Helper_Abstract{
        public function Ckeditor($name, $height = 348, $width = 900){       	
            if(isset($name)){
	            $key = $_SESSION['akey'];
                return "<script>CKEDITOR.replace('".$name."',{
            height: '".$height."',
            width: '".$width."',
            filebrowserBrowseUrl : '/filemanager/dialog.php?type=2&akey=$key&editor=ckeditor&fldr=',
	filebrowserUploadUrl : '/filemanager/dialog.php?type=2&akey=$key&editor=ckeditor&fldr=',
	filebrowserImageBrowseUrl : '/filemanager/dialog.php?type=1&akey=$key&editor=ckeditor&fldr='
			});</script>";
            }    
        }
    } 
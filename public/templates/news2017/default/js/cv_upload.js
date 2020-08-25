var timestamp = Math.floor(Date.now() / 1000);
	console.log(timestamp);
 var hasvalue = $('#hasupload').val();
  var frmContact = $('#contactForm');
 
console.log(hasvalue);
  

	//  $('#file_upload').uploadifive('upload');
	 frmContact.submit(function (ev) {
	    ev.preventDefault();	   
	  //  console.log(frmContact.serialize() + '&' + datastring);
		// $("#loadding").show();  
		loader.show();   
	    var  nameContact = $('#name').val();
	   
	 var  emailContact = $('#email').val();
	 var address = $('#address').val();
	 var phone = $('#phone').val();
	 
	   if ((nameContact == '') || (nameContact == null)){
		    alert('Hãy nhập trường tên!');
		    return false;
	    }else if((emailContact == '') || (emailContact == null)){
		    alert('Hãy nhập trường Email!');
		     return false;
		}else if((address == '') || (address == null)){
		    alert('Hãy nhập địa chỉ!');
		     return false;
		 }
		 else if((phone == '') || (phone == null)){
		    alert('Hãy nhập điện thoại!');
		     return false;
		 }
		 
	   
        $.ajax({
            type: frmContact.attr('method'),
            url: frmContact.attr('action'),
            data: frmContact.serialize(),
            success: function (data) {
	         //  $("#loadding").hide();    
	         loader.hide();
                console.log(data);
               
               // console.log(hasvalue);
               
               		alert('Bạn đã gửi hồ sơ cho chúng tôi, chúng tôi sẽ trả lời bạn trong thời gian sớm nhất. Xin vui lòng kiểm tra lại email của bạn!');
				  // $(this).fadeOut('fast');
            }
        });

        
    });			
 
  
    
  //  }
    var token = md5('unique_salt' + timestamp);
 
    $('#file_upload').uploadifive({
	            'fileType': ["image\/gif","image\/jpeg","image\/png","application\/pdf","application\/doc","application\/msword","application\/zip","application\/octet-stream","application\/zip-compressed"],
				'multi'			   : false,
				'auto'             : false,
				'fileSizeLimit' : 7500,
				'formData'         : {
				'timestamp' : timestamp,
				'token'     : token,
 				                     },
				 'buttonText'  : 'Đính kèm CV', 
				'queueID'          : 'queue',
				'uploadScript'     : '/default/recruitment/upload',
				'onError'      : function(errorType) {
				switch(errorType) {
				case '404_FILE_NOT_FOUND':
					alert('File not found!');
					break;
				case 'FORBIDDEN_FILE_TYPE':
					alert('Không hỗ trợ định dạng này');
					break;
				case 'FILE_SIZE_LIMIT_EXCEEDED':
				alert('Exceeded capacity');
				break;
				case 'QUEUE_LIMIT_EXCEEDED':
				alert('Please delete the current image file before re-selecting another file!');
				//j('.stdelete').trigger('click');
				//j('#file_upload').uploadifive('clearQueue').trigger('click');
				break;
				default:
				alert(errorType);
				alert('Sorry, please try again');
				break;
				   }
                } ,
                'onSelect' : function(queue) {
				//$('#hasupload').val(2);
				$("#submit_default"). html("<a class='btn btn-primary' href=\"javascript:$('#file_upload').uploadifive('upload')\">GỬI THÔNG TIN</a>");
				} ,
                 'onCancel'     : function() {
           //  alert('The file ' + file.name + ' was cancelled!');
           $("#submit_default"). html("<button type='submit' class='btn btn-primary'>GỬI THÔNG TIN</button>");
                 } ,
             'onProgress': function(file, e) {
				 //  console.log(e);
            	
				 },
        
                'onUpload'     : function(filesToUpload) {
          
					} ,
			'onUploadComplete' : function(file, data, response) {
				$("#filename").val(file.name);
				$("#filetype").val(file.type);
				//datastring = 'namefile=' + file.name;
			frmContact.submit();
				 // console.log(file);		
				 // console.log(data);		
				 	
			}
	
	}); 
    

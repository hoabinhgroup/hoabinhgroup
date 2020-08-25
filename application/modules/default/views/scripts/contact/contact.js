
 var frmContact = $('#contactForm');
    frmContact.submit(function (ev) {
	    ev.preventDefault();	   
	    var  nameContact = $('#name').val();
	   var  emailContact = $('#email').val();
	   var  phone = $('#phone').val();
	    if ((nameContact == '') || (nameContact == null)){
		    alert('Xin nhập tên của bạn!');
		    return false;
	    }else if(emailContact == ''){
		    alert('Xin nhập địa chỉ email!');
		     return false;
		 }else if(phone == ''){
		    alert('Xin nhập số điện thoại');
		     return false;
		 }
	    loader.show();			
 
        $.ajax({
            type: frmContact.attr('method'),
            url: frmContact.attr('action'),
            data: frmContact.serialize(),
            success: function (data) {
                  console.log(data);
                   loader.hide();
				   alert('Yêu cầu đã được gửi thành công. Cám ơn Quý khách đã gửi yêu cầu.');
				  // $(this).fadeOut('fast');
		
                
              
            }
        });

        
    });
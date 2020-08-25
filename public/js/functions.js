 function ChangeToSlug()
            {
                var str;
 
                //Lấy text từ thẻ input title 
                str = document.getElementById("name").value;
 
                //Đổi chữ hoa thành chữ thường
                str = str.toLowerCase();
 
               // xóa dấu
			    str = str.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, 'a');
			    str = str.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, 'e');
			    str = str.replace(/(ì|í|ị|ỉ|ĩ)/g, 'i');
			    str = str.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, 'o');
			    str = str.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, 'u');
			    str = str.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, 'y');
			    str = str.replace(/(đ)/g, 'd');        
			    
			     // Xóa ký tự đặc biệt
				str = str.replace(/([^0-9a-z-\s])/g, '');
				
				// Xóa khoảng trắng thay bằng ký tự -
				str = str.replace(/(\s+)/g, '-');
				
				// xóa phần dự - ở đầu
				str = str.replace(/^-+/g, '');
				
				// xóa phần dư - ở cuối
				str = str.replace(/-+$/g, '');  
				     
                document.getElementById('ident').value = str;
            }
            
            
     function ChangeToSlugRec()
            {
                var str;
 
                //Lấy text từ thẻ input title 
                str = document.getElementById("title").value;
 
                //Đổi chữ hoa thành chữ thường
                str = str.toLowerCase();
 
               // xóa dấu
			    str = str.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, 'a');
			    str = str.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, 'e');
			    str = str.replace(/(ì|í|ị|ỉ|ĩ)/g, 'i');
			    str = str.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, 'o');
			    str = str.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, 'u');
			    str = str.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, 'y');
			    str = str.replace(/(đ)/g, 'd');        
			    
			     // Xóa ký tự đặc biệt
				str = str.replace(/([^0-9a-z-\s])/g, '');
				
				// Xóa khoảng trắng thay bằng ký tự -
				str = str.replace(/(\s+)/g, '-');
				
				// xóa phần dự - ở đầu
				str = str.replace(/^-+/g, '');
				
				// xóa phần dư - ở cuối
				str = str.replace(/-+$/g, '');  
				     
                document.getElementById('ident').value = str;
            }       
            
      function ChangeToSlugMenu()
            {
                var str;
 
                //Lấy text từ thẻ input title 
                str = document.getElementById("name").value;
 
                //Đổi chữ hoa thành chữ thường
                str = str.toLowerCase();
 
               // xóa dấu
			    str = str.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, 'a');
			    str = str.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, 'e');
			    str = str.replace(/(ì|í|ị|ỉ|ĩ)/g, 'i');
			    str = str.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, 'o');
			    str = str.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, 'u');
			    str = str.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, 'y');
			    str = str.replace(/(đ)/g, 'd');        
			    
			     // Xóa ký tự đặc biệt
				str = str.replace(/([^0-9a-z-\s])/g, '');
				
				// Xóa khoảng trắng thay bằng ký tự -
				str = str.replace(/(\s+)/g, '-');
				
				// xóa phần dự - ở đầu
				str = str.replace(/^-+/g, '');
				
				// xóa phần dư - ở cuối
				str = str.replace(/-+$/g, '');  
				     
                document.getElementById('link').value = str;
            }
            
       function ChangeToSlugPage()
            {
                var str;
 
                //Lấy text từ thẻ input title 
                str = document.getElementById("name").value;
 
                //Đổi chữ hoa thành chữ thường
                str = str.toLowerCase();
 
               // xóa dấu
			    str = str.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, 'a');
			    str = str.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, 'e');
			    str = str.replace(/(ì|í|ị|ỉ|ĩ)/g, 'i');
			    str = str.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, 'o');
			    str = str.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, 'u');
			    str = str.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, 'y');
			    str = str.replace(/(đ)/g, 'd');        
			    
			     // Xóa ký tự đặc biệt
				str = str.replace(/([^0-9a-z-\s])/g, '');
				
				// Xóa khoảng trắng thay bằng ký tự -
				str = str.replace(/(\s+)/g, '-');
				
				// xóa phần dự - ở đầu
				str = str.replace(/^-+/g, '');
				
				// xóa phần dư - ở cuối
				str = str.replace(/-+$/g, '');  
				     
                document.getElementById('name_clean').value = str;
            }  
                    
    	function setNavigation() {	
	var path = window.location.pathname;
    path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);
	//alert(path);
	
	$(".navli-drop a").each(function () {
        var href = $(this).attr('href');
       // alert(path.substring(0, href.length)); return false;
        if (path === href) {
	         $(this).closest('li').addClass('active');
            $(this).closest('li').parent().css('style','display:block;');
             $(this).closest('li').parent().parent().addClass('open').addClass('active');
        }
    });
    }
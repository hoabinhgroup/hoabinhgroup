$(document).ready(function(){
    $("#owl-video").owlCarousel({
    nav : false,
     items : 1,
     navText: false,
      autoPlay :true,
   autoplayHoverPause :true
  });
  
  var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
if (isMobile) {
  $(".news-description-detail > .description > table").css("width","100%");
  $(".news-description-detail > .description > table > tbody > tr > td").css("padding","5px");
  
   $(".news-description-detail > .description > p > iframe").css("width","100%");
   $(".news-description-detail > .description > p > iframe").css("height","150px");
 
   }
     $(".news-description-detail > .description > div > img").css("width","100%");
   $(".news-description-detail > .description > div > img").css("height","auto");
   });
   
  	
						
	
	function display_data(response)
{
	var data = $.parseJSON(response);
  //console.log(data); 
 // return false;
 
  
    var item = '';
    if(data['rec'] == ''){
	 item += '<h3>Không có kết quả!</h3>';
 	}
      $.each( data['rec'], function( key, value ) {
	  
	  var feature = '';
      if(value.featured == '1'){
	      feature = 'title-featured';
      }
      item += '<div class="item-join">';
      item += '<div class="panel-title '+ feature +'">';
      item += '<img src="/public/templates/news2017/default/img/icons/hot-red.png"/> ';
      item +=  value.title;
      item += '</div>';
      item += '<div class="row">';
      item += '<div class="col-md-10">';
      item += '<div class="container-fluid">';
      item += '<div class="panel-body">';
      item += '<div class="col-md-12">';
      item += '<p><i class="fa fa-user" aria-hidden="true"></i> <b>Số lượng</b>: '+value.people+' &nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-clock-o" aria-hidden="true"></i> <b>Hạn tuyển dụng</b>: '+value.expired+'</p>';
      item += '<p><i class="fa fa-map-marker" aria-hidden="true"></i> <b>Địa điểm làm việc</b>: '+value.location+'</p>';
      item += '<p><i class="fa fa-home" aria-hidden="true"></i> <b>Công ty</b>: '+value.company+'</p>';
      item += '<p><i class="fa fa-newspaper-o" aria-hidden="true"></i> <b>Nhóm ngành nghề</b>: '+value.work_group+'</p>';
      
      item += '</div>';
      item += '</div>';
      item += '</div>';
      item += '</div>';
      item += '<div class="col-md-2"><div class="button-join"><a style="color:#05235F" href="/tuyen-dung/'+value.ident+'.html">XEM CHI TIẾT</a></div></div>';
      item += '</div>';  //row
   
      item += '</div>';   //.panel-title
      item += '</div>';  // .item-join
    
});
	
 $('#join-info').html(item); 
 //console.log(data['paging']);
 // Thay đổi nội dung phân trang
  $('#paging').html(data['paging']);

}	 
	
	
	var current = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
if($.isNumeric(current) == true){
	current_page = current;
}else{
	current_page = 1;
}

	 $.ajax({
    type: "POST",
    url: '/default/recruitment/response',
    data: {page: current_page},
    success: function(response)
    {	  
	   // loader.show();
	   // $('#join-info').html('');
	// console.log( response);
	    // alert(1);            
    	display_data(response);
		//loader.hide();
    }
    
    
});


$('.join-description').on('click','#paging a', function ()
             {
                //loader.show();
                 var url = $(this).attr('href');
                 var current = url.substring(url.lastIndexOf('/') + 1);
                 if($.isNumeric(current) == true){
				 page = current;
				}else{
				 page = 1;
				 }
                  //return false;
           
                 $.ajax({
                     url : '/default/recruitment/response',
                     type : 'POST',
                   //  dataType : 'json',
                     data: {page: page},
                     success : function (result)
                     {
	                // loader.hide();
                     	 	display_data(result);
                              
                             // Thay đổi URL trên website
                             window.history.pushState({path:url},'',url);

                        
                     }
                 });
                 return false;
             });
             
      var frmSearch = $('#louis-form-search');
    frmSearch.submit(function (ev) {
	    //loader.show();
	    ev.preventDefault();	   
	           $.ajax({
            type: frmSearch.attr('method'),
            url: frmSearch.attr('action'),
            data: frmSearch.serialize(),
            success: function (data) {
	           
               // console.log(data);
				 display_data(data);
				  // $(this).fadeOut('fast');
				//  loader.hide();
                
              
            }
        });

        
    });   
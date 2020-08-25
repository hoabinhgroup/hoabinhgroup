<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Thư pháp tri ân quý khách hàng thân thiết</title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="">

	<!-- css -->
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">

	<link href="css/style.css?v=1.8" rel="stylesheet">
	<link href="http://vsem-2019.org/public/frontend/assets/css/wait.css" rel="stylesheet">
	<link href="css/responsive.css?v=1.6.8" rel="stylesheet">
	<link href="css/countdown.css?v=1.6" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">
	<link href="css/owl.theme.default.min.css" rel="stylesheet">
	<link href="css/toast.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css" rel="stylesheet">
	
	<!-- js -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	

</head>
<body>

	 <?php 
		 
		 
		 
	function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
     }
	?>
	<?php
		 include_once("content.html"); 
		 ?>

	
	



	
	<script src="js/countdown.js?v=1"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="http://vietnamevents.com/public/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.toast.min.js"></script>
<script type="text/javascript" src="http://vietnamevents.com/public/js/app.custom.js"></script>
<script type="text/javascript" src="http://vietnamevents.com/public/js/wait.js"></script>
<script type="text/javascript" src="js/jquery.louisForm.js"></script>
	
	
	<script type="text/javascript">
		
	$.validator.addMethod("uniqueEmail", function(value, element, params) {
	var isSuccess = false;	
	$.ajax({
      type: "POST",
       url: "/default/index/validemail",
      data: "checkEmail="+value,
      dataType:"json",
      async: false,
   success: function(msg)
   {
	  
	 isSuccess = msg.status ? true : false;
	   
   	 
   }});
  // console.log(isSuccess);
    return isSuccess;

 }, jQuery.validator.format("Email này đã được đăng ký"));		
		
	$.validator.addMethod("valueNotEquals", function(value, element, arg){
			
  return arg !== value;
 }, "Value must not equal arg.");
 
  $.validator.addMethod("uniqueMobile", function(value, element, params) {
	var isSuccess = false;	
	$.ajax({
      type: "POST",
       url: "/default/index/validmobile",
      data: "checkMobile="+value,
      dataType:"json",
      async: false,
   success: function(msg)
   {
	  
	 isSuccess = msg.status ? true : false;
	   
   	 
   }});
  // console.log(isSuccess);
    return isSuccess;

 }, jQuery.validator.format("Điện thoại này đã được đăng ký"));
		

	$(document).ready(function(){

   $("#owl-caligraphy").owlCarousel({
   	 nav : true,
     items : 2,
     navText: ['<a class="left carousel-control" data-slide="prev"> <img src="http://hoabinh-group.com/public/templates/news2017/default/img/btn-l.png" /> </a>','<a data-slide="next" class="right carousel-control"><img src="http://hoabinh-group.com/public/templates/news2017/default/img/btn-r.png" /></a>'],
      autoplay :true,
      loop:true,
      smartSpeed:100,
	  autoplayHoverPause :true,
	  responsiveRefreshRate: true,
	  responsiveClass: true,
	  responsive : {
          0 : {
              items: 1,
          },
          768 : {
              items: 2
          },
          960 : {
              items: 5,
              nav: true
          }
       
      }
   
	  
  });
  });
		
	function selectCaligraphy(o)
	{

	$("html, body").animate({
        scrollTop: $('#owl-caligraphy').offset().top 
    }, 500);
	}
	
	$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
	
	function selectCaligraphyX(o){
		let id = $(o).attr('data-id');
		$("#calitext").val(id).change();
		$("html, body").animate({
        scrollTop: $('#care-form').offset().top 
    }, 500);
	}
	//data-rule-required="true" data-msg-required="Bắt buộc" data-rule-valueNotEquals="true" data-msg-valueNotEquals="Bắt buộc" multiple="true"
	$(".used_service").on('click',function(){
		//console.log('valradio',$(this).val());
		if($(this).val() == 1){
		/*	$("#section_registered").show();
			$("#service").attr('data-rule-required', true);
			$("#service").attr('data-msg-required', "Bắt buộc");
			$("#service").attr('data-rule-valueNotEquals', true);
			$("#service").attr('data-msg-valueNotEquals', "Bắt buộc");
			$("#calitext").attr('data-rule-required', true);
			$("#calitext").attr('data-msg-required', "Bắt buộc");
			$("#calitext").attr('data-rule-valueNotEquals', true);
			$("#calitext").attr('data-msg-valueNotEquals', "Bắt buộc");*/
			
		}else{
		/*	$("#section_registered").hide();
			$("#service-error").hide();
			$("#calitext-error").hide();
			$("#service").attr('data-rule-required', false);
			$("#service").attr('data-msg-required', "Bắt buộc");
			$("#service").attr('data-rule-valueNotEquals', false);
			$("#service").attr('data-msg-valueNotEquals', "Bắt buộc");
			$("#calitext").attr('data-rule-required', false);
			$("#calitext").attr('data-msg-required', "Bắt buộc");
			$("#calitext").attr('data-rule-valueNotEquals', false);
			$("#calitext").attr('data-msg-valueNotEquals', "Bắt buộc");*/
		}
	});
		
		
		$(document).ready(function(){
		    var contact =  $("#care-form");
		    

		    
	      contact.louisForm({
	  			isModal: false,	
	  			beforeAjaxSubmit: function(){
		  			run_waitMe($("body"), 1, 'bounce');
	  			},		
				onSubmit: function(result) {
	            	//console.log('onSubmit',result);	 
            	},
            	onAjaxSuccess: function(result){
	            	console.log('onAjaxSuccess',result);	 
	            	$("body").waitMe('hide');
	            	if(result.success){
						alert(result.message);
	            	}
            	},
            	onSuccess: function(result) {
	            		
	            	console.log('onSuccess',result);
				},
				onError: function(response) {
					console.log(response);
				},				
			});
	 });
	 
	 var waitme = 'Thông tin đang được gửi. Bạn vui lòng chờ trong giây lát';
function run_waitMe(el, num, effect){
		text = waitme + '...';
		fontSize = '';
		switch (num) {
			case 1:
			maxSize = '';
			textPos = 'vertical';
			break;
			case 2:
			text = '';
			maxSize = 30;
			textPos = 'vertical';
			break;
			case 3:
			maxSize = 30;
			textPos = 'horizontal';
			fontSize = '18px';
			break;
		}
		el.waitMe({
			effect: effect,
			text: text,
			bg: 'rgba(255,255,255,0.7)',
			color: '#000',
			maxSize: maxSize,
			waitTime: -1,
			source: 'img.svg',
			textPos: textPos,
			fontSize: fontSize,
			onClose: function(el) {}
		});
	}

			
//var data = 
//console.log(typeOf data);

	
	//	alert(tag);
	
	

	
	</script>
</body>
</html>
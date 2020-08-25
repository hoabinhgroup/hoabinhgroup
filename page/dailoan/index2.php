<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Taiwan Medical Miracles</title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="">

	<!-- css -->
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css">

	<link href="style.css?v=1.4" rel="stylesheet">
	<link href="responsive.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css" rel="stylesheet">

<link href="http://vietnamevents.com/public/css/wait.css" media="screen" rel="stylesheet" type="text/css" >
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	

</head>
<body>
<style>
	.help-block{
		position: absolute;
		right: -50px;
		top: 0px;	
		color: #E1E93C !important;
	}
</style>
	 <?php 
		 
		 
		 
	function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
     }
	?>
	<section id="main">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<form id="registration-form" class="form-horizontal" action="/default/index/registration2" method="POST">
					<!--<p><h1><span>Đăng Ký</span></h1></p>-->
				<div id="form-body">
					<div class="form-group">
					<label class="control-label col-sm-4">Tên Công ty</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="company" name="company" placeholder="Tên Công ty" 
						data-rule-required="true" 
						data-msg-required="Required">
					</div>
					
				</div>
				<div id="form-body">
					<div class="form-group">
					<label class="control-label col-sm-4">Họ tên</label>
					<div class="col-sm-8">
					<input type="text" class="form-control" id="name" name="name" placeholder="Tên người tham dự" data-rule-required="true" 
						data-msg-required="Required">
					</div>
				</div>
				<div id="form-body">
					<div class="form-group">
					<label class="control-label col-sm-4">Chức vụ</label>
					<div class="col-sm-8">
					<input type="text" class="form-control" id="job" name="job" placeholder="Chức vụ" data-rule-required="true" 
						data-msg-required="Required">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4">Số người tham dự</label>
					<div class="col-sm-8">
					<input type="text" class="form-control" id="number" name="number" placeholder="Nhập số lượng người tham dự">
					</div>
					</div>
				
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4">Số điện thoại</label>
					<div class="col-sm-8">
					<input type="text" class="form-control" id="phone" name="phone" placeholder="Số điện thoại" data-rule-required="true" 
						data-msg-required="Required">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4">Email</label>
					<div class="col-sm-8">
					<input type="text" class="form-control" id="email" name="email" placeholder="Email" data-rule-required="true" 
						data-msg-required="Required">
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label col-sm-4">&nbsp;</label>
					<div class="col-sm-8">
					<button id="submitCali" type="submit" class="btn btn-info form-control"><span>Đăng ký</span></button>
					</div>
				</div>
				
				</div>	
				
			</form>
			</div>
		</div><!-- .row -->
		
	</section>
	

	<!-- js -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>

<script type="text/javascript" src="http://vietnamevents.com/public/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="http://vietnamevents.com/public/js/app.custom.js"></script>
<script type="text/javascript" src="http://vietnamevents.com/public/js/wait.js"></script>
<script type="text/javascript" src="http://vietnamevents.com/public/js/jquery.louisForm.js"></script>
	
		
	<script type="text/javascript">
		function selectCaligraphyX(o){
		let id = $(o).attr('data-id');
		$("#number").val(id).change();	
		}
		
		$(document).ready(function(){
		    var contact =  $("#registration-form");
	      contact.louisForm({
	  			isModal: false,	
	  			beforeAjaxSubmit: function(){
		  			run_waitMe($("#registration-form"), 1, 'bounce');
	  			},		
				onSubmit: function(result) {
	            	console.log('onSubmit',result);	 
            	},
            	onAjaxSuccess: function(result){
	            	console.log('onAjaxSuccess',result);	 
	            	$("#registration-form").waitMe('hide');
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
	 
	 var waitme = 'Please wait';
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
		
	</script>
</body>
</html>
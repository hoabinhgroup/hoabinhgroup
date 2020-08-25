<?php
		

	//EDIT VALUES TO FIT YOUR NEEDS
		
	$toplength=10;		//maximal number of items in top list
	$minvotes=1;		//minimum votes required to show up in toplist
	$display='stars'; 	//stars or bars
	
		

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Top List</title>
	<meta name="description" content="Rating system, 5 stars, jquery plugin, php, ajax">
	<meta name="author" content="Sandi">

	<!-- Bootstrap -->
	<link href="admin/css/bootstrap.min.css" rel="stylesheet">
	<style type="text/css">
		body {
		padding-top: 0px;
		padding-bottom: 40px;
		}
	</style>		
	
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	
	<!-- Including jQuery and rating plugin -->
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.5stars.js"></script>
				
	<script type="text/javascript">
	   //display stars on document ready
		$(document).ready(function(){						
			$('.stars').rating({
				skin: 'skins/skin.png',
				step: 0,
				showtext : false,   
				displaymode: true				
			});
		});
	</script>
	
	
</head>
<body>
	
	<header class="jumbotron" id="overview">
		<div class="inner-container">
		<h1>Top</h1>    
		</div>
	</header>
	

	<div class="container">

		<div class="pull-left">			
			<h3>List of items</h3>			
		</div>	
		
				
		<!-- Print the toplist -->	
		<?php
		
			include 'admin/php/public_top.php';
		
		?>
		

	</div>		


	<div class="clearfix"></div>
	<hr>
	<div class="container"> 
		<footer>
			<p>&copy; <a href="http://codecanyon.net/user/Sandi/portfolio" target="_blank">Sandi</a></p>
		</footer>	 
    </div> 
	
	
	
 
  </body>
</html>
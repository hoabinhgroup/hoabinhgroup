<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Five Stars Admin Area V2</title>
		<meta name="description" content="Rating system, 5 stars, jquery plugin, php, ajax">
		<meta name="author" content="Sandi">

		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<?php
		if(isset($stylefile)){
			echo '<link href="'.$stylefile.'" rel="stylesheet">';
		}
		if(isset($style)){
			echo '<style type="text/css">'.$style.'</style>';
		}
		?>
		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Favicons
		================================================== -->
		<link rel="shortcut icon" href="img/favicon.ico">
		<link rel="apple-touch-icon" href="img/apple-touch-icon.png">
		<link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png">

	
	</head>
	<body>
<?php
	if(!$showNavigation){
?>
		<header class="jumbotron" id="overview">
			<div class="inner-container">
			<h1>Five Stars V2 (Admin Area)</h1>    
			</div>
		</header>
<?php
	}else{
		include 'php/navigation.php';
	}
?>
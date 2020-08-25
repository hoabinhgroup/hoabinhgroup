<?php
include 'php/redirect.php';

$admindirectory=dirname(__FILE__);
define('ADMIN',     $admindirectory.'\\');
//echo 'admin path: '.ADMIN;  //to be used on addons pages as ADMIN

//page related styles
$style='
		body {
		padding-top: 60px;
		padding-bottom: 40px;
		}

		';
$showNavigation=true;
$pageIndex=3;
include 'php/head.php';

?>			
	
	
<div class="container">
	
	
	
	<?php
		$addons = glob('addons/*',GLOB_ONLYDIR );
		
		
		if(count($addons)<1){
			echo '<div class="alert alert-info">No addons found. All available addons can be found <a href="http://codecanyon.net/user/Sandi/portfolio?ref=Sandi"><b>here</b></a>.</div>';
		}else{
			echo '<div class="alert alert-info">All available addons can be found <a href="http://codecanyon.net/user/Sandi/portfolio?ref=Sandi"><b>here</b></a>.</div>';
		
			
			//print addons here
			echo '<h3>Downloaded addons</h3><hr>';
			
			foreach($addons as $addon){
				
				$phpinfo = $addon.'/info.php';
				$htmlinfo = $addon.'/info.html';
				$textinfo = $addon.'/info.txt';
				
				if (file_exists($phpinfo)){
					@include($phpinfo);				
				}elseif (file_exists($htmlinfo)){
					$info = file_get_contents($htmlinfo);
					$info = str_replace('assets/', $addon.'/assets/', $info);
					echo $info.'<div class="clearfix"></div><hr>';
				}elseif (file_exists($textinfo)){
					$info = file_get_contents($textinfo);
					echo $info.'<div class="clearfix"></div><hr>';
				}else{
					echo '<strong>'.$addon.'</strong> : no additional info available';
				}
				
				
			}
			
		}
	?>
	
	
	

</div>   
	
<?php

include 'php/footer.php';
include 'php/closehtml.php';
?>
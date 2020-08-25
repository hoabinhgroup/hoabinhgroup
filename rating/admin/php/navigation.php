<div class="navbar navbar-fixed-top ">
  <div class="navbar-inner">
	<div class="container">
	  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </a>
	  <a class="brand" href="index.php">Five Stars v2</a>
	  <div class="nav-collapse collapse">
		<ul class="nav">
			
			<?php
				$pages=array('Statistics','Banlist');
				$refs=array('index.php','banlist.php');
				$inxs=array(0,1);
				
				//account for future addons
				$addons = glob('addons/*',GLOB_ONLYDIR );
				foreach($addons as $addon){
					$addonIDfile = $addon.'/id.php';
					
					if (file_exists($addonIDfile)){
						@include $addonIDfile;
						$pages[]=$title;
						$refs[]=$ref;
						$inxs[]=$id;
					}
					
				}
				
				array_push($pages,'Settings','Addons');
				array_push($refs,'settings.php','addons.php');
				array_push($inxs,2,3);
				
				for($i=0;$i<count($pages);$i++){
					$cref=basename($_SERVER['PHP_SELF']);
					
					if($inxs[$i]==$pageIndex || strcasecmp($cref,$refs[$i])==0) echo '<li class="active"><a href="#">'.$pages[$i].'</a></li>';
					else echo '<li><a href="'.$refs[$i].'">'.$pages[$i].'</a></li>';
				}
			?>
		           
		</ul>
		
		<ul class="nav pull-right">				
			<li><a href="php/logout.php">Sign out</a></li>              
		</ul>
		
	  </div><!--/.nav-collapse -->
	</div>
  </div>
</div>
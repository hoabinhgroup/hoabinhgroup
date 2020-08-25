<?php
include 'php/redirect.php';

//page related styles
$stylefile='css/datepicker.css';

$style='
		body {
			padding-top: 60px;
			padding-bottom: 40px;
		}
		
		div.graph
		{
			width: 40%;
			height: 300px;		
		}
		
		.legendtitle{
			font-weight:bold;
			color:#666;
		}
		
		a.disabled{
			font-weight:bold;
			text-decoration:none;
			cursor:default;
			color:#666;
		}
		
		.pagination .active{font-size:28px;}
		
		.pagination-centered li.active{font-weight:bold;}
		.circle{display:block;width:100px;height:100px;border-radius:50px;font-size:20px;color:#fff;line-height:100px;text-align:center;background:#000;}
		.block{float:left;margin:20px 20px 40px 20px;}
		.legendtitle{margin-top:10px;}
		
		
		
		.stylish{display:block;width:100px;height:100px;border-radius:66px;border:4px double #ccc;font-size:20px;color:#222;line-height:100px;text-align:center;text-decoration:none;background:#ddd;}
		.stylish:hover{border:4px double #333;color:#000;text-decoration:none;}
		
		';
$showNavigation=true;
$pageIndex=0;
include 'php/head.php';
include 'php/functions.php';

$show=array(2,5,50);	//items per page
$cols=array('n','title','mean','votes','validtill','voteperiod');

//page settings
if (isset($_GET['o'])){$order=$_GET['o']; if($order=='' || $order==0) {$order=-1;}}else {$order=-1;}
if (isset($_GET['s'])){$sortby=$_GET['s']; if($sortby=='' || $sortby<0  || $sortby>=count($cols)) {$sortby=0;}}else {$sortby=0;}
if (isset($_GET['n'])){$showIndex=$_GET['n']; if($showIndex=='' || $showIndex<0 || $showIndex>=count($show)) {$showIndex=1;}}else {$showIndex=1;}
if (isset($_GET['p'])){$page=$_GET['p']; if($page=='' || $page<1) {$page=1;}}else {$page=1;}
if (isset($_GET['e'])){$err=$_GET['e']; if($err=='') {unset($err);}}




$total=getTotals();
$minPopularityVotes=getMinVotes();

$totalmean=($total["votes"]>0)?$total["summean"]/$total["votes"]:0;
if($showIndex>0){if($total["items"]<$show[$showIndex-1]) $showIndex--; }

$maxpage=ceil($total["items"]/$show[$showIndex]);
if($page>$maxpage) $page=$maxpage;

echo '<div class="container">';

if($total["votes"]>0 && $total["items"]>0){
?>	

      <div class="">
		
		  <h1 class="pull-left offset50"><?php echo $total["items"];?> <small>items</small> </h1>
		  <h1 class="pull-left offset50"><?php echo $total["votes"];?> <small>votes</small> </h1>
		  <h1 class="pull-left offset50"><?php echo (round(10*$total["summean"]/$total["votes"])/10).' %';?> <small>mean given rate</small> </h1>
			
			
      </div>
</div>

<hr>

<div class="container">

		<div class="">
			<div id="message-box"></div>
			
			<ul class="nav nav-tabs">
			  <li><a href="index.php"><i class="icon-list"></i> List</a></li>
			  <li class="active"><a href="#"><i class="icon-signal icon-gray"></i> Graph</a></li>		
			</ul>
		</div>	
			
			
			<?php
			if($demoMode==true) echo '<div id="demo-info"></div>';
			if(isset($err)) echo '<div class="alert alert-error"><h1>Something went wrong</h1><p>ERROR: '.$err.'</p></div>';
			$t=time();
			?>
			
			<div class="pull-left"><h3 class="top-padding">Number of votes over time</h3></div>	
			<div class="pull-right interval">				
				<a href="javascript:void(0);">today</a> /
				<a href="javascript:void(0);">this month</a> /			
				<a href="javascript:void(0);">this year</a>			
			</div>
		
			<!--
			<a href="" id="datapoints">data</a>
			-->
			
			
		
			<div class="clearfix"></div>	
		
			<div id="placeholder" style="width:100%;height:300px;"></div>
		
		
		
			<div class="pagination pagination-centered datanavigation">
				<ul class="pull-left">
					<li><a href="javascript:void(0);"><?php echo date("j", $t-86400);?></a></li>	
					<li><a href="javascript:void(0);"><?php echo date("M", strtotime("-1 months"));?></a></li>		
					<li><a href="javascript:void(0);"><?php echo date("Y", $t)-1;?></a></li>		
				</ul>
				<ul class="pagination-centered" style="border:1px solid #333;">				
					<li><a href="javascript:void(0);"><?php echo date("j", $t);?></a></li>
					<li class="active"><a href="javascript:void(0);"><?php echo date("M", $t);?></a></li>
					<li><a href="javascript:void(0);"><?php echo date("Y", $t);?></a></li>					
				</ul>
				<ul class="pull-right">				
					<li class="disabled"><a href="javascript:void(0);"><?php echo date("j", $t+86400);?></a></li>
					<li class="disabled"><a href="javascript:void(0);"><?php echo date("M", strtotime("+1 months"));?></a></li>
					<li class="disabled"><a href="javascript:void(0);"><?php echo date("Y", $t)+1;?></a></li>					
				</ul>
			</div>
		
		
		<div class="muted"><small>* <b>Date/Time</b> corresponds to server time and may differ from your local time. Current server time: <?php echo date('r',time());?></small></div>
		
		
		<hr>
		<div class="pull-left"><h3 class="top-padding">Number of votes VS given rate</h3></div>	
		<div class="pull-right step">				
			step*: <a href="javascript:void(0);">5% (quarter star)</a> /
			<a href="javascript:void(0);" class="disabled">10% (half star)</a> /			
			<a href="javascript:void(0);">20% (one star)</a>			
		</div>
	
		<div class="clearfix"></div>
	
		<div id="placeholder2" style="width:100%;height:300px;"></div>
			
		
		<br>
		<br>
		<div class="muted"><small>* <b>step</b> - given in %. In case of 5 stars rating: 1 star = 20%, 2 stars = 40%  . . .  5 stars = 100%</small></div>
		
		
		<hr>
		
		
		<!-- uncomment next line to add some functionality
			<div id="togglebtn">show / hide</div>
		-->
		
		
		<div id="d1">
			<div class="pull-left"><h3 class="top-padding">Most remarkable</h3></div>	
			<div class="pull-right"><h3 class="top-padding">Most rated</h3></div>
			
			<div class="clearfix"></div>	
			
			<div id="donut1" class="graph pull-left"></div>
			<div id="donut2" class="graph pull-right"></div>
		</div>	
		<div class="clearfix"></div>
		<div id="d2" style="display: none">
			<div><h3 class="top-padding">Most remarkable</h3></div>				
			<div id="cir1"></div>
			
			<div class="clearfix"></div>
			
			<div class="pull-left"><h3 class="top-padding">Most rated</h3></div>	
			<div class="clearfix"></div>
			<div id="cir2"></div>
		</div>	
		
		<div class="clearfix"></div>
		<br>
		<br>
		<div class="muted"><small>* <b>Most remarkable</b> items - items with very hight mean rate or with a lot of votes. Formula: mean*votes.</small></div>
		
</div>
		
	
	
<?php

}else{
	echo '</div><div class="container"><h1 class="pull-left offset50">There are no items in database</h1></div>';
}
 
include 'php/footer.php';
?>

<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
<script src="js/jquery.flot.js"></script>    
<script src="js/jquery.flot.pie.js"></script>    
<script src="js/jquery.flot.resize.js"></script> 
<script src="js/application.graph.js"></script> 
 
<?php
include 'php/closehtml.php';
?>
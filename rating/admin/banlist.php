<?php
include 'php/redirect.php';

//page related styles
$style='
		body {
		padding-top: 60px;
		padding-bottom: 40px;
		}

		h1 a {color:#000;}
		
		
		';
$showNavigation=true;
$pageIndex=1;
include 'php/head.php';
include 'php/functions.php';

$show=array(10,20,40);	//items per page
$cols=array('n','ip','time');

//page settings
if (isset($_GET['o'])){$order=$_GET['o']; if($order=='' || $order==0) {$order=-1;}}else {$order=-1;}
if (isset($_GET['s'])){$sortby=$_GET['s']; if($sortby=='' || $sortby<0  || $sortby>=count($cols)) {$sortby=2;}}else {$sortby=2;}
if (isset($_GET['n'])){$showIndex=$_GET['n']; if($showIndex=='' || $showIndex<0 || $showIndex>=count($show)) {$showIndex=1;}}else {$showIndex=1;}
if (isset($_GET['p'])){$page=$_GET['p']; if($page=='' || $page<1) {$page=1;}}else {$page=1;}
if (isset($_GET['e'])){$err=$_GET['e']; if($err=='') {unset($err);}}


	$total=getBannedTotals();

	echo '<div class="container">';
	
	echo '<div class="">';
	echo '<h1 class="pull-left offset50">'.$total["sum"].' <small>banned IPs</small></h1>';
	echo '</div></div>';

	if($total["sum"]>0){
	
	?>			
		
			
		

	<hr>

	<div class="container">

		<div class="">
			<div id="message-box"></div>
			<?php
			if($demoMode==true) echo '<div id="demo-info"></div>';
			
			
			//ALL ITEMS DETAILS
			
			if($showIndex>0){if($total["sum"]<$show[$showIndex-1]) $showIndex--; }
			$maxpage=ceil($total["sum"]/$show[$showIndex]);
			if($page>$maxpage) $page=$maxpage;
			
			
			if(isset($err)) echo '<div class="alert alert-error"><h1>Something went wrong</h1><p>ERROR: '.$err.'</p></div>';
			
			
			echo '<div class="pull-left"><h3 class="top-padding">List of banned IPs</h3></div><br/>'; 						
			echo '<div class="pull-right">'; 						
			for($i=0;$i<count($show);$i++){
				if($total["sum"]>$show[$i<1?0:$i-1]){
					if($i!=$showIndex) echo '<a href="'.$_SERVER['PHP_SELF'].'?id='.$id.'&amp;o='.$order.'&&amp;='.$sortby.'&amp;n='.$i.'&amp;">'.$show[$i].'</a>';
					else echo '<b>'.$show[$i].'</b> per page';					
				}else break;
				if($total["sum"]>$show[$i]) echo ' / ';
			}
			echo '&nbsp;</div>';
			?>			
			
			
			
			<table class="table table-striped">
			
				<thead>
					<tr>
					  
					  <th><a href="<?php echo $_SERVER['PHP_SELF'].'?id='.(isset($id)?$id:'').'&amp;o='.($sortby==1?-$order:1).'&amp;s=1&amp;p=1&amp;n='.$showIndex; ?>&amp;" title="sort by IP">IP<i class="<?php echo ($sortby==1?'icon-chevron-'.($order<0?'up':'down').'':'icon-empty');?>"></i></a></th>
					  <th><a href="<?php echo $_SERVER['PHP_SELF'].'?id='.(isset($id)?$id:'').'&amp;o='.($sortby==2?-$order:1).'&amp;s=3&amp;p=1&amp;n='.$showIndex; ?>&amp;" title="sort by date">Banned<i class="<?php echo ($sortby==2?'icon-chevron-'.($order<0?'up':'down').'':'icon-empty');?>"></i></a></th>
					  <th><div class="pull-right"><a class="btn-check nolink" href="#" title="select all"><i class="icon-plus"></i></a></div></th>
					
					
					</tr>
				</thead>
				 

				<tbody>
					
					<?php
					$rows=getBannedDetails($cols[$sortby],$order,$page,$show[$showIndex]);
					while($row = mysqli_fetch_array($rows)) {
						
						echo '<tr id="'.$row["n"].'">';					
						echo '<td><a '.(($row["n"])?'class="text-error"':'').' href="ip.php?ip='.$row["ip"].'&amp;" title="show IP details">'.$row["ip"].'</a>';
						if($row["n"]) echo ' <i class="icon-ban-circle"></i>';
						
					  ?>
					  </td>
					
					  <td><?php if($row["time"]>0) echo date('j M Y H:i',$row["time"]);?></td>
					  	<td>
							<div class="pull-right"> 								 
								<a class="btn-check" href="#" ><i class="icon-plus"></i></a>
							</div>
						</td>
					</tr>
					
					<?php
					}
					
					?>
					
					
					
				  </tbody>		
			</table>
		</div>
		
		
		
		  
		
		
		<?php
		if($maxpage>1){
			echo '<div class="pagination pagination-centered">';
			echo getPagination($page, $maxpage, $_SERVER['PHP_SELF'].'?id='.$id.'&amp;o='.$order.'&amp;s='.$sortby.'&amp;n='.$showIndex);
			echo '</div>';
		}
		?>
		
	
	<br>	
	<div class="muted"><small>* <b>Date/Time</b> corresponds to server time and may differ from your local time. Current server time: <?php echo date('r',time());?></small></div>
		
	
	   
	</div>   
	
<?php
}
	
include 'php/footer.php';
?>


<script src="js/application.banlist.js"></script>  
 
<?php
include 'php/closehtml.php';
?>
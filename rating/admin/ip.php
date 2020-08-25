<?php
include 'php/redirect.php';
//$time_start = microtime(true);
//page related styles
$stylefile='css/datepicker.css';

$style='
		body {
		padding-top: 60px;
		padding-bottom: 40px;
		}

		';
$showNavigation=true;
$pageIndex=-1;
include 'php/head.php';
include 'php/functions.php';

$show=array(10,20,40);	//items per page
$cols=array('n','title','rate','time');

//page settings
if (isset($_GET['o'])){$order=$_GET['o']; if($order=='' || $order==0) {$order=-1;}}else {$order=-1;}
if (isset($_GET['s'])){$sortby=$_GET['s']; if($sortby=='' || $sortby<0  || $sortby>=count($cols)) {$sortby=3;}}else {$sortby=3;}
if (isset($_GET['n'])){$showIndex=$_GET['n']; if($showIndex=='' || $showIndex<0 || $showIndex>=count($show)) {$showIndex=0;}}else {$showIndex=0;}
if (isset($_GET['p'])){$page=$_GET['p']; if($page=='' || $page<1) {$page=1;}}else {$page=1;}
if (isset($_GET['e'])){$err=$_GET['e']; if($err=='') {unset($err);}}
if (isset($_GET['ip'])){$id=$_GET['ip']; if($id=='') {unset($id);}}
if (isset($_GET['f'])){$filter=$_GET['f']; if($filter=='') {unset($filter);}}

if(!isset($id)){
	echo '<div class="container"><h1 class="pull-left offset50">User IP is not provided...</h1></div>';
}
else{

		if(isset($filter)){
			$filters=explode('|',$filter);
			$itemstodisplay=getCountFilteredIPs($id, $filter);			
			$filter=urlencode($filter);			
		}else{
			$filters=explode('|','||');
		}

		$total=getIpTotals($id);

		echo '<div class="container">';
		echo '<input id="hiddenip" type="hidden" value="'.$id.'"/>';
		
		echo '<div>';
			echo '<h1 class="pull-left offset50">'.$id.'</h1>'; 
			
			echo '<div class="pull-right"><button id="btnban" type="button" class="btn btn-inverse btn-';
			
			if($total["bannedtime"]) echo 'bunned"><i class="icon-ok-circle icon-white"></i> remove BAN</button></div>';
			else echo 'unbunned"><i class="icon-ban-circle icon-white"></i> BAN this IP</button></div>';
				
			echo '<div id="bannedtime">';
			if($total["bannedtime"]){
				echo '<h1 class="pull-left offset50 text-error"><small><b>banned</b> since </small> ';
				echo date('j M Y \<\s\m\a\l\l\>H:i\<\/\s\m\a\l\l\>',$total["bannedtime"]).'</h1>';
				echo '<div class="clearfix"></div>';
			}
			echo '</div>';
			if($total["votes"]>0) echo '<h2 class="pull-left offset50">'.(round(10*$total["sumrate"]/$total["votes"])/10).'% <small>mean given rate</small></h2>';
			echo '<h3 class="pull-left offset50">'.$total["votes"].' <small>votes</small></h3>';
			
			?>			
		
			
		</div>
	</div>

	
	<?php
	if($total["votes"]>0){
	?>
	
	
	<hr>

	<div class="container">

		<div class="row">
			<div id="message-box"></div>
			<?php
			if($demoMode==true) echo '<div id="demo-info"></div>';
			
			if($showIndex>0){if($total["votes"]<$show[$showIndex-1]) $showIndex--; }
			//$maxpage=ceil($total["votes"]/$show[$showIndex]);
			$maxpage=ceil((isset($filter)?$itemstodisplay:$total["votes"])/$show[$showIndex]);
			if($page>$maxpage) $page=$maxpage;
			
			
			if(isset($err)) echo '<div class="alert alert-error"><h1>Something went wrong</h1><p>ERROR: '.$err.'</p></div>';
				
			?>
			</div>
			
			<div class="pull-left"><h3 class="top-padding">List of IPs activity &nbsp;</h3></div>
			
			<!-- FILTERS -->
			<div>
			<div class="pull-left top-padding">	
				 <button id="filterbtn" type="button" class="btn <?php if(isset($filter)) echo 'btn-success';?>" data-toggle="button" title="filter is <?php echo isset($filter)?'on':'off';?>"><i class="icon-filter"></i></button> &nbsp;
			</div>
			
			<div class="pull-left top-padding">
				<div id="filters1" class="btn-group" data-toggle="buttons-checkbox" style="display:none;">
					<button type="button" class="btn <?php if(isset($filter) && $filters[0]!='') echo 'active';?>" data-class-toggle="btn-filter1">Title/ID</button>
					<button type="button" class="btn <?php if(isset($filter) && $filters[1]!='') echo 'active';?>" data-class-toggle="btn-filter2">Rate</button>
					<button type="button" class="btn <?php if(isset($filter) && $filters[2]!='') echo 'active';?>" data-class-toggle="btn-filter3">Date</button>
				</div>				
			</div>
			
			<div class="pull-left top-padding">
				&nbsp;&nbsp;<button id="filterapply" type="button" class="btn btn-success" style="display:none;"><i class="icon-search"></i> apply</button>
			</div>	
			
			<div class="clearfix"></div>
						
			<div id="filterdivs1" style="display:none;">	
				<div class="alert alert-filter1 span3">
					<button type="button" class="close"><i class="icon-remove"></i></button>
					<p><b>Title/ID</b> contains:</p>
					<input type="text" class="span3" value="<?php echo $filters[0];?>" placeholder="type here"/>
					<small><span class="muted">e.g. blog, foto, video, xxx</span></small>
				</div>
				
				<?php 
					$temp1=explode(",",$filters[1]);
					$temp2=explode(",",$filters[2]);
				?>
				
				<div class="alert alert-filter2 span3">
					<button type="button" class="close"><i class="icon-remove"></i></button>
					<p>With <b>rate</b> between</p>
					<input class="input-mini" type="text" value="<?php echo isset($temp1[0])?$temp1[0]:'';?>" placeholder="min"/>
					 and <input class="input-mini" type="text" value="<?php echo isset($temp1[1])?$temp1[1]:'';?>" placeholder="max"/>
					  <br><small><span class="muted">numbers between 0 and 100</span></small>
				</div>
				
				<div class="alert alert-filter3 span3">
					<button type="button" class="close"><i class="icon-remove"></i></button>
					<p>With <b>Date</b> between</p>
					<input id="datebegin" class="input-small" type="text" value="<?php echo isset($temp2[0])?$temp2[0]:'';?>" placeholder="0"/>
					  <input id="dateend" class="input-small" type="text" value="<?php echo isset($temp2[1])?$temp2[1]:'';?>" placeholder="N"/>
					 <br><small><span class="muted">DD/MM/YYYY</span></small>
				</div>
				
			</div>
			<!-- FILTERS END-->
			
					
			</div>
						
			<div class="clearfix"></div>
			
			
			<?php
			
			if(isset($itemstodisplay)) echo '<span class="label label-info pull-left">'.$itemstodisplay.' item'.($itemstodisplay==1?'':'s').' found <a id="filteroffnow" href="ip.php?ip='.$id.'&amp;o='.$order.'&amp;s='.$sortby.'&amp;n='.$showIndex.'" title="switch OFF filter"><i class="icon-remove icon-white"></i></a></span>';
			
			
			echo '<div class="pull-right">'; 						
			$total["votes"]=(isset($filter)?$itemstodisplay:$total["votes"]);
			for($i=0;$i<count($show);$i++){
				if($total["votes"]>$show[$i<1?0:$i-1]){
					if($i!=$showIndex) echo '<a href="'.$_SERVER['PHP_SELF'].'?ip='.$id.'&amp;o='.$order.'&amp;s='.$sortby.'&amp;n='.$i.'&amp;f='.(isset($filter)?$filter:'').'&amp;">'.$show[$i].'</a>';
					else echo '<b>'.$show[$i].'</b> per page';					
				}else break;
				if($total["votes"]>$show[$i] && $i<count($show)-1) echo ' / ';
				
			}
			echo '&nbsp;</div>';
			
			//ALL GIVEN RATES
			
			if($total["votes"]>0){
			
			?>			
			
			
			
			<table class="table table-striped">
			
				<thead>
					<tr>
					  
					  <th><a href="<?php echo $_SERVER['PHP_SELF'].'?ip='.$id.'&amp;o='.($sortby==1?-$order:1).'&amp;s=1&amp;p=1&amp;n='.$showIndex.'&amp;f='.(isset($filter)?$filter:''); ?>&amp;" title="sort by title">Item title / ID<i class="<?php echo ($sortby==1?'icon-chevron-'.($order<0?'up':'down').'':'icon-empty');?>"></i></a></th>
					  <th><a href="<?php echo $_SERVER['PHP_SELF'].'?ip='.$id.'&amp;o='.($sortby==2?-$order:1).'&amp;s=2&amp;p=1&amp;n='.$showIndex.'&amp;f='.(isset($filter)?$filter:''); ?>&amp;" title="sort by rate">Given rate<i class="<?php echo ($sortby==2?'icon-chevron-'.($order<0?'up':'down').'':'icon-empty');?>"></i></a></th>
					  <th><a href="<?php echo $_SERVER['PHP_SELF'].'?ip='.$id.'&amp;o='.($sortby==3?-$order:1).'&amp;s=3&amp;p=1&amp;n='.$showIndex.'&amp;f='.(isset($filter)?$filter:''); ?>&amp;" title="sort by date">Date<i class="<?php echo ($sortby==3?'icon-chevron-'.($order<0?'up':'down').'':'icon-empty');?>"></i></a></th>
					  <th><div class="pull-right"><a class="btn-check nolink" href="#" title="select all"><i class="icon-plus"></i></a></div></th>
					
					</tr>
				</thead>
				 

				<tbody>
					
					<?php
					$rows=getIpRates($id,$cols[$sortby],$order,$page,$show[$showIndex],isset($filter)?$filter:'');
					while($row = mysqli_fetch_array($rows)) {
						
						if(!$row["title"]) $row["title"]=$row["id"];
						//order: n-itemID, detid-rateID, rate-rateValue //to reduce stress on DB then delete items
						echo '<tr id="'.$row["detid"].'"><td>';	
						echo '<a href="'.urldecode(html_entity_decode($row["link"])).'" target="_blank" title="located here"><i class="icon-share btn-link"></i></a> ';
						
						
						$row["title"]=(strlen($row["title"])>30)?substr($row["title"],0, 28).'..':$row["title"];
						
						if(isset($filter) && $filters[0]!=''){
							$row["title"] = str_ireplace($filters[0], "<b>".$filters[0]."</b>", $row["title"]);
						}
						
						echo '<span><a href="item.php?id='.$row["n"].'&amp;" title="show item details">'.($row["title"]).'</a></span>'; 
						
						
					  ?>
					  </td>
					  <td>
					  					  
						<div class="progress progress-warning<?php echo (empty($row["property"]))?'':' progress-striped'; ?>" title="<?php echo round($row["rate"]).'% ('.(round($row["rate"]/2)/10).' / 5 stars)';?>">
						  <div class="bar" style="width: <?php echo $row["rate"];?>%;"></div>
						</div>
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
			
			<?php
			}
			else if(!isset($itemstodisplay)) echo 'No items found';
			
			?>
			
			
		
		
		
		
		<?php
			if($maxpage>1){
				echo '<div class="pagination pagination-centered">';
				echo getPagination($page, $maxpage, $_SERVER['PHP_SELF'].'?ip='.$id.'&amp;o='.$order.'&amp;s='.$sortby.'&amp;n='.$showIndex.'&amp;f='.(isset($filter)?$filter:''));
				echo '</div>';
			}
			
			if($total["votes"]>0){
		?>
		
		<br>
		<div class="muted"><small>* <b>Date/Time</b> corresponds to server time and may differ from your local time. Current server time: <?php echo date('r',time());?></small></div>
		
		 <?php
		  }
		  ?>
	
	   
	</div>   
	
<?php

	
}
}

//$time_end = microtime(true);
//$time = ($time_end - $time_start)*1000;

//echo "Did nothing in $time ms\n";
include 'php/footer.php';
?>

<script src="js/bootstrap-datepicker.js"></script>
<script src="js/application.ip.js"></script>  
 
<?php
include 'php/closehtml.php';
?>
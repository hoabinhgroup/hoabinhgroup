<?php
include 'php/redirect.php';

//page related styles
$stylefile='css/datepicker.css';

$style='
		body {
		padding-top: 60px;
		padding-bottom: 40px;
		}

		
		
		';
$showNavigation=true;
$pageIndex=0;
include 'php/head.php';
include 'php/functions.php';


$cols=array('n','title','mean','votes','validtill','voteperiod','time');

//page settings
if (isset($_GET['o'])){$order=$_GET['o']; if($order=='' || $order==0) {$order=1;}}else {$order=1;}
if (isset($_GET['s'])){$sortby=$_GET['s']; if($sortby=='' || $sortby<0  || $sortby>=count($cols)) {$sortby=1;}}else {$sortby=1;}
if (isset($_GET['e'])){$err=$_GET['e']; if($err=='') {unset($err);}}
if (isset($_GET['f'])){$filter=$_GET['f']; if($filter=='') {unset($filter);}}
if (isset($_GET['id'])){$id=$_GET['id']; if($id=='') {unset($id);}}


if(!isset($id)){
	echo '<div class="container"><h1 class="pull-left offset50">Item id is not provided...</h1></div>';
}else{


if(isset($filter)){
	$filters=explode('|',$filter);
	$itemstodisplay=getCountFiltered($filter);
	$filter=urlencode($filter);
}else{
	$filters=explode('|','|||||');
}


//from index
$total=getTotals();
$minPopularityVotes=getMinVotes();
$totalmean=($total["votes"]>0)?$total["summean"]/$total["votes"]:0;

//from item
$total=getItemTotals($id);

echo '<div class="container">';
echo '<input id="thisitemid" type="hidden" value="'.$id.'" />';


if(!$total["title"]) $total["title"]=$total["id"];
$total["link"]=urldecode(html_entity_decode($total["link"]));
echo '<h1 class="pull-left offset50"><a title="open containing page" href="'.$total["link"].'" target="_blank">';
echo ''.$total["title"].'</a></h1>'; //((strlen($total["title"])>30)?substr($total["title"],0, 28).'..':
echo '<h2 class="pull-left offset50">'.$total["mean"].'% <small>rating</small></h2>';
echo '<h3 class="pull-left offset50">'.$total["votes"].' <small>votes</small></h3>';

if($total["validtill"]>0){
	if($total["validtill"]>time())	echo '<h3 class="pull-left offset50"><small>open till </small> ';
	else echo '<h3 class="pull-left offset50"><small>rating <span class="text-info">closed</span> since </small> ';
	echo date('j M Y H:i',$total["validtill"]).'</h3>';//
}

?>	
</div>
<hr>



<?php
if($total["votes"]>0){ //&& $total["items"]>0
?>

	
		<div class="container">
			<div class="">
				<div id="message-box"></div>
				
							
				<?php
				if($demoMode==true) echo '<div id="demo-info"></div>';
				if(isset($err)) echo '<div class="alert alert-error"><h1>Something went wrong</h1><p>ERROR: '.$err.'</p></div>';
				
										
				
				?>
			</div>	
			
			<div>
				<div class="pull-left">
					<h3 class="top-padding">List of SUB-items &nbsp;</h3>
				</div>
				
				
				
				<!-- FILTERS -->
				
				<div class="pull-left top-padding">	
					 <button id="filterbtn" type="button" class="btn <?php if(isset($filter)) echo 'btn-success';?>" data-toggle="button" title="filter is <?php echo isset($filter)?'on':'off';?>"><i class="icon-filter"></i></button> &nbsp;
				</div>
				
				<div class="pull-left top-padding">
					<div id="filters1" class="btn-group" data-toggle="buttons-checkbox" style="display:none;">
						<button type="button" class="btn <?php if(isset($filter) && $filters[0]!='') echo 'active';?>" data-class-toggle="btn-filter1">Title/ID</button>
						<button type="button" class="btn <?php if(isset($filter) && $filters[1]!='') echo 'active';?>" data-class-toggle="btn-filter2">Rating</button>
						<button type="button" class="btn <?php if(isset($filter) && $filters[2]!='') echo 'active';?>" data-class-toggle="btn-filter3">Number of votes</button>
						<button type="button" class="btn <?php if(isset($filter) && $filters[3]!='') echo 'active';?>" data-class-toggle="btn-filter4">Top/Flop</button>
						<button type="button" class="btn <?php if(isset($filter) && $filters[4]!='') echo 'active';?>" data-class-toggle="btn-filter5">Open/Closed</button>		 
						<button type="button" class="btn <?php if(isset($filter) && $filters[5]!='') echo 'active';?>" data-class-toggle="btn-filter6">Last rated</button>						
					
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
						$temp3=explode(",",$filters[5]);
					?>
					
					<div class="alert alert-filter2 span3">
						<button type="button" class="close"><i class="icon-remove"></i></button>
						<p>With <b>rating</b> between</p>
						<input class="input-mini" type="text" value="<?php echo isset($temp1[0])?$temp1[0]:'';?>" placeholder="min"/>
						 and <input class="input-mini" type="text" value="<?php echo isset($temp1[1])?$temp1[1]:'';?>" placeholder="max"/>
						  <br><small><span class="muted">numbers between 0 and 100</span></small>
					</div>
					
					<div class="alert alert-filter3 span3">
						<button type="button" class="close"><i class="icon-remove"></i></button>
						<p>With <b>Number of votes</b> between</p>
						<input class="input-mini" type="text" value="<?php echo isset($temp2[0])?$temp2[0]:'';?>" placeholder="0"/>
						 and <input class="input-mini" type="text" value="<?php echo isset($temp2[1])?$temp2[1]:'';?>" placeholder="N"/>
						 <br><small><span class="muted">any numbers</span></small>
					</div>
					
					<div class="alert alert-filter4 span3">
						<button type="button" class="close"><i class="icon-remove"></i></button>
						<p>Items which are</p>					
							<div class="btn-group" data-toggle="buttons-radio">
							
								<button type="button" class="btn <?php if($filters[3]==1 || !isset($filter) || $filters[3]=='') echo 'active';?>" data-toggle="button">in Top</button>
								<button type="button" class="btn <?php if($filters[3]==2) echo 'active';?>" data-toggle="button">in Flop</button>
							<p>	</p>
							</div>					
						<small>
						<span class="muted">high rated / low rated</span></small>
					</div>
					
					
					<div class="alert alert-filter5 span3">
						<button type="button" class="close"><i class="icon-remove"></i></button>
						<p>Items which are</p>
							
							<div class="btn-group" data-toggle="buttons-radio">
							
								<button type="button" class="btn <?php if($filters[4]==1 || !isset($filter) || $filters[4]=='') echo 'active';?>" data-toggle="button">open</button>
								<button type="button" class="btn <?php if($filters[4]==2) echo 'active';?>" data-toggle="button">closed</button>
							<p>
							</p>
							</div>
						
						<small><span class="muted">available / not available for voting</span></small>
					</div>
					
					<div class="alert alert-filter6 span3">
						<button type="button" class="close"><i class="icon-remove"></i></button>
						<p>With <b>Date</b> between</p>
						<input id="datebegin" class="input-small" type="text" value="<?php echo isset($temp3[0])?$temp3[0]:'';?>" placeholder="DD/MM/YYYY"/>
						  <input id="dateend" class="input-small" type="text" value="<?php echo isset($temp3[1])?$temp3[1]:'';?>" placeholder="DD/MM/YYYY"/>
						 <br><small><span class="muted">DD/MM/YYYY</span></small>
					</div>
				</div>
				<!-- FILTERS END-->
				
			</div>		
		
						<div class="clearfix"></div>
			
				
				<?php
				
				if(isset($itemstodisplay)) echo '<span class="label label-info pull-left">'.$itemstodisplay.' items found <a id="filteroffnow" href="index.php?o='.$order.'&amp;s='.$sortby.'" title="switch OFF filter"><i class="icon-remove icon-white"></i></a></span>';
				
				echo '<div class="pull-right">'; 
				$total["items"]=(isset($filter)?$itemstodisplay:$total["items"]);			
				echo '&nbsp;</div>';
				
				
				
				?>			
				
				
				
				<table class="table table-striped">
				
					<?php
					if($total["items"]>0){
					?>
				
					<thead>
						<tr>
						  
						  <th><a href="<?php echo $_SERVER['PHP_SELF'].'?id='.(isset($id)?$id:'').'&amp;o='.($sortby==1?-$order:1).'&amp;s=1&amp;p=1&amp;f='.(isset($filter)?$filter:''); ?>&amp;" title="sort by title">Title / ID<i class="<?php echo ($sortby==1?'icon-chevron-'.($order<0?'up':'down').'':'icon-empty');?>"></i></a></th>
						  <th><a href="<?php echo $_SERVER['PHP_SELF'].'?id='.(isset($id)?$id:'').'&amp;o='.($sortby==2?-$order:1).'&amp;s=2&amp;p=1&amp;f='.(isset($filter)?$filter:''); ?>&" title="sort by rating">Rating<i class="<?php echo ($sortby==2?'icon-chevron-'.($order<0?'up':'down').'':'icon-empty');?>"></i></a></th>
						  <th><a href="<?php echo $_SERVER['PHP_SELF'].'?id='.(isset($id)?$id:'').'&amp;o='.($sortby==3?-$order:1).'&amp;s=3&amp;p=1&amp;f='.(isset($filter)?$filter:''); ?>&" title="sort by number of votes">Votes<i class="<?php echo ($sortby==3?'icon-chevron-'.($order<0?'up':'down').'':'icon-empty');?>"></i></a></th>
						  <th><span class="nolink">Top/Flop *</span></th>
						  <th><a href="<?php echo $_SERVER['PHP_SELF'].'?id='.(isset($id)?$id:'').'&amp;o='.($sortby==4?-$order:1).'&amp;s=4&amp;p=1&amp;f='.(isset($filter)?$filter:''); ?>&" title="sort by date">Open until<i class="<?php echo ($sortby==4?'icon-chevron-'.($order<0?'up':'down').'':'icon-empty');?>"></i></a></th>
						  <th><a href="<?php echo $_SERVER['PHP_SELF'].'?id='.(isset($id)?$id:'').'&amp;o='.($sortby==5?-$order:1).'&amp;s=5&amp;p=1&amp;f='.(isset($filter)?$filter:''); ?>&" title="time of repeat voting">&Delta;T *<i class="<?php echo ($sortby==5?'icon-chevron-'.($order<0?'up':'down').'':'icon-empty');?>"></i></a></th>
						<th><a href="<?php echo $_SERVER['PHP_SELF'].'?id='.(isset($id)?$id:'').'&amp;o='.($sortby==6?-$order:1).'&amp;s=6&amp;p=1&amp;f='.(isset($filter)?$filter:''); ?>&amp;" title="sort by date">Last vote<i class="<?php echo ($sortby==6?'icon-chevron-'.($order<0?'up':'down').'':'icon-empty');?>"></i></a></th>
						  <th><div class="pull-right"><a class="btn-check nolink" href="#" title="select all"><i class="icon-plus"></i></a></div></th>
						
						
						</tr>
					</thead>
					<?php
					}
					?>

					<tbody>
						
						<?php
						
						if($total["items"]==0){
							//nothing to display
						}
						else{
						
						$rows=getAllItems($cols[$sortby],$order,-1,-1,isset($filter)?$filter:'');
						
						while($row = mysqli_fetch_array($rows)) {
							
							echo '<tr id="'.$row["n"].'"> <td>';					
							echo '<a href="'.urldecode(html_entity_decode($row["link"])).'" target="_blank"><i class="icon-share btn-link"></i></a> ';
							if(!$row["title"]) $row["title"]='no title';//$row["n"];
							echo '<span><input type="hidden" value="'.$row["title"].'" />';
							
							$row["title"]=(strlen($row["title"])>30)?substr($row["title"],0, 28).'..':$row["title"];
							
							if(isset($filter) && $filters[0]!=''){
								$row["title"] = str_ireplace($filters[0], "<b>".$filters[0]."</b>", $row["title"]);
							}
													
							echo '<a href="item.php?id='.$row["n"].'&amp;" title="show item details">'.($row["title"]).'</a></span>';
							
						  ?>
						  </td>
						  <td>
							<div class="progress progress-warning" title="<?php echo $row["mean"].'% ('.(round($row["mean"]/2)/10).' / 5 stars)';?>">
							  <div class="bar" style="width: <?php echo $row["mean"];?>%;"></div>
							</div>
						  </td>
						  <td><?php echo $row["votes"];?></td>
						  <td>
							<?php
								$popular=getPopularity($row["mean"], $row["votes"], $totalmean, $minPopularityVotes);
								$popular=round($popular*10)/10;
								echo '<div class="progress progress-success" title="'.(($popular==0)?($minPopularityVotes-$row["votes"]).' more vote(s) required':$popular.'%').'"><div class="bar" style="width: '.$popular.'%">';
							 ?>
							  </div>
							</div>
						  </td>
						  <td><?php if($row["validtill"]>0) echo date('j/m/Y',$row["validtill"]);?></td>
						  <td><?php echo secondsToString($row["voteperiod"]);?></td>
						<td><?php if($row["time"]>0) echo date('j M Y H:i',$row["time"]);?></td>
							
							<td>
								<div class="pull-right"> 
									<a class="btn-edit" href="#" ><i class="icon-pencil"></i></a> 
									<a class="btn-check" href="#" ><i class="icon-plus"></i></a>
								</div>
							</td>
						</tr>
						
						<?php
						}
						}
						?>
						
						
						<tr id="editform" class="info" style="display:none;">
						  <td><input id="editform-title" type="text" value="" placeholder="type short title"/>
						  <input id="editform-id" type="hidden" value="" /></td>
						  <td id="editform-mean"></td>
						  <td id="editform-n"></td>
						  <td id="editform-popularity"></td>
						  <td>
							<div class="input-prepend input-append">
								<button class="btn" type="button" title="expires now"><i class="icon-time"></i></button>
								<input id="editform-date" class="input-small" type="text" value="" placeholder="dd/mm/yyyy"/>
								<button class="btn" type="button" title="never expires"><i class="icon-minus"></i></button>
							</div>

						  </td>
						   <td>
						   
						   
						   <div class="btn-group input-append">					 
								<input id="editform-dt" type="text" class="span1" value="" placeholder="N"/>
								<button class="btn dropdown-toggle" data-toggle="dropdown"><span id="editform-dtmarker"></span> <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);">seconds</a></li>
									<li><a href="javascript:void(0);">minutes</a></li>
									<li><a href="javascript:void(0);">hours</a></li>
									<li><a href="javascript:void(0);">days</a></li>
									<li class="divider"></li>
									<li><a href="javascript:void(0);">&#8734; once per user</a></li>
								</ul>					
							</div><!-- /btn-group -->
						   
						   </td>					  
						<td><?php if($row["time"]>0) echo date('j M Y H:i',$row["time"]);?></td>					   
							
							<td>
								<div id="editform-submit" class="btn-group pull-right">
									<button class="btn btn-ok-edit" type="button" title="save changes"><i class="icon-ok"></i></button>
									<button class="btn btn-cancel-edit" type="button" title="cancel" ><i class="icon-remove"></i></button>
								</div>
							</td>
						</tr>
						
					  </tbody>		
				</table>
				
				<?php
				if($total["items"]==0 && !isset($itemstodisplay)) echo 'No items found';				
				if($total["items"]>0){
					?>
				
					<br>
					<div class="muted"><small>* <b>Top/Flop</b> - global ranking of item in database (similar to IMDb ranking system). More info <a href='http://en.wikipedia.org/wiki/Internet_Movie_Database#Ranking_.28IMDb_Top_250.29' target='_blank'>here</a>.</small></div>
					<div class="muted"><small>* <b>&Delta;T</b> - time before user can rate the same item again. Sign &#8734; (infinity) means user is allowed to rate this item only once.</small></div>
					<div class="muted"><small>* <b>Date/Time</b> corresponds to server time and may differ from your local time. Current server time: <?php echo date('r',time());?></small></div>
				
				  <?php
			  }
			  ?>
			  
		</div>		


	<?php

}else{
	echo '<h1 class="pull-left offset50">no subitems in database</h1></div>';
}
}//if id is provided


include 'php/footer.php';
?>

<script src="js/bootstrap-datepicker.js"></script>
<script src="js/application.sum.js"></script>
 
<?php
include 'php/closehtml.php';
?>
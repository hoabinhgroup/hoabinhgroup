<div class="pull-right">

<?php

	//NAVIGATION

	if(isset($_GET['f'])){$f=$_GET['f']; if($f=='' || $f==0) {$f=1;}}else {$f=1;}
	if(isset($_GET['t'])){$t=$_GET['t']; if($t=='' || $t==0) {$t=1;}}else {$t=1;}
			
	$tops=array('Top rated','Most rated','Most remarkable');
	
	for($i=0;$i<count($tops);$i++){
		if($i>0) echo ' / ';
		if($f==$i+1) echo '<b>'.$tops[$i].'</b>';
		else echo '<a href="?f='.($i+1).'&t='.$t.'">'.$tops[$i].'</a>';
	}
	
	echo '</br>';
	
	$times=array('All times','Last month','Last week');
	
	for($i=0;$i<count($times);$i++){
		if($i>0) echo ' / ';
		if($t==$i+1) echo '<b>'.$times[$i].'</b>';
		else echo '<a href="?f='.$f.'&t='.($i+1).'">'.$times[$i].'</a>';
	}
	
?>

</div>			


<div class="clearfix"></div>


<?php
	
	//CONNECT TO DB AND GET THE TOP LIST
	include dirname(__FILE__).'/config.php';

	//connect to BD
	$link = @mysqli_connect($dbhost, $dbuser, $dbpass,$db_name);

	/* check connection */
	if (mysqli_connect_errno()) {
	   die(mysqli_connect_error());
	}
	
	
	// f= 1, 2 or 3 (type of listing, i.e. top rated, most rated)
	// t= 1, 2 or 3 (all times, last 30 days or last week)
	// toplength - number of items to include in top list
	// minvotes - minimum number of votes for item to be included in top (if t=1)

	$rows=getTops($f,$t,$toplength,$minvotes);
	
?>	



<!-- PRINT RESULT INTO THE TABLE-->

<table class="table table-hover">
	<tbody>
	
	<?php	
			
		//print the result in table			
		while($row = mysqli_fetch_array($rows)) {			
			echo '<tr><td>';		
			if(!$row["title"]) $row["title"]='no title';
			echo '<span>'.++$k.'. ';		
			//$row["title"]=(strlen($row["title"])>30)?substr($row["title"],0, 28).'..':$row["title"];
			echo '<a href="'.urldecode(html_entity_decode($row["link"])).'" title="open containing page" target="_blank">'.($row["title"]).'</a></span></td>';
	?>
		 
		<td>
			
			<?php
			if($display=='stars'){
				echo '<div class="stars" data-value="'.$row["mean"].'" data-votes="'.$row["votes"].'" >x</div>';
			}else{
				echo '<div class="progress progress-success" style="width:200px" title="'.$row["mean"].'% ('.(round($row["mean"]/2)/10).' / 5 stars)">';
				echo '<div class="bar" style="width:'.$row["mean"].'%;"></div></div>';
			}
			?>
		</td>


		<td><?php echo $row["votes"].' vote'.($row["votes"]==1?'':'s');?></td>
		</tr>
		  
		  
		
	<?php
	}
	?>
	
	</tbody>
</table>


<?php
if ($f==3) echo '<div class="muted"><small>* <b>Most remarkable</b> items - items with very hight mean rate or with a lot of votes. Formula: mean*votes.</small></div>';




//function to get the top list
function getTops($toptype, $topdate, $limit, $minvotes){
	GLOBAL $rating_sum,$link;
	GLOBAL $rating_det;
	
	if($topdate==2 || $topdate==3){
		if($toptype==2) $col='COUNT(*) DESC, SUM(rate) DESC';
		else if($toptype==3) $col='SUM(rate) DESC, COUNT(*) DESC';
		else $col='SUM(rate)/COUNT(*) DESC, COUNT(*) DESC';
		
		if($topdate==2) $dt=(time()-60*60*24*30);
		else $dt=(time()-60*60*24*7);					
		
		$sql='select COUNT(*) as votes, SUM(rate)/COUNT(*) as mean, s.title as title, s.link as link from '.$rating_det.' d LEFT JOIN '.$rating_sum.' s ON s.n=d.id WHERE d.time>='.$dt.' GROUP BY d.id ORDER BY '.$col.' LIMIT 0, '.$limit;
							
	}else{					
		if($toptype==2) $col='votes DESC, mean DESC';
		else if($toptype==3) $col='votes*mean DESC, votes DESC';
		else $col='mean DESC, votes DESC';
	
		$sql='select mean, votes, title, link from '.$rating_sum.' WHERE votes>='.$minvotes.' ORDER BY '.$col.' LIMIT 0, '.$limit;
	}
	
	
	$result = $link->query($sql) or die(mysqli_error($link));
	return $result;
}
?>
	
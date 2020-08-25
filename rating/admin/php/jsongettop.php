<?php

include_once ('config.php');
include_once ('functions.php');

if (isset($_GET['flag'])){$flag=$_GET['flag']; if($flag=='') {$flag=1;}}else {$flag=1;}

$maxlength=15;
$result=getAllItems($flag==1?'mean*m1.votes':'votes',-1,1,5,'');

$top=array();
while($row = mysqli_fetch_array($result)) {	
	$shorttitle=(strlen($row["title"])>$maxlength)?substr($row["title"],0, $maxlength-2).'..':$row["title"];
	
	$stat=$flag==1?(round($row["mean"]/2)/10).' / 5 stars from '.$row["votes"].' vote'.(($row["votes"]==1)?'':'s'):($row["votes"]).' vote'.($row["votes"]==1?'':'s');
	
	$row["title"]='<a href=\"item.php?id='.$row["n"].'&\" title=\"'.htmlentities($row["title"], ENT_QUOTES).' </br>('.$stat.')\" class=\"legendtitle\">'.$shorttitle.'</a>';
	
	$shorttitle=($row["title"]);
	
	array_push($top, '{ "label": "'.$shorttitle.'",  "data": '.($flag==1?$row["mean"]*$row["votes"]:$row["votes"]).'}');
}


$data = '[';
$data .=implode(",", $top);
$data .=']';

echo $data;

?>
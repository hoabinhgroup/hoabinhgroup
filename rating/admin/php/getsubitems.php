<?php
$inside=true;
include 'redirect.php';
include_once 'config.php';

$cols=array('n','title','mean','votes','validtill','voteperiod','time');

if (isset($_GET['n'])){$n=trim($_GET['n']); if($n=='') {unset($n);}}
if (isset($_GET['o'])){$order=$_GET['o']; if($order=='' || $order==0) {$order=1;}}else {$order=1;}
if (isset($_GET['s'])){$sortby=$_GET['s']; if($sortby=='' || $sortby<0  || $sortby>=count($cols)) {$sortby=1;}}else {$sortby=1;}


$col=$cols[$sortby];
$error=false;

if(isset($n)){

	include_once 'config.php';
	
	$link = @mysqli_connect($dbhost, $dbuser, $dbpass,$db_name);

	/* check connection */
	if (mysqli_connect_errno()) {
	   $error=true;
	}else{	
		
		//get subitems by n==$n
		$sql = "SELECT id FROM $rating_sum WHERE n=$n LIMIT 1";
		$result=$link->query($sql) or $error=true;
		
		
		if(!$error){
			$row = mysqli_fetch_assoc($result);
			$sql = 'SELECT n, link, title, mean, votes,time FROM '.$rating_sum.' WHERE id="'.$row['id'].'" AND property<>""';				
			
			if($col=='voteperiod') $sql.=' ORDER BY SIGN('.$col.'+1) '.($order>0?'ASC':'DESC').', ABS('.$col.') '.($order<0?'ASC':'DESC');
			else $sql.=' ORDER BY  '.$col.' '.($order>0?'ASC':'DESC');
			
			$result=$link->query($sql) or $error=true;
			
		}
		
		
	}
	

}

if($error) echo json_encode(array("error" => mysqli_error($link)));
else{
	
	//output result here
	$output=array();
	$i=0;
	while($row = mysqli_fetch_assoc($result)) {		
		//$row["title"]=(strlen($row["title"])>30)?substr($row["title"],0, 28).'..':$row["title"];
		if($row["time"]>0) $row["time"]=date('j M Y H:i',$row["time"]);
		$row["link"]=urldecode(html_entity_decode($row["link"]));
		$output[$i]=$row;
		$i=$i+1;
	}
	echo json_encode($output);
	
}
exit();

?>
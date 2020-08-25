<?php
$ref = $_SERVER['HTTP_REFERER'];
$inside=true;
include 'redirect.php';


if (isset($_GET['n'])){$n=($_GET['n']); if($n=='') {unset($n);}}
if (isset($_GET['id'])){$itemID=($_GET['id']); if($itemID=='') {unset($itemID);}}
if (isset($_GET['pid'])){$pitemID=($_GET['pid']); if($pitemID=='') {unset($pitemID);}}

//if some browser (IE) doesn't send HTTP_REFERER make the following
if (!$ref && isset($_GET['ref'])){$ref=trim($_GET['ref']); if($ref=='') {$ref='index.php';}}
//split ref by   
$ref=explode('?&e=',$ref);
$ref=$ref[0];

include_once 'config.php';
if($demoMode){
	header("Location: ".$ref.'?&e='.htmlentities(urlencode('Prohibited in demo')));
	exit;
}



//n - list of rating_det.n
//to do: delete from rating_det where rating_sum.n=n
//and update rating_sum where rating_sum.n=rating_det.id

$error=false;
if(isset($n) && isset($itemID)){
	
	$link = @mysqli_connect($dbhost, $dbuser, $dbpass,$db_name);

	/* check connection */
	if (mysqli_connect_errno()) {
	   $error=true;
	}
	
	$rateID=explode(',', $n,-1);
	
	//very primitive way to delete/update multiple tables
	//but it will work with any SQL engine
	
	if(!$error){					
		$query = 'SELECT SUM(rate) as sr, COUNT(n) as sv FROM '.$rating_det.' WHERE n IN ('.implode(',',$rateID).')';
		$result=$link->query($query) or $error=true;
	}
	
	if(!$error){			
			
		//begin of update				
		$query = 'UPDATE '.$rating_sum;
		$row = $result->fetch_assoc();
		
		if(isset($pitemID)){
			$mean=' SET mean = CASE n ';
			$votes=' votes = CASE n ';
						
			$mean .= ' WHEN '.$itemID.' THEN ((mean*votes-'.$row['sr'].')/(votes-'.$row['sv'].')) ';  
			$votes .=' WHEN '.$itemID.' THEN (votes-'.$row['sv'].') '; 
			$mean .= ' WHEN '.$pitemID.' THEN ((mean*votes-'.$row['sr'].')/(votes-'.$row['sv'].')) ';  
			$votes .=' WHEN '.$pitemID.' THEN (votes-'.$row['sv'].') ';
			
			$query.=$mean.' END, '.$votes.' END WHERE n IN ('.$itemID.', '.$pitemID.')';				
		}else{
			$mean=' SET mean = ((mean*votes-'.$row['sr'].')/(votes-'.$row['sv'].'))';
			$votes=' votes = (votes-'.$row['sv'].')';
			$query.=$mean.', '.$votes.' WHERE n ='.$itemID;
		}
		
		$result=$link->query($query) or $error=true;
		
	}	
	////// end of update 
	
	
	//delete individual rates
	if(!$error){
		$query = 'DELETE FROM '.$rating_det.' WHERE n IN ('.implode(',', $rateID).')';
		$result=$link->query($query) or $error=true;
	}
	
	//delete from summary if zero votes
	if(!$error){
		$query = 'DELETE FROM '.$rating_sum.' WHERE ';
		if(isset($pitemID))  $query .= 'n IN ('.$itemID.', '.$pitemID.')';
		else $query .= 'n ='.$itemID;
		$query .= ' AND votes<=0'; 
		$result=$link->query($query) or $error=true;
	}
			

}


if($error) header("Location: ".$ref.'?&e='.htmlentities(urlencode(mysqli_error($link).' | '.$query)));
else header("Location: ".$ref);

exit();

?>
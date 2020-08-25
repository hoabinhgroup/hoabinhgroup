<?php
$ref = $_SERVER['HTTP_REFERER'];
$inside=true;
include 'redirect.php';


if (isset($_GET['n'])){$n=($_GET['n']); if($n=='') {unset($n);}}

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
if(isset($n)){
	
	$link = @mysqli_connect($dbhost, $dbuser, $dbpass,$db_name);

	/* check connection */
	if (mysqli_connect_errno()) {
	   $error=true;
	}
	
	$rateID=explode(',', $n,-1);
	
	if(!$error){					
		$query = 'SELECT SUM(d.rate) as sr, COUNT(d.n) as sv, d.id, s.property FROM '.$rating_det.' d LEFT JOIN '.$rating_sum.' s ON (d.id = s.n) WHERE d.n IN ('.implode(',',$rateID).') GROUP BY d.id';
		$result=$link->query($query) or $error=true;
	}
	
	if(!$error){
		$cids=array();		
		$data=array();		
		while($row = $result->fetch_assoc()){			
			
			if(!empty($row['property'])){
				$cids[] = $row['id'];	
			}
			
			$data[$row['id']]=$row;
		}
		
		
		
		//in case if there is a parent item - find its id
		if(count($cids)>0){
			
			$query = 'SELECT m1.n as parentn, m2.n as childn FROM '.$rating_sum.' m1 LEFT JOIN '.$rating_sum.' m2 ON (m1.id = m2.id) WHERE m2.n IN ('.implode(',',$cids).') AND m1.property=""';
					
			$result=$link->query($query) or $error=true;
			
			if(!$error){
				while($row = $result->fetch_assoc()){
					//$data[$row['childn']]['parentn']=$row['parentn'];
					if(empty($data[$row['parentn']])){
						$data[$row['parentn']]=$data[$row['childn']];
						$data[$row['parentn']]['id']=$row['parentn'];
						$data[$row['parentn']]['property']='';
					}else{
						$data[$row['parentn']]['sr']+=$data[$row['childn']]['sr'];
						$data[$row['parentn']]['sv']+=$data[$row['childn']]['sv'];
					}
				}
				
			}		
			
		}		
		
	}
	
	
	
	if(!$error){			
					
		$query = 'UPDATE '.$rating_sum;
		$mean=' SET mean = CASE n';
		$votes=' votes = CASE n';
		$idstoupdate=array();		
		
		foreach($data as $row) {
			//print_r($row);		
		
			$mean .= ' WHEN '.$row['id'].' THEN ((mean*votes-'.$row['sr'].')/(votes-'.$row['sv'].')) ';  
			$votes .=' WHEN '.$row['id'].' THEN (votes-'.$row['sv'].') '; 
			
			/*
			if(!empty($row['pid'])){
				$mean .= ' WHEN '.$row['pid'].' THEN ((mean*votes-'.$row['sr'].')/(votes-'.$row['sv'].')) ';  
				$votes .=' WHEN '.$row['pid'].' THEN (votes-'.$row['sv'].') '; 
				$idstoupdate[]=$row['pid'];	
			}
			*/
			
			$idstoupdate[]=$row['id'];			
		}
		$query.=$mean.' END, '.$votes.' END WHERE n IN ('.implode(',',$idstoupdate).')';		
		$result=$link->query($query) or $error=true;
		
	}	
	////// end of update 
	
	//$error=true;
	
	//delete individual rates
	if(!$error){
		$query = 'DELETE FROM '.$rating_det.' WHERE n IN ('.implode(',', $rateID).')';
		$result=$link->query($query) or $error=true;
	}
	
	//delete from summary if zero votes
	if(!$error){
		$query = 'DELETE FROM '.$rating_sum.' WHERE ';
		$query .= 'n IN ('.implode(',',$idstoupdate).')';

		$query .= ' AND votes<=0'; 
		$result=$link->query($query) or $error=true;
	}
			

}


if($error) header("Location: ".$ref.'?&e='.htmlentities(urlencode(mysqli_error($link).' | '.$query)));
else header("Location: ".$ref);

//if($error) echo ("Location: ".$ref.'?&e='.htmlentities(urlencode(mysqli_error($link).' | '.$query)));
//else echo ("Location: ".$ref);

exit();

?>
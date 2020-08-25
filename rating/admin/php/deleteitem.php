<?php
$ref = $_SERVER['HTTP_REFERER'];
$inside=true;
include 'redirect.php';

if (isset($_GET['n'])){$n=trim($_GET['n']); if($n=='') {unset($n);}}

//if some browser (IE) doesn't send HTTP_REFERER make the following
if (!$ref && isset($_GET['ref'])){$ref=trim($_GET['ref']); if($ref=='') {$ref='index.php';}}
$temp=explode('?&e=',$ref);
$ref=$temp[0];

include_once 'config.php';
if($demoMode){
	header ("Location: ".$ref.'?e='.htmlentities(urlencode('Delete items is prohibited in this demo')));
	exit;
}




$error=false;
if(isset($n)){
	
	$ids=explode("|", $n);
	
	include_once 'config.php';
	$link = @mysqli_connect($dbhost, $dbuser, $dbpass,$db_name);

	/* check connection */
	if (mysqli_connect_errno()) {
	   $error=true;
	}
	
	$idstodelete=array();
	if(!empty($ids[1])) $ids1=explode(",", $ids[1]);
	else $ids1=array();
	
	if(!$error && !empty($ids[0])){
				
		$query = 'SELECT m1.n FROM '.$rating_sum.' m1 LEFT JOIN '.$rating_sum.' m2 ON (m1.id = m2.id) WHERE m2.n IN ('.$ids[0].')';
		$result=$link->query($query) or $error=true;
		
		if($result){
			while($row = $result->fetch_assoc()){
				$idstodelete[]=$row['n'];			
				$key = array_search($row['n'], $ids1);
				if($key!==false && isset($ids1[$key])) unset($ids1[$key]);
			}
		}
		
	}

	if(count($ids1)>0){
		//update
		
		$query = 'SELECT SUM(m1.mean*m1.votes) as smv, SUM(m1.votes) nv, m1.id FROM '.$rating_sum.' m1 WHERE m1.n IN ('.implode(',',$ids1).') AND m1.property<>"" GROUP BY m1.id';
		$result=$link->query($query) or $error=true;
		
		//$output="";
		if($result){
			$parentids=array();
						
			$sqlmean='SET mean = (CASE id ';
			$sqlvotes=', votes = (CASE id ';
			while($row = $result->fetch_assoc()){
				//$output.=$row['id'].' n*v:'.$row['smv'].' votes:'.$row['nv'].' | ';		
				
				$sqlmean  .=' WHEN "'.$row['id'].'" THEN ((mean*votes-'.$row['smv'].')/(votes-'.$row['nv'].')) ';	//
				$sqlvotes .=' WHEN "'.$row['id'].'" THEN (votes-'.$row['nv'].') ';	//votes
				
				$parentids[]='"'.$row['id'].'"';
			}
			
			$query = 'UPDATE '.$rating_sum.' '.$sqlmean.' ELSE mean END) '.$sqlvotes.' ELSE votes END)';
			$query .=' WHERE id IN ('.implode(',',$parentids).') AND property=""';
			$result=$link->query($query) or $error=true;
		}
		
		
		//header('Location: '.$ref.'?&e=to update: '.$output.'  <br/>query:: '.$query.'<br/>'.htmlentities(urlencode(mysqli_error($link)))); exit();
	}

	
		
	$idstodelete = array_merge($idstodelete, $ids1);
	if(!$error && count($idstodelete)>0){
	
		$query = 'DELETE FROM '.$rating_sum.' WHERE n IN ('.implode(',',$idstodelete).')';
		$result=$link->query($query) or $error=true;	
		
		if(!$error){
			$query = 'DELETE FROM '.$rating_det.' WHERE id IN ('.implode(',',$idstodelete).')';
			$result=$link->query($query) or $error=true;	
		}
	}

}



if($error) header ("Location: ".$ref.'?e='.htmlentities(urlencode(mysqli_error($link))).' sql:: '.$query);
else header ("Location: ".$ref);

exit();

?>
<?php
$inside=true;
include 'redirect.php';


include_once 'config.php';
if($demoMode){
	echo 'Prohibited in demo';
	exit;
}



if (isset($_GET['n'])){$n=trim($_GET['n']); if($n=='') {unset($n);}}
if (isset($_GET['t'])){$title=trim($_GET['t']); if($title=='') {unset($title);}}
if (isset($_GET['d'])){$time=trim($_GET['d']); if($time=='') {unset($time);} else if($time=='now') $time=time();}
if (isset($_GET['dt'])){$dt=trim($_GET['dt']); if($dt=='') {unset($dt);}}

$error=false;
if(isset($n)){

	include_once 'config.php';
	
	$link = @mysqli_connect($dbhost, $dbuser, $dbpass,$db_name);

	/* check connection */
	if (mysqli_connect_errno()) {
	   $error=true;
	}
	
	
	if(!$error && (isset($title) || isset($time) || isset($dt))){
		$query = 'UPDATE '.$rating_sum.' SET ';
		if(isset($title)) $query .= 'title="'.htmlentities($title).'" , editable=0 ';
		if(isset($time)) $query .= (isset($title)?',':'').' validtill='.$time;
		if(isset($dt)) $query .= ((isset($title) || isset($time))?',':'').' voteperiod='.$dt;
		$query .= ' WHERE n='.$n;
		$result=$link->query($query) or $error=true;	
	}		
	

}

if($error) echo mysqli_error($link).' <br/>sql::'.$query;
else echo '1';
exit();

?>
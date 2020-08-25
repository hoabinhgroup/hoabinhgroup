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
	header("Location: ".$ref.'?&e='.htmlentities(urlencode('Prohibited in demo')));
	exit;
}



$error=false;
if(isset($n)){

	include_once 'config.php';
	$link = @mysqli_connect($dbhost, $dbuser, $dbpass,$db_name);

	/* check connection */
	if (mysqli_connect_errno()) {
	   $error=true;
	}
	

	if(!$error){
		$query = 'DELETE FROM '.$banlist.' WHERE n IN ('.$n.'-1)';
		$result=$link->query($query) or $error=true;				
	}		


}

if($error) header("Location: ".$ref.'?&e='.htmlentities(urlencode(mysqli_error($link))));
else header ("Location: ".$ref); 

exit();

?>
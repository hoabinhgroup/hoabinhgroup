<?php
$inside=true;
include 'redirect.php';

if (isset($_GET['b'])){$b=trim($_GET['b']); if($b=='') {unset($b);}}
if (isset($_GET['ip'])){$ip=trim($_GET['ip']); if($ip=='') {unset($ip);}}

$error=false;
if(isset($b) && isset($ip)){

	include_once 'config.php';
	$link = @mysqli_connect($dbhost, $dbuser, $dbpass,$db_name);

	/* check connection */
	if (mysqli_connect_errno()) {
	   $error=true;
	}
	
	if(!$error){
		if($b==0){ //remove from banlist
			$query = "DELETE FROM $banlist WHERE ip ='$ip'";
		}else{	//insert
			$query = "INSERT INTO $banlist (ip, time) VALUES ('$ip',".time().")";
		}
		$result=$link->query($query) or $error=true;	
		
		
		/*
		$query='select * from '.$banlist;
		$result=$link->query($query) or die(mysqli_error($link));
		
		while($row=mysql_fetch_array($result)){
			echo 'ip:'.$row['time'].'</br>';
		}
		*/
	}

}

if($error) echo mysqli_error($link);
else echo ($b==0)?' ':' '.date('j M Y \<\s\m\a\l\l\>H:i\<\/\s\m\a\l\l\>',time());
exit();

?>
<?php

include_once ('config.php');

//connect to BD
$link = @mysqli_connect($dbhost, $dbuser, $dbpass,$db_name);

/* check connection */
if (mysqli_connect_errno()) {
   die(mysqli_connect_error());
}


if (isset($_GET['dn'])){$dn=$_GET['dn']; if($dn=='') {$dn=20;}}else {$dn=20;}

$data='{"data": [';
$data.='[0,-1],';

$sql='select (floor(rate-0.0001) div '.$dn.')*'.$dn.' as newt, COUNT(*) as newy from '.$rating_det.' GROUP BY newt ASC';
$result = $link->query($sql) or die(mysqli_error($link ));
while($row = mysqli_fetch_array($result)) { 
	$data.='['.($row['newt']).','.$row['newy'].'],';
}


if($row['newt']!=100)
$data.='[100,-1]]  }';
else $data.='[]]}';

echo $data;
?>
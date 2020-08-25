<?php


include_once ('config.php');

//connect to BD
$link = @mysqli_connect($dbhost, $dbuser, $dbpass,$db_name);

/* check connection */
if (mysqli_connect_errno()) {
   die(mysqli_connect_error());
}

$dutc=strtotime("UTC")-time();

$m=1;$d=1;

if (isset($_GET['dt'])){$t=$_GET['dt']; if($t=='') {$t='m';}}else {$t='m';}
if (isset($_GET['t'])){$begin=$_GET['t']; if($begin=='') {$begin=time();}
	else{
		$begin=$begin/1000-$dutc;	
		$d=date("j",$begin);
		$m=date("n",$begin);
		$y=date("Y",$begin);
	}
}else {

if (isset($_GET['d'])){$d=$_GET['d']; if($d=='') {$d=date("j");}}else {$d=date("j");}
if (isset($_GET['m'])){$m=date('n', strtotime($_GET['m'])); if($m=='') {$m=date("n");}}else {$m=date("n");}
if (isset($_GET['y'])){$y=$_GET['y']; if($y=='') {$y=date("Y");}}else {$y=date("Y");}
}

//if(is_nan($m*1.0)) $m=date('n', strtotime($m));

$tmin=mktime(0, 0, 0, $m, $d, $y);


$data='{"ddt":[';
$data.='['.date("j", mktime(0, 0, 0, $m, $d-1, $y)).'],';
$data.='["'.date("M", mktime(0, 0, 0, $m-1, $d, $y)).'"],';
$data.='['.date("Y", mktime(0, 0, 0, $m, $d, $y-1)).'],';

$data.='['.date("j", $tmin).'],';
$data.='["'.date("M", $tmin).'"],';
$data.='['.date("Y", $tmin).'],';

$temp=mktime(0, 0, 0, $m, $d+1, $y);
$data.='['.date("j", $temp).','.(0).'],';

$temp=mktime(0, 0, 0, $m+1, $d, $y);
$data.='["'.date("M", $temp).'",'.(0).'],';

$temp=mktime(0, 0, 0, $m, $d, $y+1);
$data.='['.date("Y", $temp).','.(0).']'; //$temp>time()?1:

$data.='] , "label":"'.(($t=='d')?date("j M Y", $tmin):'').' '.(($t=='m')?date("M Y", $tmin):'').' '.(($t=='y')?date("Y", $tmin):'').'",';
$data.='"data": [';


$day=60*60*24;


//$tmin+=$dutc;

if($t=='d'){	
	$tmax=mktime(0, 0, 0, $m, $d+1, $y);
	$dt=60*60;
	$sql='select (floor(time+'.$dutc.') div '.$dt.')*'.$dt.' as newt, COUNT(*) as newy from '.$rating_det.' WHERE time>='.$tmin.' AND time<'.$tmax.' GROUP BY newt ASC';
	$offset=10;
}
else if($t=='m'){
	$tmin=mktime(0, 0, 0, $m, 1, $y);
	$tmax=mktime(0, 0, 0, $m+1, 1, $y);
	$dt=$day;	
	$sql='select (floor(time+'.$dutc.') div '.$dt.')*'.$dt.' as newt, COUNT(*) as newy from '.$rating_det.' WHERE time>='.$tmin.' AND time<'.$tmax.' GROUP BY newt ASC';
	$offset=20000;
}
else if($t=='y'){
	$tmin=mktime(0, 0, 0, 1, 1, $y);
	$tmax=mktime(0, 0, 0, 1, 1, $y+1);
	
	//avoid time difference between php and mysql
	$sql='select FROM_UNIXTIME('.time().') as nowt';
	$result = $link->query($sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($result);
	$dt=strtotime($row['nowt'])-time();
	
	$sql='select MONTH(FROM_UNIXTIME(time-'.$dt.')) as newt, COUNT(*) as newy from '.$rating_det.' WHERE time>='.$tmin.' AND time<'.$tmax.' GROUP BY newt ASC';
	//$sql='select (FROM_UNIXTIME(time-'.$dt.')) as newt from '.$rating_det.' WHERE time>='.$tmin.' AND time<'.$tmax.' GROUP BY MONTH(FROM_UNIXTIME(time-'.$dt.')) ASC';
	$offset=100000;
}



$result = $link->query($sql) or die(mysqli_error($link));
while($row = mysqli_fetch_array($result)) {
	if($t=='y'){$row['newt']=mktime(0, 0, 0, $row['newt'], 2, $y);}
	$data.='['.(($row['newt'])*1000).','.$row['newy'].'],';
	//$data.='<br>['.(($row['newt'])).','.$row['newy'].'],';
}


$data.='[]],"mm":['.(($tmin+$dutc-$offset)*1000).','.(($tmax+$dutc+$offset)*1000).']}';

echo $data;
?>
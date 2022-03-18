<?php 
header('Access-Control-Allow-Origin: *');
/// by Sandy v2

include ("config.php");  	//change settings in config.php file to fit your server settings


////////////   DONT CHANGE ANYTHING UNDER THIS LINE ///////////////////////////////////////////////////////////////////
if(isset($_SERVER['HTTP_REFERER'])) $ref = $_SERVER['HTTP_REFERER']; else $ref=false;
$rmethod=strtoupper($_SERVER['REQUEST_METHOD']);
if ($rmethod==='GET'){
	if (isset($_GET['id'])){$item_id=htmlspecialchars($_GET['id'], ENT_QUOTES); if($item_id=='') {unset($item_id);}}
	if (isset($_GET['rw'])){$item_rw=$_GET['rw']*1; if($item_rw=='') {$item_rw=0;}}else{$item_rw=0;} 
	if (isset($_GET['p'])){$item_property=htmlspecialchars($_GET['p'], ENT_QUOTES); if($item_property=='') {unset($item_property);}}
	if (isset($_GET['u'])){$ret_users_rate=$_GET['u']; if($ret_users_rate=='') {unset($ret_users_rate);}}
}else{
	if (isset($_POST['id'])){$item_id=htmlspecialchars($_POST['id'], ENT_QUOTES); if($item_id=='') {unset($item_id);}}
	if (isset($_POST['r'])){$item_rate=$_POST['r']; if($item_rate=='') {unset($item_rate);}else{$item_rate=floatval($item_rate);}} //get the rate as number, cut the rest for safety
	if (isset($_POST['rw'])){$item_rw=$_POST['rw']*1; if($item_rw=='') {$item_rw=0;}}else{$item_rw=0;}
	if (isset($_POST['p'])){$item_property=htmlspecialchars($_POST['p'], ENT_QUOTES); if($item_property=='') {unset($item_property);}}
	if (isset($_POST['u'])){$ret_users_rate=$_POST['u']; if($ret_users_rate=='') {unset($ret_users_rate);}}
	if (isset($_POST['i'])){$item_info=htmlspecialchars($_POST['i'], ENT_QUOTES); if($item_info=='') {unset($item_info);}}
	if (isset($_POST['pt'])){$parent_info=htmlspecialchars($_POST['pt'], ENT_QUOTES); if($parent_info=='') {unset($parent_info);}}
	
	//if some browser (IE) doesn't send HTTP_REFERER make the following
	if (!$ref && isset($_POST['ref'])){$ref=trim($_POST['ref']); if($ref=='') {$ref='index.php';}}
}

//after db connection, below, all user's data will be escaped to prevent SQL injections
//expected output: Nvotes|mean|timeToNextVote|parentrate|errorMsg


//if id is not provided -> nothing to save/load -> return error message
if (!isset($item_id)){
	echo '||||itemID cannot be blank';
	exit;
}

//if item rate >100%
if(isset($item_rate)){
	if($item_rate>100 || $item_rate<0){
		echo '||||cheater detected';
		exit;
	}
}

//if id is provided
$time=time();  	//current time
$dt=-1;			
$mean=0;
$votes=0;
$banned=false;
$default_vote_period=2678400; //in seconds
$checkIP=true;
$checkCookie=true;

//connect to BD
$link = @mysqli_connect($dbhost, $dbuser, $dbpass,$db_name);

/* check connection */
if (mysqli_connect_errno()) {
   die('||||'.mysqli_connect_error());
}


//escape to prevent SQL injections
$item_id = mysqli_real_escape_string($link,$item_id);
$ref = mysqli_real_escape_string($link,$ref);  //just in case
if(isset($item_info)) $item_info=mysqli_real_escape_string($link,$item_info);
if(isset($parent_info)) $parent_info=mysqli_real_escape_string($link,$parent_info);
if(isset($item_property)) $item_property=mysqli_real_escape_string($link,$item_property);


//get settings

$query  = 'SELECT * FROM `'.$rating_set.'` WHERE property IN("voteperiod","ip","cookie")';
$result = $link->query($query) or die('||||'.mysqli_error($link).'  ==='.$query);
if($result && mysqli_num_rows($result)>0){
	while($row = $result->fetch_assoc()){
		if($row['property']=='voteperiod'){ $default_vote_period=$row['value'];}
		else if($row['property']=='ip'){ $checkIP=$row['value'];}
		else if($row['property']=='cookie'){ $checkCookie=$row['value'];}
	}
}



//get item statistics
$query  = "SELECT n, mean, votes, voteperiod, editable, validtill, property FROM `$rating_sum` WHERE id='$item_id'";///
if(isset($item_property)) $query  .=" AND (property='' OR property='$item_property')";
//else $query  .=" AND property=''";



$result = $link->query($query) or die('||||'.mysqli_error($link));

$newitem=true;

//if its not a new item
$numofres=mysqli_num_rows($result);
if($result && $numofres>0){
	
	$tempvalid=-1;
	while ($row = $result->fetch_assoc()){
		
		if($row['validtill']>0) $tempvalid=$row['validtill'];
		
		if((isset($item_property) && $row['property']!='') || (!isset($item_property) && $row['property']=='')){
			$mean = $row['mean'];
			$votes = $row['votes'];
			$n = $row['n'];	
			if(!isset($item_property) && $row['property']=='') $default_vote_period=$row['voteperiod'];
			if($row['editable']==0) unset($item_info);
			
			//locked
			if(($tempvalid>0 && $tempvalid<$time) || (!isset($item_property) && $numofres>1)){
				$dt=$time;
				echo "$votes|$mean|$time";
				exit;
			}
						
			
			//check cookie for item id
			if($dt==-1 && $checkCookie) $dt=loadCookie($n); 
			
			$newitem=false;
			
		}elseif($row['property']==''){
			$connectedItemRef=$row['n'];
			$connectedMean=$row['mean'];
			$connectedVotes=$row['votes'];
			$connectedEditable=$row['editable'];
			$default_vote_period=$row['voteperiod'];
		}
	}
}




$givenRate=-1;

//check by IP
if($checkIP){
	$userip=get_ip(); //get IP
	
	//check if ip is in ban list	
	$query  = "SELECT * FROM `$banlist` WHERE ip='$userip'";
	$result = $link->query($query) or die('||||'.mysqli_error($link));
	
	if($result && mysqli_num_rows($result)>0){
		$banned=true;				
	}
	
	
}
////end IP check


//if ip is clean, check if user already rated this item
if(!$newitem && !$banned){
	
	$query  = "SELECT time, rate, n FROM `$rating_det` WHERE id='$n' AND ip='$userip' ORDER BY time DESC LIMIT 1";
	$result = $link->query($query) or die('||||'.mysqli_error($link));
	if($result && mysqli_num_rows($result)>0){
		$row = $result->fetch_assoc();
					
		$dt=$row['time'];			
		$givenRate=$row['rate']; 	//prev. given rate
		$recordRef=$row['n'];		//reference to db record
	}
	
}



//time to next allowed vote
if($dt>0){
	if($default_vote_period<0) $dt=$time;
	else $dt+=$default_vote_period-$time;
}else if(!$newitem && $default_vote_period<0 && $dt>0) $dt=$time;


//account for new feature with vote change
$item_rw_old=$item_rw;
if($dt<0) $item_rw=0;
elseif($item_rw==1) $dt=-1;

//if rate is not provided
if(!isset($item_rate)){
	if(isset($ret_users_rate))	echo ($givenRate>0?"1|".$givenRate:"0|0")."|".(($banned)?$time:$dt); //if banned - disable from the very beginning
	else	echo "$votes|$mean|".(($banned)?$time:$dt); //if banned - disable from the very beginning
	exit;	
}



//if voted already some time ago
if($dt>0){
	echo "$votes|$mean|$dt";
	exit;
}

//if IP banned just before user rated item
if($banned==true){
	echo "$votes|$mean|$time||sorry, your IP is banned";
	exit;
}
	

	

//if everything is fine - update tables
if(!isset($userip)) $userip=get_ip(); //get IP if its not already found


if(!isset($item_info)){
	if(isset($item_property)) $item_info=$item_property;
	else $item_info=$item_id;		
}
	
	
if($mean==0 && $votes==0){
	$mean=$item_rate;
	$votes=1; 
	$ref=htmlentities(urlencode($ref));
	
			
	if(isset($item_property)){
		$query ="INSERT INTO `$rating_sum` (id, mean, votes, time, voteperiod, link, title, property) ";
		$query .="VALUES ('$item_id', $item_rate, 1, $time, $default_vote_period, '$ref', '$item_info', '$item_property')";
		
	}else{	
		$query ="INSERT INTO `$rating_sum` (id, mean, votes, time, voteperiod, link, title) ";
		$query .="VALUES ('$item_id', $item_rate, 1, $time, $default_vote_period, '$ref', '$item_info')";
	}
	
}else{			
	
	if($item_rw==1 && $givenRate>0){ 
		//change users prev. rate to new one
		$mean+=($item_rate-$givenRate)/($votes);
	}else{
		$votes++;	
		$mean+=($item_rate-$mean)/($votes);	
	}
	
	$query = "UPDATE `$rating_sum` SET ";
	$query .="mean = $mean, ";
	$query .="time = $time, ";
	if(isset($item_info)){		
		//if(isset($item_property)) $query .="title = '$item_id::$item_property', ";
		//else 
		$query .="title = '$item_info', ";
	}
	$query .="votes = $votes ";	
	$query .="where n = $n";
	
}



$result = $link->query($query) or die('||||'.$query.'  '.mysqli_error($link));
if(!isset($n)) $n=mysqli_insert_id($link);


if($result){
	//check if connected item (without property) is in DB and insert or update it
	if(isset($item_property)){	
		
		
		if(isset($connectedItemRef)){
			
			if($item_rw==1 && $givenRate>0){ 
				//change users prev. rate to new one
				$connectedMean+=($item_rate-$givenRate)/($connectedVotes);
			}else{
				//formula for main item is mean over all of the subitems
				$connectedVotes++;
				$connectedMean+=($item_rate-$connectedMean)/($connectedVotes);			
			}
			
			$query = "UPDATE `$rating_sum` SET mean = $connectedMean, time = $time, votes = $connectedVotes";
			if($connectedEditable!=0 && isset($parent_info)) $query.=" , title = '$parent_info'";
			$query.=" where n = $connectedItemRef";
			
		}else{
			//insert
			$connectedVotes=1;
			$connectedMean=$item_rate;
			if(!isset($parent_info)) $parent_info='parent of: '.$item_info;
			$query ="INSERT INTO `$rating_sum` (id, mean, votes, time, voteperiod, link, title) ";//validtill, 
			$query .="VALUES ('$item_id', $item_rate, 1, $time, $default_vote_period, '$ref', '$parent_info')";//1,
		}
	
	
		$result = $link->query($query) or die('||||'.$query.'  '.mysqli_error($link));

	}
}


if($result){

	//table with details and IPs
	if($item_rw==1 && $givenRate>0)	$query ="UPDATE `$rating_det` SET rate=$item_rate WHERE n=$recordRef"; //, time=$time
	else $query ="INSERT INTO `$rating_det` (id, ip, rate, time) VALUES ($n, '$userip', $item_rate, $time)";	
	
	$result = $link->query($query) or die('||||'.mysqli_error($link));
	
	
	//if tables updated successfully
	if($result){
		saveCookie($n);  //save cookie
		if($default_vote_period<0) $default_vote_period = $time;
		if($item_rw_old==1) $default_vote_period=-1;
		
		if(isset($ret_users_rate)) echo "1|$item_rate|$default_vote_period".((isset($connectedVotes))?"|$connectedVotes;$connectedMean":"");
		else echo "$votes|$mean|$default_vote_period".((isset($connectedVotes))?"|$connectedVotes;$connectedMean":"");
		
		exit;
	}
	
}


//if no response was send so far, return error message
echo "$votes|$mean|$default_vote_period||unknown error";
	

	


//YES! I DID IT!:)









/////////////////////////////////////FUNCTIONS//////////////////////////////////////


//save time of vote to cookie
function saveCookie($id){
	setcookie("r$id", strval(time()), time()+60*60*24*365);	
}

function clearCookie($id){
	setcookie("r$id", NULL, time()-100);
}


//read cookie and return users last vote time
function loadCookie($id){
	$cook="r$id";	
	if(isset($_COOKIE[$cook])){		
		return $_COOKIE[$cook]*1;	
	}
	else return -1; //can vote now
}


function get_ip(){
	if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])){
		$ip = $_SERVER['HTTP_CLIENT_IP'];		
	}elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
	}elseif (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])){
		$ip = $_SERVER['REMOTE_ADDR'];
	}else{
		$ip = 'unknown';
	}		
	return $ip;
}


?>
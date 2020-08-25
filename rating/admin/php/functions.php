<?php
@include_once ('php/config.php');

//connect to BD
$link = @mysqli_connect($dbhost, $dbuser, $dbpass,$db_name);


/* check connection */
if (mysqli_connect_errno()) {
   die(mysqli_connect_error());
}


// filter related functions
function getCountFiltered($filter){
	GLOBAL $rating_sum, $link;
	$sql='select COUNT(*) as n from '.$rating_sum.' m1 WHERE m1.property=""';
	if($filter!='')	$sql.=' AND '.filterToSQL($filter).' ';
	
	$result = $link->query($sql) or die(mysqli_error($link));
	while($row = mysqli_fetch_array($result)) { 
		return $row['n'];
	}
	return 0;
}


function getCountFilteredVotes($id, $filter){
	GLOBAL $rating_det, $link;
	$sql='select COUNT(*) as n from '.$rating_det.' d ';
	$sql.=' WHERE d.id='.$id.' ';
	if($filter!='')	$sql.=' AND '.filterToSQL2($filter).' ';
	$result = $link->query($sql) or die(mysqli_error($link));
	while($row = mysqli_fetch_array($result)) { 
		return $row['n'];
	}
	return 0;
}


function getCountFilteredIPs($id, $filter){
	GLOBAL $rating_det, $link;
	GLOBAL $rating_sum;
	$sql='select COUNT(*) as n from '.$rating_det.' d ';
	$sql.='LEFT JOIN '.$rating_sum.' s ON s.n=d.id';
	$sql.=' WHERE d.ip=\''.$id.'\' ';
	if($filter!='') $sql.=' AND '.filterToSQL3($filter).' ';
	
	$result = $link->query($sql) or die(mysqli_error($link));
	while($row = mysqli_fetch_array($result)) { 
		return $row['n'];
	}
	return 0;
}


function filterToSQL($filter){	
	$filter=urldecode($filter);
	
	$filters=explode('|',$filter);
	$where=array();		
	
	if($filters[1]!=''){
		$rate=explode(",",$filters[1]);
		if($rate[0]!='' && $rate[1]!='' && $rate[0]>$rate[1]) {$temp=$rate[1]; $rate[1]=$rate[0]; $rate[0]=$temp;}
		if($rate[0]!='' && !is_nan($rate[0]*1.0)) array_push($where,'m1.mean>='.$rate[0]);
		if($rate[1]!='' && !is_nan($rate[1]*1.0)) array_push($where,'m1.mean<='.$rate[1]);
	}
	
	if($filters[2]!=''){
		$votes=explode(",",$filters[2]);
		if($votes[0]!='' && !is_nan($votes[0]*1.0)) $minv=$votes[0];		
		if($votes[1]!='' && !is_nan($votes[1]*1.0)) $maxv=$votes[1];		
	}
	
	if($filters[3]!=''){
		$top=getMinVotes();			
		if($filters[3]=='1') {if(isset($minv)) $minv=max($minv,$top); else $minv=$top;}
		else if($filters[3]=='2') {if(isset($maxv)) $maxv=min($maxv,$top-1); else $maxv=$top-1;}
	}
	
	if($filters[2]!='' || $filters[3]!=''){
		if(isset($minv) && isset($maxv) && $maxv<$minv) {$temp=$maxv; $maxv=$minv; $minv=$temp;}
		if(isset($minv)) array_push($where,'m1.votes>='.$minv);
		if(isset($maxv)) array_push($where,'m1.votes<='.$maxv);
	}
	
	if($filters[4]=='1') array_push($where,'(m1.validtill>'.time().' OR m1.validtill<0)');
	else if($filters[4]=='2') array_push($where,'m1.validtill<'.time().' AND m1.validtill>-1');
	
	if($filters[5]!=''){
		$filters[5]=urldecode($filters[5]);
		$dates=explode(",",$filters[5]);
		//if($dates[0]!='' && !is_nan($dates[0]*1.0)) array_push($where,'d.time>='.$dates[0]);	
		//if($dates[1]!='' && !is_nan($dates[1]*1.0)) array_push($where,'d.time<='.$dates[1]);	

		$min=-2;
		$max=-2;
		
		if($dates[0]!=''){
			$dmy=explode("/",$dates[0]); 
			$min=mktime(0,0,1,$dmy[1],$dmy[0],$dmy[2]);			
		}
		
		if($dates[1]!=''){
			$dmy=explode("/",$dates[1]); 
			$max=mktime(0,0,0,$dmy[1],$dmy[0],$dmy[2]);			
		}
		
		if($max<$min) {$temp=$max; $max=$min; $min=$temp;}
		
		if($min>-2) array_push($where,'m1.time>='.$min);
		if($max>-2) array_push($where,'m1.time<='.($max+24*60*60));

		
	}
	
	if($filters[0]!='') array_push($where,'m1.title LIKE \'%'.$filters[0].'%\'');
	
	return implode(" AND ", $where);
}


function filterToSQL2($filter){	
	$filter=urldecode($filter);
	
	$filters=explode('|',$filter);
	$where=array();		
	
	if($filters[1]!=''){
		$rate=explode(",",$filters[1]);
		if($rate[0]!='' && $rate[1]!='' && $rate[0]>$rate[1]) {$temp=$rate[1]; $rate[1]=$rate[0]; $rate[0]=$temp;}
		if($rate[0]!='' && !is_nan($rate[0]*1.0)) array_push($where,'d.rate>='.$rate[0]);
		if($rate[1]!='' && !is_nan($rate[1]*1.0)) array_push($where,'d.rate<='.$rate[1]);
	}
	
	if($filters[2]!=''){
		$filters[2]=urldecode($filters[2]);
		$dates=explode(",",$filters[2]);
		//if($dates[0]!='' && !is_nan($dates[0]*1.0)) array_push($where,'d.time>='.$dates[0]);	
		//if($dates[1]!='' && !is_nan($dates[1]*1.0)) array_push($where,'d.time<='.$dates[1]);	

		$min=-2;
		$max=-2;
		
		if($dates[0]!=''){
			$dmy=explode("/",$dates[0]); 
			$min=mktime(0,0,1,$dmy[1],$dmy[0],$dmy[2]);			
		}
		
		if($dates[1]!=''){
			$dmy=explode("/",$dates[1]); 
			$max=mktime(0,0,0,$dmy[1],$dmy[0],$dmy[2]);			
		}
		
		if($max<$min) {$temp=$max; $max=$min; $min=$temp;}
		
		if($min>-2) array_push($where,'d.time>='.$min);
		if($max>-2) array_push($where,'d.time<='.($max+24*60*60));

		
	}
	
	
	if($filters[0]!='') array_push($where,'d.ip LIKE \'%'.$filters[0].'%\'');
	
	return implode(" AND ", $where);
}


function filterToSQL3($filter){	
	$filter=urldecode($filter);
	
	$filters=explode('|',$filter);
	$where=array();		
	
	if($filters[1]!=''){
		$rate=explode(",",$filters[1]);
		
		if($rate[0]!='' && $rate[1]!='' && $rate[0]>$rate[1]) {$temp=$rate[1]; $rate[1]=$rate[0]; $rate[0]=$temp;}
		
		if($rate[0]!='' && !is_nan($rate[0]*1.0)) array_push($where,'d.rate>='.$rate[0]);
		if($rate[1]!='' && !is_nan($rate[1]*1.0)) array_push($where,'d.rate<='.$rate[1]);
	}
	
	//TO DO: transfer format DD/MM/YYYY to sec
	if($filters[2]!=''){
		$filters[2]=urldecode($filters[2]);
		$dates=explode(",",$filters[2]);
		//if($dates[0]!='' && !is_nan($dates[0]*1.0)) array_push($where,'d.time>='.$dates[0]);	
		//if($dates[1]!='' && !is_nan($dates[1]*1.0)) array_push($where,'d.time<='.$dates[1]);

		$min=-2;
		$max=-2;
		
		if($dates[0]!=''){
			$dmy=explode("/",$dates[0]); 
			$min=mktime(0,0,1,$dmy[1],$dmy[0],$dmy[2]);			
		}
		
		if($dates[1]!=''){
			$dmy=explode("/",$dates[1]); 
			$max=mktime(0,0,0,$dmy[1],$dmy[0],$dmy[2]);			
		}
		
		if($max<$min) {$temp=$max; $max=$min; $min=$temp;}
		
		if($min>-2) array_push($where,'d.time>='.$min);
		if($max>-2) array_push($where,'d.time<='.($max+24*60*60));
	}
	
	
	if($filters[0]!='') array_push($where,'s.title LIKE \'%'.$filters[0].'%\'');
	
	return implode(" AND ", $where);
}


function getAllItems($col, $order, $page, $limit, $filter){
	GLOBAL $rating_sum, $link;
	
	
	/*
	$sql='select *, COUNT(property) AS numofid from '.$rating_sum;  //
	
	if($filter!=''){	
		$sql.=' WHERE '.filterToSQL($filter).' ';
		//$sql.=' AND property=""';
	}else{
		//$sql.=' WHERE property=""';
	}
	
	
	$sql.=' GROUP BY id DESC';
	
	
	if($col=='voteperiod') $sql.=' ORDER BY property ASC, SIGN('.$col.'+1) '.($order>0?'ASC':'DESC').', ABS('.$col.') '.($order<0?'ASC':'DESC');
	else $sql.=' ORDER BY property ASC, '.$col.' '.($order>0?'ASC':'DESC');
	
	
	if($limit>0 && $page>0) $sql.=' LIMIT '.(($page-1)*$limit).' , '.$limit;
	*/
	
	
	
	//////////////////////////////
	$sql='select m1.*,COUNT(m1.id) AS numofid  from '.$rating_sum.' m1 LEFT JOIN '.$rating_sum.' m2 ON (m1.id = m2.id) WHERE m1.property=""';  // 
	
	if($filter!='')		$sql.=' AND '.filterToSQL($filter).' ';	
	$sql.=' GROUP BY m1.id';
	
	
	if($col=='voteperiod') $sql.=' ORDER BY SIGN(m1.'.$col.'+1) '.($order>0?'ASC':'DESC').', ABS(m1.'.$col.') '.($order<0?'ASC':'DESC');
	else $sql.=' ORDER BY  m1.'.$col.' '.($order>0?'ASC':'DESC');
	
	
	if($limit>0 && $page>0) $sql.=' LIMIT '.(($page-1)*$limit).' , '.$limit;
	
	
	/////////////////////////////
	
	
	
	//echo $sql;
	
	$result = $link->query($sql) or die(mysqli_error($link).' :: '.$sql);
	return $result;
}


function getItemDetails($id, $col, $order, $page, $limit, $filter){
	GLOBAL $rating_det, $link;
	GLOBAL $banlist;
	$sql='select d.rate, d.ip, d.n AS id, d.time, b.n FROM '.$rating_det.' d LEFT JOIN '.$banlist.' b ON b.ip=d.ip WHERE d.id='.$id;
	
	if($filter!='')	$sql.=' AND '.filterToSQL2($filter);
	
	$sql.=' ORDER BY d.'.$col.' '.($order>0?'ASC':'DESC');
	if($limit>0 && $page>0) $sql.=' LIMIT '.(($page-1)*$limit).' , '.$limit;
	$result = $link->query($sql) or die(mysqli_error($link).' | '.$sql);
	return $result;
}


function getIpRates($ip, $col, $order, $page, $limit, $filter){
	GLOBAL $rating_det, $link;
	GLOBAL $rating_sum;
	$sql='select d.rate, d.time, d.n AS detid, s.title, s.n, s.id, s.link, s.property FROM '.$rating_det.' d LEFT JOIN '.$rating_sum.' s ON s.n=d.id WHERE d.ip="'.$ip.'"';

	if($filter!='')	$sql.=' AND '.filterToSQL3($filter);
	
	$sql.=' ORDER BY ';	
	if($col=='title') $sql.=' s.title ';
	else $sql.=' d.'.$col.' ';
	$sql.=($order>0?'ASC':'DESC');
	if($limit>0 && $page>0) $sql.=' LIMIT '.(($page-1)*$limit).' , '.$limit;
	$result = $link->query($sql) or die(mysqli_error($link).' | '.$sql);
	return $result;
}



//get totals functions

function getBannedTotals(){	
	GLOBAL $banlist, $link;
	$sql='select COUNT(*) as sum FROM '.$banlist;
	$result = $link->query($sql) or die(mysqli_error($link).' | '.$sql);
	while($row = mysqli_fetch_array($result)) { 
		return $row;
	}
}

function getIpTotals($ip){
	GLOBAL $rating_det, $link;
	GLOBAL $banlist;//  //
	$sql='select SUM(d.rate) as sumrate, COUNT(*) as votes, b.time AS bannedtime FROM '.$rating_det.' d LEFT JOIN '.$banlist.' b ON b.ip=d.ip WHERE d.ip="'.$ip.'"';
	$result = $link->query($sql) or die(mysqli_error($link).' | '.$sql);
	while($row = mysqli_fetch_array($result)) { 
		return $row;
	}
}

function getBannedDetails($col, $order, $page, $limit){
	GLOBAL $banlist, $link;
	$sql='select n, ip, time FROM '.$banlist.' ORDER BY '.$col.' '.($order>0?'ASC':'DESC');
	if($limit>0 && $page>0) $sql.=' LIMIT '.(($page-1)*$limit).' , '.$limit;
	$result = $link->query($sql) or die(mysqli_error($link).' | '.$sql);
	return $result;
}

function getItemTotals($n){
	GLOBAL $rating_sum, $link;
	$sql='select m1.*, m2.title as parenttitle, m2.n as parentn, m2.validtill, m2.voteperiod from '.$rating_sum.' m1 ';
	$sql.='LEFT JOIN '.$rating_sum.' m2 ON m1.id=m2.id ';
	$sql.='WHERE m1.n='.$n.' AND m2.property=""';
	$result = $link->query($sql) or die(mysqli_error($link).' sql:'.$sql);
	while($row = mysqli_fetch_assoc($result)) { 
		//print_r($row);
		//echo '<br/>';
		return $row;
	}
	
}

function getTotals(){
	GLOBAL $rating_sum, $link;
	$sql='select sum(mean*votes) as summean, sum(votes) as votes, MAX(time) AS last, COUNT(votes) AS items from '.$rating_sum.' WHERE property=""';
	$result = $link->query($sql) or die(mysqli_error($link));
	while($row = mysqli_fetch_array($result)) { 
		return array( "summean" => $row['summean'], "items" => $row['items'],  "votes" => $row['votes'],  "last" =>date('l jS \of F Y H:i:s',$row['last'])); //
	}
}

function getMinVotes(){
	GLOBAL $rating_set, $link;
	$sql='select value from '.$rating_set.' WHERE property="minvotes"';
	$result = $link->query($sql) or die(mysqli_error($link));
	while($row = mysqli_fetch_array($result)) { 
		return $row['value']; //minimum votes req. for popularity calculation
	}
	return 0;
}


function getSettings(){
	GLOBAL $rating_set, $link;
	$sql='select * from '.$rating_set;
	$result = $link->query($sql) or die(mysqli_error($link));
	return $result;	
}

function getSettingsValue($property){
	GLOBAL $rating_set, $link;
	$sql='select value from '.$rating_set.' WHERE property="'.$property.'"';
	$result = $link->query($sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($result);  
	return $row['value'];	
}



function deleteTables($user_pass){
	GLOBAL $rating_set, $link, $rating_sum , $rating_det , $banlist;
	$result=getSettingsValue('user');
	if(!$result) return 55;
	if (strcmp($user_pass,$result) !== 0){
		return 58;
	}else{
		//delete
		$sql="DROP TABLE IF EXISTS $rating_set , $rating_sum , $rating_det , $banlist"; 
		$result = $link->query($sql) or die(mysqli_error($link).' sql:'.$sql);
		return -1;
	}
}

function setSettings($property, $value){
	GLOBAL $rating_set, $link;	
	$err=1;
	$sql = "UPDATE $rating_set SET value='$value' WHERE property='$property'";	
	$result = $link->query($sql) or $err=(mysqli_error($link).' sql:'.$sql);
	return $err;	
}

// echo functions

function getPopularity($rate, $votes, $totalmean, $minvotes){
	if($votes<$minvotes) return 0;
	return ($rate*$votes+$totalmean*$minvotes)/($votes+$minvotes);
}

function getPagination($p, $maxPage, $href){
	$navigation='<ul>';
	$offset=1;
	
	if($maxPage>3){
		$navigation.='<li';
		if($p<=1) $navigation.=' class="disabled"';
		$navigation.='><a href="'.$href.'&amp;p='.($p-1).'">&lt;&lt;</a></li>';
	}
	
	if($maxPage>1){
		for($i=1;$i<min($offset+1,max(1,$maxPage-$offset));$i++){$navigation.=getLink($p,$i,$href);}	
		$j=$i;	if(max($p-$offset,min($offset,$i))>$i) $navigation.='<li class="disabled"><a href="#">...</a></li>';
		for($i=max($p-$offset,$j);$i<=min($p+$offset,$maxPage);$i++){$navigation.=getLink($p,$i,$href);}
		$j=$i;	if(min($maxPage-$offset+1,$j+1)>$i) $navigation.='<li class="disabled"><a href="#">...</a></li>';
		for($i=max($maxPage-$offset+1,$j);$i<=$maxPage;$i++){$navigation.=getLink($p,$i,$href);}
	}
	
	if($maxPage>3){
		$navigation.='<li';
		if($p>=$maxPage) $navigation.=' class="disabled"';
		$navigation.='><a href="'.$href.'&amp;p='.($p+1).'">&gt;&gt;</a></li>';
	}
	
	return $navigation.'</ul>';
}

function getLink($p,$i,$href){
	$navigation='<li';
	if($p==$i) $navigation.=' class="active"';
	$navigation.='><a href="'.$href.'&amp;p='.$i.'">'.($i).'</a></li>';	
	return $navigation;
}

function secondsToString($n){
	if($n<0) return '&#8734;';
	if($n==0) return '';
	$k=100;
	$temp=$n/(3600*24);	if(isNRound($temp)) return $temp.' day'.($temp==1?'':'s');
	$temp=$n/(3600); if(isNRound($temp)) return $temp.' hour'.($temp==1?'':'s');
	$temp=$n/(60); if(isNRound($temp)) return $temp.' min.';
	return $n.' sec.';
}

function secondsToObj($n){
	if($n<0) return array('&#8734;','');
	if($n==0) return array('','');
	$k=100;
	$temp=$n/(3600*24);	
	if(isNRound($temp)) return array($temp,'d');
	$temp=$n/(3600); 
	if(isNRound($temp)) return array($temp,'h');
	$temp=$n/(60); 
	if(isNRound($temp)) return array($temp,'m');
	return array($n,'s');
}

function isNRound($n){
	return round($n*100)/100==$n;
}
?>
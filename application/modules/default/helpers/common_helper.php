<?php defined('_LOUIS') or die('Access Denied');
	
		if (!function_exists('getIpClient')) {
    /**
     * @param $limit
     * @return mixed
     * @author Tuan Louis
     */
    function getIpClient()
    {
	    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
    }
}

if (!function_exists('typeChat')) {
function typeChat()
		{
			$date = getdate();
	$day = $date['weekday'];
	$hours = $date['hours'];
	$minutes = $date['minutes'];
	
	$hour_minutes = floatval($date['hours'].'.'. $date['minutes']);
	
	if($day == 'Saturday'){
		return 'facebook';
	}elseif($day == 'Sunday'){
		return 'facebook';
	}else{
		if((($hour_minutes >= 17.30) && ($hour_minutes <= 23.59)) || (($hour_minutes >= 00.00) && ($hour_minutes <= 8.30))){
		return 'facebook';
	}else{
		return 'subiz';
		}
	}
		}
		
}		

if (!function_exists('getApiUrl')) {
function getApiUrl($url){
	$json = file_get_contents($url);
	$json = json_decode($json);
	return $json;
}
}
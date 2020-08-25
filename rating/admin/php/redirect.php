<?php if(session_id() == "") session_start();
if(!isset($_SESSION['username'])){
	include((isset($inside)?'':'php/').'login.php'); 
	exit();
} 
?>
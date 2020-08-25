<?php
session_start(); 
$ref = getenv('HTTP_REFERER');

if(!isset($ref) || $ref=='') $ref='../index.php';

if(isset($_SESSION['username'])) { 
	session_unset(); 
	session_destroy(); 
	session_write_close();
	setcookie(session_name(),'',0,'/');
	session_regenerate_id(true);
	header("Location: ".$ref); } 
else { header("Location: ".$ref); }
?>
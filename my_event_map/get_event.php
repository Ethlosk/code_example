<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: text/html; charset=utf-8");
require_once('class_event.php');
//if( $_SERVER['X-REQUESTED-WITH'] != 'XMLHttpRequest')
//die( 'Request Error!' );

$obg = new my_event();

switch($_POST['action']) {
	case "get_event":
	$obg->select_event($_POST['country_id'], $_POST['region_id'], $_POST['city_id']);
	break;
 } 	  
?>

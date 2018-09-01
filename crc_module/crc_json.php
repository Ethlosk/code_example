<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class_crc_select_json.php');
if( $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest')
	die( 'Request Error!' );
$obg = new sel_crc();
switch($_POST['action']) {
	    case "selectForCountry":
       $obg->sel_country();
       break;
	    case "selectForRegion":
       $obg->sel_region($_POST['country_id']);
       break;
       case "selectForCity":
       $obg->sel_city($_POST['region_id']); 
       break;
       };
?>
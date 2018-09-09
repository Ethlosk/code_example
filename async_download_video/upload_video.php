<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class_download_video.php');


if( $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest')
die( 'Request Error!' );

$obg = new downloader_video();
$obg->validate_empty($_POST['id_rezident']); //параметр собственника видео
$obg->validate_empty($_POST['video_status']); //параметр категории видео
$obg->validate_empty($_FILES['rez_video']['tmp_name']);
$obg->validate_empty($_FILES['rez_video']['name']);
if($obg->check_fc_video($_POST['id_rezident'])) {
	exit("Продолжите интеграцию видео ученика в соц. сети! В базу данных больше 4 видео добавлять нельзя!");
	}
$path_val = $obg->upload_video($_FILES['rez_video']['tmp_name'], $_FILES['rez_video']['name'], $_POST['id_rezident'], $_POST['video_status']);

?>
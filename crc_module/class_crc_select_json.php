<?php
require_once ('config_class.php'); //тут должны быть ваши параметры подключения к БД Mysql


//Класс выбора страны, региона, города
class sel_crc
{
	private $mMysqli;
	private $country = array();
	private $region = array();
	private $city = array();
		 // соединение с базой данных
  function __construct()
  {
    $this->mMysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
    $this->mMysqli->set_charset("utf8");
  }

  // разрыв соединения после уничтожения последней ссылки на класс
  function __destruct()
  {
  $this->mMysqli->close();      
  }
 
  // функция предоставления id и названия страны в формате json
  function sel_country() {
  	if($stmt=$this->mMysqli->prepare("SELECT country_id, name FROM country")) {
  	$stmt->execute();
  	$stmt->bind_result($country_id, $name);
  	while($stmt->fetch()) {
  		$this->country[$country_id] = $name;
  		}
  		$stmt->close();
  	echo json_encode($this->country);
  		} else {die('Select Error (' . $stmt->errno . ') ' . $stmt->error);}
  		}
  		
  function sel_region($country_id) {
  		if($stmt=$this->mMysqli->prepare("SELECT region_id, name FROM region WHERE country_id='". intval($country_id)."'")) {
  		$stmt->execute();
  		$stmt->bind_result($region_id, $name);
  		while($stmt->fetch()) {
  		$this->region[$region_id] = $name;
  		}
  		$stmt->close();
  	echo json_encode($this->region);
  		} else {die('Select Error (' . $stmt->errno . ') ' . $stmt->error);}
  		}
  	   
  	   function sel_city($region_id) {
  		if($stmt=$this->mMysqli->prepare("SELECT city_id, name FROM city WHERE region_id='". intval($region_id)."'")) {
  		$stmt->execute();
  		$stmt->bind_result($city_id, $name);
  		while($stmt->fetch()) {
  		$this->city[$city_id] = $name;
  		}
  		$stmt->close();
  	echo json_encode($this->city);
  		} else {die('Select Error (' . $stmt->errno . ') ' . $stmt->error);}
  		}
  	
}


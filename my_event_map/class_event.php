<?php
require_once ('config_class.php');
class my_event {
	private $event = array();
	private $event_partic = array();
	private $country_id;
	private $region_id;
	private $city_id;
	private $name_event;
	private $adr_event;
	private $desc_event;
	private $lat;
	private $lng;
	private $date_event;
	private $id_rezident;
	private $id_sch;
	private $id_event;
	private $date_part;
	
	
	 function __construct()
  {
  	 mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $this->mMysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
    $this->mMysqli->set_charset("utf8");
  }
   
  // разрыв соединения после уничтожения последней ссылки на класс
  function __destruct()
  {
  $this->mMysqli->close();      
  }
  
  private function prepar_date($string_date) {
         $day = substr($string_date,0,2);
         $month = substr($string_date,3,2);
         $year = substr($string_date,6,4);
         return $year."-".$month."-".$day;
  	}
  	private function format_date($string_date) {
  		$date_val = $this->prepar_date($string_date);
  		$date = new DateTime($date_val);
      return $date->format('Y-m-d H:i:s');
  		}
  //функция добавления нового события
  public function add_event($country_id_val, $region_id_val, $city_id_val, $name_event_val, $adr_event_val, $lat_val, $lng_val, $desc_event_val, $date_event_val) {
  	$stmt=$this->mMysqli->prepare("INSERT INTO `my_event` (country_id, region_id, city_id, name, address, lat, lng, description, date, id_rezident, id_sch) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	$stmt->bind_param("iiissddssii", $this->country_id, $this->region_id, $this->city_id, $this->name_event, $this->adr_event, $this->lat, $this->lng, $this->desc_event, $this->date_event, $this->id_rezident, $this->id_sch);
	$this->country_id = $country_id_val;
	$this->region_id = $region_id_val;
	$this->city_id = $city_id_val;
	$this->name_event = $name_event_val;
	$this->adr_event = $adr_event_val;
	$this->lat = (float)$lat_val;
	$this->lng = (float)$lng_val;
	$this->desc_event = $desc_event_val;
	$this->date_event = $this->format_date($date_event_val);
	$this->id_rezident = ($_SESSION['status'] == "rez")?$_SESSION['id_rezident']:null;
	$this->id_sch = $_SESSION['id_sch'];
   $stmt->execute();
   if($stmt->affected_rows > 0)
      	   {
      	   	echo 1;
      	   } else {
      	   	echo 0;
      	   	}
	$stmt->close();
  	}
  	//фунция выбора события из базы данных
  	public function select_event($country_id_val, $region_id_val = null, $city_id_val = null) {
  		if($city_id_val !== null) {
  			$stmt=$this->mMysqli->prepare("SELECT id, name, address, lat, lng, description, date, id_sch FROM my_event  WHERE city_id=? AND (date > (CURDATE()-1))");
  		   $stmt->bind_param("i", $city_id);
  		   $city_id = $city_id_val;
  			} elseif($region_id_val !== null && $city_id_val == null) {
  				      $stmt=$this->mMysqli->prepare("SELECT id, name, address, lat, lng, description, date, id_sch FROM my_event  WHERE region_id=? AND (date > (CURDATE()-1))");
  		            $stmt->bind_param("i", $region_id);
  		            $region_id = $region_id_val;
  				} elseif($region_id_val == null && $city_id_val == null) { 
  				         $stmt=$this->mMysqli->prepare("SELECT id, name, address, lat, lng, description, date, id_sch FROM my_event  WHERE country_id=? AND (date > (CURDATE()-1))");
  		               $stmt->bind_param("i", $country_id);
  		               $country_id = $country_id_val;
  				}
  				$stmt->execute();
  				$stmt->bind_result($id, $name, $address, $lat, $lng, $description, $date, $id_sch);
  				$i = 0;
  				while($stmt->fetch()) {
  					 $i++;
  					$this->event['comp_'.$i]['id'] = $id;
	  		   	$this->event['comp_'.$i]['name'] = $name;
	  		   	$this->event['comp_'.$i]['adress'] = $address;
	  		   	$this->event['comp_'.$i]['lat'] = $lat;
	  		   	$this->event['comp_'.$i]['lng'] = $lng;
	  		   	$this->event['comp_'.$i]['description'] = $description;
	  		   	$this->event['comp_'.$i]['date'] = $date;	
	  		   	$this->event['comp_'.$i]['id_sch'] = $id_sch;			
  					}
  					$stmt->close(); 
  					echo json_encode($this->event);
  		}
  		
  		//функция проверки наличия данного участника в событии
  		private function is_event_part($id_event_val){
  			$stmt=$this->mMysqli->prepare("SELECT status_part FROM event_part WHERE id_rezident=? AND id_sch=? AND id=?");
  			$stmt->bind_param("iii", $id_rezident, $id_sch, $id);
      		$id_rezident = ($_SESSION['status'] == "rez")?$_SESSION['id_rezident']:null;
      		$id_sch = $_SESSION['id_sch'];
      		$id = $id_compet_val;
  		      $stmt->execute();
  		      $stmt->bind_result($status);
  		      $stmt->store_result();
  		      $rows = $stmt->num_rows;
  		      $stmt->fetch();
  		      $stmt->close();
      		if($rows) {
      			return true;
      			} else {
      				return false;
      			}
  			}
  			
  		//функция добавления нового участника события в базу данных
  	public function add_event_part($id_event_val){
  	if($this->is_event_part($id_event_val)) {
  		echo 2;
  	} else {
  	$stmt=$this->mMysqli->prepare("INSERT INTO `event_part` (id, id_rezident, id_sch, date) VALUES (?, ?, ?, ?)");
	$stmt->bind_param("iiis", $this->id_event, $this->id_rezident, $this->id_sch, $this->date_part);
	$this->id_event = $id_event_val;
	$this->id_rezident = ($_SESSION['status'] == "coach")?$_SESSION['id_rezident']:null;
	$this->id_sch = $_SESSION['id_sch'];
	$this->date_part = date("Y-m-d H:i:s");
   $stmt->execute();
   if($stmt->affected_rows > 0)
      	   {
      	   	echo 1;
      	   } else {
      	   	echo 0;
      	   	}
	$stmt->close();
  			}
  			}
  			
  //функция удаления заявки на участие в событии из базы данных
  public function delete_event_part($id_event_val){
  	if(!$this->is_event_part($id_event_val)) {
  		echo 2;
  		} else {
  	$stmt=$this->mMysqli->prepare("DELETE FROM `event_part` WHERE id=? AND id_rezident=? AND id_sch=?");
	$stmt->bind_param("iii", $this->id_compet, $this->id_rezident, $this->id_sch);
	$this->id_compet = $id_compet_val;
	$this->id_rezident = ($_SESSION['status'] == "rez")?$_SESSION['id_rezident']:null;
	$this->id_sch = $_SESSION['id_sch'];
   $stmt->execute();
   if($stmt->affected_rows > 0)
      	   {
      	   	echo 1;
      	   } else {
      	   	echo 0;
      	   	}
  			}
  		$stmt->close();
  		}
  	
  //функция выбора участников конкретного события		
  public function get_partic($id_event_val){
  	$query = "SELECT 
  			          sch.sch_name,
  			          city_sch.name
  			          FROM
  			          school sch
  			          LEFT JOIN
  			          event_part part
  			          USING( id_sch )
  			          LEFT JOIN
  			          city city_sch
  			          ON
  			          sch.id_sch = part.id_sch AND city_sch.city_id = sch.city_id
  			          WHERE part.id = ?
  			         ";
  			 if($stmt = $this->mMysqli->prepare($query)) {
  			 	$stmt->bind_param("i", $id);
            $id = $id_event_val;
            $stmt->execute();
            $stmt->bind_result($sch_name, $city_name_sch);
            $i = 0;
  			while($stmt->fetch()) {
  			$i++;
  			$this->event_partic['partic_'.$i]['sch_name'] = $sch_name;
  			$this->event_partic['partic_'.$i]['city_name_sch'] = $city_name_sch;
  		  }
  		  $stmt->close();
  		 echo json_encode($this->event_partic);
  			
  			}
  	}
  	 	
}

<?php
require_once ('/var/www/config_class.php'); // здесь ваши параметры подключения к БД

//Класс загрузки видео на сервер и отражения информации в БД

class downloader_video
{
	private $mMysqli;
	private $db;
	// путь до корневого каталога
	private $path_root = '/var/www/'; //пример
	
	//папка для загрузки видео
	private $path_to_upload_video = 'upload/video/'; //пример
	
	//массив доступных расширений для добавляемого видео
	private $mime_video_types = array(

        // video
        'mp4' => 'video/mp4',
        'webm' => 'video/webm',
        'ogv' => 'application/ogg'
        );
	
	// соединение с базой данных
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
  
   // проверка введенного значения на пустоту
  	   public function validate_empty($value)
  	   {
  	   	$value = trim(strtolower($value));
  	   	if ($value == null || empty($value)) 
  	   	{
  	   	 exit("Данный параметр не может быть пустым!");
  	      }
  	   }
  		
  //функция генерации максимальной текущей позиции видео, всего в БД может быть 4 видео
  public function get_pos_video($id_rez_val){
  	if($stmt=$this->mMysqli->prepare("SELECT MAX(pos) FROM video WHERE id_rezident=?")) {
  		$stmt->bind_param("i", $id_rezident);
  		$id_rezident = $id_rez_val;
  		$stmt->execute();
  		$stmt->bind_result($pos);
  		if($stmt->fetch()) {
  		  if($pos ==4) exit("");
  		  $max_pos = $pos+1;
  		  return $max_pos;
  		  } else { $max_pos = 1;
  		           return $max_pos;
  		   }
  		 $stmt->close();  
  	}
  	}
  	
  	//функция ответа по количеству видео в БД
  	public function check_fc_video($id_rez_val) {
  		if($stmt=$this->mMysqli->prepare("SELECT id_video FROM video WHERE id_rezident=?")) {
  		$stmt->bind_param("i", $id_rezident);
  		$id_rezident = $id_rez_val;
  		$stmt->execute();
  		$stmt->store_result();
  		$rows = $stmt->num_rows;
  		if($rows>3) {
  			//exit("Продолжите интеграцию видео ученика в соц. сети! В базу данных больше 4 видео добавлять нельзя!");
  			echo 1;
  		} else {
  			echo 0;
  		}
  		$stmt->close();
  		}
  			
  		}

   
   public function upload_video($source, $filename, $id_rez_val, $status_val)
	{
		 if(preg_match('/[.](mp4)|(MP4)|(webm)|(WEBM)|(ogv)|(OGV)$/', $filename))
           {
           	$max_pos = $this->get_pos_video($id_rez_val);
            $target    = $this->path_to_upload_video.$id_rez_val."_".$max_pos."_".$filename;
            $mime_type = null;
            if (class_exists('finfo')){
            $handle = new finfo(FILEINFO_MIME);
            $mime_type = $handle->buffer(file_get_contents($source));
            if($mime_type !== 'application/x-empty') {
              $pos = strpos($mime_type, ';');
              $mime = substr($mime_type, 0, $pos);
                if(in_array($mime, $this->mime_video_types)) {
        	         if(move_uploaded_file($source, $target)) {
        	       	$this->write_path_video($id_rez_val, $status_val, $target, $max_pos, $filename);
        	       	} else { exit("Файл не скопирован! Ваш файл не должен быть больше 150 МВ!");
        	       	       }
        	     
              } else { exit("Недопустимый тип по MIME");} 
            } else { exit("Тип файла не определен! Загружаемые файлы должны быть формата mp4, webm, ogv");}
         }                 
        } else { exit("Недопустимый тип по расширению!");}
   }
   
   // функция записи путь к видео в базу данных
   private function write_path_video($id_rez_val, $status_val, $path_val,  $max_pos_val, $filename) {
   	      $stmt = $this->mMysqli->prepare('INSERT INTO video (id_rezident, id_writer, pos, path, status,  time_video) VALUES (?, ?, ?, ?, ?, ?)');
      		$stmt->bind_param("iiisss", $id_rezident, $id_writer, $pos, $path, $status, $time_video); 
      		$id_rezident = $id_rez_val; 
      		$id_writer = ($_SESSION['status'] == "writer")?$_SESSION['id_rezident']:null; //получение id записывающего
      		$pos = $max_pos_val;
      		$path = $path_val;
      		$status = $status_val;
      	   $time_video = date("Y-m-d H:i:s");
      	   $stmt->execute();	      
      	   if($stmt->affected_rows > 0)
      	   {
		   	echo ($filename); 
      	   
      	   } else {
      	   	echo "Не удалось записать файл в базу";
      	   	}
      	   $stmt->close();
   	}
  	

  }
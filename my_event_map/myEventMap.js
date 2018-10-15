;
var oMap = {
        bounds: null,
        map: null,
        marker: null,
        latMarker: null,
        lngMarker: null,
        arrMarkers:[],
        arrInfoWindows:[]
       }
       /*кроссбраузерная версия получения объекта Xmlhttprequest*/
       oMap.getXMLHttpRequest = function(){
       	if (typeof XMLHttpRequest === 'undefined') {
         XMLHttpRequest = function() {
         try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); }
         catch(e) {}
         try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); }
         catch(e) {}
         try { return new ActiveXObject("Msxml2.XMLHTTP"); }
         catch(e) {}
         try { return new ActiveXObject("Microsoft.XMLHTTP"); }
         catch(e) {}
         throw new Error("This browser does not support XMLHttpRequest.");
    };
    }
       return new XMLHttpRequest();
       	};
       	
       	/*функция инициализации карты google*/
       oMap.init = function() {
       	var labelInfoMap = document.createElement ('label');
       	labelInfoMap.innerHTML = 'Для того чтобы отметить место проведения мероприятия на карте необходимо кликнуть левой кнопки мыши в точку на карте. 
       	                          В форме под картой выберите необходимые параметры и заполните информацию по мероприятию!';
       	var divInfo = document.getElementById("divHelpCompet");
       	 divInfo.appendChild(labelInfoMap);
       	/*HTML5 geolocation.*/
	       oMap.map = new google.maps.Map(document.getElementById('mapEvent'), {
          center: {lat: 55.763585, lng: 37.560883},
          zoom: 5
        });
        
        if (navigator.geolocation) {
        	// alert("Geolocation API поддерживается");
          navigator.geolocation.getCurrentPosition(function(position) {
          var pos = {lat: position.coords.latitude, lng: position.coords.longitude}; 
          oMap.map.setCenter(pos); 
          oMap.map.setZoom(14);
          });
        } else {
          // Browser doesn't support Geolocation
          alert("Browser doesn't support Geolocation"); 
        } 

   google.maps.event.addListener(oMap.map, 'click', function(event) {

   oMap.placeMarker(event.latLng);
   //alert("lat = "+oMap.latMarker+" lng = "+oMap.lngMarker);

});
};
 
/*функция устанавливающая маркер на карте, используется в функции init*/    
oMap.placeMarker = function(location) {
	oMap.latMarker = location.lat();
	oMap.lngMarker = location.lng();
	if (oMap.marker == null)
 {
   oMap.marker = new google.maps.Marker({
      position: location,
      label: 'E',
      map: oMap.map
      }); 
  } else {   oMap.marker.setPosition(location);}
};

/*функция для получения информации о мероприятиях с отметками маркерами на карте*/

oMap.getEventMarkers = function() {
	var xmlhttp = oMap.getXMLHttpRequest();
	var divEvent = document.getElementById("divGetEvent"); // выбора дива с селектами выбора страны, региона, города
	var divListEvent = document.getElementById("divListEvent"); // выбора дива для добавления списка событий
	var divMap = document.getElementById("mapEvent"); // выбор дива карты
   var selCountry = document.getElementById("country_id");	// выбор селекта страны
   var countryId = selCountry.options[selCountry.selectedIndex].value; // выбор пункта селекта страны
	var selRegion = document.getElementById("region_id");	//выбор селекта региона
   var regionId = selRegion.options[selRegion.selectedIndex].value; //выбор пункта селекта региона
   var selCity = document.getElementById("city_id");	// выбор селекта города
   var cityId = selCity.options[selCity.selectedIndex].value; // выбор пункта селекта города
   var params ='action='+encodeURIComponent('get_event')+'&country_id='+encodeURIComponent(countryId)+'&region_id='+encodeURIComponent(regionId)+'&city_id='+encodeURIComponent(cityId);
   //alert(params);
	xmlhttp.open("POST", 'get_event.php', true);
   xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=utf-8");
   //xmlhttp.open("POST", "/json-handler");
   //xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
   //xmlhttp.setRequestHeader("X-REQUESTED-WITH", "XMLHttpRequest");
   xmlhttp.send(params);
   xmlhttp.onreadystatechange = function()
    {
        //Если обмен данными завершен
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
        	// Try HTML5 geolocation.
	       oMap.map = new google.maps.Map(document.getElementById('mapEvent'), {
          center: {lat: 55.763585, lng: 37.560883},
          zoom: 5,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        //Определяем область отображения меток на карте
          var oldEventLink = document.getElementById('markersEvent'); //Проверяем наличие старого списка в диве списков
          if (oldEventLink !== null) oldEventLink.parentNode.removeChild(oldEventLink); //удаляем старый список
		    var latlngbounds = new google.maps.LatLngBounds();
          var divLink = document.getElementById('linkEvent');
          var olList = document.createElement("ol");
          olList.id = 'markersEvent';
          olList.classList.add("rounded-list");
          var data = xmlhttp.responseText;
          var jsonData = JSON.parse(data);
              var i=0;
              for (key in jsonData) {
              if (jsonData.hasOwnProperty(key)) {
              	// начало построения списка мероприятий в диве для выбора по клику на карте.
              var liNode = document.createElement("li");
              var dateEvent = jsonData[key]["date"].substr(0, 10);
             liNode.innerHTML+='<a id="numList" href="#" rel="'+i+'"/>'+jsonData[key]["name"]+' ('+dateEvent+')</a><a class="select" id="get_event_part" title="Для просмотра участников мероприятия нажмите на кнопку!" href=get_event_partic.php?id_event='+jsonData[key]["id"]+
					                '&action=get_event_part>Участники</a>';
             olList.appendChild(liNode);
              var marker = new google.maps.Marker({
					position: new google.maps.LatLng(parseFloat(jsonData[key]["lat"]), parseFloat(jsonData[key]["lng"])),
					map: oMap.map,
					title:jsonData[key]["name"]
				});
				//Добавляем координаты меток
				latlngbounds.extend(new google.maps.LatLng(parseFloat(jsonData[key]["lat"]), parseFloat(jsonData[key]["lng"])));
				oMap.arrMarkers[i] = marker;
				var infowindow = new google.maps.InfoWindow({
					content: "<h3>"+ jsonData[key]["name"] +"</h3><p> Дата мероприятия: "+ dateEvent +"</p></br>"+"<p> Адрес: "+ jsonData[key]["adress"] +"</p></br>"+"<p> Инфо: "+ jsonData[key]["description"]+"</br>"+
					         "<a class='select' id='add_my_event' title='Для подачи заявки на участие в мероприятии нажмите на кнопку!' href=add_my_event.php?id_event="+jsonData[key]["id"]+
					         "&org="+jsonData[key]["id_sch"]+"&action=add_my_event>Подать заявку</a></br> "+
					         "<a class='select' id='del_my_event' title='Для того чтобы отзаявиться нажмите кнопку!' href=delete_my_event.php?id_event="+jsonData[key]["id"]+
					         "&org="+jsonData[key]["id_sch"]+"&action=del_my_event>Отзаявиться</a>"
					         
				});
				oMap.arrInfoWindows[i] = infowindow;
 
				google.maps.event.addListener(marker, 'click', function() {
 
					infowindow.open(oMap.map, marker);
 
				});
				i++;
      } // если объект jsonData имеет key (если у jsonData есть свойство key)
    } // перерабрать все ключи (свойства) в объекте
    divListEvent.appendChild(olList);    
  //Центрируем и масштабируем карту так, чтобы были видны все метки
    oMap.map.setCenter( latlngbounds.getCenter(), oMap.map.fitBounds(latlngbounds));     
};
        }
  
	
	};

;(function( $ ){
  
  var methods = {	
	
/*Функция выбора страны*/

selectCountry: function(element) {
	   $('#country_id').remove();
	   $('#region_id').remove();
	   $('#city_id').remove();
       $.ajax({
                        type: "POST",
                        beforeSend: function() {
                        element.append("<div class='selectStyle'><select name='country_id' id='country_id' style='float:left;'>"+
                        "<option value=''> СТРАНЫ </option><optgroup label='Выберите страну'></optgroup></select></div>");
                        },
                        url: "crc_json.php",
                        data: { action: 'selectForCountry'},
                        cache: false,
                        dataType: 'JSON',
                        success: function(json){ 
                        var optionCountry = ' ';
                       $.each(json, function(key, value) {
                      optionCountry+="<option value="+key+">"+value+"</option>";
                      });
                      $('#country_id').append(optionCountry);
                      },
                      });
        },
        
/*функция выбора региона*/
selectRegion: function() {
	     $('#region_id').remove();
	     $('#city_id').remove();
        var country_id =  $('#country_id option:selected').val();                                                   
        if(!country_id) {
               $('#region_id').remove();
	            $('#city_id').remove();
                alert('Не удалось выбрать параметр из селекта');
        }else{
                $.ajax({
                        type: "POST",
                        beforeSend: function() {
                                               $("select#country_id").after("<div class='selectStyle'><select name='region_id' id='region_id' style='float:left;'></select></div>");
                                               $('#region_id').append($("<option/>", {
                                               value: '0',
                                               text: 'Регион'
                                              }));
                        },
                        url: "crc_json.php",
                        data: { action: 'selectForRegion', country_id: country_id },
                        cache: false,
                        dataType: 'JSON',
                        success: function(json){ 
                        var optionRegion = ' ';
                       $.each(json, function(key, value) {
                      optionRegion+="<option value="+key+">"+value+"</option>";
                      });
                      $('#region_id').append(optionRegion);
                      },
                      });
                        };
},

/*функция выбора города*/
selectCity: function() {
 	     $('#city_id').remove();
        var region_id = $('#region_id option:selected').val();
        if(!region_id) {
        	alert('Не удалось выбрать селект региона');
        	} else {
        $.ajax({
                type: "POST",
                beforeSend: function() {
                        $("select#region_id").after("<div class='selectStyle'><select name='city_id' id='city_id' style='float:left;'></select></div>");
                        $('#city_id').append($("<option/>", {
                                               value: '0',
                                               text: 'Город'
                                              }));
                        },
                url: "crc_json.php",
                data: { action: 'selectForCity', region_id: region_id },
                cache: false,
                dataType: 'JSON',
                success: function(json){ 
                var optionCity = ' ';
                $.each(json, function(key, value) {
                      optionCity+="<option value="+key+">"+value+"</option>";
                      });
                      $('#city_id').append(optionCity);
                      },
                      });
                     };
                     },
		
 		
 	/*КОНЕЦ РАЗДЕЛА ИТОГОВЫХ ФУНКЦИЙ*/
  };
  
  $.fn.crc = function( method ) {
    
    // логика вызова метода
    if ( methods[method] ) {
      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Метод с именем ' +  method + ' не существует для jQuery.crc' );
    } 
  };

})( jQuery );
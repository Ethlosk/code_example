;(function( $ ){
 var widget = this;	
  
  var methods = {

  		/*метод вывода формы для добавления видео*/
  		addFormVideo: function(){
  			$(this).append("<p>Можно загрузить не более 4 видео до 50 МВ каждое!</p>"+
  			               "<p>Загружаемое видео должно быть формата .mp4, .webm, .ogv !</p>"+
  			               "<p>У каждого ученика все 4 видео должны быть одного формата !</p>"+
  			               "<iframe id='iframeUp' name='iframe_upload' src='#' style='display: none'></iframe>"+
  			               "<form id='videoform' action='upload_video.php'    method='post' enctype='multipart/form-data' target='iframe_upload'>"+
  			               "</form>"+
  			               "<div id='infoUpVideo' name='infoUpVideo'></div>");
  			},
  			
  			/*добавление инпута с кнопкой в форму видео*/
  			addInputVideo: function(element){
  			                element.append("<p>Добавить видео файл:</p><br>"+
  			               "<input id='rezVideo' type='FILE'    name='rez_video'>"+
  			               "<input type='hidden' id='video' name='video' value='ok'>"+
  			               "<input id='upVideoBut' type='submit' name='upVideoBut' value='отправить' disabled>"); // в кнопке включено свойство disabled, которое убирается специальной проверочной функцией в главной форме, при заполнении
  			                                                                                                      // всех необходимых полей;
  			},	
  			
  	
  	/*метод загрузки видео*/	
  	uploadVideo: function(event){
     event.preventDefault();
     var infoDiv = $('div[name="infoUpVideo"]'); // див для отображения информации о ходе загрузки
     infoDiv.html("");
     var formVideoData = new FormData();
     var videoFiles = $('#rezVideo');
     // Добавление файлов в formVideoData
        videoFiles.each(function(index, videoFile) {
            if (videoFile.files.length) {
                formVideoData.append('rez_video', videoFile.files[0]);
            }
            //выбираем остальные поля формы
             var id_rezident = $('#id_rezident option:selected').val(); // собственник видео, отображается селектом и добавляется в форму отдельной функцией
             var video_status = $('#video_status option:selected').val(); // категория видео, отображается селектом и добавляется в форму отдельной функцией
             
             /*если в форме загрузки видео есть еще поля, то вы должны позаботиться об их выборке*/
             
              //присоединяем остальные поля, в т.ч. ваши собственные, которых нет в данном примере
	          formVideoData.append('id_rezident', id_rezident);
	          formVideoData.append('video_status', video_status);
	          });
	          // Отправка на сервер
        $.ajax({
        	   beforeSend: function() {
                       infoDiv.append("<label> Подождите идет загрузка ...</label>");
                                   },
            url: 'upload_video.php',
            data: formVideoData,
            type: 'POST',
            processData: false,
            contentType: false,
            success: function(responce) {
            	 infoDiv.html("");
            	 var video = responce.substr(1);
                infoDiv.append("<label>Файл ("+video+") загружен !</label>");
                videoFiles.val("");
                $('#id_rezident').val("");
                $('#video_status').val(""); 
                $("#upVideoBut").prop('disabled', true);  
            }
        });
  		},
  	/* тут могут находится прочие методы по работе с видео*/	
  };
  
  
  
  
  $.fn.downloadVideo = function( method ) {
    
    // логика вызова метода
    if ( methods[method] ) {
      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Метод с именем ' +  method + ' не существует для jQuery.downloadVideo' );
    } 
  };

})( jQuery );
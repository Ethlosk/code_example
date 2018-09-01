----------------------------------------Краткое описание настройки и использования модуля CRC---------------------------------------------------------------

Для использования данного модуля динамических списков страны/регионы/города необходим настроенный стек LAMP, библиотека jquery не ниже 1.7, подключенный модуль Mysqli для Mysql;

------------Структура------------------
1. crc_dump.sql - дамп базы данных страны/регионы/города;
2. jquery.crc.js - модуль на jquery для отображения представления данных (страны/регионы/города) в виде select на страницах сайта;
3. class_crc_select_json.php - класс на php для осуществляния взаимодействия с базой данных и передачи данных в формате json в модуль jquery.crc.js;
4. crc_json.php - промежуточный файл, осуществляющий взаимодействие между jquery.crc.js и class_crc_select_json.php по средствам передачи данных (POST) от фунцкций jquery.crc.js в функции class_crc_select_json.php;

------------Использование------------
На странице, в блоке кода jquery $(document).ready (function() { ...............}), привяжите фунции модуля jquery.crc.js к желаемым событиям:

/*Инициализация селекта выбора страны при выборе селекта должности*/
divMiddle.on('change', "div#divRegRez select#post_rez",  function () { $('div[name="divRegRez"]').crc('selectCountry', $('div[name="divRegRez"]'));} );

/*Инициализация селекта выбора региона при выборе селекта страны*/  
divMiddle.on('change', "div#divRegRez select#country_id", function () {$('div[name="divRegRez"]').crc('selectRegion');} );

/*Инициализация селекта выбора города при выборе селекта региона*/
divMiddle.on('change', "div#divRegRez select#region_id", function () {$('div[name="divRegRez"]').crc('selectCity');} );

/*Инициализация какого-либо действия при выборе селекта города*/
divMiddle.on('change', "div#divRegRez select#city_id", function(){...}});


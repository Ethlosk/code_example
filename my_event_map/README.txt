----------------------------------------Краткое описание ---------------------------------------------------------------

Данный модуль осуществляет работу с google maps api.

Файл class_event.php содержит класс, позволяющий записывать в БД свои события пользователям и просматривать их.
Файл myEventMap.js содержит скрипт js отражения зарегистрированных в БД событий как в виде списка, так и в виде маркеров на карте (google map). При клике на названии события в списке, открывается дополнительная информация в метке на карте. (без предствления скрипта по регистрации нового события).
Файл get_event.php непосрественно предоставляет данные через XMLHttpRequest для отображения списка событий и маркеров с доп.информацией о данных событиях на карте.
Для отображения страница/форма должна иметь соотвествующие div-ы для отображения (див с селектами выбора страны, региона, города/див для добавления списка событий/див карты), а также соотвествующие динамические селекты выбора страны/региона/города из БД.
Остальные комментарии указаны в файле - myEventMap.js



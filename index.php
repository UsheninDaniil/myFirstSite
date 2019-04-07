<?php
session_start();
// FRONT CONTROLLER

//Общие настройки
ini_set('display_errors',1);   // включает отображание ошибок
error_reporting(E_ALL);                   // какие именно ошибки показывает

//Подключение файлов системы
define('ROOT', __DIR__);          // объявляет строковую константу ROOT и ложит в нее
require_once (ROOT.'/components/Router.php');



//Установка соединения с БД

// Вызов Router
$router = new Router();   // переменная $router стала объектом класса 'Router'
$router->run();           // через стрлочку из объекта можно вызывать методы объекта или свойства(переменные)




?>






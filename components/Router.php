<?php

class Router
{
    private $routes;    // private - область видимости свойства/метода или константы

    public function __construct()      //Подключает файл routes.php
    {
        $routesPath= ROOT.'/config/routes.php';
        $this->routes = include($routesPath);
    }

    /* Получает адресную строку из суперглобального массива $_SERVER,
       который создется веб сервером, REQUEST_URI - индекс массива */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])){
            return $_SERVER['REQUEST_URI'];   // trim - удаляет пробелы
        } else {
            return '/';
        }
    }

    public function run()
    {
        // echo'Class Router, method run';

        // Записываем в $uri адресную строку
        $uri = $this->getURI();

        // Проверяем есть ли этот адрес в массиве с роутами

        $uri_error=true;

        // Перебираем через цикл асоциативный массив с роутами и помещаем его первый элемент в $uriPattern => $path
        foreach ($this->routes as $uriPattern => $path) {

            // Дабавляю в шаблон знак $, который в регулярном выражении означает конец строки
            $uriPattern='^'.$uriPattern.'$';

            // Сравниваем адресную строку из массива ($uriPattern) и нашу адресную строку($uri)
            if (preg_match("~$uriPattern~",$uri)){

                $uri_error=false;

             // Определяем какой контроллер и action обрабатывают запрос

                // Разбиваем строку по разделителю, то есть получается массив значений без '/'
                $segments = explode('/',$path);

                // Составляем имя контроллера, берем первое значение из массива и добавляем 'Controller'
                $controllerName = array_shift($segments). 'Controller';
                $controllerName = ucfirst ($controllerName);        // преобразовываем первый символ строки в верхний регистр

                // Составляем имя action, берем первое значение из массива с большой буквы и добавляем перед ним 'action'
                $actionName = 'action'. ucfirst(array_shift($segments));

                // Подключаем файл класса контроллера

                $controllerFile = ROOT.'/controllers/'.$controllerName.'.php';

                // если файл с таким именем существует, подключаем файл
                if (file_exists($controllerFile)){
                    include_once($controllerFile);
                    // Создаем объект, вызываем метод (action)
                    $controllerObject = new $controllerName;
                    $controllerObject->$actionName();
                    break;
                }
            }

        }
        if($uri_error==true){
            require_once (ROOT.'/views/error/error.php');
        }


    }
}


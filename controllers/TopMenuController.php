<?php

require_once ('/models/DatabaseConnect.php');

class TopMenuController
{

    public function actionAbout()
    {
        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/TopMenu/about.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionContact()
    {
        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/TopMenu/contact.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionDelivery()
    {
        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/TopMenu/delivery.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionHomepage()
    {
        require_once (ROOT. '/models/Category.php');
        require_once (ROOT. '/models/Product.php');
        require_once (ROOT. '/models/User.php');
        require_once (ROOT. '/components/Pagination.php');

        $categoryList = Category::get_category_list();

        $mysqli = DatabaseConnect::connect_to_database();

        // Переменная хранит число сообщений выводимых на станице
        $num = 1;
        // Извлекаем из URL текущую страницу
        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        $page=$segments[1];
        $page=str_replace('page=', '', $page);
        // Определяем общее число сообщений в базе данных
        $result = $mysqli->query("SELECT COUNT(*) FROM product");
        $posts = $result->fetch_row();
        $posts=$posts[0];
        // Находим общее число страниц
        $total = intval(($posts - 1) / $num) + 1;
        // Определяем начало сообщений для текущей страницы
        $page = intval($page);
        // Если значение $page меньше единицы или отрицательно
        // переходим на первую страницу
        // А если слишком большое, то переходим на последнюю
        if(empty($page) or $page < 0) $page = 1;
        if($page > $total) $page = $total;
        // Вычисляем начиная к какого номера
        // следует выводить сообщения
        $start = $page * $num - $num;
        // Выбираем $num сообщений начиная с номера $start
        $result = $mysqli->query("SELECT * FROM product LIMIT $start, $num");
        // В цикле переносим результаты запроса в массив $postrow

        $i = 0;
        $productList = array();

        while ($i < $result->num_rows) {

            $row = $result->fetch_array();
            $productList[$i]['id'] = $row['id'];
            $productList[$i]['name'] = $row['name'];
            $productList[$i]['price'] = $row['price'];
            $productList[$i]['status'] = $row['status'];

            $i++;
        }

        $pagination = new Pagination();

        $total_count = $total;
        $currentPageNumber = $page;
        $limit = $num;

        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/TopMenu/homepage.php');
        require_once ('/views/layouts/footer.php');
    }
}
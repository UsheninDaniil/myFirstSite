<?php

require_once('/models/DatabaseConnect.php');

class TopMenuController
{

    public function actionAbout()
    {
        require_once('/views/layouts/header.php');
        require_once(ROOT . '/views/TopMenu/about.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionContact()
    {
        require_once('/views/layouts/header.php');
        require_once(ROOT . '/views/TopMenu/contact.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionDelivery()
    {
        require_once('/views/layouts/header.php');
        require_once(ROOT . '/views/TopMenu/delivery.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionHomepage()
    {
        require_once(ROOT . '/models/Category.php');
        require_once(ROOT . '/models/Product.php');
        require_once(ROOT . '/models/User.php');
        require_once(ROOT . '/components/Pagination.php');

        $categoryList = Category::get_category_list();

        $pagination = new Pagination();

        $amount_of_elements_on_page = 3;

        if (isset($_GET['page'])) {
            $current_page_number = $_GET['page'];
        } else {
            $current_page_number = 1;
        }

        $get_total_elements_amount_request = "SELECT COUNT(*) FROM product";

        $result_parameters = $pagination->get_pagination_parameters($current_page_number, $amount_of_elements_on_page, $get_total_elements_amount_request);

        $total_count = $result_parameters['total_count'];
        $start = $result_parameters['start'];

        $get_elements_request = "SELECT * FROM product";

        $productList = $pagination->get_pagination_elements($start, $amount_of_elements_on_page, $get_elements_request);

        if(empty($productList)){
            $current_page_number = 0;
        }

        $limit = 4;

        require_once('/views/layouts/header.php');
        require_once(ROOT . '/views/TopMenu/homepage.php');
        require_once('/views/layouts/footer.php');
    }
}
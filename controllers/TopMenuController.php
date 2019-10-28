<?php

require_once('/models/Admin.php');
require_once('/models/AdminCategory.php');
require_once('/models/AdminParameter.php');
require_once('/models/AdminProduct.php');
require_once('/models/AdminReview.php');
require_once('/models/Category.php');
require_once('/models/DatabaseConnect.php');
require_once('/models/Parameters.php');
require_once('/models/Product.php');
require_once('/models/Search.php');
require_once('/models/User.php');
require_once('/models/Color.php');
require_once('/components/Helper.php');

class TopMenuController
{

    public function actionAbout()
    {
        require_once('/views/layouts/header.php');
        require_once('/views/TopMenu/about.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionContact()
    {
        require_once('/views/layouts/header.php');
        require_once('/views/TopMenu/contact.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionDelivery()
    {
        require_once('/views/layouts/header.php');
        require_once('/views/TopMenu/delivery.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionHomepage()
    {
        require_once(ROOT . '/components/Pagination.php');

        $categoryList = Category::get_category_list();

        $pagination = new Pagination(10);

        $get_elements_request = "SELECT * FROM product";
        $get_total_elements_amount_request = "SELECT COUNT(*) FROM product";

        $productList = $pagination->get_pagination_elements($get_elements_request, $get_total_elements_amount_request);

        require_once('/views/layouts/header.php');
        require_once('/views/TopMenu/homepage.php');
        require_once('/views/layouts/footer.php');
    }
}
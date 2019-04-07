<?php

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
        $categoryList = Category::get_category_list();
        $productList = Product::get_product_list();

        if(isset($_POST["add_to_cart"])) {

            if (User::action_check_authorization()==true) {

                $product_id = $_POST["add_to_cart_product_id"];

                $cart_product_list = [];

                if (isset($_SESSION['cart_product_list'])) {
                    $cartData = unserialize($_SESSION['cart_product_list']);
                } else {
                    $cartData = [];
                }

                if (isset($cartData[$product_id])) {
                    $cartData[$product_id]++;
                } else {
                    $cartData[$product_id] = 1;
                }

                $_SESSION['cart_product_list'] = serialize($cartData);
            }
            else {
                header("Location: /login");
            }
        }







        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/TopMenu/homepage.php');
        require_once ('/views/layouts/footer.php');
    }
}
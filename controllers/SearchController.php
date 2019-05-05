<?php
/**
 * Created by PhpStorm.
 * User: Даня
 * Date: 02.05.2019
 * Time: 22:58
 */

class SearchController
{
    public function actionViewSearchResult(){

        if(isset($_GET['text'])){
            $search_query = $_GET['text'];
        }

        echo $search_query;

        require_once (ROOT. '/models/Category.php');
        require_once (ROOT. '/models/Product.php');
        require_once (ROOT. '/models/User.php');
        require_once (ROOT. '/models/Search.php');
        $categoryList = Category::get_category_list();

//        $productList = Product::get_product_list();

        $productList = Search::get_product_list_for_search($search_query);

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



        require_once(ROOT. '/views/layouts/header.php');
        require_once(ROOT. '/views/search/search_view.php');
        require_once(ROOT. '/views/layouts/footer.php');

    }
}
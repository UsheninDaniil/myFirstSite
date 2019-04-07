<?php



Class CategoryController{

    public function actionView(){

        require_once (ROOT. '/models/Category.php');
        require_once (ROOT. '/models/Product.php');
        require_once (ROOT. '/models/User.php');
        $categoryList = Category::get_category_list();


        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        $category_id=$segments[2];
        $productList = Product::get_product_list_by_category_id($category_id);

        $category_name = Category::get_category_name_by_id($category_id);

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
        require_once (ROOT.'/views/category/view.php');
        require_once ('/views/layouts/footer.php');
    }


}


?>
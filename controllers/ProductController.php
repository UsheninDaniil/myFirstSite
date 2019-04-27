<?php



Class ProductController
{


    public function actionView(){

        require_once(ROOT. '/models/Product.php');
        require_once (ROOT. '/models/Category.php');

        $product_id=Product::get_product_id();
        $product_info=Product::get_product_by_id($product_id);
        $product_parameters_info=Product::get_product_parameters_by_id($product_id);

        $categoryList = Category::get_category_list();

        $category_id = Category::get_category_id_by_product_id($product_id);

        $productList = Product::get_product_list_by_category_id($category_id);

        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/product/view.php');
        require_once ('/views/layouts/footer.php');

    }


    public function actionAdd(){

        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        $product_id=$segments[3];

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

        if (isset($_SESSION['cart_product_list'])) {
            $cartData = unserialize($_SESSION['cart_product_list']);
            $cart_product_amount=0;
            foreach ($cartData as $id => $amount){
                $cart_product_amount = $cart_product_amount + $amount;
            }
        }

        $_SESSION['cart_product_amount']=$cart_product_amount;
        echo "Корзина ($cart_product_amount)";
    }

    public function actionAdd_compare(){
        require_once ('/models/Category.php');

        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        $product_id=$segments[3];

        $category_id = Category::get_category_id_by_product_id($product_id);

        $cart_product_list = [];

        if (isset($_SESSION['compare_product_list'])) {
            $compareData = unserialize($_SESSION['compare_product_list']);
        } else {
            $compareData = [];
        }

            if (isset($compareData[$category_id])){

                if (in_array($product_id,$compareData[$category_id])){
                }
                else{
                    array_push($compareData[$category_id], $product_id);
                }
            }

            else{
                $compareData[$category_id] = array();
                array_push($compareData[$category_id], $product_id);
            }

        $_SESSION['compare_product_list'] = serialize($compareData);

        if (isset($_SESSION['compare_product_list'])) {
            $compareData = unserialize($_SESSION['compare_product_list']);
            $compare_product_amount=count($compareData, COUNT_RECURSIVE)-count($compareData);
        }

        $_SESSION['compare_product_amount']=$compare_product_amount;
        echo "Сравнение ($compare_product_amount)";
    }

}



?>
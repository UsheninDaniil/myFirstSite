<?php

require_once ('/models/DatabaseConnect.php');

Class ProductController
{

    public function actionView(){

        require_once(ROOT. '/models/Product.php');
        require_once (ROOT. '/models/Category.php');
        require_once (ROOT. '/models/User.php');

        $product_id=Product::get_product_id();

        $product_info=Product::get_product_by_id($product_id);
        $product_parameters_info=Product::get_product_parameters_by_id($product_id);

        $categoryList = Category::get_category_list();

        $category_id = Category::get_category_id_by_product_id($product_id);

        $productList = Product::get_product_list_by_category_id($category_id, 1, 10);

        $product_review_list = Product::get_product_review_list_by_product_id($product_id);

        if(isset($_SESSION["user_id"])) {
            $user_id = $_SESSION["user_id"];
            $current_user_review = Product::get_product_review_by_product_id_and_user_id($product_id, $user_id);

            if(Product::check_review_exist($product_id, $user_id) === true){
                $review_exists = true;
            } else{
                $review_exists = false;
            }

            echo "current_user_review <br/>";
            print_r($current_user_review);
        }

        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/product/view.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionReviewCrud(){
        require_once(ROOT. '/models/Product.php');

        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        $action = $segments[3];

        if($action === "create"){

            require_once(ROOT. '/models/Product.php');

            if(isset($_POST)) {
                $text_review = htmlspecialchars ($_POST["text_review"]);
                $rating = htmlspecialchars ($_POST["rating"]);
                $product_id = htmlspecialchars ($_POST["product_id"]);

                if(isset($_SESSION["user_id"])){

                    $user_id = $_SESSION["user_id"];
                    if(Product::check_review_exist($product_id, $user_id) === false){
                        $current_date = date("d.m.y");
                        $current_time = date("H:i");

                        $review_information['product_id'] = $product_id;
                        $review_information['user_id'] = $user_id;
                        $review_information['text_review'] = $text_review;
                        $review_information['rating'] = $rating;
                        $review_information['date'] = $current_date;
                        $review_information['time'] = $current_time;

                        Product::save_review($review_information);
                    }
                }
            }
        }

//        $this ->actionAdd();

        if($action === "edit"){

            $user_id = $_SESSION["user_id"];

            $product_id = $_POST['product_id'];

            $current_user_review = Product::get_product_review_by_product_id_and_user_id($product_id, $user_id);
            $edit_subject = $segments[4];

            if($edit_subject === 'review_stars'){
                echo '
                <div class="rating_star_container" data-product-id="'.$product_id.'" >

                    <div data-rating="1" class="rating_star">
                        <i class="fa-star far"></i>
                        <i class="fa-star fas"></i>
                    </div>

                    <div data-rating="2" class="rating_star">
                        <i class="fa-star far"></i>
                        <i class="fa-star fas"></i>
                    </div>

                    <div data-rating="3" class="rating_star">
                        <i class="fa-star far"></i>
                        <i class="fa-star fas"></i>
                    </div>

                    <div data-rating="4" class="rating_star">
                        <i class="fa-star far"></i>
                        <i class="fa-star fas"></i>
                    </div>

                    <div data-rating="5" class="rating_star">
                        <i class="fa-star far"></i>
                        <i class="fa-star fas"></i>
                    </div>

                </div>
                ';
            }

            if($edit_subject === 'review_text'){
                $review = $current_user_review['review'];
                echo "<form><textarea id='text_review_update'>$review</textarea></form>";
            }

        }

        if($action === "update"){

            require_once(ROOT. '/models/Product.php');

            if(isset($_POST)) {

                $text_review = htmlspecialchars ($_POST["text_review"]);
                $rating = htmlspecialchars ($_POST["rating"]);
                $product_id = htmlspecialchars ($_POST["product_id"]);

                if(isset($_SESSION["user_id"])){

                    $user_id = $_SESSION["user_id"];
                    if(Product::check_review_exist($product_id, $user_id) === true){

                        $current_date = date("d.m.y");
                        $current_time = date("H:i");

                        $review_information['product_id'] = $product_id;
                        $review_information['user_id'] = $user_id;
                        $review_information['text_review'] = $text_review;
                        $review_information['rating'] = $rating;
                        $review_information['date'] = $current_date;
                        $review_information['time'] = $current_time;

                        Product::update_review($review_information);
                    }
                }
            }

        }

        if($action === "delete"){

            require_once(ROOT. '/models/Product.php');

            if(isset($_POST)){

                $product_id = $_POST['product_id'];

                if(isset($_SESSION["user_id"])){

                    $user_id = $_SESSION["user_id"];
                    if(Product::check_review_exist($product_id, $user_id) === true) {
                        Product::delete_review($product_id, $user_id);
                    }
                }
            }
        }

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
        echo "<i class=\"fas fa-shopping-cart\"></i> Корзина ($cart_product_amount)";
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
        echo "<i class=\"fas fa-balance-scale\"></i> Сравнение ($compare_product_amount)";
    }

}



?>
<?php
include_once ('/models/DatabaseConnect.php');

Class CategoryController{

    public function actionView(){

        require_once (ROOT. '/models/Category.php');
        require_once (ROOT. '/models/Product.php');
        require_once (ROOT. '/models/User.php');
        require_once (ROOT. '/models/Parameters.php');
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

        if(!empty ($_GET)){

            require_once (ROOT. '/models/Category.php');

            $uri=$_SERVER['REQUEST_URI'];
            $segments = explode('/',$uri);
            $category_id=$segments[2];

            $mysqli = DatabaseConnect::connect_to_database();

            $united_request = "";

            $total_amount = count($_POST);
            $counter = 0;

            foreach ($_GET as $parameter_id => $parameter_values_array){

                $category_id = $mysqli->real_escape_string($category_id);
                $parameter_id = $mysqli->real_escape_string($parameter_id);
//              $parameter_values_array = $mysqli->real_escape_string($parameter_values_array);

                $counter = $counter+1;

                $request_first_part = "
                SELECT `product_id` FROM `parameter_values` 
                INNER JOIN `category_parameters` ON parameter_values.parameter_id = category_parameters.parameter_id 
                INNER JOIN product ON product.id = parameter_values.product_id 
                WHERE product.category_id = '$category_id'
                ";

                $request_second_part = "
                AND parameter_values.product_id IN (
                SELECT `product_id` FROM `parameter_values`
                WHERE parameter_values.parameter_id = '$parameter_id' 
                AND parameter_values.value IN ('".implode("','",$parameter_values_array)."') )
                ";

                $request_third_part = "
                GROUP BY product_id
                ";

                if(strlen ($united_request)< 1){
                    $united_request = $united_request.$request_first_part.$request_second_part;
                }
                else{
                    $united_request = $united_request.$request_second_part;
                }

                if($counter == $total_amount){
                    $united_request = $united_request.$request_third_part;
                }

            }

            $result = $mysqli->query ($united_request);

            if ($result->num_rows >0){

                $i = 0;
                $product_list_after_filter = [];

                while ($i < $result->num_rows){
                    $row = $result->fetch_array();
                    array_push($product_list_after_filter, $row['product_id']);
                    $i++;
                }

                DatabaseConnect::disconnect_database($mysqli);

                $product_list_after_filter_unique= array_unique($product_list_after_filter);

                $productList = Category::get_main_product_information_after_category_filter_by_product_list($product_list_after_filter_unique);

            }
            else{
                $productList = [];
            }
        }

        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/category/category_view.php');
        require_once ('/views/layouts/footer.php');
    }

}


?>
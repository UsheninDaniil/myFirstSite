<?php
require_once ('/models/DatabaseConnect.php');

Class CategoryController{

    public function actionView(){

        require_once (ROOT. '/models/Category.php');
        require_once (ROOT. '/models/Product.php');
        require_once (ROOT. '/models/User.php');
        require_once (ROOT. '/models/Parameters.php');
        require_once (ROOT. '/components/Pagination.php');

        $categoryList = Category::get_category_list();

        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        $category_id=$segments[2];

        $category_name = Category::get_category_name_by_id($category_id);

        $pagination = new Pagination();

        $index_of_page_in_url = 3;
        $amount_of_elements_on_page = 1;
        $get_total_elements_amount_request = "SELECT COUNT(*) FROM product WHERE  category_id = '$category_id'";

        $result_parameters = $pagination->get_pagination_parameters($index_of_page_in_url, $amount_of_elements_on_page, $get_total_elements_amount_request);

        $current_page_number = $result_parameters['current_page_number'];
        $total_count = $result_parameters['total_count'] ;
        $start = $result_parameters['start'] ;

        $get_elements_request = "SELECT * FROM product WHERE  category_id = '$category_id' ORDER BY id, name ASC";

        $productList = $pagination->get_pagination_elements($start, $amount_of_elements_on_page, $get_elements_request);

        $limit = 4;

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

            $total_amount = count($_GET);
            $counter = 0;

            foreach ($_GET as $parameter_id => $parameter_values_array){

                $category_id = $mysqli->real_escape_string($category_id);
                $parameter_id = $mysqli->real_escape_string($parameter_id);

                $counter = $counter+1;

                $request_first_part = "
                SELECT `product_id` FROM `parameter_values` 
                INNER JOIN `category_parameters` ON parameter_values.parameter_id = category_parameters.parameter_id 
                INNER JOIN product ON product.id = parameter_values.product_id 
                WHERE product.category_id = '$category_id'
                ";

                $request_second_part = "
                AND parameter_values.product_id IN (SELECT `product_id` FROM `parameter_values`
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

            $index_of_page_in_url = 3;
            $amount_of_elements_on_page = 1;
            $get_total_elements_amount_request = 'SELECT COUNT(*) FROM('.$united_request.') tmp';

            $result_parameters = $pagination->get_pagination_parameters($index_of_page_in_url, $amount_of_elements_on_page, $get_total_elements_amount_request);

            $current_page_number = $result_parameters['current_page_number'];
            $total_count = $result_parameters['total_count'] ;
            $start = $result_parameters['start'] ;

            $get_elements_request = $united_request;

            $result = $pagination->get_pagination_elements($start, $amount_of_elements_on_page, $get_elements_request);

            $product_id_list_after_filter = array();

            foreach ($result as $row){
                $product_id = $row['product_id'];
                array_push($product_id_list_after_filter,$product_id);
            }

            $productList=array();

            if(!empty($product_id_list_after_filter)){
                $productList = Category::get_main_product_information_after_category_filter_by_product_list($product_id_list_after_filter);
            }

            $limit = 4;
        }

        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/category/category_view.php');
        require_once ('/views/layouts/footer.php');
    }
}


?>
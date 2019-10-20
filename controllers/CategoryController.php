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

Class CategoryController
{

    public function actionView()
    {
        require_once(ROOT . '/components/Pagination.php');

        $categoryList = Category::get_category_list();

        $uri = $_SERVER['REQUEST_URI'];
        $uri_without_get_parameter = explode('?', $uri)[0];
        $category_id = explode('/', $uri_without_get_parameter)[2];

        $category_name = Category::get_category_name_by_id($category_id);

        $pagination = new Pagination();

        if (isset($_GET['page'])) {
            $current_page_number = $_GET['page'];
        } else {
            $current_page_number = 1;
        }

        $amount_of_elements_on_page = 10;
        $get_total_elements_amount_request = "SELECT COUNT(*) FROM product WHERE  category_id = '$category_id'";

        $result_parameters = $pagination->get_pagination_parameters($current_page_number, $amount_of_elements_on_page, $get_total_elements_amount_request);

        $total_count = $result_parameters['total_count'];
        $start = $result_parameters['start'];

        $get_elements_request = "SELECT * FROM product WHERE  category_id = '$category_id' ORDER BY id, name ASC";

        $productList = $pagination->get_pagination_elements($start, $amount_of_elements_on_page, $get_elements_request);

        $limit = 4;

        if (isset($_POST["add_to_cart"])) {

            if (User::action_check_authorization() == true) {

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
            } else {
                header("Location: /login");
            }
        }

        if (!empty($_GET)) {
            $get_parameters_array = $_GET;
            if (isset($get_parameters_array['page'])) {
                unset($get_parameters_array['page']);
            }
            $get_parameters_without_page = $get_parameters_array;
        }


        if (!empty ($get_parameters_without_page)) {

            $mysqli = DatabaseConnect::connect_to_database();

            $united_request = "";

            $total_amount = count($get_parameters_without_page);
            $counter = 0;

            foreach ($get_parameters_without_page as $parameter_id => $parameter_values_id_array) {

                $category_id = htmlspecialchars($category_id);

                $parameter_id = $mysqli->real_escape_string($parameter_id);

                $counter = $counter + 1;

                $request_first_part = "
                SELECT product_parameter_values.product_id FROM product_parameter_values
                INNER JOIN parameter_values ON parameter_values.id = product_parameter_values.value_id
                INNER JOIN category_parameters ON product_parameter_values.parameter_id = category_parameters.parameter_id 
                INNER JOIN product ON product.id = product_parameter_values.product_id 
                WHERE product.category_id = '$category_id'
                ";

                $request_second_part = "
                AND product_parameter_values.product_id IN (
                SELECT product_id FROM product_parameter_values
                INNER JOIN parameter_values ON parameter_values.id = product_parameter_values.value_id
                WHERE product_parameter_values.parameter_id = '$parameter_id' 
                AND parameter_values.id IN ('" . implode("','", $parameter_values_id_array) . "') )
                ";

                $request_third_part = "
                GROUP BY product_parameter_values.product_id
                ";

                if (strlen($united_request) < 1) {
                    $united_request = $united_request . $request_first_part . $request_second_part;
                } else {
                    $united_request = $united_request . $request_second_part;
                }

                if ($counter == $total_amount) {
                    $united_request = $united_request . $request_third_part;
                }

            }

            if (isset($_GET['page'])) {
                $current_page_number = $_GET['page'];
            } else {
                $current_page_number = 1;
            }

            $amount_of_elements_on_page = 10;
            $get_total_elements_amount_request = "SELECT COUNT(*) FROM($united_request) tmp";

            $result_parameters = $pagination->get_pagination_parameters($current_page_number, $amount_of_elements_on_page, $get_total_elements_amount_request);

            $total_count = $result_parameters['total_count'];
            $start = $result_parameters['start'];

            $get_elements_request = $united_request;

            $result = $pagination->get_pagination_elements($start, $amount_of_elements_on_page, $get_elements_request);

            $product_id_list_after_filter = array();

            foreach ($result as $row) {
                $product_id = $row['product_id'];
                array_push($product_id_list_after_filter, $product_id);
            }

            $productList = array();

            if (!empty($product_id_list_after_filter)) {
                $productList = Category::get_main_product_information_after_category_filter_by_product_list($product_id_list_after_filter);
            }

            $limit = 4;
        }

        if (empty($productList)) {
            $current_page_number = 0;
        }

        require_once('/views/layouts/header.php');
        require_once('/views/category/category_view.php');
        require_once('/views/layouts/footer.php');
    }


    public function actionDeleteFilterTag(){

        $get_parameters = $_POST['get_parameters'];

        parse_str($get_parameters, $new_get_parameters);

        $tag_name = $_POST['tag_name'];

        $segments = explode(' = ', $tag_name);
        $parameter_name = $segments[0];
        $parameter_value = $segments[1];

        $parameter_id = Parameters::get_parameter_id_by_parameter_name($parameter_name);
        $value_id = Parameters::get_value_id_by_value_and_parameter_id($parameter_value, $parameter_id);



        foreach ($new_get_parameters[$parameter_id] as $key => $id){

            if($id === $value_id){
                unset($new_get_parameters[$parameter_id][$key]);
            }
        }

//        echo "Вы нажали удалить тег <b>parameter_name</b> = '$parameter_name', <b>parameter_value</b> = '$parameter_value'";
//        echo "<br/>new_get_parameters <br/>";
//        print_r($new_get_parameters);

        $new_get_parameters = http_build_query($new_get_parameters);
        echo $new_get_parameters;

    }

}


//Работает
//
//SELECT product_parameter_values.product_id FROM product_parameter_values INNER JOIN parameter_values ON parameter_values.id = product_parameter_values.value_id INNER JOIN category_parameters ON product_parameter_values.parameter_id = category_parameters.parameter_id INNER JOIN product ON product.id = product_parameter_values.product_id
//
//WHERE product.category_id = '2'
//
//AND product_parameter_values.product_id IN (
//    SELECT product_id FROM product_parameter_values
//    INNER JOIN parameter_values ON parameter_values.id = product_parameter_values.value_id
//    WHERE product_parameter_values.parameter_id = '22' AND parameter_values.value IN ('4')
//)
//
//AND product_parameter_values.product_id IN (
//    SELECT product_id FROM product_parameter_values
//    INNER JOIN parameter_values ON parameter_values.id = product_parameter_values.value_id
//    WHERE product_parameter_values.parameter_id = '24' AND parameter_values.value IN ('2.7')
//)
//
//GROUP BY product_parameter_values.product_id
//
//
//
//
//Не работает
//
//SELECT product_parameter_values.product_id FROM product_parameter_values INNER JOIN parameter_values ON parameter_values.id = product_parameter_values.value_id INNER JOIN category_parameters ON product_parameter_values.parameter_id = category_parameters.parameter_id INNER JOIN product ON product.id = product_parameter_values.product_id
//
//WHERE product.category_id = '2'
//
//AND product_parameter_values.product_id IN (
//    SELECT product_id FROM product_parameter_values
//    WHERE product_parameter_values.parameter_id = '22' AND parameter_values.value IN ('4')
//)
//
//AND product_parameter_values.product_id IN (
//    SELECT product_id FROM product_parameter_values
//    WHERE product_parameter_values.parameter_id = '24' AND parameter_values.value IN ('2.7')
//)
//
//GROUP BY product_parameter_values.product_id


?>


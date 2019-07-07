<?php
require_once(ROOT . '/models/Admin.php');
require_once(ROOT . '/models/AdminProduct.php');
require_once('/models/DatabaseConnect.php');

class AdminProductController
{
    public function actionEditProductsView()
    {
        include_once('/models/Product.php');
        include_once('/models/Category.php');
        require_once(ROOT . '/components/Pagination.php');

        $category_list = Category::get_category_list();

        if (!empty($_GET)) {
            $get_parameters_array = $_GET;
            if (isset($get_parameters_array['page'])) {
                unset($get_parameters_array['page']);
            }
            $get_parameters_without_page = $get_parameters_array;
        }

        $get_products_without_filter = false;

        if (!empty($get_parameters_without_page)) {

            $united_request = '';

            foreach ($get_parameters_without_page as $parameter_name => $parameter_content) {

                if(gettype($parameter_content)== "array"){

                    $parameter_values_array= $parameter_content;

                    if (!empty($parameter_values_array)) {
                        $request_first_part = "SELECT * FROM product WHERE ";
                        $request_second_part = "$parameter_name  IN (" . implode(',', $parameter_values_array) . ")";

                        if (strlen($united_request) < 1) {
                            $united_request = $united_request . $request_first_part . $request_second_part;
                        } else {
                            $united_request = $united_request . ' AND ' . $request_second_part;
                        }
                    }
                } else{
                    $parameter_value = $parameter_content;
                    if(!empty($parameter_value)) {
                        $request_first_part = "SELECT * FROM product WHERE ";

                        if($parameter_name === "id"){
                            $request_second_part = "$parameter_name = '$parameter_value'";
                        }

                        if($parameter_name === "name"){
                            $request_second_part = "$parameter_name LIKE '%$parameter_value%'";
                        }

                        if ((strlen($united_request) < 1) && (!empty($request_second_part))) {
                            $united_request = $united_request . $request_first_part . $request_second_part;
                        } else {
                            $united_request = $united_request . ' AND ' . $request_second_part;
                        }
                    }
                }



            }

            if (!empty($united_request)) {

                $pagination = new Pagination();

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

                $get_elements_request = "$united_request";

                $productList = $pagination->get_pagination_elements($start, $amount_of_elements_on_page, $get_elements_request);

                $limit = 4;

            } else {
                $get_products_without_filter = true;
            }

        } else {
            $get_products_without_filter = true;
        }

        if ($get_products_without_filter == true) {

            $pagination = new Pagination();

            $amount_of_elements_on_page = 10;

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

            $limit = 4;
        }

        if(empty($productList)){
            $current_page_number = 0;
        }

        if(!empty($united_request)){
            echo "united_request <br/>".$united_request;
        }

        require_once('/views/layouts/header.php');
        require_once(ROOT . '/views/admin/product/edit_products_view.php');
        require_once(ROOT . '/views/admin/product/products_table.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionAddProduct()
    {
        $user_role = "user";
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $user_role = Admin::check_user_role($user_id);
        }

        if ($user_role == "admin") {

            if (isset($_POST["save_new_product"])) {

                $product_information['product_name'] = $_POST['product_name'];
                $product_information['product_price'] = $_POST['product_price'];
                $product_information['product_availability'] = $_POST['product_availability'];
                $product_information['product_category'] = $_POST['product_category_id'];

                $product_id = AdminProduct::add_new_product($product_information);

                $photo_number = 1;

                $tmp_name_array_after_checking =[];

                if(!empty($_POST)){
                    $image_names_to_upload = $_POST['image_names']; //в виде строки
                    $image_names_to_upload = explode(", ", $image_names_to_upload);
                }

                if(!empty($_FILES["images"])){

                    $tmp_name_array = $_FILES["images"]["tmp_name"];
                    $i = 0;

                    foreach ($image_names_to_upload as $image_name_to_upload){

                        if(in_array($image_name_to_upload, $_FILES["images"]["name"])){
                            $key = array_search($image_name_to_upload, $_FILES["images"]["name"]);
                            $name = $_FILES["images"]["name"][$key];
                            $tmp_name = $_FILES["images"]["tmp_name"][$key];
                            array_push($tmp_name_array_after_checking, $tmp_name);
                            $i = $i + 1;
                        }
                    }
                }

                foreach ($tmp_name_array_after_checking as $tmp_name) {

                    if (is_uploaded_file($tmp_name)) {
                        // Если загружалось, переместим его в нужную папке, дадим новое имя $photo
                        move_uploaded_file($tmp_name, $_SERVER['DOCUMENT_ROOT'] . "/images/large_images/id_{$product_id}_photo_{$photo_number}.jpg");

                        $photo_name = "id_{$product_id}_photo_{$photo_number}.jpg";

                        $mysqli = DatabaseConnect::connect_to_database();

                        $mysqli->query("INSERT INTO `myFirstSite`.`product_photos` (`product_id`,`photo_number`,`photo_name`) VALUES ('$product_id', '$photo_number', '$photo_name')");

                        DatabaseConnect::disconnect_database($mysqli);

                        $filename = $_SERVER['DOCUMENT_ROOT'] . "/images/large_images/id_{$product_id}_photo_{$photo_number}.jpg";

                        // тип содержимого
//                    header('Content-Type: image/jpeg');

                        // получение новых размеров
                        list($width_orig, $height_orig) = getimagesize($filename);

                        $ratio_orig = $width_orig / $height_orig;

                        // preview_image - отображается на главной странице и странице категории
                        $max_height = 200;
                        $max_width = $max_height * 1.5;
                        $new_path = $_SERVER['DOCUMENT_ROOT'] . "/images/preview_images/id_{$product_id}_photo_{$photo_number}.jpg";
                        AdminProduct::resize_and_save_product_photo($filename, $height_orig, $width_orig, $max_height, $max_width, $ratio_orig, $new_path);

                        // middle_image - отображается на странице товара
                        $max_height = 350;
                        $max_width = $max_height * 1.5;
                        $new_path = $_SERVER['DOCUMENT_ROOT'] . "/images/middle_images/id_{$product_id}_photo_{$photo_number}.jpg";
                        AdminProduct::resize_and_save_product_photo($filename, $height_orig, $width_orig, $max_height, $max_width, $ratio_orig, $new_path);

                        // small_image - отображается в слайдере на странице товара
                        $max_height = 50;
                        $max_width = $max_height * 1.5;
                        $new_path = $_SERVER['DOCUMENT_ROOT'] . "/images/small_images/id_{$product_id}_photo_{$photo_number}.jpg";
                        AdminProduct::resize_and_save_product_photo($filename, $height_orig, $width_orig, $max_height, $max_width, $ratio_orig, $new_path);

                    }
                    $photo_number = $photo_number + 1;
                }

                if (isset($_POST["category_parameters"])) {
                    foreach ($_POST["category_parameters"] as $parameter_id => $parameter_value) {
                        if (!empty ($parameter_value)) {
                            AdminProduct::save_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id, $parameter_value);
                        }
                    }
                }
            }

            require_once('/views/layouts/header.php');
            require_once(ROOT . '/views/admin/product/add_product.php');
            require_once('/views/layouts/footer.php');
        } else {
            header("Location: /no_permission");
        }
    }

    public function actionDeleteProduct()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $segments = explode('/', $uri);
        $product_id = $segments[3];

        require_once('/models/Product.php');
        require_once('/models/AdminProduct.php');


        if (isset($_POST["delete_product"])) {

            AdminProduct::delete_product($product_id);

            header("Location: /admin/edit_products");
        }

        require_once('/views/layouts/header.php');
        require_once(ROOT . '/views/admin/product/delete_product.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionEditProduct()
    {
        require_once(ROOT . '/models/Product.php');
        require_once(ROOT . '/models/Category.php');
        require_once(ROOT . '/models/Parameters.php');
        require_once(ROOT . '/models/AdminParameter.php');

        $uri = $_SERVER['REQUEST_URI'];
        $segments = explode('/', $uri);
        $product_id = $segments[3];

        $product_information = Product::get_product_by_id($product_id);
        $category_id = Category::get_category_id_by_product_id($product_id);
        $category_parameters_list = Parameters::get_category_parameters_list($category_id);

//        echo "Массив парамтеров категории <b>до</b> проверки на существование:<br />";
//        print_r($category_parameters_list);

        $existing_parameters_information = Parameters::get_all_parameters();
        $existing_parameters_list = [];

        foreach ($existing_parameters_information as $parameter_item) {
            $id = $parameter_item['id'];
            array_push($existing_parameters_list, $id);
        }

        $category_parameters_list_after_checking = array_intersect($category_parameters_list, $existing_parameters_list);

//        echo "<br /><br />Массив парамтеров категории <b>после</b> проверки на существование:<br />";
//        print_r($category_parameters_list_after_checking);

        $specified_parameters_list = AdminParameter::get_specified_parameters_by_product_id($product_id);

//        echo "<br /><br />Список заданных параметров для выбранного товара:<br />";
//        print_r($specified_parameters_list);

        $additional_parameters_list = array_intersect($existing_parameters_list, $specified_parameters_list);

//        echo "<br /><br />Список заданных параметров после проверки на существование:<br />";
//        print_r($additional_parameters_list);

        $additional_parameters_list_after_checking = array_diff($additional_parameters_list, $category_parameters_list);

//        echo "<br /><br />Дополнительные параметры <b>=</b> все заданные параметры товара <b>-</b> несуществующие параметры <b>-</b> параметры категории:<br />";
//        print_r($additional_parameters_list_after_checking);

        $not_specified_parameters = array_diff($existing_parameters_list, $additional_parameters_list_after_checking, $category_parameters_list_after_checking);

//        echo "<br /><br />Не заданные параметры, которые можно добавить:<br />";
//        print_r($not_specified_parameters);

//        echo "<br /><br />Содержимое <b>Post</b><br />";
//        print_r($_POST);

        if (isset($_POST["update_product_information"])) {

            if (isset($_POST['product_name'], $_POST['product_price'], $_POST['availability'])) {
                $product_name = $_POST['product_name'];
                $product_price = $_POST['product_price'];
                $availability = $_POST['availability'];
                AdminProduct::update_main_product_information_by_product_id($product_id, $product_name, $product_price, $availability);
            }

            if (isset($_POST['dynamic_parameters'])) {

                foreach ($_POST['dynamic_parameters'] as $parameter_id => $parameter_value) {
                    AdminProduct::update_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id, $parameter_value);
                }
            }

            if (isset($_POST['new_dynamic_parameters'])) {

                foreach ($_POST['new_dynamic_parameters'] as $parameter_id => $parameter_value) {
                    if (!empty ($parameter_value)) {
                        AdminProduct::save_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id, $parameter_value);
                    }
                }
            }

            header('Location: /admin/edit_products');
        }

        require_once('/views/layouts/header.php');
        require_once(ROOT . '/views/admin/product/edit_selected_product.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionLoadSelectedParametersList()
    {

        include_once('/models/AdminParameter.php');

        echo "<br />Список добавленных параметров:<br />";

        if (isset($_POST['add_parameters_list'])) {
            $parameters_list = $_POST['add_parameters_list'];
        }

        foreach ($parameters_list as $parameter_id) {
            $parameter_information = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
            $parameter_russian_name = $parameter_information['russian_name'];
            $parameter_name = $parameter_information['name'];
            $parameter_id = $parameter_information['id'];
            echo "<br /><label>$parameter_russian_name</label><br />";
            echo "<input type='text' name='new_dynamic_parameters[$parameter_id]'  value=''>";
        }
    }

    public function actionDeleteAdditionalParameter()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $segments = explode('/', $uri);
        $product_id = $segments[3];
        $parameter_id = $segments[4];

        require_once('/models/AdminProduct.php');
        AdminProduct::delete_additional_parameter_from_product($product_id, $parameter_id);
    }

    public function actionLoadCategoryParameters()
    {
        include_once('/models/Parameters.php');
        include_once('/models/AdminParameter.php');

        $uri = $_SERVER['REQUEST_URI'];
        $segments = explode('/', $uri);
        $category_id = $segments[3];

        $category_parameters_list_after_checking = [];

        $category_parameters_list = Parameters::get_category_parameters_list($category_id);
        $existing_parameters = Parameters::get_all_parameters();

        $existing_parameters_list = [];
        foreach ($existing_parameters as $parameter_information) {
            $parameter_id = $parameter_information['id'];
            array_push($existing_parameters_list, "$parameter_id");
        }

//        echo "<br /><br />Список всех существующих параметров: <br />";
//        print_r($existing_parameters_list);

        $category_parameters_list_after_checking = array_intersect($category_parameters_list, $existing_parameters_list);

//        echo "<br /><br />Список параметров категории после проверки: <br />";
//        print_r($category_parameters_list_after_checking);

        foreach ($category_parameters_list_after_checking as $parameter_id) {
            $parameter_information = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
            $parameter_name = $parameter_information['name'];
            $parameter_russian_name = $parameter_information['russian_name'];
            echo "<br /><label>$parameter_russian_name</label><br />";
            echo "<input type='text' name='category_parameters[$parameter_id]'>";
        }
    }

}














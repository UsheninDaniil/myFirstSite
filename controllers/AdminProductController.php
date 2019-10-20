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
require_once('/controllers/AdminController.php');


class AdminProductController
{
    public function __construct()
    {
        Admin::check_if_administrator();
    }

    public function actionEditProductsView()
    {
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

                if (gettype($parameter_content) == "array") {

                    $parameter_values_array = $parameter_content;

                    if (!empty($parameter_values_array)) {
                        $request_first_part = "SELECT * FROM product WHERE ";
                        $request_second_part = "$parameter_name  IN (" . implode(',', $parameter_values_array) . ")";

                        if (strlen($united_request) < 1) {
                            $united_request = $united_request . $request_first_part . $request_second_part;
                        } else {
                            $united_request = $united_request . ' AND ' . $request_second_part;
                        }
                    }
                } else {
                    $parameter_value = $parameter_content;
                    if (!empty($parameter_value)) {
                        $request_first_part = "SELECT * FROM product WHERE ";

                        if ($parameter_name === "id") {
                            $request_second_part = "$parameter_name = '$parameter_value'";
                        }

                        if ($parameter_name === "name") {
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

        if (empty($productList)) {
            $current_page_number = 0;
        }

        require_once('/views/layouts/header.php');
        require_once('/views/admin/product/edit_products_view.php');
        require_once('/views/admin/product/products_table.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionAddProduct()
    {
        Admin::check_if_administrator();

        if (isset($_POST["save_new_product"])) {

            $product_information['product_name'] = $_POST['product_name'];
            $product_information['product_price'] = $_POST['product_price'];
            $product_information['product_availability'] = $_POST['product_availability'];
            $product_information['product_category'] = $_POST['product_category_id'];

            $product_id = AdminProduct::add_new_product($product_information);

            $photo_number = 1;

            $tmp_name_array_after_checking = [];

            if (!empty($_POST)) {
                $image_names_to_upload = $_POST['image_names']; //в виде строки
                $image_names_to_upload = explode(", ", $image_names_to_upload);
            }

            if (!empty($_FILES["images"])) {

                $tmp_name_array = $_FILES["images"]["tmp_name"];
                $i = 0;

                foreach ($image_names_to_upload as $image_name_to_upload) {

                    if (in_array($image_name_to_upload, $_FILES["images"]["name"])) {
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
                foreach ($_POST["category_parameters"] as $parameter_id => $value) {
                    if (!empty ($value)) {

                        $parameter_value_exist = Parameters::check_if_value_exist($parameter_id, $value);

                        if($parameter_value_exist === true){
                            $value_id = Parameters::get_value_id($value, $parameter_id);
                        } else{
                            $value_id = Parameters::create_new_value_id($value, $parameter_id);
                        }

                        Parameters::add_new_product_parameter_value($product_id, $parameter_id, $value_id);

                    }
                }
            }

            if($_POST['availability_to_choose_the_color'] === '1'){
                foreach ($_POST['color'] as $color_id => $product_amount){
                    if($product_amount > 0) {
                        Color::save_color_product_amount($product_id, $color_id, $product_amount);
                    }
                }
            } else{
                $color_id = 1;
                $product_amount = $_POST['no_color_amount'];
                if($product_amount > 0) {
                    Color::save_color_product_amount($product_id, $color_id, $product_amount);
                }
            }

        }

        // Подробная информация о всех цветах
        $color_list = Color::get_colors_list();

        // Информация о всех цветах в формате id => name
        foreach ($color_list as $color){
            $color_id_and_name_list[$color['id']]=$color['name'];
        }

        require_once('/views/layouts/header.php');
        require_once('/views/admin/product/add_product.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionDeleteProduct()
    {
        Admin::check_if_administrator();

        $uri = $_SERVER['REQUEST_URI'];
        $segments = explode('/', $uri);
        $product_id = $segments[3];

        if (isset($_POST["delete_product"])) {

            AdminProduct::delete_product($product_id);

            header("Location: /admin/edit_products");
        }

        require_once('/views/layouts/header.php');
        require_once('/views/admin/product/delete_product.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionEditProduct()
    {
        Admin::check_if_administrator();

        $uri = $_SERVER['REQUEST_URI'];
        $segments = explode('/', $uri);
        $product_id = $segments[3];

        $product_information = Product::get_product_by_id($product_id);
        $category_id = Category::get_category_id_by_product_id($product_id);
        $category_parameters_list = Parameters::get_category_parameters_list($category_id);

        $existing_parameters_information = Parameters::get_all_parameters();
        $existing_parameters_list = [];

        foreach ($existing_parameters_information as $parameter_item) {
            $id = $parameter_item['id'];
            array_push($existing_parameters_list, $id);
        }

        $category_parameters_list_after_checking = array_intersect($category_parameters_list, $existing_parameters_list);

        // Получить список всех параметров товара, чтоб потом узнать какие параметры ему можно добавить
        $specified_parameters_list = AdminParameter::get_specified_parameters_by_product_id($product_id);

        $additional_parameters_list = array_intersect($existing_parameters_list, $specified_parameters_list);

        $additional_parameters_list_after_checking = array_diff($additional_parameters_list, $category_parameters_list);

        $not_specified_parameters = array_diff($existing_parameters_list, $additional_parameters_list_after_checking, $category_parameters_list_after_checking);

        // Колличество товаров разных цветов
        $color_product_amount = Color::get_product_colors($product_id);

        //Список id всех цветов товара (нужно для того чтоб отследить какие цвета удалили)
        $original_color_id_list = array();
        foreach ($color_product_amount as $information){
            $color_id = $information['color_id'];
            array_push($original_color_id_list, "$color_id");
        }

        // Информация о всех существующих цветах
        $color_list = Color::get_colors_list();

        // Информация о всех цветах в формате id => name
        foreach ($color_list as $color){
            $color_id_and_name_list[$color['id']]=$color['name'];
        }

        // Цвета, которых у данного товара нету и которые можно добавить
        $not_selected_colors_list = Color::get_not_selected_product_colors_list($product_id);

        // Есть ли у товара возможность выбирать цвета?
        $ability_to_choose_the_color = Color::check_is_there_ability_to_choose_the_color($product_id);

        if (isset($_POST["update_product_information"])) {

            if (isset($_POST['product_name'], $_POST['product_price'], $_POST['availability'])) {
                $product_name = $_POST['product_name'];
                $product_price = $_POST['product_price'];
                $availability = $_POST['availability'];
                AdminProduct::update_main_product_information_by_product_id($product_id, $product_name, $product_price, $availability);
            }

            if (isset($_POST['dynamic_parameters'])) {

                foreach ($_POST['dynamic_parameters'] as $parameter_id => $value) {

                    $is_value_exist = Parameters::check_if_value_exist($parameter_id, $value);

                    if($is_value_exist === true){
                        $value_id = Parameters::get_value_id($value, $parameter_id);
                    } else{
                        $value_id = Parameters::create_new_value_id($value, $parameter_id);
                    }

                    AdminProduct::update_value_id_of_product_parameter($product_id, $parameter_id, $value_id);
                }
            }

            if (isset($_POST['new_dynamic_parameters'])) {

                foreach ($_POST['new_dynamic_parameters'] as $parameter_id => $value) {
                    if (!empty ($parameter_value)) {

                        $is_value_exist = Parameters::check_if_value_exist($parameter_id, $value);

                        if($is_value_exist === true){
                            $value_id = Parameters::get_value_id($value, $parameter_id);
                        } else{
                            $value_id = Parameters::create_new_value_id($value, $parameter_id);
                        }

                        Parameters::add_new_product_parameter_value($product_id, $parameter_id, $value_id);
                    }
                }
            }

            if (isset($_POST['color'])){
                $new_color_id_list = array();
                foreach ($_POST['color'] as $color_id => $product_amount){
                    array_push($new_color_id_list, "$color_id");
                    if($product_amount > 0){
                        Color::update_product_amount_by_product_id_and_color_id($product_id, $color_id, $product_amount);
                    } elseif ($product_amount <= 0){
                        Color::delete_product_color($product_id, $color_id);
                    }
                }
            } else {
                $new_color_id_list = array();
                array_push($new_color_id_list, 0);
            }

                $deleted_color_id_list = array_diff($original_color_id_list, $new_color_id_list);
                foreach ($deleted_color_id_list as $color_id){
                    Color::delete_product_color($product_id, $color_id);
                }

            header('Location: /admin/edit_products');
//            header("Refresh:0");

        }

        require_once('/views/layouts/header.php');
        require_once('/views/admin/product/edit_selected_product/edit_selected_product.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionLoadSelectedParametersList()
    {
        Admin::check_if_administrator();

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
        Admin::check_if_administrator();

        $uri = $_SERVER['REQUEST_URI'];
        $segments = explode('/', $uri);
        $product_id = $segments[3];
        $parameter_id = $segments[4];

        AdminProduct::delete_additional_parameter_from_product($product_id, $parameter_id);
    }

    public function actionLoadCategoryParameters()
    {
        Admin::check_if_administrator();

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

        $category_parameters_list_after_checking = array_intersect($category_parameters_list, $existing_parameters_list);

        foreach ($category_parameters_list_after_checking as $parameter_id) {
            $parameter_information = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
            $parameter_name = $parameter_information['name'];
            $parameter_russian_name = $parameter_information['russian_name'];

            $popular_parameter_values = Parameters::get_most_popular_parameter_values_by_category_id_and_parameter_id($category_id, $parameter_id);

            echo "<div class='parameter_item'>";
            echo "<br /><label>$parameter_russian_name</label><br />";
            echo "<input type='text' class='tags' data-parameter-id='$parameter_id' name='category_parameters[$parameter_id]'>";
            echo "</div>";

        }
    }


    public function actionTestAutocomplete(){

        $request = $_POST;

        $search_query = $_POST['search'];
        $parameter_id = $_POST['parameter_id'];

        $result = AdminProduct::get_autocomplete_for_parameter_value($search_query, $parameter_id);

        $autocomplete_list = array();

        foreach ($result as $value_information){
            array_push($autocomplete_list, $value_information['value']);
        }

        echo json_encode($autocomplete_list);

    }

    public function actionLoadNewRowsWithSelectedColors()
    {

        if(isset($_POST['selected_colors'])){

            $selected_colors = $_POST['selected_colors'];

            print_r($selected_colors);

            $amount = 1;

            foreach ($selected_colors as $color_id => $color_name){
                include('/views/admin/product/edit_selected_product/template_color_product_amount_table_row.php');
            }

        }




    }



}














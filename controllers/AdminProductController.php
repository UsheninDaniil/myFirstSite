<?php
require_once(ROOT. '/models/Admin.php');
require_once(ROOT. '/models/AdminProduct.php');

class AdminProductController
{
    public function actionEditProductsView(){
        include_once ('/models/Product.php');
        $productList = Product::get_product_list();

        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/admin/product/edit_products_view.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionAddProduct()
    {
        $user_role = "user";
        if(isset($_SESSION['user_id'])){
            $user_id = $_SESSION['user_id'];
            $user_role = Admin::check_user_role($user_id);
        }

        if($user_role == "admin"){

            if(isset($_POST["save_new_product"])){

                $product_information['product_name'] =  $_POST['product_name'];
                $product_information['product_price'] = $_POST['product_price'];
                $product_information['product_availability'] = $_POST['product_availability'];
                $product_information['product_category'] = $_POST['product_category_id'];

                $product_id = AdminProduct::add_new_product($product_information);

                if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                    // Если загружалось, переместим его в нужную папке, дадим новое имя $photo
                    move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/images/$product_id.jpg");
                    echo "<br />Фото загружено<br />";

                    $filename = $_SERVER['DOCUMENT_ROOT'] . "/images/$product_id.jpg";

                    // задание максимальной высоты
                    $height = 200;

                    // тип содержимого
                    header('Content-Type: image/jpeg');

                    // получение новых размеров
                    list($width_orig, $height_orig) = getimagesize($filename);

                    $ratio_orig = $width_orig/$height_orig;

                    $width = $height*$ratio_orig;

                    // ресэмплирование
                    $image_p = imagecreatetruecolor($width, $height);
                    $image = imagecreatefromjpeg($filename);
                    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

                    // вывод
                    imagejpeg($image_p, $_SERVER['DOCUMENT_ROOT'] . "/images/small_product_images/$product_id.jpg", 100);

                }

                print_r($_POST);

                if(isset($_POST["category_parameters"])){
                    foreach ($_POST["category_parameters"] as $parameter_id => $parameter_value){
                        if(!empty ($parameter_value)){
                            AdminProduct::save_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id, $parameter_value);
                        }
                    }
                }

                echo 'Создан файл с айди '.$product_id;
            }

            require_once ('/views/layouts/header.php');
            require_once (ROOT.'/views/admin/product/add_product.php');
            require_once ('/views/layouts/footer.php');
        }
        else{
            header ("Location: /no_permission");
        }
    }

    public function actionDeleteProduct()
    {
        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        $product_id=$segments[3];

        require_once('/models/Product.php');
        require_once('/models/AdminProduct.php');


        if (isset($_POST["delete_product"])){

            AdminProduct::delete_product($product_id);

            header("Location: /admin/edit_products");
        }

        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/admin/product/delete_product.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionEditProduct()
    {
        require_once (ROOT.'/models/Product.php');
        require_once (ROOT.'/models/Category.php');
        require_once (ROOT.'/models/Parameters.php');
        require_once (ROOT.'/models/AdminParameter.php');

        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        $product_id=$segments[3];

        $product_information=Product::get_product_by_id($product_id);
        $category_id = Category::get_category_id_by_product_id($product_id);
        $category_parameters_list = Parameters::get_category_parameters_list($category_id);

//        echo "Массив парамтеров категории <b>до</b> проверки на существование:<br />";
//        print_r($category_parameters_list);

        $existing_parameters_information = Parameters::get_all_parameters();
        $existing_parameters_list = [];

        foreach ($existing_parameters_information as $parameter_item){
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

        if(isset($_POST["update_product_information"])){

            if (isset($_POST['product_name'], $_POST['product_price'], $_POST['availability'])){
                $product_name = $_POST['product_name'];
                $product_price = $_POST['product_price'];
                $availability = $_POST['availability'];
                AdminProduct::update_main_product_information_by_product_id($product_id, $product_name, $product_price, $availability);
            }

            if (isset($_POST['dynamic_parameters'])){

                foreach ($_POST['dynamic_parameters'] as $parameter_id => $parameter_value){
                        AdminProduct::update_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id, $parameter_value);
                }
            }

            if (isset($_POST['new_dynamic_parameters'])){

                foreach ($_POST['new_dynamic_parameters'] as $parameter_id => $parameter_value){
                    if(!empty ($parameter_value)){
                        AdminProduct::save_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id, $parameter_value);
                    }
                }
            }

           header('Location: /admin/edit_products');
        }

        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/admin/product/edit_product.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionLoadSelectedParametersList(){

        include_once ('/models/AdminParameter.php');

        echo "<br />Список добавленных параметров:<br />";

        if (isset($_POST['add_parameters_list'])){
            $parameters_list = $_POST['add_parameters_list'];
        }

        foreach ($parameters_list as $parameter_id){
            $parameter_information = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
            $parameter_russian_name = $parameter_information['russian_name'];
            $parameter_name = $parameter_information['name'];
            $parameter_id = $parameter_information['id'];
            echo "<br /><label>$parameter_russian_name</label><br />";
            echo "<input type='text' name='new_dynamic_parameters[$parameter_id]'  value=''>";
        }
    }

    public function actionDeleteAdditionalParameter(){
        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        $product_id=$segments[3];
        $parameter_id=$segments[4];

        require_once ('/models/AdminProduct.php');
        AdminProduct::delete_additional_parameter_from_product($product_id, $parameter_id);
    }

    public function actionLoadCategoryParameters(){
        include_once ('/models/Parameters.php');
        include_once ('/models/AdminParameter.php');

        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        $category_id=$segments[3];

        $category_parameters_list_after_checking = [];

        $category_parameters_list = Parameters::get_category_parameters_list($category_id);
        $existing_parameters = Parameters::get_all_parameters();

        $existing_parameters_list = [];
        foreach ($existing_parameters as $parameter_information){
            $parameter_id = $parameter_information['id'];
            array_push($existing_parameters_list, "$parameter_id");
        }

//        echo "<br /><br />Список всех существующих параметров: <br />";
//        print_r($existing_parameters_list);

        $category_parameters_list_after_checking = array_intersect ($category_parameters_list, $existing_parameters_list);

//        echo "<br /><br />Список параметров категории после проверки: <br />";
//        print_r($category_parameters_list_after_checking);

        foreach ($category_parameters_list_after_checking as $parameter_id){
            $parameter_information = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
            $parameter_name = $parameter_information['name'];
            $parameter_russian_name = $parameter_information['russian_name'];
            echo "<br /><label>$parameter_russian_name</label><br />";
            echo "<input type='text' name='category_parameters[$parameter_id]'>";
        }




    }

}
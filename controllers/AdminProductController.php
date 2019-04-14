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

        echo "Массив парамтеров категории <b>до</b> проверки на существование:<br />";
        print_r($category_parameters_list);

        $existing_parameters_information = Parameters::get_all_parameters();
        $existing_parameters_list = [];

        foreach ($existing_parameters_information as $parameter_item){
            $id = $parameter_item['id'];
            array_push($existing_parameters_list, $id);
        }

        $category_parameters_list_after_checking = array_intersect($category_parameters_list, $existing_parameters_list);

        echo "<br /><br />Массив парамтеров категории <b>после</b> проверки на существование:<br />";
        print_r($category_parameters_list_after_checking);

        $parameters_name_list = [];
        $parameters_value_list = [];

        foreach ($category_parameters_list_after_checking as $parameter_id){
            $result = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
            array_push($parameters_name_list, $result['russian_name']);

            $value = AdminParameter::get_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id);
            array_push($parameters_value_list, $value);
        }

        $specified_parameters_list = AdminParameter::get_specified_parameters_by_product_id($product_id);

        echo "<br /><br />Список заданных параметров для выбранного товара:<br />";
        print_r($specified_parameters_list);

        $additional_parameters_list = array_intersect($existing_parameters_list, $specified_parameters_list);

        echo "<br /><br />Список заданных параметров после проверки на существование:<br />";
        print_r($additional_parameters_list);

        $additional_parameters_list_after_checking = array_diff($additional_parameters_list, $category_parameters_list);

        echo "<br /><br />Дополнительные параметры <b>=</b> все заданные параметры товара <b>-</b> несуществующие параметры <b>-</b> параметры категории:<br />";
        print_r($additional_parameters_list_after_checking);

        $not_specified_parameters = [];
        $not_specified_parameters = array_diff($existing_parameters_list, $additional_parameters_list_after_checking, $category_parameters_list_after_checking);

        echo "<br /><br />Не заданные параметры, которые можно добавить:<br />";
        print_r($not_specified_parameters);


        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/admin/product/edit_product.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionLoadSelectedParametersList(){

        include_once ('/models/AdminParameter.php');

        echo "<br />Список добавленных параметров:<br />";

        if (isset($_POST['parameter_id'])){
            $parameters_list = $_POST['parameter_id'];
        }

        foreach ($parameters_list as $parameter_id){
            $parameter_information = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
            $parameter_name = $parameter_information['russian_name'];
            echo "<br /><label>$parameter_name</label><br />";
            echo "<input type='text' name='parameter_value'  value='' >";
        }

    }
}
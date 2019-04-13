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

        $parameters_list = array_intersect($category_parameters_list, $existing_parameters_list);

        echo "<br /><br />Массив парамтеров категории <b>после</b> проверки на существование:<br />";
        print_r($parameters_list);

        $parameters_name_list = [];
        $parameters_value_list = [];

        foreach ($parameters_list as $parameter_id){
            $result = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
            array_push($parameters_name_list, $result['russian_name']);

            $value = AdminParameter::get_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id);
            array_push($parameters_value_list, $value);
        }

        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/admin/product/edit_product.php');
        require_once ('/views/layouts/footer.php');
    }

}
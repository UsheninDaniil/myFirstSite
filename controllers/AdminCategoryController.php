<?php
require_once(ROOT. '/models/Admin.php');
require_once(ROOT. '/models/AdminCategory.php');
require_once ('/models/DatabaseConnect.php');

class AdminCategoryController
{
    public function actionEditCategoryView()
    {
        require_once (ROOT. '/models/Parameters.php');
        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/admin/category/edit_categories_view.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionEditSelectedCategory()
    {
        require_once (ROOT. '/models/Parameters.php');
        require_once (ROOT. '/models/Category.php');

        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        if(isset($segments[3])){
            $category_id =$segments[3];
        }
        $category_name = Category::get_category_name_by_id($category_id);
        $category_parameters_list = Parameters::get_category_parameters_list($category_id);
        $existing_parameters_list = Parameters::get_all_parameters();

        $existing_id_parameters_list=[];

        foreach ($existing_parameters_list as $parameter_item){
            $id = $parameter_item['id'];
            array_push($existing_id_parameters_list, $id);
        }

        $category_parameters_list = array_intersect($category_parameters_list, $existing_id_parameters_list);

        $not_category_parameters = array_diff($existing_id_parameters_list, $category_parameters_list);

        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/admin/category/edit_selected_category.php');
        require_once (ROOT. '/views/admin/category/category_parameters_table.php');

        echo "
        <div class=\"remove_info\"></div>
        ";

        require_once ('/views/layouts/footer.php');
    }

    public function actionReloadCategoryParametersTable(){
        require_once (ROOT. '/models/Parameters.php');
        require_once (ROOT. '/models/Category.php');

        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        if(isset($segments[3])){
            $category_id =$segments[3];
        }
        $category_name = Category::get_category_name_by_id($category_id);
        $category_parameters_list = Parameters::get_category_parameters_list($category_id);
        $existing_parameters_list = Parameters::get_all_parameters();

        $existing_id_parameters_list=[];

        foreach ($existing_parameters_list as $parameter_item){
            $id = $parameter_item['id'];
            array_push($existing_id_parameters_list, $id);
        }

        $category_parameters_list = array_intersect($category_parameters_list, $existing_id_parameters_list);

        $not_category_parameters = array_diff($existing_id_parameters_list, $category_parameters_list);

        require_once (ROOT. '/views/admin/category/category_parameters_table.php');
    }

    public function actionSaveSelectedExistingParametersToCategory(){
        require_once (ROOT. '/models/Admin.php');
        $uri=$_SERVER['REQUEST_URI'];

        print_r($uri);
        echo "<br />";

        $segments = explode('/',$uri);

        echo '<br />Содержимое POST<br />';
        print_r($_POST);

        if (isset($_POST['parameter_id'])){
            $save_parameters_list = $_POST['parameter_id'];
        }

        echo"<br /><br />Список параметров на сохранение <br />";
        print_r($save_parameters_list);

        if(isset($segments[3])){
            $category_id = $segments[3];
            AdminCategory::save_selected_existing_parameters($category_id,$save_parameters_list);
        }
    }

    public function actionSaveNewParameterToCategory(){

        require_once (ROOT. '/models/Admin.php');

        $uri=$_SERVER['REQUEST_URI'];

        print_r($uri);
        echo "<br />";

        $segments = explode('/',$uri);

        if(isset($segments[3])){
            $category_id = $segments[3];
            echo "<br/> Категория $category_id";
        }

        echo '<br/><br/>Содержимое POST<br/>';
        print_r($_POST);

        if (isset($_POST['parameter_name'])){
            $parameter_name = $_POST['parameter_name'];
        }

        if (isset($_POST['parameter_russian_name'])){
            $parameter_russian_name = $_POST['parameter_russian_name'];
        }

        if (isset($_POST['parameter_unit'])){
            $parameter_unit = $_POST['parameter_unit'];
        }

        AdminCategory::save_new_parameter($category_id, $parameter_name, $parameter_russian_name, $parameter_unit);
    }

    public function actionRemoveParameterFromCategory(){
        require_once (ROOT. '/models/Admin.php');

        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        if(isset($segments[3])){
            $parameter_id =  $segments[3];
        }

        if(isset($segments[4])){
            $category_id = $segments[4];
        }

        print_r($uri);
        AdminCategory::delete_parameters_from_category($category_id,$parameter_id);
    }

    public function actionChangeTheSortOrderOfCategories(){

        if(isset($_POST['category_id_list'])){
            echo "Содержимое POST:<br />";
            print_r($_POST);

            $i = 1;

            foreach ($_POST['category_id_list'] as $category_id){
                echo "<br />category id $category_id = sort order $i ";

                $mysqli = DatabaseConnect::connect_to_database();

                $mysqli->query("UPDATE category SET `sort_order` = '$i' WHERE id = '$category_id'");

                DatabaseConnect::disconnect_database($mysqli);

                $i = $i + 1;
            }
        }
    }

    public function actionUpdateCategoryNameUsingEditable(){

        $mysqli = DatabaseConnect::connect_to_database();

        if(isset($_POST['name']) AND ($_POST['name']=="category_name")){
            $new_category_name = $_POST['value'];
            $category_id = $_POST['pk'];
            $sql = "UPDATE category SET `name` = '$new_category_name' WHERE id='$category_id'";
            print_r($sql);
            $mysqli->query($sql);
        }

        DatabaseConnect::disconnect_database($mysqli);

        print_r($_POST);

    }

    public function actionUpdateCategoryStatusUsingEditable(){

        $mysqli = DatabaseConnect::connect_to_database();

        if(isset($_POST['name']) AND ($_POST['name']=="category_status")){
            $new_category_status = $_POST['value'];
            $category_id = $_POST['pk'];
            $sql = "UPDATE category SET `status` = '$new_category_status' WHERE id='$category_id'";
            print_r($sql);
            $mysqli->query($sql);
        }

        DatabaseConnect::disconnect_database($mysqli);

        print_r($_POST);

    }
}
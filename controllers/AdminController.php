<?php

require_once(ROOT. '/models/Admin.php');

Class AdminController
{
    public function actionCabinet()
    {
        $user_role = "user";
        if(isset($_SESSION['user_id'])){
            $user_id = $_SESSION['user_id'];
            $user_role = Admin::check_user_role($user_id);
        }

        if($user_role == "admin"){
            require_once ('/views/layouts/header.php');
            require_once (ROOT.'/views/admin/cabinet.php');
            require_once ('/views/layouts/footer.php');
        }
        else{
           header ("Location: /no_permission");
        }

    }

    public function actionNoPermission()
    {
        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/admin/NoPermission.php');
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
            require_once (ROOT.'/views/admin/product/add.php');
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
        require_once (ROOT.'/views/admin/product/delete.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionEditProduct()
    {
        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        $product_id=$segments[3];

        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/admin/product/edit.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionEditCategory()
    {
        require_once (ROOT. '/models/Parameters.php');
        $parameters_list = Parameters::get_category_parameters_list(1);

        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/admin/category/edit.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionEditCategoryParameters()
    {
        require_once (ROOT. '/models/Parameters.php');
        require_once (ROOT. '/models/Category.php');

        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        if(isset($segments[3])){
            $category_id =$segments[3];
        }

        $parameters_list = Parameters::get_category_parameters_list($category_id);
//        $parameters_list = implode ('РАЗДЕЛИТЕЛЬ', $parameters_list);

        if(isset($category_id)){
            $category_name = Category::get_category_name_by_id($category_id);
            echo "<div style='text-align: center'><br />Категория <a href='/category/$category_id' class='edit-category-name' '>$category_name</a>: <br /><br /></div>";

            echo "
                <table border='1' cellpadding='5' class='parameter_list_table' data-category-id='$category_id'>
                    <tr>
                        <th colspan='2'>Список параметров</th>
                    </tr>
                ";

            foreach ($parameters_list as $i => $parameter_id){
                $parameter_name = Parameters::get_parameter_name_by_parameter_id($parameter_id);
                echo "<tr>
                      <th>$parameter_name</th>
                      <th><a href='javascript:void(0);' class='remove_parameter' data-parameter-id='$parameter_id'><span class='glyphicon glyphicon-remove'></span></a></th>
                      </tr>";
            }

            echo "<tr><th colspan='2'>
                  <a href='javascript:void(0);' class='load_existing_parameters'>Добавить существующий параметр <span class='glyphicon glyphicon-search'></span></a>
                  <div class='load_existing_parameters_result'></div>
                  <div class='check_save_existing_parameters_button'></div>
                  </th></tr>
                  
                  
                  <tr><th colspan='2'>
                  <a href='javascript:void(0);' class='create_new_parameter'>Создать новый параметр <span class='glyphicon glyphicon-pencil'></span></a>
                  <div class='create_new_parameter_result'></div>
                  <div class='check_save_new_parameter_button'></div>
                  </th></tr></table>";
        }
    }

    public function actionLoadExistingParameters(){
        require_once (ROOT. '/models/Parameters.php');

        $parameters_list = Parameters::get_all_parameters();
        echo "<br />

        <form method='post' id ='save_parameters_list'>";

        foreach ($parameters_list as $parameter_item){
            $id = $parameter_item['id'];
            echo "<input type='checkbox' name='parameter_id[]'  value='$id' data-parameters-list='$id' class='save_parameters_list'>".$parameter_item['russian_name']."<br />";
        }
        echo "</form>";
        echo "<a href='javascript:void(0);' class='save_selected_existing_parameters'>Добавить <span class=\"glyphicon glyphicon-ok\"></span></a>";
    }

    public function actionCreateNewParameter(){
        echo '
            <form  enctype="multipart/form-data" id ="create_new_parameter_information" name = "add_product" action="" method ="post" class="feedback">
                <br /><label>Аббревиатура (латиницей):</label><br />
                <input type="text" name="parameter_name" />
                <br /><label>Название:</label><br />
                <input type="text" name="parameter_russian_name" /><br />
            </form>
            <a href= "javascript:void(0);" class="save_new_parameter">Создать <span class="glyphicon glyphicon-ok"></span></a>
       ';

    }

    public function actionSaveSelectedExistingParameters(){
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
                Admin::save_selected_existing_parameters($category_id,$save_parameters_list);
            }



//        print_r($uri);
    }

    public function actionSaveNewParameter(){

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

        Admin::save_new_parameter($category_id, $parameter_name, $parameter_russian_name);

    }

    public function actionRemoveParameter(){
        require_once (ROOT. '/models/Admin.php');

        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        if(isset($segments[3])){
            $parameter_id =  $segments[3];
        }

        if(isset($segments[4])){
            $category_id = $segments[4];
        }

        Admin::delete_parameters_from_category($category_id,$parameter_id);

        print_r($uri);

    }




}

?>

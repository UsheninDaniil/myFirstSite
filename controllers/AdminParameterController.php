<?php
require_once(ROOT. '/models/Admin.php');
require_once(ROOT. '/models/AdminParameter.php');

class AdminParameterController
{
    public function actionEditParametersView(){
        require_once (ROOT. '/models/Parameters.php');
        $existing_parameters_list = Parameters::get_all_parameters();

        require_once ('/views/layouts/header.php');
        require_once (ROOT. '/views/admin/parameters/edit_parameters_view.php');
        require_once (ROOT. '/views/admin/parameters/parameters_table.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionLoadParametersTable(){
        require_once (ROOT. '/models/Parameters.php');
        $existing_parameters_list = Parameters::get_all_parameters();
        require_once (ROOT. '/views/admin/parameters/parameters_table.php');
    }

    public function actionEditSelectedParameter(){
        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        if(isset($segments[3])){
            $parameter_id =$segments[3];
        }
        $parameter_information=AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
        require_once ('/views/layouts/header.php');
        require_once (ROOT. '/views/admin/parameters/edit_parameter.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionUpdateSelectedParameter(){
        require_once (ROOT. '/models/AdminParameter.php');
        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        if(isset($segments[3])){
            $parameter_id =$segments[3];
        }

        $parameter_information=[];

        if (isset($_POST['parameter_name'])){
            $name = $_POST['parameter_name'];
        }

        if (isset($_POST['parameter_russian_name'])){
            $russian_name = $_POST['parameter_russian_name'];
        }

        if (isset($_POST['parameter_unit'])){
            $unit = $_POST['parameter_unit'];
        }

        AdminParameter::update_parameter_information_by_parameter_id($parameter_id, $name, $russian_name, $unit);
    }

    public function actionRemoveSelectedParameter(){
        require_once (ROOT. '/models/Parameters.php');
        $existing_parameters_list = Parameters::get_all_parameters();

        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        if(isset($segments[3])){
            $parameter_id =$segments[3];
        }

        AdminParameter::RemoveSelectedParameter($parameter_id);

        $existing_parameters_list = Parameters::get_all_parameters();

        require_once (ROOT. '/views/admin/parameters/parameters_table.php');
    }

    public function actionSaveNewParameter(){

        require_once (ROOT. '/models/Admin.php');

        $uri=$_SERVER['REQUEST_URI'];

        $segments = explode('/',$uri);

        if (isset($_POST['parameter_name'])){
            $parameter_name = $_POST['parameter_name'];
        }

        if (isset($_POST['parameter_russian_name'])){
            $parameter_russian_name = $_POST['parameter_russian_name'];
        }

        if (isset($_POST['parameter_unit'])){
            $parameter_unit = $_POST['parameter_unit'];
        }

        AdminParameter::save_new_parameter($parameter_name, $parameter_russian_name, $parameter_unit);
    }


}
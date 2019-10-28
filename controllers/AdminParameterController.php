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
require_once('/components/Helper.php');
require_once('/controllers/AdminController.php');


class AdminParameterController
{
    public function actionEditParametersView()
    {
        Admin::check_if_administrator();

        $existing_parameters_list = Parameters::get_all_parameters();

        require_once('/views/layouts/header.php');
        require_once('/views/admin/parameters/edit_parameters_view.php');
        require_once('/views/admin/parameters/parameters_table.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionLoadParametersTable()
    {
        Admin::check_if_administrator();

        $existing_parameters_list = Parameters::get_all_parameters();
        require_once(ROOT . '/views/admin/parameters/parameters_table.php');
    }

    public function actionEditSelectedParameter()
    {
        Admin::check_if_administrator();

        $parameter_id = Helper::get_information_from_url(3);
        $parameter_information = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);

        require_once('/views/layouts/header.php');
        require_once('/views/admin/parameters/edit_parameter.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionUpdateSelectedParameter()
    {
        Admin::check_if_administrator();

        $parameter_information = [];

        if (isset($_POST['parameter_id'], $_POST['parameter_name'], $_POST['parameter_russian_name'], $_POST['parameter_unit'])) {
            $parameter_id = $_POST['parameter_id'];
            $name = $_POST['parameter_name'];
            $russian_name = $_POST['parameter_russian_name'];
            $unit = $_POST['parameter_unit'];

            AdminParameter::update_parameter_information_by_parameter_id($parameter_id, $name, $russian_name, $unit);
        }

    }

    public function actionRemoveSelectedParameter()
    {
        Admin::check_if_administrator();

        if(isset($_POST['parameter_id'])){
            $parameter_id = $_POST['parameter_id'];
        } else{
            return;
        }

        AdminParameter::RemoveSelectedParameter($parameter_id);
        $existing_parameters_list = Parameters::get_all_parameters();
        require_once(ROOT . '/views/admin/parameters/parameters_table.php');
    }

    public function actionSaveNewParameter()
    {
        Admin::check_if_administrator();

        if (isset($_POST['parameter_name'], $_POST['parameter_russian_name'], $_POST['parameter_unit'])) {
            $parameter_name = $_POST['parameter_name'];
            $parameter_russian_name = $_POST['parameter_russian_name'];
            $parameter_unit = $_POST['parameter_unit'];
            AdminParameter::save_new_parameter($parameter_name, $parameter_russian_name, $parameter_unit);
        }
    }


}
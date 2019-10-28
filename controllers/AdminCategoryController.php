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

class AdminCategoryController extends AdminController
{
    public function actionEditCategoryView()
    {
        $categoryList = Category::get_all_category_list();

        require_once('/views/layouts/header.php');
        require_once('/views/admin/category/edit_categories_view.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionEditSelectedCategory()
    {
        Admin::check_if_administrator();

        $category_id = Helper::get_information_from_url(3);

        $category_name = Category::get_category_name_by_id($category_id);
        $category_parameters_list = Parameters::get_category_parameters_list($category_id);
        $existing_parameters_list = Parameters::get_all_parameters();

        $existing_id_parameters_list = [];

        foreach ($existing_parameters_list as $parameter_item) {
            $id = $parameter_item['id'];
            array_push($existing_id_parameters_list, $id);
        }

        $category_parameters_list = array_intersect($category_parameters_list, $existing_id_parameters_list);

        $not_category_parameters = array_diff($existing_id_parameters_list, $category_parameters_list);

        require_once('/views/layouts/header.php');
        require_once('/views/admin/category/edit_selected_category.php');

        require_once('/views/layouts/footer.php');
    }

    public function actionReloadCategoryParametersTable()
    {
        Admin::check_if_administrator();

        if(isset($_POST['category_id'])){
            $category_id = $_POST['category_id'];
        } else{
            return;
        }

        $category_name = Category::get_category_name_by_id($category_id);
        $category_parameters_list = Parameters::get_category_parameters_list($category_id);
        $existing_parameters_list = Parameters::get_all_parameters();

        $existing_id_parameters_list = [];

        foreach ($existing_parameters_list as $parameter_item) {
            $id = $parameter_item['id'];
            array_push($existing_id_parameters_list, $id);
        }

        $category_parameters_list = array_intersect($category_parameters_list, $existing_id_parameters_list);

        $not_category_parameters = array_diff($existing_id_parameters_list, $category_parameters_list);

        require_once(ROOT . '/views/admin/category/category_parameters_table.php');
    }

    public function actionSaveSelectedExistingParametersToCategory()
    {
        Admin::check_if_administrator();

        if(isset($_POST['form_data'], $_POST['category_id'])){
            $category_id = $_POST['category_id'];
            $form_data = $_POST['form_data'];
            parse_str($form_data, $values);
            $parameters_list = ($values['parameters_list']);
            AdminCategory::save_selected_existing_parameters($category_id, $parameters_list);
        }
    }

    public function actionSaveNewParameterToCategory()
    {
        Admin::check_if_administrator();

        if (isset($_POST['category_id'], $_POST['parameter_name'], $_POST['parameter_russian_name'], $_POST['parameter_unit'])){
            $category_id = $_POST['category_id'];
            $parameter_name = $_POST['parameter_name'];
            $parameter_russian_name = $_POST['parameter_russian_name'];
            $parameter_unit = $_POST['parameter_unit'];
            AdminCategory::save_new_parameter($category_id, $parameter_name, $parameter_russian_name, $parameter_unit);
        }
    }

    public function actionRemoveParameterFromCategory()
    {
        Admin::check_if_administrator();

        if(isset($_POST['category_id'], $_POST['remove_parameter_id'])){
            $category_id = $_POST['category_id'];
            $parameter_id = $_POST['remove_parameter_id'];
            AdminCategory::delete_parameters_from_category($category_id, $parameter_id);
        }
    }

    public function actionUpdateTheSortOrderOfCategories()
    {
        Admin::check_if_administrator();

        if (isset($_POST['category_id_list'])) {
            $category_id_list = $_POST['category_id_list'];
            AdminCategory::update_the_sort_order_of_categories($category_id_list);
        }
    }

    public function actionUpdateCategoryNameUsingEditable()
    {
        Admin::check_if_administrator();

        if(isset($_POST['value'], $_POST['pk'])){
            $new_category_name = $_POST['value'];
            $category_id = $_POST['pk'];
        } else{
            return;
        }

        AdminCategory::update_category_name($category_id, $new_category_name);
    }

    public function actionUpdateCategoryStatus()
    {
        Admin::check_if_administrator();

        if (isset($_POST['category_status'], $_POST['category_id'])) {
            $new_category_status = $_POST['category_status'];
            $category_id = $_POST['category_id'];
        } else{
            return;
        }

        AdminCategory::update_category_status($category_id, $new_category_status);
    }

}
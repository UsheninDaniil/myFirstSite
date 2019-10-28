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
require_once('/components/Images.php');
require_once('/components/Helper.php');
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

        // настройки пагинации, которые влияют на ее отображение
        $pagination = new Pagination(10);

        $filter_parameters = Helper::get_get_parameters_from_url_without_page();

        if (!empty ($filter_parameters)) {
            $get_elements_request = AdminProduct::build_request_for_filter($filter_parameters);
        }

        if(empty($get_elements_request)){
            $get_elements_request = "SELECT * FROM product";
        }

        $get_total_elements_amount_request = "SELECT COUNT(*) FROM($get_elements_request) tmp";

        $productList = $pagination->get_pagination_elements($get_elements_request, $get_total_elements_amount_request);

        require_once('/views/layouts/header.php');
        require_once('/views/admin/product/edit_products_view.php');
        require_once('/views/admin/product/products_table.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionAddProduct()
    {
        if (isset($_POST["save_new_product"])){
            $files = $_FILES;
            $post = $_POST;
            AdminProduct::save_new_product($post, $files);
        }

        // Подробная информация о всех цветах
        $color_list = Color::get_colors_list();

        // Информация о всех цветах в формате id => name
        foreach ($color_list as $color) {
            $color_id_and_name_list[$color['id']] = $color['name'];
        }

        require_once('/views/layouts/header.php');
        require_once('/views/admin/product/add_product.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionDeleteProduct()
    {
        $product_id = Helper::get_information_from_url(3);

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
        $product_id = Helper::get_information_from_url(3);

        $product_information = Product::get_product_by_id($product_id);
        $category_id = Category::get_category_id_by_product_id($product_id);
        $category_parameters_list = Parameters::get_category_parameters_list($category_id);

        $existing_parameters_information = Parameters::get_all_parameters();
        $existing_parameters_list = array_column($existing_parameters_information, 'id');

        $category_parameters_list_after_checking = array_intersect($category_parameters_list, $existing_parameters_list);

        // Получить список всех параметров товара, чтоб потом узнать какие параметры ему можно добавить
        $specified_parameters_list = AdminParameter::get_specified_parameters_by_product_id($product_id);

        $additional_parameters_list = array_intersect($existing_parameters_list, $specified_parameters_list);

        $additional_parameters_list_after_checking = array_diff($additional_parameters_list, $category_parameters_list);

        $not_specified_parameters = array_diff($existing_parameters_list, $additional_parameters_list_after_checking, $category_parameters_list_after_checking);

        // Колличество товаров разных цветов
        $color_product_amount = Color::get_product_colors($product_id);

        //Список id всех цветов товара (нужно для того чтоб отследить какие цвета удалили)
        $original_color_id_list = array_column($color_product_amount, 'color_id');

        // Информация о всех существующих цветах
        $color_list = Color::get_colors_list();

        // Информация о всех цветах в формате id => name
        foreach ($color_list as $color) {
            $color_id_and_name_list[$color['id']] = $color['name'];
        }

        // Цвета, которых у данного товара нету и которые можно добавить
        $not_selected_colors_list = Color::get_not_selected_product_colors_list($product_id);

        // Есть ли у товара возможность выбирать цвета?
        $ability_to_choose_the_color = Color::check_is_there_ability_to_choose_the_color($product_id);

        if (isset($_POST["update_product_information"])) {
            $post = $_POST;
            AdminProduct::update_product_information($product_id, $post, $original_color_id_list);
            header('Location: /admin/edit_products');
        }

        require_once('/views/layouts/header.php');
        require_once('/views/admin/product/edit_selected_product/edit_selected_product.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionLoadSelectedParametersList()
    {
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
        if (isset($_POST['product_id'], $_POST['parameter_id'])) {
            $product_id = $_POST['product_id'];
            $parameter_id = $_POST['parameter_id'];
            AdminProduct::delete_additional_parameter_from_product($product_id, $parameter_id);
        }
    }

    public function actionLoadCategoryParameters()
    {
        if (isset($_POST['category_id'])) {
            $category_id = $_POST['category_id'];
        } else {
            return;
        }

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


    public function actionTestAutocomplete()
    {
        $request = $_POST;
        $search_query = $_POST['search'];
        $parameter_id = $_POST['parameter_id'];

        $result = AdminProduct::get_autocomplete_for_parameter_value($search_query, $parameter_id);

        $autocomplete_list = array();

        foreach ($result as $value_information) {
            array_push($autocomplete_list, $value_information['value']);
        }
        echo json_encode($autocomplete_list);
    }

    public function actionLoadNewRowsWithSelectedColors()
    {
        if (isset($_POST['selected_colors'])) {
            $selected_colors = $_POST['selected_colors'];
            $amount = 1;
            foreach ($selected_colors as $color_id => $color_name) {
                include('/views/admin/product/edit_selected_product/template_color_product_amount_table_row.php');
            }
        }
    }


}














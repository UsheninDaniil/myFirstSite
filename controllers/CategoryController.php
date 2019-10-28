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
require_once('/components/Helper.php');

Class CategoryController
{

    public function actionView()
    {
        require_once(ROOT . '/components/Pagination.php');

        $categoryList = Category::get_category_list();
        $category_id = Helper::get_information_from_url_with_get_parameters(2);
        $category_name = Category::get_category_name_by_id($category_id);

        // настройки пагинации, которые влияют на ее отображение
        $pagination = new Pagination(1);

        $filter_parameters = Helper::get_get_parameters_from_url_without_page();

        if (!empty ($filter_parameters)) {
            $get_elements_request = Category::build_request_for_filter($filter_parameters, $category_id);
        } else{
            $get_elements_request = "SELECT * FROM product WHERE  category_id = '$category_id' ORDER BY id, name ASC";
        }

        $get_total_elements_amount_request = "SELECT COUNT(*) FROM($get_elements_request) tmp";

        $productList = $pagination->get_pagination_elements($get_elements_request, $get_total_elements_amount_request);

        require_once('/views/layouts/header.php');
        require_once('/views/category/category_view.php');
        require_once('/views/layouts/footer.php');
    }


    public function actionDeleteFilterTag()
    {
        $get_parameters = $_POST['get_parameters'];

        parse_str($get_parameters, $new_get_parameters);

        $tag_name = $_POST['tag_name'];

        $segments = explode(' = ', $tag_name);
        $parameter_name = $segments[0];
        $parameter_value = $segments[1];

        $parameter_id = Parameters::get_parameter_id_by_parameter_name($parameter_name);
        $value_id = Parameters::get_value_id($parameter_value, $parameter_id);

        foreach ($new_get_parameters[$parameter_id] as $key => $id){
            if($id === $value_id){
                unset($new_get_parameters[$parameter_id][$key]);
            }
        }

        $new_get_parameters = http_build_query($new_get_parameters);
        echo $new_get_parameters;
    }

}



//Работает
//
//SELECT product_parameter_values.product_id FROM product_parameter_values INNER JOIN parameter_values ON parameter_values.id = product_parameter_values.value_id INNER JOIN category_parameters ON product_parameter_values.parameter_id = category_parameters.parameter_id INNER JOIN product ON product.id = product_parameter_values.product_id
//
//WHERE product.category_id = '2'
//
//AND product_parameter_values.product_id IN (
//    SELECT product_id FROM product_parameter_values
//    INNER JOIN parameter_values ON parameter_values.id = product_parameter_values.value_id
//    WHERE product_parameter_values.parameter_id = '22' AND parameter_values.value IN ('4')
//)
//
//AND product_parameter_values.product_id IN (
//    SELECT product_id FROM product_parameter_values
//    INNER JOIN parameter_values ON parameter_values.id = product_parameter_values.value_id
//    WHERE product_parameter_values.parameter_id = '24' AND parameter_values.value IN ('2.7')
//)
//
//GROUP BY product_parameter_values.product_id
//
//
//
//
//Не работает
//
//SELECT product_parameter_values.product_id FROM product_parameter_values INNER JOIN parameter_values ON parameter_values.id = product_parameter_values.value_id INNER JOIN category_parameters ON product_parameter_values.parameter_id = category_parameters.parameter_id INNER JOIN product ON product.id = product_parameter_values.product_id
//
//WHERE product.category_id = '2'
//
//AND product_parameter_values.product_id IN (
//    SELECT product_id FROM product_parameter_values
//    WHERE product_parameter_values.parameter_id = '22' AND parameter_values.value IN ('4')
//)
//
//AND product_parameter_values.product_id IN (
//    SELECT product_id FROM product_parameter_values
//    WHERE product_parameter_values.parameter_id = '24' AND parameter_values.value IN ('2.7')
//)
//
//GROUP BY product_parameter_values.product_id


?>


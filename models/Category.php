<?php

include_once('/models/DatabaseConnect.php');

class Category extends DatabaseConnect
{
    public static function get_category_list()
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT id, name, sort_order, status FROM category WHERE  status = '1' ORDER BY sort_order, name ASC");

        $parameters_list = ['id', 'name', 'sort_order', 'status'];

        $category_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $category_list;
    }

    public static function get_all_category_list()
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT id, name, sort_order, status FROM category ORDER BY sort_order, name ASC");

        $parameters_list = ['id', 'name', 'sort_order', 'status'];

        $category_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $category_list;
    }


    public static function get_category_name_by_id($category_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM category WHERE  id ='$category_id'");

        $row = $result->fetch_assoc();

        $category_name = ucfirst($row['name']);

        parent::disconnect_database($mysqli);
        return $category_name;
    }

    public static function get_category_id_by_product_id($product_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM product WHERE  id ='$product_id'");

        $row = $result->fetch_assoc();

        $category_id = $row['category_id'];

        parent::disconnect_database($mysqli);
        return $category_id;
    }

    public static function get_main_product_information_after_category_filter_by_product_list($product_id_list_after_filter)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM product WHERE  status = '1' AND id IN (" . implode(',', $product_id_list_after_filter) . ") ORDER BY id, name ASC");

        $parameters_list = ['id', 'name', 'price', 'status', 'rating'];

        $product_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $product_list;
    }

    public static function build_request_for_filter($filter_parameters, $category_id)
    {
        $total_amount = count($filter_parameters);
        $counter = 0;
        $united_request = "";

        foreach ($filter_parameters as $parameter_id => $parameter_values_id_array) {

            $counter = $counter + 1;

            $request_first_part = "
                SELECT product.* FROM product
                INNER JOIN product_parameter_values ON product.id = product_parameter_values.product_id 
                INNER JOIN parameter_values ON parameter_values.id = product_parameter_values.value_id
                INNER JOIN category_parameters ON product_parameter_values.parameter_id = category_parameters.parameter_id 
                WHERE product.category_id = '$category_id'
                ";

            $request_second_part = "
                AND product_parameter_values.product_id IN (
                SELECT product_id FROM product_parameter_values
                INNER JOIN parameter_values ON parameter_values.id = product_parameter_values.value_id
                WHERE product_parameter_values.parameter_id = '$parameter_id' 
                AND parameter_values.id IN ('" . implode("','", $parameter_values_id_array) . "') )
                ";

            $request_third_part = "
                GROUP BY product_parameter_values.product_id
                ";

            if (strlen($united_request) < 1) {
                $united_request = $united_request . $request_first_part . $request_second_part;
            } else {
                $united_request = $united_request . $request_second_part;
            }

            if ($counter == $total_amount) {
                $united_request = $united_request . $request_third_part;
            }
        }
        return $united_request;
    }

}


?>
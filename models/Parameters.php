<?php

include_once('/models/DatabaseConnect.php');

class Parameters extends DatabaseConnect
{
    public static function get_category_parameters_list($category_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT parameters_list.id FROM parameters_list INNER JOIN category_parameters ON category_parameters.parameter_id = parameters_list.id WHERE category_parameters.category_id = '$category_id'");

        $parameter = 'id';

        $category_parameters_list = DatabaseConnect::fetch_array_of_one_parameter($result, $parameter);

        parent::disconnect_database($mysqli);
        return $category_parameters_list;
    }


    public static function get_category_parameters_list_for_category_filter($category_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT parameters_list.id FROM parameters_list INNER JOIN category_parameters ON category_parameters.parameter_id = parameters_list.id WHERE category_parameters.category_id = '$category_id' AND category_parameters.show_in_filter = '1'");

        $parameters_list = ['id'];

        $category_parameters_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $category_parameters_list;
    }


    public static function get_parameter_name_by_parameter_id($parameter_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT russian_name FROM parameters_list WHERE  id = '$parameter_id' ");

        $result_array = $result->fetch_array();

        $parameter_name = $result_array['russian_name'];

        parent::disconnect_database($mysqli);
        return $parameter_name;
    }

    public static function get_parameter_id_by_parameter_name($parameter_name){
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT id FROM parameters_list WHERE  russian_name = '$parameter_name' ");

        $result_array = $result->fetch_array();

        $parameter_id = $result_array['id'];

        parent::disconnect_database($mysqli);
        return $parameter_id;
    }


    public static function get_category_parameter_information_by_category_id_and_parameter_id($category_id, $parameter_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT show_in_filter, sort_order FROM category_parameters WHERE  category_id = '$category_id' AND parameter_id = '$parameter_id' ");

        $result_array = $result->fetch_array();

        $parameters_list = ['show_in_filter', 'sort_order'];

        $category_paramter_information = DatabaseConnect::fetch_one_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $category_paramter_information;
    }


    public static function get_all_parameters()
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM parameters_list ORDER BY id ASC");

        $parameters_list = ['id', 'name', 'russian_name', 'unit'];

        $all_parameters_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $all_parameters_list;
    }


    public static function get_most_popular_parameter_values_by_category_id_and_parameter_id($category_id, $parameter_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT id FROM product WHERE category_id = '$category_id'");

        $parameter = 'id';

        $category_product_id_list = DatabaseConnect::fetch_array_of_one_parameter($result, $parameter);

        if (count($category_product_id_list) === 0) {
            $category_product_id_list = [-1];
        }

        $result_2 = $mysqli->query("
          SELECT parameter_values.value, COUNT(*) AS count 
          FROM parameter_values 
          INNER JOIN product_parameter_values ON parameter_values.id = product_parameter_values.value_id
          WHERE product_parameter_values.product_id IN (" . implode(',', $category_product_id_list) . ") 
          AND product_parameter_values.parameter_id = '$parameter_id'
          GROUP BY parameter_values.value ORDER BY 2 DESC
          ");

        $parameters_list_2 = ['value', 'count'];

        $most_popular_parameter_values_list = DatabaseConnect::fetch_two_dimensional_array($result_2, $parameters_list_2);

        parent::disconnect_database($mysqli);
        return $most_popular_parameter_values_list;
    }

    public static function get_value_id($value, $parameter_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("
        SELECT id 
        FROM parameter_values 
        WHERE  value = '$value' AND parameter_id = '$parameter_id' ");

        $result_array = $result->fetch_array();

        $value_id = $result_array['id'];

        parent::disconnect_database($mysqli);
        return $value_id;

    }

    public static function get_value_by_value_id($value_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("
        SELECT value 
        FROM parameter_values 
        WHERE  id = '$value_id'");

        $result_array = $result->fetch_array();

        $value = $result_array['value'];

        parent::disconnect_database($mysqli);
        return $value;
    }

    public static function create_new_value_id($value, $parameter_id){

        $mysqli = parent::connect_to_database();

        $mysqli->query("INSERT INTO `parameter_values` (`parameter_id`, `value`) VALUES ('$parameter_id', '$value')");

        $result = $mysqli->query("SELECT id FROM parameter_values WHERE parameter_id = '$parameter_id' AND value = '$value' ");

        $result_array = $result->fetch_assoc();

        $value_id = $result_array['id'];

        parent::disconnect_database($mysqli);

        return $value_id;

    }

    public static function add_new_product_parameter_value($product_id, $parameter_id, $value_id)
    {
        $mysqli = parent::connect_to_database();

        $mysqli->query("INSERT INTO `myFirstSite`.`product_parameter_values` (`product_id`,`parameter_id`,`value_id`) VALUES ('$product_id', '$parameter_id', '$value_id')");

        parent::disconnect_database($mysqli);
    }

    public static function check_if_value_exist($parameter_id, $value){

        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT COUNT(*) AS count FROM parameter_values WHERE parameter_id = '$parameter_id' AND value = '$value' ");

        $result_array = $result->fetch_array();

        $count = $result_array['count'];

        if($count > 0){
            $result = true;
        } else{
            $result = false;
        }

        parent::disconnect_database($mysqli);
        return $result;
    }

}


<?php

include_once ('/models/DatabaseConnect.php');

class AdminParameter extends DatabaseConnect
{
    public static function RemoveSelectedParameter($parameter_id)
    {
        $mysqli = parent::connect_to_database();

        $mysqli->query ("DELETE FROM `parameters_list` WHERE `id`='$parameter_id' ");
        $mysqli->query ("DELETE FROM `category_parameters` WHERE `parameter_id`='$parameter_id' ");
        $mysqli->query ("DELETE FROM `parameter_values` WHERE `parameter_id`='$parameter_id' ");

        parent::disconnect_database($mysqli);
    }


    public static function save_new_parameter($parameter_name, $parameter_russian_name, $parameter_unit)
    {
        $mysqli = parent::connect_to_database();

        $mysqli->query ("INSERT INTO `myFirstSite`.`parameters_list` (`name`,`russian_name`, `unit`) VALUES ('$parameter_name ', '$parameter_russian_name', '$parameter_unit')");

        parent::disconnect_database($mysqli);
    }

    public static function get_parameter_information_by_parameter_id($parameter_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT * FROM `myFirstSite`.`parameters_list` WHERE `id`='$parameter_id'");

        $parameters_list = ['id', 'name', 'russian_name', 'unit'];

        $parameter_information = DatabaseConnect::fetch_one_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);

        return $parameter_information;
    }

    public static function update_parameter_information_by_parameter_id($parameter_id, $name, $russian_name, $unit){

        $mysqli = parent::connect_to_database();

        $mysqli->query("UPDATE parameters_list SET `name` = '$name', `russian_name`='$russian_name', `unit`='$unit' WHERE id = '$parameter_id'");

        parent::disconnect_database($mysqli);
    }


    public static function get_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("
        SELECT value FROM parameter_values
        INNER JOIN product_parameter_values
        ON parameter_values.id = product_parameter_values.value_id
        WHERE product_id='$product_id' 
        AND parameter_values.parameter_id='$parameter_id' ");

        $result_array = $result->fetch_assoc();

        $parameter_value=$result_array['value'];

        parent::disconnect_database($mysqli);

        return $parameter_value;
    }

    public static function get_specified_parameters_by_product_id($product_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT * FROM product_parameter_values WHERE product_id='$product_id' ");

        $parameter = 'parameter_id';

        $specified_parameters_list = DatabaseConnect::fetch_array_of_one_parameter($result, $parameter);

        parent::disconnect_database($mysqli);

        return $specified_parameters_list;
    }


}
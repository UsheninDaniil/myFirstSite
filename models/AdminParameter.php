<?php
/**
 * Created by PhpStorm.
 * User: Даня
 * Date: 12.04.2019
 * Time: 15:21
 */

class AdminParameter
{
    public static function RemoveSelectedParameter($parameter_id)
    {
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        $mysqli->query ("DELETE FROM `parameters_list` WHERE `id`='$parameter_id' ");
        $mysqli->close();
    }


    public static function save_new_parameter($parameter_name, $parameter_russian_name, $parameter_unit)
    {
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        $mysqli->query ("INSERT INTO `myFirstSite`.`parameters_list` (`name`,`russian_name`, `unit`) VALUES ('$parameter_name ', '$parameter_russian_name', '$parameter_unit')");

        $mysqli->close();
    }

    public static function get_parameter_information_by_parameter_id($parameter_id)
    {
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        $result = $mysqli->query ("SELECT * FROM `myFirstSite`.`parameters_list` WHERE `id`='$parameter_id'");

        $result_array = $result->fetch_array();

        $parameter_information=[];
        $parameter_information['id']=$result_array['id'];
        $parameter_information['name']=$result_array['name'];
        $parameter_information['russian_name']=$result_array['russian_name'];
        $parameter_information['unit']=$result_array['unit'];

        $mysqli->close();

        return $parameter_information;
    }

    public static function update_parameter_information_by_parameter_id($parameter_id, $name, $russian_name, $unit){

        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        $mysqli->query("UPDATE parameters_list SET `name` = '$name', `russian_name`='$russian_name', `unit`='$unit' WHERE id = '$parameter_id'");

        $mysqli->close();
    }


    public static function get_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id)
    {
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        $result = $mysqli->query ("SELECT value FROM `myFirstSite`.`parameter_values` WHERE `product_id`='$product_id' AND `parameter_id`='$parameter_id' ");

        $result_array = $result->fetch_array();

        $parameter_value=$result_array['value'];

        $mysqli->close();

        return $parameter_value;
    }


}
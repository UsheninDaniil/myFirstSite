<?php

include_once ('/models/DatabaseConnect.php');

class Parameters extends DatabaseConnect
{
    public static function get_category_parameters_list($category_id){

        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT parameters_list.id FROM parameters_list INNER JOIN category_parameters ON category_parameters.parameter_id = parameters_list.id WHERE category_parameters.category_id = '$category_id'");

        $i = 0;
        $parameters_list = array();

        while ($i < $result->num_rows) {
            $row = $result->fetch_array();
            $id = $row['id'];
            array_push($parameters_list, $id);
            $i++;

        }

        parent::disconnect_database($mysqli);
        return $parameters_list;
    }


    public static function get_category_parameters_list_for_category_filter($category_id){

        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT parameters_list.id FROM parameters_list INNER JOIN category_parameters ON category_parameters.parameter_id = parameters_list.id WHERE category_parameters.category_id = '$category_id' AND category_parameters.show_in_filter = '1'");

        $i = 0;
        $parameters_list = array();

        while ($i < $result->num_rows) {
            $row = $result->fetch_array();
            $id = $row['id'];
            array_push($parameters_list, $id);
            $i++;
        }

        parent::disconnect_database($mysqli);
        return $parameters_list;
    }


    public static function get_parameter_name_by_parameter_id($parameter_id){

        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT russian_name FROM parameters_list WHERE  id = '$parameter_id' ");

        $result_array = $result->fetch_array();

        $parameter_name = $result_array['russian_name'];

        parent::disconnect_database($mysqli);
        return $parameter_name;
    }


    public static function get_category_parameter_information_by_category_id_and_parameter_id($category_id, $parameter_id){

        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT show_in_filter, sort_order FROM category_parameters WHERE  category_id = '$category_id' AND parameter_id = '$parameter_id' ");

        $result_array = $result->fetch_array();

        $category_paramter_information=[];

        $category_paramter_information['show_in_filter'] = $result_array['show_in_filter'];
        $category_paramter_information['sort_order'] = $result_array['sort_order'];

        parent::disconnect_database($mysqli);
        return $category_paramter_information;
    }


    public static function get_all_parameters(){

        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT * FROM parameters_list ORDER BY id ASC");

        $i = 0;
        $parameters_list = array();

        while ($i < $result->num_rows) {
            $row = $result->fetch_array();
            $parameters_list[$i]['id'] = $row['id'];
            $parameters_list[$i]['name'] = $row['name'];
            $parameters_list[$i]['russian_name'] = $row['russian_name'];
            $parameters_list[$i]['unit'] = $row['unit'];
            $i++;

        }

        parent::disconnect_database($mysqli);
        return $parameters_list;
    }


    public static function get_most_popular_parameter_values_by_category_id_and_parameter_id($category_id, $parameter_id){

        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT id FROM product WHERE category_id = '$category_id'");

        $i = 0;
        $category_product_id_list = [];

        while ($i < $result->num_rows){
            $row = $result->fetch_array();
            $category_product_id_list[] = $row['id'];
            $i = $i +1;
        }

        $result_2 = $mysqli->query ("SELECT `value`, COUNT(*) FROM `parameter_values` WHERE `product_id` IN (".implode(',',$category_product_id_list).") AND `parameter_id` = '$parameter_id' GROUP BY value ORDER BY 2 DESC");

        $i = 0;

        $most_popular_parameter_values_list=[];

        while ($i < $result_2->num_rows){
            $row = $result_2->fetch_array();
            $most_popular_parameter_values_list[$i]['value'] = $row['value'];
            $most_popular_parameter_values_list[$i]['count'] = $row['COUNT(*)'];
            $i = $i +1;
        }

        parent::disconnect_database($mysqli);
        return $most_popular_parameter_values_list;
    }

}
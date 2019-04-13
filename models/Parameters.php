<?php
/**
 * Created by PhpStorm.
 * User: Даня
 * Date: 18.02.2019
 * Time: 5:35
 */

class Parameters
{

    public static function get_category_parameters_list($category_id){
        //создается новый объект $mysqli
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        //выбрать id и имя и отсортировать по возрастанию и положить в переменную $result
        $result = $mysqli->query ("SELECT category_parameters FROM category WHERE  id = '$category_id' ");

        $result_array = $result->fetch_array();

        $category_parameters = unserialize($result_array['category_parameters']);

        return $category_parameters;

    }

    public static function get_parameter_name_by_parameter_id($parameter_id){
        //создается новый объект $mysqli
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        //выбрать id и имя и отсортировать по возрастанию и положить в переменную $result
        $result = $mysqli->query ("SELECT russian_name FROM parameters_list WHERE  id = '$parameter_id' ");

        $result_array = $result->fetch_array();

        $parameter_name = $result_array['russian_name'];

        return $parameter_name;
    }

    public static function get_all_parameters(){
        //создается новый объект $mysqli
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        //выбрать id и имя и отсортировать по возрастанию и положить в переменную $result
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

        return $parameters_list;

    }

}
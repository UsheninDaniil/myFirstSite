<?php
/**
 * Created by PhpStorm.
 * User: Даня
 * Date: 12.04.2019
 * Time: 15:20
 */

class AdminCategory
{
    public static function delete_parameters_from_category($category_id,$parameter_id)
    {
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        $mysqli->query ("DELETE FROM `category_parameters` WHERE `category_id`='$category_id' AND `parameter_id`='$parameter_id'");

        $mysqli->close();

        echo "<br /><br />id категории $category_id ";
        echo "<br />id параметра $parameter_id";

        echo "<br /><br />была вызвана функция удаления<br />";
    }

    public static function save_selected_existing_parameters($category_id,$save_parameters_list)
    {
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        foreach ($save_parameters_list as $parameter_id){
            $mysqli->query("INSERT INTO category_parameters (`category_id`,`parameter_id`) VALUES ('$category_id ', '$parameter_id')");
        };

        $mysqli->close();

        echo "<br /><br />была вызвана функция сохранения<br />";
    }

    public static function save_new_parameter($category_id, $parameter_name, $parameter_russian_name, $parameter_unit)
    {
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        $mysqli->query ("INSERT INTO `myFirstSite`.`parameters_list` (`name`,`russian_name`, `unit`) VALUES ('$parameter_name ', '$parameter_russian_name', '$parameter_unit')");

        $result = $mysqli->query ("SELECT id FROM parameters_list WHERE  name = '$parameter_name' ");

        $result_array = $result->fetch_array();

        $new_parameter_id = $result_array['id'];

        $mysqli->query("INSERT INTO category_parameters (`category_id`,`parameter_id`) VALUES ('$category_id ', '$new_parameter_id')");
        $mysqli->close();

        echo "<br /><br />была вызвана функция сохранения нового параметра<br />";
    }

}
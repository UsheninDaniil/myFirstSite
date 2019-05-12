<?php

include_once ('/models/DatabaseConnect.php');

class AdminCategory extends DatabaseConnect
{
    public static function delete_parameters_from_category($category_id,$parameter_id)
    {
        $mysqli = parent::connect_to_database();

        $mysqli->query ("DELETE FROM `category_parameters` WHERE `category_id`='$category_id' AND `parameter_id`='$parameter_id'");

        echo "<br /><br />id категории $category_id ";
        echo "<br />id параметра $parameter_id";

        echo "<br /><br />была вызвана функция удаления<br />";

        parent::disconnect_database($mysqli);
    }

    public static function save_selected_existing_parameters($category_id,$save_parameters_list)
    {
        $mysqli = parent::connect_to_database();

        foreach ($save_parameters_list as $parameter_id){
            $mysqli->query("INSERT INTO category_parameters (`category_id`,`parameter_id`) VALUES ('$category_id ', '$parameter_id')");
        };

        echo "<br /><br />была вызвана функция сохранения<br />";

        parent::disconnect_database($mysqli);
    }

    public static function save_new_parameter($category_id, $parameter_name, $parameter_russian_name, $parameter_unit)
    {
        $mysqli = parent::connect_to_database();

        $mysqli->query ("INSERT INTO `myFirstSite`.`parameters_list` (`name`,`russian_name`, `unit`) VALUES ('$parameter_name ', '$parameter_russian_name', '$parameter_unit')");

        $result = $mysqli->query ("SELECT id FROM parameters_list WHERE  name = '$parameter_name' ");

        $result_array = $result->fetch_array();

        $new_parameter_id = $result_array['id'];

        $mysqli->query("INSERT INTO category_parameters (`category_id`,`parameter_id`) VALUES ('$category_id ', '$new_parameter_id')");

        echo "<br /><br />была вызвана функция сохранения нового параметра<br />";

        parent::disconnect_database($mysqli);
    }

}
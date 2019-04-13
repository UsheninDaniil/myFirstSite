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
        $result = $mysqli->query ("SELECT category_parameters FROM category WHERE id = '$category_id' ");

        $result_array = $result->fetch_array();

        $category_parameters = unserialize($result_array['category_parameters']);

        echo "<br /><br />Параметры категории <b>до</b> удаления:<br />";
        print_r($category_parameters);

        $key = array_search("$parameter_id", $category_parameters);

        unset($category_parameters[$key]);

        sort($category_parameters);
        reset($category_parameters);

        echo "<br /><br /> Параметры категории <b>после</b> удаления:<br />";
        print_r($category_parameters);

        $category_parameters=serialize($category_parameters);

        $mysqli->query("UPDATE category SET category_parameters = '$category_parameters' WHERE id = '$category_id'");
        $mysqli->close();

        echo "<br /><br />id категории $category_id ";
        echo "<br />id параметра $parameter_id";

        echo "<br /><br />была вызвана функция удаления<br />";
    }

    public static function save_selected_existing_parameters($category_id,$save_parameters_list)
    {
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");
        $result = $mysqli->query ("SELECT category_parameters FROM category WHERE id = '$category_id' ");

        $result_array = $result->fetch_array();

        $category_parameters = unserialize($result_array['category_parameters']);

        echo "<br /><br />Параметры категории <b>до</b> сохранения:<br />";
        print_r($category_parameters);

        foreach ($save_parameters_list as $parameter_id){
            if(!in_array($parameter_id, $category_parameters)){
                array_push($category_parameters, $parameter_id);
            }
        };

        sort($category_parameters);
        reset($category_parameters);

        echo "<br /><br /> Параметры категории <b>после</b> сохранения:<br />";
        print_r($category_parameters);

        $category_parameters=serialize($category_parameters);

        $mysqli->query("UPDATE category SET category_parameters = '$category_parameters' WHERE id = '$category_id'");
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

        $result = $mysqli->query ("SELECT category_parameters FROM category WHERE id = '$category_id' ");

        $result_array = $result->fetch_array();

        $category_parameters = unserialize($result_array['category_parameters']);

        if(!in_array($new_parameter_id, $category_parameters)){
            array_push($category_parameters, $new_parameter_id);
        }

        sort($category_parameters);
        reset($category_parameters);

        $category_parameters=serialize($category_parameters);

        $mysqli->query("UPDATE category SET category_parameters = '$category_parameters' WHERE id = '$category_id'");
        $mysqli->close();

        echo "<br /><br />была вызвана функция сохранения нового параметра<br />";
    }

}
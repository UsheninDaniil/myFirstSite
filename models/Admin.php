<?php

Class Admin{

    public static function add_new_product($product_information){

        $product_name =   $product_information['product_name'];
        $product_price = $product_information['product_price'];
        $product_description = $product_information['product_description'];
        $product_category = $product_information['product_category'];

        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");
        $mysqli->query ("INSERT INTO `myFirstSite`.`product` (`name`,`description`,`price`, `category_id`) VALUES ('$product_name ', '$product_description', '$product_price', '$product_category')");

        $result = $mysqli->query ("SELECT id FROM product WHERE  name = '$product_name' ");

        $result_array = $result->fetch_array();

        $product_id = $result_array['id'];

        $mysqli->close();

        return $product_id;

    }


    public static function check_user_role($user_id)
    {
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");
        $result = $mysqli->query ("SELECT * FROM `myFirstSite`.`user` WHERE id = '$user_id' ");
        $mysqli->close();

        $user_data = $result->fetch_array();
        $user_role = $user_data["role"];

        return $user_role;
    }


    public static function delete_parameters_from_category($category_id,$parameter_id)
    {
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");
        $result = $mysqli->query ("SELECT category_parameters FROM category WHERE id = '$category_id' ");


        $result_array = $result->fetch_array();

        $category_parameters = unserialize($result_array['category_parameters']);

        echo "Параметры категории до удаления:<br />";
        print_r($category_parameters);

            $key = array_search("$parameter_id", $category_parameters);

            unset($category_parameters[$key]);

        echo "<br /><br /> Параметры категории после удаления:<br />";
        print_r($category_parameters);

        $category_parameters=serialize($category_parameters);

        $mysqli->query("UPDATE category SET category_parameters = '$category_parameters' WHERE id = '$category_id'");
        $mysqli->close();

        echo "<br /><br />id категории $category_id ";
        echo "<br />id параметра $parameter_id";

        echo "<br />была вызвана функция удаления<br />";


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

        echo "<br /><br /> Параметры категории <b>после</b> сохранения:<br />";
        print_r($category_parameters);

        $category_parameters=serialize($category_parameters);

        $mysqli->query("UPDATE category SET category_parameters = '$category_parameters' WHERE id = '$category_id'");
        $mysqli->close();

        echo "<br /><br />была вызвана функция сохранения<br />";

    }

    public static function save_new_parameter($category_id, $parameter_name, $parameter_russian_name)
    {
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        $mysqli->query ("INSERT INTO `myFirstSite`.`parameters_list` (`name`,`russian_name`) VALUES ('$parameter_name ', '$parameter_russian_name')");

        $result = $mysqli->query ("SELECT id FROM parameters_list WHERE  name = '$parameter_name' ");

        $result_array = $result->fetch_array();

        $new_parameter_id = $result_array['id'];

        $result = $mysqli->query ("SELECT category_parameters FROM category WHERE id = '$category_id' ");

        $result_array = $result->fetch_array();

        $category_parameters = unserialize($result_array['category_parameters']);

        if(!in_array($new_parameter_id, $category_parameters)){
            array_push($category_parameters, $new_parameter_id);
        }

        $category_parameters=serialize($category_parameters);

        $mysqli->query("UPDATE category SET category_parameters = '$category_parameters' WHERE id = '$category_id'");
        $mysqli->close();

        echo "<br /><br />была вызвана функция сохранения нового параметра<br />";


    }









}


?>
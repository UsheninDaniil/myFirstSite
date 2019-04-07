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


    public static function save_selected_existing_parameters($category_id,$parameters_list)
    {
//        echo "Список параметров:<br />";
//        print_r($parameters_list);

        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");
//        $result = $mysqli->query ("SELECT * FROM `myFirstSite`.`user` WHERE id = '$user_id' ");
        $mysqli->close();


    }











}


?>
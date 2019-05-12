<?php

include_once ('/models/DatabaseConnect.php');

class AdminProduct extends DatabaseConnect
{
    public static function add_new_product($product_information){

        $product_name =   $product_information['product_name'];
        $product_price = $product_information['product_price'];
        $product_availability = $product_information['product_availability'];
        $product_category = $product_information['product_category'];

        $mysqli = parent::connect_to_database();
        $mysqli->query ("INSERT INTO `myFirstSite`.`product` (`name`,`status`,`price`, `category_id`) VALUES ('$product_name ', '$product_availability', '$product_price', '$product_category')");

        $result = $mysqli->query ("SELECT id FROM product WHERE  name = '$product_name' ");

        $result_array = $result->fetch_array();

        $product_id = $result_array['id'];

        parent::disconnect_database($mysqli);

        return $product_id;
    }

    public static function update_main_product_information_by_product_id($product_id, $product_name, $product_price, $availability){
        $mysqli = parent::connect_to_database();

        $mysqli->query("UPDATE product SET `name` = '$product_name', `price`='$product_price', `status` = '$availability' WHERE id = '$product_id'");

        parent::disconnect_database($mysqli);
    }

    public static function update_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id, $parameter_value){

        $mysqli = parent::connect_to_database();

        $mysqli->query("INSERT INTO `parameter_values`(`product_id`,`parameter_id`,`value`) VALUES ('$product_id', '$parameter_id,', '$parameter_value') ON DUPLICATE KEY UPDATE `value` = '$parameter_value'");

        parent::disconnect_database($mysqli);
    }

    public static function save_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id, $parameter_value){

        $mysqli = parent::connect_to_database();

        $mysqli->query ("INSERT INTO `myFirstSite`.`parameter_values` (`product_id`,`parameter_id`,`value`) VALUES ('$product_id', '$parameter_id', '$parameter_value')");

        parent::disconnect_database($mysqli);
    }

    public static function delete_additional_parameter_from_product($product_id, $parameter_id){

        $mysqli = parent::connect_to_database();

        $mysqli->query ("DELETE FROM `parameter_values` WHERE `product_id`='$product_id' AND `parameter_id`='$parameter_id'");

        parent::disconnect_database($mysqli);
    }

    public static function delete_product($product_id){

        $mysqli = parent::connect_to_database();

        $mysqli->query ("DELETE FROM `product` WHERE id = '$product_id'");

        $mysqli->query ("DELETE FROM `parameter_values` WHERE product_id = '$product_id'");

        if(file_exists(ROOT."/images/$product_id.jpg")){
            unlink(ROOT."/images/$product_id.jpg");
        }

        if(file_exists(ROOT."/images/small_product_images/$product_id.jpg")){
            unlink(ROOT."/images/small_product_images/$product_id.jpg");
        }

        parent::disconnect_database($mysqli);
    }

}
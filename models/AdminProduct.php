<?php
/**
 * Created by PhpStorm.
 * User: Даня
 * Date: 12.04.2019
 * Time: 15:20
 */

class AdminProduct
{
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

    public static function update_main_product_information_by_product_id($product_id,$product_name,$product_price){
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        $mysqli->query("UPDATE product SET `name` = '$product_name', `price`='$product_price' WHERE id = '$product_id'");

        $mysqli->close();
    }

    public static function update_product_information_by_product_id_and_parameter_id($product_id, $parameter_id, $parameter_value){

        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        $mysqli->query("UPDATE parameter_values SET `value` = '$parameter_value' WHERE product_id = '$product_id' AND parameter_id = '$parameter_id'");

        $mysqli->close();
    }

    public static function add_new_existing_parameter_to_product($product_id, $parameter_id, $parameter_value){
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        $mysqli->query ("INSERT INTO `myFirstSite`.`parameter_values` (`product_id`,`parameter_id`,`value`) VALUES ('$product_id', '$parameter_id', '$parameter_value')");

        $mysqli->close();
    }
}
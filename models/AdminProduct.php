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
}
<?php

include_once('/models/DatabaseConnect.php');

class Category extends DatabaseConnect
{
    public static function get_category_list()
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT id, name, sort_order, status FROM category WHERE  status = '1' ORDER BY sort_order, name ASC");

        $parameters_list = ['id', 'name', 'sort_order', 'status'];

        $category_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $category_list;
    }

    public static function get_all_category_list()
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT id, name, sort_order, status FROM category ORDER BY sort_order, name ASC");

        $parameters_list = ['id', 'name', 'sort_order', 'status'];

        $category_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $category_list;
    }


    public static function get_category_name_by_id($category_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM category WHERE  id ='$category_id'");

        $row = $result->fetch_assoc();

        $category_name = ucfirst($row['name']);

        parent::disconnect_database($mysqli);
        return $category_name;
    }

    public static function get_category_id_by_product_id($product_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM product WHERE  id ='$product_id'");

        $row = $result->fetch_assoc();

        $category_id = $row['category_id'];

        parent::disconnect_database($mysqli);
        return $category_id;
    }

    public static function get_main_product_information_after_category_filter_by_product_list($product_id_list_after_filter)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM product WHERE  status = '1' AND id IN (" . implode(',', $product_id_list_after_filter) . ") ORDER BY id, name ASC");

        $parameters_list = ['id', 'name', 'price', 'status', 'rating'];

        $product_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $product_list;
    }

}


?>
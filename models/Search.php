<?php

include_once ('/models/DatabaseConnect.php');

class Search extends DatabaseConnect
{
    public static function get_product_list_for_search($search_query){

        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT * FROM product WHERE  name LIKE '%$search_query%' ORDER BY id, name ASC");

        $parameters_list = ['id', 'name', 'price', 'status'];

        $product_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $product_list;
    }
}
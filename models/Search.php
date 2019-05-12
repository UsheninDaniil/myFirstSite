<?php

include_once ('/models/DatabaseConnect.php');

class Search extends DatabaseConnect
{
    public static function get_product_list_for_search($search_query){

        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT * FROM product WHERE  name LIKE '%$search_query%' ORDER BY id, name ASC");

        $i = 0;
        $product_list = array();

        while ($i < $result->num_rows){
            $row = $result->fetch_array();
            $product_list[$i]['id'] = $row['id'];
            $product_list[$i]['name'] = $row['name'];
            $product_list[$i]['price'] = $row['price'];
            $product_list[$i]['status'] = $row['status'];
            $i++;
        }

        parent::disconnect_database($mysqli);
        return $product_list;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Даня
 * Date: 02.05.2019
 * Time: 23:32
 */

class Search
{
    public static function get_product_list_for_search($search_query){
        //создается новый объект $mysqli
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        //выбрать все и отсортировать по возрастанию и положить в переменную $result
        $result = $mysqli->query ("SELECT * FROM product WHERE  name LIKE '%$search_query%' ORDER BY id, name ASC");

        $mysqli->close();

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

        return $product_list;
    }
}
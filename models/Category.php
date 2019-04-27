<?php

Class Category{

    public static function get_category_list(){

        //создается новый объект $mysqli
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        //выбрать id и имя и отсортировать по возрастанию и положить в переменную $result
        $result = $mysqli->query ("SELECT id, name FROM category WHERE  status = '1' ORDER BY sort_order, name ASC");

        $i = 0;
        $categoryList = array();

        while ($i < $result->num_rows){
            $row = $result->fetch_array();
            $categoryList[$i]['id'] = $row['id'];
            $categoryList[$i]['name'] = $row['name'];
            $i++;
        }
        $mysqli->close();
        return $categoryList;
    }

    public static function  get_category_name_by_id($category_id){

        //создается новый объект $mysqli
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        //выбрать все и отсортировать по возрастанию и положить в переменную $result
        $result = $mysqli->query ("SELECT * FROM category WHERE  id ='$category_id'");

        $row = $result->fetch_array();

        $category_name = ucfirst($row['name']);

        $mysqli->close();
        return $category_name;
    }

    public  static function get_category_id_by_product_id($product_id){

        //создается новый объект $mysqli
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        //выбрать все и отсортировать по возрастанию и положить в переменную $result
        $result = $mysqli->query ("SELECT * FROM product WHERE  id ='$product_id'");

        $row = $result->fetch_array();

        $category_id = $row['category_id'];

        $mysqli->close();

        return $category_id;
    }

    public static function get_main_product_information_after_category_filter_by_product_list($product_list_after_filter){
        //создается новый объект $mysqli
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");

        //выбрать все и отсортировать по возрастанию и положить в переменную $result
        $result = $mysqli->query ("SELECT * FROM product WHERE  status = '1' AND id IN (".implode(',',$product_list_after_filter).") ORDER BY id, name ASC");

        $i = 0;
        $productList = array();

        while ($i < $result->num_rows){
            $row = $result->fetch_array();
            $productList[$i]['id'] = $row['id'];
            $productList[$i]['name'] = $row['name'];
            $productList[$i]['price'] = $row['price'];
            $productList[$i]['status'] = $row['status'];
            $i++;
        }
        $mysqli->close();

        return $productList;
    }

}


?>
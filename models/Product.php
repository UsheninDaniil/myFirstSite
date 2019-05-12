<?php

include_once ('/models/DatabaseConnect.php');

class Product extends DatabaseConnect
{
    public static function get_product_list()
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT * FROM product WHERE  status = '1' ORDER BY id, name ASC");

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

        parent::disconnect_database($mysqli);
        return $productList;
    }


    public static function get_product_list_by_category_id($category_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT * FROM product WHERE  category_id = '$category_id' ORDER BY id, name ASC");

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

        parent::disconnect_database($mysqli);
        return $productList;
    }


    public static function get_product_id(){
        $uri=$_SERVER['REQUEST_URI'];
        $segments = explode('/',$uri);
        $product_id=$segments[2];
        return $product_id;
    }


    public static function get_product_parameters_by_id($id = []){

        if (isset($id)){
            $product_id=$id;
        }
        else
        {
        $product_id=self::get_product_id();
        }

        $product_id=(int)$product_id;

        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT parameter_id,value FROM parameter_values WHERE  product_id ='$product_id'");

        $i = 0;
        $ParameterValuesList = array();

        while ($i < $result->num_rows){

            $row = $result->fetch_array();
            $parameter_id = $row['parameter_id'];
            $parameter_name=self::get_parameter_name_by_parameter_id($parameter_id);
            $ParameterValuesList[$parameter_name] = $row['value'];
            $i++;
        }

        parent::disconnect_database($mysqli);
        return $ParameterValuesList;
    }


    public static function get_parameter_name_by_parameter_id($parameter_id){

        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT russian_name FROM parameters_list WHERE  id ='$parameter_id'");

        $row = $result->fetch_array();

        $parameter_name=$row['russian_name'];

        parent::disconnect_database($mysqli);
        return $parameter_name;
    }


    public static function get_product_by_id($product_id){

        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT * FROM product WHERE  id = '$product_id'");

            $row = $result->fetch_array();

            $productInfo['id'] = $row['id'];
            $productInfo['name'] = $row['name'];
            $productInfo['price'] = $row['price'];
            $productInfo['status'] = $row['status'];
            $productInfo['category_id'] = $row['category_id'];

        parent::disconnect_database($mysqli);
        return $productInfo;
    }


    public static function get_compare_parameters_list_by_product_list($product_list){

        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT parameter_id FROM parameter_values WHERE product_id IN (".implode(',',$product_list).") GROUP BY parameter_id");

        $i = 0;
        $parameters_list = array();

        while ($i < $result->num_rows){
            $row = $result->fetch_array();
            $parameter_id = $row['parameter_id'];
            array_push($parameters_list, $parameter_id);
            $i++;
        }

        parent::disconnect_database($mysqli);
        return $parameters_list;
    }
}




?>
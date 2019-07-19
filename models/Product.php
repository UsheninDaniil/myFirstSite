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


    public static function get_product_list_by_category_id($category_id, $start, $num)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT * FROM product WHERE  category_id = '$category_id' ORDER BY id, name ASC LIMIT $start, $num");

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


    public static function save_review($review_information){

        $mysqli = parent::connect_to_database();

        $product_id = $review_information['product_id'];
        $user_id = $review_information['user_id'];
        $text_review = $review_information['text_review'];
        $rating = $review_information['rating'];
        $date = $review_information['date'];
        $time = $review_information['time'];

        $mysqli->query ("INSERT INTO `product_reviews` (`product_id`, `user_id`, `review`, `rating`, `date`, `time`) VALUES ('$product_id', '$user_id', '$text_review', '$rating', '$date', '$time')");

        parent::disconnect_database($mysqli);

    }

    public static function update_review($review_information){

        $mysqli = parent::connect_to_database();

        $product_id = $review_information['product_id'];
        $user_id = $review_information['user_id'];
        $text_review = $review_information['text_review'];
        $rating = $review_information['rating'];
        $date = $review_information['date'];
        $time = $review_information['time'];

        $mysqli->query ("UPDATE `product_reviews` SET `review`='$text_review', `rating`='$rating', `date`='$date', `time`='$time' WHERE `product_id`='$product_id' AND `user_id`='$user_id' ");

        parent::disconnect_database($mysqli);

    }

    public static function delete_review($product_id, $user_id){

        $mysqli = parent::connect_to_database();

        $mysqli->query ("DELETE FROM `product_reviews` WHERE `product_id`='$product_id' AND `user_id`='$user_id' ");

        parent::disconnect_database($mysqli);

    }

    public static function get_product_review_list_by_product_id($product_id){

        $mysqli = parent::connect_to_database();
        $result = $mysqli->query ("SELECT * FROM product_reviews WHERE `product_id` = '$product_id'");
        $i = 0;
        $product_review_list = array();

        while ($i < $result->num_rows){
            $row = $result->fetch_array();
            $product_review_list[$i]['product_id'] = $row['product_id'];
            $product_review_list[$i]['user_id'] = $row['user_id'];
            $product_review_list[$i]['review'] = $row['review'];
            $product_review_list[$i]['rating'] = $row['rating'];
            $product_review_list[$i]['date'] = $row['date'];
            $product_review_list[$i]['time'] = $row['time'];
            $i++;
        }

        parent::disconnect_database($mysqli);
        return $product_review_list;
    }

    public static function get_product_review_by_product_id_and_user_id($product_id, $user_id){

        $mysqli = parent::connect_to_database();
        $result = $mysqli->query ("SELECT * FROM product_reviews WHERE `product_id` = '$product_id' AND `user_id` = '$user_id' LIMIT 1");
        $review_information = array();
        $row = $result->fetch_array();

        if(!empty($row)){
        $review_information['product_id'] = $row['product_id'];
        $review_information['user_id'] = $row['user_id'];
        $review_information['review'] = $row['review'];
        $review_information['rating'] = $row['rating'];
        $review_information['date'] = $row['date'];
        $review_information['time'] = $row['time'];
        }

        parent::disconnect_database($mysqli);
        return $review_information;
    }

    public static function check_review_exist($product_id, $user_id){

        $mysqli = parent::connect_to_database();
        $result = $mysqli->query ("SELECT * FROM product_reviews WHERE `product_id` = '$product_id' AND `user_id` = '$user_id' LIMIT 1");
        $review_information = array();
        $row = $result->fetch_array();

        if(!empty($row)){
            $result = true;
        } else{
            $result = false;
        }
        return $result;
    }

}




?>
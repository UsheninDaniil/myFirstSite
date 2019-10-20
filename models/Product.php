<?php

include_once('/models/DatabaseConnect.php');

class Product extends DatabaseConnect
{
    public static function get_product_list()
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM product WHERE  status = '1' ORDER BY id, name ASC");

        $parameters_list = ['id', 'name', 'price', 'status', 'rating'];

        $productList = parent::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $productList;
    }


    public static function get_product_list_by_category_id($category_id, $start, $num)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM product WHERE  category_id = '$category_id' ORDER BY id, name ASC LIMIT $start, $num");

        $parameters_list = ['id', 'name', 'price', 'status', 'rating'];

        $product_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $product_list;
    }


    public static function get_product_id()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $segments = explode('/', $uri);
        $product_id = $segments[2];
        return $product_id;
    }


    public static function get_product_parameters_by_id($id = [])
    {
        if (isset($id)) {
            $product_id = $id;
        } else {
            $product_id = self::get_product_id();
        }

        $product_id = (int)$product_id;

        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("
        SELECT product_parameter_values.parameter_id, value, name, russian_name 
        FROM parameter_values
        INNER JOIN product_parameter_values ON parameter_values.id = product_parameter_values.value_id
        INNER JOIN parameters_list ON parameter_values.parameter_id = parameters_list.id
        WHERE  product_id ='$product_id'
        ");

        $parameters_list = ['name', 'value', 'russian_name'];

        $product_parameters_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        foreach ($product_parameters_list as $product_parameter){
            $parameter_name = $product_parameter['russian_name'];
            $parameter_value = $product_parameter['value'];
            $parameter_values_list[$parameter_name] = $parameter_value;
        }

        parent::disconnect_database($mysqli);

        return $parameter_values_list;
    }


    public static function get_parameter_name_by_parameter_id($parameter_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT russian_name FROM parameters_list WHERE  id ='$parameter_id'");

        $row = $result->fetch_assoc();

        $parameter_name = $row['russian_name'];

        parent::disconnect_database($mysqli);
        return $parameter_name;
    }


    public static function get_product_by_id($product_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM product WHERE  id = '$product_id'");

        $parameters_list = ['id', 'name', 'price', 'status', 'category_id'];

        $product_info = DatabaseConnect::fetch_one_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $product_info;
    }


    public static function get_compare_parameters_list_by_product_list($product_list)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT parameter_id FROM product_parameter_values WHERE product_id IN (" . implode(',', $product_list) . ") GROUP BY parameter_id");

        $parameter = 'parameter_id';

        $compare_parameters_list = DatabaseConnect::fetch_array_of_one_parameter($result, $parameter);

        parent::disconnect_database($mysqli);
        return $compare_parameters_list;
    }


    public static function save_review($review_information)
    {
        $mysqli = parent::connect_to_database();

        $product_id = $review_information['product_id'];
        $user_id = $review_information['user_id'];
        $text_review = $review_information['text_review'];
        $rating = $review_information['rating'];
        $date = $review_information['date'];
        $time = $review_information['time'];

        $mysqli->query("INSERT INTO `product_reviews` (`product_id`, `user_id`, `review`, `rating`, `date`, `time`) VALUES ('$product_id', '$user_id', '$text_review', '$rating', '$date', '$time')");

        parent::disconnect_database($mysqli);

    }

    public static function update_review($review_information)
    {
        $mysqli = parent::connect_to_database();

        $product_id = $review_information['product_id'];
        $user_id = $review_information['user_id'];
        $text_review = $review_information['text_review'];
        $rating = $review_information['rating'];
        $date = $review_information['date'];
        $time = $review_information['time'];

        $mysqli->query("UPDATE `product_reviews` SET `review`='$text_review', `rating`='$rating', `date`='$date', `time`='$time' WHERE `product_id`='$product_id' AND `user_id`='$user_id' ");

        parent::disconnect_database($mysqli);

    }

    public static function delete_review($product_id, $user_id)
    {
        $mysqli = parent::connect_to_database();

        $mysqli->query("DELETE FROM `product_reviews` WHERE `product_id`='$product_id' AND `user_id`='$user_id' ");

        parent::disconnect_database($mysqli);

    }

    public static function get_product_review_list_by_product_id($product_id)
    {
        $mysqli = parent::connect_to_database();
        $result = $mysqli->query("SELECT * FROM product_reviews WHERE `product_id` = '$product_id'");

        $parameters_list = ['id', 'product_id', 'user_id', 'review', 'rating', 'date', 'time'];

        $product_review_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $product_review_list;
    }

    public static function get_product_review_by_product_id_and_user_id($product_id, $user_id)
    {
        $mysqli = parent::connect_to_database();
        $result = $mysqli->query("SELECT * FROM product_reviews WHERE `product_id` = '$product_id' AND `user_id` = '$user_id' LIMIT 1");

        $parameters_list = ['id', 'product_id', 'user_id', 'review', 'rating', 'date', 'time'];

        $review_information = DatabaseConnect::fetch_one_dimensional_array($result, $parameters_list);

        if(empty($review_information)){
            $review_information = null;
        }

        parent::disconnect_database($mysqli);
        return $review_information;
    }

    public static function check_review_exist($product_id, $user_id)
    {
        $mysqli = parent::connect_to_database();
        $result = $mysqli->query("SELECT * FROM product_reviews WHERE `product_id` = '$product_id' AND `user_id` = '$user_id' LIMIT 1");
        $review_information = array();
        $row = $result->fetch_assoc();

        if (!empty($row)) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    public static function save_review_vote($review_id, $user_id, $vote)
    {
        $mysqli = parent::connect_to_database();

        $query = "
        INSERT INTO review_rating(review_id, user_id, vote) 
        VALUES ('$review_id', '$user_id,', '$vote') 
        ON DUPLICATE KEY 
        UPDATE `vote` = '$vote'
        ";

        $mysqli->query($query);
        parent::disconnect_database($mysqli);
    }

    public static function delete_review_vote($review_id, $user_id, $vote)
    {
        $mysqli = parent::connect_to_database();

        $query = "
        DELETE FROM review_rating
        WHERE review_id = '$review_id' 
        AND user_id = '$user_id'
        AND vote = '$vote'
        ";

        $mysqli->query($query);
        parent::disconnect_database($mysqli);
    }


    public static function get_likes_list_by_user_id_and_product_id($user_id, $product_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("
        SELECT * 
        FROM review_rating
        INNER JOIN product_reviews ON review_rating.review_id = product_reviews.id
        WHERE review_rating.user_id = '$user_id' AND product_reviews.product_id = '$product_id'
        ");

        $parameters_list = ['review_id', 'user_id', 'vote'];

        $review_likes_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $review_likes_list;
    }

    public static function get_rating_of_review($review_id)
    {
        $mysqli = parent::connect_to_database();

        $result_1 = $mysqli->query("
        SELECT COUNT(*) AS likes
        FROM review_rating
        WHERE vote = '1' AND review_id = '$review_id'
        ");

        $row_1 = $result_1->fetch_assoc();

        $likes_amount = $row_1['likes'];

        $result_2 = $mysqli->query("
        SELECT COUNT(*) AS dislikes
        FROM review_rating
        WHERE vote = '0' AND review_id = '$review_id'
        ");

        $row_2 = $result_2->fetch_assoc();

        $dislikes_amount = $row_2['dislikes'];

        $rating_of_product_review = ['likes_amount'=> $likes_amount, 'dislikes_amount'=>$dislikes_amount];

        parent::disconnect_database($mysqli);

        return $rating_of_product_review;
    }

    public static function save_review_comment($user_id, $review_id, $text, $current_date, $current_time)
    {
        $mysqli = parent::connect_to_database();

        $query = "
        INSERT INTO review_comments(review_id, user_id, comment, date, time) 
        VALUES ('$review_id', '$user_id', '$text', '$current_date', '$current_time') 
        ";

        $mysqli->query($query);

        parent::disconnect_database($mysqli);
    }

    public static function get_review_comments_range($review_id, $already_loaded, $amount)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("
        SELECT * FROM review_comments
        WHERE review_id = '$review_id'
        ORDER BY id DESC
        LIMIT $already_loaded, $amount
        ");

        $parameters_list = ['id', 'review_id', 'user_id', 'comment', 'date', 'time'];

        $review_comments = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        $review_comments = array_reverse($review_comments);

        parent::disconnect_database($mysqli);

        return $review_comments;

    }

    public static function get_review_comments_count($review_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("
        SELECT COUNT(*) AS count FROM review_comments
        WHERE review_id = '$review_id'
        ORDER BY id ASC
        ");

        $row = $result->fetch_assoc();

        $comments_count = $row['count'];

        parent::disconnect_database($mysqli);

        return $comments_count;
    }

}


?>
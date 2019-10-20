<?php
/**
 * Created by PhpStorm.
 * User: Даня
 * Date: 21.07.2019
 * Time: 16:47
 */

include_once('/models/DatabaseConnect.php');


class AdminReview extends DatabaseConnect
{
    public static function get_review_list()
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM `product_reviews`");

        $parameters_list = ['id', 'product_id', 'user_id', 'review', 'rating', 'date', 'time'];

        $review_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $review_list;
    }

    public static function delete_review_by_id($review_id)
    {
        $mysqli = parent::connect_to_database();

        $mysqli->query("DELETE FROM `product_reviews` WHERE `id`='$review_id'");

        parent::disconnect_database($mysqli);
    }

}
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

    public static function build_request_for_filter($filter_parameters)
    {
        $united_request = '';

        foreach ($filter_parameters as $parameter_name => $parameter_value) {

            if (!empty($parameter_value)) {
                $request_first_part = "SELECT * FROM product_reviews WHERE ";

                if ($parameter_name === "product_id") {
                    $request_second_part = "$parameter_name = '$parameter_value'";
                }
                if ($parameter_name === "user_id") {
                    $request_second_part = "$parameter_name = '$parameter_value'";
                }
                if ($parameter_name === "rating") {
                    $request_second_part = "$parameter_name = '$parameter_value'";
                }
                if ($parameter_name === "sorting") {
                    if($parameter_value == "ascending"){
                        $order_by = " ORDER BY rating ASC";
                    }
                    if($parameter_value == "descending"){
                        $order_by = " ORDER BY rating DESC";
                    }
                    continue;
                }

                if ((strlen($united_request) < 1) && (!empty($request_second_part))) {
                    $united_request = $united_request . $request_first_part . $request_second_part;
                } else {
                    $united_request = $united_request . ' AND ' . $request_second_part;
                }
            }
        }

        if(!empty($order_by)){
            if(strlen($united_request) > 0){
                $united_request = $united_request.$order_by;
            } else{
                $united_request = "SELECT * FROM product_reviews".$order_by;
            }
        }

        return $united_request;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Даня
 * Date: 27.10.2019
 * Time: 22:55
 */

class AdminOrder extends DatabaseConnect
{
    public static function get_orders_list(){
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM orders ORDER BY id");

        $parameters_list = ['id', 'user_id' ,'date', 'time', 'status'];

        $orders_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $orders_list;
    }

    public static function get_order_information($order_id){
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("
        SELECT * FROM orders WHERE id = '$order_id'");

        $parameters_list = ['id', 'user_id' , 'date', 'time', 'status'];

        $order_information = DatabaseConnect::fetch_one_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $order_information;
    }

    public static function get_product_list_by_order_id($order_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM orders INNER JOIN orders_information ON orders.id = orders_information.order_id WHERE order_id = '$order_id' ORDER BY orders.id");

        $parameters_list = ['product_id', 'color_id', 'product_amount', 'product_price'];

        $product_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $product_list;
    }

    public static function get_order_list_by_user_id($user_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM orders  WHERE user_id = '$user_id' ORDER BY id");

        $parameters_list = ['id', 'user_id', 'date', 'time', 'status'];

        $order_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $order_list;
    }

    public static function delete_order($order_id)
    {
        $mysqli = parent::connect_to_database();

        $mysqli->query("DELETE FROM orders WHERE id ='$order_id' ");
        $mysqli->query("DELETE FROM orders_information WHERE order_id ='$order_id' ");

        parent::disconnect_database($mysqli);
    }
}
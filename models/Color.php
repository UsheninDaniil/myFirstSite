<?php

include_once('/models/DatabaseConnect.php');

class Color extends DatabaseConnect
{
    public static function get_product_colors($product_id)
    {
        $mysqli = parent::connect_to_database();

        $request = "
        SELECT product_colors.color_id, colors.name, colors.hex_code, product_colors.amount 
        FROM product_colors 
        INNER JOIN product ON product.id = product_colors.product_id
        INNER JOIN colors ON colors.id = product_colors.color_id  
        WHERE  product_colors.product_id = '$product_id'
        ";

        $result = $mysqli->query($request);

        $parameters_list = ['color_id', 'amount', 'name', 'hex_code'];

        $product_colors = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);

        return $product_colors;
    }

    public static function get_colors_list()
    {
        $mysqli = parent::connect_to_database();

        $request = "SELECT * FROM colors WHERE id > '1'";

        $result = $mysqli->query($request);

        $parameters_list = ['id', 'name', 'hex_code'];

        $colors_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);

        return $colors_list;
    }


    public static function save_color_product_amount($product_id, $color_id, $product_amount)
    {
        $mysqli = parent::connect_to_database();

        $mysqli->query("INSERT INTO product_colors (product_id, color_id, amount) VALUES ('$product_id', '$color_id' , '$product_amount')");

        parent::disconnect_database($mysqli);
    }

    public static function get_not_selected_product_colors_list($product_id)
    {
        $mysqli = parent::connect_to_database();

        $request = "
        SELECT colors.id AS id, colors.name AS name, colors.hex_code AS hex_code FROM colors
        INNER JOIN product_colors
        ON colors.id = product_colors.color_id
        WHERE product_id = '$product_id'
        ";

        $result = $mysqli->query($request);

        $parameters_list = ['id', 'name', 'hex_code'];

        $product_colors_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        $all_colors_list = Color::get_colors_list();

        $not_selected_product_colors_list = array();

        function udiffCompare($a, $b)
        {
            return $a['id'] - $b['id'];
        }

        $not_selected_product_colors_list = array_udiff($all_colors_list, $product_colors_list, 'udiffCompare');

        parent::disconnect_database($mysqli);

        return $not_selected_product_colors_list;
    }

    public static function update_product_amount_by_product_id_and_color_id($product_id, $color_id, $product_amount)
    {
        $mysqli = parent::connect_to_database();

        $mysqli->query("
        INSERT INTO product_colors(product_id, color_id, amount) 
        VALUES ('$product_id', '$color_id,', '$product_amount') 
        ON DUPLICATE KEY UPDATE amount = '$product_amount'");

        parent::disconnect_database($mysqli);
    }

    public static function delete_product_color($product_id, $color_id)
    {
        $mysqli = parent::connect_to_database();

        $mysqli->query("DELETE FROM product_colors WHERE product_id = '$product_id' AND color_id = '$color_id'");

        parent::disconnect_database($mysqli);
    }

    public static function get_hex_code_by_color_id($color_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM colors WHERE id = '$color_id'");

        $row = $result->fetch_assoc();

        $hex_code = $row['hex_code'];

        parent::disconnect_database($mysqli);

        return $hex_code;
    }

    public static function check_is_there_ability_to_choose_the_color($product_id)
    {
        $color_product_amount = Color::get_product_colors($product_id);

        if(in_array('no_color', array_column($color_product_amount, 'name'))){
            $ability_to_choose_the_color = 'false';
        } else{
            $ability_to_choose_the_color = 'true';
        }

        return $ability_to_choose_the_color;
    }

}
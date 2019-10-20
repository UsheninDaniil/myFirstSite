<?php

include_once('/models/DatabaseConnect.php');

class AdminProduct extends DatabaseConnect
{
    public static function add_new_product($product_information)
    {
        $product_name = $product_information['product_name'];
        $product_price = $product_information['product_price'];
        $product_availability = $product_information['product_availability'];
        $product_category = $product_information['product_category'];

        $mysqli = parent::connect_to_database();
        $mysqli->query("INSERT INTO `myFirstSite`.`product` (`name`,`status`,`price`, `category_id`) VALUES ('$product_name ', '$product_availability', '$product_price', '$product_category')");

        $result = $mysqli->query("SELECT id FROM product WHERE  name = '$product_name' ");

        $row = $result->fetch_assoc();

        $product_id = $row['id'];

        parent::disconnect_database($mysqli);

        return $product_id;
    }

    public static function update_main_product_information_by_product_id($product_id, $product_name, $product_price, $availability)
    {
        $mysqli = parent::connect_to_database();

        $mysqli->query("UPDATE product SET `name` = '$product_name', `price`='$product_price', `status` = '$availability' WHERE id = '$product_id'");

        parent::disconnect_database($mysqli);
    }

    public static function update_value_id_of_product_parameter($product_id, $parameter_id, $new_value_id)
    {
        $mysqli = parent::connect_to_database();

        $mysqli->query("
        INSERT INTO product_parameter_values(product_id,parameter_id,value_id) 
        VALUES ('$product_id', '$parameter_id,', '$new_value_id') 
        ON DUPLICATE KEY UPDATE value_id = '$new_value_id'");

        parent::disconnect_database($mysqli);
    }

    public static function delete_additional_parameter_from_product($product_id, $parameter_id)
    {
        $mysqli = parent::connect_to_database();

        $mysqli->query("DELETE FROM `parameter_values` WHERE `product_id`='$product_id' AND `parameter_id`='$parameter_id'");

        parent::disconnect_database($mysqli);
    }

    public static function delete_product($product_id)
    {
        $mysqli = parent::connect_to_database();

        $mysqli->query("DELETE FROM product WHERE id = '$product_id'");

        $mysqli->query("DELETE FROM product_parameter_values WHERE product_id = '$product_id'");

        $mysqli->query("DELETE FROM product_photos WHERE product_id = '$product_id'");

        $mysqli->query("DELETE FROM product_colors WHERE product_id = '$product_id'");

        $mysqli->query("
        DELETE review_comments
        FROM review_comments
        INNER JOIN product_reviews
        ON product_reviews.id = review_comments.review_id
        WHERE product_id = '$product_id'");

        $mysqli->query("DELETE FROM product_reviews WHERE product_id = '$product_id'");

        $mysqli->query("
        DELETE review_rating
        FROM review_rating 
        INNER JOIN product_reviews
        ON product_reviews.id = review_rating.review_id
        WHERE product_id = '$product_id'");

        $mysqli->query("DELETE FROM orders_information WHERE product_id = '$product_id'");

        if (file_exists(ROOT . "/images/$product_id.jpg")) {
            unlink(ROOT . "/images/$product_id.jpg");
        }

        if (file_exists(ROOT . "/images/small_product_images/$product_id.jpg")) {
            unlink(ROOT . "/images/small_product_images/$product_id.jpg");
        }

        parent::disconnect_database($mysqli);
    }

    public static function resize_and_save_product_photo($filename, $height_orig, $width_orig, $max_height, $max_width, $ratio_orig, $new_path)
    {
        if ($height_orig > $max_height) {
            $height = $max_height;
            $width = $height * $ratio_orig;
        } else {
            $height = $height_orig;
            $width = $height * $ratio_orig;
        }

        // если получившаяся ширина больше максимальной - пропорционально уменьшить фотографию до допустимой ширины
        if ($width > $max_width) {
            $width = $max_width;
            $height = $width / $ratio_orig;
        }

        // ресэмплирование
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($filename);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        // сохранение
        imagejpeg($image_p, $new_path, 100);
    }

    public static function get_autocomplete_for_parameter_value($search_query, $parameter_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM parameter_values WHERE  parameter_id = '$parameter_id' AND value LIKE '%$search_query%'");

        $parameters_list = ['value'];

        $autocomplete_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $autocomplete_list;

    }

}
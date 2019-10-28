<?php

include_once('/models/DatabaseConnect.php');

class AdminProduct extends DatabaseConnect
{
    public static function save_new_product($post, $files)
    {
        $product_information['product_name'] = $post['product_name'];
        $product_information['product_price'] = $post['product_price'];
        $product_information['product_availability'] = $post['product_availability'];
        $product_information['product_category'] = $post['product_category_id'];

        $product_id = AdminProduct::save_new_product_main_information($product_information);

        if (!empty($files["images"]) && !empty($post['image_names'])) {
            Images::save_product_photos_for_a_new_product($product_id, $files, $post);
        }

        if (isset($post["category_parameters"])) {
            $category_parameters = $post["category_parameters"];
            AdminProduct::save_new_product_category_parameters($product_id, $category_parameters);
        }

        AdminProduct::save_new_product_colors($product_id, $post);
    }

    public static function save_new_product_main_information($product_information)
    {
        $product_name = $product_information['product_name'];
        $product_price = $product_information['product_price'];
        $product_availability = $product_information['product_availability'];
        $product_category = $product_information['product_category'];

        $mysqli = parent::connect_to_database();
        $mysqli->query("INSERT INTO product (name, status, price, category_id) VALUES ('$product_name ', '$product_availability', '$product_price', '$product_category')");

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

    public static function get_autocomplete_for_parameter_value($search_query, $parameter_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM parameter_values WHERE  parameter_id = '$parameter_id' AND value LIKE '%$search_query%'");

        $parameters_list = ['value'];

        $autocomplete_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $autocomplete_list;

    }

    public static function save_new_product_category_parameters($product_id, $category_parameters)
    {
        foreach ($category_parameters as $parameter_id => $value) {
            if (!empty ($value)) {
                $parameter_value_exist = Parameters::check_if_value_exist($parameter_id, $value);
                if ($parameter_value_exist === true) {
                    $value_id = Parameters::get_value_id($value, $parameter_id);
                } else {
                    $value_id = Parameters::create_new_value_id($value, $parameter_id);
                }
                Parameters::add_new_product_parameter_value($product_id, $parameter_id, $value_id);
            }
        }
    }

    public static function save_new_product_colors($product_id, $post)
    {
        if ($_POST['availability_to_choose_the_color'] === '1') {
            foreach ($_POST['color'] as $color_id => $product_amount) {
                if ($product_amount > 0) {
                    Color::save_color_product_amount($product_id, $color_id, $product_amount);
                }
            }
        } else {
            $color_id = 1;
            $product_amount = $_POST['no_color_amount'];
            if ($product_amount > 0) {
                Color::save_color_product_amount($product_id, $color_id, $product_amount);
            }
        }
    }

    public static function build_request_for_filter($filter_parameters)
    {
        $united_request = '';
        foreach ($filter_parameters as $parameter_name => $parameter_content) {

            if (gettype($parameter_content) == "array") {

                $parameter_values_array = $parameter_content;

                if (!empty($parameter_values_array)) {
                    $request_first_part = "SELECT * FROM product WHERE ";
                    $request_second_part = "$parameter_name  IN (" . implode(',', $parameter_values_array) . ")";

                    if (strlen($united_request) < 1) {
                        $united_request = $united_request . $request_first_part . $request_second_part;
                    } else {
                        $united_request = $united_request . ' AND ' . $request_second_part;
                    }
                }
            } else {
                $parameter_value = $parameter_content;
                if (!empty($parameter_value)) {
                    $request_first_part = "SELECT * FROM product WHERE ";

                    if ($parameter_name === "id") {
                        $request_second_part = "$parameter_name = '$parameter_value'";
                    }

                    if ($parameter_name === "name") {
                        $request_second_part = "$parameter_name LIKE '%$parameter_value%'";
                    }

                    if ((strlen($united_request) < 1) && (!empty($request_second_part))) {
                        $united_request = $united_request . $request_first_part . $request_second_part;
                    } else {
                        $united_request = $united_request . ' AND ' . $request_second_part;
                    }
                }
            }
        }
        return $united_request;
    }

    public static function update_product_information($product_id, $post, $original_color_id_list)
    {
        if (isset($post['product_name'], $post['product_price'], $post['availability'])) {
            $product_name = $post['product_name'];
            $product_price = $post['product_price'];
            $availability = $post['availability'];
            AdminProduct::update_main_product_information_by_product_id($product_id, $product_name, $product_price, $availability);
        }

        if (isset($post['dynamic_parameters'])) {

            foreach ($post['dynamic_parameters'] as $parameter_id => $value) {

                $is_value_exist = Parameters::check_if_value_exist($parameter_id, $value);

                if ($is_value_exist === true) {
                    $value_id = Parameters::get_value_id($value, $parameter_id);
                } else {
                    $value_id = Parameters::create_new_value_id($value, $parameter_id);
                }

                AdminProduct::update_value_id_of_product_parameter($product_id, $parameter_id, $value_id);
            }
        }

        if (isset($post['new_dynamic_parameters'])) {

            foreach ($post['new_dynamic_parameters'] as $parameter_id => $value) {
                if (!empty ($parameter_value)) {

                    $is_value_exist = Parameters::check_if_value_exist($parameter_id, $value);

                    if ($is_value_exist === true) {
                        $value_id = Parameters::get_value_id($value, $parameter_id);
                    } else {
                        $value_id = Parameters::create_new_value_id($value, $parameter_id);
                    }

                    Parameters::add_new_product_parameter_value($product_id, $parameter_id, $value_id);
                }
            }
        }

        if (isset($post['color'])) {
            $new_color_id_list = array();
            foreach ($post['color'] as $color_id => $product_amount) {
                array_push($new_color_id_list, "$color_id");
                if ($product_amount > 0) {
                    Color::update_product_amount_by_product_id_and_color_id($product_id, $color_id, $product_amount);
                } elseif ($product_amount <= 0) {
                    Color::delete_product_color($product_id, $color_id);
                }
            }
        } else {
            $new_color_id_list = array();
            array_push($new_color_id_list, 0);
        }

        $deleted_color_id_list = array_diff($original_color_id_list, $new_color_id_list);
        foreach ($deleted_color_id_list as $color_id) {
            Color::delete_product_color($product_id, $color_id);
        }
    }

}
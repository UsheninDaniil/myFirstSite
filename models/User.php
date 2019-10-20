<?php

include_once('/models/DatabaseConnect.php');

class User extends DatabaseConnect
{
    public static function action_authorization($email, $password)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM `myFirstSite`.`user` WHERE email = '$email' AND password = '$password' ");
        $user_data = $result->fetch_array();
        if ($result->num_rows == 1) {
            $_SESSION["login"] = $user_data["login"];
            $_SESSION["logged_in"] = true;
            $_SESSION["user_id"] = $user_data["id"];
            header("Location: /cabinet");
        } else {
            echo "Неверный логин или праоль";
            $_SESSION["logged_in"] = false;
        }
        parent::disconnect_database($mysqli);
    }


    public static function action_check_authorization()
    {
        if (isset($_SESSION['logged_in'])) {
            if ($_SESSION["logged_in"] == true) {
                $authorization_result = true;
            }
        } else {
            $authorization_result = false;
        }
        return $authorization_result;
    }


    public static function check_login_form($email, $password)
    {
        $error_email = "";
        $error_password = "";
        $error = false;

        if ($email == "") {
            $error_email = "Введите email";
            $error = true;
        }
        if ($password == "") {
            $error_password = "Введите пароль";
            $error = true;
        }
        return array($error, $error_email, $error_password);
    }


    public static function action_register_new($login, $password, $email)
    {
        $mysqli = parent::connect_to_database();

        $mysqli->query("INSERT INTO `myFirstSite`.`user` (`login`,`password`,`email`) VALUES ('$login', '$password', '$email')");

        parent::disconnect_database($mysqli);
    }


    public static function check_register_form($login, $password, $email)
    {
        $error_login = "";
        $error_password = "";
        $error_email = "";
        $error = false;

        if ($login == "") {
            $error_login = "Введите коррекный логин";
            $error = true;
        }
        if ($password == "") {
            $error_password = "Введите коррекный пароль";
            $error = true;
        }
        if ($email == "") {
            $error_email = "Введите корректный email";
            $error = true;
        }
        return array(
            "error" => $error,
            "error_login" => $error_login,
            "error_password" => $error_password,
            "error_email" => $error_email);
    }


    public static function check_feedback_form($from, $to, $subject, $message)
    {
        $error_from = "";
        $error_to = "";
        $error_subject = "";
        $error_message = "";
        $error = false;

        if ($from == "" || !preg_match("/@/", $from)) {
            $error_from = "Введите коррекный email";
            $error = true;
        }

        if ($to == "" || !preg_match("/@/", $to)) {
            $error_to = "Введите коррекный email";
            $error = true;
        }

        if (strlen($subject) < 3) {
            $error_subject = "Тема не может быть меньше 3 символов";
            $error = true;
        }

        if (strlen($message) < 1) {
            $error_message = "Напишите сообщение";
            $error = true;
        }

        if (!$error) {
            $subject = "=?utf-8?B?" . base64_encode($subject) . "?=";
            mail($to, $subject, $message, $headers);
            header("Location: successfeedback.php");
            exit;
        }

        return array(
            "error" => $error,
            "error_from" => $error_from,
            "error_to" => $error_to,
            "error_subject" => $error_subject,
            "error_message" => $error_message);
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

    public static function get_product_list_by_order_id_and_user_id($order_id, $user_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM orders INNER JOIN orders_information ON orders.id = orders_information.order_id WHERE user_id = '$user_id' AND order_id = '$order_id' ORDER BY orders.id");

        $parameters_list = ['product_id', 'color_id', 'product_amount', 'product_price'];

        $product_list = DatabaseConnect::fetch_two_dimensional_array($result, $parameters_list);

        parent::disconnect_database($mysqli);
        return $product_list;
    }


    public static function make_an_order($cartData, $user_id, $current_date, $current_time)
    {
        $cartData = unserialize($cartData);

        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT MAX(`id`) AS order_id FROM `orders`");

        if ($result !== false) {
            $row = $result->fetch_assoc();
            $order_id = $row['order_id'];
            $order_id = $order_id + 1;
        } else {
            $order_id = 1;
        }

        $mysqli->query("INSERT INTO orders (id, user_id, date, time, status) VALUES ('$order_id', '$user_id', '$current_date', '$current_time', 'в обработке')");


        foreach ($cartData as $product_id => $inforamation) {

            foreach ($inforamation as $color_id => $product_amount) {
                $mysqli->query("INSERT INTO orders_information (order_id, product_id, color_id, product_amount) VALUES ('$order_id', '$product_id', '$color_id', '$product_amount')");
            }
        }

        unset($_SESSION['cart_product_list']);
        unset($_SESSION['cart_product_amount']);

        parent::disconnect_database($mysqli);
    }


    public static function delete_product_color_from_cart_list($delete_product_id, $delete_product_color)
    {
        $cartData = unserialize($_SESSION['cart_product_list']);
        unset($cartData[$delete_product_id][$delete_product_color]);

        $cart_product_amount = 0;

        foreach ($cartData as $product_id => $inforamation) {
            foreach ($inforamation as $color_id => $amount) {
                $cart_product_amount = $cart_product_amount + $amount;
            }
        }

        $_SESSION['cart_product_amount'] = $cart_product_amount;

        if ($cart_product_amount == 0) {
            unset($_SESSION['cart_product_list']);
            unset($_SESSION['cart_product_amount']);
        } else {
            $_SESSION['cart_product_list'] = serialize($cartData);
        }
        header("Location: /cart");
    }


    public static function delete_product_from_compare_list($delete_product_id, $category_id)
    {
        $compareData = unserialize($_SESSION['compare_product_list']);

        $compare_category_products = $compareData[$category_id];

        $i = array_search("$delete_product_id", $compare_category_products);

        unset($compare_category_products[$i]);

        if (empty($compare_category_products)) {
            unset($compareData[$category_id]);

            if (empty($compareData)) {
                unset($_SESSION['compare_product_list']);
                unset($_SESSION['compare_product_amount']);
            } else {
                $_SESSION['compare_product_list'] = serialize($compareData);
                $compare_product_amount = count($compareData, COUNT_RECURSIVE) - count($compareData);
                $_SESSION['compare_product_amount'] = $compare_product_amount;
            }
        } else {
            $compareData[$category_id] = $compare_category_products;
            $_SESSION['compare_product_list'] = serialize($compareData);
            $compare_product_amount = count($compareData, COUNT_RECURSIVE) - count($compareData);
            $_SESSION['compare_product_amount'] = $compare_product_amount;
        }
    }


    public static function find_product_with_the_most_parameters_from_product_list($product_list)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT product_id, COUNT(*) FROM parameter_values WHERE product_id IN (" . implode(',', $product_list) . ") GROUP BY product_id ORDER BY 2 DESC LIMIT 1");

        $result_array = $result->fetch_assoc();

        $product_id = $result_array['product_id'];

        parent::disconnect_database($mysqli);
        return $product_id;
    }

    public static function get_user_name_by_user_id($user_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM `user` WHERE `id` = '$user_id' ");

        $row = $result->fetch_assoc();

        $user_name = $row['login'];

        parent::disconnect_database($mysqli);
        return $user_name;
    }


}
<?php

include_once('/models/DatabaseConnect.php');

Class Admin extends DatabaseConnect
{
    public static function check_user_role($user_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query("SELECT * FROM `myFirstSite`.`user` WHERE id = '$user_id' ");

        $user_data = $result->fetch_assoc();
        $user_role = $user_data["role"];

        parent::disconnect_database($mysqli);

        return $user_role;
    }

    public static function check_if_administrator()
    {
        $user_role = "user";

        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $user_role = Admin::check_user_role($user_id);
        }

        if ($user_role !== "admin") {
            header("Location: /no_permission");
        }
    }


}

?>
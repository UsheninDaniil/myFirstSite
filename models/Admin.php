<?php

include_once ('/models/DatabaseConnect.php');

Class Admin extends DatabaseConnect
{
    public static function check_user_role($user_id)
    {
        $mysqli = parent::connect_to_database();

        $result = $mysqli->query ("SELECT * FROM `myFirstSite`.`user` WHERE id = '$user_id' ");

        $user_data = $result->fetch_array();
        $user_role = $user_data["role"];

        parent::disconnect_database($mysqli);

        return $user_role;
    }


}

?>
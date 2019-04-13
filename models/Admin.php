<?php

Class Admin{



    public static function check_user_role($user_id)
    {
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");
        $result = $mysqli->query ("SELECT * FROM `myFirstSite`.`user` WHERE id = '$user_id' ");
        $mysqli->close();

        $user_data = $result->fetch_array();
        $user_role = $user_data["role"];

        return $user_role;
    }


}

?>
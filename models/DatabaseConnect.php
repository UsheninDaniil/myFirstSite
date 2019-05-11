<?php
/**
 * Created by PhpStorm.
 * User: Даня
 * Date: 11.05.2019
 * Time: 22:39
 */





class DatabaseConnect
{
    public static function connect_to_database(){
        $mysqli = new mysqli ("localhost", "root", "","myFirstSite");
        $mysqli->query ("SET NAMES 'utf8'");
        return $mysqli;
    }

    public static function disconnect_database($mysqli){
        $mysqli->close();
    }
}
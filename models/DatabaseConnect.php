<?php

class DatabaseConnect
{
    public static function connect_to_database()
    {
        $mysqli = new mysqli ("localhost", "root", "12345678", "myFirstSite");
        $mysqli->query("SET NAMES 'utf8'");
        return $mysqli;
    }


    public static function disconnect_database($mysqli)
    {
        $mysqli->close();
    }


    // Функция возвращает одномерный массив из нескольких параметров, например информацию только об одном товаре
    public static function fetch_one_dimensional_array($result, $parameters_list)
    {
        $result_list = array();

        $row = $result->fetch_assoc();

        if (empty($row)) {
            return $result_list;
        }

        foreach ($parameters_list as $parameter) {
            $result_list[$parameter] = $row[$parameter];
        }

        return $result_list;
    }


    //Функция возвращает двумерный массив из нескольких параметров, например список товаров вместе со всей информацией
    public static function fetch_two_dimensional_array($result, $parameters_list)
    {
        $i = 0;
        $result_list = array();

        while ($i < $result->num_rows) {
            $row = $result->fetch_assoc();

            foreach ($parameters_list as $parameter) {
                $result_list[$i][$parameter] = $row[$parameter];
            }
            $i++;
        }
        return $result_list;
    }


    // Функция возвращает одномерный массив, состоящий только из одного параметра, например список id
    public static function fetch_array_of_one_parameter($result, $parameter){
        $i = 0;
        $result_list = array();

        while ($i < $result->num_rows) {
            $row = $result->fetch_assoc();

            array_push($result_list, $row[$parameter]);
            $i++;
        }
        return $result_list;
    }



}


?>
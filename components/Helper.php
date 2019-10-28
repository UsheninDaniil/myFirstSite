<?php

class Helper{

    public static function get_information_from_url($segment_number)
    {
        $uri = $_SERVER['REQUEST_URI'];
        $segments = explode('/', $uri);
        if (isset($segments[$segment_number])) {
            $information = $segments[$segment_number];
        } else{
            $information = null;
        }
        return $information;
    }


    public static function get_information_from_url_with_get_parameters($segment_number)
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri_without_get_parameter = explode('?', $uri)[0];
        $segments = explode('/', $uri_without_get_parameter);
        if (isset($segments[$segment_number])) {
            $information = $segments[$segment_number];
        } else{
            $information = null;
        }
        return $information;
    }

    public static function get_get_parameters_from_url_without_page()
    {
        if (!empty($_GET)) {
            $get_parameters_array = $_GET;
            if (isset($get_parameters_array['page'])) {
                unset($get_parameters_array['page']);
            }
            $get_parameters_without_page = $get_parameters_array;
        } else{
            $get_parameters_without_page = null;
        }

        return $get_parameters_without_page;
    }
}
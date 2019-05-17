<?php
/**
 * Created by PhpStorm.
 * User: Даня
 * Date: 12.05.2019
 * Time: 14:25
 */

class Pagination
{

    public function __construct(){
    }

    public function get_pagination_parameters($index_of_page_in_url, $amount_of_elements_on_page, $get_total_elements_amount_request){

        $mysqli = DatabaseConnect::connect_to_database();

        // Получаем адресную строку без get параметров
        $uri_with_parameters = $_SERVER['REQUEST_URI'];
        $uri_parts = explode('?', $uri_with_parameters, 2);
        $uri_without_parameters = $uri_parts[0];

        $segments = explode('/',$uri_without_parameters);

        // Получаем строку с номером текущей страницы, если он указан в адресной строке
        if(isset($segments[$index_of_page_in_url])){
            $current_page_number='/'.$segments[$index_of_page_in_url];}
        else{
            $current_page_number='';
        }

        // Получаем номер страницы из этой строки
        $current_page_number = str_replace('/page=', '', $current_page_number);

        // Определяем общее число элементов в базе данных
        $result = $mysqli->query("$get_total_elements_amount_request");
        $total_elements_amount = $result->fetch_row();
        $total_elements_amount=$total_elements_amount[0];

        // Находим общее число страниц
        $total_count = ceil(($total_elements_amount / $amount_of_elements_on_page));

        // Определяем начало сообщений для текущей страницы
        $current_page_number = intval($current_page_number);

        // Если значение $current_page_number меньше единицы или отрицательно, то переходим на первую страницу
        if(empty($current_page_number) or $current_page_number < 0){
            $current_page_number = 1;
        }

        // Если значение $current_page_number слишком большое, то переходим на последнюю
        if($current_page_number > $total_count){
            $current_page_number = $total_count;
        }

        // Вычисляем начиная к какого номера следует выводить сообщения
        $start = $current_page_number * $amount_of_elements_on_page - $amount_of_elements_on_page;
        if($start < 0){
            $start = 0;
        };

        $result_parameters = array();
        $result_parameters['current_page_number'] = $current_page_number ;
        $result_parameters['total_count'] = $total_count ;
        $result_parameters['start'] = $start ;

        return $result_parameters;
    }

    public function get_pagination_elements($start, $amount_of_elements_on_page, $get_elements_request){

        $mysqli = DatabaseConnect::connect_to_database();

        // Выбираем заданное количество элементов из базы данных, начиная с номера $start
        $result = $mysqli->query("$get_elements_request LIMIT $start, $amount_of_elements_on_page");

        $i = 0;
        $elements_list = array();

        while ($i < $result->num_rows) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            array_push($elements_list, $row);
            $i++;
        }

        return $elements_list;
    }

    public function build_pagination($total_count, $current_page_number, $limit){

        if($current_page_number > 0){

        echo '<div class="pagination_buttons">';

        if(($limit%2)==1){
            $limit = $limit -1;
        }

            if ($current_page_number <= $limit/2){
                $page_left_amount = $current_page_number - 1;
                $page_right_amount = $limit - $page_left_amount;
            }
            elseif (($total_count - $current_page_number) < $limit/2){
                $page_right_amount = $total_count - $current_page_number;
                $page_left_amount = $limit - $page_right_amount;
            }
            else{
                $page_right_amount = $limit/2;
                $page_left_amount = $limit/2;
            };

        $currentURI = rtrim($_SERVER['REQUEST_URI'],'/');

        $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);

        $uri_without_parameters = $uri_parts[0];
        $uri_without_parameters = rtrim($uri_without_parameters,'/');

        if(isset($uri_parts[1])){
            $get_parameters = '?'.$uri_parts[1];
        } else{
            $get_parameters = '';
        }

        $newUri = preg_replace('~/page=[0-9]{0,999}+~', '', $uri_without_parameters);

        if(($current_page_number - $page_left_amount) > 1){
            $first_page = "<a href= '$newUri/page=1/$get_parameters' class='page-link'>1</a> ";
            $left_ellipsis = "<a href='' class='page-link'>...</a>";
        } else{
            $first_page = "<a href= '$newUri/page=1/$get_parameters' class='page-link' style='visibility: hidden'>1</a> ";
            $left_ellipsis = "<a href='' class='page-link' style='visibility: hidden'>...</a>";
        }

        if(($total_count - $current_page_number - $page_right_amount) >= 1){
            $last_page = " <a href='$newUri/page=$total_count/$get_parameters' class='page-link'>$total_count</a>";
            $right_ellipsis = "<a href='' class='page-link'>...</a>";
        } else{
            $last_page = " <a href='$newUri/page=$total_count/$get_parameters' class='page-link' style='visibility: hidden'>$total_count</a>";
            $right_ellipsis = "<a href='' class='page-link' style='visibility: hidden'>...</a>";
        }

        echo "<ul class='pagination justify-content-center'>";

        echo "<li class='page-item'>$first_page</li>";
        echo "<li class='page-item'>$left_ellipsis</li>";

        for ($i = 1; $i <= $page_left_amount; $i++){
            $page_number = $current_page_number - $page_left_amount - 1 + $i;
            if($page_number>=1){
                $page_link = "<li class='page-item'><a href='$newUri/page=$page_number/$get_parameters' class='page-link'>$page_number</a></li>";
                echo $page_link;
            }
        }

        $currentPage = "<li class='page-item active'><a href='$newUri/page=$current_page_number/$get_parameters' class='page-link'>$current_page_number</a></li>";

        echo $currentPage;

        for ($i = 1; $i <= $page_right_amount; $i++){
            $page_number = $current_page_number + $i;
            if($page_number<=$total_count){
            $page_link = "<li class='page-item'><a href='$newUri/page=$page_number/$get_parameters' class='page-link'>$page_number</a></li>";
            echo $page_link;
            }
        }

        echo "<li class='page-item'>$right_ellipsis</li>";
        echo "<li class='page-item'>$last_page</li>";

        echo '</ul>';
        echo '</div>';
        }
    }



}
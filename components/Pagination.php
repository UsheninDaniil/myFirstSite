<?php
/**
 * Created by PhpStorm.
 * User: Даня
 * Date: 12.05.2019
 * Time: 14:25
 */

class Pagination
{
    // сколько показывать кнопок пагинации
    public $limit = null;
    // сколько показывать элементов на странице
    public $amount_of_elements_on_page = null;
    // после получения элементов для пагинации будет проверка, есть ли хоть 1 элемент, если нет - пагинация не будет построена
    public $elements_exist = null;
    public $total_amount = null;
    public $start = null;
    public $current_page_number = null;

    public function __construct($amount_of_elements_on_page = 1, $limit = 4)
    {
        $this->amount_of_elements_on_page = $amount_of_elements_on_page;
        $this->limit = $limit;
        $this->current_page_number = $this->get_current_page_number_from_url();
    }

    public function calculate_parameters($get_total_elements_amount_request)
    {
        $current_page_number = $this->current_page_number;
        $amount_of_elements_on_page = $this->amount_of_elements_on_page;
        $mysqli = DatabaseConnect::connect_to_database();

        // Получаем адресную строку без get параметров
        $uri_with_parameters = $_SERVER['REQUEST_URI'];
        $uri_parts = explode('?', $uri_with_parameters, 2);
        $uri_without_parameters = $uri_parts[0];

        $segments = explode('/', $uri_without_parameters);

        // Определяем общее число элементов в базе данных
        $result = $mysqli->query("$get_total_elements_amount_request");
        if($result === false){
            $total_elements_amount = 0;
        } else{
            $total_elements_amount = $result->fetch_row();
            $total_elements_amount = $total_elements_amount[0];
        }

        // Находим общее число страниц
        $total_amount = ceil(($total_elements_amount / $amount_of_elements_on_page));

        // Определяем начало сообщений для текущей страницы
        $current_page_number = intval($current_page_number);

        // Если значение $current_page_number меньше единицы или отрицательно, то переходим на первую страницу
        if (empty($current_page_number) or $current_page_number < 0) {
            $current_page_number = 1;
        }

        // Если значение $current_page_number слишком большое, то переходим на последнюю
        if ($current_page_number > $total_amount) {
            $current_page_number = $total_amount;
        }

        // Вычисляем начиная к какого номера следует выводить сообщения
        $start = $current_page_number * $amount_of_elements_on_page - $amount_of_elements_on_page;
        if ($start < 0) {
            $start = 0;
        };

        $this->total_amount = $total_amount;
        $this->start = $start;
    }

     public function get_current_page_number_from_url()
     {
         if (isset($_GET['page'])) {
             $current_page_number = $_GET['page'];
         } else {
             $current_page_number = 1;
         }
         return $current_page_number;
     }

    public function get_pagination_elements($get_elements_request, $get_total_elements_amount_request)
    {
        $this->calculate_parameters($get_total_elements_amount_request);
        $start = $this->start;

        $amount_of_elements_on_page = $this->amount_of_elements_on_page;
        $mysqli = DatabaseConnect::connect_to_database();

        // Выбираем заданное количество элементов из базы данных, начиная с номера $start
        $result = $mysqli->query("$get_elements_request LIMIT $start, $amount_of_elements_on_page");

        $i = 0;
        $elements_list = array();

        while ($i < $result->num_rows) {
            $row = $result->fetch_assoc();
            array_push($elements_list, $row);
            $i++;
        }

        if(empty($elements_list)){
            $this->elements_exist = false;
        } else{
            $this->elements_exist = true;
        }

        return $elements_list;
    }

    public function build_pagination()
    {
        $current_page_number = $this->current_page_number;
        $total_amount = $this->total_amount;
        $limit = $this->limit;

        if ($this->elements_exist === true) {

            if (!empty($_GET)) {
                $get_parameters = $_GET;

                if (isset($get_parameters['page'])) {
                    unset($get_parameters['page']);
                }

                $get_parameters_without_page = http_build_query($get_parameters);
            } else {
                $get_parameters_without_page = '';
            }

            echo '<div class="pagination_buttons">';

            if (($limit % 2) == 1) {
                $limit = $limit - 1;
            }

            if ($current_page_number <= $limit / 2) {
                $page_left_amount = $current_page_number - 1;
                $page_right_amount = $limit - $page_left_amount;
            } elseif (($total_amount - $current_page_number) < $limit / 2) {
                $page_right_amount = $total_amount - $current_page_number;
                $page_left_amount = $limit - $page_right_amount;
            } else {
                $page_right_amount = $limit / 2;
                $page_left_amount = $limit / 2;
            };

            $currentURI = rtrim($_SERVER['REQUEST_URI'], '/');

            $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);

            $uri_without_parameters = $uri_parts[0];
            $uri_without_parameters = rtrim($uri_without_parameters, '/');

            $newUri = $uri_without_parameters;

            if (($current_page_number - $page_left_amount) > 1) {
                $new_get_parameters = '';
                $first_page = "<a href= '$newUri{$new_get_parameters}' class='page-link'>1</a> ";
                $left_ellipsis = "<a href='' class='page-link'>...</a>";
            } else
                $new_get_parameters = '';
            $first_page = "<a href= '$newUri{$new_get_parameters}' class='page-link' style='visibility: hidden'>1</a> ";
            $left_ellipsis = "<a href='' class='page-link' style='visibility: hidden'>...</a>";


            if (($total_amount - $current_page_number - $page_right_amount) >= 1) {
                if (!empty($get_parameters_without_page)) {
                    $new_get_parameters = "?page=$total_amount&" . $get_parameters_without_page;
                } else {
                    $new_get_parameters = "?page=$total_amount";
                }
                $last_page = " <a href='$newUri{$new_get_parameters}' class='page-link'>$total_amount</a>";
                $right_ellipsis = "<a href='' class='page-link'>...</a>";
            } else {
                if (!empty($get_parameters_without_page)) {
                    $new_get_parameters = "?page=$total_amount&" . $get_parameters_without_page;
                } else {
                    $new_get_parameters = "?page=$total_amount";
                }
                $last_page = " <a href='$newUri{$new_get_parameters}' class='page-link' style='visibility: hidden'>$total_amount</a>";
                $right_ellipsis = "<a href='' class='page-link' style='visibility: hidden'>...</a>";
            }

            echo "<ul class='pagination justify-content-center'>";

            echo "<li class='page-item'>$first_page</li>";
            echo "<li class='page-item'>$left_ellipsis</li>";

            for ($i = 1; $i <= $page_left_amount; $i++) {
                $page_number = $current_page_number - $page_left_amount - 1 + $i;
                if ($page_number >= 1) {
                    if (!empty($get_parameters_without_page)) {
                        $new_get_parameters = "?page=$page_number&" . $get_parameters_without_page;
                    } else {
                        $new_get_parameters = "?page=$page_number";
                    }
                    $page_link = "<li class='page-item'><a href='$newUri{$new_get_parameters}' class='page-link'>$page_number</a></li>";
                    echo $page_link;
                }
            }

            if (!empty($get_parameters_without_page)) {
                $new_get_parameters = "?page=$current_page_number&" . $get_parameters_without_page;
            } else {
                $new_get_parameters = "?page=$current_page_number";
            }

            $currentPage = "<li class='page-item active'><a href='$newUri{$new_get_parameters}' class='page-link'>$current_page_number</a></li>";

            echo $currentPage;

            for ($i = 1; $i <= $page_right_amount; $i++) {
                $page_number = $current_page_number + $i;
                if ($page_number <= $total_amount) {
                    if (!empty($get_parameters_without_page)) {
                        $new_get_parameters = "?page=$page_number&" . $get_parameters_without_page;
                    } else {
                        $new_get_parameters = "?page=$page_number";
                    }
                    $page_link = "<li class='page-item'><a href='$newUri{$new_get_parameters}' class='page-link'>$page_number</a></li>";
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
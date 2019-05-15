<?php
/**
 * Created by PhpStorm.
 * User: Даня
 * Date: 12.05.2019
 * Time: 14:25
 */

class Pagination
{

    public function __construct()
    {

    }

    public function build_pagination($total_count, $currentPageNumber, $limit){

        echo '<div class="pagination_buttons">';

        $limit = 4;

        if(($limit%2)==1){
            $limit = $limit -1;
        }

            if ($currentPageNumber <= $limit/2){
                $page_left_amount = $currentPageNumber - 1;
                $page_right_amount = $limit - $page_left_amount;
            }
            elseif (($total_count - $currentPageNumber) < $limit/2){
                $page_right_amount = $total_count - $currentPageNumber;
                $page_left_amount = $limit - $page_right_amount;
            }
            else{
                $page_right_amount = $limit/2;
                $page_left_amount = $limit/2;
            };

        echo "Количество ссылок справа $page_right_amount<br />";
        echo "Количество ссылок слева $page_left_amount<br />";

        if(($currentPageNumber - $page_left_amount) > 1){
            $first_page = '<a href= /page=1 class="page-link">1</a> ';
            $left_ellipsis = '<a href="" class="page-link">...</a>';
        } else{
            $first_page = '<a href= /page=1 class="page-link" style="visibility: hidden">1</a> ';
            $left_ellipsis = '<a href="" class="page-link" style="visibility: hidden">...</a>';
        }

        if(($total_count - $currentPageNumber - $page_right_amount) >= 1){
            $last_page = " <a href='/page=".$total_count."' class='page-link'>".$total_count."</a>";
            $right_ellipsis = '<a href="" class="page-link">...</a>';
        } else{
            $last_page = " <a href='/page=".$total_count."' class='page-link' style='visibility: hidden'>".$total_count."</a>";
            $right_ellipsis = '<a href="" class="page-link" style="visibility: hidden">...</a>';
        }

        echo '<ul class="pagination justify-content-center">';

        echo "<li class='page-item'>$first_page</li>";
        echo "<li class='page-item'>$left_ellipsis</li>";

        for ($i = 1; $i <= $page_left_amount; $i++){
            $page_number = $currentPageNumber - $page_left_amount - 1 + $i;
            if($page_number>=1){
                $page_link = '<li class="page-item"><a href= /page='.$page_number.' class="page-link">'. $page_number .'</a></li>';
                echo $page_link;
            }
        }

        $currentPage = '<li class="page-item active"><a href= /page='. $currentPageNumber .' class="page-link">'. $currentPageNumber .'</a></li>';

        echo $currentPage;

        for ($i = 1; $i <= $page_right_amount; $i++){
            $page_number = $currentPageNumber + $i;
            if($page_number<=$total_count){
            $page_link = '<li class="page-item"><a href= /page='.$page_number.' class="page-link">'. $page_number .'</a></li>';
            echo $page_link;
            }
        }

        echo "<li class='page-item'>$right_ellipsis</li>";
        echo "<li class='page-item'>$last_page</li>";

        echo '</ul>';

        echo '</div>';

    }

}
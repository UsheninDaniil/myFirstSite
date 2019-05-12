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
        // Проверяем нужны ли стрелки назад
        if ($currentPageNumber != 1) {
            $pervpage = '<a href= /page=' . ($currentPageNumber - 1) . ' class="page-link"><</a> ';
            $firstpage = '<a href= /page=1 class="page-link"><<</a> ';
            } else{
                $pervpage = '';
                $firstpage = '';
            }
        // Проверяем нужны ли стрелки вперед
        if ($currentPageNumber != $total_count) {
                $nextpage = ' <a href= /page='. ($currentPageNumber + 1) .' class="page-link">></a> ';
                $lastpage = ' <a href= /page='. $total_count .' class="page-link">>></a> ';
            } else {
                $nextpage = '';
                $lastpage = '';
            }

        // Находим две ближайшие станицы с обоих краев, если они есть
        if($currentPageNumber - 2 > 0) {
            $page2left = ' <a href= /page='. ($currentPageNumber - 2) .' class="page-link">'. ($currentPageNumber - 2) .'</a> ';
        } else {
            $page2left = '';};

        if($currentPageNumber - 1 > 0) {
            $page1left = '<a href= /page='. ($currentPageNumber - 1) .' class="page-link">'. ($currentPageNumber - 1) .'</a> ';
        } else {
            $page1left = '';};

        if($currentPageNumber + 2 <= $total_count) {
            $page2right = '<a href= /page='. ($currentPageNumber + 2) .' class="page-link">'. ($currentPageNumber + 2) .'</a>';
        } else {
            $page2right ='';};

        if($currentPageNumber + 1 <= $total_count) {
            $page1right = '<a href= /page='. ($currentPageNumber + 1) .' class="page-link">'. ($currentPageNumber + 1) .'</a>';
        } else {
            $page1right ='';};



        $currentPage = '<a href= /page='. $currentPageNumber .' class="page-link">'. $currentPageNumber .'</a>';



        echo '
        
       <ul class="pagination justify-content-center">
       
            <li class="page-item">
                '.$firstpage.'
            </li>
            <li class="page-item">
                '.$pervpage.'
            </li>
            <li class="page-item">
                '.$page2left.'
            </li>
            <li class="page-item">
                '.$page1left.'
            </li>
            <li class="page-item active">
                '.$currentPage.'
            </li>
            <li class="page-item">
                '.$page1right.'
            </li>
            <li class="page-item">
                '.$page2right.'
            </li>
            <li class="page-item">
                '.$nextpage.'
            </li>
            <li class="page-item">
                '.$lastpage.'
            </li>
       </ul>
        
        ';






        echo '</div>';

    }

}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <!--Сторонние файлы и плагины-->

    <link rel="stylesheet" href="/template/third_party_files/fontawesome-free-5.8.1-web/css/all.min.css" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="/template/third_party_files/bootstrap4-editable/css/bootstrap-editable.css" >
    <link rel="stylesheet" href="/template/third_party_files/bootstrap-4.3.1-dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="/template/third_party_files/swiper-4.4.6/dist/css/swiper.min.css">
    <link rel="stylesheet" href="/template/third_party_files/jquery-ui-1.12.1.custom/jquery-ui.min.css">

    <link rel="stylesheet" href="/template/third_party_files/jQuery-UI-Multiple-Select-Widget/css/jquery.multiselect.css">
    <link rel="stylesheet" href="/template/third_party_files/jQuery-UI-Multiple-Select-Widget/css/jquery.multiselect.filter.css">

    <link rel="stylesheet" href="/template/third_party_files/tagify-plugin-old-version/tagify.css">
    <link rel="stylesheet" href="/template/third_party_files/tagify-plugin-old-version/jquerysctipttop.css">

    <!--Самописные файлы и плагины-->

    <link rel="stylesheet" href="/template/css/images_preview.css">

    <link rel="stylesheet" href="/template/css/main.css?v=<?php echo uniqid()?>">

    <link rel="stylesheet" href="/template/css/product_images_slider.css">

</head>
<body>

<div class="wrapper">
    <div class="content">

    <div class="header">
        <div class="top-menu" >

            <div class="header_left_part" style="display: inline-block">

                    <ul class="topmenu">
                        <li>
                            <b><a href="" class="header_show_category_list"><i class="fas fa-list"></i> Каталог товаров </a></b>

                            <ul class="submenu">
                                <li><a href="">Компьютеры</a></li>
                                <li><a href="">Ноутбуки</a></li>
                                <li><a href="">Планшеты</a></li>
                                <li><a href="">Телефоны</a></li>
                            </ul>

                        </li>
                        <li><a href="/" class="header_go_to_homepage"><i class="fas fa-home"></i></a></li>
                    </ul>




            </div>

            <div class="header_search" style="display: inline-block">
                <form class="search_form" action="/search/" method="get">
                    <input type="text" name="text" placeholder="Поиск по сайту">
                    <button type="submit"></button>
                </form>
            </div>

            <div class="header_main_information" style="display: inline-block">
                <ul class="header_main_information_list">
                    <li><a href ="/cabinet" style="color: black"><i class="fas fa-home"></i> Личный кабинет</a></li>
                    <li>
                        <a href ="/cart" class="cart-count" style="color: black">
                            <i class="fas fa-shopping-cart"></i>
                            <?php
                            if(isset($_SESSION['cart_product_amount'])){
                                $cart_product_amount = $_SESSION['cart_product_amount'];
                                echo "Корзина ($cart_product_amount)";
                            }
                            else {
                                echo "Корзина";
                            }
                            ?>
                        </a>
                    </li>
                    <li>
                        <a href ="/compare" class="compare-count" style="color: black">
                            <i class="fas fa-balance-scale"></i>
                            <?php
                            if(isset($_SESSION['compare_product_amount'])){
                                $compare_product_amount = $_SESSION['compare_product_amount'];
                                echo "Сравнение ($compare_product_amount)";
                            }
                            else {
                                echo "Сравнение";
                            }
                            ?>
                        </a>
                    </li>
                </ul>
            </div>

        </div>


    </div>





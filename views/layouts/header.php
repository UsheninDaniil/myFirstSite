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

            <div class="header_left_part" style="overflow: auto;">

                    <ul class="topmenu scalable_text">
                        <li>
                            <div><pre><b><a href="" class="header_show_category_list"><i class="fas fa-list"></i> Каталог товаров </a></b></pre></div>

<!--                            <ul class="submenu">-->
<!--                                <li><a href="">Компьютеры</a></li>-->
<!--                                <li><a href="">Ноутбуки</a></li>-->
<!--                                <li><a href="">Планшеты</a></li>-->
<!--                                <li><a href="">Телефоны</a></li>-->
<!--                            </ul>-->

                        </li>
                        <li>
                            <div><pre><a href="/" class="header_go_to_homepage"><i class="fas fa-home"></i></a></pre></div>
                        </li>
                    </ul>

            </div>

            <div class="header_search" style="display: inline-block">
                <form class="search_form" action="/search/" method="get">
                    <div style="overflow: auto; float: left">
                        <input class="input" style="" type="text" name="text" placeholder="Поиск по сайту 123456789_123456789_123456789">
                    </div>
<!--                    <button type="submit"></button>-->
                </form>
            </div>

            <div class="header_main_information" style="overflow: auto;">
                <ul class="header_main_information_list scalable_text">

                    <li>
                        <pre><a href ="/cabinet" style="color: black"><i class="fas fa-home"></i>Личный кабинет</a></pre>
                    </li>

                    <li>
                       <pre><a href ="/cart" class="cart-count" style="color: black"><?php
                                if(isset($_SESSION['cart_product_amount'])){
                                    $cart_product_amount = $_SESSION['cart_product_amount'];
                                    echo "<i class='fas fa-shopping-cart'></i>Корзина($cart_product_amount)";
                                }
                                else {
                                    echo "<i class='fas fa-shopping-cart'></i>Корзина";
                                }
                                ?></a></pre>
                    </li>

                    <li>
                        <pre><a href ="/compare" class="compare-count" style="color: black"><?php
                                if(isset($_SESSION['compare_product_amount'])){
                                    $compare_product_amount = $_SESSION['compare_product_amount'];
                                    echo "<i class='fas fa-balance-scale'></i>Сравнение ($compare_product_amount)";
                                }
                                else {
                                    echo "<i class='fas fa-balance-scale'></i>Сравнение";
                                }
                                ?></a></pre>
                    </li>
                </ul>
            </div>

        </div>


    </div>



<script>


    var buffer = [];

    function resize_input_to_width_of_placeholder() {

        var input = document.querySelectorAll('.input');
        for (var i = 0; input.length > i; i++) {

            if (input[i].placeholder !== '') {

                if(buffer[i]){
                    input[i].parentNode.removeChild(buffer[i]);
                    // console.log("buffer существует, удалил");
                } else {
                    // console.log("buffer не существует");
                }

                input[i].parentElement.style.fontSize = 100 + "%";

                buffer[i] = document.createElement('div');
                buffer[i].className = "buffer";
                //вставляем скрытый div.buffer
                input[i].parentNode.insertBefore(buffer[i], input[i].nextSibling);

                input[i].nextElementSibling.innerHTML = input[i].placeholder;
                input[i].style.width = input[i].nextElementSibling.clientWidth + 'px';

                input[i].parentElement.style.width = input[i].nextElementSibling.clientWidth + 'px';

                var search_form_width = input[i].closest(".search_form").offsetWidth;

                var necessary_width = search_form_width;
                necessary_width = Math.ceil(necessary_width);
                var real_width = input[i].scrollWidth;

                var font_size = Math.floor((necessary_width / real_width)*100);
                font_size = font_size - 1;
                font_size = font_size + '%';

                input[i].parentElement.style.fontSize = font_size;

                input[i].parentElement.style.width = search_form_width + 'px';
                input[i].style.width = search_form_width + 'px';
            }

            input[i].oninput = function() {
                this.style.width = 100 + '%';
            };

        }

    }

    function resize_scalable_text() {

        console.log("вызвана функция уменьшения текста");

        var scalable_text_array = document.querySelectorAll('.scalable_text');

        for (var i = 0; i < scalable_text_array.length; i++) {

            var scalable_text = scalable_text_array[i];

            scalable_text.parentElement.style.fontSize = 100 + "%";

            var necessary_width = scalable_text.parentElement.clientWidth;
            necessary_width = Math.ceil(necessary_width);
            var real_width = scalable_text.scrollWidth;

            console.log("necessary_width = " + necessary_width);
            console.log("real_width = " + real_width);

            var font_size = Math.floor((necessary_width / real_width)*100);
            font_size = font_size - 1;
            font_size = font_size + '%';

            scalable_text.parentElement.style.fontSize = font_size;
        }
    };

    window.addEventListener('resize', resize_input_to_width_of_placeholder);
    window.addEventListener('resize', resize_scalable_text);

    document.addEventListener("DOMContentLoaded", resize_input_to_width_of_placeholder);
    document.addEventListener("DOMContentLoaded", resize_scalable_text);

</script>

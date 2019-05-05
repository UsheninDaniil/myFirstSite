<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <link rel="stylesheet" href="/template/fontawesome-free-5.8.1-web/css/all.min.css" >

    <link rel="stylesheet" href="/template/bootstrap-editable/css/bootstrap-editable.css" >
    <link rel="stylesheet" href="/template/bootstrap-4.3.1-dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="/template/swiper-4.4.6/dist/css/swiper.min.css">
    <link rel="stylesheet" href="/template/jquery-ui-1.12.1.custom/jquery-ui.min.css">

    <link rel="stylesheet" href="/template/css/main.css?v=<?php echo uniqid()?>">
    <style>
    </style>


</head>
<body>

    <div class="header">
        <div class="top-menu">
            <ul class="list-of-sections">
                <li><a href ="/">Главная страница</a></li>
                <li><a href ="/about">О нас</a></li>
                <li><a href ="/contact">Контакты</a></li>
                <li><a href ="/delivery">Доставка</a></li>
                <li><a href ="/feedback">Обратная связь</a></li>
                <li><a href ="/registration">Регистрация</a></li>
                <li><a href ="/login">Вход</a></li>

                <?php
                include_once ('/models/User.php');
                $authorization_result = User::action_check_authorization();
                if ($authorization_result==true): ?>
                    <li><a href ="/cabinet">Личный кабинет</a></li>
                <?php endif; ?>

                <li>
                    <a href ="/cart" class="cart-count">
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

                <li><a href ="/compare" class="compare-count">
                        <?php
                        if(isset($_SESSION['compare_product_amount'])){
                            $compare_product_amount = $_SESSION['compare_product_amount'];
                            echo "Сравнение ($compare_product_amount)";
                        }
                        else {
                            echo "Сравнение";
                        }
                        ?>
                    </a></li>
            </ul>
        </div>

        <div class="search">
            <form action="/search/" method="get">
                <p><input type="search" name="text" placeholder="Поиск по сайту">
                    <input type="submit" value="Найти"></p>
            </form>
        </div>
    </div>



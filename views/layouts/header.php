<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

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

    <link rel="stylesheet" href="/template/third_party_files/ON-OFF-Toggle-Switches-Switcher/css/switcher.css" >

    <link rel="stylesheet" href="/template/third_party_files/lou-multi-select/css/multi-select.css" media="screen"  type="text/css">

    <!--Самописные файлы и плагины-->

    <link rel="stylesheet" href="/template/css/images_preview.css">

    <link rel="stylesheet" href="/template/css/product_images_slider.css">

    <link rel="stylesheet" href="/template/css/main.css?v=<?php echo uniqid()?>">
    <link rel="stylesheet" href="/template/css/media_screen.css?v=<?php echo uniqid()?>">

</head>
<body>

<div class="wrapper">
    <div class="content">

    <div class="header">
        <div class="top-menu" >

            <div class="header_left_part">
                <div class="header_show_category_list_container">
                    <b><a href="" class="header_show_category_list"><i class="fas fa-list"></i><span> Каталог товаров</span></a></b>
                </div>
                <div>
                    <a href="/" class="header_go_to_homepage"><i class="fas fa-home"></i></a>
                </div>
            </div>

            <div class="header_search">
                <form class="search_form" action="/search/" method="get">
                    <div class="header_search_inner">
                    <input class="input" style="" type="text" name="text" placeholder="Поиск по сайту">
                    <button type="submit"></button>
                    </div>
                </form>
            </div>

            <div class="header_right_part">
                <a href ="/cabinet" style="color: black"><i class="fas fa-user"></i><span> Личный кабинет</span></a>
                <?php
                        require_once (ROOT.'/views/layouts/cart_template.php');
                        require_once (ROOT.'/views/layouts/compare_template.php');
                ?>
            </div>

        </div>

    </div>




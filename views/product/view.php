<?php

$photo_amount = 0;

$max_photo_width = 0;
$max_photo_height = 0;

$first_photo_id = 1;

$mysqli = DatabaseConnect::connect_to_database();

$result = $mysqli->query("SELECT photo_name FROM product_photos WHERE  product_id = '$product_id' ");

$i = 0;
$product_images_list = array();

while ($i < $result->num_rows) {
    $row = $result->fetch_assoc();
    $photo_name = $row['photo_name'];
    array_push($product_images_list, $photo_name);
    $i++;
}


$root = $_SERVER['DOCUMENT_ROOT'];

//echo "root = $root<br/>";
//$root_old = ROOT;
//echo "ROOT = $root_old<br/>";

foreach ($product_images_list as $image) {

    $image_path = ROOT . "/images/small_images/$image";

    if (file_exists($image_path)) {

        $photo_amount = $photo_amount + 1;
        list($width, $height, $type, $attr) = getimagesize($image_path);
        if ($width > $max_photo_width) {
            $max_photo_width = $width;
        }
        if ($height > $max_photo_height) {
            $max_photo_height = $height;
        }

    }
}

$border_width = 3;
$element_padding = 7;
$margin = 5;
$padding_bottom = $margin;
$element_height = $max_photo_height + $margin * 2 + $border_width * 2 + $element_padding * 2;
$photo_on_page = 4;

$slider_height = $photo_on_page * $element_height + $padding_bottom;
$slider_polosa_height = $element_height * $photo_amount + $padding_bottom;


$ability_to_choose_the_color = Color::check_is_there_ability_to_choose_the_color($product_id);

?>


<div class="view_product_information_block">

    <div class="view_product_image">

        <div id="product_photos">

            <?php if (count($product_images_list) > 1): ?>
                <div id="slider_with_buttons">

                    <button id="previous" class="btn btn-light"><i class="fas fa-angle-up"></i></button>

                    <div id="slider" style="height: <?= $slider_height ?>px"
                         data-photo-amount="<?= $photo_amount ?>"
                         data-photo-on-page="<?= $photo_on_page ?>"
                         data-element-height="<?= $element_height ?>"
                         data-current-top-value="0">

                        <div id="polosa" style="padding-bottom: <?= $padding_bottom ?>px;">

                            <?php foreach ($product_images_list as $image):
                                $image_path = ROOT . "/images/small_images/$image";
                                $show_element = false;
                                if (file_exists($image_path)) {
                                    $show_element = true;
                                }
                                ?>

                                <?php if ($show_element == true): ?>
                                <div class="element" onclick="ShowSelectedPhoto(this)" style="
                                        width: <?= $max_photo_width ?>px;
                                        margin-top: <?= $margin ?>px;
                                        margin-bottom: <?= $margin ?>px;
                                        height: <?= $max_photo_height ?>px;
                                        border: <?= $border_width ?>px solid transparent;
                                        padding: <?= $element_padding ?>px;">
                                    <img src="/images/small_images/<?= $image ?>" alt=""
                                         data-border-width="<?= $border_width; ?>">
                                </div>
                            <?php endif; ?>

                            <?php endforeach; ?>

                        </div>
                    </div>

                    <button id="next" class="btn btn-light"><i class="fas fa-angle-down"></i></button>

                </div>
            <?php endif; ?>


            <div id="selected_photo_container">

                <?php
                $image_path = "/images/middle_images/id_{$product_id}_photo_1.jpg";
                //                echo "$image_path";
                if (file_exists($image_path)) {
//                    echo "файл существует";
                } else {
//                    echo "файла нету";
                }
                ?>
                <img id="selected_photo" src="<?= $image_path ?>">

            </div>


            <div class="product_photo_swiper_container">
                <div class="swiper-container product_photo_swiper">
                    <div class="swiper-wrapper">

                        <?php foreach ($product_images_list as $image):
                            $image_path = ROOT . "/images/middle_images/$image";
                            $show_element = false;
                            if (file_exists($image_path)) {
                                $show_element = true;
                            }
                            ?>

                            <?php if ($show_element == true): ?>
                            <div class="swiper-slide">

                                <img data-src="/images/middle_images/<?= $image ?>" class="swiper-lazy">
                                <div class="swiper-lazy-preloader"></div>

                            </div>
                        <?php endif; ?>

                        <?php endforeach; ?>

                    </div>
                    <div class="swiper-pagination"></div>
                </div>

                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>

        </div>

    </div>

    <div class="name_and_price_outer_block">

        <div class="name_and_price_inner_block">

            <div class="view_product_name">
                <a href="/product/<?= $product_id ?>"><?php echo $product_info['name']; ?></a>
            </div>

            <?php if ($ability_to_choose_the_color === 'true'): ?>

                <div class="view_product_colors" style="display: flex; align-items: center; justify-content: center">
                    <div style="display: inline-flex">
                        <span style="margin-right: 5px">Цвет:</span>
                    </div>

                    <?php foreach ($product_colors as $color) {
                        $amount = $color['amount'];
                        $color_id = $color['color_id'];
                        $name = $color['name'];
                        $hex_code = $color['hex_code'];

                        include('/views/product/color_link_template.php');
                    }
                    ?>


                </div>

            <?php endif; ?>

            <div class="select_color_warning"
                 style="display:none; color:darkred; text-align: center; margin-top: 10px;">Вначале выберите цвет
                товара!
            </div>

            <div class="view_price">

                <?php echo $product_info['price'] . ' грн' ?>

                <a href="#" class="add_to_cart" data-product-id="<?php echo $product_info['id']; ?>"
                    <?php
                    if ($ability_to_choose_the_color === 'false') {
                        echo 'data-color-id="1"';
                    }
                    ?>
                >
                    Купить <i class="fas fa-shopping-cart"></i>

                    <span class="check-in-the-cart<?php echo $product_info['id']; ?>">
                    <?php
                    if (isset($_SESSION['cart_product_list'])) {
                        $cartData = unserialize($_SESSION['cart_product_list']);
                        if (isset($cartData[$product_info['id']])) {
                            echo "<i class='far fa-check-square'></i>";
                        }
                    }
                    ?>
                </span>

                </a>


            </div>

            <a href="#" data-id="<?php echo $product_info['id']; ?>" class="add-to-compare">
                Добавить в сравнение <i class="fas fa-balance-scale"></i>

                <span class="check-in-the-compare<?= $product_id ?>">
                    <?php
                    if (isset($_SESSION['compare_product_list'])) {
                        $compareData = unserialize($_SESSION['compare_product_list']);  // тут хранятся айди товаров, добавленных в сравнение

                        foreach ($compareData as $compare_category_products) {
                            if (in_array($product_id, $compare_category_products)) {
                                echo "<i class='far fa-check-square'></i>";
                            }
                        }
                    }
                    ?>
                </span>
            </a>

        </div>
    </div>

    <div class="view_product_description">
        <table class="view_product_table">
            <tr>
                <th colspan="2">
                    <div style="text-align: center"><b>Характеристики</b></div>
                </th>
            </tr>
            <?php foreach ($product_parameters_info as $parameter_name => $value):
                ?>
                <tr>
                    <th><?= $parameter_name ?></th>
                    <th><?= $value ?></th>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="review_container" style="width: 100%;">

        <div class="review_list" style="padding: 10px; border: 1px solid black; text-align: center">
            <h4>Список отзывов</h4>

            <?php

            if (!empty($current_user_review)):

                $product_id = $current_user_review['product_id'];
                $user_id = $current_user_review['user_id'];

                $user_name = User::get_user_name_by_user_id($user_id);


                $review = $current_user_review['review'];
                $rating = $current_user_review['rating'];
                $date = $current_user_review['date'];
                $time = $current_user_review['time'];
                ?>

                <div class="current_user_review" style="">

                    <div class="review_control_container" data-review-id>

                        <b style="flex-grow: 1">Ваш отзыв</b>
                        <span class="review_control">
                <a href="javascript:void(0);" onclick="edit_product_review()" data-review-edit
                   data-is-edited-now="false"><b><i class="far fa-edit" style="color: black"></i></b></a>
                <a href="javascript:void(0);" onclick="delete_product_review()"><b><i
                                class="far fa-times-circle"></i></b></a>
            </span>

                    </div>

                    <div class="review_item">
                        <div class="review_information">
                            <div class="review_user_name"><b><?= $user_name ?></b></div>
                            <div class="review_rating">

                                <?php for ($i = 1; $i <= $rating; $i++): ?>
                                    <div class="active_star"><i class="fa-star far"></i><i class="fa-star fas"></i>
                                    </div>
                                <? endfor; ?>

                                <?php for ($i = 5; $i > $rating; $i--): ?>
                                    <div><i class="fa-star far"></i></div>
                                <? endfor; ?>

                            </div>
                            <div class="review_date"><?= $date ?></div>
                        </div>
                        <div class="review_text"><?= $review ?></div>

                        <div class="review_update_and_cancel" hidden="true">
                            <a href='javascript:void(0);' onclick='update_product_review()'>Сохранить</a>
                            <a href='javascript:void(0);' onclick='cancel_edit_review()'>Отмена</a>
                        </div>

                    </div>

                </div>

            <?php endif; ?>

            <?php foreach ($product_review_list as $product_review):
                $user_id = $product_review['user_id'];
                $user_name = User::get_user_name_by_user_id($user_id);
                $review = $product_review['review'];
                $rating = $product_review['rating'];
                $date = $product_review['date'];
                $time = $product_review['time'];
                $review_id = $product_review['id'];

                $rating_of_product_review = Product::get_rating_of_review($review_id);

                $already_loaded = 0;
                $amount_to_load = 5;
                $review_comments = Product::get_review_comments_range($review_id, $already_loaded, $amount_to_load);
                $already_loaded = $already_loaded + $amount_to_load;

                $comments_amount = Product::get_review_comments_count($review_id);

                $likes_amount = $rating_of_product_review['likes_amount'];
                $dislikes_amount = $rating_of_product_review['dislikes_amount'];

                // проверяем, если при переборе всех отзывов попадается наш отзыв - его пропускаем и не выводим
                if (isset($current_user_review)) {
                    if ($current_user_review['id'] === $review_id) {
                        continue;
                    }
                }

                if (isset($review_likes_list_of_current_user)) {
                    $key = array_search($review_id, array_column($review_likes_list_of_current_user, 'review_id'));

                    if ($key !== false) {
                        $like_or_dislike = $review_likes_list_of_current_user[$key]['vote'];
                    } else {
                        $like_or_dislike = 'not_exist';
                    }
                }

                ?>

                <div class="review_item">
                    <div class="review_information">
                        <div class="review_user_name"><b><?= $user_name ?></b></div>
                        <div class="review_rating">

                            <?php for ($i = 1; $i <= $rating; $i++): ?>
                                <div class="active_star"><i class="fa-star far"></i><i class="fa-star fas"></i></div>
                            <? endfor; ?>

                            <?php for ($i = 5; $i > $rating; $i--): ?>
                                <div><i class="fa-star far"></i></div>
                            <? endfor; ?>

                        </div>
                        <div class="review_date"><?= $date ?></div>
                    </div>
                    <div class="review_text"><?= $review ?></div>

                    <div class="review_comments_controll">
                        <a href="javascript:void(0);" class="show_review_comments">
                            ответы (<span class="review_comments_amount"><?= $comments_amount ?></span>) <i
                                    class="far fa-comment"></i>
                        </a>

                        <a href="javascript:void(0);" class="leave_a_review_comment">Ответить
                            <i style="font-size: 80%" class="fa fa-reply" aria-hidden="true"></i>
                        </a>
                    </div>


                    <div class="show_more_review_comments_container" style="display: none"
                         data-already-loaded="<?= $already_loaded ?>"
                         data-amount-to-load="<?= $amount_to_load ?>"
                         data-comments-amount="<?= $comments_amount ?>"
                         data-review-id="<?= $review_id ?>">
                        <span>
                            <a href="javascript:void(0);" class="show_more_review_comments" style="color: darkred">
                                показать еще
                                <span class="how_many_to_show">
                                    <?php if ($amount_to_load > ($comments_amount - $already_loaded)) {
                                        echo $comments_amount - $already_loaded;
                                    } else {
                                        echo "$amount_to_load";
                                    } ?>
                                </span>
                                из
                                <span class="how_many_left">
                                    <?= $comments_amount - $already_loaded; ?>
                                </span>
                                    комментариев

                            </a>
                        </span>
                    </div>

                    <div class="review_comments_container" data-comments-amount="<?= $comments_amount ?>">

                        <?php foreach ($review_comments as $comment) {
                            $text = $comment['comment'];
                            $user_id = $comment['user_id'];
                            $date = $comment['date'];
                            $user_name = User::get_user_name_by_user_id($user_id);
                            include('/views/product/review_comment_template.php');
                        } ?>

                    </div>


                    <div class="new_review_comment_container">
                        <form class="new_review_comment_form" method="post">
                            Комментарий:
                            <br/><textarea style="width: 70%; margin-top: 5px" name="text"
                                           data-review-id="<?= $review_id ?>"></textarea><br/>
                            <a href="javascript:void(0);"
                               class="save_new_review_comment <?php if ($is_user_logged_in === 'yes') {
                                   echo 'user_is_logged_in';
                               } ?>"
                                <?php
                                if ($is_user_logged_in === 'no') {
                                    echo 'data-toggle="modal" data-target="#please_log_in_to_vote_review"';
                                }
                                ?>>
                                Сохранить
                            </a>
                        </form>
                    </div>


                    <div class="review_vote_rating">

                        <input type="hidden" class="user_vote" value="<?php if (isset($like_or_dislike)) {
                            echo $like_or_dislike;
                        } ?>" data-is-user-logged-in="<?= $is_user_logged_in ?>">

                        <span class="like_amount"><?= $likes_amount ?></span>

                        <a href="javascript:void(0);"
                           style="<?php if (isset($like_or_dislike) && ($like_or_dislike === '1')) {
                               echo 'color:green';
                           } ?>"
                           class="review_vote_like <?php if ($is_user_logged_in === 'yes') {
                               echo 'user_is_logged_in';
                           } ?>"
                           data-review-id="<?= $review_id ?>"
                            <?php
                            if ($is_user_logged_in === 'no') {
                                echo 'data-toggle="modal" data-target="#please_log_in_to_vote_review"';
                            }
                            ?>
                        >
                            <i class="far fa-thumbs-up"></i>
                        </a>

                        <a href="javascript:void(0);"
                           style="<?php if (isset($like_or_dislike) && ($like_or_dislike === '0')) {
                               echo 'color:red';
                           } ?>"
                           class="review_vote_dislike <?php if ($is_user_logged_in === 'yes') {
                               echo 'user_is_logged_in';
                           } ?>"
                           data-review-id="<?= $review_id ?>"
                            <?php
                            if ($is_user_logged_in === 'no') {
                                echo 'data-toggle="modal" data-target="#please_log_in_to_vote_review"';
                            }
                            ?>
                        >
                            <i class="far fa-thumbs-down"></i>
                        </a>

                        <span class="dislike_amount"><?= $dislikes_amount ?></span>

                        <br/>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>

        <div class="review" style="padding: 10px; text-align: center">
            <h4>Отзыв</h4>

            <form name="review_form" action="" method="post">
                <textarea id="text_review_new"></textarea><br/>
            </form>

            <div class="rating_star_container" data-product-id="<?= $product_id ?>">

                <div data-rating="1" class="rating_star">
                    <i class="fa-star far"></i>
                    <i class="fa-star fas"></i>
                </div>

                <div data-rating="2" class="rating_star">
                    <i class="fa-star far"></i>
                    <i class="fa-star fas"></i>
                </div>

                <div data-rating="3" class="rating_star">
                    <i class="fa-star far"></i>
                    <i class="fa-star fas"></i>
                </div>

                <div data-rating="4" class="rating_star">
                    <i class="fa-star far"></i>
                    <i class="fa-star fas"></i>
                </div>

                <div data-rating="5" class="rating_star">
                    <i class="fa-star far"></i>
                    <i class="fa-star fas"></i>
                </div>

            </div>

            <br/>


            <input type="button" form="review_form" name="save_review" value="Отправить"
                   onclick="save_product_review()" <?php if (isset($review_exists)) {
                if ($review_exists === true) {
                    echo "disabled";
                }
            } ?>>

            <br/>
            <br/>
            <?php
            echo "<b>Внимание! <br/> Отзыв можно оставлять только после покупки товара.</b>";
            ?>

        </div>

    </div>


    <div style="border: 1px solid black; width: 100%">

    </div>


</div>

<br/>


<br/>


<!--Модальное окно-->
<div class="modal fade" id="please_log_in_to_vote_review" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Пожалуйста, войдите в систему</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Это действие доступно только зарегистрированым пользователям, вы можете
                <a href="/registration">зарегистрироваться</a> или <a href="/login">войти</a>.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>


<h2 style="text-align: center">Похожие товары</h2>

<!-- Swiper -->
<!--<div class="swiper-container">-->
<!--    <div class="swiper-wrapper">-->
<!--        --><?php //foreach ($productList as $productItem) : ?>
<!--            <div class="swiper-slide">-->
<!--                --><?php
//                $product_id = $productItem['id'];
//                $path = "/images/preview_images/id_{$product_id}_photo_1.jpg";
////                if(!file_exists($path)){
////                    $path = "/images/preview_images/no_photo.png";
////                }
//                ?>
<!--                <div><img src="--><?php //echo $path ?><!--" alt="photo" class="product_photo"/></div>-->
<!--                <div>-->
<!--                    <a href="/product/--><? //= $productItem['id']; ?><!--">-->
<!--                        --><?php //echo $productItem['name']; ?>
<!--                    </a>-->
<!--                </div>-->
<!--                <div class="price"> --><?php //echo $productItem['price'] . ' грн'; ?><!--</div>-->
<!--                <div>-->
<!--                    <form name="cart" action="" method="post"><br/>-->
<!--                        <input type="hidden" name="add_to_cart_product_id" value="--><? //= $productItem['id'] ?><!--">-->
<!--                        <input type="submit" name="add_to_cart" value="Добавить в корзину"><br/>-->
<!--                    </form>-->
<!--                </div>-->
<!--            </div>-->
<!--        --><?php //endforeach; ?>
<!--    </div>-->
<!--    <!-- Add Arrows -->-->
<!--    <div class="swiper-button-next"></div>-->
<!--    <div class="swiper-button-prev"></div>-->
<!--</div>-->


<script src="/template/third_party_files/swiper-4.4.6/dist/js/swiper.min.js"></script>


<!-- Initialize Swiper -->
<!--<script>-->
<!--    var swiper = new Swiper('.swiper-container', {-->
<!--        slidesPerView: 3,-->
<!--        spaceBetween: 10,-->
<!--        navigation: {-->
<!--            nextEl: '.swiper-button-next',-->
<!--            prevEl: '.swiper-button-prev',-->
<!--        },-->
<!---->
<!--        breakpoints: {-->
<!--            // when window width is <= 480px-->
<!--            640: {-->
<!--                slidesPerView: 1,-->
<!--                spaceBetween: 10-->
<!--            },-->
<!--            // when window width is <= 640px-->
<!--            800: {-->
<!--                slidesPerView: 2,-->
<!--                spaceBetween: 20-->
<!--            }-->
<!--        },-->
<!---->
<!--        pagination: {-->
<!--            el: '.swiper-pagination',-->
<!--            type: 'bullets',-->
<!--        },-->
<!--    });-->
<!--</script>-->


<script>
    var swiper_2 = new Swiper('.swiper-container', {
        // slidesPerView: 1,
        preloadImages: false,
        lazy: {
            loadPrevNext: true,
            loadPrevNextAmount: 1,
        },
        pagination: {
            el: '.swiper-pagination',
            // type: 'fraction',
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
</script>


<script src="/template/js/rating_stars.js"></script>
























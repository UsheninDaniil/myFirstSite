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
$element_height = $max_photo_height + $margin + $border_width * 2 + $element_padding * 2;
$photo_on_page = 5;

$slider_height = $photo_on_page * $element_height + $padding_bottom;
$slider_polosa_height = $element_height * $photo_amount + $padding_bottom;

?>


<br/>
<div class="view_product_information_block">

    <div class="view_product_image">

        <div id="product_photos">

            <?php if (count($product_images_list)>1):?>
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
            <?php endif;?>


            <div id="selected_photo_container">

                <?php
                $image_path = "/images/middle_images/id_{$product_id}_photo_1.jpg";
//                echo "$image_path";
                if (file_exists($image_path)) {
//                    echo "файл существует";
                } else{
//                    echo "файла нету";
                }
                ?>
                <img id="selected_photo" src="<?=$image_path?>">

            </div>

        </div>

    </div>

    <div class="view_name_and_price_block">

        <div class="view_product_name"><a href="/product/<?= $product_id ?>"><?php echo $product_info['name']; ?></a>
        </div>
        <div class="view_price"><?php echo $product_info['price'] ?></div>

        <a href="#" data-id="<?php echo $product_info['id']; ?>" class="add-to-cart">
            В корзину <i class="fas fa-shopping-cart"></i>

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

        <a href="#" data-id="<?php echo $product_info['id']; ?>" class="add-to-compare">
            Сравнить <i class="fas fa-balance-scale"></i>

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

    <div class="view_product_description">
        <table border="0" cellpadding="5" class="view_product_table">
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

if(!empty($current_user_review)):

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
            <b>Ваш отзыв</b>
            <span class="review_control">
                <a href="javascript:void(0);" data-review-edit onclick="edit_product_review()"><b><i class="far fa-edit" style="color: black"></i></b></a>
                <a href="javascript:void(0);" onclick="delete_product_review()"><b><i class="far fa-times-circle"></i></b></a>
            </span>
        </div>

        <div class="review_item" style="border: 2px solid red">
            <div class="review_information">
                <div class="review_user_name"><b><?=$user_name?></b></div>
                <div class="review_rating">

                    <?php for ($i = 1; $i <= $rating; $i++):?>
                        <div class="active_star"><i class="fa-star far"></i><i class="fa-star fas"></i></div>
                    <? endfor; ?>

                    <?php for ($i = 5; $i > $rating; $i--):?>
                        <div><i class="fa-star far"></i></div>
                    <? endfor; ?>

                </div>
                <div class="review_date"><?=$date?></div>
            </div>
            <div class="review_text"><?=$review?></div>

        </div>

    </div>

<?php endif;?>


<!--        --><?php //foreach ($product_review_list as $product_review):
//            $user_id = $product_review['user_id'];
//            $user_name = User::get_user_name_by_user_id($user_id);
//            $review = $product_review['review'];
//            $rating = $product_review['rating'];
//            $date = $product_review['date'];
//            $time = $product_review['time'];
//
//            ?>
<!---->
<!--            <div class="review_item">-->
<!--                <div class="review_information">-->
<!--                    <div class="review_user_name"><b>--><?//=$user_name ?><!--</b></div>-->
<!--                    <div class="review_rating">-->
<!---->
<!--                        --><?php //for ($i = 1; $i <= $rating; $i++):?>
<!--                            <div class="active_star"><i class="fa-star far"></i><i class="fa-star fas"></i></div>-->
<!--                        --><?// endfor; ?>
<!---->
<!--                        --><?php //for ($i = 5; $i > $rating; $i--):?>
<!--                            <div><i class="fa-star far"></i></div>-->
<!--                        --><?// endfor; ?>
<!---->
<!--                    </div>-->
<!--                    <div class="review_date">--><?//=$date ?><!--</div>-->
<!--                </div>-->
<!--                <div class="review_text">--><?//=$review ?><!--</div>-->
<!--            </div>-->
<!---->
<!--        --><?php //endforeach; ?>

    </div>

    <div class="review" style="padding: 10px; text-align: center">
        <h4>Отзыв</h4>

        <form name="review_form" action="" method="post">
            <textarea id="text_review_new"></textarea><br/>
        </form>

        <div class="rating_star_container" data-product-id="<?=$product_id?>">

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



        <input  type="button" form="review_form" name ="save_review" value="Отправить" onclick="save_product_review()" <?php if(isset($review_exists)){if($review_exists === true){echo "disabled";}}?>>

        <br/>
        <br/>
        <?php
        echo "<b>Внимание! <br/> Отзыв можно оставлять только после покупки товара.</b>";
        ?>

    </div>

    </div>


</div>

<br/>






<br/>

<h2 style="text-align: center">Похожие товары</h2>

<!-- Swiper -->
<div class="swiper-container">
    <div class="swiper-wrapper">
        <?php foreach ($productList as $productItem) : ?>
            <div class="swiper-slide">
                <?php
                $product_id = $productItem['id'];
                $path = "/images/preview_images/id_{$product_id}_photo_1.jpg";
//                if(!file_exists($path)){
//                    $path = "/images/preview_images/no_photo.png";
//                }
                ?>
                <div><img src="<?php echo $path ?>" alt="photo" class="product_photo"/></div>
                <div>
                    <a href="/product/<?= $productItem['id']; ?>">
                        <?php echo $productItem['name']; ?>
                    </a>
                </div>
                <div class="price"> <?php echo $productItem['price'] . ' грн'; ?></div>
                <div>
                    <form name="cart" action="" method="post"><br/>
                        <input type="hidden" name="add_to_cart_product_id" value="<?= $productItem['id'] ?>">
                        <input type="submit" name="add_to_cart" value="Добавить в корзину"><br/>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- Add Arrows -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>


<script src="/template/third_party_files/swiper-4.4.6/dist/js/swiper.min.js"></script>


<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 3,
        spaceBetween: 10,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
</script>




<script src="/template/js/rating_stars.js"></script>
























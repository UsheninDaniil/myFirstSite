
<br />

<div class="side_menu">
    <h2 class="headline">Каталог</h2>

    <div style="border-top: 1px solid lightgray;">

    <?php foreach ($categoryList as $categoryItem) : ?>

    <div class="category_field">

            <a href="/category/<?= $categoryItem['id']; ?>" class="category_name">
                <?php echo $categoryItem['name']; ?>
            </a>

    </div>

    <?php endforeach; ?>

    </div>

</div>


<div class="product_block">
    <h2 class="headline">Товары</h2>

    <div class="product_list">

    <?php foreach ($productList as $productItem) : ?>

        <div class="product_field">

        <div class="product_inside_field">

            <?php
            $product_id =$productItem['id'];
            if (file_exists(ROOT."/images/small_product_images/$product_id.jpg")) {
                $path = "/images/small_product_images/$product_id.jpg";
            }
            else {
                $path = "/images/small_product_images/no_photo.png";
            }
            ?>

            <div >

            <a href="/product/<?= $productItem['id']; ?>" >
                <img src= "<?php echo $path ?>" alt="photo" class="product_photo" >
            </a>

            </div>

            <a href="/product/<?= $productItem['id']; ?>" class="product-name">
                <?php echo $productItem['name']; ?>
            </a>
            <div class="price"> <?php echo $productItem['price'].' грн'; ?></div>

            <a href="#" data-id="<?php echo $productItem['id']; ?>" class="add-to-cart">
                В корзину <i class="fas fa-shopping-cart"></i>

                <span class="check-in-the-cart<?php echo $productItem['id']; ?>">
                    <?php
                    if (isset($_SESSION['cart_product_list'])){
                        $cartData = unserialize($_SESSION['cart_product_list']);
                        if (isset($cartData[$productItem['id']])){
                            echo "<i class='far fa-check-square'></i>";
                        }
                    }
                    ?>
                </span>

            </a>

            <div>
            <a href="#" data-id="<?php echo $productItem['id']; ?>" class="add-to-compare">
                Сравнить <i class="fas fa-balance-scale"></i>

                <span class="check-in-the-compare<?=$product_id?>">
                    <?php
                    if (isset($_SESSION['compare_product_list'])){
                        $compareData = unserialize($_SESSION['compare_product_list']);  // тут хранятся айди товаров, добавленных в сравнение

                        foreach ($compareData as $compare_category_products){
                            if(in_array ($product_id, $compare_category_products)){
                                echo "<i class='far fa-check-square'></i>";
                            }
                        }
                    }
                    ?>
                </span>
            </a>
            </div>

        </div>

        </div>

    <?php endforeach; ?>

    </div>

<!--  <div style="clear:both;"></div>-->

</div>



<div class="pagination_buttons" style="text-align: center">

<?php

// Проверяем нужны ли стрелки назад
if ($page != 1) {
        $pervpage = '<a href= ./page=1><<</a> 
                     <a href= ./page=' . ($page - 1) . '><</a> ';
    } else{
        $pervpage = '';
    }
// Проверяем нужны ли стрелки вперед
if ($page != $total) {
        $nextpage = ' <a href= ./page='. ($page + 1) .'>></a> 
                      <a href= ./page=' .$total. '>>></a>';
    } else {
        $nextpage = '';
    }

// Находим две ближайшие станицы с обоих краев, если они есть
if($page - 2 > 0) {$page2left = ' <a href= ./page='. ($page - 2) .'>'. ($page - 2) .'</a> | ';} else {$page2left = '';};
if($page - 1 > 0) {$page1left = '<a href= ./page='. ($page - 1) .'>'. ($page - 1) .'</a> | ';} else {$page1left = '';};
if($page + 2 <= $total) {$page2right = ' | <a href= ./page='. ($page + 2) .'>'. ($page + 2) .'</a>';} else {$page2right ='';};
if($page + 1 <= $total) {$page1right = ' | <a href= ./page='. ($page + 1) .'>'. ($page + 1) .'</a>';} else {$page1right ='';};

// Вывод меню
echo $pervpage.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$nextpage;

?>


</div>












<!--<div class="swiper-container">-->
<!--    <div class="swiper-wrapper">-->
<!--        --><?php //foreach ($productList as $productItem) : ?>
<!--        <div class="swiper-slide">-->
<!--            --><?php
//            $product_id =$productItem['id'];
//            $path = "/images/small_product_images/"."$product_id.jpg";
//            ?>
<!--            <div><img src= "--><?php //echo $path ?><!--" alt="photo" class="product_photo" /></div>-->
<!--            <div>-->
<!--                <a href="/product/--><?//= $productItem['id']; ?><!--">-->
<!--                    --><?php //echo $productItem['name']; ?>
<!--                </a>-->
<!--            </div>-->
<!--            <div class="price"> --><?php //echo $productItem['price'].' грн'; ?><!--</div>-->
<!--            <div>-->
<!--                <form name = "cart" action="" method ="post"><br />-->
<!--                    <input type="hidden" name="add_to_cart_product_id" value="--><?//= $productItem['id'] ?><!--">-->
<!--                    <input type = "submit" name ="add_to_cart" value="Добавить в корзину"><br />-->
<!--                </form>-->
<!--            </div>-->
<!--        </div>-->
<!--        --><?php //endforeach; ?>
<!--    </div>-->
<!--    <div class="swiper-button-next"></div>-->
<!--    <div class="swiper-button-prev"></div>-->
<!--</div>-->

<!-- Swiper JS -->
<script src="../template/swiper-4.4.6/dist/js/swiper.min.js"></script>

<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper('.swiper-container', {
        slidesPerView:3,
        spaceBetween: 10,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
</script>




















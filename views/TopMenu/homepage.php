<h2 class="headline">Главная страница</h2>

<div class="side_menu">
    <h2 class="headline">Каталог</h2>



    <!-- разбирает двумерный массив $categoryList по элментам, то есть переменная $categoryItem будет по порядку использоваться для хранения асоциативных массивов-->
    <?php foreach ($categoryList as $categoryItem) : ?>

    <div class="category_field">

        <div class="name">
            <a href="/category/<?= $categoryItem['id']; ?>">
                <?php echo $categoryItem['name']; ?>
            </a>
        </div>

    </div>

    <?php endforeach; ?>



</div>


<div class="product_block">
    <h2 class="headline">Товары</h2>

    <div class="product_list">

    <?php foreach ($productList as $productItem) : ?>

        <div class="product_field">

        <div class="product_inside_field">

            <?php
            $product_id =$productItem['id'];
            if (file_exists(ROOT."/images/$product_id.jpg")) {
                $path = "/images/$product_id.jpg";
            }
            else {
                $path = "/images/no_photo.png";
            }
            ?>

            <a href="/product/<?= $productItem['id']; ?>" >
                <img src= "<?php echo $path ?>" alt="photo" class="product_photo" />
            </a>

            <a href="/product/<?= $productItem['id']; ?>" class="product-name">
                <?php echo $productItem['name']; ?>
            </a>
            <div class="price"> <?php echo $productItem['price'].' грн'; ?></div>

            <a href="#" data-id="<?php echo $productItem['id']; ?>" class="add-to-cart">
                В корзину <span class="glyphicon glyphicon-shopping-cart"></span>

                <span class="check-in-the-cart<?php echo $productItem['id']; ?>">
                    <?php
                    if (isset($_SESSION['cart_product_list'])){
                        $cartData = unserialize($_SESSION['cart_product_list']);
                        if (isset($cartData[$productItem['id']])){
                            echo "<span class=\"glyphicon glyphicon-check\"></span>";
                        }
                    }
                    ?>
                </span>

            </a>

            <div>
            <a href="#" data-id="<?php echo $productItem['id']; ?>" class="add-to-compare">
                Сравнить <span class="glyphicon glyphicon-stats"></span>

                <span class="check-in-the-compare<?php echo $productItem['id']; ?>">
                    <?php
                    if (isset($_SESSION['compare_product_list'])){
                        $compareData = unserialize($_SESSION['compare_product_list']);
                        if (isset($compareData[$productItem['id']])){
                            echo "<span class=\"glyphicon glyphicon-check\"></span>";
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





<!-- Swiper -->
<div class="swiper-container">
    <div class="swiper-wrapper">
        <?php foreach ($productList as $productItem) : ?>
        <div class="swiper-slide">
            <?php
            $product_id =$productItem['id'];
            $path = "/images/"."$product_id.jpg";
            ?>
            <div><img src= "<?php echo $path ?>" alt="photo" class="product_photo" /></div>
            <div>
                <a href="/product/<?= $productItem['id']; ?>">
                    <?php echo $productItem['name']; ?>
                </a>
            </div>
            <div class="price"> <?php echo $productItem['price'].' грн'; ?></div>
            <div>
                <form name = "cart" action="" method ="post"><br />
                    <input type="hidden" name="add_to_cart_product_id" value="<?= $productItem['id'] ?>">
                    <input type = "submit" name ="add_to_cart" value="Добавить в корзину"><br />
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <!-- Add Arrows -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

<!-- Swiper JS -->
<!--<script src="../dist/js/swiper.min.js"></script>-->
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




















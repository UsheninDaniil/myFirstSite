
    <br />
    <div class="view_product_information_block">

        <div class="view_product_image">

            <?php
            if (file_exists(ROOT."/images/small_product_images/$product_id.jpg")) {
                $path = "/images/small_product_images/$product_id.jpg";
            }
            else {
                $path = "/images/no_photo.png";
            }
            ?>



            <img src="<?php echo $path ?>" alt="photo" class="product_photo"/>

        </div>

        <div class="view_name_and_price_block">

            <div class="view_product_name"><a href="/product/<?=$product_id?>"><?php echo $product_info['name'] ;?></a></div>
            <div class="view_price"><?php echo $product_info['price'] ?></div>

            <a href="#" data-id="<?php echo $product_info['id']; ?>" class="add-to-cart">
                В корзину <i class="fas fa-shopping-cart"></i>

                <span class="check-in-the-cart<?php echo $product_info['id']; ?>">
                    <?php
                    if (isset($_SESSION['cart_product_list'])){
                        $cartData = unserialize($_SESSION['cart_product_list']);
                        if (isset($cartData[$product_info['id']])){
                            echo "<i class='far fa-check-square'></i>";
                        }
                    }
                    ?>
                </span>

            </a>

            <a href="#" data-id="<?php echo $product_info['id']; ?>" class="add-to-compare">
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



        <div class="view_product_description">


            <table border="0" cellpadding="5" class="view_product_table">

                <tr>
                    <th colspan="2">
                    <div style="text-align: center"><b>Характеристики</b></div>
                    </th>
                </tr>
                <?php  foreach ($product_parameters_info as $parameter_name => $value):
                    ?>
                    <tr>
                        <th><?= $parameter_name ?></th>
                        <th><?= $value ?></th>
                    </tr>
                <?php endforeach;?>

            </table>

        </div>

    </div>

<h2 style="text-align: center">Похожие товары</h2>

<!-- Swiper -->
<div class="swiper-container">
    <div class="swiper-wrapper">
        <?php foreach ($productList as $productItem) : ?>
            <div class="swiper-slide">
                <?php
                $product_id =$productItem['id'];
                $path = "/images/small_product_images/"."$product_id.jpg";
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

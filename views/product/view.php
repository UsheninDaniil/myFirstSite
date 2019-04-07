
    <br />
    <div class="view_product_information_block">

        <div class="view_product_image">

            <?php $path = "/images/$product_id.jpg"; ?>

            <img src="<?php echo $path ?>" alt="photo" class="product_photo"/>

        </div>

        <div class="view_name_and_price_block">

            <div class="view_product_name"><?php echo $product_info['name'] ;?></div>
            <div class="view_price"><?php echo $product_info['price'] ?></div>

            <form name = "cart" action="" method ="post"><br />
                <input type = "submit" name ="add_to_cart" value="Добавить в корзину"><br />
            </form>
        </div>

        <div class="view_product_description">
            <table border="1" width="50%" cellpadding="5" class="product_list_table">
                <tr>
                    <th>Параметр</th>
                    <th>Значение</th>
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

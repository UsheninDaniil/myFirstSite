<h2 class="headline"><?= $category_name?></h2>

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
                    $path = "/images/"."$product_id.jpg";
                    ?>

                    <img src= "<?php echo $path ?>" alt="photo" class="product_photo" />

                    <a href="/product/<?= $productItem['id']; ?>">
                        <?php echo $productItem['name']; ?>
                    </a>
                    <div class="price"> <?php echo $productItem['price'].' грн'; ?></div>

                    <form name = "cart" action="" method ="post"><br />
                        <input type="hidden" name="add_to_cart_product_id" value="<?= $productItem['id'] ?>">
                        <input type = "submit" name ="add_to_cart" value="Добавить в корзину"><br />
                    </form>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

    <!--  <div style="clear:both;"></div>-->

</div>























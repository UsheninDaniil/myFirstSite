
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
            if (file_exists(ROOT."/images/preview_images/id_{$product_id}_photo_1.jpg")) {
                $path = "/images/preview_images/id_{$product_id}_photo_1.jpg";
            }
            else {
                $path = "/images/no_photo.png";
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

</div>



<?php
echo "<br />";
echo $pagination->build_pagination($total_count, $current_page_number, $limit);
?>
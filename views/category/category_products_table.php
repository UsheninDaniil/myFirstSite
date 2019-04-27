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

                <div>

                    <a href="/product/<?= $productItem['id']; ?>" >
                        <img src= "<?php echo $path ?>" alt="photo" class="product_photo" />
                    </a>

                </div>

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
                    <a href="javascript:void(0);" data-id="<?php echo $productItem['id']; ?>" class="add-to-compare">
                        Сравнить <span class="glyphicon glyphicon-stats"></span>

                        <span class="check-in-the-compare<?=$product_id?>">
                    <?php
                    if (isset($_SESSION['compare_product_list'])){
                        $compareData = unserialize($_SESSION['compare_product_list']);  // тут хранятся айди товаров, добавленных в сравнение

                        foreach ($compareData as $compare_category_products){
                            if(in_array ($product_id, $compare_category_products)){
                                echo "<span class='glyphicon glyphicon-check'></span>";
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

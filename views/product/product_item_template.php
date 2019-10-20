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

        $ability_to_choose_the_color = Color::check_is_there_ability_to_choose_the_color($product_id);
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

        <div class="product_rating_container">

            <div class="product_rating">

                <?php for ($i = 1; $i <= $productItem['rating']; $i++):?>
                    <div class="active_star"><i class="fa-star far"></i><i class="fa-star fas"></i></div>
                <? endfor; ?>

                <?php for ($i = 5; $i > $productItem['rating']; $i--):?>
                    <div><i class="fa-star far"></i></div>
                <? endfor; ?>

            </div>

        </div>

            <a href="javascript:void(0);" class="add_to_cart_modal"
               data-toggle="modal"
               data-target="#addToCartModal"
               data-product-id="<?php echo $productItem['id']; ?>"
               data-ability-to-choose-the-color="<?php if($ability_to_choose_the_color === 'true'){echo 'true';}else{echo 'false';}?>"
            >
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
            <a href="javascript:void(0);" data-id="<?php echo $productItem['id']; ?>" class="add-to-compare">
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
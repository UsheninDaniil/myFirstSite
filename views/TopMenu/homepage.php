
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

        <?php

            foreach ($productList as $productItem){
                include('/views/product/product_item_template.php');
            }
        ?>

    </div>

</div>



<?php
echo "<br />";
echo $pagination->build_pagination();
?>


<?php
include_once('/views/product/select_product_color_modal.php');
?>




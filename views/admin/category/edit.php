<?php

require_once (ROOT. '/models/Category.php');
$categoryList = Category::get_category_list();

?>


<form  enctype="multipart/form-data" name = "add_product" action="" method ="post" class="feedback">


    <label>Выберите категрию:</label><br />
    <select name="product_category_id" onchange="edit_category()" id="product_category_id">
        <?php if(is_array($categoryList)): ?>
            <?php foreach ($categoryList as $category): ?>
                <option value="<?= $category['id']; ?>">
                    <?= $category['name']; ?>
                </option>
            <?php endforeach;?>
        <?php endif;?>
    </select><br />
</form>

<div class="replace">текст</div>


<div class="remove_info"></div>




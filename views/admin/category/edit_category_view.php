<?php
require_once (ROOT. '/models/Category.php');
$categoryList = Category::get_category_list();
?>

<br />
<a href="/admin" class="go-back-to-admin-panel" /><b>Панель администратора</b></a>
→
<a href="/admin/edit_category" class="go-back-to-admin-panel"/><b>Управление категориями</b></a>

<form  enctype="multipart/form-data" name = "add_product" action="" method ="post" class="feedback">


    <br /><label>Категрия:</label><br />
    <select name="product_category_id" onchange="edit_category()" id="product_category_id">
        <option>Выберите категорию</option>
        <?php if(is_array($categoryList)): ?>
            <?php foreach ($categoryList as $category): ?>
                <option value="<?= $category['id']; ?>">
                    <?= $category['name']; ?>
                </option>
            <?php endforeach;?>
        <?php endif;?>
    </select><br />
</form>

<div class="replace"></div>


<div class="remove_info"></div>




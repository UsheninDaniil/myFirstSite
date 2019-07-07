<?php

require_once (ROOT. '/models/Category.php');
$categoryList = Category::get_category_list();

?>

<div class="container_with_breadcrumb">


<div class="breadcrumb">
    <a href="/admin" class="go-back-to-admin-panel" /><b>Панель администратора</b></a>
    →
    <a href="/admin/edit_products" class="go-back-to-admin-panel"/><b>Управление товарами</b></a>
    →
    <a href="/admin/add_product" class="go-back-to-admin-panel"/><b>Добавление товара</b></a>
</div>

<div class="add_new_product_main_container">

<h4 style="text-align: center">Добавить новый товар</h4>

<form  enctype="multipart/form-data" name="add_product" id="add_product" action="" method ="post" class="feedback">

    <label>Название товара:</label><br />
    <input type="text" name="product_name" /><br />

    <label>Цена:</label><br />
    <input type="text" name="product_price" /><br />

    <label>Есть в наличии?</label><br />
    <input name="product_availability" type="radio" value="1" checked>Да
    <input name="product_availability" type="radio" value="0">Нет
    <br /><br />

    <label>Категория:</label><br />
    <select name="product_category_id" onchange="load_category_parameters()" id="load_category_parameters_to_add_product">
        <option>Выберите категорию</option>
        <?php if(is_array($categoryList)): ?>
            <?php foreach ($categoryList as $category): ?>
                <option value="<?= $category['id']; ?>">
                    <?= $category['name']; ?>
                </option>
            <?php endforeach;?>
        <?php endif;?>
    </select><br />

    <div class="category_parameters_list" id="category_parameters_list"></div>

    <br />Выберите изображение: <br />
    <input type="hidden" name="image_names" value="" id="image_names">
    <input type="file" name="images[]" id="input" multiple onchange="handleFiles(this.files)">
</form>

<div id="images_container">
    <div id="test">&nbsp</div>
</div>

<div style="text-align: center">
    <input type="submit" name ="save_new_product" value="Добавить товар" form="add_product"><br />
</div>

</div>

</div>
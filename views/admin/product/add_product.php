<?php

if(isset($_POST["send"])){

    $product_information['product_name'] = htmlspecialchars ($_POST['product_name']);
    $product_information['product_price'] = htmlspecialchars($_POST['product_price']);
    $product_information['product_description'] = htmlspecialchars($_POST['product_description']);
    $product_information['product_category'] = htmlspecialchars($_POST['product_category_id']);

    $product_id = Admin::add_new_product($product_information);

    if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
        // Если загружалось, переместим его в нужную папке, дадим новое имя
//        $photo
        move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/images/$photo$product_id.jpg");

        echo "Фото загружено";

    }
    echo 'Создан файл с айди '.$product_id;
}

require_once (ROOT. '/models/Category.php');
$categoryList = Category::get_category_list();

?>

<br />
<a href="/admin" class="go-back-to-admin-panel" /><b>Панель администратора</b></a>
→
<a href="/admin/edit_products" class="go-back-to-admin-panel"/><b>Управление товарами</b></a>
→
<a href="/admin/add_product" class="go-back-to-admin-panel"/><b>Добавление товара</b></a>

<h4>Добавить новый товар</h4>

<form  enctype="multipart/form-data" name = "add_product" action="" method ="post" class="feedback">

    <label>Название товара:</label><br />
    <input type="text" name="product_name" /><br />

    <label>Категория:</label><br />
    <select name="product_category_id">
        <?php if(is_array($categoryList)): ?>
            <?php foreach ($categoryList as $category): ?>
                <option value="<?= $category['id']; ?>">
                    <?= $category['name']; ?>
                </option>
            <?php endforeach;?>
        <?php endif;?>
    </select><br />

    <label>Цена:</label><br />
    <input type="text" name="product_price" /><br />

    <label>Описание товара:</label><br />
    <textarea name = "product_description" colls ="30" rows="10"></textarea><br /><br />


<!--    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />-->


    Выберите изображение: <br /><br />
    <input name="image" type="file" /><br /><br />


    <input type = "submit" name ="send" value="Добавить товар "><br />

</form>


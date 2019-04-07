<?php
include_once ('/models/Product.php');
$productList = Product::get_product_list();
?>


<h2>Панель администратора</h2>

<div>
<a href="/admin/add">Добавить товар</a>
</div>

<div>
    <a href="/admin/edit_category">Редактировать категорию</a>
</div>

<table border="1" width="50%" cellpadding="5" class="product_list_table">

    <tr>
        <th>Id</th>
        <th>Название</th>
        <th>Цена</th>
        <th colspan="2">Действие</th>
    </tr>

    <?php foreach ($productList as $product) : ?>

    <tr>
        <td><?= $product['id'] ?></td>
        <td><a href="/product/<?= $product['id']?> "> <?= $product['name'] ?></a></td>
        <td><?= $product['price'] ?></td>
        <td><a href="/admin/delete/<?= $product['id']?>">Удалить</a></td>
        <td><a href="/admin/edit/<?= $product['id']?>">Редактировать</a></td>

    </tr>

    <?php endforeach; ?>

</table>

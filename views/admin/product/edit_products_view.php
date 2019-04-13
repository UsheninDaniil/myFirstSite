
<br />
<a href="/admin" class="go-back-to-admin-panel" /><b>Панель администратора</b></a>
→
<a href="/admin/edit_products" class="go-back-to-admin-panel"/><b>Управление товарами</b></a>

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
            <td><a href="/admin/delete_product/<?= $product['id']?>"><span class="glyphicon glyphicon-remove"></span></a></td>
            <td><a href="/admin/edit_poduct/<?= $product['id']?>"><span class="glyphicon glyphicon-pencil"></span></a></td>

        </tr>

    <?php endforeach; ?>

    <tr>
        <td colspan="5"><a href="/admin/add_product">Добавить товар</a></td>
    </tr>

</table>
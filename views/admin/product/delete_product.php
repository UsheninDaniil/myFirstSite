
<div class="container_with_breadcrumb">


<div class="breadcrumb">
    <a href="/admin" class="go-back-to-admin-panel" /><b>Панель администратора</b></a>
    →
    <a href="/admin/edit_products" class="go-back-to-admin-panel"/><b>Управление товарами</b></a>
    →
    <a href="/admin/delete_product/<?=$product_id?>" class="go-back-to-admin-panel"/><b>Удаление товара #<?=$product_id?></b></a>
</div>

<form name = "delete product" action="" method ="post"><br />

    <input type="submit" name="delete_product" value="Удалить товар №<?= $product_id ?>" />

</form>

</div>



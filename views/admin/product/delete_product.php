

<br />
<a href="/admin" class="go-back-to-admin-panel" /><b>Панель администратора</b></a>
→
<a href="/admin/edit_products" class="go-back-to-admin-panel"/><b>Управление товарами</b></a>
→
<a href="/admin/delete_product/<?=$product_id?>" class="go-back-to-admin-panel"/><b>Удаление товара #<?=$product_id?></b></a>

<?php

require_once('/models/Product.php');


if (isset($_POST["delete_product"])){

$mysqli = new mysqli ("localhost", "root", "","myFirstSite");
$mysqli->query ("SET NAMES 'utf8'");
$result = $mysqli->query ("DELETE FROM `myFirstSite`.`product` WHERE id = '$product_id'");

$mysqli->close();

if(file_exists(ROOT."/images/$product_id.jpg")){
    unlink(ROOT."/images/$product_id.jpg");
}

echo "<br /><br />Товар $product_id удален";
}

?>


<form name = "delete product" action="" method ="post"><br />

    <input type="submit" name="delete_product" value="Удалить товар №<?= $product_id ?>" />

</form>






<h2>Панель администратора</h2>
<h3>Удаление товара</h3>

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

echo "Товар $product_id удален";
}

?>


<form name = "delete product" action="" method ="post">

    <input type="submit" name="delete_product" value="Удалить товар №<?= $product_id ?>" />

</form>

<br /><a href="/admin">Вернуться в панель администратора</a>


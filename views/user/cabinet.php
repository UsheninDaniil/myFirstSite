<?php

require_once(ROOT. '/models/Product.php');
require_once(ROOT. '/models/Admin.php');

$user_id = $_SESSION["user_id"];
$user_role = Admin::check_user_role($user_id);

if(isset($_POST["logout"])) {
    session_destroy();
    echo "Вы вышли из учетной записи.";
    // header ("Location: index.php");
}

$order_list=User::get_order_list_by_user_id($user_id);

?>

<h2>Личный кабинет</h2>

<?php
    echo "Добро пожаловать ".$_SESSION["login"];
    echo "<br />Права: $user_role";
?>

    <form name = "logout_button" action="" method ="post"><br />
        <input type = "submit" name ="logout" value="Выйти из учетной записи">
    </form><br />

<?php
    if ($user_role = "admin"): ?>
    <div>
    <a href ="/admin"> Панель администратора </a>
    </div><br />
    <?php endif;
    ?>


<?php
if (!empty($order_list)): ?>

<div style="text-align: center;">Список заказов</div>


<div style="display: flex; justify-content: center;">

<table style="text-align: center;" border="1" cellpadding="5">

    <tr>
        <th rowspan="2">id</th>
        <th rowspan="2">Дата</th>
        <th rowspan="2">Время</th>
        <th rowspan="2">Статус</th>
        <th colspan="4">Товары</th>
    </tr>

    <tr>
        <td>id</td>
        <td>name</td>
        <td>amount</td>
        <td>price</td>
    </tr>


<!--    Получает спиоок заказов    -->
<?php  foreach ($order_list as $order):?>
    <tr>
        <?php
        $product_list = User::get_product_list_by_order_id_and_user_id($order['id'], $user_id);
        $product_amount = count($product_list);

        $first_product_id = $product_list[0]['product_id'];
        $product_information = Product::get_product_by_id($first_product_id);
        $first_product_name = $product_information['name'];
        $first_product_amount = $product_list[0]['product_amount'];
        $first_product_price = $product_list[0]['product_price'];

        unset($product_list[0]);
        ?>

        <td rowspan="<?=$product_amount?>"><?php echo $order['id']; ?></td>
        <td rowspan="<?=$product_amount?>"><?php echo $order['date']?></a></td>
        <td rowspan="<?=$product_amount?>"><?php echo $order['time']?></td>
        <td rowspan="<?=$product_amount?>"><?php echo $order['status']?></td>
        <td><?=$first_product_id ?></td>
        <td><a href="/product/<?=$first_product_id?>"><?=$first_product_name ?></a></td>
        <td><?=$first_product_amount." шт."?></td>
        <td><?=$first_product_price. " грн" ?></td>

    </tr>
        <!--    Получает список товаров в заказе-->
        <?php foreach ($product_list as $product):
        $product_id = $product['product_id'];
        $product_information = Product::get_product_by_id($product_id);
        $product_name = $product_information['name'];
        $product_amount = $product['product_amount'];
        $product_price = $product['product_price']; ?>

        <tr>
            <td><?=$product_id ?></td>
            <td><a href="/product/<?=$product_id?>"><?=$product_name ?></a></td>
            <td><?=$product_amount." шт."?></td>
            <td><?=$product_price. " грн" ?></td>
        </tr>

        <?php endforeach;?>

<?php endforeach;?>

</table>

</div>
<?php endif;?>
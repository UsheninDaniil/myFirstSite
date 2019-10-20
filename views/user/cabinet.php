

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
        <th colspan="5">Товары</th>
    </tr>

    <tr>
        <td>id</td>
        <td>name</td>
        <td>color</td>
        <td>amount</td>
        <td>price</td>
    </tr>


<!--    Получает спиоок заказов    -->
<?php  foreach ($order_list as $order):?>
    <tr>
        <?php
        $product_list = User::get_product_list_by_order_id_and_user_id($order['id'], $user_id);
        $total_product_amount = count($product_list);

        $product_id = $product_list[0]['product_id'];
        $product_information = Product::get_product_by_id($product_id);
        $product_name = $product_information['name'];
        $color_id = $product_list[0]['color_id'];
        $product_amount = $product_list[0]['product_amount'];
        $product_price = $product_list[0]['product_price'];
        $hex_code = Color::get_hex_code_by_color_id($color_id);

        unset($product_list[0]);
        ?>

        <td rowspan="<?=$total_product_amount?>"><?php echo $order['id']; ?></td>
        <td rowspan="<?=$total_product_amount?>"><?php echo $order['date']?></a></td>
        <td rowspan="<?=$total_product_amount?>"><?php echo $order['time']?></td>
        <td rowspan="<?=$total_product_amount?>"><?php echo $order['status']?></td>

        <?php
        include('/views/user/order_row_template.php');
        ?>

    </tr>
        <!--    Получает список товаров в заказе-->
        <?php foreach ($product_list as $product):
            $product_id = $product['product_id'];
            $product_information = Product::get_product_by_id($product_id);
            $product_name = $product_information['name'];
            $color_id = $product['color_id'];
            $product_amount = $product['product_amount'];
            $product_price = $product['product_price'];
            $hex_code = Color::get_hex_code_by_color_id($color_id); ?>
        <tr>
            <?php include('/views/user/order_row_template.php'); ?>
        </tr>

        <?php endforeach; ?>


<?php endforeach;?>

</table>

</div>
<?php endif;?>
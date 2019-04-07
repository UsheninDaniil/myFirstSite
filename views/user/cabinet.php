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

<div style="text-align: center">Список заказов</div>

<table border="1" width="50%" cellpadding="5" class="product_list_table">

    <tr>
        <th>id заказа</th>
        <th>Дата</th>
        <th>Время</th>
        <th>Статус</th>
        <th colspan="2">Содержимое</th>
    </tr>


<!--    Получает спиоок заказов    -->
    <?php  foreach ($order_list as $order):?>

                <?php
                $list=unserialize($order['description']);
                $amount_of_products_in_the_order = count($list);
                ?>

<!--    Получает список товаров в заказе-->

                    <tr>
                    <td rowspan="<?= $amount_of_products_in_the_order?>"><?php echo $order['id']; ?></td>
                    <td rowspan="<?= $amount_of_products_in_the_order?>"><?php echo $order['date']?></a></td>
                    <td rowspan="<?= $amount_of_products_in_the_order?>"><?php echo $order['time']?></td>
                    <td rowspan="<?= $amount_of_products_in_the_order?>"><?php echo $order['status']?></td>
                    <td>
                         <?php
//                         print_r($list);
                         $first_product_id = key($list);
                         $first_product_amount = $list[$first_product_id];
                         unset($list[$first_product_id]);
                         $product_information = Product::get_product_by_id($first_product_id);
                         $product_name = $product_information['name'];
                         echo "<a href='/product/$first_product_id'>$product_name</a>";
                         ;?>
                    </td>
                    <td>
                        <?php
                        echo "$first_product_amount шт.";
                        ?>
                    </td>
                    </tr>

                <?php foreach ($list as $product_id =>$amount):
                    $product_information = Product::get_product_by_id($product_id);
//                    print_r($product_information);
                    $product_name = $product_information['name'];
                ?>

                    <tr>
                    <td>
                        <?php
                        echo "<a href='/product/$product_id'>$product_name</a>";?>
                    </td>
                        <td>
                            <?php echo "$amount шт.";?>
                        </td>
                    </tr>

                <?php endforeach;?>

    <?php endforeach;?>

</table>
<?php endif;?>
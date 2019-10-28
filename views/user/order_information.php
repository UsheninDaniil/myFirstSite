
<div class="container_with_breadcrumb">

    <div class="breadcrumb">
        <a href="/cabinet" class="go-back-to-admin-panel" ><b>Личный кабинет</b></a>
        →
        <a href="/cabinet/orders_list" class="go-back-to-admin-panel"><b>Список заказов</b></a>
        →
        <a href="/admin/edit_category" class="go-back-to-admin-panel"><b>Заказ #<?= $order_information['id'] ?></b></a>
    </div>

</div>


<br/>
<br/>

<table style="text-align: center;" border="1" cellpadding="5" class="review_information_table">

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

    <?php $total_product_amount = count($product_list)+1;?>

    <td rowspan="<?=$total_product_amount?>"><?=$order_information['id'] ?></td>
    <td rowspan="<?=$total_product_amount?>"><?=$order_information['date']?></td>
    <td rowspan="<?=$total_product_amount?>"><?=$order_information['time']?></td>
    <td rowspan="<?=$total_product_amount?>"><?=$order_information['status']?></td>

    <?php foreach ($product_list as $product):
        $product_id = $product['product_id'];
        $product_information = Product::get_product_by_id($product_id);
        $product_name = $product_information['name'];
        $color_id = $product['color_id'];
        $product_amount = $product['product_amount'];
        $product_price = $product['product_price'];
        $hex_code = Color::get_hex_code_by_color_id($color_id); ?>
        <tr>

            <td><?=$product_id?></td>
            <td><a href="/product/<?=$product_id?>"> <?=$product_name?></a></td>
            <td><?=$color_id?></td>
            <td><?=$product_amount?></td>
            <td><?=$product_price?></td>
        </tr>

    <?php endforeach; ?>


</table>
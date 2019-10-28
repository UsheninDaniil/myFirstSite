

<div class="container_with_breadcrumb">

    <div class="breadcrumb">
        <a href="/admin" class="go-back-to-admin-panel" /><b>Панель администратора</b></a>
        →
        <a href="/admin/orders_control" class="go-back-to-admin-panel"/><b>Управление заказами</b></a>
    </div>

Список заказов


<table border="1" width="50%" cellpadding="5" class="review_list_table">

    <tr>
        <th>Заказ</th>
        <th>Дата</th>
        <th>Время</th>
        <th>Статус</th>
        <th>Действие</th>
    </tr>

    <?php foreach ($orders_list as $order) : ?>

        <tr>
            <td><a href="/admin/order/<?= $order['id'] ?>"> Заказ #<?= $order['id'] ?></a></td>
            <td><?= $order['date'] ?></td>
            <td><?= $order['time'] ?></td>
            <td><?= $order['status'] ?></td>
            <td><a href="javascript:void(0);" class="delete_selected_order" data-order-id="<?= $order['id'] ?>"><i class="fas fa-trash-alt"></i></a></td>
        </tr>




    <?php endforeach; ?>

</table>


</div>
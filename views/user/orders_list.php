<div class="container_with_breadcrumb">

    <div class="breadcrumb">
        <a href="/cabinet" class="go-back-to-admin-panel" ><b>Личный кабинет</b></a>
        →
        <a href="/cabinet/orders_list" class="go-back-to-admin-panel"><b>Список заказов</b></a>
    </div>

</div>


<?php
if (!empty($orders_list)): ?>

    <div style="text-align: center;">Список заказов</div>


    <div style="display: flex; justify-content: center;">

        <table border="1" width="50%" cellpadding="5" class="review_list_table">

            <tr>
                <th>Id</th>
                <th>Дата</th>
                <th>Время</th>
                <th>Статус</th>
            </tr>

            <?php foreach ($orders_list as $order) : ?>

                <tr>
                    <td><a href="/cabinet/order/<?= $order['id'] ?>"> Заказ #<?= $order['id'] ?></a></td>
                    <td><?= $order['date'] ?></td>
                    <td><?= $order['time'] ?></td>
                    <td><?= $order['status'] ?></td>
                </tr>

            <?php endforeach; ?>

        </table>

    </div>
<?php endif;?>
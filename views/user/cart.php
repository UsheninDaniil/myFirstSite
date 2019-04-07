

<div style="text-align: center">Содержимое корзины</div>

    <table border="1" width="50%" cellpadding="5" class="product_list_table">
        <tr>
            <th>Id</th>
            <th>Название</th>
            <th>Цена</th>
            <th>Количество</th>
            <th>Действие</th>
        </tr>

        <?php  foreach ($cartData as $id => $count):
            $product_info=Product::get_product_by_id($id);
            ?>
            <tr>
                <th><?php echo $id; ?></th>
                <th><a href="/product/<?=$id?>"><?php echo $product_info['name']?></a></th>
                <th><?php echo $product_info['price']?></th>
                <th><?php echo $count; ?></th>
                <th>
                    <form action="" method ="post">
                        <input type="hidden" name="delete_product_id" value="<?= $id ?>">
                        <input type = "submit" name ="delete_product_from_cart_list" value="Удалить" ><br />
                    </form>
                </th>
            </tr>

        <?php endforeach;?>

    </table>

    <form action="" method ="post" class="order_button"><br />
        <input type = "submit" name ="order" value="Заказать"><br />
    </form>



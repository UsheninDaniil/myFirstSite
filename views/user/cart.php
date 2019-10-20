


<div class="cart_main_container">

    <div style="text-align: center; margin-bottom: 10px">Содержимое корзины</div>


    <table border="1" width="60%" cellpadding="5" style="text-align: center; margin: auto">
        <tr>
            <th>Id</th>
            <th>Название</th>
            <th>Цвет</th>
            <th>Цена</th>
            <th>Количество</th>
            <th>Действие</th>
        </tr>

        <?php

//        echo "<b>cart_data</b><br/>";
//        echo "<pre>";
//        print_r($cartData);
//        echo "</pre>";

        ?>

        <?php  foreach ($cartData as $product_id => $inforamation):
            foreach ($inforamation as $color_id => $product_amount):
            $hex_code = Color::get_hex_code_by_color_id($color_id);
            $product_info=Product::get_product_by_id($product_id);
            ?>
            <tr>
                <td><?php echo $product_id; ?></td>
                <td><a href="/product/<?=$product_id?>"><?php echo $product_info['name']?></a></td>
                <td>
                    <?php if($color_id === 1):?>
                        универсальный
                    <?php else:?>
                        <div style="display: inline-flex">
                            <span class="span_color" style="background-color:<?="#$hex_code"?>"></span>
                        </div>
                    <?php endif;?>
                </td>
                <td><?php echo $product_info['price']?></td>
                <td><?php echo $product_amount; ?></td>
                <td>
                    <form action="" method ="post">
                        <input type="hidden" name="delete_product_id" value="<?= $product_id ?>">
                        <input type="hidden" name="delete_product_color" value="<?= $color_id ?>">
                        <input type = "submit" name ="delete_product_from_cart_list" value="Удалить" ><br />
                    </form>
                </td>
            </tr>

            <?php endforeach?>
        <?php endforeach;?>

    </table>

    <form action="" method ="post" class="order_button"><br />
        <input type = "submit" name ="order" value="Заказать"><br />
    </form>

</div>
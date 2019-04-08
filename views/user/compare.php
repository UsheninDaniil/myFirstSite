
<div style="text-align: center">Сравнение товаров</div>
<br />


<table border="1" width="50%" cellpadding="5" class="product_list_table">


    <tr>
        <th>Фото</th>
        <?php  foreach ($compareData as $id => $count):
        $product_id = $id;
        $path = "/images/"."$product_id.jpg";
        ?>
        <th colspan="2">
            <img src= "<?php echo $path ?>" alt="photo" class="product_photo"  />
        </th>
            <?php endforeach;?>
    </tr>

    <?php

    $id=$compareData[2];

    $product_info=Product::get_product_by_id($id);
    $product_parameters_info=Product::get_product_parameters_by_id($id);

    foreach ($product_parameters_info as $parameter_name => $parameter_value):?>

    <tr>
        <th>
            <?php echo $parameter_name?>
        </th>

        <?php
        foreach ($compareData as $id=>$count):
        $product_parameters_info=Product::get_product_parameters_by_id($id);
        ?>

        <th colspan="2">
            <?php
            if(isset($product_parameters_info[$parameter_name])){
            echo $product_parameters_info[$parameter_name];}
            ?>
        </th>
        <?php endforeach;?>
    </tr>

    <?php
    endforeach;
    ?>



    <tr>
        <th rowspan="2"></th>
        <?php  foreach ($compareData as $id => $count):?>
            <td>
                <form action="" method ="post">
                    <input type="hidden" name="delete_product_id" value="<?= $id ?>">
                    <input type = "submit" name ="delete_product_from_compare_list" value="Удалить" ><br />
                </form>
            </td>
            <td>
                <form action="" method ="post">
                    <input type="hidden" name="add_to_cart_product_id" value="<?= $product_info['id'] ?>">
                    <input type = "submit" name ="add_to_cart" value="В корзину"><br />
                </form>
            </td>
        <?php endforeach;?>
    </tr>




</table>

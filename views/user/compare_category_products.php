<br /><br /><br />
<div style="text-align: center">Сравнение товаров</div> <br />

<table border="1" width="50%" cellpadding="5" class="product_list_table">

    <tr>
        <th>Фото</th>
        <?php  foreach ($compare_product_list_of_selected_category as $id):
        $product_id = $id;
        $path = "/images/"."$product_id.jpg";
        ?>
        <th colspan="2">
            <img src= "<?php echo $path ?>" alt="photo" class="product_photo"  />
        </th>
            <?php endforeach;?>
    </tr>

    <tr>
        <th>Название товара</th>
        <?php  foreach ($compare_product_list_of_selected_category as $id):
            $product_info=Product::get_product_by_id($id);
        ?>
        <th colspan="2">
            <a href="/product/<?php echo $product_info['id'];?>"><?php echo $product_info['name'];?></a>
        </th>
        <?php endforeach;?>
    </tr>

    <tr>
        <th>Цена</th>
        <?php  foreach ($compare_product_list_of_selected_category as $id):
            $product_info=Product::get_product_by_id($id);
            ?>
            <th colspan="2">
                <?php echo $product_info['price'];?>
            </th>
        <?php endforeach;?>
    </tr>

    <?php

//    $id = User::find_product_with_the_most_parameters_from_product_list($compare_product_list_of_selected_category);
//    $product_info=Product::get_product_by_id($id);
//    $product_parameters_info=Product::get_product_parameters_by_id($id);

    $product_parameters_list=Product::get_compare_parameters_list_by_product_list($compare_product_list_of_selected_category);

    foreach ($product_parameters_list as $parameter_id):
    $parameter_name=Product::get_parameter_name_by_parameter_id($parameter_id);
    ?>

    <tr>
        <th>
            <?php echo $parameter_name?>
        </th>

        <?php
        foreach ($compare_product_list_of_selected_category as $product_id):
        $product_parameters_values=Product::get_product_parameters_by_id($product_id);
        ?>

        <th colspan="2">
            <?php
            if(isset($product_parameters_values[$parameter_name])){
            echo $product_parameters_values[$parameter_name];}
            ?>
        </th>
        <?php endforeach;?>
    </tr>

    <?php
    endforeach; ?>

    <tr>
        <th rowspan="2"></th>
        <?php  foreach ($compare_product_list_of_selected_category as $id):?>
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

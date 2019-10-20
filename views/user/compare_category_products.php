<br />
<div style="text-align: center">Сравнение товаров</div> <br />

<?php
$count_category_products = count($compareData[$category_id]);
$colspan_amount = $count_category_products*2 + 1;
?>

<table border="1" width="50%" cellpadding="5" class="product_list_table">

    <tr>
        <td colspan="<?=$colspan_amount?>" >

        <table border="1" width="100%" cellpadding="5" style=" border-collapse: collapse;">
        <tr style="text-align: center">

        <?php if(isset($compareData[1])):?>
            <th <?php if($category_id==1){echo "bgcolor='LightBlue'";}?> ><a href="/compare_category/1">Компьютеры <?php echo"(".count($compareData[1]).")"; ?></a></th>
        <?php endif;?>

        <?php if(isset($compareData[2])):?>
            <th <?php if($category_id==2){echo "bgcolor='LightBlue'";}?>><a href="/compare_category/2">Ноутбуки <?php echo"(".count($compareData[2]).")"; ?></a></th>
        <?php endif;?>

        <?php if(isset($compareData[3])):?>
            <th <?php if($category_id==3){echo "bgcolor='LightBlue'";}?>><a href="/compare_category/3">Планшеты <?php echo"(".count($compareData[3]).")"; ?></a></th>
        <?php endif;?>

        <?php if(isset($compareData[4])):?>
            <th <?php if($category_id==4){echo "bgcolor='LightBlue'";}?>><a href="/compare_category/4">Телефоны <?php echo"(".count($compareData[4]).")"; ?></a></th>
        <?php endif;?>

        </tr>
        </table>

        </td>
    </tr>

    <?php
    $width_of_parameter_name_column = 20;
    $column_count = count($compare_product_list_of_selected_category);
    $width_of_parameter_value_column = (100-$width_of_parameter_name_column)/$column_count;
    $width_of_parameter_value_column = floor($width_of_parameter_value_column);
    ?>

    <tr>
        <th width="<?=$width_of_parameter_name_column?>">Фото</th>
        <?php  foreach ($compare_product_list_of_selected_category as $product_id):
        $path = "/images/preview_images/id_"."$product_id"."_photo_1.jpg";
        ?>
        <th colspan="2" width="<?=$width_of_parameter_value_column ?>">
            <img src= "<?php echo $path ?>" alt="photo" class="product_photo"  />
        </th>
            <?php endforeach;?>
    </tr>

    <tr>
        <th width="<?=$width_of_parameter_name_column?>">Название</th>
        <?php  foreach ($compare_product_list_of_selected_category as $product_id):
            $product_info=Product::get_product_by_id($product_id);
        ?>
        <th colspan="2" width="<?=$width_of_parameter_value_column?>">
            <a href="/product/<?php echo $product_info['id'];?>"><?php echo $product_info['name'];?></a>
        </th>
        <?php endforeach;?>
    </tr>

    <tr>
        <th width="<?=$width_of_parameter_name_column?>">Цена</th>
        <?php  foreach ($compare_product_list_of_selected_category as $product_id):
            $product_info=Product::get_product_by_id($product_id);
            ?>
            <th colspan="2" width="<?=$width_of_parameter_value_column?>">
                <?php echo $product_info['price'];?>
            </th>
        <?php endforeach;?>
    </tr>

    <?php

    $product_parameters_list=Product::get_compare_parameters_list_by_product_list($compare_product_list_of_selected_category);

    foreach ($product_parameters_list as $parameter_id):
    $parameter_name=Product::get_parameter_name_by_parameter_id($parameter_id);
    ?>

    <tr>
        <th width="<?=$width_of_parameter_name_column?>">
            <?php echo $parameter_name?>
        </th>

        <?php
        foreach ($compare_product_list_of_selected_category as $product_id):
        $product_parameters_values=Product::get_product_parameters_by_id($product_id);
        ?>

        <th colspan="2" width="<?=$width_of_parameter_value_column?>">
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
        <?php
            foreach ($compare_product_list_of_selected_category as $product_id):
            $ability_to_choose_the_color = Color::check_is_there_ability_to_choose_the_color($product_id);
        ?>
            <td>
                <a href="javascript:void(0);" class="delete_product_from_compare_list cool_link_style" data-product-id="<?=$product_id ?>" data-category-id="<?=$category_id ?>">Удалить</a>
            </td>
            <td>

                    <a href="javascript:void(0);" class="add_to_cart_modal"
                       data-toggle="modal"
                       data-target="#addToCartModal"
                       data-product-id="<?= $product_id ?>"
                       data-ability-to-choose-the-color="<?php if($ability_to_choose_the_color === 'true'){echo 'true';}else{echo 'false';}?>"
                    >

                    В корзину <i class="fas fa-shopping-cart"></i>
                    <span class="check-in-the-cart<?= $product_id ?>">
                    <?php
                    if (isset($_SESSION['cart_product_list'])){
                        $cartData = unserialize($_SESSION['cart_product_list']);
                        if (isset($cartData[$product_id])){
                            echo "<i class='far fa-check-square'></i>";
                        }
                    }
                    ?>
                </span>
                </a>

            </td>
        <?php endforeach;?>
    </tr>

</table>


<?php
include_once('/views/product/select_product_color_modal.php');
?>
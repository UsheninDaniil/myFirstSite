<div class="container_with_breadcrumb">

<?php
//    echo "<pre>";
//    print_r($color_product_amount);
//    echo "</pre>";
?>

    <div class="breadcrumb">
        <a href="../../../../index.php" class="go-back-to-admin-panel"><b>Панель администратора</b></a>
        →
        <a href="/admin/edit_products" class="go-back-to-admin-panel"><b>Управление товарами</b></a>
        →
        <a href="/admin/edit_product/<?= $product_id ?>" class="go-back-to-admin-panel"><b>Изменение товара
                #<?= $product_id ?></b></a>
    </div>


    <div class="edit_selected_product_main_container">

        <h4 style="text-align: center">Изменение товара #<?= $product_id ?></h4>

        <form enctype="multipart/form-data" id="edit_product" data-product-id="<?= $product_id ?>" action=""
              method="post">

            <br/>

            <div class="main_parameters">

                <label>Название товара:</label><br/>
                <input type="text" name="product_name" value="<?= $product_information['name'] ?>"><br/>

                <label>Цена:</label><br/>
                <input type="text" name="product_price" value="<?= $product_information['price'] ?>"><br/><br/>

                <label>Отображать на сайте?</label><br/>
                <input name="availability" type="radio" value="1" <?php if ($product_information['status'] = 1) {
                    echo "checked";
                } ?>>Да
                <input name="availability" type="radio" value="0" <?php if ($product_information['status'] = 0) {
                    echo "checked";
                } ?>>Нет


                <br/><br/>
                <label>Есть возможность выбирать цвет?</label><br/>
                <input name="availability_to_choose_the_color" type="radio" value="1" <?php if($ability_to_choose_the_color === 'true'){echo 'checked';} ?> >Да
                <input name="availability_to_choose_the_color" type="radio" value="0" <?php if($ability_to_choose_the_color === 'false'){echo 'checked';} ?> >Нет
                <br/><br/>

            </div>


            <div class="product_amount">

                <div style="margin: 10px 0">Имеется в наличии</div>

                <div style="margin: 10px 0">
                    <table class="product_colors_table" border="1" cellpadding="5" style="border-collapse: collapse;">
                        <tr>
                            <th style="width: 35%">Цвет</th>
                            <th style="width: 50%">Колличество</th>
                            <th style="width: 15%"></th>
                        </tr>

                        <?php
                        foreach ($color_product_amount as $color_information){

                            $color_name = $color_information['name'];
                            $amount = $color_information['amount'];
                            $color_id = $color_information['color_id'];

                            include('/views/admin/product/edit_selected_product/template_color_product_amount_table_row.php');
                        }


                        ?>


                    </table>

                </div>

                <div style="display: flex; justify-content: center">
                    <button data-toggle="modal" data-target="#AddNewColorModal" type="button" class="btn btn-light" <?php if($ability_to_choose_the_color === 'false'){echo 'disabled';} ?>>добавить цвет</button>
                </div>
            </div>


            <div class="dynamic_parameters">

                <b><a href="" class="go-back-to-admin-panel"><?php echo "Параметры категории" ?></a></b><br/><br/>

                <div class="category_parameters">

                    <?php foreach ($category_parameters_list_after_checking as $parameter_id): ?>

                        <?php
                        $parameter_information = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
                        $parameter_russian_name = $parameter_information['russian_name'];
                        $parameter_name = $parameter_information['name'];
                        $parameter_id = $parameter_information['id'];
                        $value = AdminParameter::get_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id);
                        ?>

                        <label><?= $parameter_russian_name ?></label><br/>

                        <input type="text" name="dynamic_parameters[<?= $parameter_id ?>]" value="<?= $value ?>"><br/>
                    <?php endforeach; ?>

                </div>

                <br/><b><a href="" class="go-back-to-admin-panel"><?php echo "Дополнительные параметры" ?></a></b></b>
                <br/><br/>

                <div class="additional_parameters">

                    <?php foreach ($additional_parameters_list_after_checking as $parameter_id): ?>

                        <?php
                        $parameter_information = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
                        $parameter_russian_name = $parameter_information['russian_name'];
                        $parameter_name = $parameter_information['name'];
                        $parameter_id = $parameter_information['id'];
                        $value = AdminParameter::get_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id);
                        ?>

                        <label><?= $parameter_russian_name; ?><br/></label>

                        <input type="text" name="dynamic_parameters[<?= $parameter_id ?>]" value="<?= $value ?>">
                        <a href="javascript:void(0);" class="remove_additional_parameter"
                           data-parameter-id="<?= $parameter_id ?>"><i class="fas fa-trash-alt"></i></a><br/>

                    <?php endforeach; ?>

                    <div class="new_parameters"></div>

                </div>
                <br/>

                <a href='javascript:void(0);' id="load_existing_parameter">Добавить существующий параметр <i
                            class="fas fa-search"></i></a>
                <a href="javascript:void(0);" class="hide_button" style="display: none"><i class="fas fa-minus"></i></a>

                <div class="existing_parameters_form" style="display: none">

                    <?php
                    foreach ($not_specified_parameters as $parameter_id) {
                        $result = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
                        $russian_name = $result['russian_name'];
                        echo "<input type='checkbox' name='add_parameters_list[]'  value='$parameter_id' data-parameters-list='$parameter_id' class=''>" . $russian_name . "<br />";
                    }
                    ?>

                    <a href='javascript:void(0);' id='add_selected_parameters'>Добавить <i class="fas fa-check"></i></a>

                </div>
            </div>

            <br/>
            <input type="submit" name="update_product_information" value="Изменить товар "><br/>

        </form>

    </div>

</div>


<div class="modal fade" id="AddNewColorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Добавить цвет</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="select_available_colors">
                    <select id='multiple_colors_list' multiple='multiple'>
                        <?php foreach ($not_selected_colors_list as $color):
                            $color_name = $color['name'];
                            $color_id = $color['id'];
                            ?>
                            <option value='<?= $color_id ?>'><?= $color_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="selected_colors" style="display: flex; flex-direction: column">

                </div>

                <input class="all_colors_information" type="hidden" disabled value='<?php echo json_encode($color_id_and_name_list)?>'>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary add_colors_modal_submit" data-dismiss="modal">Применить</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>


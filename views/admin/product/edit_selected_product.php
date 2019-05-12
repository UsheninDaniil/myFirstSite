
<br />
<a href="/admin" class="go-back-to-admin-panel"><b>Панель администратора</b></a>
→
<a href="/admin/edit_products" class="go-back-to-admin-panel"><b>Управление товарами</b></a>
→
<a href="/admin/edit_product/<?=$product_id?>" class="go-back-to-admin-panel"><b>Изменение товара #<?=$product_id?></b></a>


<form  enctype="multipart/form-data" id = "edit_product" data-product-id = "<?=$product_id?>" action="" method ="post">

    <br /><b style="color: #dd0029"><?php echo "Основные параметры"?></b><br /><br />

    <div class="main_parameters">

    <label>Название товара:</label><br />
    <input type="text" name="product_name" value="<?=$product_information['name']?>"><br />

    <label>Цена:</label><br />
    <input type="text" name="product_price" value="<?=$product_information['price']?>" ><br />

    <label>Есть в наличии?</label><br />
        <input name="availability" type="radio" value="1" <?php if($product_information['status']=1){echo "checked";} ?>>Да
        <input name="availability" type="radio" value="0" <?php if($product_information['status']=0){echo "checked";} ?>>Нет

    </div>

    <br /><b style="color: #dd0029"><?php echo "Динамические параметры"?></b><br />

    <div class="dynamic_parameters">

    <br /><b><a href="" class="go-back-to-admin-panel"><?php echo "Параметры категории"?></a></b><br /><br />

    <div class="category_parameters">

        <?php foreach ($category_parameters_list_after_checking as $parameter_id):?>

            <?php
            $parameter_information = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
            $parameter_russian_name = $parameter_information['russian_name'];
            $parameter_name = $parameter_information['name'];
            $parameter_id = $parameter_information['id'];
            $value = AdminParameter::get_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id);
            ?>

        <label><?=$parameter_russian_name?></label><br />

        <input type="text" name="dynamic_parameters[<?=$parameter_id?>]" value="<?=$value?>"><br />
        <?php endforeach;?>

    </div>

    <br /><b><a href="" class="go-back-to-admin-panel"><?php echo "Дополнительные параметры"?></a></b></b><br /><br />

    <div class="additional_parameters">

        <?php foreach ($additional_parameters_list_after_checking as $parameter_id):?>

            <?php
            $parameter_information = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
            $parameter_russian_name = $parameter_information['russian_name'];
            $parameter_name = $parameter_information['name'];
            $parameter_id = $parameter_information['id'];
            $value = AdminParameter::get_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id);
            ?>

        <label><?=$parameter_russian_name;?><br /></label>

        <input type="text" name="dynamic_parameters[<?=$parameter_id?>]" value="<?=$value?>">
        <a href="javascript:void(0);" class="remove_additional_parameter" data-parameter-id="<?=$parameter_id?>"><i class="fas fa-trash-alt"></i></a><br />

        <?php endforeach;?>

    <div class="new_parameters"></div>

    </div><br />

    <a href='javascript:void(0);' id="load_existing_parameter">Добавить существующий параметр <i class="fas fa-search"></i></a>
    <a href= "javascript:void(0);" class="hide_button" style="display: none" ><i class="fas fa-minus"></i></a>

    <div class="existing_parameters_form" style="display: none">

            <?php
            foreach ($not_specified_parameters as $parameter_id){
                $result = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
                $russian_name = $result['russian_name'];
                echo "<input type='checkbox' name='add_parameters_list[]'  value='$parameter_id' data-parameters-list='$parameter_id' class=''>".$russian_name."<br />";
            }
            ?>

        <a href='javascript:void(0);' id ='add_selected_parameters'>Добавить <i class="fas fa-check"></i></a>

    </div>
    </div>

    <br />
    <input type = "submit" name ="update_product_information" value="Изменить товар "><br />

</form>


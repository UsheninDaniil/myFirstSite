
<br />
<a href="/admin" class="go-back-to-admin-panel"><b>Панель администратора</b></a>
→
<a href="/admin/edit_products" class="go-back-to-admin-panel"><b>Управление товарами</b></a>
→
<a href="/admin/edit_poduct/<?=$product_id?>" class="go-back-to-admin-panel"><b>Изменение товара #<?=$product_id?></b></a>


<form  enctype="multipart/form-data" id = "edit_product" action="" method ="post">

    <br /><b style="color: #dd0029"><?php echo "Основные параметры"?></b><br /><br />

    <div class="main_parameters">

    <label>Название товара:</label><br />
    <input type="text" name="product_name" value="<?=$product_information['name']?>"><br />

    <label>Цена:</label><br />
    <input type="text" name="product_price" value="<?=$product_information['price']?>" ><br />

    </div>

    <br /><b style="color: #dd0029"><?php echo "Динамические параметры"?></b><br />

    <div class="dynamic_parameters">

    <br /><b><a href="" class="go-back-to-admin-panel"><?php echo "Параметры категории"?></a></b><br /><br />

    <div class="category_parameters">

    <?php
    for ($i =0; $i < count($parameters_name_list); $i++):?>
    <label><?=$parameters_name_list["$i"]?></label><br />
    <input type="text" name="product_price" value="<?=$parameters_value_list["$i"]?>" ><br />
    <?php endfor?>

    </div>

    <br /><b><a href="" class="go-back-to-admin-panel"><?php echo "Дополнительные параметры"?></a></b></b><br /><br />

    <div class="additional_parameters">

        <?php foreach ($additional_parameters_list_after_checking as $parameter_id):?>

        <label>
            <?php
            $result = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
            $parameter_name = $result['russian_name'];
            echo "$parameter_name<br />";
            ?>
        </label>

        <?php $value = AdminParameter::get_parameter_value_by_product_id_and_parameter_id($product_id, $parameter_id)?>

        <input type="text" value="<?=$value?>"><br />

        <?php endforeach;?>

        <div class="new_parameters">

        </div>

    </div><br />

    <a href='javascript:void(0);' id="load_existing_parameter">Добавить существующий параметр <span class='glyphicon glyphicon-search'></span></a>
    <a href= "javascript:void(0);" class="hide_button" style="display: none" ><span class="glyphicon glyphicon-minus"></span></a>

    <div class="existing_parameters_form" style="display: none">

            <?php
            foreach ($not_specified_parameters as $parameter_id){
                $result = AdminParameter::get_parameter_information_by_parameter_id($parameter_id);
                $russian_name = $result['russian_name'];
                echo "<input type='checkbox' name='parameter_id[]'  value='$parameter_id' data-parameters-list='$parameter_id' class=''>".$russian_name."<br />";
            }
            ?>

        <a href='javascript:void(0);' id ='add_selected_parameters'>Добавить <span class="glyphicon glyphicon-ok"></span></a>

    </div>

    </div>

    <br />
    <input type = "submit" name ="send" value="Изменить товар "><br />

</form>



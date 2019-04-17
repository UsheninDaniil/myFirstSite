


<div class="parameters_list_table"><br /><br />

    <table border="1" width="50%" cellpadding="5" class="product_list_table" id="parameters_table">

        <tr>
            <th>Id</th>
            <th>name</th>
            <th>russian_name</th>
            <th>unit</th>
            <th colspan="2">Действие</th>
        </tr>

        <?php foreach ($existing_parameters_list as $parameter) : ?>

            <tr>
                <td><?= $parameter['id'] ?></td>
                <td><?= $parameter['name'] ?></td>
                <td><?= $parameter['russian_name'] ?></td>
                <td><?= $parameter['unit'] ?></td>
                <td><a href="javascript:void(0);" class="remove_selected_parameter" data-parameter-id="<?= $parameter['id']?>"><span class="glyphicon glyphicon-remove"></span></a></td>
                <td><a href="/admin/edit_selected_parameter/<?= $parameter['id']?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
            </tr>

        <?php endforeach; ?>

        <tr>
            <th colspan="6">
                <a href="javascript:void(0);" class='load_new_parameter_form_2'>Создать новый параметр</a>
                <a href= "javascript:void(0);" class="hide_load_new_parameter_form_button_2" id="hide_load_new_parameter_form_button_2" style="display: none"><span class="glyphicon glyphicon-minus"></span></a>

                <div id="load_new_parameter_form_2" style="display: none">

                    <form  enctype="multipart/form-data" id ="create_new_parameter_information_2" name = "add_product" action="" method ="post" class="feedback">
                        <br /><label>Аббревиатура (латиницей):</label><br />
                        <input type="text" name="parameter_name" />
                        <br /><label>Название:</label><br />
                        <input type="text" name="parameter_russian_name" />
                        <br /><label>Единица измерения:</label><br />
                        <input type="text" name="parameter_unit" /><br />
                    </form>

                    <a href= "javascript:void(0);" class="save_new_parameter" >Создать <span class="glyphicon glyphicon-ok"></span></a>

                </div>
            </th>
        </tr>

    </table>

</div>

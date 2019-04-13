

<div style='text-align: center'><br />Категория <a href='/category/<?=$category_id?>' class='edit-category-name' '><?=$category_name?></a>: <br /><br /></div>


<table border='1' cellpadding='5' class='parameter_list_table' data-category-id='<?=$category_id?>' >
    <tr>
        <th colspan='2'>Список параметров</th>
    </tr>

    <?php

    foreach ($parameters_list as $i => $parameter_id){
    $parameter_name = Parameters::get_parameter_name_by_parameter_id($parameter_id);
    echo "<tr>
        <th>$parameter_name</th>
        <th><a href='javascript:void(0);' class='remove_parameter_from_category' data-parameter-id='$parameter_id'><span class='glyphicon glyphicon-remove'></span></a></th>
    </tr>";
    }

    ?>

        <tr><th colspan='2'>
            <a href='javascript:void(0);' class='load_existing_parameters_form'>Добавить существующий параметр <span class='glyphicon glyphicon-search'></span></a>
            <a href= "javascript:void(0);" class="hide_load_existing_parameters_form_button" id="hide_load_existing_parameters_form_button" style="display: none"><span class="glyphicon glyphicon-minus"></span></a>

            <div id="load_existing_parameters_form" style="display: none">
                <form method='post' id ='save_parameters_list'>
                    <?php
                        foreach ($existing_parameters_list as $parameter_item){
                        $id = $parameter_item['id'];
                        echo "<input type='checkbox' name='parameter_id[]'  value='$id' data-parameters-list='$id' class='save_parameters_list'>".$parameter_item['russian_name']."<br />";
                        }
                    ?>
                </form>
                <a href='javascript:void(0);' class='save_selected_existing_parameters_to_category'>Добавить <span class="glyphicon glyphicon-ok"></span></a>
            </div>

            <div class='check_save_existing_parameters_button'></div>
        </th></tr>


        <th colspan='2'>
            <a href='javascript:void(0);' class='load_new_parameter_form'>Создать новый параметр <span class='glyphicon glyphicon-pencil'></span></a>
            <a href= "javascript:void(0);" class="hide_load_new_parameter_form_button" id="hide_load_new_parameter_form_button" style="display: none"><span class="glyphicon glyphicon-minus"></span></a>

            <div id="load_new_parameter_form" style="display: none">

            <form  enctype="multipart/form-data" id ="create_new_parameter_information" name = "add_product" action="" method ="post" class="feedback">
                <br /><label>Аббревиатура (латиницей):</label><br />
                <input type="text" name="parameter_name" />
                <br /><label>Название:</label><br />
                <input type="text" name="parameter_russian_name" />
                <br /><label>Единица измерения:</label><br />
                <input type="text" name="parameter_unit" /><br />
            </form>
                <a href= "javascript:void(0);" class="save_new_parameter_to_category" >Создать <span class="glyphicon glyphicon-ok"></span></a>

            </div>

            <div class='check_save_new_parameter_button'></div>
        </th></tr></table>
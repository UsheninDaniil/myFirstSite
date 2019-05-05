<div class="category_parameters_table">

<table border='1' cellpadding='5' class='parameter_list_table' data-category-id='<?=$category_id?>' >
    <tr>
        <th colspan='5'>Список параметров</th>
    </tr>

    <tr>
        <th colspan='1'><b style="color: darkred">Название</b></th>
        <th colspan='1'><b style="color: darkred">отображение в фильтре</b></th>
        <th colspan='1'><b style="color: darkred">порядок сортировки</b></th>
        <th colspan='2'><b style="color: darkred">действие</b></th>
    </tr>

    <?php

    foreach ($category_parameters_list as $i => $parameter_id){
    $parameter_name = Parameters::get_parameter_name_by_parameter_id($parameter_id);
    $category_parameters_information = Parameters::get_category_parameter_information_by_category_id_and_parameter_id($category_id, $parameter_id);
    $show_in_filter = $category_parameters_information['show_in_filter'];
    $sort_order = $category_parameters_information['sort_order'];
    echo "<tr>
        <th>$parameter_name</th>";
        if($show_in_filter == 1){
            echo"<th>Да</th>";
        } elseif ($show_in_filter == 0){
            echo"<th>Нет</th>";
        } else{
            echo"<th></th>";
        }

    echo "
        <th>$sort_order</th>
        <th><a href='javascript:void(0);' class='remove_parameter_from_category' data-parameter-id='$parameter_id'><i class='fas fa-trash-alt'></i></a></th>
        <th><a href=''><i class=\"far fa-edit\"></i></a> </th>
    </tr>";

    }

    ?>

        <tr><th colspan='5'>
            <a href='javascript:void(0);' class='load_existing_parameters_form'>Добавить существующий параметр <i class="fas fa-search"></i></a>
            <a href= "javascript:void(0);" class="hide_load_existing_parameters_form_button" id="hide_load_existing_parameters_form_button" style="display: none"><i class="fas fa-minus"></i></a>

            <div id="load_existing_parameters_form" style="display: none">
                <form method='post' id ='save_parameters_list'>
                    <?php
                        foreach ($not_category_parameters as $parameter_id){
                        $russian_name = Parameters::get_parameter_name_by_parameter_id($parameter_id);
                        echo "<input type='checkbox' name='parameter_id[]'  value='$parameter_id' data-parameters-list='$parameter_id' class='save_parameters_list'>".$russian_name."<br />";
                        }
                    ?>
                </form>
                <a href='javascript:void(0);' class='save_selected_existing_parameters_to_category'>Добавить <i class="fas fa-check"></i></a>
            </div>

            <div class='check_save_existing_parameters_button'></div>
        </th></tr>


        <th colspan='5'>
            <a href='javascript:void(0);' class='load_new_parameter_form'>Создать новый параметр <i class="far fa-edit"></i></a>
            <a href= "javascript:void(0);" class="hide_load_new_parameter_form_button" id="hide_load_new_parameter_form_button" style="display: none"><i class="fas fa-minus"></i></a>

            <div id="load_new_parameter_form" style="display: none">

            <form  enctype="multipart/form-data" id ="create_new_parameter_information" name = "add_product" action="" method ="post" class="feedback">
                <br /><label>Аббревиатура (латиницей):</label><br />
                <input type="text" name="parameter_name" />
                <br /><label>Название:</label><br />
                <input type="text" name="parameter_russian_name" />
                <br /><label>Единица измерения:</label><br />
                <input type="text" name="parameter_unit" /><br />
            </form>
                <a href= "javascript:void(0);" class="save_new_parameter_to_category" >Создать <i class="fas fa-check"></i></a>

            </div>

            <div class='check_save_new_parameter_button'></div>
        </th></tr>

</table>

</div>
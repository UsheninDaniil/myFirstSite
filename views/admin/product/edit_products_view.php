
<div class="container_with_breadcrumb">

<div class="breadcrumb">
    <a href="/admin" class="go-back-to-admin-panel" /><b>Панель администратора</b></a>
    →
    <a href="/admin/edit_products" class="go-back-to-admin-panel"/><b>Управление товарами</b></a>
</div>



<?php


    $status_list = array(1 => 'отображается' ,0 => 'не отображается');

    if(isset($_GET['name'])){
        $search_product_name = $_GET['name'];
    }

    if(isset($_GET['id'])){
        $search_product_id = $_GET['id'];
    }

?>

<br/><br/>

<div class="product_parameters_filter">

<form id="admin_product_multiselect_form">

<input type="text" name="name" placeholder="поиск по названию" id="search_name" value="<?php if(isset($search_product_name)){echo $search_product_name;}?>">
<input type="text" name="id" placeholder="поиск по id" id="search_id" value="<?php if(isset($search_product_name)){echo $search_product_id;}?>"><br/><br/>

<select name="category_id[]" multiple="multiple" size="5" class="category_multiselect" id="select_category">
    <?php foreach ($category_list as $category_information):?>

        <?php
        $category_id = $category_information['id'];
        $category_name = $category_information['name'];
        $is_selected = false;

        if(isset($get_parameters_without_page['category_id'])){
            $category_id_array = $get_parameters_without_page['category_id'];
            if(in_array($category_id, $category_id_array)){
                $is_selected = true;
            }
        }
        ?>

        <option value="<?=$category_id?>" <?php if($is_selected==true){echo 'selected';}?> ><?=$category_name?></option>
    <?php endforeach; ?>
</select>

<select name="status[]"  size="5" class="product_status" id="select_status">
    <?php foreach ($status_list as $status_value => $status_name):?>

    <?php
        $is_selected = false;
        if(isset($get_parameters_without_page['status'])){
            $status_array = $get_parameters_without_page['status'];
            if(in_array($status_value, $status_array)){
                $is_selected = true;
            }
        }
    ?>
    <option value="<?=$status_value?>" <?php if($is_selected==true){echo 'selected';}?> > <?=$status_name?> </option>

    <?php endforeach;?>
</select>

<input type="button" name="apply_filter" value="Применить" onclick="apply_filter_in_edit_products()">


</form>

</div>



<?php
$filter_tags = '';

if(!empty($_GET)) {
    $get_parameters_array = $_GET;
    if(isset($get_parameters_array['page'])){
        unset($get_parameters_array['page']);
    }
    $get_parameters_without_page = $get_parameters_array;

    foreach ($get_parameters_without_page as $parameter_name => $parameter_values){

        if(empty($parameter_values)) {
            continue;
        }

        if (gettype($parameter_values) == "array") {

            foreach ($parameter_values as $parameter_value) {
                if ($parameter_name == "category_id") {
                    include_once('/models/Category.php');
                    $category_list = Category::get_category_list();
                    $key = array_search($parameter_value, array_column($category_list, 'id'));
                    $tag_name = $category_list[$key]['name'];
                }
                if ($parameter_name == "status") {
                    if ($parameter_value == 1) {
                        $tag_name = "отображается";
                    } elseif ($parameter_value == 0) {
                        $tag_name = "не отображается";
                    }
                }
                if (strlen($filter_tags) == 0) {
                    $filter_tags = $filter_tags . $tag_name;
                } else {
                    $filter_tags = $filter_tags . ',' . $tag_name;
                }
            }
        } else {

            $parameter_value = $parameter_values;

            if ($parameter_name == "name") {
                $tag_name = "name = $parameter_value";
            }

            if ($parameter_name == "id") {
                $tag_name = "id = $parameter_value";
            }

            if ((strlen($filter_tags) == 0) && (!empty($tag_name))) {
                $filter_tags = $filter_tags . $tag_name;
            } else {
                $filter_tags = $filter_tags . ',' . $tag_name;
            }
        }
    }
}
?>

    <div class="admin_product_filter_tags">
        <input  name="tags" placeholder="текст" value="<?=$filter_tags?>">
    </div>

    <br/>

    </div>




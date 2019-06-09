
<br />
<a href="/admin" class="go-back-to-admin-panel" /><b>Панель администратора</b></a>
→
<a href="/admin/edit_products" class="go-back-to-admin-panel"/><b>Управление товарами</b></a>

<br /><br /><br />

<?php
    if(isset($get_parameters_without_page)){
        echo "Содержимое <b style='color: darkred;'>get_parameters_without_page</b> <br />";
        print_r($get_parameters_without_page);
    }

        $status_list = array(1 => 'отображается' ,0 => 'не отображается')
?>

<br/><br/>

<form id="admin_product_multiselect_form">

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



<select name="status[]"  size="5" class="product_status">
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

<?php
$filter_tags = '';

if(!empty($_GET)) {
    $get_parameters_array = $_GET;
    if(isset($get_parameters_array['page'])){
        unset($get_parameters_array['page']);
    }
    $get_parameters_without_page = $get_parameters_array;

    foreach ($get_parameters_without_page as $parameter_name => $parameter_values_array){
        foreach ($parameter_values_array as $parameter_value){
            if($parameter_name == "category_id"){
                include_once ('/models/Category.php');
                $category_list = Category::get_category_list();
                $row = $category_list[$parameter_value-1]['name'];
            }
            if($parameter_name == "status"){
                if($parameter_value == 1){
                    $row = "отображается";
                } elseif ($parameter_value ==0){
                    $row = "не отображается";
                }
            }
            if(strlen($filter_tags)==0){
                $filter_tags = $filter_tags.$row;
            }
            else{
                $filter_tags = $filter_tags.','.$row;
            }
        }
    }
}
?>

<input  name="tags" placeholder="текст" value="<?=$filter_tags?>">




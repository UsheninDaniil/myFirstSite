<?php
require_once (ROOT. '/models/Category.php');
$categoryList = Category::get_category_list();

print_r($_POST);
?>

<br />
<a href="/admin" class="go-back-to-admin-panel" ><b>Панель администратора</b></a>
→
<a href="/admin/edit_category" class="go-back-to-admin-panel"><b>Управление категориями</b></a>

<br /><br /><br />


<table border='1' cellpadding='5' class='parameter_list_table' id="sortable_categories_list_table" style="table-layout:fixed;">
    <col width="14%">
    <col width="28%">
    <col width="14%">
    <col width="28%">
    <col width="14%">
    <thead>
    <tr>
        <th colspan="5">Список категорий</th>
    </tr>

    <?php
    $narrow_column_count = 3;
    $wide_column_count = 2;

    $narrow_column_width= 100/($narrow_column_count + $wide_column_count*2);
    $wide_column_width = $narrow_column_width*2;

    $narrow_column_width = floor($narrow_column_width);
    $wide_column_width = floor($wide_column_width);

    echo "Ширина узкой колонки = $narrow_column_width %<br />";
    echo "Ширина широкой колонки = $wide_column_width %<br />";

    ?>

    <tr>
        <th><b style="color: darkred">id</b></th>
        <th><b style="color: darkred">Название</b></th>
        <th><b style="color: darkred">Порядок сортировки</b></th>
        <th><b style="color: darkred">Статус</b></th>
        <th><b style="color: darkred">Список параметров</b></th>
    </tr>
    </thead>
    <tbody>

    <?php foreach ($categoryList as $category_information):
        $category_name = $category_information['name'];
        $category_id = $category_information['id'];
        $sort_order = $category_information['sort_order'];
        $status = $category_information['status'];
        ?>

        <tr data-category-id="<?=$category_id?>" style="cursor: move">
            <th><?php echo $category_id ?></th>
            <th><a href="#" class="category_name" data-pk="<?=$category_id ?>"> <?=$category_name ?></a></th>
            <th id="sort_order"><?php echo $sort_order ?></th>
            <th><a href="#" class="category_status" data-pk="<?=$category_id ?>" data-source="{'1': 'отображается', '0': 'не отображается'}"><?= $status?></a></th>
            <th><a href="/admin/edit_selected_category/<?=$category_id?>">настроить</a> </th>
        </tr>

    <?php endforeach;?>

    </tbody>
</table>

<div class="information"></div>

<div class="editable_information"></div>



<div class="container_with_breadcrumb">

    <div class="breadcrumb">
        <a href="/admin" class="go-back-to-admin-panel" ><b>Панель администратора</b></a>
        →
        <a href="/admin/edit_category" class="go-back-to-admin-panel"><b>Управление категориями</b></a>
    </div>

</div>

<table border='1' cellpadding='5' id="sortable_categories_list_table">

    <thead>
    <tr>
        <th colspan="5">Список категорий</th>
    </tr>

    <?php
    $narrow_column_count = 2;
    $wide_column_count = 3;

    $narrow_column_width= 100/($narrow_column_count + $wide_column_count*2);
    $wide_column_width = $narrow_column_width*2;

    $narrow_column_width = floor($narrow_column_width);
    $wide_column_width = floor($wide_column_width);

    ?>

    <tr>
        <th><b style="color: darkred; width: <?=$narrow_column_width?>%">id</b></th>
        <th><b style="color: darkred; width: <?=$wide_column_width?>%">Название</b></th>
        <th><b style="color: darkred; width: <?=$narrow_column_width?>%">Сортировка</b></th>
        <th><b style="color: darkred; width: <?=$wide_column_width?>%">Статус</b></th>
        <th><b style="color: darkred; width: <?=$wide_column_width?>%">Параметры</b></th>
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
            <th style="width:<?=$narrow_column_width?>%"><?php echo $category_id ?></th>

            <th style="width:<?=$wide_column_width?>%" class="cell_with_category_name"><a href="javascript:void(0);" class="category_name" data-pk="<?=$category_id ?>" > <?=$category_name ?></a></th>
            <th style="width:<?=$narrow_column_width?>%" id="sort_order"><?=$sort_order?></th>
            <th style="width:<?=$wide_column_width?>%">

                <div class="category_status_cell" data-category-id="<?=$category_id ?>">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input category_status_checkbox" type="checkbox" id="inlineCheckbox1" value="option1" <?php if ($status==1){echo "checked";}?> >
                    </div>
                </div>


            </th>
            <th style="width:<?=$wide_column_width?>%"><a href="/admin/edit_selected_category/<?=$category_id?>">настроить</a> </th>
        </tr>

    <?php endforeach;?>

    </tbody>
</table>








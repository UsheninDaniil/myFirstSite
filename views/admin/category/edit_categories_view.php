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



<table border='1' cellpadding='5' class='parameter_list_table' id="sortable_categories_list_table" >
<!--    style="table-layout:fixed"-->
<!--    <col width="12%">-->
<!--    <col width="25%">-->
<!--    <col width="12%">-->
<!--    <col width="25%">-->
<!--    <col width="25%">-->
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

    echo "Ширина узкой колонки = $narrow_column_width %<br />";
    echo "Ширина широкой колонки = $wide_column_width %<br />";

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

            <th style="width:<?=$wide_column_width?>%" class="cell_with_category_name"><a href="#" class="category_name" data-pk="<?=$category_id ?>" > <?=$category_name ?></a></th>
            <th style="width:<?=$narrow_column_width?>%" id="sort_order"><?=$sort_order?></th>
            <th style="width:<?=$wide_column_width?>%">

<!--                <a href="#" class="category_status" data-pk="--><?//=$category_id ?><!--" data-source="{'1': 'отображается', '0': 'не отображается'}">--><?php //echo  ($status == 1)? "отображается" : "не отображается"; ?><!--</a>-->

              <?php if ($status=1){


                  echo '<button class="btn btn-primary btn-sm" style="background-color: salmon; color: white"><i class="fas fa-eye"></i></button>';

                  echo '<button class="btn btn-primary btn-sm" style="background-color: dodgerblue; color: white"><i class="fas fa-eye-slash"></i></button>';
              }
              else{
                  echo '<i class="fas fa-eye-slash" style="color: red"></i>';
              }
              ?>

            </th>
            <th style="width:<?=$wide_column_width?>%"><a href="/admin/edit_selected_category/<?=$category_id?>">настроить</a> </th>
        </tr>

    <?php endforeach;?>

    </tbody>
</table>

<div id="information" class="editable-input">
    <form>
        <input type="text" class="form-control form-control-sm" style="padding-right: 24px;" value="информация">
    </form>

</div>

<div class="editable_information"></div>




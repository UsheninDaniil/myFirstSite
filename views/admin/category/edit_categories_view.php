<?php
require_once (ROOT. '/models/Category.php');
$categoryList = Category::get_category_list();
?>

<br />
<a href="/admin" class="go-back-to-admin-panel" /><b>Панель администратора</b></a>
→
<a href="/admin/edit_category" class="go-back-to-admin-panel"/><b>Управление категориями</b></a>

<br /><br /><br />

<table border='1' cellpadding='5' class='parameter_list_table' data-category-id='<?=$category_id?>' >

    <tr>
        <th colspan='3'>Список категорий</th>
    </tr>

    <tr>
        <th><b style="color: darkred">Название</b></th>
        <th><b style="color: darkred">Порядок сортировки</b></th>
        <th><b style="color: darkred">Список параметров</b></th>
    </tr>

    <?php foreach ($categoryList as $category_information):
        $category_name = $category_information['name'];
        $category_id = $category_information['id'];
        $sort_order = $category_information['sort_order'];
    ?>

    <tr>
        <th><?php echo "$category_name"?></th>
        <th><?php echo "$sort_order"?></th>
        <th><a href="/admin/edit_selected_category/<?=$category_id?>">настройка параметров</a> </th>
    </tr>

    <?php endforeach;?>

</table>






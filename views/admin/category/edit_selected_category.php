
<div class="container_with_breadcrumb">

    <div class="breadcrumb">
        <a href="/admin" class="go-back-to-admin-panel" /><b>Панель администратора</b></a>
        →
        <a href="/admin/edit_category" class="go-back-to-admin-panel"/><b>Управление категориями</b></a>
        →
        <a href="/admin/edit_selected_category/<?=$category_id?>" class="go-back-to-admin-panel"/><b>Настройка парамтеров категории #<?=$category_id?></b></a>

    </div>



</div>



<?php
require_once('/views/admin/category/category_parameters_table.php');
?>



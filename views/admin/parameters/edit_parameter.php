

<div class="container_with_breadcrumb">

    <div class="breadcrumb">
        <a href="/admin" class="go-back-to-admin-panel" /><b>Панель администратора</b></a>
        →
        <a href="/admin/edit_parameters" class="go-back-to-admin-panel"/><b>Управление параметрами</b></a>
        →
        <a href="/admin/edit_selected_parameter/<?=$parameter_information['id']?>" class="go-back-to-admin-panel"/><b>Изменение параметра #<?=$parameter_information['id']?></b></a>
    </div>





<form  enctype="multipart/form-data" action="" method ="post" id="update_selected_parameter_information">

    <label>параметр:</label><br />
    <input type="text" name="update_parameter_name" value="<?=$parameter_information['name'] ?>"/><br />

    <label>название (на русском):</label><br />
    <input type="text" name="update_parameter_russian_name" value="<?=$parameter_information['russian_name'] ?>"/><br />

    <label>единица измерения:</label><br />
    <input type="text" name="update_parameter_unit" value="<?=$parameter_information['unit'] ?>"/><br />

</form>

<br/>

<a href="javascript:void(0);" class="update_selected_parameter_information" data-parameter-id="<?=$parameter_information['id']?>">Обновить</a>

</div>
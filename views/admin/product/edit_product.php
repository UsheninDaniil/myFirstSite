
<br />
<a href="/admin" class="go-back-to-admin-panel" /><b>Панель администратора</b></a>
→
<a href="/admin/edit_products" class="go-back-to-admin-panel"/><b>Управление товарами</b></a>
→
<a href="/admin/edit_poduct/<?=$product_id?>" class="go-back-to-admin-panel"/><b>Изменение товара #<?=$product_id?></b></a>


<form  enctype="multipart/form-data" name = "add_product" action="" method ="post" class="feedback">

    <br /><b style="color: #dd0029"><?php echo "Основные параметры"?></b><br /><br />

    <label>Название товара:</label><br />
    <input type="text" name="product_name" value="<?=$product_information['name']?>"><br />

    <label>Цена:</label><br />
    <input type="text" name="product_price" value="<?=$product_information['price']?>" ><br />

    <br /><b style="color: #dd0029"><?php echo "Динамические параметры"?></b><br />
    <br /><b><a href="" class="go-back-to-admin-panel"><?php echo "Параметры категории"?></a></b><br /><br />

    <?php
    for ($i =0; $i < count($parameters_name_list); $i++):?>
    <label><?=$parameters_name_list["$i"]?></label><br />
    <input type="text" name="product_price" value="<?=$parameters_value_list["$i"]?>" ><br />
    <?php endfor?>

    <br /><b><a href="" class="go-back-to-admin-panel"><?php echo "Дополнительные параметры"?></a></b></b><br /><br />








    <br />

    <b><a href="javascript:void(0);" class="go-back-to-admin-panel">Добавить параметр</a></b><br /><br />

    <input type = "submit" name ="send" value="Изменить товар "><br />

</form>



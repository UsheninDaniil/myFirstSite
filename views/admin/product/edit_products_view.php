
<br />
<a href="/admin" class="go-back-to-admin-panel" /><b>Панель администратора</b></a>
→
<a href="/admin/edit_products" class="go-back-to-admin-panel"/><b>Управление товарами</b></a>

<br /><br /><br />

<form id="admin_product_multiselect_form">

<select name="category_id[]" multiple="multiple" size="5" class="category_multiselect">

    <?php foreach ($category_list as $category_information): ?>
        <option value="<?=$category_information['id']?>" ><?=$category_information['name']?></option>
    <?php endforeach; ?>

</select>


    <select name="status[]"  size="5" class="product_status">

        <option value="1" >отображается</option>
        <option value="0" >не отображается</option>

    </select>

</form>






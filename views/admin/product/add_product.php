<?php



require_once (ROOT. '/models/Category.php');
$categoryList = Category::get_category_list();

?>

<br />
<a href="/admin" class="go-back-to-admin-panel" /><b>Панель администратора</b></a>
→
<a href="/admin/edit_products" class="go-back-to-admin-panel"/><b>Управление товарами</b></a>
→
<a href="/admin/add_product" class="go-back-to-admin-panel"/><b>Добавление товара</b></a>

<h4>Добавить новый товар</h4>

<form  enctype="multipart/form-data" name = "add_product" action="" method ="post" class="feedback">

    <label>Название товара:</label><br />
    <input type="text" name="product_name" /><br />

    <label>Цена:</label><br />
    <input type="text" name="product_price" /><br />

    <label>Есть в наличии?</label><br />
    <input name="product_availability" type="radio" value="1" checked>Да
    <input name="product_availability" type="radio" value="0">Нет
    <br /><br />

    <label>Категория:</label><br />
    <select name="product_category_id" onchange="load_category_parameters()" id="load_category_parameters_to_add_product">
        <option>Выберите категорию</option>
        <?php if(is_array($categoryList)): ?>
            <?php foreach ($categoryList as $category): ?>
                <option value="<?= $category['id']; ?>">
                    <?= $category['name']; ?>
                </option>
            <?php endforeach;?>
        <?php endif;?>
    </select><br />

    <div class="category_parameters_list" id="category_parameters_list">

    </div>

<!--    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />-->

    <br />Выберите изображение: <br />
    <input name="images[]" type="file" multiple><br /><br />

    <input type="file" id="files" name="files[]"  multiple />
    <ul id="list"></ul>

    <input type="file" id="files_2" name="files[]"  multiple />
    <ul id="list_2"></ul>

    <input type = "submit" name ="save_new_product" value="Добавить товар "><br />

</form>

<script>
    function showFile(e) {
        var files = e.target.files;
        for (var i = 0, f; f = files[i]; i++) {
            if (!f.type.match('image.*')) continue;
            var fr = new FileReader();
            fr.onload = (function(theFile) {
                return function(e) {
                    var li = document.createElement('li');
                    li.innerHTML = "<img src='" + e.target.result + "' />";
                    document.getElementById('list').insertBefore(li, null);
                };
            })(f);

            fr.readAsDataURL(f);
        }
    }
    document.getElementById('files').addEventListener('change', showFile, false);
</script>

<script>
    function showFile(e) {
        var selectedFile = document.getElementById('files_2').files[0].name;
        console.log('selectedFile:\n' + selectedFile);

    }
    document.getElementById('files_2').addEventListener('change', showFile, false);
</script>
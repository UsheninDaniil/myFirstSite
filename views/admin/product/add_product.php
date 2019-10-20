<?php

require_once(ROOT . '/models/Category.php');
$categoryList = Category::get_category_list();

?>

<div class="container_with_breadcrumb">

    <div class="breadcrumb">
        <a href="/admin" class="go-back-to-admin-panel"/><b>Панель администратора</b></a>
        →
        <a href="/admin/edit_products" class="go-back-to-admin-panel"/><b>Управление товарами</b></a>
        →
        <a href="/admin/add_product" class="go-back-to-admin-panel"/><b>Добавление товара</b></a>
    </div>

    <div class="add_new_product_main_container">

        <h4 style="text-align: center">Добавить новый товар</h4>

        <form enctype="multipart/form-data" name="add_product" id="add_product" action="" method="post"
              class="feedback">

            <label>Название товара:</label><br/>
            <input type="text" name="product_name"/><br/>

            <label>Цена:</label><br/>
            <input type="text" name="product_price"/><br/>

            <br/>
            <label>Отображать на сайте?</label><br/>
            <input name="product_availability" type="radio" value="1" checked>Да
            <input name="product_availability" type="radio" value="0">Нет
            <br/>

            <br/>
            <label>Есть возможность выбирать цвет?</label><br/>
            <input name="availability_to_choose_the_color" type="radio" value="1" checked>Да
            <input name="availability_to_choose_the_color" type="radio" value="0">Нет
            <br/><br/>

            <div class="select_available_colors">
                <select id='multiple_colors_list' multiple='multiple'>
                    <?php foreach ($color_list as $color):
                        $color_name = $color['name'];
                        $color_id = $color['id'];
                        ?>
                        <option value='<?= $color_id ?>' <?php if ($color_name === 'black') {
                            echo "selected";
                        } ?>><?= $color_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="margin: 7px 0">Имеется в наличии</div>
            <div class="product_amount">

                <input name="no_color_amount" class="only_one_color_input" style="margin: 5px 0 5px 0; display: none">

                <div class="product_colors" style="margin-top: 5px;">
                    <table class="product_colors_table" border="1" cellpadding="5">
                        <tr>
                            <th style="width: 40%">Цвет</th>
                            <th style="width: 60%">Колличество</th>
                        </tr>

                        <tr class="color_amount" data-color-id="5">
                            <td>black</td>
                            <td><input name="color[5]"></td>
                        </tr>

                    </table>

                    <input class="all_colors_information" type="hidden" disabled value='<?php echo json_encode($color_id_and_name_list)?>'>

                </div>

            </div>


            <br/>

            <label>Категория:</label><br/>
            <select name="product_category_id" id="load_category_parameters_to_add_product">
                <option>Выберите категорию</option>
                <?php if (is_array($categoryList)): ?>
                    <?php foreach ($categoryList as $category): ?>
                        <option value="<?= $category['id']; ?>">
                            <?= $category['name']; ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select><br/>

            <div class="category_parameters_list" id="category_parameters_list"></div>

            <br/>Выберите изображение: <br/>
            <input type="hidden" name="image_names" value="" id="image_names">
            <input type="file" name="images[]" id="input" multiple onchange="handleFiles(this.files)">
        </form>

        <div id="images_container">
            <div id="test">&nbsp</div>
        </div>

        <div style="text-align: center">
            <input type="submit" name="save_new_product" value="Добавить товар" form="add_product"><br/>
        </div>

    </div>

</div>





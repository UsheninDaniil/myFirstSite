
<div class="container_with_breadcrumb">

    <div class="breadcrumb">
        <a href="/admin" class="go-back-to-admin-panel" /><b>Панель администратора</b></a>
        →
        <a href="/admin/reviews_control" class="go-back-to-admin-panel"/><b>Управление отзывами</b></a>
    </div>

    <form method="get" class="review_filter_form" action="/admin/reviews_control?">
        <div style="display: flex; justify-content: space-between; ">

        <div style="padding: 10px; display:flex; flex-direction:column;">
            <label>поиск по id пользователя</label>
            <input type="text" name="user_id" value="<?php if(isset($search_user_id)){echo $search_user_id;}?>">
        </div>

        <div style="padding: 10px; display:flex; flex-direction:column;">
            <label>поиск по id товара</label>
            <input type="text" name="product_id" value="<?php if(isset($search_product_id)){echo $search_product_id;}?>">
        </div>

        <div style="padding: 10px; display:flex; flex-direction:column;">
            <label>поиск по рейтингу</label>
            <input type="text" name="rating" value="<?php if(isset($search_rating)){echo $search_rating;}?>">
        </div>

        <div style="padding: 10px; height: auto; position: relative">
            <input type="submit" value="Применить" style="position: absolute; bottom: 10px;">
        </div>


        </div>




    </form>

<div style="width: 100%">
    <table border="1" class="review_control_table">
        <tr>
            <th rowspan="2">Id</th>
            <th colspan="2">Товар</th>
            <th colspan="2">Пользователь</th>
            <th rowspan="2">Отзыв</th>
            <th rowspan="2">
                Оценка <a href="javascript:void(0);" class="show_review_rating_sorting" style="color: darkred"><i class="fas fa-sort-down"></i></a>
                <div class="review_rating_sorting" hidden>
                    <a href="javascript:void(0);" class="sorting_ascending">по возрастанию</a>
                    <a href="javascript:void(0);" class="sorting_descending">по убыванию</a>
                </div>
            </th>
            <th rowspan="2">Действие</th>
        </tr>

        <tr>
            <td>id</td>
            <td>Название</td>
            <td>id</td>
            <td>Имя</td>
        </tr>

        <?php foreach ($review_list as $review) :

            $product_id = $review['product_id'];
            $product_information = Product::get_product_by_id($product_id);
            $product_name = $product_information['name'];
            $user_id = $review['user_id'];
            $review_text = $review['review'];
            $review_rating = $review['rating'];
            $review_id = $review['id'];
            $user_name = User::get_user_name_by_user_id($user_id);

            ?>

            <tr>
                <td><?= $review_id ?></td>
                <td><?= $product_id ?></td>
                <td><a href="/product/<?= $product_id ?>"><?= $product_name ?></a></td>
                <td><?= $user_id ?></td>
                <td><?= $user_name ?></td>
                <td>
                    <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="<?= $review_text ?>">
                    <pre><p style="width: 150px; margin: 0; overflow: hidden; text-overflow: ellipsis"><?=$review_text?></p></pre>
                    </button>

                </td>
                <td><?= $review_rating ?></td>
                <td><a href="javascript:void(0);" class="delete_product_review" data-review_id="<?= $review_id ?>"><i class="fas fa-trash-alt"></i></a></td>
            </tr>

        <?php endforeach; ?>

    </table>

</div>

    <?php
    echo "<br />";
    echo $pagination->build_pagination();
    ?>

</div>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>


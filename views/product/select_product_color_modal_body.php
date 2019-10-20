<div>
    <span style="display: flex; justify-content: center"><a href=""><?= $product_info['name'] ?></a></span>
</div>

<?php if($ability_to_choose_the_color === 'true'):?>

<div class="view_product_colors" style="display: flex; align-items: center; justify-content: center; margin-top: 10px">
    <div style="display: inline-flex">
        <span style="margin-right: 5px">Цвет:</span>
    </div>

    <?php foreach ($product_colors as $color) {
        $amount = $color['amount'];
        $color_id = $color['color_id'];
        $name = $color['name'];
        $hex_code = $color['hex_code'];
        include('/views/product/color_link_template.php');
        }
     ?>
</div>

<?php endif; ?>

<div class="modal_input_product_amount" style="display: flex; justify-content: center; margin-top: 10px">

    <button disabled class="btn btn-light product_amount_decrement" style="margin: 0 5px"><i class="fas fa-minus"></i></button>
    <input disabled size="5" class="input_product_amount" style="text-align: center" value="1">

    <?php foreach ($product_colors as $color) {
        $amount = $color['amount'];
        $color_id = $color['color_id'];
        $name = $color['name'];
        $hex_code = $color['hex_code'];
        echo "<input type='hidden' data-color-id='$color_id' value='$amount'>";
    }
    ?>

    <button class="btn btn-light product_amount_increment" style="margin: 0 5px"><i class="fas fa-plus"></i></button>
</div>

<div class="select_color_warning" style="display:none; color:darkred; text-align: center; margin-top: 10px;">Вначале выберите цвет товара!</div>

<tr class="color_amount" data-color-id="<?= $color_id ?>" data-color-name="<?=$color_name ?>">
    <td><?php if($color_name === 'no_color'){echo "универсальный";}else{ echo "$color_name";} ?></td>
    <td class="amount_cell" style="display: flex; justify-content: center; align-items: center;">
        <button type="button" class="btn btn-light product_amount_decrement" style="margin: 0 5px"><i class="fas fa-minus"></i></button>
        <input size="5" name="color[<?= $color_id ?>]" class="input_product_amount" style="text-align: center" value="<?= $amount ?>">
        <button type="button" class="btn btn-light product_amount_increment" style="margin: 0 5px"><i class="fas fa-plus"></i></button>
    </td>
    <td>
        <a href="javascript:void(0);" class="delete_selected_product_color">
            <i class="far fa-trash-alt"></i>
        </a>
    </td>
</tr>
<br/>

<div class="side_menu">
    <h2 class="headline">Каталог</h2>

    <div style="border-top: 1px solid lightgrey;">

        <?php foreach ($categoryList as $categoryItem) : ?>

            <div class="category_field" style="">

                <a href="/category/<?= $categoryItem['id']; ?>" class="category_name">
                    <?php echo $categoryItem['name']; ?>
                </a>

            </div>

        <?php endforeach; ?>

    </div>


    <div class="category_filter_block " style="text-align: center; border-top: 1px solid lightgrey;">
        <h4 class="headline">Фильтр</h4>

        <div id="accordion">

            <form method="get" id="category_parameters_filter" action="">
            </form>

            <?php
            $category_parameters_list = Parameters::get_category_parameters_list_for_category_filter($category_id);

            foreach ($category_parameters_list as $parameter):
                $parameter_id = $parameter['id'];
                $parameter_name = Parameters::get_parameter_name_by_parameter_id($parameter_id);
                ?>

                <div class="filter_parameter_item">

                    <b><?= $parameter_name ?></b>

                    <div class="parameter_filter" style="text-align: left; padding-left : 10px; padding-right: 10px">
                        <?php
                        $most_popular_parameter_values_list = Parameters::get_most_popular_parameter_values_by_category_id_and_parameter_id($category_id, $parameter_id);
                        ?>

                        <?php foreach ($most_popular_parameter_values_list as $element):
                            $value = $element['value'];
                            $value_id = Parameters::get_value_id($value, $parameter_id);
                            $count = $element['count'];
                            ?>
                            <input type="checkbox" name='<?php echo $parameter_id ?>[]' value="<?= $value_id ?>" form='category_parameters_filter'

                                <?php
                                if (!empty($_GET)) {
                                    if (isset($_GET["$parameter_id"])) {
                                        $parameter_values_list = $_GET["$parameter_id"];
                                        if (in_array($value, $parameter_values_list))
                                            echo "checked";
                                    }
                                }
                                ?>
                            >
                            <?php echo $value . " (" . $count . ")"; ?><br/>
                        <?php endforeach; ?>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <div style="border-top: 1px solid lightgrey; padding-bottom: 10px; padding-top: 10px">

            <input type='submit' form='category_parameters_filter' value="Применить">

        </div>
    </div>

</div>


<div class="product_block">
    <h2 class="headline">Товары</h2>



    <?php
    $filter_tags = '';

    if(!empty($_GET)) {
        $get_parameters_array = $_GET;
        if(isset($get_parameters_array['page'])){
            unset($get_parameters_array['page']);
        }
        $get_parameters_without_page = $get_parameters_array;

        foreach ($get_parameters_without_page as $parameter_id => $parameter_values_id){

            if(empty($parameter_values_id)) {
                continue;
            }

            $parameter_name = Parameters::get_parameter_name_by_parameter_id($parameter_id);

            if (gettype($parameter_values_id) == "array") {
                foreach ($parameter_values_id as $value_id) {
                    $parameter_value = Parameters::get_value_by_value_id($value_id);
                    $tag_name = $parameter_name.' = '.$parameter_value;
                    if (strlen($filter_tags) == 0) {
                        $filter_tags = $filter_tags . $tag_name;
                    } else {
                        $filter_tags = $filter_tags . ',' . $tag_name;
                    }
                }
            } else {
                $value_id = $parameter_values_id;
                $parameter_value = Parameters::get_value_by_value_id($value_id);
                $tag_name = $parameter_name.' = '.$parameter_value;
                if ((strlen($filter_tags) == 0) && (!empty($tag_name))) {
                    $filter_tags = $filter_tags . $tag_name;
                } else {
                    $filter_tags = $filter_tags . ',' . $tag_name;
                }
            }
        }
    }
    ?>

    <div class="category_filter_tags">
        <input  name="tags" placeholder="текст" value="<?=$filter_tags?>">

        <br/>
        <span class="category_filter_tags_test"></span>
    </div>

    <br/>

    <div class="product_list">
        <?php
            foreach ($productList as $productItem){
                include('/views/product/product_item_template.php');
            }
        ?>
    </div>

</div>


<?php
echo "<br />";
echo $pagination->build_pagination();
?>



<?php
include_once('/views/product/select_product_color_modal.php');
?>


















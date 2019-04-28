<?php


?>

<h2 class="headline">Главная страница</h2>

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


    <div class="category_filter_block "  style="text-align: center; border-top: 1px solid lightgrey;" >
        <h4 class="headline">Фильтр</h4>

        <div id="accordion">

            <form method="get" id="category_parameters_filter"  action="">
            </form>

        <?php
        $category_parameters_list = Parameters::get_category_parameters_list_for_category_filter($category_id);
        foreach ($category_parameters_list as $parameter_id):
        $parameter_name = Parameters::get_parameter_name_by_parameter_id($parameter_id);
        ?>

        <div>

        <h4 style="color: darkred; text-align:left "><b><?=$parameter_name?></b></h4>

        <div class="parameter_filter"  style="text-align: left; padding-left : 10px; padding-right: 10px" >
            <?php
            $most_popular_parameter_values_list = Parameters::get_most_popular_parameter_values_by_category_id_and_parameter_id($category_id, $parameter_id);
            ?>

                    <?php foreach ($most_popular_parameter_values_list as $element):
                        $value = $element['value'];
                        $count = $element['count'];
                    ?>
                    <input type="checkbox" name='<?php echo $parameter_id ?>[]' value="<?=$value?>" form='category_parameters_filter'

                        <?php
                        if(!empty($_GET)){
                            if(isset($_GET["$parameter_id"])){
                                $parameter_values_list = $_GET["$parameter_id"];
                                if (in_array($value, $parameter_values_list))
                                echo "checked";
                            }
                        }
                        ?>
                    >
                        <?php echo $value." (".$count.")";?><br />
                    <?php endforeach;?>

        </div>

        </div>

        <?php endforeach;?>

        </div>

        <div style="border-top: 1px solid lightgrey; padding-bottom: 10px; padding-top: 10px">

            <input type='submit' form='category_parameters_filter' value="Применить">

        </div>
    </div>

</div>


<div class="product_block">
    <h2 class="headline">Товары</h2>

    <div class="product_list">



        <?php foreach ($productList as $productItem) : ?>

            <div class="product_field">

                <div class="product_inside_field">

                    <?php
                    $product_id =$productItem['id'];
                    if (file_exists(ROOT."/images/small_product_images/$product_id.jpg")) {
                        $path = "/images/small_product_images/$product_id.jpg";
                    }
                    else {
                        $path = "/images/small_product_images/no_photo.png";
                    }
                    ?>

                    <div>

                        <a href="/product/<?= $productItem['id']; ?>" >
                            <img src= "<?php echo $path ?>" alt="photo" class="product_photo" />
                        </a>

                    </div>

                    <a href="/product/<?= $productItem['id']; ?>" class="product-name">
                        <?php echo $productItem['name']; ?>
                    </a>
                    <div class="price"> <?php echo $productItem['price'].' грн'; ?></div>

                    <a href="#" data-id="<?php echo $productItem['id']; ?>" class="add-to-cart">
                        В корзину <span class="glyphicon glyphicon-shopping-cart"></span>

                        <span class="check-in-the-cart<?php echo $productItem['id']; ?>">
                    <?php
                    if (isset($_SESSION['cart_product_list'])){
                        $cartData = unserialize($_SESSION['cart_product_list']);
                        if (isset($cartData[$productItem['id']])){
                            echo "<span class=\"glyphicon glyphicon-check\"></span>";
                        }
                    }
                    ?>
                </span>

                    </a>

                    <div>
                        <a href="javascript:void(0);" data-id="<?php echo $productItem['id']; ?>" class="add-to-compare">
                            Сравнить <span class="glyphicon glyphicon-stats"></span>

                            <span class="check-in-the-compare<?=$product_id?>">
                    <?php
                    if (isset($_SESSION['compare_product_list'])){
                        $compareData = unserialize($_SESSION['compare_product_list']);  // тут хранятся айди товаров, добавленных в сравнение

                        foreach ($compareData as $compare_category_products){
                            if(in_array ($product_id, $compare_category_products)){
                                echo "<span class='glyphicon glyphicon-check'></span>";
                            }
                        }
                    }
                    ?>
                </span>
                        </a>
                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

    <?php
    if(isset($united_request)){
        echo "<br /><b style='color: darkred'>Запрос:</b><br /> $united_request <br />";
    }

    if(!empty($_GET)){
        echo "<br /><b style='color: darkred'>Содержимое GET:</b><br />";
        print_r($_GET);
    }
    ?>

</div>

























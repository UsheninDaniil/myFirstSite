<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/template/fontawesome-free-5.8.1-web/css/all.min.css" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/template/bootstrap-4.3.1-dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="/test_something/test.css?v=<?php echo uniqid()?>">
    <script src="/test_something/test.js?v=<?php echo uniqid()?>" defer></script>
    <script src="/template/jquery-3.3.1.min.js"></script>

</head>



<body>

<?php
$product_id = 1;
$pattern = "small_images/id_{$product_id}_photo_*.jpg";

//        echo "шаблон по которому ищу картинки = $pattern <br /><br />";

$photo_amount = 0;

$max_photo_width = 0;
$max_photo_height = 0;

$first_photo_id = 1;

    foreach (glob($pattern) as $image){
        $photo_amount = $photo_amount +1;
//        echo "image = $image <br/>";
        list($width, $height, $type, $attr) = getimagesize($image);
        if($width>$max_photo_width){
            $max_photo_width = $width;
        }
        if($height>$max_photo_height){
            $max_photo_height = $height;
        }
//        echo "<br />"; print_r($image); echo "<br/>";
    }

$border_width = 3;
$element_padding = 7;
$margin = 5;
$padding_bottom = $margin;
$element_height = $max_photo_height + $margin + $border_width*2 + $element_padding*2;
$photo_on_page = 5;

$slider_height = $photo_on_page * $element_height + $padding_bottom;
$slider_polosa_height = $element_height * $photo_amount + $padding_bottom;

echo "<br/>";
echo "max_photo_width = $max_photo_width <br />";
echo "max_photo_height = $max_photo_height<br />";

?>

<div id="product_photos">

<div id="slider_with_buttons">

    <button id="previous" class="btn btn-light"><i class="fas fa-angle-up"></i></button>

<div id="slider" style="height: <?=$slider_height?>px"
     data-photo-amount="<?=$photo_amount?>"
     data-photo-on-page="<?=$photo_on_page?>"
     data-element-height="<?=$element_height?>"
     data-current-top-value="0">

    <div id="polosa" style="padding-bottom: <?=$padding_bottom?>px;">

            <?php foreach (glob($pattern) as $image):?>

        <div class="element" onclick="ShowSelectedPhoto(this)" style="
                width: <?=$max_photo_width?>px;
                margin-top: <?=$margin?>px;
                height: <?=$max_photo_height?>px;
                border: <?=$border_width?>px solid transparent;
                padding: <?=$element_padding?>px;
                ">
            <img src="/test_something/<?=$image?>" alt=""  data-border-width = "<?=$border_width;?>">
        </div>

            <?php endforeach;?>

    </div>
</div>

    <button id="next" class="btn btn-light"><i class="fas fa-angle-down"></i></button>

</div>


<div id="selected_photo_container">

    <img id="selected_photo" src="/test_something/middle_images/id_<?=$product_id?>_photo_<?=$first_photo_id?>.jpg">

</div>

</div>





</body>
</html>